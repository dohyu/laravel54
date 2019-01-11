<div class="media">
    @include('users.partial.avatar', ['user' => $article->user])
    <div class="media-body">
        <h4 class="media-heading">
            <a href="{{ route('articles.show', $article->id) }}">{{ $article->title }}</a>
        </h4>

        <p class="text-muted">
            <i class="fa fa-user"> {{ $article->user->name }}</i>
            <i class="fa fa-clock-o"> {{ $article->created_at->diffForHumans() }}</i>
        </p>
    </div>
</div>
