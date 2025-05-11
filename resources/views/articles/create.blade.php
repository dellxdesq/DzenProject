{{-- resources/views/articles/create.blade.php --}}
<x-app-layout>
    <div class="container article-create">

        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-header">
                <h1>Создание статьи</h1>

                <div class="form-header-actions">
                    <input type="file" name="preview" id="preview-input" style="display: none;" onchange="updateFileName(this)">
                    <!--<label for="preview-input" class="preview-button">Загрузить превью</label>-->
                    <!--<span id="preview-filename" class="preview-filename">Файл не выбран</span>-->

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

        </form>
    </div>

    <script>
        function updateFileName(input) {
            const filename = input.files.length > 0 ? input.files[0].name : 'Файл не выбран';
            document.getElementById('preview-filename').textContent = filename;
        }
    </script>
</x-app-layout>
