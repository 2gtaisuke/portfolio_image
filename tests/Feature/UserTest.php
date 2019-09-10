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
}