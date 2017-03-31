<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Return name if it is set; otherwise, return the username.
     *
     * @return string
     */
    public function getNameOrUsername()
    {
        if (!$this->name) {
            return $this->username;
        }

        return $this->name;
    }

    /**
     * Get the avatar of the user.
     *
     * @param  integer  $size
     * @return string
     */
    public function getAvatar(int $size = 100)
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower($this->email)) . '?s=' . $size . '&d=mm';
    }

    /**
     * A user has many posts.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * A user has many topics.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
