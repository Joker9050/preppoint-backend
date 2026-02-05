@extends('admin.layout')

@section('title', 'Mock Exams')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Mock Exams</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage and organize mock examination papers</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exams.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Exam
                    </a>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label for="category" class="text-sm font-medium text-gray-700">Category:</label>
                    <select name="category" id="category" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Categories</option>
                        <option value="SSC" {{ request('category') == 'SSC' ? 'selected' : '' }}>SSC</option>
                        <option value="Banking" {{ request('category') == 'Banking' ? 'selected' : '' }}>Banking</option>
                        <option value="Railway" {{ request('category') == 'Railway' ? 'selected' : '' }}>Railway</option>
                        <option value="Defence" {{ request('category') == 'Defence' ? 'selected' : '' }}>Defence</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label for="status" class="text-sm font-medium text-gray-700">Status:</label>
                    <select name="status" id="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        <option value="">All Status</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-center space-x-2">
                    <label for="search" class="text-sm font-medium text-gray-700">Search:</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search exams..." class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                </div>

                <div class="flex items-center space-x-2">
                    <button type="submit" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-filter mr-2"></i>
                        Filter
                    </button>
                    <a href="{{ route('admin.mock-exams.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-times mr-2"></i>
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-graduation-cap text-3xl text-indigo-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Exams</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $exams->total() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Exams</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $exams->where('status', 1)->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-alt text-3xl text-blue-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Papers</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $exams->sum(fn($exam) => $exam->papers()->count()) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tags text-3xl text-purple-500"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Categories</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $exams->pluck('category')->unique()->count() }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Exams Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($exams as $exam)
            <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $exam->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $exam->short_name }}</p>
                                </div>
                            </div>

                            <div class="mt-4 space-y-2">
                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-tag mr-2 text-gray-400"></i>
                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-xs font-medium">{{ $exam->category }}</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-clock mr-2 text-gray-400"></i>
                                    <span>{{ ucfirst($exam->mode) }} Mode</span>
                                </div>

                                <div class="flex items-center text-sm text-gray-600">
                                    <i class="fas fa-file-alt mr-2 text-gray-400"></i>
                                    <span>{{ $exam->papers()->count() }} Papers</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $exam->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-circle mr-1 text-xs {{ $exam->status ? 'text-green-400' : 'text-red-400' }}"></i>
                                {{ $exam->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.mock-exams.show', $exam) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                View
                            </a>
                            <a href="{{ route('admin.mock-exams.edit', $exam) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                        </div>

                        <div class="flex items-center space-x-1">
                            <form action="{{ route('admin.mock-exams.toggle-status', $exam) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="inline-flex items-center p-2 border border-transparent text-sm font-medium rounded-md {{ $exam->status ? 'text-yellow-600 hover:text-yellow-500' : 'text-green-600 hover:text-green-500' }} hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors" title="{{ $exam->status ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas {{ $exam->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                </button>
                            </form>

                            <form action="{{ route('admin.mock-exams.destroy', $exam) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this exam? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center p-2 border border-transparent text-sm font-medium rounded-md text-red-600 hover:text-red-500 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-graduation-cap text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Mock Exams Found</h3>
                    <p class="text-gray-500 mb-6">Get started by creating your first mock examination.</p>
                    <a href="{{ route('admin.mock-exams.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Create New Exam
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($exams->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 rounded-lg shadow">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $exams->firstItem() }} to {{ $exams->lastItem() }} of {{ $exams->total() }} results
                </div>
                <div class="flex-1 flex justify-end">
                    {{ $exams->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
