<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\UserService;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    /** @var UserService */
    private $user_service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user_service = app()->make(UserService::class);
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function store()
    {
        $name = 'foo';
        $profile_image = uniqid() . 'jpg';
        $user = $this->user_service->store($name, null, null, $profile_image);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas(
            'users',
            ['name' => $name, 'profile_image' => $profile_image]
        );
    }

    /**
     * @test
     */
    public function storeWithSocialAccount()
    {
        $name = 'foo';
        $profile_image = uniqid() . 'jpg';
        $provider_name = 'github';
        $provider_id = uniqid();
        $user = $this->user_service->storeWithSocialAccount($name, $profile_image, $provider_name, $provider_id);

        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users',[
            'name' => $name,
            'profile_image' => $profile_image,
        ]);

        $this->assertDatabaseHas('social_accounts',[
            'provider_name' => $provider_name,
            'provider_id' => $provider_id
        ]);
    }
}