@extends('admin.layout')

@section('title', 'View Subject')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">View Subject</h1>
            <p class="text-gray-600">Subject details</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.subjects.edit', $subject) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-edit mr-2"></i>Edit Subject
            </a>
            <a href="{{ route('admin.subjects.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Subjects
            </a>
        </div>
    </div>

    <!-- Subject Details -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Subject Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->name }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Subcategory</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->subcategory->name ?? 'N/A' }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Priority</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->priority }}</dd>
                </div>
                <div class="sm:col-span-1">
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $subject->created_at ? $subject->created_at->format('M j, Y g:i A') : 'N/A' }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Topics Section -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Topics</h3>
            @if($subject->topics->count() > 0)
                <ul class="divide-y divide-gray-200">
                    @foreach($subject->topics as $topic)
                        <li class="py-4">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $topic->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $topic->description ?? 'No description' }}</p>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-sm text-gray-500">No topics found for this subject.</p>
            @endif
        </div>
    </div>
</div>
@endsection
