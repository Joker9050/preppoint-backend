@extends('admin.layout')

@section('title', 'Create Job Update')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Create Job Update</h1>
            <p class="text-gray-600">Add a new job notification</p>
        </div>
        <a href="{{ route('admin.job-updates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-medium">
            <i class="fas fa-arrow-left mr-2"></i>Back to List
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('admin.job-updates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700">Job Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                               placeholder="e.g., SSC CGL 2024 Recruitment">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="organization" class="block text-sm font-medium text-gray-700">Organization <span class="text-red-500">*</span></label>
                        <input type="text" id="organization" name="organization" value="{{ old('organization') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('organization') border-red-500 @enderror"
                               placeholder="e.g., Staff Selection Commission">
                        @error('organization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                        <select id="category" name="category"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror">
                            <option value="">Select Category</option>
                            <option value="govt" {{ old('category') == 'govt' ? 'selected' : '' }}>Government</option>
                            <option value="private" {{ old('category') == 'private' ? 'selected' : '' }}>Private</option>
                            <option value="bank" {{ old('category') == 'bank' ? 'selected' : '' }}>Banking</option>
                            <option value="it" {{ old('category') == 'it' ? 'selected' : '' }}>IT</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">SEO Slug</label>
                        <input type="text" id="slug" name="slug" value="{{ old('slug') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                               placeholder="Auto-generated from title">
                        <p class="mt-1 text-sm text-gray-500">Leave empty to auto-generate from title</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Job Description <span class="text-red-500">*</span></label>
                    <textarea id="description" name="description" rows="6"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              placeholder="Detailed job description, requirements, eligibility criteria, etc.">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Important Dates -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Important Dates</h3>
                <div id="dates-container">
                    <!-- Dynamic date fields will be added here -->
                </div>
                <button type="button" id="add-date-btn" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-plus mr-2"></i>Add Date
                </button>
            </div>

            <!-- URLs -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Important Links</h3>
                <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                    <div>
                        <label for="apply_url" class="block text-sm font-medium text-gray-700">Apply Now URL</label>
                        <input type="url" id="apply_url" name="apply_url" value="{{ old('apply_url') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('apply_url') border-red-500 @enderror"
                               placeholder="https://example.com/apply">
                        @error('apply_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="admit_card_url" class="block text-sm font-medium text-gray-700">Admit Card URL</label>
                        <input type="url" id="admit_card_url" name="admit_card_url" value="{{ old('admit_card_url') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('admit_card_url') border-red-500 @enderror"
                               placeholder="https://example.com/admit-card">
                        @error('admit_card_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="result_url" class="block text-sm font-medium text-gray-700">Result URL</label>
                        <input type="url" id="result_url" name="result_url" value="{{ old('result_url') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('result_url') border-red-500 @enderror"
                               placeholder="https://example.com/result">
                        @error('result_url')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Status</h3>
                <div class="space-y-4">
                    <div>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="draft" {{ old('status', 'draft') == 'draft' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2">Draft</span>
                        </label>
                    </div>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="radio" name="status" value="published" {{ old('status') == 'published' ? 'checked' : '' }}
                                   class="form-radio h-4 w-4 text-blue-600">
                            <span class="ml-2">Publish Now</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.job-updates.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md font-medium">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                    <i class="fas fa-save mr-2"></i>Create Job Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let dateIndex = 0;

function addDateField() {
    const container = document.getElementById('dates-container');
    const dateField = document.createElement('div');
    dateField.className = 'date-field flex space-x-4 items-end mb-4';
    dateField.innerHTML = `
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Date Label</label>
            <input type="text" name="important_dates[${dateIndex}][label]"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                   placeholder="e.g., Application Start Date">
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium text-gray-700">Date</label>
            <input type="date" name="important_dates[${dateIndex}][date]"
                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <button type="button" onclick="removeDateField(this)" class="bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-md">
            <i class="fas fa-trash"></i>
        </button>
    `;
    container.appendChild(dateField);
    dateIndex++;
}

function removeDateField(button) {
    button.closest('.date-field').remove();
}

document.getElementById('add-date-btn').addEventListener('click', addDateField);

// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-')
        .trim('-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
