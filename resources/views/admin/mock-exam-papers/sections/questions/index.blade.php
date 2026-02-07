@extends('admin.layout')

@section('title', 'Section Questions - {{ $section->name }}')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $section->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $mockExamPaper->title }} • {{ $mockExamPaper->exam->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.sections.questions.add', [$mockExamPaper, $section]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Questions
                    </a>
                    <a href="{{ route('admin.mock-exam-papers.show', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Paper
                    </a>
                </div>
            </div>
        </div>

        <!-- Section Details -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-question-circle text-2xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Questions</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $section->total_questions }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Added Questions</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $questions->total() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy text-2xl text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Positive Marks</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $section->positive_marks }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-minus-circle text-2xl text-red-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Negative Marks</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $section->negative_marks }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Questions List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Questions in Section</h3>
        </div>
        <div class="px-6 py-4">
            @if($questions->count() > 0)
                <div class="space-y-4">
                    @foreach($questions as $index => $paperQuestion)
                        @if($paperQuestion->question)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full">
                                            {{ $questions->firstItem() + $index }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="prose prose-sm max-w-none">
                                                    {!! $paperQuestion->question->question !!}
                                                </div>
                                                @if($paperQuestion->question->options->count() > 0)
                                                    <div class="mt-4 space-y-2">
                                                        @foreach($paperQuestion->question->options as $option)
                                                            <div class="flex items-center space-x-3">
                                                                <span class="inline-flex items-center justify-center w-6 h-6 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                                                    {{ chr(65 + $loop->index) }}
                                                                </span>
                                                                <span class="text-sm">{{ $option->option_text }}</span>
                                                                @if($option->is_correct)
                                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                        Correct Answer
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                <div class="mt-4 flex items-center space-x-4 text-sm text-gray-600">
                                                    <span><strong>Marks:</strong> {{ $paperQuestion->marks }}</span>
                                                    <span><strong>Negative:</strong> {{ $paperQuestion->negative_marks }}</span>
                                                    <span><strong>Subject:</strong> {{ $paperQuestion->question->subject->name ?? 'N/A' }}</span>
                                                    <span><strong>Topic:</strong> {{ $paperQuestion->question->topic->name ?? 'N/A' }}</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <form method="POST" action="{{ route('admin.mock-exam-papers.sections.questions.destroy', [$mockExamPaper, $section, $paperQuestion]) }}" onsubmit="return confirm('Are you sure you want to remove this question from the section?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                        <i class="fas fa-trash mr-2"></i>
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $questions->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-question-circle text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Questions Added</h3>
                    <p class="text-gray-500 mb-6">This section doesn't have any questions yet.</p>
                    <a href="{{ route('admin.mock-exam-papers.sections.questions.add', [$mockExamPaper, $section]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Questions
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
