<?php

use App\Http\Controllers\AboutController; // App พิมพ์ใหญ่
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth::routes();

// Route::get('/', [AdminController::class, 'login'])->name('home');
// Route::get('login', [AdminController::class, 'login'])->name('login');
// Route::get('logout', [AdminController::class, 'logout'])->name('logout');
// Route::post('checkLogin', [AdminController::class, 'checkLogin'])->name('checkLogin');

// Logs
// Route::get('logs', [\Rap2hpoutre\Controllers\LogViewerController::class, 'index']);

// Route::group(['middleware' => 'auth.custom'], function () {

//     // Invoice
//     Route::any('invoice', [InvoiceController::class, 'index'])->name('invoice');
//     Route::post('genInvoice', [InvoiceController::class, 'genInvoice'])->name('genInvoice');

//     // History
//     Route::any('history', [HistoryController::class, 'index'])->name('history');
//     Route::post('history/update', [HistoryController::class, 'updateHistory'])->name('updateHistory');
// });
// Route::get('agent/query/{id}', [InvoiceController::class, 'queryAgent'])->name('queryAgent');

Route::get('/admin', [HomeController::class, 'index'])->name('home');

Route::group(['middleware'], function () {
    // Invoice
    Route::any('invoice', [InvoiceController::class, 'index'])->name('invoice');
    Route::post('genInvoice', [InvoiceController::class, 'genInvoice'])->name('genInvoice');

    // History
    Route::any('history', [HistoryController::class, 'index'])->name('history');
    Route::post('history/update', [HistoryController::class, 'updateHistory'])->name('updateHistory');
});




Route::get('agent/query/{id}', [InvoiceController::class, 'queryAgent'])->name('queryAgent');
Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);


