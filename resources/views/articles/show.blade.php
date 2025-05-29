<x-app-layout>
    <div class="article-page">

        <div class="article-header-left">
            <h2 class="channel-name">
                Канал:
                @if($article->channel)
                    <a href="{{ route('channels.show', $article->channel->id) }}"
                       class="text-indigo-600 hover:text-indigo-800 underline transition">
                        {{ $article->channel->name }}
                    </a>
                @else
                    <span class="text-gray-500">Не указано</span>
                @endif
            </h2>

            <h2 class="article-title">Тема: {{ $article->title }}</h2>
            <div class="article-meta">
                <p><strong>Автор:</strong> {{ $article->author?->full_name ?? 'Неизвестно' }}</p>
                @auth
                    @if(auth()->user()->hasRole('moder'))
                        <a href="{{ route('articles.edit', $article->id) }}" class="edit-button">
                            ✏️ Редактировать
                        </a>

                    @endif
                @endauth
                <p><strong>Опубликовано:</strong>
                    {{ $article->publish_date ? \Carbon\Carbon::parse($article->publish_date)->format('d.m.Y H:i') : 'Не указано' }}
                </p>
            </div>
        </div>

        <div class="article-description">
            <span style="font-weight: bold;">{!! $article->description !!}</span></p>
        </div>
        <div class="article-content">
            {!! $article->content_html !!}
        </div>

        <div class="article-tags">
            <strong>Теги:</strong>
            @foreach ($article->tags as $tag)
                <span class="tag-badge">{{ $tag->name }}</span>
            @endforeach
        </div>

        <div class="article-footer">

            <div class="action-buttons">
                @auth
                    <form action="{{ route('articles.like', $article->id) }}" method="POST" class="like-form">
                        @csrf
                        @php
                            $hasLiked = $article->likes->contains('user_id', auth()->id());
                        @endphp
                        <form action="{{ route('articles.like', $article->id) }}" method="POST" class="like-form">
                            @csrf
                            <button type="submit" class="like-button">
                                {{ $hasLiked ? '💔' : '❤️' }} ({{ $article->likes->count() }})
                            </button>
                        </form>
                    </form>

                    <button onclick="toggleCommentForm()" class="comment-toggle">💬 Оставить комментарий</button>
                @else
                    <p>Войдите, чтобы ставить лайки и оставлять комментарии.</p>
                @endauth
            </div>

            @auth
                <div class="comments" id="comment-section" style="display: none;">
                    <form action="{{ route('articles.comment', $article->id) }}" method="POST" class="comment-form">
                        @csrf
                        <textarea name="text" rows="3" placeholder="Ваш комментарий..."></textarea>
                        <br>
                        <button type="submit">Добавить</button>
                    </form>
                </div>
            @endauth

            <div class="comments-list">
                <h3>Комментарии ({{ $article->comments->count() }})</h3>
                <ul>
                    @foreach ($article->comments as $comment)
                        <li style="margin-bottom: 1rem;">
                            <strong>
                                {{ $comment->user?->full_name ?? $comment->user?->login ?? 'Аноним' }}
                            </strong>: {{ $comment->text }}<br>
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
</x-app-layout>
