@extends('admin.layout')

@section('title', 'Create Subtopic')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Create New Subtopic</h1>
                <p class="text-gray-600 text-sm">Add a new subtopic to organize your MCQ questions under "{{ $topic->name }}"</p>
            </div>
            <a href="{{ route('admin.topics.show', $topic) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Topic
            </a>
        </div>
    </div>

    <!-- Create Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('admin.topics.subtopics.store', $topic) }}" method="POST" class="space-y-6">
            @csrf

            <!-- Topic Information -->
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-info-circle text-indigo-600"></i>
                    <span class="text-sm font-medium text-gray-700">Creating subtopic under:</span>
                </div>
                <div class="mt-2 flex items-center space-x-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $topic->subject->name }}
                    </span>
                    <i class="fas fa-chevron-right text-gray-400"></i>
                    <span class="text-sm text-gray-900 font-medium">{{ $topic->name }}</span>
                </div>
            </div>

            <!-- Subtopic Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag mr-1 text-indigo-600"></i>Subtopic Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm @error('name') border-red-500 @enderror"
                       placeholder="Enter subtopic name..." required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Subtopic name must be unique within this topic</p>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.topics.show', $topic) }}"
                   class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-save mr-2"></i>Create Subtopic
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
            $submitBtn.prop('disabled', false).html('<i class="fas fa-save mr-2"></i>Create Subtopic');
        }, 3000);
    });

    // Auto-focus on name input
    $('#name').focus();
});
</script>
@endpush
