<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view customers')->only(['index', 'show']);
        $this->middleware('permission:create customers')->only(['create', 'store']);
        $this->middleware('permission:edit customers')->only(['edit', 'update']);
        $this->middleware('permission:delete customers')->only('destroy');
    }

    /**
     * Display a listing of customers.
     *
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        // Start with a base query
        $query = User::role('customer');
        
        // Apply search filter if provided
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where('name', 'like', "%{$searchTerm}%");
        }
        
        // Apply sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'a-z':
                $query->orderBy('name', 'asc');
                break;
            case 'z-a':
                $query->orderBy('name', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }
        
        // Get paginated results
        $customers = $query->paginate(10)->withQueryString();
        
        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => [
                'search' => $request->input('search', ''),
                'sort' => $sort,
            ],
            'can' => [
                'create' => Auth::user()->can('create customers'),
                'edit' => Auth::user()->can('edit customers'),
                'delete' => Auth::user()->can('delete customers'),
            ]
        ]);
    }

    /**
     * Show the form for creating a new customer.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('Customers/Create');
    }

    /**
     * Store a newly created customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Assign customer role
        $user->assignRole('customer');

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     *
     * @param  \App\Models\User  $customer
     * @return \Inertia\Response
     */
    public function show(User $customer)
    {
        // Ensure user has the customer role
        if (!$customer->hasRole('customer')) {
            abort(404);
        }
        
        // Get tickets and other related data
        $tickets = [] ; // In a real app, get tickets related to this customer
        
        return Inertia::render('Customers/Show', [
            'customer' => $customer,
            'tickets' => $tickets,
            'can' => [
                'edit' => Auth::user()->can('edit customers'),
                'delete' => Auth::user()->can('delete customers'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified customer.
     *
     * @param  \App\Models\User  $customer
     * @return \Inertia\Response
     */
    public function edit(User $customer)
    {
        // Ensure user has the customer role
        if (!$customer->hasRole('customer')) {
            abort(404);
        }
        
        return Inertia::render('Customers/Edit', [
            'customer' => $customer,
        ]);
    }

    /**
     * Update the specified customer in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $customer)
    {
        // Ensure user has the customer role
        if (!$customer->hasRole('customer')) {
            abort(404);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $customer->id,
            'password' => 'nullable|string|min:8',
        ]);

        // Update basic info
        $customer->name = $validated['name'];
        $customer->email = $validated['email'];
        
        // Update password if provided
        if (!empty($validated['password'])) {
            $customer->password = Hash::make($validated['password']);
        }
        
        $customer->save();

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from storage.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $customer)
    {
        // Ensure user has the customer role
        if (!$customer->hasRole('customer')) {
            abort(404);
        }
        
        // Get customer name for success message
        $name = $customer->name;
        
        // Delete the customer
        $customer->delete();
        
        return redirect()->route('customers.index')
            ->with('success', "Customer \"$name\" has been deleted successfully.");
    }
} 