<?php

namespace Tests\Feature;


use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function ユーザーページが表示されること()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user, 'web')
            ->get(route('user.show', ['id' => $user->id]))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function 自分のユーザー情報編集ページが表示されること()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user, 'web')
            ->get(route('user.edit', ['id' => $user->id]))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function 他人のユーザー情報編集ページが表示されないこと()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this
            ->actingAs($user, 'web')
            ->get(route('user.edit', ['id' => $user2->id]))
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function ユーザー削除が行えること()
    {
        $user = factory(User::class)->create();

        $this
            ->actingAs($user, 'web')
            ->delete(route('user.destroy', ['id' => $user->id]))
            ->assertStatus(302)
            ->assertLocation('/');
    }

    /**
     * @test
     */
    public function 他人のユーザー削除は行えないこと()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this
            ->actingAs($user, 'web')
            ->delete(route('user.destroy', ['id' => $user2->id]))
            ->assertStatus(403);
    }
}