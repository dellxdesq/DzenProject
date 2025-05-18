<x-app-layout>
    <div style="display: flex; justify-content: center; padding: 20px 0;">
        <div style="
            background-color: white;
            width: 1250px;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            font-family: Consolas;
            position: relative;
        ">
            {{-- Настройки(Для владельца канала) --}}
            @if(auth()->id() === $channel->user_id)
                <button onclick="openModal()"
                        style="position: absolute; top: 20px; right: 20px; background: none; border: none; cursor: pointer; padding: 0;">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.2502 22L8.8502 18.8C8.63353 18.7167 8.42936 18.6167 8.2377 18.5C8.04603 18.3833 7.85853 18.2583 7.6752 18.125L4.7002 19.375L1.9502 14.625L4.5252 12.675C4.50853 12.5583 4.5002 12.4458 4.5002 12.3375V11.6625C4.5002 11.5542 4.50853 11.4417 4.5252 11.325L1.9502 9.375L4.7002 4.625L7.6752 5.875C7.85853 5.74167 8.0502 5.61667 8.2502 5.5C8.4502 5.38333 8.6502 5.28333 8.8502 5.2L9.2502 2H14.7502L15.1502 5.2C15.3669 5.28333 15.571 5.38333 15.7627 5.5C15.9544 5.61667 16.1419 5.74167 16.3252 5.875L19.3002 4.625L22.0502 9.375L19.4752 11.325C19.4919 11.4417 19.5002 11.5542 19.5002 11.6625V12.3375C19.5002 12.4458 19.4835 12.5583 19.4502 12.675L22.0252 14.625L19.2752 19.375L16.3252 18.125C16.1419 18.2583 15.9502 18.3833 15.7502 18.5C15.5502 18.6167 15.3502 18.7167 15.1502 18.8L14.7502 22H9.2502ZM12.0502 15.5C13.0169 15.5 13.8419 15.1583 14.5252 14.475C15.2085 13.7917 15.5502 12.9667 15.5502 12C15.5502 11.0333 15.2085 10.2083 14.5252 9.525C13.8419 8.84167 13.0169 8.5 12.0502 8.5C11.0669 8.5 10.2377 8.84167 9.5627 9.525C8.8877 10.2083 8.5502 11.0333 8.5502 12C8.5502 12.9667 8.8877 13.7917 9.5627 14.475C10.2377 15.1583 11.0669 15.5 12.0502 15.5Z"
                              fill="#1D1B20" />
                    </svg>
                </button>
            @endif

            {{-- Шапка: Фото, Название, Описание --}}
            <div style="display: flex; align-items: flex-start; gap: 25px;">
                <img src="{{ asset($channel->photo ?? 'https://via.placeholder.com/100') }}"
                     style="
                        width: 100px;
                        height: 100px;
                        object-fit: cover;
                        border-radius: 50%;
                        flex-shrink: 0;
                     ">

                <div style="flex-grow: 1;">
                    <div style="font-size: 24px; margin-bottom: 10px; word-wrap: break-word;">
                        {{ $channel->name }}
                    </div>
                    <div id="description"
                         style="font-size: 20px;
                         max-width: 100%;
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

    {{-- Модальное окно настроек --}}
    @if(auth()->id() === $channel->user_id)
        <div id="channelModal" style="
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            width: 700px;
            height: 500px;
            background: white;
            transform: translate(-50%, -50%);
            z-index: 9999;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            padding: 20px;
            font-family: Consolas;
            box-sizing: border-box;
        ">
            <button onclick="closeModal()" style="
                position: absolute;
                top: 10px;
                right: 15px;
                font-size: 18px;
                border: none;
                background: none;
                cursor: pointer;
            ">✖</button>

            <h2 style="font-size: 22px; margin-bottom: 15px;">Редактировать канал</h2>

            <form action="{{ route('channels.update', $channel->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Название, описание, фото -->
                <label>Название:</label>
                <input type="text" name="name" value="{{ $channel->name }}" style="width: 100%; margin-bottom: 10px;">

                <label>Описание:</label>
                <textarea name="description" style="width: 100%; height: 190px; margin-bottom: 10px; resize: none; overflow-y: auto;">{{ $channel->description }}</textarea>

                <label>Фото:</label>
                <input type="file" name="photo" style="margin-bottom: 20px;">

                <div style="position: absolute; bottom: 20px; left: 20px;">
                    <button type="submit" style="
                        padding: 8px 16px;
                        background-color: #6A9774;
                        color: white;
                        border: none;
                        border-radius: 5px;">
                        Сохранить
                    </button>
                </div>
            </form>

            <form action="{{ route('channels.destroy', $channel->id) }}" method="POST"
                  onsubmit="return confirm('Удалить канал и все статьи?')"
                  style="position: absolute; bottom: 20px; right: 20px;">
                @csrf
                @method('DELETE')
                <button type="submit" style="
                    padding: 8px 16px;
                    background-color: red;
                    color: white;
                    border: none;
                    border-radius: 5px;">
                    Удалить канал
                </button>
            </form>
        </div>
    @endif

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

        function openModal() {
            document.getElementById('channelModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('channelModal').style.display = 'none';
        }
    </script>
</x-app-layout>
