<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payroll_details', function (Blueprint $table) {
            $table->id();

            // Relasi ke snapshot payroll
            $table->foreignId('snapshot_id')
                ->constrained('payroll_snapshots')
                ->cascadeOnDelete();

            // Identitas karyawan
            $table->string('employee_no');
            $table->string('name')->nullable();
            $table->string('nric')->nullable();
            $table->string('gov_tax_file_no')->nullable();

            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('cost_center')->nullable();
            $table->string('grade')->nullable();
            $table->string('employment_status')->nullable();

            $table->date('join_date')->nullable();
            $table->date('terminate_date')->nullable();

            $table->string('length_of_service')->nullable();
            $table->string('tax_status')->nullable();

            // Gaji pokok
            $table->decimal('salary', 18, 2)->default(0);

            // Allowance / tunjangan
            $table->decimal('allowance_bpjstk_jkm', 18, 2)->default(0);
            $table->decimal('allowance_bpjstk_jkk', 18, 2)->default(0);
            $table->decimal('allowance_bpjstk_jht', 18, 2)->default(0);

            $table->decimal('tunjangan_jabatan', 18, 2)->default(0);
            $table->decimal('overtime', 18, 2)->default(0);
            $table->decimal('allowance', 18, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 18, 2)->default(0);
            $table->decimal('tunjangan_operational', 18, 2)->default(0);
            $table->decimal('reward', 18, 2)->default(0);
            $table->decimal('thr', 18, 2)->default(0);
            $table->decimal('tak', 18, 2)->default(0);
            $table->decimal('rapel_gaji', 18, 2)->default(0);

            $table->decimal('allowance_bpjsks_company', 18, 2)->default(0);
            $table->decimal('allowance_bpjstk_jp_company', 18, 2)->default(0);
            $table->decimal('tunjangan_keahlian', 18, 2)->default(0);

            // Total income
            $table->decimal('total_income', 18, 2)->default(0);

            // Pajak
            $table->decimal('tax_borne_by_company', 18, 2)->default(0);

            // Potongan
            $table->decimal('deduction', 18, 2)->default(0);

            $table->decimal('deduction_bpjsstk_jht_employee', 18, 2)->default(0);
            $table->decimal('deduction_bpjstk_jp_employee', 18, 2)->default(0);
            $table->decimal('deduction_bpjsks_company', 18, 2)->default(0);
            $table->decimal('potongan_lain_lain', 18, 2)->default(0);
            $table->decimal('deduction_bpjstk_jkk', 18, 2)->default(0);
            $table->decimal('deduction_bpjstk_jkm', 18, 2)->default(0);
            $table->decimal('deduction_bpjstk_jht', 18, 2)->default(0);
            $table->decimal('deduction_bpjsks_employee', 18, 2)->default(0);
            $table->decimal('deduction_bpjstk_jp_company', 18, 2)->default(0);

            $table->decimal('total_deduction', 18, 2)->default(0);

            // Hasil akhir payroll
            $table->decimal('tax', 18, 2)->default(0);
            $table->decimal('net_salary', 18, 2)->default(0);

            // Data tambahan payroll
            $table->decimal('dasar_bpjstk', 18, 2)->default(0);
            $table->decimal('netral_bpjstk_jp', 18, 2)->default(0);
            $table->decimal('dasar_bpjsks', 18, 2)->default(0);

            $table->decimal('total_ot_1', 18, 2)->default(0);
            $table->decimal('total_ot_2', 18, 2)->default(0);
            $table->decimal('total_ot_3', 18, 2)->default(0);
            $table->decimal('total_ot_4', 18, 2)->default(0);

            $table->decimal('total_overtime', 18, 2)->default(0);
            $table->decimal('nilai_ot_mak', 18, 2)->default(0);

            $table->timestamps();

            // Index untuk mempercepat validasi antarbulan
            $table->index('employee_no');
            $table->index('department');
            $table->index('snapshot_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_details');
    }
};