<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\FinancialAccount
 *
 * @property int $id
 * @property string $account_code
 * @property string $account_name
 * @property string $account_type
 * @property string $category
 * @property float $balance
 * @property bool $is_active
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JournalEntryLine> $journalEntryLines
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder|FinancialAccount active()
 * 
 * @mixin \Eloquent
 */
class FinancialAccount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'account_code',
        'account_name',
        'account_type',
        'category',
        'balance',
        'is_active',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the journal entry lines for the account.
     */
    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class);
    }

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}