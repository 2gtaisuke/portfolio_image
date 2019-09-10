<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /** @var UserService */
    private $user_service;

    public function __construct(UserService $user_service)
    {
        $this->user_service = $user_service;
    }

    /**
     * ユーザーの情報を表示する
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $followers = $this->user_service->getFollower($user);
        return view('user.show', compact('user', 'followers'));
    }

    /**
     * ユーザーのフォローをトグルする
     *
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleFollowUser(User $user, Request $request)
    {
        $login_user = $request->user();

        try {
            $is_follow = $this->user_service->toggleFollow($login_user, $user);
        } catch (\Exception $e) {
            return response()
                ->json([
                    'status' => 'failed'
                ]);
        }

        return response()
            ->json([
                'status' => 'success',
                'following'  => $is_follow
            ]);
    }
}