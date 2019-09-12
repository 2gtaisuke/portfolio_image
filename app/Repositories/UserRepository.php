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
     * @param string|null $api_token
     * @param string|null $profile_image
     * @return User
     * @throws \Throwable
     */
    public function store(
        string $user_name,
        string $email = null,
        string $password = null,
        string $api_token = null,
        string $profile_image = null
    ): User
    {
        $this->user->fill([
            'name' => $user_name,
            'email' => $email,
            'password' => $password,
            'api_token' => $api_token,
            'profile_image' => $profile_image
        ])->saveOrFail();

        return $this->user;
    }

    /**
     * ユーザーを更新する
     *
     * @param int $id
     * @param string $user_name
     * @param bool $is_expose_email
     * @param string $profile_image
     * @return User
     * @throws \Exception
     */
    public function update(int $id, string $user_name, bool $is_expose_email = false, string $profile_image = null): User
    {
        if(!($user = $this->user->find($id))) {
            throw new \Exception();
        }

        $attributes = [
            'name' => $user_name,
            'expose_email' => (int)$is_expose_email
        ];

        if (!is_null($profile_image)) {
            $attributes = array_merge($attributes, [
                'profile_image' => $profile_image
            ]);
        }

        $user->fill($attributes)->saveOrFail();

        return $user;
    }
}