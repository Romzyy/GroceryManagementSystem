<?php
require_once '../classes/DataHandler.php';

class Authentication {
    private $dataHandler;

    public function __construct() {
        $this->dataHandler = new DataHandler();
    }

    public function register($username, $password, $email, $role) {
        
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            setcookie('role', $role, time() + (86400 * 30), "/");
            return true;
        } else {
            return false;
        }
    }

    public function login($username, $password, $rememberMe = false) {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                if ($rememberMe) {
                    setcookie('user_id', $user['id'], time() + (86400 * 30), "/");
                    setcookie('role', $user['role'], time() + (86400 * 30), "/");
                }

                return true;
            } else {
                echo "Password verification failed.<br>";
            }
        } else {
            echo "User not found.<br>";
        }
        return false;
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        setcookie('user_id', '', time() - 3600, "/");
        setcookie('role', '', time() - 3600, "/");
    }

    public function getProfile($user_id) {
        $query = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($user_id, $username, $email) {
        $query = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->dataHandler->prepareStatement($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $user_id);
        return $stmt->execute();
    }
}
?>