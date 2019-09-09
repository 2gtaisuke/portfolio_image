<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\User;
use App\Repositories\SocialAccountRepositoryInterface as SocialAccountRepository;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Contracts\Factory as Socialite;

class SocialAccountService
{
    /** @var Socialite */
    private $socialite_manager;

    /** @var SocialAccountRepository */
    private $social_account_repo;

    public function __construct
    (
        Socialite $socialite_manager,
        SocialAccountRepository $social_account_repo
    )
    {
        $this->socialite_manager = $socialite_manager;
        $this->social_account_repo = $social_account_repo;
    }

    /**
     * $providerへリダイレクトする
     *
     * @param $provider
     * @param $scope
     * @return RedirectResponse
     */
    public function redirect_provider(string $provider, string $scope): RedirectResponse
    {
        return $this->socialite_manager->driver($provider)->scopes($scope)->redirect();
    }

    /**
     * プロバイダーから帰ってきたユーザー情報を返す
     *
     * @param string $provider
     * @return mixed
     */
    public function getProvidedsUser(string $provider)
    {
        return $this->socialite_manager->driver($provider)->user();
    }

    /**
     * $dataに一致するSocialAccountのインスタンス、あるいはコレクションを返す
     *
     * @param array $data
     */
    public function find(array $data)
    {
        return $this->social_account_repo->find($data);
    }

    /**
     * ソーシャルアカウントを作成する
     *
     * @param User $user
     * @param string $provider_name
     * @param string $provider_id
     * @return SocialAccount
     */
    public function store(User $user, string $provider_name, string $provider_id): SocialAccount
    {
        return $this->social_account_repo->store($user, $provider_name, $provider_id);
    }
}