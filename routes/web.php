<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\VideoController;
use App\Http\Livewire\Video\AllVideo;
use App\Http\Livewire\Video\CreateVideo;
use App\Http\Livewire\Video\EditVideo;
use App\Http\Livewire\Video\ShowVideo;
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

Route::middleware('auth')->group(function () {
    Route::get('/', [VideoController::class,'index'])->name('home');
    Route::get('/videos/create',CreateVideo::class)->name('video.create');
    Route::get('/videos/{video}/edit',EditVideo::class)->name('video.edit');
    Route::get('/videos/all',AllVideo::class)->name('video.all');
    Route::get('/videos/gdrive',ShowVideo::class)->name('video.gdrive');
});
Route::get('logout', [LoginController::class, 'logout']);
Route::get('proxy',[ShowVideo::class,'proxy'])->name('video.proxy');
Route::get('stream',[ShowVideo::class,'stream'])->name('video.stream');


Auth::routes();
