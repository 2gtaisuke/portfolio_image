<?php

namespace Tests\Unit\SocialAccount;

use App\Models\SocialAccount;
use App\Models\User;
use App\Repositories\SocialAccountRepository;
use App\Repositories\SocialAccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class SocialAccountRepositoryTest extends TestCase
{
    /** @var SocialAccountRepositoryInterface */
    private $social_account_repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->social_account_repo = app()->make(SocialAccountRepository::class);
    }

    /**
     * @test
     */
    public function find_結果が１レコードの場合にSocialAccountインスタンスを返す()
    {
        $data = [
            'provider_name' => 'github',
            'provider_id' => uniqid()
        ];

        factory(User::class)->create()->each(function($user) use ($data) {
            $user->socialAccounts()->save(factory(SocialAccount::class)->make($data));
        });

        $social_account = $this->social_account_repo->find([
            'provider_name' => $data['provider_name'],
            'provider_id' => $data['provider_id']
        ]);

        $this->assertInstanceOf(SocialAccount::class, $social_account);
    }

    /**
     * @test
     * @depends find_結果が１レコードの場合にSocialAccountインスタンスを返す
     */
    public function find_結果が２レコード以上の場合にCollectionインスタンスを返す()
    {
        $data = [];
        for($i = 0; $i < 5; $i++) {
            $data[$i] = [
                'provider_name' => 'github',
                'provider_id' => uniqid()
            ];
        }

        $users = factory(User::class, 5)->create();
        $i = 0;
        foreach($users as $user) {
            $user->socialAccounts()->save(factory(SocialAccount::class)->make($data[$i]));
            $i++;
        }

        $social_account = $this->social_account_repo->find([
            'provider_name' => 'github'
        ]);

        $this->assertInstanceOf(Collection::class, $social_account);
    }

    /**
     * @test
     * @depends find_結果が１レコードの場合にSocialAccountインスタンスを返す
     */
    public function find_結果が０の場合にnullを返す()
    {
        $social_account = $this->social_account_repo->find([
            'provider_name' => 'github'
        ]);

        $this->assertNull($social_account);
    }

    /**
     * @test
     */
    public function store()
    {
        $user = factory(User::class)->create();

        $id = uniqid();
        $social_account = $this->social_account_repo->store($user,'github', $id);

        $this->assertInstanceOf(SocialAccount::class, $social_account);
        $this->assertDatabaseHas(
            'social_accounts',
            [
                'provider_id' => $id, 'provider_name' => 'github'
            ]
        );
    }
}
