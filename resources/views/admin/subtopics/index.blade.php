@extends('admin.layout')

@section('title', 'Subtopics Management')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">Content Management</span>
</li>
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">Subtopics</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Subtopics Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage subtopics for organizing MCQ questions</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button type="button" id="add-subtopic-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>Add Subtopic
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dot-circle text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Total Subtopics</dt>
                            <dd class="text-3xl font-bold">{{ $subtopics->total() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Topics</dt>
                            <dd class="text-3xl font-bold">{{ $totalTopics }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Subjects</dt>
                            <dd class="text-3xl font-bold">{{ $totalSubjects }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Filter Subtopics</h2>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.subtopics.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <select id="subject_id" name="subject_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="topic_id" class="block text-sm font-medium text-gray-700 mb-2">Topic</label>
                    <select id="topic_id" name="topic_id" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic->id }}" data-subject-id="{{ $topic->subject_id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                                {{ $topic->name }} ({{ $topic->subject->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.subtopics.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Subtopics Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">All Subtopics</h2>
            @if(request('subject_id') || request('topic_id'))
                <p class="mt-1 text-sm text-gray-600">
                    Filtered results:
                    @if(request('subject_id'))
                        Subject: {{ $subjects->find(request('subject_id'))->name ?? 'Unknown' }}
                    @endif
                    @if(request('subject_id') && request('topic_id')) | @endif
                    @if(request('topic_id'))
                        Topic: {{ $topics->find(request('topic_id'))->name ?? 'Unknown' }}
                    @endif
                </p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table id="subtopics-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtopic</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Questions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($subtopics as $subtopic)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-dot-circle text-indigo-600 text-xs"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $subtopic->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $subtopic->topic->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $subtopic->topic->subject->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subtopic->mcqs->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $subtopic->created_at->format('M j, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.subtopics.show', $subtopic) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.topics.subtopics.edit', [$subtopic->topic, $subtopic]) }}" class="text-blue-600 hover:text-blue-900 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.topics.subtopics.destroy', [$subtopic->topic, $subtopic]) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this subtopic?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 transition-colors">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                    <i class="fas fa-dot-circle text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No subtopics found</h3>
                                <p class="text-gray-500 text-sm">Get started by creating your first subtopic.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($subtopics->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $subtopics->links() }}
            </div>
        @endif
    </div>

    <!-- Add Subtopic Modal -->
    <div id="add-subtopic-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[20vh] overflow-hidden">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Add New Subtopic</h3>
                    <p class="text-sm text-gray-600 mt-1">Select a topic to add your subtopic to</p>
                </div>
                <button id="close-modal-btn" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-4">
                <!-- Filters -->
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="modal-subject-filter" class="block text-sm font-medium text-gray-700 mb-2">Filter by Subject</label>
                        <select id="modal-subject-filter" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="modal-search" class="block text-sm font-medium text-gray-700 mb-2">Search Topics</label>
                        <div class="relative">
                            <input type="text" id="modal-search" placeholder="Search topics..." class="block w-full px-3 py-2 pl-10 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Topics List -->
                <div class="max-h-32 overflow-y-auto border rounded-md">
                    @if($topics->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                <i class="fas fa-list text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No topics available</h3>
                            <p class="text-gray-500 text-sm mb-4">You need to create topics before you can add subtopics.</p>
                            <a href="{{ route('admin.topics.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                <i class="fas fa-plus mr-2"></i>Create Topic
                            </a>
                        </div>
                    @else
                        <div class="divide-y divide-gray-200">
                            @foreach($topics as $topic)
                                <div class="topic-item p-4 hover:bg-gray-50 cursor-pointer" data-topic-id="{{ $topic->id }}" data-topic-name="{{ strtolower($topic->name) }}" data-subject-id="{{ $topic->subject_id }}">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-list text-indigo-600"></i>
                                            </div>
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">{{ $topic->name }}</h4>
                                                <p class="text-sm text-gray-500">{{ $topic->subject->name }}</p>
                                            </div>
                                        </div>
                                        <button class="select-topic-btn inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-topic-id="{{ $topic->id }}" data-topic-name="{{ $topic->name }}">
                                            Select Topic
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex items-center justify-end px-6 py-4 border-t bg-gray-50">
                <button id="cancel-modal-btn" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#subtopics-table').DataTable({
        "pageLength": 15,
        "ordering": true,
        "searching": true,
        "paging": false,
        "info": false,
        "columnDefs": [
            { "orderable": false, "targets": 5 }
        ]
    });

    // Dynamic topic filtering based on subject selection
    $('#subject_id').change(function() {
        var subjectId = $(this).val();
        var topicSelect = $('#topic_id');

        if (subjectId) {
            // Filter topics by selected subject
            topicSelect.find('option').each(function() {
                var topicSubjectId = $(this).data('subject-id');
                if (topicSubjectId && topicSubjectId != subjectId) {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            });
            // Reset topic selection if current selection is hidden
            if (topicSelect.find('option:selected').is(':hidden')) {
                topicSelect.val('');
            }
        } else {
            // Show all topics
            topicSelect.find('option').show();
        }
    });

    // Trigger change on page load if subject is pre-selected
    if ($('#subject_id').val()) {
        $('#subject_id').trigger('change');
    }

    // Modal Functionality
    function openModal() {
        $('#add-subtopic-modal').removeClass('hidden');
        $('body').addClass('overflow-hidden');
    }

    function closeModal() {
        $('#add-subtopic-modal').addClass('hidden');
        $('body').removeClass('overflow-hidden');
        // Reset filters
        $('#modal-subject-filter').val('');
        $('#modal-search').val('');
        filterTopics();
    }

    function filterTopics() {
        var subjectFilter = $('#modal-subject-filter').val().toLowerCase();
        var searchFilter = $('#modal-search').val().toLowerCase();

        $('.topic-item').each(function() {
            var $item = $(this);
            var subjectId = $item.data('subject-id');
            var topicName = $item.data('topic-name');

            var subjectMatch = !subjectFilter || subjectId == subjectFilter;
            var searchMatch = !searchFilter || topicName.includes(searchFilter);

            if (subjectMatch && searchMatch) {
                $item.show();
            } else {
                $item.hide();
            }
        });
    }

    // Open modal
    $('#add-subtopic-btn').click(function() {
        openModal();
    });

    // Close modal events
    $('#close-modal-btn, #cancel-modal-btn').click(function() {
        closeModal();
    });

    // Close modal when clicking outside
    $('#add-subtopic-modal').click(function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with Escape key
    $(document).keydown(function(e) {
        if (e.keyCode === 27 && !$('#add-subtopic-modal').hasClass('hidden')) {
            closeModal();
        }
    });

    // Filter functionality
    $('#modal-subject-filter, #modal-search').on('input change', function() {
        filterTopics();
    });

    // Select topic
    $('.select-topic-btn').click(function() {
        var topicId = $(this).data('topic-id');
        var topicName = $(this).data('topic-name');

        // Redirect to subtopic creation page
        window.location.href = '/admin/topics/' + topicId + '/subtopics/create';
    });
});
</script>
@endpush
@endsection

