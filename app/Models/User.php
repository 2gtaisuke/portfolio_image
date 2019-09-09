<?php

namespace App\Models;

use App\Models\Traits\FollowUserTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, FollowUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'profile_image', 'api_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follow_user',
            'following_id',
            'followed_id'
            );
    }

    public function follower()
    {
        return $this->belongsToMany(
            User::class,
            'follow_user',
            'followed_id',
            'following_id'
        );
    }

    /**
     * $compared_userが自分自身であればtrueを返す
     *
     * @param User $compared_user
     * @return bool
     */
    public function isMyself(User $compared_user): bool
    {
        return intval($this->id) === intval($compared_user->id);
    }
}