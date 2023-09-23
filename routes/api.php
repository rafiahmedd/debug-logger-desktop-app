<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Logger;

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

Route::get('/open', [Logger::class, 'addLogToApp']);
Route::get('/fetch-logs', [Logger::class, 'fetchAllLogs']);
Route::get('/fetch-logs/{id}', [Logger::class, 'fetchLog'])->whereNumber('id');
Route::delete('/delete-log/{id}', [Logger::class, 'deleteLog'])->whereNumber('id');
route::post('/clear-log', [Logger::class, 'clearLogFile']);

Route::get('/monitor-file', [Logger::class, 'monitorFile']);
