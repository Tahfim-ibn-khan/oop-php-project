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


    // Function to check access and authentication
    private function log_status() {
        $log_status = $this->authentication->decodeToken();
        if($log_status != false){
            Response::json(['error' => 'You are in logged in state. Please Logout first'], 403);
            exit;
        }
    }

    public function register() {
        $this->log_status();
        $data = Response::requestBody();

        $requiredFields = ['name', 'email', 'password'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                return Response::json(['error' => "Please provide $field."]);
            }
        }

        // Validations for the fields

        $name = trim($data['name']);
        if (strlen($name) < 2 || strlen($name) > 50) {
            return Response::json(['error' => 'Name must be between 2 and 50 characters.']);
        }

        $email = trim($data['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Response::json(['error' => 'Invalid email format.']);
        }


        // Using regex to check the strength of the password
        // It needs to be 8 charected atleast, 1 digit or 1 special charecter, and 1 lower and upper case letter atleast.
        $password = trim($data['password']);
        $pattern = '/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/';
        if (!(preg_match($pattern, $password))) 
            {
                return Response::json(['Password must include one uppercase letter, one lowercase letter, one number, and one special character such as $ or %."']);
            } 

        // Check if email already exists
        if ($this->userModel->findByEmail($email)) {
            return Response::json(['error' => 'Email already registered.']);
        }

        $register = $this->userModel->register(
            $name,
            $email,
            $password
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
        $this->log_status();
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

    public function viewProfile() {
    $userId = $this->authentication->decodeToken('user_id');

    if (!$userId) {
        return Response::json(['error' => 'Authentication required'], 401);
    }

    $user = $this->userModel->findById($userId);

    if ($user) {
        return Response::json(['profile' => $user]);
    } else {
        return Response::json(['error' => 'User not found'], 404);
    }
}

}
