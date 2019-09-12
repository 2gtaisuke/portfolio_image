<?php


namespace App\Services;

use App\Services\Traits\FollowUserServiceTrait;
use App\Models\User;
use App\Repositories\UserRepositoryInterface as UserRepository;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Storage;


class UserService
{
    use FollowUserServiceTrait;

    /** @var UserRepository */
    private $user_repo;

    /** @var SocialAccountService */
    private $social_account_service;

    /** @var DatabaseManager */
    private $database_manager;

    /** @var Client */
    private $guzzle;

    public function __construct
    (
        UserRepository $user_repo,
        SocialAccountService $social_account_service,
        DatabaseManager $database_manager,
        Client $guzzle_client
    )
    {
        $this->user_repo = $user_repo;
        $this->social_account_service = $social_account_service;
        $this->database_manager = $database_manager;
        $this->guzzle = $guzzle_client;
    }

    /**
     * ユーザーを作成する
     *
     * @param string $user_name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $api_token
     * @param string|null $profile_image
     * @return User
     * @throws \Throwable
     */
    public function store(
        string $user_name,
        string $email = null,
        string $password = null,
        string $api_token = null,
        string $profile_image = null
    ): User
    {
        return $this->user_repo->store($user_name, $email, $password, $api_token, $profile_image);
    }


    /**
     * ユーザー情報を更新する
     *
     * @param int $id
     * @param string $name
     * @param bool $is_expose_email
     * @param string|null $profile_image
     */
    public function update(int $id, string $name, bool $is_expose_email = false, string $profile_image = null)
    {
        $this->user_repo->update($id, $name, $is_expose_email, $profile_image);
    }

    /**
     * ユーザーを削除する
     *
     * @param User $user
     * @throws \Exception
     */
    public function deleteUser(User $user)
    {
        $user->delete();
    }

    /**
     * ユーザーとソーシャルアカウントを作成する
     *
     * @param string $user_name
     * @param string $api_token
     * @param string $profile_image
     * @param string $provider_name
     * @param string $provider_id
     * @return User
     * @throws \Throwable
     */
    public function storeWithSocialAccount(
        string $user_name,
        string $api_token,
        string $profile_image,
        string $provider_name,
        string $provider_id
    ): User
    {
        try {
            $this->database_manager->beginTransaction();
            $created_user = $this->store($user_name, null, null, $api_token, $profile_image);
            $this->social_account_service->store($created_user, $provider_name, $provider_id);

            $this->database_manager->commit();
        } catch (\Exception $e) {
            $this->database_manager->rollBack();
            throw $e;
        }

        return $created_user;
    }

    /**
     * $file_urlのファイルを保存し、保存したファイルパスを返す
     *
     * @param string $file_url
     * @return string
     * @throws \Exception
     */
    public function downloadAndStoreImage(string $file_url): string
    {
        try {
            $response = $this->guzzle->request('GET', $file_url);

        } catch (ClientException | RequestException | BadResponseException | GuzzleException $e) {
            throw new \Exception($e->getMessage());
        }

        $content_type = $response->getHeaderLine('content-type');
        $extension = substr($content_type, strrpos($content_type, '/') + 1);

        $dest_name = uniqid() . '.' . $extension;

        // FIXME: 第３引数はなに？
        Storage::put($dest_name, $response->getBody(), 'public');

        return $dest_name;
    }

    /**
     * 画像を保存する
     *
     * @param string $file_name
     * @param string $resouce
     */
    public function storeImage(string $file_name, string $resouce)
    {
        Storage::put($file_name, $resouce);
    }

    /**
     * プロフィール画像を削除する
     *
     * @param string $file_path
     * @return bool
     */
    public function deleteImage(string $file_path): bool
    {
        return Storage::delete($file_path);
    }
}