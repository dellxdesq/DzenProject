<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Channel;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'login' => 'author',
            'full_name' => 'Test Author',
            'email' => 'author@example.com',
            'password' => bcrypt('123'),
        ]);

        $channel = Channel::create([
            'user_id' => $user->id,
            'name' => 'Author Channel',
            'description' => 'Канал с прикольными статьями.',
            'photo' => 'https://i.pravatar.cc/100?img=3',
        ]);

        foreach (range(1, 12) as $i) {
            $article = Article::create([
                'author_id' => $user->id,
                'title' => "Статья #{$i}",
                'content' => "Контент для статьи #{$i}. Это может быть что угодно.",
                'preview_path' => 'https://via.placeholder.com/300x200.png?text=Превью',
                'created_date' => Carbon::now()->subDays(6 - $i),
                'publish_date' => Carbon::now()->subDays(6 - $i),
                'is_publish' => true,
            ]);

            // Пример тегов
            $tags = ['Наука', 'Спорт', 'Еда'];
            Tag::create([
                'post_id' => $article->id,
                'name' => $tags[array_rand($tags)]
            ]);
        }
    }
}

