@extends('admin.layout')

@section('title', 'View Mock Exam: ' . $mockExam->name)

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $mockExam->name }}</h1>
                    <p class="mt-1 text-sm text-gray-600">{{ $mockExam->short_name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.mock-exams.edit', $mockExam) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Exam
                    </a>
                    <a href="{{ route('admin.mock-exams.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Exam Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Exam Information -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Exam Information</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Short Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->short_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Category</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 bg-gray-100 rounded-full text-xs font-medium">{{ $mockExam->category }}</span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Mode</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($mockExam->mode) }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Status</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mockExam->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <i class="fas fa-circle mr-1 text-xs {{ $mockExam->status ? 'text-green-400' : 'text-red-400' }}"></i>
                                    {{ $mockExam->status ? 'Active' : 'Inactive' }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">UI Template</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->ui_template ? ucfirst($mockExam->ui_template) : 'Default' }}</dd>
                        </div>
                        @if($mockExam->slug)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Slug</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->slug }}</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Created</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->created_at->format('M d, Y H:i') }}</dd>
                        </div>
                    </dl>

                    @if($mockExam->description)
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500">Description</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $mockExam->description }}</dd>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Exam Papers -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Exam Papers</h3>
                        <a href="{{ route('admin.mock-exam-papers.create', ['exam' => $mockExam->id]) }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-600 bg-indigo-50 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-plus mr-2"></i>
                            Add Paper
                        </a>
                    </div>
                </div>
                <div class="px-6 py-4">
                    @if($mockExam->papers->count() > 0)
                        <div class="space-y-4">
                            @foreach($mockExam->papers as $paper)
                            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-alt text-indigo-600"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-900">{{ $paper->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $paper->total_questions ?? 0 }} questions • {{ $paper->duration_minutes ?? 0 }} minutes</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.mock-exam-papers.show', $paper) }}" class="inline-flex items-center p-2 border border-transparent text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.mock-exam-papers.edit', $paper) }}" class="inline-flex items-center p-2 border border-transparent text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Papers Found</h3>
                            <p class="text-gray-500 mb-6">This exam doesn't have any papers yet.</p>
                            <a href="{{ route('admin.mock-exam-papers.create', ['exam' => $mockExam->id]) }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <i class="fas fa-plus mr-2"></i>
                                Create First Paper
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Quick Stats</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Papers</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $mockExam->papers->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Total Questions</span>
                        <span class="text-lg font-semibold text-gray-900">{{ $mockExam->papers->sum('questions_count') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $mockExam->updated_at ? $mockExam->updated_at->diffForHumans() : 'Never' }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Actions</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    <form action="{{ route('admin.mock-exams.toggle-status', $mockExam) }}" method="POST" class="inline-block w-full">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md {{ $mockExam->status ? 'text-yellow-600 bg-yellow-50 hover:bg-yellow-100' : 'text-green-600 bg-green-50 hover:bg-green-100' }} focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <i class="fas {{ $mockExam->status ? 'fa-toggle-on' : 'fa-toggle-off' }} mr-2"></i>
                            {{ $mockExam->status ? 'Deactivate' : 'Activate' }} Exam
                        </button>
                    </form>

                    <button onclick="confirmDelete()" class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-red-600 bg-red-50 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Exam
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this exam? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.mock-exams.destroy", $mockExam) }}';

        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';

        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';

        form.appendChild(csrf);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
