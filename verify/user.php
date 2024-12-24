<?php
class User {
    private $conn;

    // Constructor to initialize database connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Login function
    public function login($email, $password) {
        $sql = "SELECT id, name, password, usertype_id FROM users WHERE email = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $email);

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $name, $hashed_password, $usertypeid);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            // Start session and set session variables
                            if (session_status() == PHP_SESSION_NONE) {
                                session_start();
                            }
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["name"] = $name;
                            $_SESSION["usertypeid"] = $usertypeid;

                            // Redirect based on usertype_id
                            switch ($usertypeid) {
                                case 0:
                                    header("Location: ../Homepage.php");
                                    break;
                                case 1:
                                    header("Location: ../Admin/Admin.php");
                                    break;
                                case 2:
                                    header("Location: ../Admin/Suppliers.php");
                                    break;
                            }
                            exit();
                        } else {
                            return "The password you entered was not valid.";
                        }
                    }
                } else {
                    return "No account found with that email.";
                }
            } else {
                return "Oops! Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
        return null;
    }

    // Register function
    public function register($name, $email, $password, $confirm_password) {
        if ($password !== $confirm_password) {
            return "Passwords do not match.";
        }

        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $this->conn->prepare($sql)) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                return "This email is already in use.";
            }
            $stmt->close();
        }

        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
        if ($stmt = $this->conn->prepare($sql)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                return true;
            } else {
                return "Registration failed. Please try again.";
            }
        }
        return null;
    }


     // Edit user details
     public function edit($user_id, $name, $email, $password = null, $role) {
        $usertype_id = ($role === 'admin') ? 1 : 0;

        if (!empty($password)) {
            // Validate password
            if (!preg_match('/[A-Z]/', $password) || 
                !preg_match('/[0-9]/', $password) || 
                !preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password) || 
                strlen($password) < 8) {
                return "Password must be at least 8 characters long and include one uppercase letter, one number, and one special character.";
            }

            // Hash the new password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET name = ?, email = ?, password = ?, usertype_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("sssii", $name, $email, $hashed_password, $usertype_id, $user_id);
        } else {
            // If no password is provided, do not update the password field
            $sql = "UPDATE users SET name = ?, email = ?, usertype_id = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ssii", $name, $email, $usertype_id, $user_id);
        }

        if ($stmt->execute()) {
            return true;
        } else {
            return "Error updating user!";
        }
    }
}
?>
