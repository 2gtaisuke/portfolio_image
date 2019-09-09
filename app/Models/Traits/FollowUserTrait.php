<?php

namespace App\Models\Traits;

use App\Models\User;

trait FollowUserTrait
{
    /**
     * $userをフォローする
     *
     * @param User $user
     * @throws \Exception
     */
    public function follow(User $user): void
    {
        if ($this->isFollowing($user)) {
            throw new \Exception('既にフォローしています');
        }
        $this->following()->attach($user->id);
    }

    /**
     * $userをアンフォローする
     *
     * @param User $user
     * @throws \Exception
     */
    public function unfollow(User $user): void
    {
        if (!$this->isFollowing($user)) {
            throw new \Exception('まだフォローしていません');
        }

        $this->following()->detach($user->id);
    }

    /**
     * $userをフォローしているかどうかを返す
     *
     * @param User $user
     * @return bool
     */
    public function isFollowing(User $user): bool
    {
        return !is_null($this->load('following')->following->find($user->id));
    }
}