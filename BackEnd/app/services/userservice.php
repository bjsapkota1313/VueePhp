<?php
namespace Services;

use Exception;
use Models\Exceptions\AlreadyExistsException;
use Models\Exceptions\FileManagementException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Models\ImageManager;
use Models\Roles;
use Repositories\UserRepository;

class UserService
{
    private $repository;
    use ImageManager;


    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @throws NotFoundException
     */
    public function verifyAndGetUser($email, $password): ?\Models\User
    {
        return $this->repository->verifyAndGetUser($email, $password);
    }
    public function getUserById($id): ?\Models\User
    {
        return $this->repository->getUserById($id);
    }


    public function hashPassword($password): array
    {
        try {
            $salt = bin2hex(random_bytes(32));
            $hashPassword = password_hash($password . $salt, PASSWORD_ARGON2I);
            return [$hashPassword, $salt];
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * @throws AlreadyExistsException
     * @throws InternalErrorException
     */
    public function createNewUser($userDetails): ?\Models\User
    {
        if($this->CheckUserExistenceByEmail($userDetails->emailAddress)){
            throw new AlreadyExistsException("This email address already exist with us");
        }
        $hashPasswordWithSalt = $this->hashPassword($userDetails->password);
        $userDetails->hashPassword = $hashPasswordWithSalt[0];
        $userDetails->salt = $hashPasswordWithSalt[1];
        return $this->repository->insertUserInDatabase($userDetails);
    }

    public function CheckUserExistenceByEmail($email) :bool{
        return $this->repository->CheckUserEmailExistence($email);
    }
    public function getAll() :?array
    {
        return $this->repository->getAll();
    }

    /**
     * @throws InternalErrorException
     */
    public function isUserAdmin($userId): bool
    {
        $user = $this->getUserById($userId);
        if(empty($user)){
            throw new InternalErrorException("User Does not exist");
        }
        return $user->getRole()->getValue() === Roles::Admin()->getValue();
    }

    /**
     * @throws FileManagementException
     */
    public function deleteUser($userId): bool
    {
        $this->processUserAds($userId);
        return $this->repository->deleteUser($userId);
    }

    /**
     * @throws FileManagementException
     */
    public function processUserAds($userId) :void
    {
        if($this->repository->hasUserAds($userId)){
            $imagesNames =$this->repository->getAdImagesOfUser($userId);
            $this->deleteImagesFromDirectory($imagesNames,__DIR__ . "/../public");
        }

    }
}
