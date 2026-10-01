<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'snapshot_id',

        // Identitas karyawan
        'employee_no',
        'name',
        'nric',
        'gov_tax_file_no',
        'position',
        'department',
        'cost_center',
        'grade',
        'employment_status',
        'join_date',
        'terminate_date',
        'length_of_service',
        'tax_status',

        // Gaji pokok
        'salary',

        // Allowance / tunjangan
        'allowance_bpjstk_jkm',
        'allowance_bpjstk_jkk',
        'allowance_bpjstk_jht',
        'tunjangan_jabatan',
        'overtime',
        'allowance',
        'tunjangan_prestasi',
        'tunjangan_operational',
        'reward',
        'thr',
        'tak',
        'rapel_gaji',
        'allowance_bpjsks_company',
        'allowance_bpjstk_jp_company',
        'tunjangan_keahlian',

        // Total income
        'total_income',

        // Pajak
        'tax_borne_by_company',

        // Potongan
        'deduction',
        'deduction_bpjsstk_jht_employee',
        'deduction_bpjstk_jp_employee',
        'deduction_bpjsks_company',
        'potongan_lain_lain',
        'deduction_bpjstk_jkk',
        'deduction_bpjstk_jkm',
        'deduction_bpjstk_jht',
        'deduction_bpjsks_employee',
        'deduction_bpjstk_jp_company',
        'total_deduction',

        // Hasil akhir
        'tax',
        'net_salary',

        // Data tambahan
        'dasar_bpjstk',
        'netral_bpjstk_jp',
        'dasar_bpjsks',
        'total_ot_1',
        'total_ot_2',
        'total_ot_3',
        'total_ot_4',
        'total_overtime',
        'nilai_ot_mak',
    ];

    protected $casts = [
        'snapshot_id' => 'integer',

        'join_date' => 'date',
        'terminate_date' => 'date',

        'salary' => 'decimal:2',
        'allowance_bpjstk_jkm' => 'decimal:2',
        'allowance_bpjstk_jkk' => 'decimal:2',
        'allowance_bpjstk_jht' => 'decimal:2',
        'tunjangan_jabatan' => 'decimal:2',
        'overtime' => 'decimal:2',
        'allowance' => 'decimal:2',
        'tunjangan_prestasi' => 'decimal:2',
        'tunjangan_operational' => 'decimal:2',
        'reward' => 'decimal:2',
        'thr' => 'decimal:2',
        'tak' => 'decimal:2',
        'rapel_gaji' => 'decimal:2',
        'allowance_bpjsks_company' => 'decimal:2',
        'allowance_bpjstk_jp_company' => 'decimal:2',
        'tunjangan_keahlian' => 'decimal:2',

        'total_income' => 'decimal:2',

        'tax_borne_by_company' => 'decimal:2',

        'deduction' => 'decimal:2',
        'deduction_bpjsstk_jht_employee' => 'decimal:2',
        'deduction_bpjstk_jp_employee' => 'decimal:2',
        'deduction_bpjsks_company' => 'decimal:2',
        'potongan_lain_lain' => 'decimal:2',
        'deduction_bpjstk_jkk' => 'decimal:2',
        'deduction_bpjstk_jkm' => 'decimal:2',
        'deduction_bpjstk_jht' => 'decimal:2',
        'deduction_bpjsks_employee' => 'decimal:2',
        'deduction_bpjstk_jp_company' => 'decimal:2',
        'total_deduction' => 'decimal:2',

        'tax' => 'decimal:2',
        'net_salary' => 'decimal:2',

        'dasar_bpjstk' => 'decimal:2',
        'netral_bpjstk_jp' => 'decimal:2',
        'dasar_bpjsks' => 'decimal:2',
        'total_ot_1' => 'decimal:2',
        'total_ot_2' => 'decimal:2',
        'total_ot_3' => 'decimal:2',
        'total_ot_4' => 'decimal:2',
        'total_overtime' => 'decimal:2',
        'nilai_ot_mak' => 'decimal:2',
    ];

    public function snapshot()
    {
        return $this->belongsTo(
            PayrollSnapshot::class,
            'snapshot_id'
        );
    }
}