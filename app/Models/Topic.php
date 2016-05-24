<?php

namespace Forum\Models;

use Forum\Models\Post;
use Forum\Models\User;
use Forum\Models\Section;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{ 
    protected $fillable = [
        'title',
        'slug',
        'body',
        'section_id',
    ];

    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function replyCountText()
    {
        $count = $this->replyCount();

        if ($count <= 1) {
            return $count . ' reply';
        }

        return $count . ' replies';
    }

    public function replyCount()
    {
        return $this->posts()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
