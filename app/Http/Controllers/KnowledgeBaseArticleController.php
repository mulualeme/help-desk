<?php

namespace App\Http\Controllers;

use App\Models\KnowledgeBaseArticle;
use App\Models\KnowledgeBaseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class KnowledgeBaseArticleController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view kb article')->only(['index', 'show']);
        $this->middleware('permission:create kb article')->only(['create', 'store']);
        $this->middleware('permission:edit kb article')->only(['edit', 'update']);
        $this->middleware('permission:delete kb article')->only('destroy');
        $this->middleware('permission:publish kb article')->only(['publish']);
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = KnowledgeBaseArticle::with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return Inertia::render('KnowledgeBase/Articles/Index', [
            'articles' => $articles,
            'can' => [
                'create' => auth()->user()->can('create kb article'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $categories = KnowledgeBaseCategory::orderBy('name')
            ->get();
            
        return Inertia::render('KnowledgeBase/Articles/Create', [
            'categories' => $categories,
            'preselectedCategory' => $request->input('category_id') ? 
                intval($request->input('category_id')) : null,
            'canPublish' => auth()->user()->can('publish kb article'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:knowledge_base_categories,id',
            'is_published' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['author_id'] = auth()->id();
        
        // Only allow users with publishing permission to publish articles
        if (!auth()->user()->can('publish kb article')) {
            $validated['is_published'] = false;
            $validated['published_at'] = null;
        } else if ($validated['is_published'] && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        KnowledgeBaseArticle::create($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(KnowledgeBaseArticle $article)
    {
        $article->load(['category', 'author']);
        $relatedArticles = KnowledgeBaseArticle::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->where('is_published', true)
            ->limit(5)
            ->get();
            
        return Inertia::render('KnowledgeBase/Articles/Show', [
            'article' => $article,
            'relatedArticles' => $relatedArticles,
            'can' => [
                'update' => auth()->user()->can('edit kb article'),
                'delete' => auth()->user()->can('delete kb article'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KnowledgeBaseArticle $article)
    {
        $categories = KnowledgeBaseCategory::orderBy('name')
            ->get();
            
        return Inertia::render('KnowledgeBase/Articles/Edit', [
            'article' => $article,
            'categories' => $categories,
            'canPublish' => auth()->user()->can('publish kb article'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, KnowledgeBaseArticle $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category_id' => 'required|exists:knowledge_base_categories,id',
            'is_published' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        
        // Only allow users with publishing permission to publish articles
        if (!auth()->user()->can('publish kb article')) {
            // Maintain the current publish status if already published by someone with permission
            if (!$article->is_published) {
                $validated['is_published'] = false;
                $validated['published_at'] = null;
            }
        } else {
            // Set published_at timestamp when article is first published
            if ($validated['is_published'] && !$article->is_published) {
                $validated['published_at'] = now();
            } elseif (!$validated['is_published']) {
                $validated['published_at'] = null;
            }
        }

        $article->update($validated);

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KnowledgeBaseArticle $article)
    {
        $article->delete();

        return redirect()->route('knowledge-base.index')
            ->with('success', 'Article deleted successfully.');
    }
} 