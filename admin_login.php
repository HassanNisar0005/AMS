<?php
session_start();
include('php/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $useremail = $_POST['useremail'];
    $password = $_POST['password'];

    if (!empty($useremail) && !empty($password)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE Useremail = ? AND UserType = 'admin'");
        $stmt->bind_param("s", $useremail);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['AdminID'] = $user['UserID'];
            $_SESSION['useremail'] = $user['Username'];

            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid useremail or password.";
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Attendance Management System</title>
    <link rel="stylesheet" href="css/admin_login.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <div class="row justify-content-center">
            <form action="admin_login.php" method="POST" class="custom-form">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <h2 class="text-center">Admin Login</h2>
                <div class="form-group">
                    <label for="useremail">Admin Email</label>
                    <input type="text" id="useremail" name="useremail" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Login</button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php" class="btn btn-secondary">Back to Home</a>
            </div>
        </div>
    </div>


    <?php include('includes/footer.php'); ?>
    <script src="js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>