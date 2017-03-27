@extends('layouts.app')

@section('banner')
    @component('layouts.components.banner')
        <div class="banner-title">
            {{ $topic->title }}
        </div>
    @endcomponent
@endsection

@section('content')
    @for ($i = 0; $i < 5; $i++)
    <div class="post">
        <div class="post-header">
            <div class="post-author">
                <a href="#" class="post-author_image" style="background-image: url({{ $topic->user->getAvatar(50) }})"></a>

                <div class="post-author_info">
                    <a href="#">{{ $topic->user->getNameOrUsername() }}</a> <br /> {{ $topic->created_at->diffForHumans() }}

                    @can('update', $topic)
                        &mdash; <a href="{{ route('topics.edit', $topic) }}">Edit</a>
                    @endcan
                </div>
            </div>
        </div>

        <div class="post-body">{!! Markdown::text($topic->body) !!}</div>
    </div>
    @endfor
@endsection
