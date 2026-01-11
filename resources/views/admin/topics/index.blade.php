@extends('admin.layout')

@section('title', 'Topics')

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="space-y-1">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Topics Management</h1>
                <p class="text-gray-600 text-sm">Organize and manage your MCQ topics with advanced filtering and search capabilities</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 bg-gray-50 px-3 py-1 rounded-full">
                    <i class="fas fa-list mr-1"></i>{{ $topics->count() }} topics
                </div>
                <a href="{{ route('admin.topics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>Add New Topic
                </a>
            </div>
        </div>
    </div>

    <!-- DataTable Container -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Controls -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50/50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="text-sm font-medium text-gray-700">Data Controls</div>
                    <div class="flex items-center space-x-2">
                        <button id="refresh-btn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors">
                            <i class="fas fa-sync-alt mr-1.5"></i>Refresh
                        </button>
                        <button id="export-btn" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 transition-colors">
                            <i class="fas fa-download mr-1.5"></i>Export
                        </button>
                    </div>
                </div>
                <div class="text-xs text-gray-500">
                    Last updated: <span id="last-updated">{{ now()->format('M j, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table id="topics-table" class="w-full">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-hashtag text-gray-400"></i>
                                <span>ID</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-tag text-gray-400"></i>
                                <span>Topic Name</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-book text-gray-400"></i>
                                <span>Subject</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-dot-circle text-gray-400"></i>
                                <span>Subtopics</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-calendar text-gray-400"></i>
                                <span>Created</span>
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-cogs text-gray-400"></i>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($topics as $topic)
                    <tr class="hover:bg-indigo-50/30 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                                    {{ $topic->id }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-list text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                        {{ $topic->name }}
                                    </div>
                                    <div class="text-xs text-gray-500">Topic #{{ $topic->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $topic->subject->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-blue-100 text-blue-800 text-xs font-medium rounded-full mr-2">
                                    {{ $topic->subtopics->count() }}
                                </span>
                                <span class="text-sm text-gray-600">subtopics</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $topic->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-1">
                                <a href="{{ route('admin.topics.show', $topic) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-md hover:bg-indigo-100 hover:text-indigo-800 transition-all duration-150 group/btn">
                                    <i class="fas fa-eye mr-1.5 group-hover/btn:scale-110 transition-transform"></i>
                                    View
                                </a>
                                <a href="{{ route('admin.topics.edit', $topic) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-800 transition-all duration-150 group/btn">
                                    <i class="fas fa-edit mr-1.5 group-hover/btn:scale-110 transition-transform"></i>
                                    Edit
                                </a>
                                <form action="{{ route('admin.topics.destroy', $topic) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this topic? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-800 transition-all duration-150 group/btn">
                                        <i class="fas fa-trash mr-1.5 group-hover/btn:scale-110 transition-transform"></i>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-list text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No topics found</h3>
                                <p class="text-gray-500 text-sm mb-4">Get started by creating your first topic.</p>
                                <a href="{{ route('admin.topics.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add First Topic
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable with enhanced configuration
    const table = $('#topics-table').DataTable({
        responsive: {
            details: {
                type: 'column',
                target: 'tr'
            }
        },
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
        columnDefs: [
            { orderable: false, targets: 5 }, // Actions column not sortable
            { responsivePriority: 1, targets: 0 }, // ID always visible
            { responsivePriority: 2, targets: 1 }, // Name always visible
            { responsivePriority: 3, targets: 5 }  // Actions always visible
        ],
        language: {
            search: "",
            lengthMenu: "Show _MENU_ entries per page",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "No entries available",
            infoFiltered: "(filtered from _MAX_ total entries)",
            zeroRecords: "No matching records found",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"flex flex-col sm:flex-row justify-between items-center mb-4"<"flex items-center mb-2 sm:mb-0"l><"flex items-center"f>>rt<"flex flex-col sm:flex-row justify-between items-center mt-4"<"flex items-center mb-2 sm:mb-0"i><"flex items-center"p>>',
        initComplete: function() {
            // Add search placeholder
            $('.dataTables_filter input').attr('placeholder', 'Search topics...');

            // Style the search input with icon
            $('.dataTables_filter').prepend('<i class="fas fa-search text-gray-400 mr-2"></i>');

            // Style the search input
            $('.dataTables_filter input').addClass('pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm w-64 transition-all');

            // Style the length select
            $('.dataTables_length select').addClass('px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white');

            // Style pagination buttons
            $('.dataTables_paginate .paginate_button').addClass('px-3 py-2 mx-1 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition-all duration-200');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 shadow-sm');
            $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed');

            // Style table info
            $('.dataTables_info').addClass('text-sm text-gray-600');

            // Add loading state for actions
            $('.dataTables_wrapper').on('click', 'a, button', function() {
                const $this = $(this);
                if ($this.is('a') && !$this.hasClass('no-loading')) {
                    $this.addClass('pointer-events-none opacity-50');
                    setTimeout(() => $this.removeClass('pointer-events-none opacity-50'), 1000);
                }
            });
        },
        drawCallback: function() {
            // Re-apply hover effects after table redraw
            $('#topics-table tbody tr').hover(
                function() { $(this).addClass('bg-indigo-50/50'); },
                function() { $(this).removeClass('bg-indigo-50/50'); }
            );

            // Update last updated timestamp
            $('#last-updated').text(new Date().toLocaleString());
        }
    });

    // Enhanced search with debouncing
    let searchTimeout;
    $('.dataTables_filter input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const $this = $(this);
        searchTimeout = setTimeout(function() {
            table.search($this.val()).draw();
        }, 300);
    });

    // Refresh button functionality
    $('#refresh-btn').on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1.5"></i>Refreshing...');

        // Simulate refresh (in real app, this would reload data)
        setTimeout(function() {
            table.ajax.reload(null, false);
            $btn.prop('disabled', false).html('<i class="fas fa-sync-alt mr-1.5"></i>Refresh');
            $('#last-updated').text(new Date().toLocaleString());

            // Show success message
            showNotification('Data refreshed successfully', 'success');
        }, 1000);
    });

    // Export button functionality
    $('#export-btn').on('click', function() {
        const $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1.5"></i>Exporting...');

        setTimeout(function() {
            // In a real application, this would trigger a download
            $btn.prop('disabled', false).html('<i class="fas fa-download mr-1.5"></i>Export');
            showNotification('Export feature coming soon!', 'info');
        }, 1000);
    });

    // Notification system
    function showNotification(message, type = 'info') {
        const colors = {
            success: 'bg-green-100 text-green-800 border-green-200',
            error: 'bg-red-100 text-red-800 border-red-200',
            warning: 'bg-yellow-100 text-yellow-800 border-yellow-200',
            info: 'bg-blue-100 text-blue-800 border-blue-200'
        };

        const notification = $(`
            <div class="fixed top-4 right-4 z-50 p-4 rounded-lg border ${colors[type]} shadow-lg transform translate-x-full transition-transform duration-300">
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-info-circle'} mr-2"></i>
                    <span class="text-sm font-medium">${message}</span>
                </div>
            </div>
        `);

        $('body').append(notification);
        setTimeout(() => notification.removeClass('translate-x-full'), 100);

        setTimeout(() => {
            notification.addClass('translate-x-full');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Keyboard shortcuts
    $(document).on('keydown', function(e) {
        // Ctrl/Cmd + F to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            $('.dataTables_filter input').focus();
        }

        // Ctrl/Cmd + R to refresh
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            $('#refresh-btn').click();
        }
    });

    // Add tooltips to action buttons
    $('#topics-table').on('mouseenter', '.group/btn', function() {
        const $this = $(this);
        if (!$this.attr('title')) {
            const action = $this.text().trim();
            $this.attr('title', action);
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* DataTables custom styling to match Tailwind design */
.dataTables_wrapper {
    font-family: inherit;
}

.dataTables_filter {
    margin-bottom: 0;
}

.dataTables_length {
    margin-bottom: 0;
}

.dataTables_filter input,
.dataTables_length select {
    background-color: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
}

.dataTables_filter input:focus,
.dataTables_length select:focus {
    outline: none;
    ring: 2px;
    ring-color: #6366f1;
    border-color: #6366f1;
}

.dataTables_paginate .paginate_button {
    color: #374151;
    background: #ffffff;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    margin: 0 0.125rem;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.15s ease-in-out;
}

.dataTables_paginate .paginate_button:hover {
    background-color: #f9fafb;
    border-color: #9ca3af;
}

.dataTables_paginate .paginate_button.current {
    background-color: #4f46e5;
    color: #ffffff;
    border-color: #4f46e5;
}

.dataTables_paginate .paginate_button.current:hover {
    background-color: #4338ca;
    border-color: #4338ca;
}

.dataTables_paginate .paginate_button.disabled {
    color: #9ca3af;
    background: #ffffff;
    border-color: #d1d5db;
    opacity: 0.5;
    cursor: not-allowed;
}

.dataTables_info {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 0;
}

/* Table styling */
#topics-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

#topics-table thead th {
    background-color: #f9fafb;
    color: #374151;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 0.75rem 1.5rem;
    text-align: left;
    border-bottom: 1px solid #e5e7eb;
}

#topics-table tbody td {
    padding: 1rem 1.5rem;
    color: #374151;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}

#topics-table tbody tr:hover {
    background-color: #f9fafb;
}

#topics-table tbody tr:last-child td {
    border-bottom: none;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate {
        text-align: center;
    }

    #topics-table thead th,
    #topics-table tbody td {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
