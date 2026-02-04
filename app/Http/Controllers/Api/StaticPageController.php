<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StaticPageController extends Controller
{
    /**
     * Get all static pages
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $pages = StaticPage::orderBy('title')->get();

            return response()->json([
                'success' => true,
                'data' => $pages->map(function ($page) {
                    return [
                        'id' => $page->id,
                        'page_name' => $page->page_name,
                        'title' => $page->title,
                        'admin_id' => $page->admin_id,
                        'updated_at' => $page->updated_at ? $page->updated_at->toDateTimeString() : null
                    ];
                }) // Fixed: Added closing parenthesis for map()
            ]); // Fixed: Added closing bracket for response array

        } catch (\Exception $e) {
            \Log::error('Static pages fetch error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching pages. Please try again later.'
            ], 500);
        }
    }

    /**
     * Get a specific static page by page_name
     *
     * @param string $pageName
     * @return JsonResponse
     */
    public function show($pageName): JsonResponse
    {
        try {
            // Fixed: Changed to use where() clause instead of potentially non-existent findByPageName()
            $page = StaticPage::where('page_name', $pageName)->first();

            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $page->id,
                    'page_name' => $page->page_name,
                    'title' => $page->title,
                    'content' => $page->content,
                    'admin_id' => $page->admin_id,
                    'updated_at' => $page->updated_at ? $page->updated_at->toDateTimeString() : null
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Static page fetch error: ' . $e->getMessage(), [
                'page_name' => $pageName
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the page. Please try again later.'
            ], 500);
        }
    }
}