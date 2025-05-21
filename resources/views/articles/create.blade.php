<x-app-layout>
    <div class="container article-create">

        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-header">
                <h1>Создание статьи</h1>
                <div class="form-header-actions">
                    <button type="submit">Опубликовать</button>
                </div>
            </div>

            <div class="form-body">
                <div>
                    <label for="title">Тема</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div>
                    <label for="content">Содержимое</label>
                    <textarea name="content" rows="10" required>{{ old('content') }}</textarea>
                </div>
            </div>

            <div>
                <label for="preview">Превью (изображение)</label>
                <input type="file" name="preview" id="preview-input" accept="image/*">
            </div>

            <div>
                <label for="tags">Теги (через запятую)</label>
                <input type="text" name="tags" value="{{ old('tags') }}" placeholder="тег1, тег2, тег3">
            </div>

        </form>
    </div>

    <script>
        function updateFileName(input) {
            const filename = input.files.length > 0 ? input.files[0].name : 'Файл не выбран';
            document.getElementById('preview-filename').textContent = filename;
        }
    </script>
</x-app-layout>
