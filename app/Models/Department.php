<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the agents assigned to this department.
     */
    public function agents()
    {
        return $this->belongsToMany(User::class, 'department_user')
                    ->whereHas('roles', function($query) {
                        $query->where('name', 'agent');
                    });
    }

    /**
     * Get all tickets in this department.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
