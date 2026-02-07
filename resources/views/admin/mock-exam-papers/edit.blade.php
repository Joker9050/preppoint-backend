@extends('admin.layout')

@section('title', 'Edit Mock Exam Paper')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Mock Exam Paper</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $mockExamPaper->title }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.show', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-eye mr-2"></i>
                        View Paper
                    </a>
                    <a href="{{ route('admin.mock-exam-papers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white shadow rounded-lg">
        <form action="{{ route('admin.mock-exam-papers.update', $mockExamPaper) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Paper Information</h3>
            </div>

            <div class="px-6 py-4 space-y-6">
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam <span class="text-red-500">*</span></label>
                        <select name="exam_id" id="exam_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('exam_id') border-red-500 @enderror" required>
                            <option value="">Select Exam</option>
                            @foreach($exams as $exam)
                                <option value="{{ $exam->id }}" {{ old('exam_id', $mockExamPaper->exam_id) == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->name }} ({{ $exam->short_name }})
                                </option>
                            @endforeach
                        </select>
                        @error('exam_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="paper_type" class="block text-sm font-medium text-gray-700">Paper Type <span class="text-red-500">*</span></label>
                        <select name="paper_type" id="paper_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('paper_type') border-red-500 @enderror" required>
                            <option value="mock" {{ old('paper_type', $mockExamPaper->paper_type) == 'mock' ? 'selected' : '' }}>Mock Test</option>
                            <option value="pyq" {{ old('paper_type', $mockExamPaper->paper_type) == 'pyq' ? 'selected' : '' }}>Previous Year Question</option>
                        </select>
                        @error('paper_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Title and Year -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" id="title" value="{{ old('title', $mockExamPaper->title) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('title') border-red-500 @enderror"
                               placeholder="e.g., SSC CGL 2024 Tier-1 Mock Test 1" required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700">Year (for PYQ)</label>
                        <input type="number" name="year" id="year" value="{{ old('year', $mockExamPaper->year) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('year') border-red-500 @enderror"
                               min="2000" max="{{ date('Y') + 1 }}" placeholder="2024">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Shift -->
                <div>
                    <label for="shift" class="block text-sm font-medium text-gray-700">Shift (optional)</label>
                    <input type="text" name="shift" id="shift" value="{{ old('shift', $mockExamPaper->shift) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('shift') border-red-500 @enderror"
                           placeholder="Morning/Evening">
                    @error('shift')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Paper Settings -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="total_questions" class="block text-sm font-medium text-gray-700">Total Questions <span class="text-red-500">*</span></label>
                        <input type="number" name="total_questions" id="total_questions" value="{{ old('total_questions', $mockExamPaper->total_questions) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('total_questions') border-red-500 @enderror"
                               min="1" required>
                        @error('total_questions')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="total_marks" class="block text-sm font-medium text-gray-700">Total Marks <span class="text-red-500">*</span></label>
                        <input type="number" name="total_marks" id="total_marks" value="{{ old('total_marks', $mockExamPaper->total_marks) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('total_marks') border-red-500 @enderror"
                               min="1" required>
                        @error('total_marks')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_minutes" class="block text-sm font-medium text-gray-700">Duration (minutes) <span class="text-red-500">*</span></label>
                        <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $mockExamPaper->duration_minutes) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('duration_minutes') border-red-500 @enderror"
                               min="1" max="300" required>
                        @error('duration_minutes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Difficulty and Status -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="difficulty_level" class="block text-sm font-medium text-gray-700">Difficulty Level <span class="text-red-500">*</span></label>
                        <select name="difficulty_level" id="difficulty_level" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('difficulty_level') border-red-500 @enderror" required>
                            <option value="easy" {{ old('difficulty_level', $mockExamPaper->difficulty_level) == 'easy' ? 'selected' : '' }}>Easy</option>
                            <option value="moderate" {{ old('difficulty_level', $mockExamPaper->difficulty_level) == 'moderate' ? 'selected' : '' }}>Moderate</option>
                            <option value="hard" {{ old('difficulty_level', $mockExamPaper->difficulty_level) == 'hard' ? 'selected' : '' }}>Hard</option>
                        </select>
                        @error('difficulty_level')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('status') border-red-500 @enderror" required>
                            <option value="draft" {{ old('status', $mockExamPaper->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="live" {{ old('status', $mockExamPaper->status) == 'live' ? 'selected' : '' }}>Live</option>
                            <option value="archived" {{ old('status', $mockExamPaper->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="ui_template" class="block text-sm font-medium text-gray-700">UI Template</label>
                        <select name="ui_template" id="ui_template" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('ui_template') border-red-500 @enderror">
                            <option value="">Default</option>
                            <option value="eduquity" {{ old('ui_template', $mockExamPaper->ui_template) == 'eduquity' ? 'selected' : '' }}>Eduquity</option>
                            <option value="tcs" {{ old('ui_template', $mockExamPaper->ui_template) == 'tcs' ? 'selected' : '' }}>TCS</option>
                        </select>
                        @error('ui_template')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Instructions -->
                <div>
                    <label for="instructions" class="block text-sm font-medium text-gray-700">Instructions</label>
                    <textarea name="instructions" id="instructions" rows="4"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 @error('instructions') border-red-500 @enderror"
                              placeholder="Enter paper instructions...">{{ old('instructions', $mockExamPaper->instructions) }}</textarea>
                    @error('instructions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('admin.mock-exam-papers.show', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-eye mr-2"></i>
                    View Paper
                </a>
                <a href="{{ route('admin.mock-exam-papers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-save mr-2"></i>
                    Update Paper
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
