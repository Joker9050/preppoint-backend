@extends('admin.layout')

@section('title', 'Mock Exam Paper Details')

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
                    <a href="{{ route('admin.mock-exam-papers.edit', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Paper
                    </a>
                    <a href="{{ route('admin.mock-exam-papers.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Papers
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

    <!-- Paper Information -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Paper Information</h3>
        </div>
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Exam</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockExamPaper->exam->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Paper Type</dt>
                    <dd class="mt-1 text-sm text-gray-900 capitalize">{{ $mockExamPaper->paper_type }}</dd>
                </div>
                @if($mockExamPaper->year)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Year</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockExamPaper->year }}</dd>
                </div>
                @endif
                @if($mockExamPaper->shift)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Shift</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockExamPaper->shift }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-sm font-medium text-gray-500">UI Template</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $mockExamPaper->ui_template ?? 'Default' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($mockExamPaper->status === 'live') bg-green-100 text-green-800
                            @elseif($mockExamPaper->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($mockExamPaper->status) }}
                        </span>
                    </dd>
                </div>
            </dl>
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

    <!-- Sections -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium text-gray-900">Paper Sections</h3>
                <a href="{{ route('admin.mock-exam-papers.sections.create', $mockExamPaper) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Add Section
                </a>
            </div>
        </div>
        <div class="px-6 py-4">
            @if($mockExamPaper->sections->count() > 0)
                <div class="space-y-4">
                    @foreach($mockExamPaper->sections as $section)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">{{ $section->name }}</h4>
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
                                            <span class="font-medium">Instructions:</span> {{ Str::limit($section->instructions, 100) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center space-x-2 ml-4">
                                    <a href="{{ route('admin.mock-exam-papers.sections.questions', [$mockExamPaper, $section]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-list mr-2"></i>
                                        Manage Questions
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-layer-group text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Sections Added</h3>
                    <p class="text-gray-500 mb-6">Get started by adding sections to this paper.</p>
                    <a href="{{ route('admin.mock-exam-papers.sections.create', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add First Section
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex items-center justify-end space-x-3">
        <a href="{{ route('admin.mock-exam-papers.preview', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
            <i class="fas fa-eye mr-2"></i>
            Preview Paper
        </a>
        <a href="{{ route('admin.mock-exam-papers.edit', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i class="fas fa-edit mr-2"></i>
            Edit Paper
        </a>
    </div>
</div>
@endsection
