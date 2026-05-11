<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'basic_salary',
        'gross_salary',
        'total_allowances',
        'total_deductions',
        'total_taxes',
        'net_salary',
        'bank_name',
        'bank_account_number',
        'ifsc_code',
        'status',
        'breakdown',
        'remarks',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'gross_salary' => 'decimal:2',
            'total_allowances' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_taxes' => 'decimal:2',
            'net_salary' => 'decimal:2',
            'breakdown' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
