<?php


namespace App\Repositories;


interface UserRepositoryInterface
{
    public function store(
        string $user_name,
        string $email = null,
        string $password = null,
        string $profile_image = null
    );
}