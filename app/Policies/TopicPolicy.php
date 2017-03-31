<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the topic.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }

    /**
     * Determine whether the user can delete the topic.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Topic  $topic
     * @return mixed
     */
    public function delete(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }
}
