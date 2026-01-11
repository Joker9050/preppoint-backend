@extends('admin.layout')

@section('title', 'Edit Scraped Draft')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Scraped Draft</h1>
            <p class="text-gray-600">Review and modify scraped content before approval</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.scraped-drafts.show', $scrapedDraft) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-eye mr-2"></i>View
            </a>
            <a href="{{ route('admin.scraped-drafts.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.scraped-drafts.update', $scrapedDraft) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $scrapedDraft->title) }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="Draft title">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Content</h3>
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700">Content <span class="text-red-500">*</span></label>
                    <textarea id="content" name="content" rows="15"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('content') border-red-500 @enderror"
                              placeholder="Scraped content...">{{ old('content', $scrapedDraft->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Source Information (Read-only) -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Source Information</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Source Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $scrapedDraft->source_name }}</dd>
                        </div>
                        @if($scrapedDraft->source_url)
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Source URL</dt>
                            <dd class="mt-1 text-sm text-blue-600">
                                <a href="{{ $scrapedDraft->source_url }}" target="_blank" class="hover:underline">
                                    {{ Str::limit($scrapedDraft->source_url, 50) }}
                                    <i class="fas fa-external-link-alt ml-1"></i>
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Scraped Data (Read-only) -->
            @if($scrapedDraft->scraped_data)
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Original Scraped Data</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <details class="cursor-pointer">
                        <summary class="text-sm font-medium text-gray-700 hover:text-gray-900">View Raw Data</summary>
                        <pre class="mt-2 text-xs text-gray-800 whitespace-pre-wrap overflow-x-auto">{{ json_encode($scrapedDraft->scraped_data, JSON_PRETTY_PRINT) }}</pre>
                    </details>
                </div>
            </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.scraped-drafts.show', $scrapedDraft) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-2"></i>Update Draft
                </button>
            </div>
        </form>
    </div>

    <!-- Change History -->
    @if($scrapedDraft->change_log && count($scrapedDraft->change_log) > 0)
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Change History</h3>
        <div class="space-y-4">
            @foreach(array_reverse($scrapedDraft->change_log) as $log)
            <div class="border-l-4 border-blue-400 pl-4">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($log['action']) }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($log['timestamp'])->format('M d, Y H:i') }}</div>
                </div>
                <div class="text-sm text-gray-600">by {{ $log['admin_name'] }}</div>
                @if($log['notes'])
                <div class="text-sm text-gray-500 mt-1">{{ $log['notes'] }}</div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<script>
// Auto-save functionality (optional)
let autoSaveTimeout;
function autoSave() {
    clearTimeout(autoSaveTimeout);
    autoSaveTimeout = setTimeout(() => {
        // Could implement auto-save here if needed
        console.log('Auto-save triggered');
    }, 30000); // 30 seconds
}

// Trigger auto-save on content changes
document.getElementById('content').addEventListener('input', autoSave);
document.getElementById('title').addEventListener('input', autoSave);
</script>
@endsection
