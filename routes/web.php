<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MilestoneController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

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

// Routes d'administration
Route::prefix('admin')->group(function () {
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
});