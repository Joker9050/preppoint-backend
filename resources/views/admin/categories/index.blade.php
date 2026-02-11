@extends('admin.layout')

@section('title', 'Categories - Admin Panel')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">Categories</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Categories</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage categories for organizing content</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Category
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-tags text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Total Categories</dt>
                            <dd class="text-3xl font-bold">{{ $categories->total() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-list text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Total Subcategories</dt>
                            <dd class="text-3xl font-bold">{{ $categories->sum(function($category) { return $category->subcategories->count(); }) }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-book text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Total Subjects</dt>
                            <dd class="text-3xl font-bold">{{ $categories->sum(function($category) { return $category->subcategories->sum(function($subcategory) { return $subcategory->subjects->count(); }); }) }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTable Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <!-- Table Header with Enhanced Controls -->
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 via-white to-blue-50">
            <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <i class="fas fa-table text-indigo-600 text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Categories Management</h3>
                            <p class="text-sm text-gray-600">Advanced data table with search, sort, and export capabilities</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="flex items-center space-x-3">
                        <button id="refresh-btn" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i>
                            Refresh Data
                        </button>
                        <button id="export-btn" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-indigo-600 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 shadow-sm">
                            <i class="fas fa-download mr-2"></i>
                            Export CSV
                        </button>
                    </div>
                    <div class="flex items-center space-x-4 text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            <span>Last updated: <span id="last-updated" class="font-semibold text-gray-900">{{ now()->format('M j, Y g:i A') }}</span></span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-database mr-2 text-gray-400"></i>
                            <span>Total: <span class="font-bold text-indigo-600">{{ $categories->total() }}</span> categories</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable Table -->
        <div class="overflow-x-auto">
            <table id="categories-table" class="w-full min-w-[1000px] text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-indigo-500 rounded-full"></div>
                                <span>ID</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span>Category Details</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>Subcategories</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span>Subjects</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                <span>Created</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="bg-white hover:bg-indigo-50/30 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-800 text-xs font-medium rounded-full">
                                    {{ $category->id }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tag text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-indigo-700 transition-colors">
                                        {{ $category->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-green-100 text-green-800 text-xs font-medium rounded-full mr-2">
                                    {{ $category->subcategories->count() }}
                                </span>
                                <span class="text-sm text-gray-600">{{ Str::plural('subcategory', $category->subcategories->count()) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-purple-100 text-purple-800 text-xs font-medium rounded-full mr-2">
                                    {{ $category->subcategories->sum(function($subcategory) { return $subcategory->subjects->count(); }) }}
                                </span>
                                <span class="text-sm text-gray-600">{{ Str::plural('subject', $category->subcategories->sum(function($subcategory) { return $subcategory->subjects->count(); })) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span>{{ $category->created_at->format('M j, Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $category->created_at->format('g:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('admin.categories.show', $category) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-800 transition-all duration-150">
                                    View
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-800 transition-all duration-150">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category? This will also delete all associated subcategories and subjects. This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-800 transition-all duration-150">
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
                                    <i class="fas fa-tags text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No categories found</h3>
                                <p class="text-gray-500 text-sm mb-4">Get started by creating your first category.</p>
                                <a href="{{ route('admin.categories.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add First Category
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
    const table = $('#categories-table').DataTable({
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
            lengthMenu: "_MENU_",
            info: "",
            infoEmpty: "",
            infoFiltered: "",
            zeroRecords: "No matching records found",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6"<"flex items-center space-x-4"l><"flex items-center space-x-4"f>>rt<"flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6"<"flex items-center text-sm text-gray-600"i><"flex items-center space-x-2"p>>',
        initComplete: function() {
            // Enhanced search input styling
            $('.dataTables_filter').addClass('relative');
            $('.dataTables_filter input').attr('placeholder', 'Search categories...');

            // Enhanced length select styling - show dropdown with label
            $('.dataTables_length select').addClass('px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm bg-white shadow-sm transition-all duration-200 min-w-32');
            $('.dataTables_length label').addClass('flex items-center space-x-2 text-sm font-medium text-gray-700 ml-6');

            // Enhanced pagination styling
            $('.dataTables_paginate').addClass('flex items-center space-x-1');
            $('.dataTables_paginate .paginate_button').addClass('px-4 py-2 mx-1 border border-gray-300 rounded-xl hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm font-medium transition-all duration-200 shadow-sm');
            $('.dataTables_paginate .paginate_button.current').addClass('bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700 shadow-md');
            $('.dataTables_paginate .paginate_button.disabled').addClass('opacity-50 cursor-not-allowed bg-gray-100 text-gray-400');
            $('.dataTables_paginate .paginate_button:not(.current):not(.disabled)').addClass('text-gray-700 hover:text-indigo-600');

            // Enhanced info styling
            $('.dataTables_info').addClass('text-sm text-gray-600 font-medium');
            $('.dataTables_info').prepend('<i class="fas fa-info-circle text-indigo-500 mr-2"></i>');

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
            $('#categories-table tbody tr').hover(
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
    $('#categories-table').on('mouseenter', '.group/btn', function() {
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
#categories-table {
    width: 100%;
    border-collapse: collapse;
    background-color: #ffffff;
    border-radius: 0.5rem;
    overflow: hidden;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

#categories-table thead th {
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

#categories-table tbody td {
    padding: 1rem 1.5rem;
    color: #374151;
    font-size: 0.875rem;
    border-bottom: 1px solid #f3f4f6;
}

#categories-table tbody tr:hover {
    background-color: #f9fafb;
}

#categories-table tbody tr:last-child td {
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

    #categories-table thead th,
    #categories-table tbody td {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
}
</style>
@endpush
