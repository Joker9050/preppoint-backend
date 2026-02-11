@extends('admin.layout')

@section('title', 'Category Management - Admin Panel')

@section('breadcrumb')
<li class="flex items-center">
    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
    <span class="text-gray-500">Category Management</span>
</li>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Category Management</h1>
                    <p class="mt-1 text-sm text-gray-600">Manage categories and subcategories for content organization</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="openCategoryModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Category
                    </button>
                    <button onclick="openSubcategoryModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <i class="fas fa-plus mr-2"></i>
                        Add Subcategory
                    </button>
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
                            <dd class="text-3xl font-bold">{{ $categories->count() }}</dd>
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
                            <dd class="text-3xl font-bold">{{ $subcategories->count() }}</dd>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-chart-line text-3xl"></i>
                        </div>
                        <div class="ml-4">
                            <dt class="text-sm font-medium opacity-90">Avg Subcategories</dt>
                            <dd class="text-3xl font-bold">{{ $categories->count() > 0 ? round($subcategories->count() / $categories->count(), 1) : 0 }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-indigo-50 via-white to-blue-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-indigo-100 rounded-lg">
                    <i class="fas fa-tags text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Categories</h3>
                    <p class="text-sm text-gray-600">Manage main categories</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-sm text-left">
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
                                <span>Category Name</span>
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
                                    {{ $category->subcategories_count }}
                                </span>
                                <span class="text-sm text-gray-600">{{ Str::plural('subcategory', $category->subcategories_count) }}</span>
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
                                <button onclick="editCategory({{ $category->id }}, '{{ $category->name }}')"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-800 transition-all duration-150">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.category-management.categories.destroy', $category) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this category? This will also delete all associated subcategories.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-800 transition-all duration-150">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-tags text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No categories found</h3>
                                <p class="text-gray-500 text-sm mb-4">Get started by creating your first category.</p>
                                <button onclick="openCategoryModal()" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add First Category
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Subcategories Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-green-50 via-white to-emerald-50">
            <div class="flex items-center space-x-3">
                <div class="p-2 bg-green-100 rounded-lg">
                    <i class="fas fa-list text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Subcategories</h3>
                    <p class="text-sm text-gray-600">Manage subcategories within categories</p>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-sm text-left">
                <thead class="text-xs text-gray-700 uppercase bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span>ID</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <span>Subcategory Name</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 font-bold text-gray-900">
                            <div class="flex items-center space-x-2">
                                <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                                <span>Parent Category</span>
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
                    @forelse($subcategories as $subcategory)
                    <tr class="bg-white hover:bg-green-50/30 transition-colors duration-150 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-green-100 text-green-800 text-xs font-medium rounded-full">
                                    {{ $subcategory->id }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-list text-white text-sm"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 group-hover:text-green-700 transition-colors">
                                        {{ $subcategory->name }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $subcategory->category->name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span>{{ $subcategory->created_at->format('M j, Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $subcategory->created_at->format('g:i A') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center justify-end space-x-1">
                                <button onclick="editSubcategory({{ $subcategory->id }}, '{{ $subcategory->name }}', {{ $subcategory->category_id }})"
                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 hover:text-blue-800 transition-all duration-150">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <form action="{{ route('admin.category-management.subcategories.destroy', $subcategory) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Are you sure you want to delete this subcategory?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-50 border border-red-200 rounded-md hover:bg-red-100 hover:text-red-800 transition-all duration-150">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-list text-gray-400 text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-1">No subcategories found</h3>
                                <p class="text-gray-500 text-sm mb-4">Create categories first, then add subcategories.</p>
                                <button onclick="openSubcategoryModal()" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Add First Subcategory
                                </button>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
