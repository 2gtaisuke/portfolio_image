<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SocialAccount;
use Faker\Generator as Faker;

$factory->define( SocialAccount::class, function (Faker $faker) {
    return [
        'provider_name' => 'github',
        'provider_id' => uniqid()
    ];
});
