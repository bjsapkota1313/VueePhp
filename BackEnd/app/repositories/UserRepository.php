<?php

namespace Repositories;

use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Models\Roles;
use Models\User;
use PDO;
use Repositories\AbstractRepository;

class UserRepository extends AbstractRepository
{
    /**
     * @throws NotFoundException
     */
    function verifyAndGetUser($email, $password)
    {
        $query = "SELECT id,firstName, lastName, email,HashPassword,Salt,role FROM Users WHERE email= :email";
        $params = array(
            ":email" => $email
        );
        $result = $this->executeQueryAndGetResult($query, $params, false); // since db does not contains any duplicate data
        if (!$this->checkUserExistenceByEmail($email)) {
            throw new NotFoundException("This {$email} email address does not exist With us.");
        }
        if ($this->verifyPassword($password, $result["HashPassword"], $result["Salt"])) {
            return new User($result["id"], $result["firstName"], $result["lastName"], $result["email"], Roles::fromString($result["role"]));
        }
        return null;

    }

    public function checkUserExistenceByEmail($email): bool
    {
        $query = "SELECT id,firstName, lastName, email,HashPassword,Salt,role FROM Users WHERE email= :email";
        return !empty($this->executeQueryAndGetResult($query, [":email" => $email]));
    }

    private function verifyPassword($enteredPassword, $hashedPassword, $salt): bool
    {
        return password_verify($enteredPassword . $salt, $hashedPassword);
    }

    public function getUserById($id): ?User
    {
        $query = "SELECT id,firstName, lastName, email,HashPassword,role FROM Users WHERE id= :id";
        $result = $this->executeQueryAndGetResult($query, array(":id" => $id), false);
        if (!empty($result)) {
            return new User($result["id"], $result["firstName"], $result["lastName"], $result["email"], Roles::fromString($result["role"]));
        }
        return null;
    }

    /**
     * @throws InternalErrorException
     */
    public function insertUserInDatabase($userDetails): User
    {
        $query = "INSERT INTO Users( firstName, lastName, email, HashPassword, Salt) VALUES (:firstName,:lastName,:email,:hashPassword,:salt)";
        $parameters = array(
            ":firstName" => $userDetails->firstName,
            ":lastName" => $userDetails->lastName,
            ":email" => $userDetails->emailAddress,
            ":hashPassword" => $userDetails->hashPassword,
            ":salt" => $userDetails->salt
        );
        $result = $this->executeQueryAndGetResult($query, $parameters, false, true);
        if (is_numeric($result)) {
            return $this->getUserById($result);
        }
        throw new InternalErrorException("Error Processing Request");
    }

    public function checkUserEmailExistence($email): bool
    {
        $query = "SELECT email From Users WHERE email= :email";
        return !empty($this->executeQueryAndGetResult($query, [":email" => $email]));
    }

    public function getAll($limit, $offset): ?array
    {
        $query = "SELECT id,firstName, lastName, email,HashPassword,role FROM Users";
        $parameters = array();
        if (!empty($this->buildLimitOffsetClause($limit, $offset))) {
            $query .= " LIMIT :limit OFFSET :offset";
            $parameters[":limit"] = (int)$limit;
            $parameters[":offset"] = (int)$offset;
        }
        $dbResult = $this->executeQueryAndGetResult($query, $parameters);
        if (!empty($dbResult)) {
            $users = [];
            foreach ($dbResult as $result) {
                $users[] = new User($result["id"], $result["firstName"], $result["lastName"], $result["email"], Roles::fromString($result["role"]));
            }
            return $users;
        }
        return null;
    }

    public function hasUserAds($userId): bool
    {
        $query = "SELECT id FROM Ads WHERE userId= :userId";
        return !empty($this->executeQueryAndGetResult($query, array(":userId" => $userId)));
    }

    public function getAdImagesOfUser($userId): ?array
    {
        $query = "SELECT imageURI FROM Ads WHERE userID=:userId";
        $dbResult = $this->executeQueryAndGetResult($query, array(":userId" => $userId));
        if (!empty($dbResult)) {
            $imagesNames = [];
            foreach ($dbResult as $result) {
                $imagesNames[] = $result["imageURI"];
            }
            return $imagesNames;
        }
        return null;
    }

    public function deleteUser($userId): bool
    {
        $query = "DELETE FROM Users WHERE id=:userId"; // database on delete cascade whole ads while be deleted when
        // user is deleted
        return $this->executeQueryAndGetResult($query, array(":userId" => $userId));
    }

}