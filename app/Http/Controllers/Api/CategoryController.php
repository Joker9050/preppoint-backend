<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories with their subcategories and subjects.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $categories = Category::with([
                'subcategories.subjects' => function ($query) {
                    $query->orderBy('priority')->orderBy('name');
                }
            ])
            ->orderBy('id', 'asc')
            ->get()
            ->map(function ($category) {
                $subcategories = $category->subcategories->map(function ($subcategory) use ($category) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'category_id' => $subcategory->category_id,
                        'category_name' => $category->name,
                        'subjects_count' => $subcategory->subjects->count(),
                        'subjects' => $subcategory->subjects->map(function ($subject) use ($subcategory) {
                            return [
                                'id' => $subject->id,
                                'name' => $subject->name,
                                'icon_key' => $subject->icon_key,
                                'subcategory_id' => $subject->subcategory_id,
                                'subcategory_name' => $subcategory->name,
                                'priority' => $subject->priority,
                            ];
                        }),
                    ];
                });

                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'subcategories_count' => $subcategories->count(),
                    'subjects_count' => $subcategories->sum('subjects_count'),
                    'subcategories' => $subcategories,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve categories: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subcategories for a specific category.
     *
     * @param Category $category
     * @return JsonResponse
     */
    public function subcategories(Category $category): JsonResponse
    {
        try {
            $subcategories = $category->subcategories()
                ->with(['subjects' => function ($query) {
                    $query->orderBy('priority')->orderBy('name');
                }])
                ->get()
                ->map(function ($subcategory) {
                    return [
                        'id' => $subcategory->id,
                        'name' => $subcategory->name,
                        'category_id' => $subcategory->category_id,
                        'category_name' => $category->name,
                        'subjects_count' => $subcategory->subjects->count(),
                        'subjects' => $subcategory->subjects->map(function ($subject) {
                            return [
                                'id' => $subject->id,
                                'name' => $subject->name,
                                'icon_key' => $subject->icon_key,
                                'subcategory_id' => $subject->subcategory_id,
                                'subcategory_name' => $subcategory->name,
                                'priority' => $subject->priority,
                            ];
                        }),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $subcategories,
                'message' => 'Subcategories retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get subjects for a specific subcategory.
     *
     * @param Subcategory $subcategory
     * @return JsonResponse
     */
    public function subjects(Subcategory $subcategory): JsonResponse
    {
        try {
            $subjects = $subcategory->subjects()
                ->orderBy('priority')
                ->orderBy('name')
                ->get()
                ->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'icon_key' => $subject->icon_key,
                        'subcategory_id' => $subject->subcategory_id,
                        'subcategory_name' => $subcategory->name,
                        'category_id' => $subcategory->category_id,
                        'category_name' => $subcategory->category->name,
                        'priority' => $subject->priority,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'message' => 'Subjects retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all subjects ordered by priority for learning scroll.
     *
     * @return JsonResponse
     */
    public function learningScrollSubjects(): JsonResponse
    {
        try {
            $subjects = Subject::orderBy('priority', 'asc')->get(['id', 'name', 'icon_key', 'priority']);

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'message' => 'Learning scroll subjects retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve learning scroll subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all subjects with their subcategory and category information.
     *
     * @return JsonResponse
     */
    public function allSubjects(): JsonResponse
    {
        try {
            $subjects = Subject::with(['subcategory.category'])
                ->orderBy('priority', 'asc')
                ->orderBy('name', 'asc')
                ->get()
                ->map(function ($subject) {
                    return [
                        'id' => $subject->id,
                        'name' => $subject->name,
                        'icon_key' => $subject->icon_key,
                        'subcategory_id' => $subject->subcategory_id,
                        'subcategory_name' => $subject->subcategory->name,
                        'category_id' => $subject->subcategory->category->id,
                        'category_name' => $subject->subcategory->category->name,
                        'priority' => $subject->priority,
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $subjects,
                'message' => 'Subjects retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subjects',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
