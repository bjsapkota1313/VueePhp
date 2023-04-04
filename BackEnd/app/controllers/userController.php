<?php

namespace Controllers;

use Exception;
use Models\Exceptions\AlreadyExistsException;
use Models\Exceptions\InternalErrorException;
use Models\Exceptions\NotFoundException;
use Services\UserService;
use \Firebase\JWT\JWT;

class UserController extends AbstractController
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login()
    {
        $postedUser = $this->getSanitizedData();
        // get user from db
        try {
            $user = $this->service->verifyAndGetUser($postedUser->emailAddress, $postedUser->password);
        } catch (NotFoundException $e) {
            $this->respondWithError(401, $e->getMessage());
            return;
        }
        // if the method returned false, the username and/or password were incorrect
        if (empty($user)) {
            $this->respondWithError(401, "Invalid Password Try Again");
            return;
        }
        // generate jwt
        $tokenResponse = $this->generateJwt($user);

        $this->respond($tokenResponse);
    }

    public function create()
    {
        $postedUser = $this->getSanitizedData();
        try {
            $user = $this->service->createNewUser($postedUser);
            if (empty($user)) {
                $this->respondWithError(500, "Internal Error");
                return;
            }
        } catch (AlreadyExistsException $e) {
            $this->respondWithError(409, $e->getMessage()); // conflict error
            return;
        } catch (InternalErrorException  $e) {
            $this->respondWithError(500, $e->getMessage()); // internal error
            return;
        }
        $this->respond($user);
    }

    public function getAll()
    {
        $users = $this->service->getAll();
        $this->respond($users); //TODO: add pagination
    }

    private function generateJwt($user): array
    {
        $secret_key = "YOUR_SECRET_KEY";
        $issuer = "OurMarket.com";
        $audience = "OurMarket.com/Website";
        $issuedAt = time(); // issued at
        $notbefore = $issuedAt; //not valid before 
        $expire = $issuedAt + 86400; // expiration time is set at +600 seconds (25 min ) //TODO: change to 25 min

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notbefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->getId(),
                "emailAddress" => $user->getEmail()
            ));

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        return
            array(
                "message" => "Successful login.",
                "jwt" => $jwt,
                "firstName" => $user->getFirstName(),
                "expireAt" => $expire
            );
    }
}
