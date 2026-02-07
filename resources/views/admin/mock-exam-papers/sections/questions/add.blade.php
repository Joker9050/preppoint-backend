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
                    <a href="{{ route('admin.mock-exam-papers.sections.questions', [$mockExamPaper, $section]) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                    <select id="topic_id" name="topic_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Topics</option>
                        @if(request('subject_id'))
                            @php
                                $topics = \App\Models\MockTopic::where('subject_id', request('subject_id'))->where('status', true)->get();
                            @endphp
                            @foreach($topics as $topic)
                                <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>{{ $topic->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div>
                    <label for="subtopic_id" class="block text-sm font-medium text-gray-700">Subtopic</label>
                    <select id="subtopic_id" name="subtopic_id" onchange="this.form.submit()" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Subtopics</option>
                        @if(request('topic_id'))
                            @php
                                $subtopics = \App\Models\MockSubtopic::where('topic_id', request('topic_id'))->where('status', true)->get();
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
                        <button type="button" onclick="openCreateQuestionModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i>
                            Create New Question
                        </button>
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

<!-- Create Question Modal -->
<div id="createQuestionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Create New Question</h3>
                <button onclick="closeCreateQuestionModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="createQuestionForm" method="POST" action="{{ route('admin.mock-exam-papers.sections.questions.create', [$mockExamPaper, $section]) }}">
                @csrf
                <div class="space-y-6">
                    <!-- Subject, Topic, Subtopic -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="modal_subject_id" class="block text-sm font-medium text-gray-700">Subject *</label>
                            <select id="modal_subject_id" name="subject_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="modal_topic_id" class="block text-sm font-medium text-gray-700">Topic *</label>
                            <select id="modal_topic_id" name="topic_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Topic</option>
                            </select>
                        </div>

                        <div>
                            <label for="modal_subtopic_id" class="block text-sm font-medium text-gray-700">Subtopic</label>
                            <select id="modal_subtopic_id" name="subtopic_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Subtopic (Optional)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Question Type and Difficulty -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="question_type" class="block text-sm font-medium text-gray-700">Question Type *</label>
                            <select id="question_type" name="question_type" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="mcq">Multiple Choice Question (MCQ)</option>
                                <option value="numeric">Numeric Answer</option>
                            </select>
                        </div>

                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-gray-700">Difficulty Level *</label>
                            <select id="difficulty_level" name="difficulty_level" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="easy">Easy</option>
                                <option value="moderate">Moderate</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700">Question Text *</label>
                        <textarea id="question_text" name="question_text" rows="4" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter the question text..."></textarea>
                    </div>

                    <!-- Options (for MCQ) -->
                    <div id="options_section" class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700">Options *</label>
                        <div id="options_container">
                            <div class="option-item grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded">A</span>
                                </div>
                                <div class="col-span-9">
                                    <input type="text" name="options[A]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option A">
                                </div>
                                <div class="col-span-1">
                                    <input type="radio" name="correct_option" value="A" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="option-item grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded">B</span>
                                </div>
                                <div class="col-span-9">
                                    <input type="text" name="options[B]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option B">
                                </div>
                                <div class="col-span-1">
                                    <input type="radio" name="correct_option" value="B" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="option-item grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded">C</span>
                                </div>
                                <div class="col-span-9">
                                    <input type="text" name="options[C]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option C">
                                </div>
                                <div class="col-span-1">
                                    <input type="radio" name="correct_option" value="C" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="option-item grid grid-cols-12 gap-2 items-end">
                                <div class="col-span-1">
                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded">D</span>
                                </div>
                                <div class="col-span-9">
                                    <input type="text" name="options[D]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option D">
                                </div>
                                <div class="col-span-1">
                                    <input type="radio" name="correct_option" value="D" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                </div>
                                <div class="col-span-1">
                                    <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addOption()" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            Add Option
                        </button>
                    </div>

                    <!-- Numeric Answer (for numeric questions) -->
                    <div id="numeric_section" class="hidden">
                        <label for="numeric_answer" class="block text-sm font-medium text-gray-700">Correct Answer *</label>
                        <input type="number" id="numeric_answer" name="numeric_answer" step="any" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter the numeric answer">
                    </div>

                    <!-- Explanation -->
                    <div>
                        <label for="explanation" class="block text-sm font-medium text-gray-700">Explanation</label>
                        <textarea id="explanation" name="explanation" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Optional explanation for the answer..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeCreateQuestionModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-save mr-2"></i>
                        Create & Add Question
                    </button>
                </div>
            </form>
        </div>
    </div>
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

    // Modal functionality
    const modalSubject = document.getElementById('modal_subject_id');
    const modalTopic = document.getElementById('modal_topic_id');
    const modalSubtopic = document.getElementById('modal_subtopic_id');
    const questionType = document.getElementById('question_type');

    // Load topics when subject changes
    modalSubject.addEventListener('change', function() {
        const subjectId = this.value;
        if (subjectId) {
            fetch(`/admin/get-topics/${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    modalTopic.innerHTML = '<option value="">Select Topic</option>';
                    modalSubtopic.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
                    data.forEach(topic => {
                        modalTopic.innerHTML += `<option value="${topic.id}">${topic.name}</option>`;
                    });
                });
        } else {
            modalTopic.innerHTML = '<option value="">Select Topic</option>';
            modalSubtopic.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
        }
    });

    // Load subtopics when topic changes
    modalTopic.addEventListener('change', function() {
        const topicId = this.value;
        if (topicId) {
            fetch(`/admin/get-subtopics/${topicId}`)
                .then(response => response.json())
                .then(data => {
                    modalSubtopic.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
                    data.forEach(subtopic => {
                        modalSubtopic.innerHTML += `<option value="${subtopic.id}">${subtopic.name}</option>`;
                    });
                });
        } else {
            modalSubtopic.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
        }
    });

    // Toggle options/numeric sections based on question type
    questionType.addEventListener('change', function() {
        const optionsSection = document.getElementById('options_section');
        const numericSection = document.getElementById('numeric_section');
        const correctOptionRadios = document.querySelectorAll('input[name="correct_option"]');
        const optionInputs = document.querySelectorAll('input[name^="options["]');

        if (this.value === 'mcq') {
            optionsSection.classList.remove('hidden');
            numericSection.classList.add('hidden');
            correctOptionRadios.forEach(radio => radio.required = true);
            optionInputs.forEach(input => input.required = true);
            document.getElementById('numeric_answer').required = false;
        } else {
            optionsSection.classList.add('hidden');
            numericSection.classList.remove('hidden');
            correctOptionRadios.forEach(radio => radio.required = false);
            optionInputs.forEach(input => input.required = false);
            document.getElementById('numeric_answer').required = true;
        }
    });
});

function openCreateQuestionModal() {
    document.getElementById('createQuestionModal').classList.remove('hidden');
}

function closeCreateQuestionModal() {
    document.getElementById('createQuestionModal').classList.add('hidden');
    document.getElementById('createQuestionForm').reset();
}

function addOption() {
    const container = document.getElementById('options_container');
    const optionItems = container.querySelectorAll('.option-item');
    const nextLetter = String.fromCharCode(65 + optionItems.length);

    const optionHtml = `
        <div class="option-item grid grid-cols-12 gap-2 items-end">
            <div class="col-span-1">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded">${nextLetter}</span>
            </div>
            <div class="col-span-9">
                <input type="text" name="options[${nextLetter}]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option ${nextLetter}">
            </div>
            <div class="col-span-1">
                <input type="radio" name="correct_option" value="${nextLetter}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300">
            </div>
            <div class="col-span-1">
                <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    updateRemoveButtons();
}

function removeOption(button) {
    button.closest('.option-item').remove();
    updateOptionLabels();
    updateRemoveButtons();
}

function updateOptionLabels() {
    const optionItems = document.querySelectorAll('.option-item');
    optionItems.forEach((item, index) => {
        const letter = String.fromCharCode(65 + index);
        item.querySelector('span').textContent = letter;
        const input = item.querySelector('input[name^="options["]');
        input.name = `options[${letter}]`;
        input.placeholder = `Option ${letter}`;
        const radio = item.querySelector('input[type="radio"]');
        radio.value = letter;
    });
}

function updateRemoveButtons() {
    const optionItems = document.querySelectorAll('.option-item');
    optionItems.forEach((item, index) => {
        const removeBtn = item.querySelector('button[onclick="removeOption(this)"]');
        removeBtn.disabled = optionItems.length <= 2;
    });
}

// Close modal when clicking outside
document.getElementById('createQuestionModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCreateQuestionModal();
    }
});
</script>
@endsection
