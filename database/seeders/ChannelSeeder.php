<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Channel;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'login' => 'author',
            'full_name' => 'Test Author',
            'email' => 'author@example.com',
            'password' => bcrypt('123'),
        ]);

        // создаём канал
        $channel = Channel::create([
            'user_id' => $user->id,
            'name' => 'Author Channel',
            'description' => 'Какое-то описание канала с множеством слов, которое не поместится в одну строку и надо будет нажать на описание чтобы увидеть это описание целиком.',
            'photo' => 'https://i.pravatar.cc/100?img=3',
        ]);

        // создаём статьи
        foreach (range(1, 6) as $i) {
            Article::create([
                'author_id' => $user->id,
                'title' => "Статья #{$i}",
                'content' => 'Какое-то наполнение статьи на какую-то тему с какими-то приколами',
            ]);
        }
    }
}
