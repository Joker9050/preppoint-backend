@extends('admin.layout')

@section('title', 'Preview Mock Exam Paper')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $mockExamPaper->title }}</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $mockExamPaper->exam->name }} • {{ ucfirst($mockExamPaper->paper_type) }}
                        @if($mockExamPaper->year)
                            • {{ $mockExamPaper->year }}
                        @endif
                        @if($mockExamPaper->shift)
                            • {{ $mockExamPaper->shift }}
                        @endif
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.show', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Paper
                    </a>
                </div>
            </div>
        </div>

        <!-- Paper Details -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-question-circle text-2xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Questions</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $mockExamPaper->total_questions }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-trophy text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Marks</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $mockExamPaper->total_marks }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-2xl text-orange-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Duration</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $mockExamPaper->duration_minutes }} min</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-2xl text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Difficulty</dt>
                            <dd class="text-lg font-semibold text-gray-900 capitalize">{{ $mockExamPaper->difficulty_level }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    @if($mockExamPaper->instructions)
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Instructions</h3>
        </div>
        <div class="px-6 py-4">
            <div class="prose prose-sm max-w-none">
                {!! nl2br(e($mockExamPaper->instructions)) !!}
            </div>
        </div>
    </div>
    @endif

    <!-- Sections and Questions -->
    @if($mockExamPaper->sections->count() > 0)
        @foreach($mockExamPaper->sections as $section)
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">{{ $section->name }}</h3>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-600">
                        <div>
                            <span class="font-medium">Questions:</span> {{ $section->total_questions }}
                        </div>
                        <div>
                            <span class="font-medium">Marks:</span> {{ $section->section_marks }}
                        </div>
                        <div>
                            <span class="font-medium">Time:</span> {{ $section->section_time_minutes ?? 'N/A' }} min
                        </div>
                        <div>
                            <span class="font-medium">Sequence:</span> {{ $section->sequence_no }}
                        </div>
                    </div>
                    @if($section->instructions)
                        <div class="mt-2 text-sm text-gray-600">
                            <span class="font-medium">Instructions:</span> {{ $section->instructions }}
                        </div>
                    @endif
                </div>
                <div class="px-6 py-4">
                    @if($section->questions->count() > 0)
                        <div class="space-y-6">
                            @foreach($section->questions as $index => $paperQuestion)
                                @if($paperQuestion->question)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-800 text-sm font-medium rounded-full">
                                                    {{ $index + 1 }}
                                                </span>
                                            </div>
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
                                                    <span><strong>Marks:</strong> {{ $paperQuestion->marks ?? $section->positive_marks }}</span>
                                                    <span><strong>Negative:</strong> {{ $paperQuestion->negative_marks ?? $section->negative_marks }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-question-circle text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">No Questions Added</h4>
                            <p class="text-gray-500">This section doesn't have any questions yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-layer-group text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Sections Found</h3>
                <p class="text-gray-500">This paper doesn't have any sections configured yet.</p>
            </div>
        </div>
    @endif
</div>
@endsection
