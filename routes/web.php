<?php


namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;



Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::patch('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::get('/piority', [TaskController::class, 'piority'])->name('tasks.piority');
Route::get('/jadwal', [TaskController::class, 'jadwal'])->name('tasks.jadwal');
Route::put('/tasks/{task}', [TaskController::class, 'editTask'])->name('tasks.edit');
Route::resource('tasks', TaskController::class);

