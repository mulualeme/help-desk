<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'message',
        'message_type',
        'is_internal',
        'user_id',
        'is_system_message',
        'has_attachments',
        'read_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_internal' => 'boolean',
        'is_system_message' => 'boolean',
        'has_attachments' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the ticket that the message belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who created the message.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments()
    {
        return $this->hasMany(MessageAttachment::class, 'message_id');
    }

    /**
     * Scope a query to only include public messages.
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope a query to only include internal messages.
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Scope a query to only include messages from customers.
     */
    public function scopeFromCustomers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->where('name', 'customer');
            });
        });
    }

    /**
     * Scope a query to only include messages from staff.
     */
    public function scopeFromStaff($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->whereHas('roles', function ($r) {
                $r->whereIn('name', ['admin', 'agent']);
            });
        });
    }

    /**
     * Check if the message is from staff.
     */
    public function isFromStaff()
    {
        return $this->user && $this->user->hasAnyRole(['admin', 'agent']);
    }

    /**
     * Check if the message is unread.
     */
    public function isUnread()
    {
        return $this->read_at === null;
    }
}
