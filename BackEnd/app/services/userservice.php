<?php
namespace Services;

use Exception;
use Models\Exceptions\AlreadyExistsException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Repositories\UserRepository;

class UserService
{
    private $repository;


    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @throws NotFoundException
     */
    public function verifyAndGetUser($email, $password)
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
}
