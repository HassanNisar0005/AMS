<?php
// Start the session to get the admin's information
session_start();
include('db.php');

if (!isset($_SESSION['AdminID'])) {
    header('Location: admin_login.php');
    exit();
}

$grading = [];
$message = '';
$error = '';

// Fetch grading thresholds from the database
$sql = "SELECT * FROM grading_system";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $grading[$row['Grade']] = $row['Threshold'];
    }
} else {
    $grading = [
        'A' => 26,
        'B' => 21,
        'C' => 16,
        'D' => 11
    ];
}

// Handle updating grading thresholds
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_grading'])) {
    $gradeA = $_POST['grade_A'];
    $gradeB = $_POST['grade_B'];
    $gradeC = $_POST['grade_C'];
    $gradeD = $_POST['grade_D'];

    if (is_numeric($gradeA) && is_numeric($gradeB) && is_numeric($gradeC) && is_numeric($gradeD)) {
        $conn->query("DELETE FROM grading_system");

        $sql = "INSERT INTO grading_system (Grade, Threshold) VALUES
            ('A', '$gradeA'),
            ('B', '$gradeB'),
            ('C', '$gradeC'),
            ('D', '$gradeD')";

        if ($conn->query($sql) === TRUE) {
            $message = "Grading thresholds updated successfully!";
        } else {
            $error = "Error updating grading thresholds: " . $conn->error;
        }
    } else {
        $error = "Please enter valid numeric values for grading thresholds.";
    }
}

// Function to calculate grade based on attendance
function getGrade($attendance, $grading)
{
    if ($attendance >= $grading['A'])
        return 'A';
    if ($attendance >= $grading['B'])
        return 'B';
    if ($attendance >= $grading['C'])
        return 'C';
    if ($attendance >= $grading['D'])
        return 'D';
    return 'F';
}

// Fetch users and their attendance count
$sql = "SELECT u.UserID, u.Username, u.Useremail, COUNT(a.AttendanceID) AS AttendanceCount 
        FROM users u 
        LEFT JOIN attendance a ON u.UserID = a.UserID AND a.Status = 'Present' 
        WHERE u.UserType = 'student'
        GROUP BY u.UserID";

$result = $conn->query($sql);
$users = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['Grade'] = getGrade($row['AttendanceCount'], $grading);
        $users[] = $row;
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Grading System</title>
    <link rel="stylesheet" href="../css/grading.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_gra mt-4 mb-2">
        <div class="container container_gra">
            <h3>Grading System</h3>

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

            <h3>Update Grading Thresholds</h3>
            <form method="POST" action="grading_system.php">
                <div class="form-group">
                    <label for="grade_A">Grade A (Days):</label>
                    <input type="number" class="form-control" id="grade_A" name="grade_A"
                        value="<?php echo $grading['A']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="grade_B">Grade B (Days):</label>
                    <input type="number" class="form-control" id="grade_B" name="grade_B"
                        value="<?php echo $grading['B']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="grade_C">Grade C (Days):</label>
                    <input type="number" class="form-control" id="grade_C" name="grade_C"
                        value="<?php echo $grading['C']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="grade_D">Grade D (Days):</label>
                    <input type="number" class="form-control" id="grade_D" name="grade_D"
                        value="<?php echo $grading['D']; ?>" required>
                </div>
                <button type="submit" name="update_grading" class="mt-2 btn btn-primary">Update Grading</button>
            </form>

            <h3 class="mt-2">Users and Their Grades</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Useremail</th>
                        <th>Attendance Count</th>
                        <th>Grade</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['Username']; ?></td>
                            <td><?php echo $user['Useremail']; ?></td>
                            <td><?php echo $user['AttendanceCount']; ?></td>
                            <td><?php echo $user['Grade']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <a href="../index.php" class="btn btn-secondary mt-3">Back to Dashboard</a>
        </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <div><?php include('../includes/footer.php'); ?></div>
</body>

</html>