@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Создать статью</h1>

        <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div>
                <label for="title">Название</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
            </div>

            <div>
                <label for="content">Содержимое</label>
                <textarea name="content" rows="10" required>{{ old('content') }}</textarea>
            </div>

            <div>
                <label for="preview">Превью (изображение)</label>
                <input type="file" name="preview">
            </div>

            <button type="submit">Опубликовать</button>
        </form>
    </div>
@endsection

