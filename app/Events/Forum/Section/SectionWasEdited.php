<?php

namespace Forum\Events\Forum\Section;

use Forum\Models\User;
use Forum\Events\Event;
use Forum\Models\Section;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SectionWasEdited extends Event
{
    use SerializesModels;

    public $section;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Section $section, User $user)
    {
        $this->section = $section;
        $this->user = $user;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}