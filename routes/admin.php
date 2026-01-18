<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\McqController;
use App\Http\Controllers\Admin\MockTestController;
use App\Http\Controllers\Admin\JobUpdateController;
use App\Http\Controllers\Admin\ScrapedDraftController;
use App\Http\Controllers\Admin\SettingsController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [LoginController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Protected Admin Routes
Route::middleware(['admin.auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    // Profile Management
    Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::patch('/profile', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    Route::patch('/password/change', [AdminController::class, 'changePassword'])->name('admin.password.change');
    Route::post('/logout/all', [AdminController::class, 'logoutAllSessions'])->name('admin.logout.all');
    Route::get('/login-history', [AdminController::class, 'getLoginHistory'])->name('admin.login.history');

    // MCQ Management Routes
    Route::resource('subjects', SubjectController::class, ['as' => 'admin']);
    Route::resource('topics', TopicController::class, ['as' => 'admin']);
    Route::resource('mcqs', McqController::class, ['as' => 'admin', 'except' => ['show']]);
    Route::get('mcqs/{mcq}/preview', [McqController::class, 'preview'])->name('admin.mcqs.preview');
    Route::get('mcqs/get-subtopics/{topic}', [McqController::class, 'getSubtopics'])->name('admin.mcqs.get-subtopics');

    // Mock Test Management Routes
    Route::resource('mock-tests', MockTestController::class, ['as' => 'admin']);
    Route::patch('mock-tests/{mock_test}/toggle-status', [MockTestController::class, 'toggleStatus'])->name('admin.mock-tests.toggle-status');
    Route::get('mock-tests/get-topics/{subject}', [MockTestController::class, 'getTopics'])->name('admin.mock-tests.get-topics');
    Route::post('mock-tests/get-mcqs', [MockTestController::class, 'getMcqs'])->name('admin.mock-tests.get-mcqs');

    // Job Updates Management Routes
    Route::resource('job-updates', JobUpdateController::class, ['as' => 'admin']);
    Route::patch('job-updates/{job_update}/toggle-status', [JobUpdateController::class, 'toggleStatus'])->name('admin.job-updates.toggle-status');

    // Scraped Drafts
    Route::resource('scraped-drafts', ScrapedDraftController::class, ['as' => 'admin']);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::get('/settings/{group}/edit', [SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::patch('/settings/{group}', [SettingsController::class, 'update'])->name('admin.settings.update');
});
