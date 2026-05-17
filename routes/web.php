<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\admin\SiteViewStatisticsController;
use App\Http\Controllers\admin\ContentController;
use App\Http\Controllers\admin\SpinnerController;
use App\Http\Controllers\admin\QuizController;
use App\Models\spinner;
use App\Models\quiz;
use App\Models\SiteView;

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
    $stats = [
        // Spinner
        'spinner_total'   => spinner::count(),
        'spinner_winners' => spinner::where('score', '>=', 50)->count(),
        'spinner_today'   => spinner::whereDate('created_at', today())->count(),

        // Quiz
        'quiz_total'      => quiz::count(),
        'quiz_winners'    => quiz::where('score', '>=', 50)->count(),
        'quiz_today'      => quiz::whereDate('created_at', today())->count(),

        // Site Views
        'views_total'     => SiteView::count(),
        'views_today'     => SiteView::whereDate('created_at', today())->count(),

        // Chart: last 7 days spinner + quiz activity
        'chart_labels'    => collect(range(6, 0))->map(fn($d) => now()->subDays($d)->format('M d'))->values(),
        'chart_spinner'   => collect(range(6, 0))->map(fn($d) => spinner::whereDate('created_at', now()->subDays($d)->toDateString())->count())->values(),
        'chart_quiz'      => collect(range(6, 0))->map(fn($d) => quiz::whereDate('created_at', now()->subDays($d)->toDateString())->count())->values(),
    ];
    return view('dashboard', $stats);
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

    // ── Spinner admin routes ───────────────────────────────────────────────────
    Route::get('/spinner', [SpinnerController::class, 'adminIndex'])->name('spinner.index');
    Route::get('/spinner/export', [SpinnerController::class, 'exportCsv'])->name('spinner.export');

    // ── Quiz admin routes ─────────────────────────────────────────────────────
    Route::get('/quiz', [QuizController::class, 'adminIndex'])->name('quiz.index');
    Route::get('/quiz/export', [QuizController::class, 'exportCsv'])->name('quiz.export');
});

require __DIR__.'/auth.php';
