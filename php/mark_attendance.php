<?php
// Start the session to get the current user's information
session_start();
include('db.php');

if (!isset($_SESSION['UserID'])) {
    header('Location: ./login.php');
    exit();
}

$userID = $_SESSION['UserID'];
$date = date('Y-m-d');

// Check if the user has already marked attendance for today
$sql = "SELECT * FROM attendance WHERE UserID='$userID' AND Date='$date'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $message = "You have already marked your attendance today.";
} else {
    $sql = "INSERT INTO attendance (UserID, Date, Status) VALUES ('$userID', '$date', 'Present')";

    if ($conn->query($sql) === TRUE) {
        $message = "Attendance marked successfully!";
    } else {
        $message = "Error marking attendance: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../css/mark_att.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_mark">
        <div class="container_mark p-5">
            <h2>Mark Attendance</h2>
            <div class="alert alert-info">
                <?php echo $message; ?>
            </div>
            <a href="../index.php" class="btn btn-primary">Go Back</a>
        </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>