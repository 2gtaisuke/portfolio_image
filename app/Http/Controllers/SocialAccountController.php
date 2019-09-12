<?php

namespace App\Http\Controllers;

use App\Services\SocialAccountService;
use App\Services\UserService;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\User;
use Psr\Log\LoggerInterface;
use Illuminate\Http\RedirectResponse;

class SocialAccountController extends Controller
{
    /** @var AuthManager */
    private $auth;

    /** @var LoggerInterface */
    private $logger;

    /** @var SocialAccountService */
    private $social_account_service;

    /** @var UserService */
    private $user_service;

    public function __construct(
        AuthManager $auth,
        LoggerInterface $logger,
        SocialAccountService $social_account_service,
        UserService $user_service
    )
    {
        $this->auth = $auth;
        $this->logger = $logger;
        $this->social_account_service = $social_account_service;
        $this->user_service = $user_service;
    }

    /**
     * GitHubの認証ページヘユーザーをリダイレクト
     * @param string $provider
     * @return RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        return $this->social_account_service->redirect_provider($provider, config('services.' . $provider . '.scope'));
    }

    /**
     * GitHubからユーザー情報を取得
     *
     * @param string social provider
     * @return RedirectResponse
     */
    public function handleProviderCallback(string $provider)
    {
        // ソーシャル情報を取得
        try {
            /** @var User $provided_user_info */
            $provided_user_info = $this->social_account_service->getProvidedsUser($provider);
        } catch (\Exception $e) {
            return redirect()->route('login');
        }

        $social_account = $this->social_account_service->find([
            'provider_id' => $provided_user_info->getId(),
            'provider_name' => $provider
        ]);

        if (!is_null($social_account)) {
            $this->auth->guard()->login($social_account->user()->get()->first());
            // TODO: routeにする
            return redirect('/');
        }

        try {
            // 画像保存
            $profile_image = $this->user_service->downloadAndStoreImage($provided_user_info->getAvatar());

            // プロバイダによって異なる。
            // github: getNickName()
            // google: getName()
            $user_name = $provided_user_info->getNickname() ?? $provided_user_info->getName();

            $user = $this->user_service->storeWithSocialAccount(
                $user_name,
                Str::random(60),
                $profile_image,
                $provider,
                $provided_user_info->getId()
            );

        } catch(\Exception | \Throwable $e) {
            $this->logger->error($e->getMessage());
            # 画像を削除
            if(isset($local_avatar_path)) {
                $this->user_service->deleteImage($local_avatar_path);
            }
            return redirect()->route('login');
        }

        $this->auth->guard()->login($user);
        // TODO: routeにする
        return redirect('/');
    }
}