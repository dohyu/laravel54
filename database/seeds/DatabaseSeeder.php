<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        if (config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        }

        App\User::truncate();
        $this->call(UsersTableSeeder::class);

        App\Article::truncate();
        $this->call(ArticlesTableSeeder::class);

        App\Tag::truncate();
        $tags = config('project.tags');

        foreach ($tags as $slug => $name) {
            App\Tag::create([
                'name' => $name,
                'slug' => str_slug($slug),
            ]);
        }
        $this->command->info('Seeded: tags table');

        // 변수 선언
        $faker = app(Faker\Generator::class);
        $users = App\User::all();
        $articles = App\Article::all();
        $tags = App\Tag::all();

        // 아티클과 태그 연
        foreach ($articles as $article) {
            $article->tags()->sync(
                $faker->randomElements(
                    $tags->pluck('id')->toArray(), rand(1, 3)
                )
            );
        }
        $this->command->info('Seeded: article_tag table');

        if (config('database.default') !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
}
