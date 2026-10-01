<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payroll_snapshots', function (Blueprint $table) {
            $table->id();

            // Informasi dari Monthly Report
            $table->string('payroll_period')->nullable();
            $table->date('report_date')->nullable();

            // Periode yang digunakan untuk analisis
            $table->unsignedTinyInteger('periode_bulan');
            $table->unsignedSmallInteger('periode_tahun');

            // Ringkasan payroll
            $table->unsignedInteger('total_karyawan')->default(0);

            $table->decimal('total_salary', 18, 2)->default(0);
            $table->decimal('total_income', 18, 2)->default(0);
            $table->decimal('total_overtime', 18, 2)->default(0);
            $table->decimal('total_thr', 18, 2)->default(0);
            $table->decimal('total_deduction', 18, 2)->default(0);
            $table->decimal('total_tax', 18, 2)->default(0);
            $table->decimal('total_net_salary', 18, 2)->default(0);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_snapshots');
    }
};