<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Thread;
use App\Channel;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {

    $title = $faker->sentence;

    return [
        'user_id' => User::count() ? User::pluck('id')->random() : factory(User::class),
        'channel_id' => Channel::count() ? Channel::pluck('id')->random() : factory(Channel::class),
        'title' => $title,
        'slug' => Str::slug($title),
        'body' => $faker->paragraph(10)
    ];
});
