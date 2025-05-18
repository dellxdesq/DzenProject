<x-app-layout>
    <div style="display: flex; justify-content: center; padding: 20px 0;">
        <div style="
            background-color: white;
            width: 1250px;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Consolas;
            box-sizing: border-box;
        ">
            {{-- Шапка: Фото, Название, Описание --}}
            <div style="display: flex; align-items: flex-start; gap: 25px;">
                <img src="{{ $channel->photo ?? 'https://via.placeholder.com/100' }}"
                     width="100" height="100"
                     style="border-radius: 50%;">
                <div style="flex-grow: 1;">
                    <div style="font-size: 24px; margin-bottom: 10px; word-wrap: break-word;">
                        {{ $channel->name }}
                    </div>
                    <div id="description"
                         style="font-size: 20px;
                                max-width: 1000px;
                                overflow: hidden;
                                white-space: nowrap;
                                text-overflow: ellipsis;
                                cursor: pointer;
                                transition: all 0.3s ease;">
                        {{ $channel->description }}
                    </div>
                </div>
            </div>

            {{-- Список статей --}}
            <div style="
                margin-top: 40px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
                gap: 10px;
            ">
                @foreach($channel->articles as $article)
                    <a href="{{ route('articles.show', $article->id) }}"
                       style="text-decoration: none; color: inherit;">
                        <div style="
                            border: 1px solid black;
                            border-radius: 15px;
                            padding: 10px;
                            transition: border-color 0.2s ease;
                        "
                             onmouseover="this.style.borderColor='#6A9774'"
                             onmouseout="this.style.borderColor='black'">
                            <div style="font-size: 20px;">{{ $article->title }}</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        const desc = document.getElementById('description');
        desc.addEventListener('click', function () {
            if (this.style.whiteSpace === 'normal') {
                this.style.whiteSpace = 'nowrap';
                this.style.overflow = 'hidden';
                this.style.textOverflow = 'ellipsis';
            } else {
                this.style.whiteSpace = 'normal';
                this.style.overflow = 'visible';
                this.style.textOverflow = 'unset';
            }
        });
    </script>
</x-app-layout>
