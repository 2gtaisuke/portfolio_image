<?php


namespace App\Repositories;


use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Collection;

class SocialAccountRepository implements SocialAccountRepositoryInterface
{
    /** @var SocialAccount */
    private $social_account;

    public function __construct(SocialAccount $social_account)
    {
        $this->social_account = $social_account;
    }

    /**
     * $dataに一致するSocialAccountのインスタンス、あるいはコレクションを返す
     *
     * @param array $data
     * @return mixed
     * @return Collection|SocialAccount|null
     */
    public function find(array $data)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException();
        }

        $accounts = $this->social_account->where($data)->get();

        switch(count($accounts)) {
            case 1:
                return $accounts->first();
            case 0:
                return null;
            default:
                return $accounts;
        }
    }

    /**
     * $userのsocial_accountsを作成する
     *
     * @param User $user
     * @param string $provider_name
     * @param string $provider_id
     * @return SocialAccount
     */
    public function store(User $user, string $provider_name, string $provider_id): SocialAccount
    {
        $this->social_account->fill([
            'provider_name' => $provider_name,
            'provider_id' => $provider_id
        ]);

        $user->socialAccounts()->save($this->social_account);

        return $this->social_account;
    }
}