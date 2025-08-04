<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Member
 *
 * @property int $id
 * @property string $member_id
 * @property string $name
 * @property string $email
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $gender
 * @property string|null $id_card_number
 * @property \Illuminate\Support\Carbon $join_date
 * @property string $status
 * @property float $share_capital
 * @property float $mandatory_savings
 * @property float $voluntary_savings
 * @property int $points
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Installment> $installments
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Member query()
 * @method static \Illuminate\Database\Eloquent\Builder|Member active()

 * 
 * @mixin \Eloquent
 */
class Member extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'member_id',
        'name',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'id_card_number',
        'join_date',
        'status',
        'share_capital',
        'mandatory_savings',
        'voluntary_savings',
        'points',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'birth_date' => 'date',
        'join_date' => 'date',
        'share_capital' => 'decimal:2',
        'mandatory_savings' => 'decimal:2',
        'voluntary_savings' => 'decimal:2',
        'points' => 'integer',
    ];

    /**
     * Get the transactions for the member.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the installments for the member.
     */
    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    /**
     * Scope a query to only include active members.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get total savings amount.
     */
    public function getTotalSavingsAttribute(): float
    {
        return $this->mandatory_savings + $this->voluntary_savings;
    }

    /**
     * Generate next member ID.
     */
    public static function generateMemberId(): string
    {
        $lastMember = static::orderBy('id', 'desc')->first();
        $nextNumber = $lastMember ? (int) substr($lastMember->member_id, 3) + 1 : 1;
        return 'IBS' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}