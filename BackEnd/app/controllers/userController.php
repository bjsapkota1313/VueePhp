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
                $this->respondWithError(500, "Internal Error Try Again Later");
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
    { // will egt all the user if it has admin role and if it is a user
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        $offset = null;
        $limit = null;

        if (isset($_GET["offset"]) && is_numeric($_GET["offset"])) {
            $offset = $_GET["offset"];
        }
        if (isset($_GET["limit"]) && is_numeric($_GET["limit"])) {
            $limit = $_GET["limit"];
        }
        try{
            if ($this->service->isUserAdmin($token->data->id)) {
                $users = $this->service->getAll($limit, $offset);
                if(empty($users)){
                    $this->respondWithError(204, "No Users Found");
                    return;
                }
                $this->respond($users);
                return;
            }
            $this->respondWithError(403, "You are not authorized to view this page");
            return;
        }
        catch(InternalErrorException $e){
            $this->respondWithError(500, "Internal Error");
        }

    }
    public function delete($id)
    {
        $token = $this->checkForJwt();
        if (empty($token)) {
            return;
        }
        try{
            if ($this->service->isUserAdmin($token->data->id)) {
                $this->service->deleteUser($id);
                $this->respond(true);
                return;
            }
            $this->respondWithError(403, "Currently you dont have permission to delete users");
            return;
        }
        catch(InternalErrorException |Exception){
            $this->respondWithError(500, "Internal Error Try Again Later");
        }
    }

    private function generateJwt($user): array
    {
        $secret_key = "YOUR_SECRET_KEY";
        $issuer = "OurMarket.com";
        $audience = "OurMarket.com/Website";
        $issuedAt = time(); // issued at
        $notBefore = $issuedAt; //not valid before
        $expire = $issuedAt + 1500; // expiration time is set at +600 seconds (25 min )

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
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
                "emailAddress" => $user->getEmail(),
                "expireAt" => $expire
            );
    }
}
