<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollPeriod extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'period_start',
        'period_end',
        'payout_date',
        'status',
        'total_gross',
        'total_deductions',
        'total_taxes',
        'total_net',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'period_start' => 'date',
            'period_end' => 'date',
            'payout_date' => 'date',
            'approved_at' => 'datetime',
            'total_gross' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_taxes' => 'decimal:2',
            'total_net' => 'decimal:2',
        ];
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->whereYear('period_start', $year);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
