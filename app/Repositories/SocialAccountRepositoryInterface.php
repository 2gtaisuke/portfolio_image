<?php


namespace App\Repositories;


use App\Models\User;

interface SocialAccountRepositoryInterface
{
    public function find(array $data);

    public function store(User $user, string $provider_name, string $provider_id);
}