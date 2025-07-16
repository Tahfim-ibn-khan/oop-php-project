<?php

namespace Models;

use Config\Database;

class User extends Database {

    // Issue found earlier:
    // Defining $conn in the class and initializing it in the constructor
    // was causing unintended connection failures due to premature DB connection.
    // Solution: Use method-based lazy connection for better control.
    // TODO: Need To understand the issue and discuss with Rocky bhaia

    private function getConnection() {
        return $this->connect();
    }

    // This function has been created to check the duplicacy of the email while registering in the DB for validation
    public function findByEmail($email) {
        $conn = $this->getConnection();

        /* Here The LIMIT clause could be escaped ad Email is unique.
        But what it does is after finding the first row match, it will stop looking.
        Unless it will iterarte through the whole thing which ois unnecessary.
        */
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function register($name, $email, $password, $role = 'Customer') {
        $conn = $this->getConnection();
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        $query = "INSERT INTO users (name, email, password, role)
                  VALUES (:name, :email, :password, :role)";
        $stmt = $conn->prepare($query);

        try {
            return $stmt->execute([
                'name'     => $name,
                'email'    => $email,
                'password' => $passwordHash,
                'role'     => $role
            ]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        $conn = $this->getConnection();
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $stmt = $conn->prepare($query);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }
}
