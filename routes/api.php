<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PriorityController;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/employees', [EmployeeController::class, 'index'])->name('api.employees.index');
Route::post('/employees', [EmployeeController::class, 'store'])->name('api.employees.store');
Route::get('/employees/{id}', [EmployeeController::class, 'show'])->name('api.employees.show');
Route::put('/employees/{id}', [EmployeeController::class, 'update'])->name('api.employees.update');
Route::delete('/employees/{id}', [EmployeeController::class, 'destroy'])->name('api.employees.destroy');



Route::apiResource('categories', CategoryController::class);
Route::apiResource('priorities', PriorityController::class);
Route::apiResource('tasks', TaskController::class);
