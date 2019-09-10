<?php

namespace Tests\Browser\User;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserTest extends DuskTestCase
{

    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        factory(User::class)->create([
            'email' => 'alice@gmail.com', 'name' => 'alice'
        ]);
    }

    /**
     * @test
     */
    public function ログインしていない場合にヘッダーに『SignUp』『Login』の文字列が表示されること()
    {
        $this->browse(function (Browser $browser) {

            $browser->visit('/')
                ->assertSee('Sign up')
                ->assertSee('Login');
        });
    }

    /**
     * @test
     */
    public function ログインしている場合にヘッダーにユーザーの画像が表示されること()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();

            $browser->loginAs($user);
            $browser->visit('/')
                ->assertSourceHas(get_user_profile_image($user->profile_image));

            $browser->logout();
        });
    }
}