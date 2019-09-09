<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
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
            uniqid() . 'jpg'
        );

        $user = $this->user->find(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('foo', $user->name);
    }
}
