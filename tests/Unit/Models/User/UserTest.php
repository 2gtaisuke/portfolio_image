<?php

namespace Tests\Unit\Models\User;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function isMyself_同一のユーザーの場合trueを返すこと()
    {
        $user = factory(User::class)->create();
        $this->assertTrue($user->isMyself($user));
    }

    /**
     * @test
     */
    public function isMyself_異なるユーザーの場合falseを返すこと()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->assertFalse($user1->isMyself($user2));
    }
}
