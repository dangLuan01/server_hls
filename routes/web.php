<?php

use App\Http\Livewire\Video\AllVideo;
use App\Http\Livewire\Video\CreateVideo;
use App\Http\Livewire\Video\EditVideo;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('main');
});
Route::get('/videos/create',CreateVideo::class)->name('video.create');
Route::get('/videos/{video}/edit',EditVideo::class)->name('video.edit');
Route::get('/videos/all',AllVideo::class)->name('video.all');
