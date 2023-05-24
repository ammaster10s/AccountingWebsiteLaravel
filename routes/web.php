<?php

use App\Http\Controllers\AboutController; // App พิมพ์ใหญ่
use App\Http\Controllers\MemberController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/test', 'AboutController@index'); Version เก่า

Route::get('/about', [AboutController::class,'index'])->name('about');
Route::get('/Member', [MemberController::class,'index']);
Route::get('/Admin', [AdminController::class,'index']);
Route::get('/login', [LoginController::class,'index', 'login'])->name('login');
Route::get('/keycloak-login', [KeycloakController::class, 'login'])->name('keycloak.login');
Route::get('/keycloak-callback', [KeycloakController::class, 'callback'])->name('keycloak.callback');
Route::get('/keycloak-logout', [KeycloakController::class, 'logout'])->name('keycloak.logout');
