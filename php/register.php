<?php
// Start the session
session_start();
include('db.php'); 

$error = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($email) || empty($password)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } else {
        $sql = "SELECT * FROM users WHERE Username = ? OR Useremail = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Username or email already exists.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (Username, Useremail, Password, UserType) VALUES (?, ?, ?, 'student')";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss', $username, $email, $hashedPassword);

            if ($stmt->execute()) {
                $_SESSION['success_message'] = 'Registration successful! Please log in.';
                header('Location: ../user_login.php');
                exit();
            } else {
                $error = 'Error registering user: ' . $conn->error;
            }
        }
    }
}

// Close database connection
$conn->close();
?>
