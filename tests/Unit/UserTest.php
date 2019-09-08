<?php

namespace Tests\Unit;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @var User */
    private $user;

    protected function setUp(): void
    {
        parent::setUp();
        factory(User::class, 3)->create();
        $this->user = User::first();
    }

    /**
     * @test
     */
    public function isMyself_同一のユーザーの場合trueを返すこと()
    {
        $compared_user = User::first();
        $this->assertTrue($this->user->isMyself($compared_user));
    }

    /**
     * @test
     */
    public function isMyself_異なるユーザーの場合falseを返すこと()
    {
        $compared_user = factory(User::class)->make();
        $this->assertFalse($this->user->isMyself($compared_user));
    }
}
