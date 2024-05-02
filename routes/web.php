<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\SignatoryController;
use App\Http\Controllers\ParticipantController;
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

Route::get('/dashboard',[TrainingController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //Signatory
    Route::get('/signatory', [SignatoryController::class, 'index'])->name('signatory');
    Route::post('/add-signatory', [SignatoryController::class, 'store'])->name('signatory.store');

    //Trainings
    Route::post('/add-training' ,[TrainingController::class, 'store'])->name('training.store');

    //Participants
    Route::prefix('participants')->group(function () {
        Route::get('/{trainingId}', [ParticipantController::class, 'index'])->name('participant.index');
        Route::post('/store',[ParticipantController::class,'store'])->name('participant.store');
    });
    
});

require __DIR__.'/auth.php';
