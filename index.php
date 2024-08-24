<?php
session_start();
include 'php/db.php';
// Fetch the current profile picture from the database
$sql = "SELECT ProfilePicture FROM users WHERE UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['UserID']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$currentPicture = $user['ProfilePicture'] ?? 'default.png';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Attendance Management System</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container mt-5">
        <div class="row p-5">
            <div class="col-md-12">
                <h1 class="text-center">Welcome to the Attendance Management System</h1>
                <p class="text-center">Manage your attendance, view records, and handle leave requests efficiently.</p>
                
                <?php if (!isset($_SESSION['UserID']) && !isset($_SESSION['AdminID'])): ?>
                    <div class="text-center mt-4">
                        <a href="user_login.php" class="btn btn-primary">User Login</a>
                        <a href="admin_login.php" class="btn btn-secondary">Admin Login</a>
                    </div>
                <?php elseif (isset($_SESSION['UserID'])): ?>
                    <div class="text-center mt-4">
                        <p>Welcome, <?php echo htmlspecialchars($_SESSION['Username']); ?>!</p>
                        <p>
                            <img id="profilePicture" src="images/<?php echo htmlspecialchars($currentPicture); ?>" alt="Profile Picture" width="100" height="100">
                        </p>
                        <a href="php/mark_attendance.php" class="btn btn-primary">Mark Attendance</a>
                        <a href="php/view_attendance.php" class="btn btn-secondary">View Attendance</a>
                        <a href="php/leave_request.php" class="btn btn-info">Leave Request</a>
                        <a href="php/edit_profile.php" class="btn btn-warning">Edit Profile</a>
                    </div>
                <?php elseif (isset($_SESSION['AdminID'])): ?>
                    <div class="text-center mt-4">
                        <p>Welcome, Admin!</p>
                        <a href="php/admin_dashboard.php" class="btn btn-primary">Admin Dashboard</a>
                        <a href="php/manage_attendance.php" class="btn btn-secondary">Manage Attendance</a>
                        <a href="php/generate_report.php" class="btn btn-info">Generate Report</a>
                        <a href="php/leave_approval.php" class="btn btn-warning">Leave Approval</a>
                        <a href="php/grading_system.php" class="btn btn-danger">Grading System</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include('includes/footer.php'); ?>
</body>
<script src="js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</html>
