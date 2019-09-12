<?php

namespace Tests\Unit\Services;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Str;
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
        $api_token = Str::random(60);
        $profile_image = uniqid() . 'jpg';
        $user = $this->user_service->store($name, null, null, $api_token, $profile_image);
        $this->assertInstanceOf(User::class, $user);
        $this->assertDatabaseHas(
            'users',
            ['name' => $name, 'api_token' => $api_token, 'profile_image' => $profile_image]
        );
    }

    /**
     * @test
     */
    public function deleteUser()
    {
        $name = 'foo';
        $email = 'foo@gmail.com';
        $user = factory(User::class)->create([
            'name' => $name, 'email' => $email
        ]);
        $this->user_service->deleteUser($user);

        $this->assertDatabaseMissing(
            'users',
            ['name' => $name, 'email' => $email]
        );
    }

    /**
     * @test
     */
    public function storeWithSocialAccount()
    {
        $name = 'foo';
        $api_token = Str::random(60);
        $profile_image = uniqid() . 'jpg';
        $provider_name = 'github';
        $provider_id = uniqid();
        $user = $this->user_service->storeWithSocialAccount(
            $name,
            $api_token,
            $profile_image,
            $provider_name,
            $provider_id
        );

        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users',[
            'name' => $name,
            'api_token' => $api_token,
            'profile_image' => $profile_image,
        ]);

        $this->assertDatabaseHas('social_accounts',[
            'provider_name' => $provider_name,
            'provider_id' => $provider_id
        ]);
    }
}