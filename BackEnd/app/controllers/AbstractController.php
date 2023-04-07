<?php

namespace Controllers;

use DateTime;
use Exception;
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

abstract class AbstractController
{
    function checkForJwt()
    {
        // Check for token header
        if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $this->respondWithError(401, "No token provided");
            return;
        }

        // Read JWT from header
        $authHeader = $_SERVER['HTTP_AUTHORIZATION'];

        // Strip the "Bearer " prefix from the header
        $jwt = str_replace("Bearer ", "", $authHeader);

        // Decode JWT
        $secret_key = "YOUR_SECRET_KEY";

        try {
            return JWT::decode($jwt, new Key($secret_key, 'HS256'));

        } catch (Exception $e) {
            $this->respondWithError(401, $e->getMessage());
            return;
        }
    }


    function respond($data)
    {
        $this->respondWithCode(200, $data);
    }

    function respondWithError($httpCode, $message)
    {
        $data = array('errorMessage' => $message);
        $this->respondWithCode($httpCode, $data);
    }
    protected function respondWithCode($httpCode, $data): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($httpCode);
        echo json_encode($data);
    }
    protected function getSanitizedData()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json);
        return $this->sanitize($data);
    }

    protected function sanitize($data)
    {
        if (is_object($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data->$key = htmlspecialchars($value);
                }
            }
        } else if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_string($value)) {
                    $data[$key] = htmlspecialchars($value);
                }
            }
        }
        return $data;
    }
}
