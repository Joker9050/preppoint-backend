@extends('admin.layout')

@section('title', 'View Mock Subtopic - {{ $mockSubtopic->name }}')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <a href="{{ route('admin.mock-subtopics.index') }}" class="text-gray-500 hover:text-indigo-600">Mock Subtopics</a>
</li>
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">{{ $mockSubtopic->name }}</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $mockSubtopic->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">Subtopic details and questions</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-subtopics.edit', $mockSubtopic) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Subtopic
                    </a>
                    <a href="{{ route('admin.mock-subtopics.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Subtopics
                    </a>
                </div>
            </div>
        </div>

        <!-- Subtopic Details -->
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Topic</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $mockSubtopic->topic->name ?? 'N/A' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Subject</dt>
                    <dd class="mt-1 text-sm text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ $mockSubtopic->topic->subject->name ?? 'N/A' }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockSubtopic->created_at->format('M d, Y \a\t h:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Questions Count</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockSubtopic->mcqs->count() }}</dd>
                </div>
                @if($mockSubtopic->description)
                <div class="sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockSubtopic->description }}</dd>
                </div>
                @endif
            </dl>
        </div>
    </div>

    <!-- Questions Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Questions ({{ $mockSubtopic->mcqs->count() }})</h3>
                <a href="{{ route('admin.questions.create') }}?subtopic_id={{ $mockSubtopic->id }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i class="fas fa-plus mr-2"></i>
                    Add Question
                </a>
            </div>
        </div>

        <div class="px-6 py-4">
            @if($mockSubtopic->mcqs->count() > 0)
                <div class="space-y-4">
                    @foreach($mockSubtopic->mcqs as $question)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="prose prose-sm max-w-none">
                                        {!! Str::limit($question->question_text, 200) !!}
                                        @if(strlen($question->question_text) > 200)
                                            <span class="text-gray-500">...</span>
                                        @endif
                                    </div>
                                    <div class="mt-2 flex items-center space-x-4 text-sm text-gray-600">
                                        <span><strong>Type:</strong> {{ ucfirst($question->question_type) }}</span>
                                        <span><strong>Difficulty:</strong> {{ ucfirst($question->difficulty_level) }}</span>
                                        <span><strong>Marks:</strong> {{ $question->marks }}</span>
                                    </div>
                                </div>
                                <div class="ml-4 flex items-center space-x-2">
                                    <a href="#" class="text-indigo-600 hover:text-indigo-900 text-sm">
                                        View Details
                                    </a>
                                    <a href="#" class="text-blue-600 hover:text-blue-900 text-sm">
                                        Edit
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($mockSubtopic->mcqs->count() >= 10)
                    <div class="mt-6 text-center">
                        <a href="{{ route('admin.question-bank') }}?subtopic_id={{ $mockSubtopic->id }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View All Questions
                        </a>
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-question-circle text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No questions found</h3>
                    <p class="text-gray-500 mb-6">This subtopic doesn't have any questions yet.</p>
                    <a href="{{ route('admin.questions.create') }}?subtopic_id={{ $mockSubtopic->id }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                        <i class="fas fa-plus mr-2"></i>
                        Create Question
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
