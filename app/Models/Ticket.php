<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_number',
        'subject',
        'description',
        'status',
        'priority',
        'customer_id',
        'assigned_to',
        'created_by',
        'department_id',
        'sla_id',
        'due_date',
        'first_response_time',
        'resolution_time',
        'last_activity_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'due_date' => 'datetime',
        'first_response_time' => 'datetime',
        'resolution_time' => 'datetime',
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the customer who owns the ticket.
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the agent assigned to the ticket.
     */
    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created the ticket.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the department the ticket is assigned to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the SLA applied to the ticket.
     */
    public function sla()
    {
        return $this->belongsTo(Sla::class);
    }

    /**
     * Get the messages for the ticket.
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class);
    }

    /**
     * Get the attachments for the ticket.
     */
    public function attachments()
    {
        return $this->hasMany(TicketAttachment::class);
    }

    /**
     * Check if the ticket is overdue.
     */
    public function isOverdue()
    {
        if (!$this->due_date) {
            return false;
        }
        
        return Carbon::now()->greaterThan($this->due_date);
    }

    /**
     * Calculate SLA due date based on priority and SLA settings.
     */
    public function calculateDueDate()
    {
        if (!$this->sla) {
            return null;
        }

        $now = Carbon::now();
        $resolutionMinutes = $this->sla->resolution_time_minutes;
        
        return $now->addMinutes($resolutionMinutes);
    }

    /**
     * Get the latest message for the ticket.
     */
    public function latestMessage()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Format the ticket number for display.
     */
    public function getFormattedTicketNumberAttribute()
    {
        return '#' . str_pad($this->ticket_number, 6, '0', STR_PAD_LEFT);
    }
}
