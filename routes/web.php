<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\SiteViewStatisticsController;
use App\Http\Controllers\admin\ContentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', function () {
    // redirect to dashboard
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // ============================ site view route ============
    // site view statistics route
    Route::get('/dashboard/site-view-statistics', [SiteViewStatisticsController::class, 'statistics'])->name('site-view-statistics');
    Route::get('/site-view', [SiteViewStatisticsController::class, 'index'])->name('site-view.index');


    //route for content management
    Route::resource('contents', ContentController::class)->names('contents');
});

require __DIR__.'/auth.php';
