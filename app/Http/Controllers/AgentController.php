<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class AgentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage agents')->only('index');
        $this->middleware('permission:view agents|manage agents')->only('show');
        $this->middleware('permission:invite agents')->only('store');
        $this->middleware('permission:edit agent roles')->only(['edit', 'update']);
        $this->middleware('permission:delete agents')->only('destroy');
    }

    /**
     * Display a listing of the agents.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $agents = User::role(['agent', 'admin'])->with('roles')->get();
        
        return Inertia::render('Agents/Index', [
            'agents' => $agents,
            'can' => [
                'invite' => Auth::user()->can('invite agents'),
                'edit_roles' => Auth::user()->can('edit agent roles'),
                'delete' => Auth::user()->can('delete agents'),
            ]
        ]);
    }

    /**
     * Invite a new agent.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        // Generate a random password
        $password = Str::random(10);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        // Assign agent role
        $user->assignRole('agent');

        // Send invitation email with credentials
        Mail::to($user->email)->send(new \App\Mail\AgentInvitation($user, $password));

        return redirect()->route('agents.index')->with('success', 'Agent invited successfully. An email with login credentials has been sent.');
    }

    /**
     * Show the form for editing the agent role.
     *
     * @param  \App\Models\User  $agent
     * @return \Inertia\Response
     */
    public function edit(User $agent)
    {
        // Ensure user has the agent or admin role
        if (!$agent->hasRole(['agent', 'admin'])) {
            abort(404);
        }
        
        $roles = Role::whereIn('name', ['agent', 'admin'])->get();
        
        return Inertia::render('Agents/Edit', [
            'agent' => $agent->load('roles'),
            'roles' => $roles,
        ]);
    }

    /**
     * Update the agent role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $agent)
    {
        // Ensure user has the agent or admin role
        if (!$agent->hasRole(['agent', 'admin'])) {
            abort(404);
        }
        
        $request->validate([
            'role' => 'required|string|in:agent,admin',
        ]);

        // Remove existing agent/admin roles and assign the new one
        $agent->removeRole('agent');
        $agent->removeRole('admin');
        $agent->assignRole($request->role);

        return redirect()->route('agents.index')->with('success', 'Agent role updated successfully');
    }

    /**
     * Remove the specified agent from storage.
     *
     * @param  \App\Models\User  $agent
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $agent)
    {
        // Ensure user has the agent or admin role
        if (!$agent->hasRole(['agent', 'admin'])) {
            abort(404);
        }
        
        // Get agent name for confirmation message
        $name = $agent->name;
        
        // Delete the agent
        $agent->delete();
        
        return redirect()->route('agents.index')
            ->with('success', "Agent \"$name\" has been deleted successfully.");
    }

    /**
     * Display the agent's performance metrics.
     *
     * @param  \App\Models\User  $agent
     * @return \Inertia\Response
     */
    public function show(User $agent)
    {
        // Ensure user has the agent or admin role
        if (!$agent->hasRole(['agent', 'admin'])) {
            abort(404);
        }
        
        // Check if the current user is viewing their own profile or has manage_agents permission
        if (Auth::id() !== $agent->id && !Auth::user()->can('manage agents')) {
            abort(403, 'Unauthorized action.');
        }
        
        // Load the agent's departments
        $agent->load(['roles', 'departments']);
        
        // Get performance metrics
        // In a real application, you would calculate these based on actual ticket data
        $performance = [
            'totalTickets' => rand(50, 200),
            'resolvedTickets' => rand(30, 150),
            'avgResponseTime' => rand(1, 8) . ' hours',
            'satisfaction' => rand(85, 98) . '%',
        ];
        
        // Get recent activities
        // In a real application, you would fetch actual activity data
        $activities = $this->getMockActivities($agent);
        
        return Inertia::render('Agents/Show', [
            'agent' => $agent,
            'performance' => $performance,
            'activities' => $activities,
            'can' => [
                'edit_roles' => Auth::user()->can('edit agent roles'),
                'delete' => Auth::user()->can('delete agents'),
            ]
        ]);
    }
    
    /**
     * Get mock activities for demo purposes.
     * In a real application, this would be replaced with actual data.
     *
     * @param  \App\Models\User  $agent
     * @return array
     */
    private function getMockActivities($agent)
    {
        $today = now();
        
        return [
            [
                'id' => 1,
                'title' => 'Resolved Ticket #1234',
                'description' => 'Successfully resolved a customer issue with login problems.',
                'date' => $today->copy()->subHours(2),
            ],
            [
                'id' => 2,
                'title' => 'Assigned to Ticket #1236',
                'description' => 'Was assigned to help with a billing inquiry.',
                'date' => $today->copy()->subHours(5),
            ],
            [
                'id' => 3,
                'title' => 'Commented on Ticket #1230',
                'description' => 'Added troubleshooting steps for a technical issue.',
                'date' => $today->copy()->subHours(8),
            ],
            [
                'id' => 4,
                'title' => 'Closed Ticket #1228',
                'description' => 'Closed an issue as resolved after customer confirmation.',
                'date' => $today->copy()->subDay(),
            ],
            [
                'id' => 5,
                'title' => 'Received 5-star rating',
                'description' => 'Customer gave a 5-star rating for excellent service.',
                'date' => $today->copy()->subDays(2),
            ],
        ];
    }
} 