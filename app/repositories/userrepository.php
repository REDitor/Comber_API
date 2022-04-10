<?php

namespace Repositories;

use PDO;
use PDOException;

class UserRepository extends Repository
{
    private string $login = "SELECT id, username, email, password, role 
                                FROM users 
                                WHERE username = :username
                                OR email = :username";

    function checkUsernamePassword($username, $password)
    {
        try {
            // retrieve the user with the given username
            $stmt = $this->connection->prepare($this->login);
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Models\User');
            $user = $stmt->fetch();

            // verify if the password matches the hash in the database
            $result = $this->verifyPassword($password, $user->password);

            if (!$result)
                return false;

            // do not pass the password hash to the caller
            $user->password = "";

            return $user;
        } catch (PDOException $e) {
            echo $e;
        }
    }

    // hash the password (currently uses bcrypt)
    function hashPassword($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    // verify the password hash
    function verifyPassword($input, $hash): bool
    {
        return password_verify($input, $hash);
    }
}
