<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', [PageController::class , 'login_show'])->name('login_show');


Route::get('/admin/index/{id?}', [PageController::class , 'admin_index'])->name('admin_index');

Route::post('/login', [PageController::class, 'login'])->name('login');
Route::get('/reg_show', [PageController::class, 'reg_show'])->name('reg_show');
Route::post('/reg', [PageController::class, 'reg'])->name('reg');

Route::get('/logout', [PageController::class, 'logout'])->name('logout');


Route::get('/my_appls', [PageController::class, 'my_appls'])->name('my_appls');
Route::get('/send_appl', [PageController::class, 'send_appl'])->name('send_appl');
Route::post('/send_appl_db', [PageController::class, 'send_appl_db'])->name('send_appl_db');

Route::get('/all_appls', [PageController::class, 'all_appls'])->name('all_appls');

Route::post('/change_status', [PageController::class, 'change_status'])->name('change_status');



 