<?php

namespace App\Http\Controllers;

use App\Models\Sla;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class SlaController extends Controller
{
    /**
     * Display a listing of the SLAs.
     */
    public function index(Request $request)
    {
        // Authorize the user
        $this->authorize('viewAny', Sla::class);

        // Get SLAs with customer and creator relationships
        $slas = Sla::with(['customer', 'creator'])
            ->when($request->input('search'), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->input('priority'), function ($query, $priority) {
                $query->where('priority', $priority);
            })
            ->when($request->input('status') === 'active', function ($query) {
                $query->where('is_active', true);
            })
            ->when($request->input('status') === 'inactive', function ($query) {
                $query->where('is_active', false);
            })
            ->orderBy($request->input('sort', 'created_at'), $request->input('direction', 'desc'))
            ->paginate(10)
            ->withQueryString();

        // Ensure formatted times are included in the response
        $slas->getCollection()->transform(function ($sla) {
            $sla->formatted_response_time = $sla->formatted_response_time;
            $sla->formatted_resolution_time = $sla->formatted_resolution_time;
            return $sla;
        });

        $user = auth()->user();

        return Inertia::render('Slas/Index', [
            'slas' => $slas,
            'filters' => $request->only(['search', 'priority', 'status', 'sort', 'direction']),
            'can' => [
                'createSla' => $user->hasPermissionTo('create sla'),
                'updateSla' => $user->hasPermissionTo('edit sla'),
                'deleteSla' => $user->hasPermissionTo('delete sla'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new SLA.
     */
    public function create()
    {
        // Authorize the user
        $this->authorize('create', Sla::class);

        // Get all customers for the dropdown
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        return Inertia::render('Slas/Create', [
            'customers' => $customers,
            'priorities' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    /**
     * Store a newly created SLA in storage.
     */
    public function store(Request $request)
    {
        // Authorize the user
        $this->authorize('create', Sla::class);

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'response_time_minutes' => 'required|integer|min:1',
            'resolution_time_minutes' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'is_active' => 'boolean',
        ]);

        // Add the created_by field
        $validated['created_by'] = Auth::id();

        // Create the SLA
        Sla::create($validated);

        return redirect()->route('slas.index')
            ->with('success', 'SLA created successfully.');
    }

    /**
     * Show the form for editing the specified SLA.
     */
    public function edit(Sla $sla)
    {
        // Authorize the user
        $this->authorize('update', $sla);

        // Load the customer relationship
        $sla->load('customer');

        // Get all customers for the dropdown
        $customers = User::whereHas('roles', function ($query) {
            $query->where('name', 'customer');
        })->get();

        return Inertia::render('Slas/Edit', [
            'sla' => $sla,
            'customers' => $customers,
            'priorities' => ['low', 'medium', 'high', 'urgent'],
        ]);
    }

    /**
     * Update the specified SLA in storage.
     */
    public function update(Request $request, Sla $sla)
    {
        // Authorize the user
        $this->authorize('update', $sla);

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'response_time_minutes' => 'required|integer|min:1',
            'resolution_time_minutes' => 'required|integer|min:1',
            'customer_id' => 'nullable|exists:users,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'is_active' => 'boolean',
        ]);

        // Update the SLA
        $sla->update($validated);

        return redirect()->route('slas.index')
            ->with('success', 'SLA updated successfully.');
    }

    /**
     * Remove the specified SLA from storage.
     */
    public function destroy(Sla $sla)
    {
        // Authorize the user
        $this->authorize('delete', $sla);

        // Check if the SLA is being used by any tickets
        if ($sla->tickets()->exists()) {
            return back()->with('error', 'Cannot delete SLA that is being used by tickets.');
        }

        // Delete the SLA
        $sla->delete();

        return back()->with('success', 'SLA deleted successfully.');
    }

    /**
     * Toggle the active status of the SLA.
     */
    public function toggleStatus(Sla $sla)
    {
        // Authorize the user
        $this->authorize('update', $sla);

        // Toggle the status
        $sla->update(['is_active' => !$sla->is_active]);
        
        // Load fresh model with all attributes
        $sla->refresh();
        
        // Make sure formatted attributes are loaded
        $sla->formatted_response_time;
        $sla->formatted_resolution_time;

        return back()->with('success', 'SLA status updated successfully.');
    }

    /**
     * Display the specified SLA.
     */
    public function show(Sla $sla)
    {
        // Authorize the user
        $this->authorize('view', $sla);

        // Load the customer and creator relationships
        $sla->load(['customer', 'creator']);
        
        // Make sure formatted attributes are loaded
        $sla->formatted_response_time;
        $sla->formatted_resolution_time;

        return Inertia::render('Slas/Show', [
            'sla' => $sla,
            'can' => [
                'updateSla' => auth()->user()->can('update', $sla),
                'deleteSla' => auth()->user()->can('delete', $sla),
            ],
        ]);
    }
}
