<x-app-layout>
    <div style="
        max-width: 1250px;
        margin: 1rem auto;
        background: #fff;
        padding: 1.5rem;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    ">
        <h2 class="text-2xl font-bold mb-6">Редактирование статьи</h2>

        <form action="{{ route('articles.update', $article->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700">Описание</label>
                <textarea name="description" rows="3"
                          class="w-full mt-1 rounded border border-gray-300">{{ old('description', $article->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700">Содержимое</label>
                <textarea name="content" rows="8"
                          class="w-full mt-1 rounded border border-gray-300">{{ old('content', $article->content) }}</textarea>
            </div>

            <div class="flex justify-between items-center">
                <button type="submit"
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded">
                    💾 Сохранить
                </button>

                @if(auth()->user()->hasRole('moder'))
                    <button type="submit" name="publish" value="1"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded">
                        ✅ Сохранить и опубликовать
                    </button>
                @endif
            </div>
        </form>

        @if(auth()->user()->hasRole('moder'))
            <form action="{{ route('articles.destroy', $article->id) }}" method="POST" class="mt-4 text-right">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded"
                        onclick="return confirm('Удалить статью?')">
                    🗑️ Удалить
                </button>
            </form>
        @endif
    </div>
</x-app-layout>
