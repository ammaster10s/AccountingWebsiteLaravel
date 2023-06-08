<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ConfigController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('invoice/list', [InvoiceController::class, 'getInvoice']);

Route::get('getVoucherNo', [InvoiceController::class, 'getVoucherNo']);



Route::post('createInvoice', [InvoiceController::class, 'createInvoice']);

// History
Route::get('history/list', [HistoryController::class, 'getHistory']);

// Config
Route::get('agent/list', [ConfigController::class, 'getAgent']);


