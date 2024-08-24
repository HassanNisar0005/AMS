<?php
// Start the session to get the admin's information
session_start();
include('db.php');

if (!isset($_SESSION['AdminID'])) {
    header('Location: admin_login.php');
    exit();
}

$reports = [];
$message = '';
$error = '';

// Handle report generation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['generate_report'])) {
    $fromDate = $_POST['from_date'];
    $toDate = $_POST['to_date'];

    if (strtotime($fromDate) && strtotime($toDate) && $fromDate <= $toDate) {
        $sql = "SELECT u.Username, u.Useremail, a.Date, a.Status 
        FROM attendance a 
        JOIN users u ON a.UserID = u.UserID 
        WHERE a.Date BETWEEN '$fromDate' AND '$toDate' 
        ORDER BY a.Date";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $reports[] = $row;
            }
        } else {
            $message = "No records found for the specified date range.";
        }
    } else {
        $error = "Invalid date range. Please ensure the 'From' date is less than or equal to the 'To' date.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generate Report</title>
    <link rel="stylesheet" href="../css/generate_report.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_gen">
        <div class="container container_gen">
            <h2>Generate Attendance Report</h2>

            <?php if ($message): ?>
                <div class="alert alert-info">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="generate_report.php">
                <div class="form-group">
                    <label for="from_date">From Date:</label>
                    <input type="date" class="form-control" id="from_date" name="from_date" required>
                </div>
                <div class="form-group">
                    <label for="to_date">To Date:</label>
                    <input type="date" class="form-control" id="to_date" name="to_date" required>
                </div>
                <button type="submit" name="generate_report" class="mt-2 btn btn-primary">Generate Report</button>
            </form>

            <?php
            function compareByDate($a, $b)
            {
                return strcmp($a['Date'], $b['Date']);
            }

            usort($reports, 'compareByDate');
            if (!empty($reports)):
                ?>
                <h3>Attendance Report</h3>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reports as $report): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($report['Useremail']); ?></td>
                                <td><?php echo htmlspecialchars($report['Date']); ?></td>
                                <td><?php echo htmlspecialchars($report['Status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <a href="../index.php" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>