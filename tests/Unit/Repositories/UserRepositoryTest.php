<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    private $user_repo;

    /** @var User */
    private $user;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user_repo = app()->make(UserRepository::class);
        $this->user = app()->make(User::class);
    }


    /**
     * @test
     */
    public function store()
    {
        $this->user_repo->store(
            'foo',
            'foo@gmail.com',
            Hash::make('foobarbaz'),
            Str::random(60),
            uniqid() . 'jpg'
        );

        $user = $this->user->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('foo', $user->name);
    }

    /**
     * @test
     */
    public function update()
    {
        $name = 'updated_foo';
        $profile_image = 'updated_foo.jpg';

        $user = factory(User::class)->create([
            'name' => 'foo',
            'profile_image' => 'foo.jpg',
        ]);

        $returned_user = $this->user_repo->update($user->id, $name, true, $profile_image);

        $this->assertInstanceOf(User::class, $returned_user);
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'expose_email' => '1',
            'profile_image' => $profile_image
        ]);
    }

    /**
     * @test
     * @expectedException \Exception
     */
    public function update_ユーザーが存在しない場合に例外をスローすること()
    {
        $name = 'updated_foo';
        $profile_image = 'updated_foo.jpg';
        $not_found_user_id = 1000;

        $this->user_repo->update($not_found_user_id, $name, false, $profile_image);
    }

    /**
     * @test
     */
    public function update_profile_imageがnullの場合に更新しないこと()
    {
        $name = 'updated_foo';
        $profile_image = null;

        $user = factory(User::class)->create([
            'name' => 'foo',
            'profile_image' => 'foo.jpg',
        ]);

        $returned_user = $this->user_repo->update($user->id, $name);
        $this->assertInstanceOf(User::class, $returned_user);
        $this->assertDatabaseHas('users', [
            'name' => $name,
            'expose_email' => 0,
            'profile_image' => 'foo.jpg'
        ]);
    }
}
