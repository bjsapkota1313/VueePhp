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
        $user = null;
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

    public function checkUserExistenceByEmail($email)
    {
        $query = "SELECT id,firstName, lastName, email,HashPassword,Salt,role FROM Users WHERE email= :email";
        $params = array(
            ":email" => $email
        );
        $result = $this->executeQueryAndGetResult($query, $params);
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    private function checkUserExistence($stmt): bool
    {
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    private function verifyPassword($enteredPassword, $hashedPassword, $salt): bool
    {
        return password_verify($enteredPassword . $salt, $hashedPassword);
    }

    public function getUserById($id)
    {
        try {
            $query = "SELECT id,firstName, lastName, email,HashPassword,role FROM Users WHERE id= :id";
            $result = $this->executeQueryAndGetResult($query, array(":id" => $id), false);
            if (!empty($result)) {
                return new User($result["id"], $result["firstName"], $result["lastName"], $result["email"], Roles::fromString($result["role"]));
            }
            return null;
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    /**
     * @throws InternalErrorException
     */
    public function insertUserInDatabase($userDetails): User
    {
        try {
            $query = "INSERT INTO Users( firstName, lastName, email, HashPassword, Salt) VALUES (:firstName,:lastName,:email,:hashPassword,:salt)";
            $parameters = array(
                ":firstName" => $userDetails->firstName,
                ":lastName" => $userDetails->lastName,
                ":email" => $userDetails->emailAddress,
                ":hashPassword" => $userDetails->hashPassword,
                ":salt" => $userDetails->salt
            );
           $result= $this->executeQueryAndGetResult($query, $parameters, false, true);
            if(is_numeric($result)) {
              return $this->getUserById($result);
            }
            throw new InternalErrorException("Error Processing Request", );
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }

    }

    public function CheckUserEmailExistence($email): bool
    {
        try {
            $stmt = $this->connection->prepare("SELECT email From Users WHERE email= :email");
            $stmt->bindValue(":email", $email);
            return $this->checkUserExistence($stmt);
        } catch (PDOException $e) {
            $message = '[' . date("F j, Y, g:i a e O") . ']' . $e->getMessage() . $e->getCode() . $e->getFile() . ' Line ' . $e->getLine() . PHP_EOL;
            error_log("Database connection failed: " . $message, 3, __DIR__ . "/../Errors/error.log");
            http_response_code(500);
            exit();
        }
    }

    public function getAll()
    {
        try {
            $stmt =
            $stmt = $this->connection->prepare("SELECT * FROM Users");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\\User');
            return $stmt->fetchAll();
        } catch (PDOException $e) {

        }
    }

}