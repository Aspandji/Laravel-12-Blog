<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    /**
     * Get the category that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Query Scope untuk post yang published
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    // Query Scope untuk post yang latest posts
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->published()->latest('published_at');
    }

    // Query Scope dengan Eager loading
    public function scopeWhithCategory(Builder $query): Builder
    {
        return $query->with('category');
    }

    // Helper untuk SEO
    public function getMetaTitleAttribute($value)
    {
        return $value ?: $this->title;
    }

    public function getMetaDescriptionAttribute($value)
    {
        return $value ?: ($this->excerpt ?: Str::limit(strip_tags($this->content), 160));
    }
}
