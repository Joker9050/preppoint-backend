<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ContactController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\McqController;
use App\Http\Controllers\Api\HierarchyController;

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

// Public API routes for frontend
Route::prefix('v1')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}/subcategories', [CategoryController::class, 'subcategories']);
    Route::get('/subcategories/{subcategory}/subjects', [CategoryController::class, 'subjects']);
    Route::get('/learning_scroll_subjects', [CategoryController::class, 'learningScrollSubjects']);

    // Hierarchy API - separate from MCQs
    Route::get('/hierarchy', [HierarchyController::class, 'index']);

    // Optimized MCQ API - direct queries only
    Route::get('/mcqs', [McqController::class, 'index']);
});

// Admin API routes (protected)
Route::prefix('admin')->middleware(['auth:sanctum', 'admin'])->group(function () {
    // Admin routes here
});

// Contact and feedback routes
Route::post('/contact', [ContactController::class, 'store']);
Route::post('/feedback', [FeedbackController::class, 'store']);
