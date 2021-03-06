@extends('layouts.app')

@section('content')
    @php $viewName = 'articles.show'; @endphp

    <div class="page-header">
        <h4>포럼<small> / 글 목록</small></h4>
    </div>

    <div class="text-right action__article">
        <a href="{{ route('articles.create') }}" class="btn btn-primary">
            <i class="fa fa-plus-circle"></i> 새 글 쓰기
        </a>

        <div class="btn-group sort__article">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-sort"></i>
                목록 정렬
                <span class="caret"></span>
            </button>

            <ul class="dropdown-menu" role="menu">
                @foreach (config('project.sorting') as $column => $text)
                <li {!! request()->input('sort') == $column ? 'class="active"' : '' !!}>
                    {!! link_for_sort($column, $text) !!}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="row container__article">
        <div class="col-md-3 sidebar__article">
            <aside>
                @include('articles.partial.search')
                @include('tags.partial.index')
            </aside>
        </div>

        <div class="col-md-9 list__article">
            <article>
                @forelse($articles as $article)
                    @include('articles.partial.article', compact('article'))
                @empty
                    <p class="text-center text-danger">글이 없습니다.</p>
                @endforelse
            </article>
        </div>

        @if($articles->count())
            <div class="text-center">
                {!! $articles->appends(Request::except('page'))->render() !!}
            </div>
        @endif
    </div>
@endsection
