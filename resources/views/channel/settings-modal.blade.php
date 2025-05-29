@if(auth()->id() === $channel->user_id)
    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black bg-opacity-50 z-40" onclick="closeModal()"></div>

    <div id="channelModal"
         class="hidden fixed top-1/2 left-1/2 w-[700px] h-[500px] bg-gray-100 dark:bg-gray-900
         transform -translate-x-1/2 -translate-y-1/2 z-50 rounded-[10px] shadow-xl p-[20px] font-mono box-border">
        <button onclick="closeModal()"
                class="absolute top-[10px] right-[15px] text-[18px] border-none
                bg-none cursor-pointer text-gray-800 hover:text-[#4f46e5] dark:text-gray-300 dark:hover:text-[#4f46e5]">
            ✖
        </button>

        <h2 class="text-[22px] mb-[15px] text-gray-900 dark:text-white">Редактировать канал</h2>

        <form action="{{ route('channels.update', $channel->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <label class="text-gray-800 dark:text-gray-200">Название:</label>
            <input type="text" name="name" value="{{ $channel->name }}"
                   class="w-full mb-[10px] rounded border border-gray-300
                   dark:border-gray-700 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-3 py-2">

            <label class="text-gray-800 dark:text-gray-200">Описание:</label>
            <textarea name="description"
                      class="w-full h-[190px] mb-[10px] resize-none overflow-y-auto
                      rounded border border-gray-300 dark:border-gray-700
                      bg-white dark:bg-gray-700 text-gray-900
                      dark:text-white px-3 py-2">{{ $channel->description }}</textarea>

            <label class="text-gray-800 dark:text-gray-200">Фото:</label>
            <input type="file" name="photo"
                   class="mb-[20px] text-sm text-gray-600 dark:text-gray-300">

            <div class="absolute bottom-[20px] left-[20px]">
                <button type="submit"
                        class="px-[16px] py-[8px] bg-indigo-600
                        hover:bg-indigo-700 text-white rounded transition">
                    Сохранить
                </button>
            </div>
        </form>

        <form action="{{ route('channels.destroy', $channel->id) }}" method="POST"
              onsubmit="return confirm('Удалить канал и все статьи?')"
              class="absolute bottom-[20px] right-[20px]">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="px-[16px] py-[8px] bg-red-600
                    text-white rounded hover:bg-red-700 transition">
                Удалить канал
            </button>
        </form>
    </div>
@endif
