@extends('layouts.app')

@section('content')
    <div class="article-page">

        <div class="article-header-left">
            <h2 class="channel-name">Название канала: нихуяшеньки пока</h2>
            <h2 class="article-title">Тема статьи: {{ $article->title }}</h2>
        </div>

        <div class="article-content">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="article-footer">

            <div class="action-buttons">
                <form action="{{ route('articles.like', $article->id) }}" method="POST" class="like-form">
                    @csrf
                    <button type="submit" class="like-button">❤️ {{ $article->likes->count() }}</button>
                </form>

                <button onclick="toggleCommentForm()" class="comment-toggle">💬 Оставить комментарий</button>
            </div>

            <div class="comments" id="comment-section" style="display: none;">
                <form action="{{ route('articles.comment', $article->id) }}" method="POST" class="comment-form">
                    @csrf
                    <textarea name="text" rows="3" placeholder="Ваш комментарий..."></textarea>
                    <br>
                    <button type="submit">Добавить</button>
                </form>
            </div>

            <div class="comments-list">
                <h3>Комментарии ({{ $article->comments->count() }})</h3>
                <ul>
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

    <script>
        function toggleCommentForm() {
            const section = document.getElementById('comment-section');
            section.style.display = section.style.display === 'none' ? 'block' : 'none';
        }
    </script>
@endsection
