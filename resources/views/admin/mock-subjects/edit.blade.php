@extends('admin.layout')

@section('title', 'Edit Mock Subject - {{ $mockSubject->name }}')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <a href="{{ route('admin.mock-subjects.index') }}" class="text-gray-500 hover:text-indigo-600">Mock Subjects</a>
</li>
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">Edit</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Subject</h1>
                    <p class="mt-1 text-sm text-gray-600">Update subject details</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-subjects.show', $mockSubject) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-eye mr-2"></i>
                        View Subject
                    </a>
                    <a href="{{ route('admin.mock-subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Subjects
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Subject Form -->
    <form method="POST" action="{{ route('admin.mock-subjects.update', $mockSubject) }}" class="bg-white shadow rounded-lg">
        @csrf
        @method('PUT')
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Subject Details</h3>
        </div>
        <div class="px-6 py-4 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Subject Name <span class="text-red-500">*</span></label>
                    <input type="text" id="name" name="name" value="{{ old('name', $mockSubject->name) }}" required class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('name') border-red-500 @enderror" placeholder="e.g., Mathematics">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select id="status" name="status" required class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('status') border-red-500 @enderror">
                        <option value="active" {{ old('status', $mockSubject->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $mockSubject->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea id="description" name="description" rows="4" class="block w-full px-3 py-2 border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200 @error('description') border-red-500 @enderror" placeholder="Optional description for this subject...">{{ old('description', $mockSubject->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
            <a href="{{ route('admin.mock-subjects.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>
                Update Subject
            </button>
        </div>
    </form>
</div>
@endsection
