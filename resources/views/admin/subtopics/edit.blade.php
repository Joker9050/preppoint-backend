@extends('admin.layout')

@section('title', 'Edit Subtopic')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Subtopic</h1>
            <p class="text-gray-600">Update subtopic information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.topics.show', $topic) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-eye mr-2"></i>View Topic
            </a>
            <a href="{{ route('admin.topics.show', $topic) }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Topic
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <form action="{{ route('admin.topics.subtopics.update', [$topic, $subtopic]) }}" method="POST" class="p-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="flex items-center text-sm font-semibold text-gray-700">
                        <i class="fas fa-tag mr-2 text-indigo-500"></i>Subtopic Name
                    </label>
                    <div class="relative">
                        <input type="text" id="name" name="name" value="{{ old('name', $subtopic->name) }}" class="block w-full pl-3 pr-8 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-black focus:border-black transition duration-200 ease-in-out shadow-sm @error('name') border-red-300 ring-1 ring-red-300 @enderror" placeholder="Enter subtopic name">
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
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.topics.show', $topic) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-2"></i>Update Subtopic
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
