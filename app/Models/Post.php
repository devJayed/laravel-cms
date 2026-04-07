<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'body',
        'status',
        'rejection_reason',
        'published_at',
    ];
    /**
     * Attribute casting
     */
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }
    /**
     * User create post (Belongs-To relationship)
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    /**
     * Status helper methods
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Query Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
    /**
     * Model Events (Boot Method)
     */
    protected static function boot()
    {
        parent::boot();
        // Auto-generate slug from title when creating a post
        static::creating(function ($post) {
            $post->slug = static::generateUniqueSlug($post->title);
        });
        //Auto-update slug when title is updated 
        static::updating(function ($post) {
            if ($post->isDirty('title')) {
                $post->slug = static::generateUniqueSlug($post->title, $post->id);
            }
        });
    }
    protected static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title); // ✅ generate base slug
        $originalSlug = $slug;
        $count = 1;

        // Check if slug already exists
        while (static::where('slug', $slug)
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->exists()
        ) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }
}
