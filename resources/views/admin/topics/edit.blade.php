@extends('admin.layout')

@section('title', 'Edit Topic')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Edit Topic</h1>
                <p class="text-gray-600 text-sm">Update topic information and settings</p>
            </div>
            <a href="{{ route('admin.topics.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Topics
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.topics.update', $topic) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

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
                        <option value="{{ $subject->id }}" {{ (old('subject_id', $topic->subject_id) == $subject->id) ? 'selected' : '' }}>
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
                <input type="text" id="name" name="name" value="{{ old('name', $topic->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('name') border-red-500 @enderror"
                       placeholder="Enter topic name..." required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Topic name must be unique within the selected subject</p>
            </div>

            <!-- Current Information Display -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-700 mb-2">Current Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Created:</span>
                        <span class="ml-1 text-gray-900">{{ $topic->created_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Last Updated:</span>
                        <span class="ml-1 text-gray-900">{{ $topic->updated_at->format('M j, Y g:i A') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Subtopics:</span>
                        <span class="ml-1 text-gray-900">{{ $topic->subtopics->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.topics.show', $topic) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-eye mr-2"></i>View Topic
                </a>
                <a href="{{ route('admin.topics.index') }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>Update Topic
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Store original values for change detection
    const originalSubjectId = '{{ $topic->subject_id }}';
    const originalName = '{{ $topic->name }}';

    // Form validation enhancement
    $('form').on('submit', function(e) {
        const $form = $(this);
        const $submitBtn = $form.find('button[type="submit"]');

        // Check if any changes were made
        const currentSubjectId = $('#subject_id').val();
        const currentName = $('#name').val().trim();

        if (currentSubjectId === originalSubjectId && currentName === originalName) {
            alert('No changes were made to the topic.');
            return false;
        }

        // Disable submit button to prevent double submission
        $submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Updating...');

        // Re-enable after 3 seconds (in case of client-side validation failure)
        setTimeout(function() {
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Update Topic');
        }, 3000);
    });

    // Auto-focus on name input
    $('#name').focus();

    // Subject change handler
    $('#subject_id').on('change', function() {
        const selectedSubject = $(this).find('option:selected').text();
        if (selectedSubject) {
            console.log('Selected subject:', selectedSubject);
            // Could add dynamic validation or suggestions here
        }
    });

    // Name input validation
    $('#name').on('input', function() {
        const $input = $(this);
        const value = $input.val().trim();

        // Remove error styling if user starts typing
        if (value.length > 0) {
            $input.removeClass('border-red-500');
            $input.next('.text-red-600').remove();
        }
    });
});
</script>
@endpush
