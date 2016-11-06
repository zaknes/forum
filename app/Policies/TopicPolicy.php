<?php

namespace Forum\Policies;

use Forum\Models\User;
use Forum\Models\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    protected $staffIgnoredAbilities = [
        'report',
    ];

    /**
     * Method to be called before all others.
     *
     * @param  Forum\Models\User  $user
     * @param  void  $ability
     * @return boolean
     */
    public function before(User $user, $ability)
    {
        if ($user->isGroup(['moderator', 'administrator']) && in_array($ability, $this->staffIgnoredAbilities)) {
            return false;
        }

        if ($user->isGroup('administrator')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the topic.
     *
     * @param  Forum\Models\User  $user
     * @param  Forum\Models\Topic  $topic
     * @return mixed
     */
    public function view(User $user, Topic $topic)
    {
        return true;
    }

    /**
     * Determine whether the user can create topics.
     *
     * @param  Forum\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the topic.
     *
     * @param  Forum\Models\User  $user
     * @param  Forum\Models\Topic  $topic
     * @return mixed
     */
    public function update(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }

    /**
     * Determine whether the user can delete the topic.
     *
     * @param  Forum\Models\User  $user
     * @param  Forum\Models\Topic  $topic
     * @return mixed
     */
    public function delete(User $user, Topic $topic)
    {
        return $user->id == $topic->user_id;
    }

    /**
     * Determine whether the user can report the topic.
     *
     * @param  Forum\Models\User  $user
     * @param  Forum\Models\Topic  $topic
     * @return mixed
     */
    public function report(User $user, Topic $topic)
    {
        return $user->id !== $topic->user_id;
    }
}
