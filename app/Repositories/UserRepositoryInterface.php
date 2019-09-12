<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function store(
        string $user_name,
        string $email = null,
        string $password = null,
        string $profile_image = null
    );

    public function update(int $id, string $user_name, bool $is_expose_email = false, string $profile_image = null): User;
}