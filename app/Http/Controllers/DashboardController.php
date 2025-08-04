<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Get dashboard statistics
        $stats = [
            'total_members' => Member::active()->count(),
            'total_products' => Product::active()->count(),
            'total_sales_today' => Transaction::where('type', 'sale')
                ->where('status', 'completed')
                ->whereDate('created_at', today())
                ->sum('final_amount'),
            'pending_installments' => Transaction::where('payment_method', 'installment')
                ->where('status', 'completed')
                ->whereHas('installment', function ($query) {
                    $query->where('status', 'active');
                })
                ->count(),
        ];

        // Get recent transactions
        $recentTransactions = Transaction::with(['member', 'user'])
            ->latest()
            ->take(5)
            ->get();

        // Get low stock products
        $lowStockProducts = Product::active()
            ->lowStock()
            ->take(5)
            ->get();

        // Get recent members
        $recentMembers = Member::active()
            ->latest()
            ->take(5)
            ->get();

        return Inertia::render('dashboard', [
            'stats' => $stats,
            'recentTransactions' => $recentTransactions,
            'lowStockProducts' => $lowStockProducts,
            'recentMembers' => $recentMembers,
            'userRole' => $user->role,
        ]);
    }
}