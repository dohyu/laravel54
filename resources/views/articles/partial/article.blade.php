<div class="media">
    @include('users.partial.avatar', ['user' => $article->user])

    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
        </h4>

        <p class="text-muted meta__article">
            By
            <a href="{{ gravatar_profile_url($article->user->email) }}">
                <i class="fa fa-user"> {{ $article->user->name }}</i>
            </a>

            <small>
                • {{ $article->created_at->diffForHumans() }}
                • 조회수 {{ $article->view_count }}

                @if ($article->comment_count > 0)
                    • 댓글 {{ $article->comment_count }}개
                @endif
            </small>
        </p>

        @if ($viewName === 'article.index')
            @include('tags.partial.list', ['tags' => $article->tags])
        @endif

        @if ($viewName === 'article.show')
            @include('attachments.partial.list', ['attachments' => $article->attachments])
        @endif
    </div>
</div>
