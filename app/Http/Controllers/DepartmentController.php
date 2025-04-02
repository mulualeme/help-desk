<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Routing\Controller as BaseController;

class DepartmentController extends BaseController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']); // Base middleware for authentication
        
        // Permission middleware for specific actions
        $this->middleware('permission:view department')->only(['index', 'show']);
        $this->middleware('permission:create department')->only(['create', 'store']);
        $this->middleware('permission:edit department')->only(['edit', 'update']);
        $this->middleware('permission:delete department')->only('destroy');
        $this->middleware('permission:assign agent to department')->only(['assignAgent', 'removeAgent', 'getAvailableAgents']);
    }

    /**
     * Display a listing of the departments.
     */
    public function index()
    {
        return Inertia::render('Departments/Index', [
            'departments' => Department::withCount('agents')->get(),
            'can' => [
                'create' => Auth::user()->can('create department'),
                'edit' => Auth::user()->can('edit department'),
                'delete' => Auth::user()->can('delete department'),
                'view' => Auth::user()->can('view department'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        return Inertia::render('Departments/Create');
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $department = Department::create($validated);

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        $department->load(['agents']);
        
        return Inertia::render('Departments/Show', [
            'department' => $department,
            'can' => [
                'edit' => Auth::user()->can('edit department'),
                'delete' => Auth::user()->can('delete department'),
                'assign_agent' => Auth::user()->can('assign agent to department'),
            ],
        ]);
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        return Inertia::render('Departments/Edit', [
            'department' => $department
        ]);
    }

    /**
     * Update the specified department in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('departments')->ignore($department)],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean'
        ]);

        $department->update($validated);

        return redirect()
            ->route('departments.show', $department)
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return redirect()
            ->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }

    /**
     * Assign an agent to the department.
     */
    public function assignAgent(Request $request, Department $department)
    {
        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('department_user', 'user_id')->where('department_id', $department->id)
            ]
        ]);

        $user = User::findOrFail($validated['user_id']);
        
        // Check if user has agent role
        if (!$user->hasRole('agent')) {
            return back()->with('error', 'Selected user is not an agent.');
        }

        $department->agents()->attach($validated['user_id']);

        return back()->with('success', 'Agent assigned successfully.');
    }

    /**
     * Remove an agent from the department.
     */
    public function removeAgent(Department $department, User $user)
    {
        // Check if user is actually an agent
        if (!$user->hasRole('agent')) {
            return back()->with('error', 'Selected user is not an agent.');
        }

        // Check if agent is assigned to this department
        if (!$department->agents()->where('user_id', $user->id)->exists()) {
            return back()->with('error', 'Agent is not assigned to this department.');
        }

        $department->agents()->detach($user->id);

        return back()->with('success', 'Agent removed successfully.');
    }

    /**
     * Get available agents that can be assigned to the department.
     */
    public function getAvailableAgents(Department $department)
    {
        $agents = User::role('agent')
            ->whereNotIn('id', $department->agents->pluck('id'))
            ->get(['id', 'name', 'email']);

        return response()->json($agents);
    }
}
