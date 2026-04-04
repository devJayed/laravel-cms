<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
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
            'password' => 'hashed', // Auto hash password
        ];
    }

    /**
     * Relationships
     *
     */
    /**
     * One user has many posts.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
    /**
     * Role helper methods
     */
    /**
     * Is User an admin?
     * Admin can manage user roles 
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }
    /**
     * Editor and Admin both can approve posts, but only Admin can manage user roles.
     */
    /** */
    /**
     * return bangla name of the role
     */
    public function getRoleNameAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'অ্যাডমিন',
            'editor' => 'এডিটর',
            'author' => 'লেখক',
            default => $this->role,
        };
    }
}
