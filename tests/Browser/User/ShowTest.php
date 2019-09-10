<?php

namespace Tests\Browser\User;

use App\Models\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowTest extends DuskTestCase
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
    public function ログインしている場合にフォローボタンが表示されること()
    {
        $show_user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($show_user) {
            $user = User::first();

            $browser
                ->loginAs($user)
                ->visit(route('user.show', ['id' => $show_user->id], false))
                ->assertSee('フォローする');

            $browser->logout();
        });
    }

    /**
     * @test
     */
    public function ログインしていない場合にフォローボタンが表示されないこと()
    {
        $show_user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($show_user) {

            $browser
                ->visit(route('user.show', ['id' => $show_user->id], false))
                ->assertDontSee('フォローする');
        });
    }

    /**
     * @test
     */
    public function 自分自身の場合はフォローボタンが表示されないこと()
    {
        $this->browse(function (Browser $browser) {
            $user = User::first();

            $browser
                ->loginAs($user)
                ->visit(route('user.show', ['id' => $user->id], false))
                ->assertDontSee('フォローする');

            $browser->logout();
        });
    }

    /**
     * @test
     */
    public function フォローボタンを押下したときにユーザーのフォロー、アンフォローの切り替えができること()
    {
        // FIXME:
        // click() is not working
        $this->markTestIncomplete();

        $following_user = User::first();
        $followed_user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($following_user, $followed_user) {

            $process = $browser
                ->loginAs($following_user)
                ->visit(route('user.show', ['id' => $followed_user->id], false))
                ->assertSee('フォローする')
                ->click('#followUserBtn');

            $this->assertDatabaseHas('follow_user', [
                'following_id' => $following_user->id,
                'followed_id'  => $followed_user->id,
            ]);

            $process
                ->assertSee('フォローを外す')
                ->click('#followUserBtn');

            $this->assertDatabaseMissing('follow_user', [
                'following_id' => $following_user->id,
                'followed_id'  => $followed_user->id,
            ]);

            $process
                ->assertSee('フォローする')
                ->click('#followUserBtn');

            $this->assertDatabaseHas('follow_user', [
                'following_id' => $following_user->id,
                'followed_id'  => $followed_user->id,
            ]);

            $browser->logout();
        });

    }

    /**
     * @test
     */
    public function フォローしているユーザーがフォロワー一覧に表示されること()
    {
        // FIXME:
        // dump()すると/user/1があるのだが。。。
        $this->markTestIncomplete();

        /** @var User $following_user */
        $following_user = User::first();
        /** @var User $followed_user */
        $followed_user = factory(User::class)->create();
        $following_user->follow($followed_user);

        $this->browse(function (Browser $browser) use ($following_user, $followed_user) {
            $browser
                ->visit(route('user.show', ['id' => $followed_user->id], false))
                ->assertSeeIn('')
                ->assertSeelink(route('user.show', ['id' => $following_user->id], false));
        });
    }

    /**
     * @test
     */
    public function フォローしているユーザー数が一致すること()
    {
        /** @var User $following_user */
        $following_user = User::first();
        /** @var User $followed_user */
        $followed_user = factory(User::class)->create();
        $following_user->follow($followed_user);

        $this->browse(function (Browser $browser) use ($following_user, $followed_user) {
            $browser
                ->visit(route('user.show', ['id' => $followed_user->id], false))
                ->assertSeeIn('.followers-list > .card-header', '（1）');
        });
    }
}