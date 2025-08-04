<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property string $transaction_number
 * @property string $type
 * @property int|null $member_id
 * @property int $user_id
 * @property float $total_amount
 * @property float $discount_amount
 * @property float $tax_amount
 * @property float $final_amount
 * @property string $payment_method
 * @property string $status
 * @property string|null $notes
 * @property int $points_used
 * @property int $points_earned
 * @property \Illuminate\Support\Carbon|null $completed_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Member|null $member
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TransactionItem> $items
 * @property-read \App\Models\Installment|null $installment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntry> $journalEntries
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction completed()

 * 
 * @mixin \Eloquent
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_number',
        'type',
        'member_id',
        'user_id',
        'total_amount',
        'discount_amount',
        'tax_amount',
        'final_amount',
        'payment_method',
        'status',
        'notes',
        'points_used',
        'points_earned',
        'completed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'points_used' => 'integer',
        'points_earned' => 'integer',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the member that owns the transaction.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Get the user that processed the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the transaction items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(TransactionItem::class);
    }

    /**
     * Get the installment for the transaction.
     */
    public function installment(): HasOne
    {
        return $this->hasOne(Installment::class);
    }

    /**
     * Get the journal entries for the transaction.
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Scope a query to only include completed transactions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Generate next transaction number.
     */
    public static function generateTransactionNumber(string $type): string
    {
        $prefix = match ($type) {
            'sale' => 'SAL',
            'purchase' => 'PUR',
            'contribution' => 'CON',
            'receivable' => 'REC',
            'expense' => 'EXP',
            'shu_distribution' => 'SHU',
            default => 'TRX',
        };

        $lastTransaction = static::where('type', $type)
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = $lastTransaction ? 
            (int) substr($lastTransaction->transaction_number, -6) + 1 : 1;

        return $prefix . date('Ymd') . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}