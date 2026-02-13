<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TopicController;
use App\Http\Controllers\Admin\McqController;
use App\Http\Controllers\Admin\MockExamController;
use App\Http\Controllers\Admin\MockExamPaperController;
use App\Http\Controllers\Admin\MockSubjectController;
use App\Http\Controllers\Admin\MockTopicController;
use App\Http\Controllers\Admin\MockSubtopicController;
use App\Http\Controllers\Admin\JobUpdateController;
use App\Http\Controllers\Admin\ScrapedDraftController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\CategoryManagementController;
use App\Http\Controllers\Admin\SubtopicController;
use App\Http\Controllers\Admin\UserManagementController;

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

    // Category Management Routes
    Route::resource('categories', CategoryController::class, ['as' => 'admin']);
    Route::resource('subcategories', SubcategoryController::class, ['as' => 'admin']);
    Route::get('category-management', [CategoryManagementController::class, 'index'])->name('admin.category-management.index');
    Route::post('category-management/categories', [CategoryManagementController::class, 'storeCategory'])->name('admin.category-management.categories.store');
    Route::patch('category-management/categories/{category}', [CategoryManagementController::class, 'updateCategory'])->name('admin.category-management.categories.update');
    Route::delete('category-management/categories/{category}', [CategoryManagementController::class, 'destroyCategory'])->name('admin.category-management.categories.destroy');
    Route::post('category-management/subcategories', [CategoryManagementController::class, 'storeSubcategory'])->name('admin.category-management.subcategories.store');
    Route::patch('category-management/subcategories/{subcategory}', [CategoryManagementController::class, 'updateSubcategory'])->name('admin.category-management.subcategories.update');
    Route::delete('category-management/subcategories/{subcategory}', [CategoryManagementController::class, 'destroySubcategory'])->name('admin.category-management.subcategories.destroy');

    // MCQ Management Routes
    Route::resource('subjects', SubjectController::class, ['as' => 'admin']);
    Route::resource('topics', TopicController::class, ['as' => 'admin']);
    Route::resource('subtopics', SubtopicController::class, ['as' => 'admin']);
    Route::resource('topics.subtopics', SubtopicController::class, ['as' => 'admin']);
    Route::resource('mcqs', McqController::class, ['as' => 'admin', 'except' => ['show']]);
    Route::get('mcqs/{mcq}/preview', [McqController::class, 'preview'])->name('admin.mcqs.preview');
    Route::get('mcqs/get-subtopics/{topic}', [McqController::class, 'getSubtopics'])->name('admin.mcqs.get-subtopics');



    // Mock Content Management Routes
    Route::resource('mock-subjects', MockSubjectController::class, ['as' => 'admin']);
    Route::patch('mock-subjects/{mock_subject}/toggle-status', [MockSubjectController::class, 'toggleStatus'])->name('admin.mock-subjects.toggle-status');

    Route::resource('mock-topics', MockTopicController::class, ['as' => 'admin']);
    Route::patch('mock-topics/{mock_topic}/toggle-status', [MockTopicController::class, 'toggleStatus'])->name('admin.mock-topics.toggle-status');

    Route::resource('mock-subtopics', MockSubtopicController::class, ['as' => 'admin']);

    // Mock Exam Management Routes
    Route::resource('mock-exams', MockExamController::class, ['as' => 'admin']);
    Route::patch('mock-exams/{mock_exam}/toggle-status', [MockExamController::class, 'toggleStatus'])->name('admin.mock-exams.toggle-status');

    // Mock Exam Paper Management Routes
    Route::resource('mock-exam-papers', MockExamPaperController::class, ['as' => 'admin']);
    Route::patch('mock-exam-papers/{mock_exam_paper}/toggle-status', [MockExamPaperController::class, 'toggleStatus'])->name('admin.mock-exam-papers.toggle-status');
    Route::get('mock-exam-papers/{mock_exam_paper}/sections', [MockExamPaperController::class, 'sections'])->name('admin.mock-exam-papers.sections');
    Route::get('mock-exam-papers/{mock_exam_paper}/sections/create', [MockExamPaperController::class, 'createSection'])->name('admin.mock-exam-papers.sections.create');
    Route::post('mock-exam-papers/{mock_exam_paper}/sections', [MockExamPaperController::class, 'storeSection'])->name('admin.mock-exam-papers.sections.store');
    Route::get('mock-exam-papers/{mock_exam_paper}/sections/{section}/edit', [MockExamPaperController::class, 'editSection'])->name('admin.mock-exam-papers.sections.edit');
    Route::patch('mock-exam-papers/{mock_exam_paper}/sections/{section}', [MockExamPaperController::class, 'updateSection'])->name('admin.mock-exam-papers.sections.update');
    Route::delete('mock-exam-papers/{mock_exam_paper}/sections/{section}', [MockExamPaperController::class, 'destroySection'])->name('admin.mock-exam-papers.sections.destroy');
    Route::get('mock-exam-papers/{mock_exam_paper}/sections/{section}/questions', [MockExamPaperController::class, 'sectionQuestions'])->name('admin.mock-exam-papers.sections.questions.index');
    Route::get('mock-exam-papers/{mock_exam_paper}/sections/{section}/questions/add', [MockExamPaperController::class, 'addQuestionsToSection'])->name('admin.mock-exam-papers.sections.questions.add');
    Route::post('mock-exam-papers/{mock_exam_paper}/sections/{section}/questions', [MockExamPaperController::class, 'storeQuestionsToSection'])->name('admin.mock-exam-papers.sections.questions.store');
    Route::post('mock-exam-papers/{mock_exam_paper}/sections/{section}/questions/create', [MockExamPaperController::class, 'createQuestion'])->name('admin.mock-exam-papers.sections.questions.create');
    Route::delete('mock-exam-papers/{mock_exam_paper}/sections/{section}/questions/{question}', [MockExamPaperController::class, 'removeQuestionFromSection'])->name('admin.mock-exam-papers.sections.questions.destroy');
    Route::get('mock-exam-papers/{mock_exam_paper}/preview', [MockExamPaperController::class, 'preview'])->name('admin.mock-exam-papers.preview');
    Route::get('question-bank', [MockExamPaperController::class, 'questionBank'])->name('admin.question-bank');
    Route::get('get-topics/{subjectId}', [MockExamPaperController::class, 'getTopics'])->name('admin.get-topics');
    Route::get('get-subtopics/{topicId}', [MockExamPaperController::class, 'getSubtopics'])->name('admin.get-subtopics');

    // Question Management Routes
    Route::get('/questions/create', [MockExamPaperController::class, 'createQuestionPage'])->name('admin.questions.create');
    Route::post('/questions/store', [MockExamPaperController::class, 'storeQuestion'])->name('admin.questions.store');

    // User Management Routes
    Route::get('/users', [UserManagementController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{id}', [UserManagementController::class, 'show'])->name('admin.users.show');
    Route::patch('/users/{id}/block', [UserManagementController::class, 'block'])->name('admin.users.block');
    Route::patch('/users/{id}/unblock', [UserManagementController::class, 'unblock'])->name('admin.users.unblock');
    Route::delete('/users/{id}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy');

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
