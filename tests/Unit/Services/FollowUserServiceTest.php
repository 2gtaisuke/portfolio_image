<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class FollowUserServiceTest extends TestCase
{
    /** @var UserService */
    private $user_service;

    protected function setUp(): void
    {
        parent::setUp();

        factory(User::class, 4)->create();
        $this->user_service = app()->make(UserService::class);
    }
    /**
     * @test
     * @group followUser
     */
    public function followUser()
    {
        $user = User::first();
        $followed_user = User::find(2);
        $this->user_service->followUser($user, $followed_user);

        $this->assertEquals($user->following()->first()->id, $followed_user->id);
    }

    /**
     * @test
     * @group followUser
     * @expectedException App\Exceptions\FollowUserException
     */
    public function followUser_既にフォローしてる場合に例外をスローすること()
    {
        $user = User::first();
        $followed_user = User::find(2);
        $this->user_service->followUser($user, $followed_user);
        $this->user_service->followUser($user, $followed_user);
    }

    /**
     * @test
     * @group followUser
     * @expectedException App\Exceptions\FollowUserException
     */
    public function followUser_自分自身の場合に例外をスローすること()
    {
        $user = $followed_user = User::first();
        $this->user_service->followUser($user, $followed_user);
    }

    /**
     * @test
     * @group unfollowUser
     */
    public function unfollowUser()
    {
        $user = User::first();
        $followed_user = User::find(2);
        $user->following()->attach($followed_user->id);

        $this->user_service->unfollowUser($user, $followed_user);

        $this->assertFalse($user->isFollowing($followed_user));
    }

    /**
     * @test
     * @group unfollowUser
     * @expectedException App\Exceptions\FollowUserException
     */
    public function unfollowUser_まだフォローしていない場合に例外をスローすること()
    {
        $user = User::first();
        $followed_user = User::find(2);

        $this->user_service->unfollowUser($user, $followed_user);
    }

    /**
     * @test
     * @group unfollowUser
     * @expectedException App\Exceptions\FollowUserException
     */
    public function unfollowUser_自分自身の場合に例外をスローすること()
    {
        $user = $followed_user = User::first();
        $this->user_service->unfollowUser($user, $followed_user);
    }

    /**
     * @test
     */
    public function toggleFollow_まだフォローしていない場合にフォローすること()
    {
        $following_user = User::first();
        $followed_user = User::find(2);

        $is_follow = $this->user_service->toggleFollow($following_user, $followed_user);

        $this->assertTrue($is_follow);
        $this->assertTrue($following_user->isFollowing($followed_user));
    }

    /**
     * @test
     */
    public function toggleFollow_既にフォローしている場合にフォローを外すこと()
    {
        $following_user = User::first();
        $followed_user = User::find(2);

        $following_user->follow($followed_user);

        $is_follow = $this->user_service->toggleFollow($following_user, $followed_user);

        $this->assertFalse($is_follow);
        $this->assertFalse($following_user->isFollowing($followed_user));
    }
}