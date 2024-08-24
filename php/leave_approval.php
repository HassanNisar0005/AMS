<?php
// Start the session to get the admin's information
session_start();
include('db.php');

if (!isset($_SESSION['AdminID'])) {
    header('Location: admin_login.php');
    exit();
}

$leaveRequests = [];
$message = '';
$error = '';
$sNo = 1;

// Fetch leave requests from the database
$sql = "SELECT lr.LeaveID, lr.UserID, lr.LeaveReason, lr.LeaveDate, lr.Status, u.Username FROM leave_requests lr JOIN users u ON lr.UserID = u.UserID";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $leaveRequests[] = $row;
    }
}

// Handle leave approval or rejection
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $leaveID = $_POST['leave_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $status = 'Approved';
    } elseif ($action == 'reject') {
        $status = 'Rejected';
    } else {
        $error = "Invalid action.";
    }

    if (!$error) {
        $sql = "UPDATE leave_requests SET Status='$status' WHERE LeaveID='$leaveID'";
        if ($conn->query($sql) === TRUE) {
            $message = "Leave request $status successfully!";
        } else {
            $error = "Error updating leave request: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Leave Approval</title>
    <link rel="stylesheet" href="../css/leave_app.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_app">
        <div class="container container_app">
            <h2>Leave Approval</h2>

            <?php if ($message): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>S.no</th>
                        <th>Username</th>
                        <th>Leave Reason</th>
                        <th>Leave Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($leaveRequests as $request): ?>
                        <tr>
                            <td><?php echo $sNo++; ?></td>
                            <td><?php echo htmlspecialchars($request['Username']); ?></td>
                            <td><?php echo htmlspecialchars($request['LeaveReason']); ?></td>
                            <td><?php echo htmlspecialchars($request['LeaveDate']); ?></td>
                            <td><?php echo htmlspecialchars($request['Status']); ?></td>
                            <td>
                                <?php if ($request['Status'] == 'pending'): ?>
                                    <form method="POST" action="leave_approval.php" style="display:inline;">
                                        <input type="hidden" name="leave_id"
                                            value="<?php echo htmlspecialchars($request['LeaveID']); ?>">
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-approve">Approve</button>
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-reject">Reject</button>
                                    </form>
                                <?php else: ?>
                                    <span
                                        class="badge badge-secondary"><?php echo htmlspecialchars($request['Status']); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="../index.php" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>