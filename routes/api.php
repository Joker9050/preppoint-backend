<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FeedbackController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes for frontend
Route::prefix('v1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}/subcategories', [CategoryController::class, 'subcategories']);
    Route::get('/subcategories/{subcategory}/subjects', [CategoryController::class, 'subjects']);
    Route::get('/learning_scroll_subjects', [CategoryController::class, 'learningScrollSubjects']);
});

// Contact submission route
Route::post('/contact', [ContactController::class, 'store']);

// Feedback submission route
Route::post('/feedback', [FeedbackController::class, 'store']);

// Static pages route
Route::get('/pages/{slug}', [StaticPageController::class, 'show']);

// Direct subjects endpoint for welcome page
Route::get('/subjects', [CategoryController::class, 'allSubjects']);
