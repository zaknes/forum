<?php

namespace Forum\Http\Controllers\Forum;

use Forum\Models\Topic;
use Forum\Http\Requests;
use Forum\Models\Section;
use Illuminate\Http\Request;
use Forum\Http\Controllers\Controller;
use Forum\Http\Requests\Forum\Section\EditSectionFormRequest;
use Forum\Http\Requests\Forum\Section\CreateSectionFormRequest;

class SectionController extends Controller
{
    /**
     * Get the view to show all sections.
     * @param  Section  $section  Section model injection.
     * @return \Illuminate\Http\Response
     */
    public function all(Section $section)
    {
        $sections = $section->paginate(10);

        return view('forum.section.all', [
            'sections' => $sections,    
        ]);
    }

    /**
     * Get the view to show all topics under a specific section.
     * @param  string   $slug     Section slug.
     * @param  Request  $request  Form request for validation
     * @param  Section  $section  Section model injection.
     * @param  Topic    $topic    Topic model injection.
     * @return \Illuminate\Http\Response
     */
    public function show($slug, Request $request, Section $section, Topic $topic)
    {
        $show = $section->where('slug', $slug)->firstOrFail();

        if ($request->search) {
            $topics = $show->topics()
                ->whereIn('id', collect($topic->search($request->search)['hits'])
                    ->lists('id')
                    ->all())
                    ->with('user')
                    ->paginate(25);
        } else {
            $topics = $show->topics()->with('user')->paginate(25);
        }

        return view('forum.section.show', [
            'section' => $show,
            'topics' => $topics,
        ]);
    }

    /**
     * Get the view to create a new section.
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('forum.section.create');
    }

    /**
     * Create new section.
     * @param  CreateSectionFormRequest  $request  Form request for validation
     * @param  Section                   $section  Section model injection.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateSectionFormRequest $request, Section $section)
    {
        $section->create([
            'name' => $request->input('name'),
            'slug' => $request->input('slug'),
            'description' => $request->input('description'),
        ]);

        notify()->flash('Success', 'success', [
            'text' => 'Section has been created.',
            'timer' => 2000,
        ]);

        return redirect()->route('home');
    }

    /**
     * Mark section as deleted.
     * @param  integer  $id       Section identifier.
     * @param  Section  $section  Section model injection.
     * @param  Topic    $topic    Topic model injection.
     * @param  Post     $post     Post model injection.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id, Section $section, Topic $topic, Post $post)
    {
        $destroy = $section->findOrFail($id);

        $destroy->delete();

        notify()->flash('Success', 'success', [
            'text' => 'Section has been deleted.',
            'timer' => 2000,
        ]);

        // Reindex Algolia indices.
        $section->reindex();
        $topic->reindex();
        $post->reindex();

        return redirect()->route('home');
    }
}
