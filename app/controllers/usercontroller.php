<?php

namespace Controllers;

use Services\UserService;
use \Firebase\JWT\JWT;

class UserController extends Controller
{
    private $service;

    // initialize services
    function __construct()
    {
        $this->service = new UserService();
    }

    public function login() {
        // read user data from request body
        $postedUser = $this->createObjectFromPostedJson('Models\\User');

        // get user from db
        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);

        // if the method returns false, username and/password is incorrect
        if (!$user) {
            $this->respondWithError(401, 'Invalid Login');
            return;
        }

        // generate JWT
        $tokenResponse = $this->generateJwt($user);

        $this->respond($tokenResponse);
    }

//TODO: create JWT class to generate and check for JWT
    function generateJwt($user): array
    {
        $secretKey = 'shhh_dont_tell_anyone';

        // set values for payload
        $issuer = 'example.com';
        $audience = 'example.com';
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $issuedAt + 1800; // expires after 1800 seconds (30 min.)

        $payload = array(
            "iss" => $issuer,
            "aud" => $audience,
            "iat" => $issuedAt,
            "nbf" => $notBefore,
            "exp" => $expire,
            "data" => array(
                "id" => $user->id,
                "username" => $user->email,
                "email" => $user->email,
                "role" => $user->role
            )
        );

        $jwt = JWT::encode($payload, $secretKey, 'HS256');

        return array(
            "message" => "Successfull Login",
            "jwt" => $jwt,
            "id" => $user->id,
            "username" => $user->username,
            "role" => $user->role,
            "expireAt" => $expire
        );
    }
}
