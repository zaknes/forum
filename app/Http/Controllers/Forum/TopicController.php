<?php

namespace Forum\Http\Controllers\Forum;

use Forum\Models\Post;
use Forum\Models\Topic;
use Forum\Http\Requests;
use Forum\Models\Section;
use Illuminate\Http\Request;
use Forum\Http\Controllers\Controller;
use Forum\Events\Forum\Topic\TopicWasCreated;
use Forum\Events\Forum\Topic\TopicWasDeleted;
use Forum\Events\Forum\Topic\TopicWasReported;
use Forum\Events\Forum\Topic\TopicReportsWereCleared;
use Forum\Http\Requests\Forum\Topic\CreateTopicFormRequest;
use Forum\Http\Requests\Forum\Topic\EditTopicFormRequest;

class TopicController extends Controller
{
    /**
     * Report topic.
     *
     * @param  integer             $id
     * @param  Forum\Models\Topic  $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function report($id, Topic $topic)
    {
        $topic = $topic->findOrFail($id);

        event(new TopicWasReported($topic));

        notify()->flash('Success', 'success', [
            'text' => 'Thank you for reporting.',
            'timer' => 2000,
        ]);

        return redirect()->back();
    }

    /**
     * Clear reports on topic.
     *
     * @param  integer             $id
     * @param  Forum\Models\Topic  $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearReports($id, Topic $topic)
    {
        $topic = $topic->findOrFail($id);

        event(new TopicReportsWereCleared($topic));

        notify()->flash('Success', 'success', [
            'text' => 'Reports have been cleared.',
            'timer' => 2000,
        ]);

        return redirect()->back();
    }

    /**
     * Get the view to create a new topic.
     *
     * @param  Forum\Models\Topic    $topic
     * @param  Forum\Models\Section  $section
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Topic $topic, Section $section)
    {
        $sections = $section->get();

        if (!$sections->count()) {
            notify()->flash('Oops..', 'error', [
                'text' => 'No sections available.',
                'timer' => 2000,
            ]);

            return redirect()->route('home');
        }

        return view('forum.topic.create', [
            'sections' => $sections,
            'id' => $request['section_id'],
        ]);
    }

    /**
     * Get the view that displays all of the topics.
     *
     * @param  Forum\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function all(Topic $topic)
    {
        $topics = $topic->latestFirst()->paginate(10);

        return view('moderation.topic.all', [
            'topics' => $topics,
        ]);
    }

    /**
     * Get the view that displays a single topic with its replies.
     *
     * @param  string              $slug
     * @param  integer             $id
     * @param  Forum\Models\Topic  $topic
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $id, Topic $topic)
    {
        $show = $topic->findOrFail($id);

        $show->increment('views_count');

        $posts = $show->posts()->latestLast()->get();

        return view('forum.topic.show', [
            'topic' => $show,
            'posts' => $posts,
        ]);
    }

    /**
     * Store the new topic in database.
     *
     * @param  CreateTopicFormRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateTopicFormRequest $request)
    {
        $topic = $request->user()->topics()->create([
            'name' => $request->input('name'),
            'slug' => str_slug($request->input('name')),
            'body' => $request->input('body'),
            'section_id' => $request->input('section_id'),
        ]);

        event(new TopicWasCreated($topic, $request->user()));

        notify()->flash('Success', 'success', [
            'text' => 'Your topic has been added.',
            'timer' => 2000,
        ]);

        return redirect()->route('forum.topic.show', [
            'slug' => $topic->slug,
            'id' => $topic->id,
        ]);
    }

    /**
     * Mark topic as deleted.
     * @param  integer             $id
     * @param  Forum\Models\Topic  $topic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Request $request, Topic $topic)
    {
        $topic = $topic->findOrFail($id);

        event(new TopicWasDeleted($topic, $request->user()));

        $topic->delete();

        notify()->flash('Success', 'success', [
            'text' => 'Topic has been deleted.',
            'timer' => 2000,
        ]);

        return redirect()->route('home');
    }
}
