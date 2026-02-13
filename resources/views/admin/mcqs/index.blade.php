@extends('admin.layout')

@section('title', 'MCQs')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">MCQs</h1>
            <p class="text-gray-600">Manage multiple choice questions</p>
        </div>
        <a href="{{ route('admin.mcqs.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>Add MCQ
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">Filter MCQs</h2>
        </div>
        <div class="px-6 py-4">
            <form method="GET" action="{{ route('admin.mcqs.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div>
                    <label for="topic_id" class="block text-xs font-medium text-gray-700 mb-1">Topic</label>
                    <select id="topic_id" name="topic_id" class="block w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                        <option value="{{ $topic->id }}" {{ request('topic_id') == $topic->id ? 'selected' : '' }}>
                            {{ $topic->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="block w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div>
                    <label for="difficulty" class="block text-xs font-medium text-gray-700 mb-1">Difficulty</label>
                    <select id="difficulty" name="difficulty" class="block w-full px-2 py-1.5 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">All Levels</option>
                        <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                        <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full justify-center">
                        <i class="fas fa-filter mr-1"></i>Apply
                    </button>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('admin.mcqs.index') }}" class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-xs font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full justify-center">
                        <i class="fas fa-times mr-1"></i>Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- MCQs Table -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-medium text-gray-900">All MCQs</h2>
            @if(request('subject_id') || request('topic_id') || request('status') || request('difficulty'))
                <p class="mt-1 text-sm text-gray-600">
                    Filtered results:
                    @if(request('subject_id'))
                        Subject: {{ $subjects->find(request('subject_id'))->name ?? 'Unknown' }}
                    @endif
                    @if(request('subject_id') && (request('topic_id') || request('status') || request('difficulty'))) | @endif
                    @if(request('topic_id'))
                        Topic: {{ $topics->find(request('topic_id'))->name ?? 'Unknown' }}
                    @endif
                    @if(request('topic_id') && (request('status') || request('difficulty'))) | @endif
                    @if(request('status'))
                        Status: {{ ucfirst(request('status')) }}
                    @endif
                    @if(request('status') && request('difficulty')) | @endif
                    @if(request('difficulty'))
                        Difficulty: {{ ucfirst(request('difficulty')) }}
                    @endif
                </p>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table id="mcqs-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Difficulty</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($mcqs as $mcq)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $mcq->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                {{ Str::limit($mcq->question, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $mcq->topic->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $mcq->topic->subject->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($mcq->difficulty == 'easy') bg-green-100 text-green-800
                                    @elseif($mcq->difficulty == 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($mcq->difficulty) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @if($mcq->status == 'active') bg-green-100 text-green-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($mcq->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('admin.mcqs.preview', $mcq) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors" title="Preview">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mcqs.edit', $mcq) }}" class="text-blue-600 hover:text-blue-900 transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.mcqs.destroy', $mcq) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')" title="Delete">
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
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 mx-auto">
                                    <i class="fas fa-question-circle text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No MCQs found</h3>
                                <p class="text-gray-500 text-sm">Get started by creating your first MCQ.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($mcqs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $mcqs->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#mcqs-table').DataTable({
        "pageLength": 15,
        "ordering": true,
        "searching": true,
        "paging": false,
        "info": false,
        "columnDefs": [
            { "orderable": false, "targets": 6 }
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
});
</script>
@endpush
@endsection
