@extends('admin.layout')

@section('title', 'Create New Question')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Create New Question</h1>
                    <p class="mt-1 text-sm text-gray-600">Add a new question to the question bank</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ $returnUrl }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Question Form -->
    <form method="POST" action="{{ route('admin.questions.store') }}" enctype="multipart/form-data" id="createQuestionForm">
        @csrf
        <input type="hidden" name="return_url" value="{{ $returnUrl }}">

        <div class="space-y-8">
            <!-- Basic Information Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Basic Information</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                            <select id="subject_id" name="subject_id" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="topic_id" class="block text-sm font-medium text-gray-700 mb-2">Topic <span class="text-red-500">*</span></label>
                            <select id="topic_id" name="topic_id" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200">
                                <option value="">Select Topic</option>
                            </select>
                        </div>

                        <div>
                            <label for="subtopic_id" class="block text-sm font-medium text-gray-700 mb-2">Subtopic</label>
                            <select id="subtopic_id" name="subtopic_id" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200">
                                <option value="">Select Subtopic (Optional)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question Details Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Question Details</h3>
                </div>
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="question_type" class="block text-sm font-medium text-gray-700 mb-2">Question Type <span class="text-red-500">*</span></label>
                            <select id="question_type" name="question_type" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200">
                                <option value="mcq">Multiple Choice Question (MCQ)</option>
                                <option value="numeric">Numeric Answer</option>
                            </select>
                        </div>

                        <div>
                            <label for="difficulty_level" class="block text-sm font-medium text-gray-700 mb-2">Difficulty Level <span class="text-red-500">*</span></label>
                            <select id="difficulty_level" name="difficulty_level" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200">
                                <option value="easy">Easy</option>
                                <option value="moderate">Moderate</option>
                                <option value="hard">Hard</option>
                            </select>
                        </div>
                    </div>

                    <!-- Question Text -->
                    <div class="mb-6">
                        <label for="question_text" class="block text-sm font-medium text-gray-700 mb-2">Question Text <span class="text-red-500">*</span></label>
                        <textarea id="question_text" name="question_text" rows="4" required class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200" placeholder="Enter the question text..."></textarea>
                    </div>

                    <!-- Question Image -->
                    <div class="mb-6">
                        <label for="question_image" class="block text-sm font-medium text-gray-700 mb-2">Question Image</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="question_image" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                        <span>Upload an image</span>
                                        <input id="question_image" name="question_image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                            </div>
                        </div>
                        <div id="image-preview" class="mt-4 hidden">
                            <img id="preview-img" class="max-w-full h-auto rounded-lg shadow-sm" alt="Question preview">
                            <button type="button" onclick="removeImage()" class="mt-2 text-sm text-red-600 hover:text-red-800">Remove image</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Answer Section -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Answer Details</h3>
                </div>
                <div class="px-6 py-4">
                    <!-- Options (for MCQ) -->
                    <div id="options_section" class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Options <span class="text-red-500">*</span></label>
                        <div id="options_container" class="space-y-3">
                            <div class="option-item bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">A</span>
                                    </div>
                                    <div class="col-span-8">
                                        <input type="text" name="options[A]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option A">
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <input type="radio" name="correct_option" value="A" required class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 mr-2">
                                        <label class="text-sm text-gray-700">Correct</label>
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="option-item bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">B</span>
                                    </div>
                                    <div class="col-span-8">
                                        <input type="text" name="options[B]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option B">
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <input type="radio" name="correct_option" value="B" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 mr-2">
                                        <label class="text-sm text-gray-700">Correct</label>
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="option-item bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">C</span>
                                    </div>
                                    <div class="col-span-8">
                                        <input type="text" name="options[C]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option C">
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <input type="radio" name="correct_option" value="C" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 mr-2">
                                        <label class="text-sm text-gray-700">Correct</label>
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800 disabled:opacity-50" disabled>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="option-item bg-white border border-gray-200 rounded-lg p-4">
                                <div class="grid grid-cols-12 gap-4 items-center">
                                    <div class="col-span-1">
                                        <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">D</span>
                                    </div>
                                    <div class="col-span-8">
                                        <input type="text" name="options[D]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option D">
                                    </div>
                                    <div class="col-span-2 flex items-center">
                                        <input type="radio" name="correct_option" value="D" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 mr-2">
                                        <label class="text-sm text-gray-700">Correct</label>
                                    </div>
                                    <div class="col-span-1 text-center">
                                        <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="button" onclick="addOption()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>
                            Add Option
                        </button>
                    </div>

                    <!-- Numeric Answer (for numeric questions) -->
                    <div id="numeric_section" class="hidden">
                        <label for="numeric_answer" class="block text-sm font-medium text-gray-700 mb-2">Correct Answer <span class="text-red-500">*</span></label>
                        <input type="number" id="numeric_answer" name="numeric_answer" step="any" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200" placeholder="Enter the numeric answer">
                        <p class="mt-1 text-sm text-gray-500">Enter the exact numeric value for the answer</p>
                    </div>

                    <!-- Explanation -->
                    <div class="mt-6">
                        <label for="explanation" class="block text-sm font-medium text-gray-700 mb-2">Explanation</label>
                        <textarea id="explanation" name="explanation" rows="3" class="block w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors duration-200" placeholder="Optional explanation for the answer..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 mt-8">
            <a href="{{ $returnUrl }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                Cancel
            </a>
            <button type="submit" id="submit-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <i class="fas fa-save mr-2"></i>
                Create Question
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load topics when subject changes
    const subjectSelect = document.getElementById('subject_id');
    const topicSelect = document.getElementById('topic_id');
    const subtopicSelect = document.getElementById('subtopic_id');
    const questionTypeSelect = document.getElementById('question_type');
    const optionsSection = document.getElementById('options_section');
    const numericSection = document.getElementById('numeric_section');

    // Load topics when subject changes
    subjectSelect.addEventListener('change', function() {
        const subjectId = this.value;
        if (subjectId) {
            fetch(`/admin/get-topics/${subjectId}`)
                .then(response => response.json())
                .then(data => {
                    topicSelect.innerHTML = '<option value="">Select Topic</option>';
                    subtopicSelect.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
                    data.forEach(topic => {
                        topicSelect.innerHTML += `<option value="${topic.id}">${topic.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading topics:', error);
                    topicSelect.innerHTML = '<option value="">Error loading topics</option>';
                });
        } else {
            topicSelect.innerHTML = '<option value="">Select Topic</option>';
            subtopicSelect.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
        }
    });

    // Load subtopics when topic changes
    topicSelect.addEventListener('change', function() {
        const topicId = this.value;
        if (topicId) {
            fetch(`/admin/get-subtopics/${topicId}`)
                .then(response => response.json())
                .then(data => {
                    subtopicSelect.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
                    data.forEach(subtopic => {
                        subtopicSelect.innerHTML += `<option value="${subtopic.id}">${subtopic.name}</option>`;
                    });
                })
                .catch(error => {
                    console.error('Error loading subtopics:', error);
                    subtopicSelect.innerHTML = '<option value="">Error loading subtopics</option>';
                });
        } else {
            subtopicSelect.innerHTML = '<option value="">Select Subtopic (Optional)</option>';
        }
    });

    // Toggle options/numeric sections based on question type
    questionTypeSelect.addEventListener('change', function() {
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

    // Image upload preview
    const imageInput = document.getElementById('question_image');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    // Form validation
    const form = document.getElementById('createQuestionForm');
    const submitBtn = document.getElementById('submit-btn');

    form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
    });
});

function addOption() {
    const container = document.getElementById('options_container');
    const optionItems = container.querySelectorAll('.option-item');
    const nextLetter = String.fromCharCode(65 + optionItems.length);

    const optionHtml = `
        <div class="option-item bg-white border border-gray-200 rounded-lg p-4">
            <div class="grid grid-cols-12 gap-4 items-center">
                <div class="col-span-1">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">${nextLetter}</span>
                </div>
                <div class="col-span-8">
                    <input type="text" name="options[${nextLetter}]" required class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Option ${nextLetter}">
                </div>
                <div class="col-span-2 flex items-center">
                    <input type="radio" name="correct_option" value="${nextLetter}" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 mr-2">
                    <label class="text-sm text-gray-700">Correct</label>
                </div>
                <div class="col-span-1 text-center">
                    <button type="button" onclick="removeOption(this)" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
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

function removeImage() {
    document.getElementById('question_image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
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
</script>
@endsection
