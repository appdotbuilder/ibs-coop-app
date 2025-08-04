<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\InstallmentPayment
 *
 * @property int $id
 * @property int $installment_id
 * @property int $installment_number
 * @property float $amount
 * @property float $penalty_amount
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $paid_at
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Installment $installment
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|InstallmentPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstallmentPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstallmentPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstallmentPayment pending()
 * 
 * @mixin \Eloquent
 */
class InstallmentPayment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'installment_id',
        'installment_number',
        'amount',
        'penalty_amount',
        'due_date',
        'paid_at',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installment_number' => 'integer',
        'amount' => 'decimal:2',
        'penalty_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the installment that owns the payment.
     */
    public function installment(): BelongsTo
    {
        return $this->belongsTo(Installment::class);
    }

    /**
     * Scope a query to only include pending payments.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}