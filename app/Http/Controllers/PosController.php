<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PosController extends Controller
{
    /**
     * Display the POS interface.
     */
    public function index()
    {
        $products = Product::active()->get();
        $members = Member::active()->get(['id', 'member_id', 'name', 'email']);

        return Inertia::render('pos/index', [
            'products' => $products,
            'members' => $members,
        ]);
    }

    /**
     * Process a sale transaction.
     */
    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'member_id' => 'nullable|exists:members,id',
            'payment_method' => 'required|in:cash,transfer,credit,installment',
            'discount_amount' => 'numeric|min:0',
            'points_used' => 'integer|min:0',
            'installment_count' => 'required_if:payment_method,installment|integer|min:2|max:24',
            'down_payment' => 'required_if:payment_method,installment|numeric|min:0',
        ]);

        return DB::transaction(function () use ($request) {
            // Calculate totals
            $totalAmount = 0;
            $totalPoints = 0;

            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                // Check stock availability
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                $itemTotal = $item['quantity'] * $item['unit_price'];
                $totalAmount += $itemTotal;
                $totalPoints += $product->points_earned * $item['quantity'];
            }

            $discountAmount = $request->discount_amount ?? 0;
            $pointsUsed = $request->points_used ?? 0;
            $finalAmount = $totalAmount - $discountAmount;

            // Apply points discount (1 point = 1 rupiah)
            if ($pointsUsed > 0) {
                $finalAmount = max(0, $finalAmount - $pointsUsed);
            }

            // Create transaction
            $transaction = Transaction::create([
                'transaction_number' => Transaction::generateTransactionNumber('sale'),
                'type' => 'sale',
                'member_id' => $request->member_id,
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'discount_amount' => $discountAmount,
                'tax_amount' => 0,
                'final_amount' => $finalAmount,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'installment' ? 'completed' : 'completed',
                'points_used' => $pointsUsed,
                'points_earned' => $totalPoints,
                'completed_at' => now(),
            ]);

            // Create transaction items and update stock
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                    'discount_amount' => 0,
                ]);

                // Update product stock
                $product->decrement('stock_quantity', $item['quantity']);
            }

            // Handle member points
            if ($request->member_id) {
                $member = Member::find($request->member_id);
                
                // Deduct used points
                if ($pointsUsed > 0) {
                    $member->decrement('points', $pointsUsed);
                }
                
                // Add earned points
                if ($totalPoints > 0) {
                    $member->increment('points', $totalPoints);
                }
            }

            // Handle installment
            if ($request->payment_method === 'installment') {
                $this->createInstallment($transaction, $request);
            }

            return redirect()->route('pos.receipt', $transaction)
                ->with('success', 'Transaction completed successfully.');
        });
    }

    /**
     * Show transaction receipt.
     */
    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'member', 'installment.payments']);

        return Inertia::render('pos/receipt', [
            'transaction' => $transaction,
        ]);
    }

    /**
     * Create installment for transaction.
     */
    protected function createInstallment(Transaction $transaction, Request $request)
    {
        $totalAmount = $transaction->final_amount;
        $downPayment = $request->down_payment ?? 0;
        $remainingAmount = $totalAmount - $downPayment;
        $installmentCount = $request->installment_count;
        $installmentAmount = $remainingAmount / $installmentCount;

        $installment = Installment::create([
            'transaction_id' => $transaction->id,
            'member_id' => $transaction->member_id,
            'total_amount' => $totalAmount,
            'down_payment' => $downPayment,
            'remaining_amount' => $remainingAmount,
            'installment_count' => $installmentCount,
            'paid_installments' => 0,
            'installment_amount' => $installmentAmount,
            'start_date' => now()->addMonth(),
            'end_date' => now()->addMonths($installmentCount),
            'status' => 'active',
        ]);

        // Create installment payment schedules
        for ($i = 1; $i <= $installmentCount; $i++) {
            InstallmentPayment::create([
                'installment_id' => $installment->id,
                'installment_number' => $i,
                'amount' => $installmentAmount,
                'penalty_amount' => 0,
                'due_date' => now()->addMonths($i),
                'status' => 'pending',
            ]);
        }
    }
}