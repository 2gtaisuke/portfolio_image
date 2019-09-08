<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        factory(User::class, 5)->create();
        $this->user = User::first();
    }

    /**
     * @test
     */
    public function 自分自身ののユーザー画面にアクセスできること()
    {
        $this->actingAs($this->user)
            ->get(route('user.show', ['id' => $this->user->id], false))
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function 異なるユーザーのユーザー画面にアクセスできないこと()
    {
        $second_user = User::find(2);
        $this->actingAs($this->user)
            ->get(route('user.show', ['id' => $second_user->id], false))
            ->assertStatus(403);
    }
}
