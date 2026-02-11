@extends('admin.layout')

@section('title', 'Category Management')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Category Management</h1>

    <!-- Categories Section -->
    <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold text-gray-800">Categories</h2>
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openCategoryModal()">Add Category</button>
                </div>
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
                @endif
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Subcategories Count</th>
                    <th class="px-4 py-2">Created At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td class="border px-4 py-2">{{ $category->name }}</td>
                    <td class="border px-4 py-2">{{ $category->subcategories_count }}</td>
                    <td class="border px-4 py-2">{{ $category->created_at->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2">
                        <button class="text-blue-500 hover:text-blue-700" onclick="editCategory({{ $category->id }}, '{{ $category->name }}')">Edit</button>
                        <form method="POST" action="{{ route('admin.category-management.categories.destroy', $category) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Subcategories Section -->
    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Subcategories</h2>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="openSubcategoryModal()">Add Subcategory</button>
        </div>
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Category</th>
                    <th class="px-4 py-2">Created At</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subcategories as $subcategory)
                <tr>
                    <td class="border px-4 py-2">{{ $subcategory->name }}</td>
                    <td class="border px-4 py-2">{{ $subcategory->category->name }}</td>
                    <td class="border px-4 py-2">{{ $subcategory->created_at->format('Y-m-d') }}</td>
                    <td class="border px-4 py-2">
                        <button class="text-blue-500 hover:text-blue-700" onclick="editSubcategory({{ $subcategory->id }}, '{{ $subcategory->name }}', {{ $subcategory->category_id }})">Edit</button>
                        <form method="POST" action="{{ route('admin.category-management.subcategories.destroy', $subcategory) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700 ml-2">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Category Modal -->
<div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900" id="categoryModalTitle">Add Category</h3>
            <form id="categoryForm" method="POST" class="mt-4">
                @csrf
                <input type="hidden" id="categoryId" name="category_id">
                <div class="mb-4">
                    <label for="categoryName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="categoryName" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeCategoryModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Subcategory Modal -->
<div id="subcategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900" id="subcategoryModalTitle">Add Subcategory</h3>
            <form id="subcategoryForm" method="POST" class="mt-4">
                @csrf
                <input type="hidden" id="subcategoryId" name="subcategory_id">
                <div class="mb-4">
                    <label for="subcategoryName" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" id="subcategoryName" name="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                </div>
                <div class="mb-4">
                    <label for="categorySelect" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="categorySelect" name="category_id" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center justify-between">
                    <button type="button" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeSubcategoryModal()">Cancel</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openCategoryModal() {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryForm').action = '{{ route("admin.category-management.categories.store") }}';
    document.getElementById('categoryModalTitle').textContent = 'Add Category';
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryName').value = '';
}

function editCategory(id, name) {
    document.getElementById('categoryModal').classList.remove('hidden');
    document.getElementById('categoryForm').action = '{{ route("admin.category-management.categories.update", ":id") }}'.replace(':id', id);
    document.getElementById('categoryForm').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PATCH">');
    document.getElementById('categoryModalTitle').textContent = 'Edit Category';
    document.getElementById('categoryId').value = id;
    document.getElementById('categoryName').value = name;
}

function closeCategoryModal() {
    document.getElementById('categoryModal').classList.add('hidden');
}

function openSubcategoryModal() {
    document.getElementById('subcategoryModal').classList.remove('hidden');
    document.getElementById('subcategoryForm').action = '{{ route("admin.category-management.subcategories.store") }}';
    document.getElementById('subcategoryModalTitle').textContent = 'Add Subcategory';
    document.getElementById('subcategoryId').value = '';
    document.getElementById('subcategoryName').value = '';
    document.getElementById('categorySelect').value = '';
}

function editSubcategory(id, name, categoryId) {
    document.getElementById('subcategoryModal').classList.remove('hidden');
    document.getElementById('subcategoryForm').action = '{{ route("admin.category-management.subcategories.update", ":id") }}'.replace(':id', id);
    document.getElementById('subcategoryForm').insertAdjacentHTML('afterbegin', '<input type="hidden" name="_method" value="PATCH">');
    document.getElementById('subcategoryModalTitle').textContent = 'Edit Subcategory';
    document.getElementById('subcategoryId').value = id;
    document.getElementById('subcategoryName').value = name;
    document.getElementById('categorySelect').value = categoryId;
}

function closeSubcategoryModal() {
    document.getElementById('subcategoryModal').classList.add('hidden');
}
</script>
@endsection
