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
}
