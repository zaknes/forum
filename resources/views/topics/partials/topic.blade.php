<div class="post">
    <div class="post-header">
        <div class="post-author">
            <a href="{{ route('users.show', $topic->user->username) }}" class="post-author_image" style="background-image: url({{ $topic->user->getAvatar(50) }})"></a>

            <div class="post-author_info">
                <a href="{{ route('users.show', $topic->user->username) }}">
                    {{ $topic->user->getNameOrUsername() }}
                </a> <br /> {{ $topic->created_at->diffForHumans() }}

                @can('update', $topic)
                    &mdash; <a href="{{ route('topics.edit', $topic) }}">Edit</a>
                @endcan
            </div>
        </div>
    </div>

    <div class="post-body">@parsedown($topic->body)</div>
</div>
