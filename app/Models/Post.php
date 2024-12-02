<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = ['title', 'description', 'image_id', 'slug', 'guid'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            // Generate a slug if not provided
            if (empty($post->slug)) {
                $post->slug = Str::slug(Str::random(8));
            }

            // Generate a GUID if not provided
            if (empty($post->guid)) {
                $post->guid = Str::uuid()->toString();
            }
        });
    }

    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id');
    }
}
