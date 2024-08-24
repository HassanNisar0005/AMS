<?php
// Start the session to get the admin's information
session_start();
include('db.php');


if (!isset($_SESSION['AdminID'])) {
    header('Location: admin_login.php');
    exit();
}

$attendance = [];
$message = '';
$error = '';
$editRecord = null;

// Fetch all attendance records
$sql = "SELECT a.AttendanceID, a.UserID, a.Date, a.Status, u.Username, u.Useremail 
        FROM attendance a 
        JOIN users u ON a.UserID = u.UserID";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
}

// Check if the user wants to edit an attendance record
if (isset($_GET['id'])) {
    $attendanceID = $_GET['id'];
    $sql = "SELECT * FROM attendance WHERE AttendanceID='$attendanceID'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $editRecord = $result->fetch_assoc();
    }
}

// Handle adding new attendance record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_attendance'])) {
    $userID = $conn->real_escape_string($_POST['user_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "SELECT * FROM attendance WHERE UserID='$userID' AND Date='$date'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $error = "Attendance record for this date already exists.";
    } else {
        $leaveSql = "SELECT * FROM leave_requests WHERE UserID='$userID' AND LeaveDate='$date' AND Status='Pending'";
        $leaveResult = $conn->query($leaveSql);

        if ($leaveResult->num_rows > 0 && $status !== 'Present') {
            $status = 'Absent';
        }

        $sql = "INSERT INTO attendance (UserID, Date, Status) VALUES ('$userID', '$date', '$status')";
        if ($conn->query($sql) === TRUE) {
            $message = "Attendance record added successfully!";
        } else {
            $error = "Error adding attendance record: " . $conn->error;
        }
    }
}

// Handle deleting an attendance record
if (isset($_GET['delete'])) {
    $attendanceID = $_GET['delete'];
    $sql = "DELETE FROM attendance WHERE AttendanceID='$attendanceID'";
    if ($conn->query($sql) === TRUE) {
        $message = "Attendance record deleted successfully!";
        header('Location: manage_attendance.php');
        exit();
    } else {
        $error = "Error deleting attendance record: " . $conn->error;
    }
}

// Handle editing an attendance record
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_attendance'])) {
    $attendanceID = $conn->real_escape_string($_POST['attendance_id']);
    $userID = $conn->real_escape_string($_POST['user_id']);
    $date = $conn->real_escape_string($_POST['date']);
    $status = $conn->real_escape_string($_POST['status']);

    $leaveSql = "SELECT * FROM leave_requests WHERE UserID='$userID' AND LeaveDate='$date' AND Status='Pending'";
    $leaveResult = $conn->query($leaveSql);

    if ($leaveResult->num_rows > 0 && $status !== 'Present') {
        $status = 'Absent';
    }

    $sql = "UPDATE attendance SET UserID='$userID', Date='$date', Status='$status' WHERE AttendanceID='$attendanceID'";
    if ($conn->query($sql) === TRUE) {
        $message = "Attendance record updated successfully!";
    } else {
        $error = "Error updating attendance record: " . $conn->error;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Attendance</title>
    <link rel="stylesheet" href="../css/manage_att.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    
<div class="row_mark mt-5 mb-5">
    <div class="container container_mark">
        <h2>Manage Attendance</h2>

        <!-- Display success or error messages -->
        <?php if (isset($message) && $message): ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error) && $error): ?>
            <div class="alert alert-danger">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <!-- Add Attendance Record Form -->
        <div id="addAttendanceForm">
            <h3>Add Attendance Record</h3>
            <form class="form_att p-2" method="POST" action="manage_attendance.php">
                <div class="form-group">
                    <label for="user_id">User:</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <?php
                        // Fetch user data from the database
                        $sql = "SELECT UserID, Useremail FROM users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['UserID'] . "'>" . $row['Useremail'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" id="date" name="date" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                </div>
                <button type="submit" name="add_attendance" class="mt-2 btn btn-primary">Add Attendance</button>
            </form>
        </div>

        <!-- Edit Attendance Record Form -->
        <?php if (isset($editRecord) && $editRecord): ?>
            <h3>Edit Attendance Record</h3>
            <form class="form_att" method="POST" action="manage_attendance.php">
                <input type="hidden" name="attendance_id" value="<?php echo $editRecord['AttendanceID']; ?>">
                <div class="form-group">
                    <label for="user_id">User:</label>
                    <select class="form-control" id="user_id" name="user_id" required>
                        <?php
                        // Fetch user data from the database
                        $sql = "SELECT UserID, Useremail FROM users";
                        $result = $conn->query($sql);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $selected = ($row['UserID'] == $editRecord['UserID']) ? 'selected' : '';
                                echo "<option value='" . $row['UserID'] . "' $selected>" . $row['Useremail'] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date:</label>
                    <input type="date" class="form-control" id="date" name="date"
                        value="<?php echo $editRecord['Date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status" required>
                        <option value="Present" <?php echo ($editRecord['Status'] == 'Present') ? 'selected' : ''; ?>>
                            Present</option>
                        <option value="Absent" <?php echo ($editRecord['Status'] == 'Absent') ? 'selected' : ''; ?>>Absent
                        </option>
                    </select>
                </div>
                <button type="submit" name="edit_attendance" class="mt-2 btn btn-primary">Update Attendance</button>
            </form>
        <?php endif; ?>

        <!-- Attendance Records Table -->
        <h3>Attendance Records</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Useremail</th>
                    <th>Username</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($attendance as $record): ?>
                    <tr>
                        <td><?php echo $record['Useremail']; ?></td>
                        <td><?php echo $record['Username']; ?></td>
                        <td><?php echo $record['Date']; ?></td>
                        <td><?php echo $record['Status']; ?></td>
                        <td>
                            <a href="manage_attendance.php?id=<?php echo $record['AttendanceID']; ?>"
                                class="btn btn-warning btn-sm edit-button">Edit</a>
                            <a href="manage_attendance.php?delete=<?php echo $record['AttendanceID']; ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Back to Dashboard -->
        <a href="../index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
    </div>
</div>

<!-- JavaScript to toggle forms -->
<script>
    const editButtons = document.querySelectorAll('.edit-button');
    const addAttendanceForm = document.getElementById('addAttendanceForm');

    // Check if an edit record exists, and hide the add form
    <?php if (isset($editRecord) && $editRecord): ?>
        addAttendanceForm.style.display = 'none';
    <?php endif; ?>

    // Attach event listeners to the edit buttons
    editButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            addAttendanceForm.style.display = 'none';
            // Optionally redirect to the edit form
            window.location.href = this.href;
        });
    });
</script>

    <?php include('../includes/footer.php'); ?>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>