@extends('admin.layout')

@section('title', 'Create Section - {{ $mockExamPaper->title }}')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Section</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $mockExamPaper->title }} • {{ $mockExamPaper->exam->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.sections', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Sections
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Section Form -->
    <form method="POST" action="{{ route('admin.mock-exam-papers.sections.store', $mockExamPaper) }}" class="bg-white shadow rounded-lg">
        @csrf
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Section Details</h3>
        </div>
        <div class="px-6 py-4 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Section Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('name') border-red-500 @enderror" placeholder="e.g., General Intelligence">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sequence_no" class="block text-sm font-medium text-gray-700 mb-2">Sequence Number <span class="text-red-500">*</span></label>
                    <input type="number" id="sequence_no" name="sequence_no" value="{{ old('sequence_no') }}" required min="1" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('sequence_no') border-red-500 @enderror" placeholder="1">
                    @error('sequence_no')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="total_questions" class="block text-sm font-medium text-gray-700 mb-2">Total Questions <span class="text-red-500">*</span></label>
                    <input type="number" id="total_questions" name="total_questions" value="{{ old('total_questions') }}" required min="1" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('total_questions') border-red-500 @enderror" placeholder="25">
                    @error('total_questions')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="section_marks" class="block text-sm font-medium text-gray-700 mb-2">Section Marks <span class="text-red-500">*</span></label>
                    <input type="number" id="section_marks" name="section_marks" value="{{ old('section_marks') }}" required min="1" step="0.5" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('section_marks') border-red-500 @enderror" placeholder="50">
                    @error('section_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="section_time_minutes" class="block text-sm font-medium text-gray-700 mb-2">Time (minutes)</label>
                    <input type="number" id="section_time_minutes" name="section_time_minutes" value="{{ old('section_time_minutes') }}" min="1" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('section_time_minutes') border-red-500 @enderror" placeholder="30">
                    @error('section_time_minutes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="positive_marks" class="block text-sm font-medium text-gray-700 mb-2">Positive Marks <span class="text-red-500">*</span></label>
                    <input type="number" id="positive_marks" name="positive_marks" value="{{ old('positive_marks') }}" required min="0" step="0.5" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('positive_marks') border-red-500 @enderror" placeholder="2">
                    @error('positive_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="negative_marks" class="block text-sm font-medium text-gray-700 mb-2">Negative Marks <span class="text-red-500">*</span></label>
                    <input type="number" id="negative_marks" name="negative_marks" value="{{ old('negative_marks') }}" required min="0" step="0.5" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('negative_marks') border-red-500 @enderror" placeholder="0.5">
                    @error('negative_marks')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="instructions" class="block text-sm font-medium text-gray-700 mb-2">Instructions</label>
                <textarea id="instructions" name="instructions" rows="4" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('instructions') border-red-500 @enderror" placeholder="Optional instructions for this section...">{{ old('instructions') }}</textarea>
                @error('instructions')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.mock-exam-papers.sections', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>
                Create Section
            </button>
        </div>
    </form>
</div>
@endsection
