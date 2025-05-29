<x-app-layout>
    <div class="container article-create">

        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-header">
                <h1>Создание статьи</h1>
                <div class="form-header-actions">
                    <button type="submit" class="button-submit">Опубликовать</button>
                </div>
            </div>

            <div class="form-body">
                <div>
                    <label for="title">Тема</label>
                    <input type="text" name="title" value="{{ old('title') }}" required>
                </div>

                <div>
                    <label for="preview">Превью (изображение)</label>
                    <input type="file" name="preview" id="preview-input" accept="image/*">
                </div>

                <div>
                    <label for="category">Категория:</label>
                    <select name="category" id="category" required class="form-control">
                        <option value="" disabled selected>Выберите категорию</option>
                        @foreach(\App\Models\Category::where('is_hidden', false)->get() as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="tags">Теги (через запятую)</label>
                    <input type="text" name="tags" value="{{ old('tags') }}" placeholder="тег1, тег2, тег3">
                </div>

                <div>
                    <label for="description">Описание</label>
                    <textarea name="description" rows="5" required>{{ old('description') }}</textarea>
                </div>

                <div>
                    <label for="content">Содержимое</label>
                    <textarea name="content" rows="20" required>{{ old('content') }}</textarea>
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
    <script>
        $(document).ready(function() {
            $('#category').select2({
                placeholder: 'Выберите категорию',
                width: '100%',
                allowClear: true
            });
        });
    </script>

</x-app-layout>
