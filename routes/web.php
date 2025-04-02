<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CustomerController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Department routes
    Route::resource('departments', DepartmentController::class);
    
    // Department agent management routes
    Route::post('/departments/{department}/agents', [DepartmentController::class, 'assignAgent'])
        ->name('departments.assign-agent');
    Route::delete('/departments/{department}/agents/{user}', [DepartmentController::class, 'removeAgent'])
        ->name('departments.remove-agent');
    Route::get('/departments/{department}/available-agents', [DepartmentController::class, 'getAvailableAgents'])
        ->name('departments.available-agents');
        
    // Agent management routes
    Route::get('/agents', [AgentController::class, 'index'])->name('agents.index');
    Route::post('/agents/invite', [AgentController::class, 'store'])->name('agents.store');
    Route::get('/agents/{agent}', [AgentController::class, 'show'])->name('agents.show');
    Route::get('/agents/{agent}/edit', [AgentController::class, 'edit'])->name('agents.edit');
    Route::put('/agents/{agent}', [AgentController::class, 'update'])->name('agents.update');
    Route::delete('/agents/{agent}', [AgentController::class, 'destroy'])->name('agents.destroy');
    
    // Customer management routes
    Route::resource('customers', CustomerController::class);
});

require __DIR__.'/auth.php';
