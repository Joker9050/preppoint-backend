@extends('admin.layout')

@section('title', 'Job Update Details')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $jobUpdate->title }}</h1>
            <p class="text-gray-600">Job update details and information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.job-updates.edit', $jobUpdate) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
            <a href="{{ route('admin.job-updates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Status and Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                {!! $jobUpdate->status_badge !!}
                {!! $jobUpdate->category_badge !!}
                @if($jobUpdate->published_at)
                    <span class="text-sm text-gray-500">Published: {{ $jobUpdate->published_at->format('M d, Y H:i') }}</span>
                @endif
            </div>
            <div class="flex space-x-2">
                @if($jobUpdate->status !== 'published')
                    <form action="{{ route('admin.job-updates.toggle-status', $jobUpdate) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="published">
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm font-medium">
                            Publish
                        </button>
                    </form>
                @endif
                @if($jobUpdate->status !== 'draft')
                    <form action="{{ route('admin.job-updates.toggle-status', $jobUpdate) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="draft">
                        <button type="submit" class="bg-yellow-600 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm font-medium">
                            Draft
                        </button>
                    </form>
                @endif
                @if($jobUpdate->status !== 'archived')
                    <form action="{{ route('admin.job-updates.toggle-status', $jobUpdate) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="archived">
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded text-sm font-medium">
                            Archive
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Job Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Job Title</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->title }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Organization</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->organization }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Category</dt>
                        <dd class="mt-1">{!! $jobUpdate->category_badge !!}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">SEO Slug</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->slug }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Description -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                <div class="prose max-w-none">
                    {!! nl2br(e($jobUpdate->description)) !!}
                </div>
            </div>

            <!-- Important Dates -->
            @if($jobUpdate->important_dates && count($jobUpdate->important_dates) > 0)
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                <div class="space-y-3">
                    @foreach($jobUpdate->important_dates as $date)
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="font-medium text-gray-900">{{ $date['label'] ?? 'Date' }}</span>
                        <span class="text-gray-600">{{ $date['date'] ? \Carbon\Carbon::parse($date['date'])->format('M d, Y') : 'Not specified' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Important Links -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Important Links</h3>
                <div class="space-y-3">
                    @if($jobUpdate->apply_url)
                    <div>
                        <a href="{{ $jobUpdate->apply_url }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Apply Now
                        </a>
                    </div>
                    @endif

                    @if($jobUpdate->admit_card_url)
                    <div>
                        <a href="{{ $jobUpdate->admit_card_url }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Admit Card
                        </a>
                    </div>
                    @endif

                    @if($jobUpdate->result_url)
                    <div>
                        <a href="{{ $jobUpdate->result_url }}" target="_blank" class="flex items-center text-blue-600 hover:text-blue-800">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Result
                        </a>
                    </div>
                    @endif

                    @if(!$jobUpdate->apply_url && !$jobUpdate->admit_card_url && !$jobUpdate->result_url)
                    <p class="text-gray-500 text-sm">No links available</p>
                    @endif
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Metadata</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->created_at->format('M d, Y H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->updated_at->format('M d, Y H:i') }}</dd>
                    </div>
                    @if($jobUpdate->admin)
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created By</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $jobUpdate->admin->name ?? 'Unknown' }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.job-updates.edit', $jobUpdate) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium text-center block">
                        <i class="fas fa-edit mr-2"></i>Edit Job Update
                    </a>
                    <form action="{{ route('admin.job-updates.destroy', $jobUpdate) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job update?')" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md font-medium">
                            <i class="fas fa-trash mr-2"></i>Delete Job Update
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
