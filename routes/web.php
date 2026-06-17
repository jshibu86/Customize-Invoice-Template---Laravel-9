<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\InvoiceTemplateController;

Route::get('/invoice/templates', [InvoiceTemplateController::class, 'templates'])->name('invoice.templates');
Route::get('/invoice/builder', fn () => redirect()->route('invoice.templates'));
Route::get('/invoice/builder/{template}', [InvoiceTemplateController::class, 'builder'])->name('invoice.builder');
Route::post('/invoice/save/{template}', [InvoiceTemplateController::class, 'save'])->name('invoice.save');
Route::get('/invoice/logo/{template}', [InvoiceTemplateController::class, 'logo'])->name('invoice.logo');

Route::get('/invoice/preview/{template?}', [InvoiceTemplateController::class, 'preview'])->name('invoice.preview');
Route::get('/invoice/export-html/{template}', [InvoiceTemplateController::class, 'exportHtml'])->name('invoice.export-html');
Route::get('/invoice/preview-html/{template?}', [InvoiceTemplateController::class, 'previewHtml'])->name('invoice.preview-html');
