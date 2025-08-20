<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index()
    {
        $categories = Category::with(['children', 'parent'])
            ->active()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        $category->load(['children', 'parent', 'products']);

        return response()->json([
            'category' => $category,
        ]);
    }

    /**
     * Get root categories (no parent)
     */
    public function roots()
    {
        $categories = Category::with('children')
            ->active()
            ->root()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Get category tree structure
     */
    public function tree()
    {
        $categories = Category::with('children.children')
            ->active()
            ->root()
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }
}
