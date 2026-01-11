@extends('admin.layout')

@section('title', 'Topic Details')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $topic->name }}</h1>
                <p class="text-gray-600 text-sm">Topic details and associated subtopics</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.topics.edit', $topic) }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Topic
                </a>
                <a href="{{ route('admin.topics.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Topics
                </a>
            </div>
        </div>
    </div>

    <!-- Topic Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Topic Information -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>Topic Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Topic Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $topic->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Subject</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $topic->subject->name }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Created</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $topic->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $topic->updated_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subtopics Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-indigo-600"></i>Subtopics
                        <span class="ml-2 inline-flex items-center justify-center w-6 h-6 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                            {{ $topic->subtopics->count() }}
                        </span>
                    </h2>
                    <a href="#" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 transition-colors">
                        <i class="fas fa-plus mr-1.5"></i>Add Subtopic
                    </a>
                </div>

                @if($topic->subtopics->count() > 0)
                    <div class="space-y-3">
                        @foreach($topic->subtopics as $subtopic)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-dot-circle text-indigo-600 text-xs"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">{{ $subtopic->name }}</h3>
                                        <p class="text-xs text-gray-500">Created {{ $subtopic->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-gray-400 hover:text-red-600 transition-colors">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                            <i class="fas fa-list text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-1">No subtopics yet</h3>
                        <p class="text-gray-500 text-sm mb-4">Get started by creating your first subtopic for this topic.</p>
                        <button class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add First Subtopic
                        </button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Statistics Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Subtopics</span>
                        <span class="text-sm font-medium text-gray-900">{{ $topic->subtopics->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Subject</span>
                        <span class="text-sm font-medium text-gray-900">{{ $topic->subject->name }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Created</span>
                        <span class="text-sm font-medium text-gray-900">{{ $topic->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.topics.edit', $topic) }}"
                       class="w-full inline-flex items-center px-3 py-2 text-sm font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Topic
                    </a>
                    <button class="w-full inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 bg-green-50 border border-green-200 rounded-md hover:bg-green-100 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add Subtopic
                    </button>
                    <button class="w-full inline-flex items-center px-3 py-2 text-sm font-medium text-purple-700 bg-purple-50 border border-purple-200 rounded-md hover:bg-purple-100 transition-colors">
                        <i class="fas fa-copy mr-2"></i>Duplicate Topic
                    </button>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-xl shadow-sm border border-red-200 p-6">
                <h3 class="text-lg font-semibold text-red-900 mb-4">Danger Zone</h3>
                <p class="text-sm text-red-700 mb-4">Once you delete this topic, there is no going back. Please be certain.</p>
                <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this topic? This action cannot be undone and will also delete all associated subtopics.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 transition-colors">
                        <i class="fas fa-trash mr-2"></i>Delete Topic
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add loading states for actions
    $('.bg-white button, .bg-white a').on('click', function(e) {
        const $this = $(this);
        if ($this.is('button') && !$this.hasClass('no-loading')) {
            $this.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i>Loading...');
        }
    });

    // Quick action handlers (placeholders for now)
    $('button:contains("Add Subtopic")').on('click', function(e) {
        e.preventDefault();
        alert('Add Subtopic functionality coming soon!');
    });

    $('button:contains("Duplicate Topic")').on('click', function(e) {
        e.preventDefault();
        alert('Duplicate Topic functionality coming soon!');
    });
});
</script>
@endpush
