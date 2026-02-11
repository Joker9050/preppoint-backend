@extends('admin.layout')

@section('title', 'Create Subject')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Subject</h1>
            <p class="text-gray-600">Add a new MCQ subject</p>
        </div>
        <a href="{{ route('admin.subjects.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <form action="{{ route('admin.subjects.store') }}" method="POST" class="p-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Subcategory -->
                <div class="space-y-2">
                    <label for="subcategory_id" class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-tags mr-2 text-indigo-500"></i>Subcategory
                    </label>
                    <div class="relative">
                        <select id="subcategory_id" name="subcategory_id" class="appearance-none block w-full pl-4 pr-10 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out bg-white shadow-sm @error('subcategory_id') border-red-300 ring-1 ring-red-300 @enderror">
                            <option value="">Select a subcategory</option>
                            @foreach($subcategories as $subcategory)
                            <option value="{{ $subcategory->id }}" {{ old('subcategory_id') == $subcategory->id ? 'selected' : '' }}>
                                {{ $subcategory->name }}
                            </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                    @error('subcategory_id')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-book mr-2 text-indigo-500"></i>Subject Name
                    </label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="block w-full pl-4 pr-12 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out shadow-sm @error('name') border-red-300 ring-1 ring-red-300 @enderror" placeholder="Enter subject name">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-edit text-gray-400"></i>
                        </div>
                    </div>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Priority -->
                <div class="space-y-2">
                    <label for="priority" class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-sort-numeric-up mr-2 text-indigo-500"></i>Priority
                    </label>
                    <div class="relative">
                        <input type="number" id="priority" name="priority" value="{{ old('priority', 10) }}" min="0" class="block w-full pl-4 pr-12 py-3 text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 ease-in-out shadow-sm @error('priority') border-red-300 ring-1 ring-red-300 @enderror" placeholder="Enter priority (lower = higher priority)">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i class="fas fa-hashtag text-gray-400"></i>
                        </div>
                    </div>
                    @error('priority')
                        <p class="mt-2 text-sm text-red-600 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.subjects.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-2"></i>Create Subject
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
