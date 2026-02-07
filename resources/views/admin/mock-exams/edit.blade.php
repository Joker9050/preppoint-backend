@extends('admin.layout')

@section('title', 'Edit Mock Exam: ' . $mockExam->name)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="bg-white bg-opacity-20 p-3 rounded-lg">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl font-bold text-white">Edit Mock Exam</h1>
                            <p class="text-indigo-100 mt-1">{{ $mockExam->name }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.mock-exams.show', $mockExam) }}"
                           class="inline-flex items-center px-4 py-2 border border-white border-opacity-30 text-sm font-medium rounded-lg text-white bg-white bg-opacity-10 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition-all duration-200">
                            <i class="fas fa-eye mr-2"></i>
                            View Exam
                        </a>
                        <a href="{{ route('admin.mock-exams.index') }}"
                           class="inline-flex items-center px-4 py-2 border border-white border-opacity-30 text-sm font-medium rounded-lg text-white bg-white bg-opacity-10 hover:bg-opacity-20 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50 transition-all duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <form action="{{ route('admin.mock-exams.update', $mockExam) }}" method="POST" class="divide-y divide-gray-200">
                @csrf
                @method('PUT')

                <!-- Basic Information Section -->
                <div class="px-6 py-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-indigo-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-info-circle text-indigo-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>
                                Full Name <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $mockExam->name) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('name') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="Enter full exam name" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="short_name" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-code mr-2 text-gray-400"></i>
                                Short Name <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="short_name" id="short_name" value="{{ old('short_name', $mockExam->short_name) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('short_name') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="Enter short name" required>
                            @error('short_name')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Configuration Section -->
                <div class="px-6 py-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-green-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-cogs text-green-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Configuration</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="category" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-folder mr-2 text-gray-400"></i>
                                Category <span class="text-red-500 ml-1">*</span>
                            </label>
                            <input type="text" name="category" id="category" value="{{ old('category', $mockExam->category) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('category') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="e.g., SSC, Banking, Railway" required>
                            @error('category')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="mode" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-globe mr-2 text-gray-400"></i>
                                Mode <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="mode" id="mode"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('mode') border-red-500 ring-2 ring-red-200 @enderror" required>
                                <option value="online" {{ old('mode', $mockExam->mode) == 'online' ? 'selected' : '' }}>🖥️ Online</option>
                                <option value="offline" {{ old('mode', $mockExam->mode) == 'offline' ? 'selected' : '' }}>📄 Offline</option>
                            </select>
                            @error('mode')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div class="space-y-2">
                            <label for="status" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-toggle-on mr-2 text-gray-400"></i>
                                Status <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="status" id="status"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('status') border-red-500 ring-2 ring-red-200 @enderror" required>
                                <option value="1" {{ old('status', $mockExam->status) ? 'selected' : '' }}>✅ Active</option>
                                <option value="0" {{ old('status', $mockExam->status) ? '' : 'selected' }}>⏸️ Inactive</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="ui_template" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-palette mr-2 text-gray-400"></i>
                                UI Template
                            </label>
                            <select name="ui_template" id="ui_template"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('ui_template') border-red-500 ring-2 ring-red-200 @enderror">
                                <option value="">🎨 Default</option>
                                <option value="eduquity" {{ old('ui_template', $mockExam->ui_template) == 'eduquity' ? 'selected' : '' }}>📚 Eduquity</option>
                                <option value="tcs" {{ old('ui_template', $mockExam->ui_template) == 'tcs' ? 'selected' : '' }}>🏢 TCS</option>
                            </select>
                            @error('ui_template')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Advanced Settings Section -->
                <div class="px-6 py-6">
                    <div class="flex items-center mb-6">
                        <div class="bg-purple-100 p-2 rounded-lg mr-3">
                            <i class="fas fa-sliders-h text-purple-600"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Advanced Settings</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="slug" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-link mr-2 text-gray-400"></i>
                                Slug
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug', $mockExam->slug) }}"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('slug') border-red-500 ring-2 ring-red-200 @enderror"
                                   placeholder="url-friendly-identifier">
                            <p class="mt-2 text-sm text-gray-500 flex items-center">
                                <i class="fas fa-info-circle mr-1"></i>
                                Leave blank for auto-generation from name
                            </p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-align-left mr-2 text-gray-400"></i>
                                Description
                            </label>
                            <textarea name="description" id="description" rows="4"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors duration-200 @error('description') border-red-500 ring-2 ring-red-200 @enderror"
                                      placeholder="Detailed description of the exam...">{{ old('description', $mockExam->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="px-6 py-6 bg-gray-50">
                    <div class="flex flex-col sm:flex-row sm:justify-end sm:space-x-3 space-y-3 sm:space-y-0">
                        <a href="{{ route('admin.mock-exams.show', $mockExam) }}"
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Update Exam
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Custom animations and enhancements */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-section {
    animation: fadeInUp 0.5s ease-out;
}

/* Enhanced focus states */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

/* Better hover effects */
button:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Responsive improvements */
@media (max-width: 640px) {
    .max-w-4xl {
        margin-left: 1rem;
        margin-right: 1rem;
    }
}
</style>
@endsection
