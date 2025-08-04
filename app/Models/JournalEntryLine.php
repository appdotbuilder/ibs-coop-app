<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\JournalEntryLine
 *
 * @property int $id
 * @property int $journal_entry_id
 * @property int $financial_account_id
 * @property string $description
 * @property float $debit_amount
 * @property float $credit_amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\JournalEntry $journalEntry
 * @property-read \App\Models\FinancialAccount $financialAccount
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryLine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryLine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|JournalEntryLine query()
 * 
 * @mixin \Eloquent
 */
class JournalEntryLine extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'journal_entry_id',
        'financial_account_id',
        'description',
        'debit_amount',
        'credit_amount',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'debit_amount' => 'decimal:2',
        'credit_amount' => 'decimal:2',
    ];

    /**
     * Get the journal entry that owns the line.
     */
    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class);
    }

    /**
     * Get the financial account for the line.
     */
    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class);
    }
}