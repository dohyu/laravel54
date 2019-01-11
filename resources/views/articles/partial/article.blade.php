<div class="media">
    @include('users.partial.avatar', ['user' => $article->user])

    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
        </h4>

        <p class="text-muted meta__article">
            <a href="{{ gravatar_profile_url($article->user->email) }}">
                <i class="fa fa-user"> {{ $article->user->name }}</i>
            </a>
            <small>
                / <i class="fa fa-clock-o"> {{ $article->created_at->diffForHumans() }}</i>
            </small>
        </p>

        @if($viewName === 'article.index')
            @include('tags.partial.list', ['tags' => $article->tags])
        @endif
    </div>
</div>
