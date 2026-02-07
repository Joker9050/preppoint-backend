@extends('admin.layout')

@section('title', 'Mock Exam Papers')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mock Exam Papers</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage and organize your mock exam papers</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exam-papers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Paper
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700 mb-1">Exam</label>
                    <select id="exam_id" name="exam_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Exams</option>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" {{ request('exam_id') == $exam->id ? 'selected' : '' }}>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="paper_type" class="block text-sm font-medium text-gray-700 mb-1">Paper Type</label>
                    <select id="paper_type" name="paper_type" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Types</option>
                        <option value="mock" {{ request('paper_type') == 'mock' ? 'selected' : '' }}>Mock</option>
                        <option value="pyq" {{ request('paper_type') == 'pyq' ? 'selected' : '' }}>PYQ</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                    </select>
                </div>

                <div class="flex items-end space-x-2">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.mock-exam-papers.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Papers Grid -->
    @if($papers->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($papers as $paper)
                <div class="bg-white shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                    <!-- Card Header -->
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $paper->title }}</h3>
                                <p class="text-sm text-gray-600">{{ $paper->exam->name ?? 'N/A' }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($paper->paper_type == 'mock') bg-blue-100 text-blue-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ strtoupper($paper->paper_type) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($paper->status == 'live') bg-green-100 text-green-800
                                    @elseif($paper->status == 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($paper->status) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-6 py-4">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-question-circle text-xl text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Questions</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $paper->total_questions }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-clock text-xl text-orange-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Duration</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $paper->duration_minutes }}m</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-trophy text-xl text-green-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Marks</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $paper->total_marks }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-3">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-calendar text-xl text-purple-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-gray-500">Year</p>
                                        <p class="text-sm font-semibold text-gray-900">{{ $paper->year ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($paper->year || $paper->shift)
                            <div class="mb-4 text-sm text-gray-600">
                                @if($paper->year)<span class="font-medium">Year:</span> {{ $paper->year }}@endif
                                @if($paper->shift) @if($paper->year) • @endif <span class="font-medium">Shift:</span> {{ $paper->shift }}@endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.mock-exam-papers.show', $paper) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <i class="fas fa-eye mr-2"></i>
                                    View
                                </a>
                                <a href="{{ route('admin.mock-exam-papers.edit', $paper) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-orange-600 bg-orange-50 hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit
                                </a>
                            </div>

                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.mock-exam-papers.preview', $paper) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    <i class="fas fa-search mr-2"></i>
                                    Preview
                                </a>

                                <div class="relative">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="menu-button-{{ $paper->id }}" aria-expanded="false" aria-haspopup="true">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>

                                    <div class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $paper->id }}" id="menu-{{ $paper->id }}">
                                        <div class="py-1" role="none">
                                            <form action="{{ route('admin.mock-exam-papers.toggle-status', $paper) }}" method="POST" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full text-left">
                                                    <i class="fas fa-toggle-on mr-2"></i>
                                                    {{ $paper->status == 'live' ? 'Make Draft' : 'Make Live' }}
                                                </button>
                                            </form>
                                            <div class="border-t border-gray-100"></div>
                                            <form action="{{ route('admin.mock-exam-papers.destroy', $paper) }}" method="POST" class="block px-4 py-2 text-sm text-red-700 hover:bg-red-50 hover:text-red-900" role="menuitem" onsubmit="return confirm('Are you sure you want to delete this paper?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left">
                                                    <i class="fas fa-trash mr-2"></i>
                                                    Delete Paper
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $papers->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-12 text-center">
                <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Papers Found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first mock exam paper.</p>
                <a href="{{ route('admin.mock-exam-papers.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-plus mr-2"></i>
                    Create First Paper
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown menu functionality
    document.querySelectorAll('[id^="menu-button-"]').forEach(button => {
        button.addEventListener('click', function() {
            const menuId = this.id.replace('menu-button-', 'menu-');
            const menu = document.getElementById(menuId);

            // Close all other menus
            document.querySelectorAll('[id^="menu-"]').forEach(m => {
                if (m.id !== menuId) {
                    m.classList.add('hidden');
                }
            });

            // Toggle current menu
            menu.classList.toggle('hidden');
        });
    });

    // Close menus when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('[id^="menu-button-"]') && !event.target.closest('[id^="menu-"]')) {
            document.querySelectorAll('[id^="menu-"]').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });
});
</script>
@endsection
