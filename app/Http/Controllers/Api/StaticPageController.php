<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class StaticPageController extends Controller
{
    /**
     * Get a specific static page by slug
     *
     * @param string $slug
     * @return JsonResponse
     */
    public function show($slug): JsonResponse
    {
        try {
            $page = StaticPage::findBySlug($slug);

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
                    'slug' => $page->slug,
                    'title' => $page->title,
                    'content' => $page->content,
                    'is_active' => $page->is_active,
                    'created_at' => $page->created_at->toISOString(),
                    'updated_at' => $page->updated_at->toISOString()
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('Static page fetch error: ' . $e->getMessage(), [
                'slug' => $slug
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching the page. Please try again later.'
            ], 500);
        }
    }
}
