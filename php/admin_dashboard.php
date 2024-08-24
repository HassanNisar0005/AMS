<?php
// Start the session to get the admin's information
session_start();
include('db.php');

if (!isset($_SESSION['AdminID'])) {
    header('Location: admin_login.php');
    exit();
}


$users = [];
$attendance = [];
$grades = [];
$sNO = 1;

// Fetch all users
$sql = "SELECT * FROM users WHERE UserType='student'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Fetch all attendance records
$sql = "SELECT a.AttendanceID, a.UserID, a.Date, a.Status, u.Username FROM attendance a JOIN users u ON a.UserID = u.UserID";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $attendance[] = $row;
    }
}

// Handle grading system
$sql = "SELECT a.UserID, u.Useremail, COUNT(*) as DaysAttended 
        FROM attendance a 
        JOIN users u ON a.UserID = u.UserID 
        WHERE a.Status = 'Present' 
        GROUP BY a.UserID, u.Useremail";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $userID = $row['UserID'];
        $useremail = $row['Useremail'];
        $daysAttended = $row['DaysAttended'];

        if ($daysAttended >= 26) {
            $grade = 'A';
        } elseif ($daysAttended >= 20) {
            $grade = 'B';
        } elseif ($daysAttended >= 15) {
            $grade = 'C';
        } else {
            $grade = 'D';
        }

        $grades[] = ['Useremail' => $useremail, 'Grade' => $grade];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/admin_das.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_adm">
    <div class="container container_adm p-5">
        <h2>Admin Dashboard</h2>

        <h3>Manage Users</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Profile Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $sNO; ?></td>
                        <td><?php echo htmlspecialchars($user['Username']); ?></td>
                        <td><?php echo htmlspecialchars($user['Useremail']); ?></td>
                        <td><img src="images/<?php echo htmlspecialchars($user['ProfilePicture']); ?>" alt="Profile Picture"
                                width="50"></td>
                    </tr>
                    <?php
                    $sNO++;
                endforeach; ?>
            </tbody>
        </table>

        <h3>Grading System</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($grades as $grade): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($grade['Useremail']); ?></td>
                        <td><?php echo htmlspecialchars($grade['Grade']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="../index.php" class="btn btn-danger mt-3">Back</a>
    </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>