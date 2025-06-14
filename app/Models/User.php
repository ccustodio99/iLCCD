<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\LogsAudit;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Ticket;
use App\Models\TicketComment;

class User extends Authenticatable
{
    /**
     * List of valid user roles.
     *
     * @var array<int, string>
     */
    public const ROLES = [
        'admin',
        'staff',
        'head',
        'president',
        'finance',
        'itrc',
        'registrar',
        'hr',
        'clinic',
        'user',
        'academic',
    ];
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department',
        'contact_info',
        'is_active',
        'failed_login_attempts',
        'lockout_until',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'failed_login_attempts' => 'integer',
            'lockout_until' => 'datetime',
        ];
    }

    public function watchedTickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class, 'ticket_watchers')->withTimestamps();
    }

    public function ticketComments(): HasMany
    {
        return $this->hasMany(TicketComment::class);
    }
}
