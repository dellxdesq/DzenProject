<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between items-center py-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Список статей') }}
            </h2>

            @if (Auth::user()?->login === 'author')
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
            <aside class="w-60 bg-gray-800 p-4 rounded shadow shrink-0">
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
                        <div class="bg-white dark:bg-gray-800 border border-gray-700 rounded shadow overflow-hidden flex flex-col min-h-[320px] w-full">

                            @if($article->preview_path)
                                <img src="{{ route('articles.preview', basename($article->preview_path)) }}"
                                     alt="Превью статьи"
                                     class="w-full h-[200px] object-cover">
                            @else
                                <div class="w-full h-[200px] bg-gray-700 text-white flex items-center justify-center text-sm italic">
                                    Фото отсутствует
                                </div>
                            @endif

                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                    <a href="{{ route('articles.show', $article->id) }}">
                                        {{ $article->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-700 dark:text-gray-300 text-sm overflow-hidden break-words mb-3"
                                   style="max-height: 4.5rem;">
                                    {{ \Illuminate\Support\Str::limit($article->description, 150) }}
                                </p>
                                <div class="flex justify-between items-center text-sm text-gray-400 mt-auto pt-2 border-t border-gray-700">
                                    <span>Канал:
                                        <a href="{{ route('channels.show', $article->channel->id) }}"
                                           class="text-indigo-600 hover:text-indigo-800 underline transition">{{ $article->channel->name }}
                                        </a>
                                    </span>

                                    <span>{{ \Carbon\Carbon::parse($article->publish_date)->format('d.m.Y') }}</span>
                                </div>
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
