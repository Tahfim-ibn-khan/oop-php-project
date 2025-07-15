<?php
namespace Controllers;

use Models\User;
use Helpers\Response;
use Helpers\Authentication;

class UserController {
    private $userModel;
    private $authentication;

    public function __construct(User $user, Authentication $authentication) {
        $this->userModel = $user;
        $this->authentication = $authentication;
    }

    public function register() {
        $data = Response::requestBody();

        $requiredFields = ['name', 'email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return Response::json(['error' => "Please provide $field."]);
            }
        }

        // Validate name
        $name = trim($data['name']);
        if (strlen($name) < 2 || strlen($name) > 50) {
            return Response::json(['error' => 'Name must be between 2 and 50 characters.']);
        }

        // Validate email format
        $email = trim($data['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Response::json(['error' => 'Invalid email format.']);
        }

        // Validate password strength
        $password = trim($data['password']);
        if (strlen($password) < 8) {
            return Response::json(['error' => 'Password must be at least 8 characters long.']);
        }

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            return Response::json(['error' => 'Email already registered.']);
        }

        $register = $this->userModel->register(
            $name,
            $email,
            $password // Pass raw password
        );

        if ($register) {
            return Response::json([
                'message' => 'User registration successful.'
            ]);
        } else {
            return Response::json([
                'error' => 'User registration failed.'
            ]);
        }
    }

    public function login() {
        $data = Response::requestBody();

        $requiredFields = ['email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return Response::json(['error' => "Please provide $field"]);
            }
        }

        $user = $this->userModel->login(trim($data['email']), trim($data['password']));

        if ($user) {
            $token = $this->authentication->generateToken(
                $user['id'],
                $user['name'],
                $user['email'],
                $user['role']
            );

            if (!$token) {
                return Response::json(['error' => 'Access Denied']);
            }

            return Response::json(['token' => $token]);
        } else {
            return Response::json(['error' => 'Login Failed']);
        }
    }
}
