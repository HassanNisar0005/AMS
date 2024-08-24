<?php
// Start the session to get the current user's information
session_start();
include('db.php');

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}


$message = '';
$error = '';


$userID = $_SESSION['UserID'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leaveReason = $_POST['leave_reason'];
    $leaveDate = $_POST['leave_date'];

    $sql = "INSERT INTO leave_requests (UserID, LeaveReason, LeaveDate, Status, CreatedAt) VALUES ('$userID', '$leaveReason', '$leaveDate', 'Pending', NOW())";

    if ($conn->query($sql) === TRUE) {
        $message = "Leave request submitted successfully!";
    } else {
        $error = "Error submitting leave request: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leave Request</title>
    <link rel="stylesheet" href="../css/leave_req.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_req">
    <div class="container container_req">
        <h2>Leave Request</h2>
        <form method="POST" action="leave_request.php">
            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <div class="form-group">
                <label for="leave_date">Leave Date:</label>
                <input type="date" class="form-control" id="leave_date" name="leave_date" required>
            </div>
            <div class="form-group">
                <label for="leave_reason">Leave Reason:</label>
                <textarea class="form-control" id="leave_reason" name="leave_reason" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Leave Request</button>
        </form>

        <a href="../index.php" class="btn btn-secondary mt-3">Go Back</a>
    </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>