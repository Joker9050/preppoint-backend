@extends('admin.layout')

@section('title', 'Create Topic')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create New Topic</h1>
                <p class="text-gray-600 text-sm">Add a new topic to organize your MCQ questions</p>
            </div>
            <a href="{{ route('admin.topics.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Topics
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.topics.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-book mr-1 text-indigo-600"></i>Subject <span class="text-red-500">*</span>
                </label>
                <select id="subject_id" name="subject_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white @error('subject_id') border-red-500 @enderror"
                        required>
                    <option value="">Select a subject...</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
                @error('subject_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Topic Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-indigo-600"></i>Topic Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('name') border-red-500 @enderror"
                       placeholder="Enter topic name..." required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Topic name must be unique within the selected subject</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.topics.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>Create Topic
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation enhancement
    $('form').on('submit', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');

        // Disable submit button to prevent double submission
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Creating...');

        // Re-enable after 3 seconds (in case of client-side validation failure)
        setTimeout(function() {
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Create Topic');
        }, 3000);
    });

    // Auto-focus on first input
    $('#subject_id').focus();

    // Subject change handler (could be used for dynamic validation)
    $('#subject_id').on('change', function() {
        const selectedSubject = $(this).find('option:selected').text();
        if (selectedSubject) {
            console.log('Selected subject:', selectedSubject);
            // Could add dynamic validation or suggestions here
        }
    });
});
</script>
@endpush
