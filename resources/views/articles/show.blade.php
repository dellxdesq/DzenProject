@extends('layouts.app')

@section('content')
    <div class="article-page">

        <div class="article-header">
            <h2 class="channel-name">Название канала: нихуяшеньки пока</h2>
            <h1 class="article-title">Название статьи: {{ $article->title }}</h1>
        </div>

        <div class="article-content">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="article-footer">

            <form action="{{ route('articles.like', $article->id) }}" method="POST" style="margin-bottom: 1rem;">
                @csrf
                <button type="submit">Лайк ({{ $article->likes->count() }})</button>
            </form>

            <div class="comments">
                <h3>Комментарии ({{ $article->comments->count() }})</h3>

                <form action="{{ route('articles.comment', $article->id) }}" method="POST">
                    @csrf
                    <textarea name="text" rows="3" placeholder="Ваш комментарий..."></textarea>
                    <br>
                    <button type="submit">Добавить</button>
                </form>

                <ul style="margin-top: 1rem;">
                    @foreach ($article->comments as $comment)
                        <li style="margin-bottom: 1rem;">
                            <strong>Аноним:</strong> {{ $comment->text }}<br>
                            <small>{{ $comment->created_at->format('d.m.Y H:i') }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

    </div>
@endsection
