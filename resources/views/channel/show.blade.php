<x-app-layout>
    <div class="flex justify-center py-5">
        <div class="bg-white dark:bg-gray-800 w-[1250px] rounded-[15px] p-[30px] shadow font-mono relative">
            @if(auth()->id() === $channel->user_id)
                <button onclick="openModal()"
                        class="absolute top-[20px] right-[20px] bg-none border-none cursor-pointer p-0">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path d="..." fill="#1D1B20"/>
                    </svg>
                </button>
            @endif

            {{-- Шапка: Фото, Название, Описание --}}
            <div class="flex items-start gap-[25px]">
                <img src="{{ asset($channel->photo ?? 'https://via.placeholder.com/100') }}"
                     class="w-[100px] h-[100px] object-cover rounded-full shrink-0">

                <div class="flex-1 w-0">
                    <div class="text-[24px] mb-[10px] break-words text-gray-900 dark:text-white">
                        {{ $channel->name }}
                    </div>
                    <div id="description"
                         class="text-[18px] text-gray-700 dark:text-gray-300 break-words whitespace-normal
                         overflow-hidden line-clamp-2 transition-all duration-300 cursor-pointer">
                        {{ $channel->description }}
                    </div>
                </div>
            </div>

            @if(auth()->id() === $channel->user_id)
                <button onclick="openModal()"
                        class="absolute top-[20px] right-[20px] bg-none border-none cursor-pointer p-0">
                    <x-icons.settings
                        class="w-6 h-6 fill-current text-gray-500
                        dark:text-white hover:text-[#4f46e5] dark:hover:text-[#4f46e5]"/>
                </button>
            @endif

            {{-- Список статей --}}
            <div class="grid grid-cols-[repeat(auto-fit,minmax(290px,1fr))] gap-[10px] mt-[40px]">
                @foreach($articles as $article)
                    <a href="{{ route('articles.show', $article->id) }}" class="group">
                        <div class="bg-gray-100 dark:bg-gray-900 border border-gray-300 dark:border-gray-700
                                    rounded-2xl overflow-hidden transition duration-200
                                    group-hover:border-[#4f46e5] cursor-pointer flex flex-col">
                            {{-- Превью --}}
                            @if($article->preview_path)
                                <img src="{{ route('articles.preview', basename($article->preview_path)) }}"
                                     alt="Превью статьи"
                                     class="w-full h-[200px] object-cover">
                            @endif

                            {{-- Название и описание --}}
                            <div class="px-4 py-3 border-b border-gray-300 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $article->title }}
                                </h3>
                                <p class="text-sm text-gray-700 dark:text-gray-300
                                whitespace-nowrap overflow-hidden text-ellipsis">
                                    {{ \Illuminate\Support\Str::limit($article->description, 80) }}
                                </p>
                            </div>

                            {{-- Лайки, коменты и дата --}}
                            <div
                                class="flex justify-between items-center px-4 py-2
                                text-sm text-gray-600 dark:text-gray-400">
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
                                <div>
                                    @if(!$article->is_publish)
                                        <span title="Ожидайте пока эту статью одобрит модератор" class="text-red-500 cursor-help">
                                        Не опубликована
                                        </span>
                                    @else
                                        {{ \Carbon\Carbon::parse($article->publish_date)->format('d.m.Y') }}
                                    @endif
                                </div>

                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Модальное окно настроек --}}
    @include('channel.settings-modal', ['channel' => $channel])
    <script>
        const desc = document.getElementById('description');
        let expanded = false;

        desc.addEventListener('click', () => {
            expanded = !expanded;
            if (expanded) {
                desc.classList.remove('line-clamp-2', 'overflow-hidden');
            } else {
                desc.classList.add('line-clamp-2', 'overflow-hidden');
            }
        });

        function openModal() {
            const scrollbarWidth = window.innerWidth - document.documentElement.clientWidth;
            document.body.style.paddingRight = `${scrollbarWidth}px`;
            document.body.classList.add('overflow-hidden');
            document.getElementById('channelModal').classList.remove('hidden');
            document.getElementById('modalBackdrop').classList.remove('hidden');
        }

        function closeModal() {
            document.body.style.paddingRight = '';
            document.body.classList.remove('overflow-hidden');
            document.getElementById('channelModal').classList.add('hidden');
            document.getElementById('modalBackdrop').classList.add('hidden');
        }
    </script>
</x-app-layout>
