<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryManagementController extends Controller
{
    public function index()
    {
        $categories = Category::with('subcategories')->withCount('subcategories')->get();
        $subcategories = Subcategory::with('category')->get();

        return view('admin.category-management.index', compact('categories', 'subcategories'));
    }

    public function create()
    {
        return view('admin.category-management.create');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $request->name,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('admin.category-management.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load('subcategories');
        return view('admin.category-management.show', compact('category'));
    }

    public function edit(Category $category)
    {
        return view('admin.category-management.edit', compact('category'));
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $request->name,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('admin.category-management.index')->with('success', 'Category updated successfully.');
    }

    public function destroyCategory(Category $category)
    {
        if ($category->subcategories()->count() > 0) {
            return redirect()->route('admin.category-management.index')->with('error', 'Cannot delete category with existing subcategories.');
        }

        $category->delete();
        return redirect()->route('admin.category-management.index')->with('success', 'Category deleted successfully.');
    }

    // Subcategory methods
    public function createSubcategory(Category $category)
    {
        return view('admin.category-management.create-subcategory', compact('category'));
    }

    public function storeSubcategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name',
            'category_id' => 'required|exists:categories,id',
        ]);

        Subcategory::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('admin.category-management.index')->with('success', 'Subcategory created successfully.');
    }

    public function editSubcategory(Subcategory $subcategory)
    {
        $categories = Category::all();
        return view('admin.category-management.edit-subcategory', compact('subcategory', 'categories'));
    }

    public function updateSubcategory(Request $request, Subcategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:subcategories,name,' . $subcategory->id,
            'category_id' => 'required|exists:categories,id',
        ]);

        $subcategory->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'admin_id' => Auth::guard('admin')->user()->id,
        ]);

        return redirect()->route('admin.category-management.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroySubcategory(Subcategory $subcategory)
    {
        $subcategory->delete();
        return redirect()->route('admin.category-management.index')->with('success', 'Subcategory deleted successfully.');
    }
}
