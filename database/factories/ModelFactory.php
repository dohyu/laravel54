<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    $activated = $faker->randomElement([0, 1]);

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('secret'),
        'remember_token' => str_random(10),
        'activated' => $activated,
        'confirm_code' => $activated ? null : str_random(60),
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    $date = $faker->dateTimeThisMonth;
    $userId = App\User::pluck('id')->toArray();

    return [
        'title' => $faker->sentence(),
        'content' => $faker->paragraph(),
        'user_id' => $faker->randomElement($userId),
        'created_at' => $date,
        'updated_at' => $date,
    ];
});

$factory->define(App\Attachment::class, function (Faker\Generator $faker) {
    return [
        'filename' => sprintf("%s.%s",
            str_random(),
            $faker->randomElement(config('project.mimes'))
        )
    ];
});

$factory->define(App\Comment::class, function (Faker\Generator $faker) {
    $articleIds = App\Article::pluck('id')->toArray();
    $userIds = App\User::pluck('id')->toArray();

    return [
        'content' => $faker->paragraph,
        'commentable_type' => App\Article::class,
        'commentable_id' => function () use ($faker, $articleIds) {
            return $faker->randomElement($articleIds);
        },
        'user_id' => function () use ($faker, $userIds) {
            return $faker->randomElement($userIds);
        },
    ];
});

$factory->define(App\Vote::class, function (Faker\Generator $faker) {
    $up = $faker->randomElement([true, false]);
    $down = ! $up;
    $userIds = App\User::pluck('id')->toArray();

    return [
        'up' => $up ? 1 : null,
        'down' => $down ? 1 : null,
        'user_id' => function () use ($faker, $userIds) {
            return $faker->randomElement($userIds);
        },
    ];
});
