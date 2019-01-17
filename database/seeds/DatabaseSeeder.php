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

        File::cleanDirectory(attachments_path());

        // public/files/.gitignore 파일이 있어야 커밋할 때 빈 디렉터리를 유지할 수 있다.
        File::put(attachments_path('.gitignore'), "*\n!.gitignore");

        $this->command->error(
            'Downloading ' . $articles->count() . ' images from lorempixel. It takes time...'
        );

        $articles->each(function ($article) use ($faker) {
            $path = $faker->image(attachments_path());
            $filename = File::basename($path);
            $bytes = File::size($path);
            $mime = File::mimeType($path);
            $this->command->warn("File save: {$filename}");

            $article->attachments()->save(
                factory(App\Attachment::class)->make(compact('filename', 'bytes', 'mime'))
            );
        });
        $this->command->info('Seeded: attachments table and files');

        // 댓글
        $articles->each(function ($article) {
            $article->comments()->save(factory(App\Comment::class)->make());
            $article->comments()->save(factory(App\Comment::class)->make());
        });

        // 댓글의 댓글
        $articles->each(function ($article) use ($faker) {
            $commentIds = App\Comment::pluck('id')->toArray();

            foreach (range(1, 5) as $index) {
                $article->comments()->save(
                    factory(App\Comment::class)->make([
                        'parent_id' => $faker->randomElement($commentIds)
                    ])
                );
            }
        });

        $this->command->info('Seeded: comments table');

        // votes
        $comments = App\Comment::all();
        $comments->each(function ($comment) {
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
            $comment->votes()->save(factory(App\Vote::class)->make());
        });
        $this->command->info('Seeded: votes table');
    }
}
