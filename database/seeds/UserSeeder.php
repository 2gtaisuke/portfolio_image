<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'foo', 'email' => 'foo@gmail.com'
        ])->each(function($user){
            $user->socialAccounts()
                ->save(factory(App\Models\SocialAccount::class)->make());
        });
    }
}
