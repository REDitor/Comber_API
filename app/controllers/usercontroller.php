<?php

namespace Controllers;

use Exception;
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
            "username" => $user->username,
            "role" => $user->role,
            "expireAt" => $expire
        );
    }

//    public function login() {
//
//        // read user data from request body
//        $postedUser = $this->createObjectFromPostedJson("Models\\User");
//
//        // get user from db
//        //TODO: also add email
//        $user = $this->service->checkUsernamePassword($postedUser->username, $postedUser->password);
//
//        // if the method returned false, the username and/or password were incorrect
//        if(!$user) {
//            $this->respondWithError(401, "Invalid login");
//            return;
//        }
//
//        // generate jwt
//        $tokenResponse = $this->generateJwt($user);
//
//        $this->respond($tokenResponse);
//    }

//    public function generateJwt($user) {
//        $secret_key = "YOUR_SECRET_KEY";
//
//        $issuer = "THE_ISSUER"; // this can be the domain/servername that issues the token
//        $audience = "THE_AUDIENCE"; // this can be the domain/servername that checks the token
//
//        $issuedAt = time(); // issued at
//        $notBefore = $issuedAt; //not valid before
//        $expire = $issuedAt + 1800; // expiration time is set at +1800 seconds (30 minutes)
//
//        // JWT expiration times should be kept short (10-30 minutes)
//        // A refresh token system should be implemented if we want clients to stay logged in for longer periods
//
//        // note how these claims are 3 characters long to keep the JWT as small as possible
//        $payload = array(
//            "iss" => $issuer,
//            "aud" => $audience,
//            "iat" => $issuedAt,
//            "nbf" => $notBefore,
//            "exp" => $expire,
//            "data" => array(
//                "id" => $user->id,
//                "username" => $user->username,
//                "email" => $user->email
//        ));
//
//        $jwt = JWT::encode($payload, $secret_key, 'HS256');
//
//        return
//            array(
//                "message" => "Successful login.",
//                "jwt" => $jwt,
//                "username" => $user->username,
//                "expireAt" => $expire
//            );
//    }
}
