<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollSnapshot extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period',
        'report_date',
        'periode_bulan',
        'periode_tahun',
        'total_karyawan',
        'total_salary',
        'total_income',
        'total_overtime',
        'total_thr',
        'total_deduction',
        'total_tax',
        'total_net_salary',
    ];

    protected $casts = [
        'report_date' => 'date',

        'periode_bulan' => 'integer',
        'periode_tahun' => 'integer',
        'total_karyawan' => 'integer',

        'total_salary' => 'decimal:2',
        'total_income' => 'decimal:2',
        'total_overtime' => 'decimal:2',
        'total_thr' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'total_tax' => 'decimal:2',
        'total_net_salary' => 'decimal:2',
    ];

    public function details()
    {
        return $this->hasMany(
            PayrollDetail::class,
            'snapshot_id'
        );
    }
}