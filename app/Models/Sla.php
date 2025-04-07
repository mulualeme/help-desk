<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sla extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'response_time_minutes',
        'resolution_time_minutes',
        'customer_id',
        'priority',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'response_time_minutes' => 'integer',
        'resolution_time_minutes' => 'integer',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_response_time',
        'formatted_resolution_time',
    ];

    /**
     * Get the customer associated with the SLA.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the creator of the SLA.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the tickets that are using this SLA.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    /**
     * Get formatted response time.
     */
    public function getFormattedResponseTimeAttribute()
    {
        return $this->formatTime($this->response_time_minutes);
    }
    
    /**
     * Get formatted resolution time.
     */
    public function getFormattedResolutionTimeAttribute()
    {
        return $this->formatTime($this->resolution_time_minutes);
    }
    
    /**
     * Format minutes into human-readable time.
     */
    private function formatTime($minutes)
    {
        if (!$minutes) {
            return 'N/A';
        }
        
        if ($minutes < 60) {
            return $minutes . ' minutes';
        } elseif ($minutes < 1440) {
            $hours = floor($minutes / 60);
            $extraMinutes = $minutes % 60;
            
            if ($extraMinutes > 0) {
                return $hours . ' ' . ($hours == 1 ? 'hour' : 'hours') . ' ' . $extraMinutes . ' minutes';
            }
            
            return $hours . ' ' . ($hours == 1 ? 'hour' : 'hours');
        } else {
            $days = floor($minutes / 1440);
            $extraHours = floor(($minutes % 1440) / 60);
            
            if ($extraHours > 0) {
                return $days . ' ' . ($days == 1 ? 'day' : 'days') . ' ' . $extraHours . ' ' . ($extraHours == 1 ? 'hour' : 'hours');
            }
            
            return $days . ' ' . ($days == 1 ? 'day' : 'days');
        }
    }
}
