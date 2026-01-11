@extends('admin.layout')

@section('title', 'Edit Mock Test')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Mock Test</h1>
            <p class="text-gray-600">Update mock test configuration</p>
        </div>
        <a href="{{ route('admin.mock-tests.show', $mockTest) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to Details
        </a>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.mock-tests.update', $mockTest) }}" method="POST" class="bg-white shadow rounded-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Test Title *</label>
                <input type="text" id="title" name="title" value="{{ old('title', $mockTest->title) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror">
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject *</label>
                <select id="subject_id" name="subject_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('subject_id') border-red-500 @enderror">
                    <option value="">Select Subject</option>
                    @foreach($subjects as $subject)
                    <option value="{{ $subject->id }}" {{ old('subject_id', $mockTest->subject_id) == $subject->id ? 'selected' : '' }}>
                        {{ $subject->name }}
                    </option>
                    @endforeach
                </select>
                @error('subject_id')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                      placeholder="Brief description of the mock test">{{ old('description', $mockTest->description) }}</textarea>
            @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Settings -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label for="time_limit" class="block text-sm font-medium text-gray-700">Time Limit (minutes) *</label>
                <input type="number" id="time_limit" name="time_limit" min="1" max="300" value="{{ old('time_limit', $mockTest->time_limit) }}" required
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('time_limit') border-red-500 @enderror">
                @error('time_limit')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="is_ssc_pyq" class="block text-sm font-medium text-gray-700">Test Type</label>
                <div class="mt-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_ssc_pyq" value="1" {{ old('is_ssc_pyq', $mockTest->is_ssc_pyq) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">SSC PYQ Type</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700">Status</label>
                <div class="mt-2 space-y-2">
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="1" {{ old('is_active', $mockTest->is_active) ? 'checked' : '' }}
                               class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Active</span>
                    </label>
                    <br>
                    <label class="inline-flex items-center">
                        <input type="radio" name="is_active" value="0" {{ old('is_active', $mockTest->is_active) ? '' : 'checked' }}
                               class="border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Inactive</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Topic Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Topics *</label>
            <div id="topics-container" class="grid grid-cols-2 md:grid-cols-3 gap-3 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-3">
                <!-- Topics will be loaded here -->
            </div>
            @error('topic_ids')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- MCQ Selection -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Select MCQs *</label>
            <div class="mb-3">
                <button type="button" id="select-all-mcqs" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">
                    Select All
                </button>
                <span id="selected-count" class="ml-3 text-sm text-gray-600">0 selected</span>
            </div>
            <div id="mcqs-container" class="max-h-60 overflow-y-auto border border-gray-300 rounded-md p-3 space-y-2">
                <!-- MCQs will be loaded here -->
            </div>
            @error('mcq_ids')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Instructions -->
        <div>
            <label for="instructions" class="block text-sm font-medium text-gray-700">Test Instructions</label>
            <textarea id="instructions" name="instructions" rows="4"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('instructions') border-red-500 @enderror"
                      placeholder="Enter instructions for students taking this test">{{ old('instructions', $mockTest->instructions) }}</textarea>
            @error('instructions')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('admin.mock-tests.show', $mockTest) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                Cancel
            </a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-save mr-2"></i>Update Mock Test
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectSelect = document.getElementById('subject_id');
    const topicsContainer = document.getElementById('topics-container');
    const mcqsContainer = document.getElementById('mcqs-container');
    const selectAllBtn = document.getElementById('select-all-mcqs');
    const selectedCount = document.getElementById('selected-count');

    // Load initial data
    loadTopics();
    loadMcqs();

    subjectSelect.addEventListener('change', function() {
        loadTopics();
        loadMcqs();
    });

    function loadTopics() {
        const subjectId = subjectSelect.value;
        if (!subjectId) {
            topicsContainer.innerHTML = '<p class="text-sm text-gray-500">Select a subject first</p>';
            return;
        }

        fetch(`{{ url('admin/mock-tests/get-topics') }}/${subjectId}`)
            .then(response => response.json())
            .then(data => {
                topicsContainer.innerHTML = '';
                data.forEach(topic => {
                    const checkbox = document.createElement('label');
                    checkbox.className = 'flex items-center';
                    checkbox.innerHTML = `
                        <input type="checkbox" name="topic_ids[]" value="${topic.id}"
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-2 topic-checkbox"
                               ${{{ json_encode($mockTest->topic_ids ?? []) }}.includes(topic.id) ? 'checked' : ''}>
                        <span class="text-sm text-gray-700">${topic.name}</span>
                    `;
                    topicsContainer.appendChild(checkbox);
                });

                // Add event listeners
                document.querySelectorAll('.topic-checkbox').forEach(cb => {
                    cb.addEventListener('change', loadMcqs);
                });
            });
    }

    function loadMcqs() {
        const subjectId = subjectSelect.value;
        const selectedTopics = Array.from(document.querySelectorAll('.topic-checkbox:checked')).map(cb => cb.value);

        if (!subjectId || selectedTopics.length === 0) {
            mcqsContainer.innerHTML = '<p class="text-sm text-gray-500">Select topics to load MCQs</p>';
            return;
        }

        fetch('{{ route("admin.mock-tests.get-mcqs") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                subject_id: subjectId,
                topic_ids: selectedTopics
            })
        })
        .then(response => response.json())
        .then(data => {
            mcqsContainer.innerHTML = '';
            data.forEach(mcq => {
                const checkbox = document.createElement('label');
                checkbox.className = 'flex items-start p-2 border border-gray-200 rounded';
                checkbox.innerHTML = `
                    <input type="checkbox" name="mcq_ids[]" value="${mcq.id}"
                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 mr-3 mt-1 mcq-checkbox"
                           ${{{ json_encode($mockTest->mcq_ids ?? []) }}.includes(mcq.id) ? 'checked' : ''}>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-gray-900">${mcq.question.substring(0, 80)}...</div>
                        <div class="text-xs text-gray-500 mt-1">
                            Topic: ${mcq.topic_name} | Difficulty: ${mcq.difficulty}
                        </div>
                    </div>
                `;
                mcqsContainer.appendChild(checkbox);
            });

            updateSelectedCount();
        });
    }

    function updateSelectedCount() {
        const count = document.querySelectorAll('.mcq-checkbox:checked').length;
        selectedCount.textContent = `${count} selected`;
    }

    selectAllBtn.addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('.mcq-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);

        checkboxes.forEach(cb => {
            cb.checked = !allChecked;
        });

        updateSelectedCount();
    });

    mcqsContainer.addEventListener('change', updateSelectedCount);
});
</script>
@endsection
