<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class FollowUserTest extends TestCase
{
    /**
     * @test
     */
    public function toggleFollowUser_まだフォローしていない場合にフォローする()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $this
            ->actingAs($following_user, 'api')
            ->post(
                route('follow.toggle', ['id' => $followed_user->id])
            )
            ->assertStatus(200)
            ->assertJson(['status' => 'success', 'following' => true]);

        $this->assertTrue($following_user->isFollowing($followed_user));
    }

    /**
     * @test
     */
    public function toggleFollowUser_既にフォローしている場合にフォローを外す()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $following_user->follow($followed_user);

        $this
            ->actingAs($following_user, 'api')
            ->post(
                route('follow.toggle', ['id' => $followed_user->id])
            )
            ->assertStatus(200)
            ->assertJson(['status' => 'success', 'following' => false]);

        $this->assertFalse($following_user->isFollowing($followed_user));
    }

    /**
     * @test
     */
    public function toggleFollowUser_apiがない場合はログイン画面にリダイレクトされる()
    {
        $following_user = factory(User::class)->create();
        $followed_user = factory(User::class)->create();

        $following_user->follow($followed_user);

        $this
            ->post(
                route('follow.toggle', ['id' => $followed_user->id])
            )->assertStatus(302)
            ->assertLocation(route('login'));
    }
}