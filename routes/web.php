<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Profile;
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
    
    // Route de profil utilisateur
    Route::get('/profile', Profile::class)->name('profile');

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
        
        // Gestion des utilisateurs
        Route::get('/users', function () {
            return view('pages.admin.users');
        })->name('admin.users');
    });
});