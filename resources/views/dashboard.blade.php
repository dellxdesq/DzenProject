<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between items-center py-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Список статей') }}
            </h2>

            @if (Auth::user()?->hasRole('author'))
                <a href="{{ route('articles.create') }}"
                   class="inline-block bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    + Создать статью
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-6 px-4 mx-auto">
        <div class="flex gap-6">
            {{-- Категории --}}
            <aside class="w-60 bg-gray-100 dark:bg-gray-900 p-4 rounded shadow shrink-0">
                <h4 class="font-semibold mb-2 text-white">Категории</h4>
                <ul class="space-y-1">
                    @foreach($categories as $category)
                        <li>
                            <a href="{{ route('dashboard', array_merge(request()->all(), ['category' => $category->id])) }}"
                               class="block text-sm px-3 py-1 rounded
                {{ request('category') == $category->id
                    ? 'bg-blue-600 text-white font-semibold'
                    : 'text-gray-200 hover:bg-gray-700' }}"
                               style="word-break: break-word;">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                    <li class="pt-2">
                        <a href="{{ route('dashboard', request()->except('category')) }}"
                           class="block text-sm px-3 py-1 rounded border text-white border-white hover:bg-red-700 hover:text-white transition">
                            Сбросить фильтр
                        </a>
                    </li>
                </ul>
            </aside>

            {{-- Поиск и статьи --}}
            <div class="flex-1 space-y-6">
                <form method="GET" action="{{ route('dashboard') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="border border-gray-300 rounded px-3 py-1 w-full"
                           placeholder="Поиск по статьям...">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-1 rounded">Поиск</button>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse ($articles as $article)
                        <div class="bg-white dark:bg-gray-900 border border-gray-700 rounded shadow overflow-hidden flex flex-col w-full">

                            {{-- Превью статьи --}}
                            @if($article->preview_path)
                                <img src="{{ route('articles.preview', basename($article->preview_path)) }}"
                                     alt="Превью статьи"
                                     class="w-full h-[200px] object-cover">
                            @else
                                <div class="w-full h-[200px] bg-gray-700 text-white flex items-center justify-center text-sm italic">
                                    Фото отсутствует
                                </div>
                            @endif

                            {{-- Заголовок --}}
                            <div class="px-4 pt-3">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                    <a href="{{ route('articles.show', $article->id) }}" class="hover:underline">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                            </div>

                            {{-- Описание --}}
                            <div class="px-4">
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-2 line-clamp-2">
                                    {{ \Illuminate\Support\Str::limit($article->description, 120) }}
                                </p>
                            </div>

                            {{-- Канал автора --}}
                            <div class="px-4 mb-1">
                                <a href="{{ route('channels.show', $article->channel->id) }}" class="flex items-center gap-2 group">
                                    <img src="{{ $article->channel->photo ?? 'https://via.placeholder.com/40' }}"
                                         alt="{{ $article->channel->name }}"
                                         class="w-8 h-8 rounded-full object-cover border border-gray-300 group-hover:scale-105 transition">
                                    <span class="text-sm text-indigo-400 group-hover:text-indigo-600 transition">
                                        {{ $article->channel->name }}
                                    </span>
                                </a>
                            </div>

                            {{-- Лайки, Комментарии, Дата --}}
                            <div class="flex justify-between items-center px-4 py-2 text-sm text-gray-600 dark:text-gray-400 border-t border-gray-700 mt-auto">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1">
                                        <span>{{ $article->likes->count() }}</span>
                                        <span>❤️</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <span>{{ $article->comments->count() }}</span>
                                        <span>💬</span>
                                    </div>
                                </div>
                                <span>
                                    {{ \Carbon\Carbon::parse($article->publish_date)->format('d.m.Y') }}
                                </span>
                            </div>
                        </div>

                    @empty
                        <p class="col-span-3 text-gray-600 dark:text-gray-400">Статей не найдено</p>
                    @endforelse
                </div>

                <div>
                    {{ $articles->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
