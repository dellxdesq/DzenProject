<x-app-layout>
    <div class="flex justify-center py-5">
        <div class="bg-white dark:bg-gray-800 w-[1250px] rounded-[15px] p-[10px] shadow font-mono relative">
            <div class="p-6">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Пользователи</h2>

                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <!-- Селектор колонки -->
                    <form action="{{ route('users.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                        <select name="filter_by" class="appearance-none max-w-[200px] border rounded px-3 py-2 text-sm dark:bg-gray-700 dark:text-white">
                            <option value="login" {{ request('filter_by') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="full_name" {{ request('filter_by') == 'full_name' ? 'selected' : '' }}>ФИО</option>
                            <option value="email" {{ request('filter_by') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="id" {{ request('filter_by') == 'id' ? 'selected' : '' }}>ID</option>
                        </select>

                        <!-- Поле ввода -->
                        <input type="text" name="query" value="{{ request('query') }}" placeholder="Поиск..."
                               class=" order rounded px-3 py-2 text-sm w-[200px] dark:bg-gray-700 dark:text-white">

                        <!-- Кнопка -->
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded text-sm">
                            Поиск
                        </button>
                    </form>
                </div>

                <!-- Оборачиваем таблицу, а не родителя -->
                <div class="w-full overflow-x-auto">
                    <!-- min-w-full даст таблице занимать всю ширину контейнера и не сжиматься -->
                    <table class="min-w-full w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                        <tr class="text-left text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <th class="px-4 py-3">ID</th>
                            <th class="px-4 py-3">Дата регистрации</th>
                            <th class="px-4 py-3">Login</th>
                            <th class="px-4 py-3 resize-x overflow-auto min-w-[150px] max-w-[600px]">ФИО</th>
                            <th class="px-4 py-3">Email</th>
                            @if(auth()->user()->hasRole('admin'))
                                <th class="px-4 py-3">Роль</th>
                                <th class="px-4 py-3 text-center">Автор</th>
                                <th class="px-4 py-3 text-center">Модер</th>
                                <th class="px-4 py-3 text-center">Бан</th>
                            @elseif(auth()->user()->hasRole('moder'))
                                <th class="px-4 py-3">Роль</th>
                                <th class="px-4 py-3 text-center">Автор</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700 text-gray-800 dark:text-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                <td class="px-4 py-2">{{ $user->id }}</td>
                                <td class="px-4 py-2 ">{{ $user->created_at->format('d.m.Y') }}</td>
                                <td class="px-4 py-2 max-w-[200px] truncate" title="{{ $user->login }}">{{ \Illuminate\Support\Str::limit($user->login, 10) }}</td>
                                <td class="px-4 py-2 max-w-[200px] truncate" title="{{ $user->full_name }}">{{ \Illuminate\Support\Str::limit($user->full_name, 25) }}</td>
                                <td class="px-4 py-2 max-w-[200px] truncate" title="{{ $user->email }}">{{ \Illuminate\Support\Str::limit($user->email, 30) }}</td>
                                @if(auth()->user()->hasRole('admin'))
                                    <td class="px-4 py-2">{{ $user->roles->pluck('name')->join(', ') }}</td>

                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('users.makeAuthor', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="author" value="0">
                                            <input type="checkbox" name="author" value="1" onchange="this.form.submit()" {{ $user->hasRole('author') ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('users.makeModerator', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="moder" value="0">
                                            <input type="checkbox" name="moder" value="1" onchange="this.form.submit()" {{ $user->hasRole('moder') ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-blue-600">
                                        </form>
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('users.ban', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="ban" value="0">
                                            <input type="checkbox" name="ban" value="1" onchange="this.form.submit()" {{ $user->hasRole('ban') ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-red-600">
                                        </form>
                                    </td>
                                @elseif(auth()->user()->hasRole('moder'))
                                    <td class="px-4 py-2">{{ $user->roles->pluck('name')->join(', ') }}</td>
                                    <td class="px-4 py-2 text-center">
                                        <form action="{{ route('users.makeAuthor', $user) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="author" value="0">
                                            <input type="checkbox" name="author" value="1" onchange="this.form.submit()" {{ $user->hasRole('author') ? 'checked' : '' }} class="form-checkbox h-5 w-5 text-green-600">
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                @if (auth()->user()->hasRole('moder'))
                    <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Статьи на модерации</h3>

                        @if(!$moderationArticles || $moderationArticles->isEmpty())

                        <p class="text-gray-700 dark:text-gray-300">Нет статей для модерации.</p>
                        @else
                            <ul class="space-y-2">
                                @foreach($moderationArticles as $article)
                                    <li class="flex items-center justify-between p-2 border border-gray-200 dark:border-gray-600 rounded">
                                        <div>
                                            <p class="text-gray-900 dark:text-gray-100 font-semibold">{{ $article->title }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Автор: {{ $article->author->full_name ?? 'Неизвестно' }}</p>
                                        </div>
                                        <a href="{{ route('articles.show', $article->id) }}"
                                           class="text-white hover:underline dark:text-blue-400">
                                            Перейти
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
