<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'status',
        'published_at',
    ];

    protected $casts = [
        'title' => 'array',
        'content' => 'array',
        'excerpt' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Boot method for the Post model.
     *
     * This method is called automatically when the model is initialized.
     * It attaches a "saving" event listener to ensure that the `slug` attribute
     * is generated based on the `title` attribute if it is not already set.
     *
     * The slug is created using the `Str::slug` method to ensure it is URL-friendly.
     * If a slug already exists in the database for another post, a unique suffix
     * is appended to the slug to avoid duplication.
     *
     * - `$originalSlug`: The initial slug generated from the post title.
     * - `$slug`: The final slug, which may include a numeric suffix for uniqueness.
     * - `$i`: Counter used to append a numeric suffix to the slug.
     *
     * The uniqueness of the slug is ensured by checking the database for existing
     * records with the same slug but a different ID.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($post) {
            if (empty($post->slug)) {
                $originalSlug = Str::slug($post->title);
                $slug = $originalSlug;
                $i = 1;
        
                while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                    $slug = $originalSlug . '-' . $i++;
                }
        
                $post->slug = $slug;
            }
        });
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getImageUrlAttribute()
    {
        $disk = config('filesystems.default');
        $path = $this->image;

        if (!$path) return null;

        if ($disk === 's3') {
            //return Storage::disk('s3')->temporaryUrl($path, now()->addMinutes(5));
            return Storage::disk('s3')->url($path);
        }

        return asset('storage/' . $path); // local/public fallback

        return $this->image
        ? Storage::disk('public')->url($this->image)
        : null;
    }
}
