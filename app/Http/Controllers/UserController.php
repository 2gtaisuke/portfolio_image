<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserEditRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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
     * ユーザー情報変更画面を表示する
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('user.edit', compact('user'));
    }


    /**
     *
     *
     * @param User $user
     * @param UserEditRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(User $user, UserEditRequest $request)
    {
        // TODO: サムネイル作成
        try {
            $dest_file = null;

            if (isset($request->user['profile_image'])) {
                $profile_image = $request->user['profile_image'];
                $dest_file = uniqid() . '.' . $profile_image->extension();
                $this->user_service->storeImage($dest_file, $profile_image->get());
                $old_profile_image = $user->profile_image;
            }

            $expose_email = isset($request->user['expose_email']) ? '1' : '0';

            $this->user_service->update($user->id, $request->user['name'], $expose_email, $dest_file);

        } catch (\Exception $e) {
            if ($dest_file) {
                $this->user_service->deleteImage($dest_file);
            }

            return redirect()->route('user.edit', ['id' => $user->id]);
        }

        // 古い画像を削除
        if (isset($old_profile_image)) {
            $this->user_service->deleteImage($old_profile_image);
        }

        return redirect()->route('user.show', ['id' => $user->id]);
    }


    /**
     * ユーザーを削除する
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        try {
            $this->user_service->deleteUser($user);
            $this->user_service->deleteImage($user->profile_image);
        } catch (\Exception $e) {
            // TODO: アラート
            return redirect()->route('user.edit', ['id' => $user->id]);
        }

        // TODO: topのrouteに置き換え
        // TODO: アラート
        return redirect('/');
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