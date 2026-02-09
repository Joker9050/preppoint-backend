@extends('admin.layout')

@section('title', 'Sections - {{ $mockExamPaper->title }}')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Sections</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $mockExamPaper->title }} • {{ $mockExamPaper->exam->name }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.show', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Paper
                    </a>
                    <a href="{{ route('admin.mock-exam-papers.sections.create', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add Section
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sections List -->
    @if($sections->count() > 0)
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Paper Sections</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sequence</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Section Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($sections as $section)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $section->sequence_no }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $section->name }}</div>
                                    @if($section->instructions)
                                        <div class="text-sm text-gray-500 truncate max-w-xs">{{ $section->instructions }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $section->total_questions }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $section->section_marks }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($section->section_time_minutes)
                                        {{ $section->section_time_minutes }} min
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.mock-exam-papers.sections.questions.index', [$mockExamPaper, $section]) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-list mr-1"></i>
                                            Questions
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('admin.mock-exam-papers.sections.questions.add', [$mockExamPaper, $section]) }}" class="text-green-600 hover:text-green-900">
                                            <i class="fas fa-plus mr-1"></i>
                                            Add Questions
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <a href="{{ route('admin.mock-exam-papers.sections.edit', [$mockExamPaper, $section]) }}" class="text-orange-600 hover:text-orange-900">
                                            <i class="fas fa-edit mr-1"></i>
                                            Edit
                                        </a>
                                        <span class="text-gray-300">|</span>
                                        <form action="{{ route('admin.mock-exam-papers.sections.destroy', [$mockExamPaper, $section]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this section?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-layer-group text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Sections Found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first section for this paper.</p>
                <a href="{{ route('admin.mock-exam-papers.sections.create', $mockExamPaper) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Section
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
