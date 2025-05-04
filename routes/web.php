<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Routes principales
Route::get('/', function () {
    return redirect()->route('timeline'); 
});

// Page Timeline
Route::get('/timeline', function () {
    return view('pages.timeline');
})->name('timeline');

// Page Commentaires
Route::get('/comments', function () {
    return view('pages.comments');
})->name('comments');

// Routes d'authentification
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Routes de profil utilisateur
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes d'administration
    Route::prefix('admin')->middleware('auth')->group(function () {
        // Gestion des jalons
        Route::get('/milestones', function () {
            return view('pages.admin.milestones');
        })->name('admin.milestones');
        
        // Gestion des outils
        Route::get('/tools', function () {
            return view('pages.admin.tools');
        })->name('admin.tools');
        
        // Gestion des concepts
        Route::get('/concepts', function () {
            return view('pages.admin.concepts');
        })->name('admin.concepts');
        
        // Gestion des cours
        Route::get('/courses', function () {
            return view('pages.admin.courses');
        })->name('admin.courses');
        
        // Gestion des utilisateurs (désormais entièrement gérée par Livewire)
        Route::get('/users', function () {
            return view('pages.admin.users');
        })->name('admin.users');
    });
});