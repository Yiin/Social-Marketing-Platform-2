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
$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\Template::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->sentence(random_int(2, 4)),
        'url' => $faker->url,
        'image_url' => $faker->imageUrl(),
        'description' => ucwords($faker->word) . ' {' . implode('|', $faker->words(5)) . '} ' . $faker->paragraph,
        'message' => ucwords($faker->word) . ' {' . implode('|', $faker->words(5)) . '} ' . ' {' . implode('|', $faker->words(3)) . '} ',
        'caption' => $faker->title
    ];
});

$factory->define(App\Modules\Errors\Models\ErrorLog::class, function (Faker\Generator $faker) {
    return [
        'message' => $faker->sentence(random_int(5, 8))
    ];
});