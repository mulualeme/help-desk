<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class KnowledgeBaseCategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view kb category')->only(['index', 'show']);
        $this->middleware('permission:create kb category')->only(['create', 'store']);
        $this->middleware('permission:edit kb category')->only(['edit', 'update']);
        $this->middleware('permission:delete kb category')->only('destroy');
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KnowledgeBaseCategory::with(['articles' => function($query) use ($request) {
            $query->with('author');
            
            // Filter articles by title if provided
            if ($request->filled('title')) {
                $query->where('title', 'like', '%' . $request->input('title') . '%');
            }
            
            // Filter articles by published status
            if ($request->filled('status')) {
                if ($request->input('status') === 'published') {
                    $query->where('is_published', true);
                } elseif ($request->input('status') === 'draft') {
                    $query->where('is_published', false);
                }
            }
        }])
        ->orderBy('order');
        
        $categories = $query->paginate(10)
            ->withQueryString();
        
        // Filter out categories with no matching articles if filters are applied
        if ($request->filled('title') || $request->filled('status')) {
            $categories->setCollection(
                $categories->getCollection()->filter(function ($category) {
                    return $category->articles->count() > 0;
                })
            );
        }

        return Inertia::render('KnowledgeBase/Index', [
            'categories' => $categories,
            'filters' => $request->only(['title', 'status']),
            'can' => [
                'createCategory' => auth()->user()->can('create kb category'),
                'createArticle' => auth()->user()->can('create kb article'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('KnowledgeBase/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        KnowledgeBaseCategory::create($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeBaseCategory $category)
    {
        $category->load(['articles' => function($query) {
            $query->with('author');
        }]);
        
        return Inertia::render('KnowledgeBase/Categories/Show', [
            'category' => $category,
            'can' => [
                'update' => auth()->user()->can('edit kb category'),
                'delete' => auth()->user()->can('delete kb category'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeBaseCategory $category)
    {
        return Inertia::render('KnowledgeBase/Categories/Edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KnowledgeBaseCategory $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'integer',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        
        $category->update($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeBaseCategory $category)
    {
        $category->delete();

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Category deleted successfully.');
    }
} 