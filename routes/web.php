<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Timeline;
use App\Livewire\Comments;
use App\Livewire\MilestoneManager;

Route::get('/', function () {
    return view('timeline');
});
Route::get('/comments', Comments::class);
Route::get('/admin/milestones', MilestoneManager::class);