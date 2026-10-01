<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayrollImportController;
use App\Http\Controllers\PayrollValidationController;
use App\Http\Controllers\PayrollAnalysisController;
use App\Http\Controllers\PayrollChangeValidationController;
use App\Http\Controllers\PayrollDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get(
    '/payroll/import',
    [PayrollImportController::class, 'index']
)->name('payroll.import');

Route::post(
    '/payroll/import',
    [PayrollImportController::class, 'import']
)->name('payroll.import.process');

Route::get(
    '/payroll/validation',
    [PayrollValidationController::class, 'index']
)->name('payroll.validation');

Route::get(
    '/payroll/analysis',
    [PayrollAnalysisController::class, 'index']
)->name('payroll.analysis');

/*
|--------------------------------------------------------------------------
| EXPORT ANALYSIS
|--------------------------------------------------------------------------
*/

Route::get(
    '/payroll/analysis/export/excel',
    [PayrollAnalysisController::class, 'exportExcel']
)->name('payroll.analysis.export.excel');

Route::get(
    '/payroll/analysis/export/pdf',
    [PayrollAnalysisController::class, 'exportPdf']
)->name('payroll.analysis.export.pdf');

Route::get(
    '/payroll/change-validation',
    [PayrollChangeValidationController::class, 'index']
)->name('payroll.change-validation');

Route::get(
    '/payroll/dashboard',
    [PayrollDashboardController::class, 'index']
)->name('payroll.dashboard');

Route::get(
    '/payroll/change-validation/export/excel',
    [PayrollChangeValidationController::class, 'exportExcel']
)->name('payroll.change-validation.export.excel');

Route::get(
    '/payroll/change-validation/export/pdf',
    [PayrollChangeValidationController::class, 'exportPdf']
)->name('payroll.change-validation.export.pdf');