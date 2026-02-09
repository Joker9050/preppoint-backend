@extends('admin.layout')

@section('title', 'Add Questions to Section - {{ $section->name }}')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Add Questions to Section</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ $section->name }} • {{ $mockExamPaper->title }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.sections.questions.index', [$mockExamPaper, $section]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Section
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
                            <dd class="text-lg font-semibold text-gray-900">{{ $section->questions()->count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-plus-circle text-2xl text-orange-500"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium text-gray-500 truncate">Available Slots</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $section->total_questions - $section->questions()->count() }}</dd>
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
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Filter Questions</h3>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.mock-exam-papers.sections.questions.add', [$mockExamPaper, $section]) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select id="subject_id" name="subject_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="topic_id" class="block text-sm font-medium text-gray-700">Topic</label>
                    <select id="topic_id" name="topic_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Topics</option>
                        @if(request('subject_id'))
                            @php
                                $topics = \App\Models\MockTopic::where('subject_id', request('subject_id'))->get();
                            @endphp
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label for="subtopic_id" class="block text-sm font-medium text-gray-700">Subtopic</label>
                    <select id="subtopic_id" name="subtopic_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Subtopics</option>
                        @if(request('topic_id'))
                            @php
                                $subtopics = \App\Models\MockSubtopic::where('topic_id', request('topic_id'))->get();
                            @endphp
                            @foreach($subtopics as $subtopic)
                                <option value="{{ $subtopic->id }}" {{ request('subtopic_id') == $subtopic->id ? 'selected' : '' }}>{{ $subtopic->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label for="difficulty_level" class="block text-sm font-medium text-gray-700">Difficulty</label>
                    <select id="difficulty_level" name="difficulty_level" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Levels</option>
                        <option value="easy" {{ request('difficulty_level') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ request('difficulty_level') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ request('difficulty_level') == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Questions Form -->
    <form method="POST" action="{{ route('admin.mock-exam-papers.sections.questions.store', [$mockExamPaper, $section]) }}" id="add-questions-form">
        @csrf
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Available Questions</h3>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.questions.create', ['return_url' => request()->fullUrl()]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create New Question
                        </a>
                        <span id="selected-count" class="text-sm text-gray-600">0 questions selected</span>
                        <button type="submit" id="add-selected-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-plus mr-2"></i>
                            Add Selected Questions
                        </button>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4">
                @if($questions->count() > 0)
                    <div class="space-y-4">
                        @foreach($questions as $question)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" class="question-checkbox h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="prose prose-sm max-w-none">
                                                    {!! $question->question !!}
                                                </div>
                                                @if($question->options->count() > 0)
                                                    <div class="mt-4 space-y-2">
                                                        @foreach($question->options as $option)
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
                                                    <span><strong>Subject:</strong> {{ $question->subject->name ?? 'N/A' }}</span>
                                                    <span><strong>Topic:</strong> {{ $question->topic->name ?? 'N/A' }}</span>
                                                    <span><strong>Difficulty:</strong> <span class="capitalize">{{ $question->difficulty_level }}</span></span>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-2 ml-4">
                                                <div class="text-right">
                                                    <label class="block text-sm font-medium text-gray-700">Marks</label>
                                                    <input type="number" name="marks[{{ $question->id }}]" value="{{ $section->positive_marks }}" min="0" step="0.5" class="mt-1 block w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <div class="text-right">
                                                    <label class="block text-sm font-medium text-gray-700">Negative</label>
                                                    <input type="number" name="negative_marks[{{ $question->id }}]" value="{{ $section->negative_marks }}" min="0" step="0.5" class="mt-1 block w-20 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $questions->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-search text-4xl text-gray-400"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No Questions Found</h3>
                        <p class="text-gray-500">Try adjusting your filters to find more questions.</p>
                    </div>
                @endif
            </div>
        </div>
    </form>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.question-checkbox');
    const selectedCount = document.getElementById('selected-count');
    const addSelectedBtn = document.getElementById('add-selected-btn');

    function updateSelectedCount() {
        const checkedBoxes = document.querySelectorAll('.question-checkbox:checked');
        const count = checkedBoxes.length;
        selectedCount.textContent = count + ' question' + (count !== 1 ? 's' : '') + ' selected';
        addSelectedBtn.disabled = count === 0;
    }

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    updateSelectedCount();
});

// AJAX functions for dynamic dropdowns
function loadTopics() {
    const subjectId = document.getElementById('subject_id').value;
    const topicSelect = document.getElementById('topic_id');
    const subtopicSelect = document.getElementById('subtopic_id');

    // Clear existing options
    topicSelect.innerHTML = '<option value="">All Topics</option>';
    subtopicSelect.innerHTML = '<option value="">All Subtopics</option>';

    if (subjectId) {
        fetch(`/admin/get-topics/${subjectId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(topic => {
                    const option = document.createElement('option');
                    option.value = topic.id;
                    option.textContent = topic.name;
                    topicSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading topics:', error));
    }
}

function loadSubtopics() {
    const topicId = document.getElementById('topic_id').value;
    const subtopicSelect = document.getElementById('subtopic_id');

    // Clear existing options
    subtopicSelect.innerHTML = '<option value="">All Subtopics</option>';

    if (topicId) {
        fetch(`/admin/get-subtopics/${topicId}`)
            .then(response => response.json())
            .then(data => {
                data.forEach(subtopic => {
                    const option = document.createElement('option');
                    option.value = subtopic.id;
                    option.textContent = subtopic.name;
                    subtopicSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading subtopics:', error));
    }
}

// Auto-submit form when filters change
document.getElementById('subject_id').addEventListener('change', function() {
    document.getElementById('topic_id').value = '';
    document.getElementById('subtopic_id').value = '';
    this.form.submit();
});

document.getElementById('topic_id').addEventListener('change', function() {
    document.getElementById('subtopic_id').value = '';
    this.form.submit();
});

document.getElementById('subtopic_id').addEventListener('change', function() {
    this.form.submit();
});

document.getElementById('difficulty_level').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endsection
