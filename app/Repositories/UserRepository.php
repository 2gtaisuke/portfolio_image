<?php


namespace App\Repositories;

use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /** @var User */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * ユーザーを作成する
     *
     * @param string $user_name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $profile_image
     * @return User
     * @throws \Throwable
     */
    public function store(
        string $user_name,
        string $email = null,
        string $password = null,
        string $profile_image = null
    ): User
    {
        $this->user->fill([
            'name' => $user_name,
            'email' => $email,
            'password' => $password,
            'profile_image' => $profile_image
        ])->saveOrFail();

        return $this->user;
    }
}