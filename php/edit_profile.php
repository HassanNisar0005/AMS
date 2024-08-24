<?php
// Start the session to get the current user's information
session_start();
include('db.php');

if (!isset($_SESSION['UserID'])) {
    header('Location: login.php');
    exit();
}

$userID = $_SESSION['UserID'];

// Fetch the current profile picture
$sql = "SELECT ProfilePicture FROM users WHERE UserID='$userID'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $currentPicture = $row['ProfilePicture'];
} else {
    $currentPicture = 'default.png';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle the profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $targetDir = "../images/";
        $fileName = basename($_FILES["profile_picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Allow only certain file formats
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowedTypes)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                $sql = "UPDATE users SET ProfilePicture='$fileName' WHERE UserID='$userID'";
                if ($conn->query($sql) === TRUE) {
                    if ($currentPicture != 'default.png' && file_exists("..images/" . $currentPicture)) {
                        unlink("../images/" . $currentPicture);
                    }
                    $_SESSION['message'] = "Profile picture updated successfully!";
                    header('Location: edit_profile.php');
                    exit();
                } else {
                    $error = "Error updating profile picture: " . $conn->error;
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $error = "Only JPG, JPEG, PNG, and GIF files are allowed.";
        }
    } else {
        $error = "Please select a file to upload.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../css/edit_pf.css">
    <link rel="stylesheet" href="../css/navbar.css">
    <link rel="stylesheet" href="../css/footer.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>

<body>
    <?php include('../includes/admin_header.php'); ?>
    <div class="row_edit">
        <div class="container container_edit p-5">

            <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
                <h2>Edit Profile</h2>
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert alert-success">
                        <?php
                        echo $_SESSION['message'];
                        unset($_SESSION['message']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="profile_picture">Profile Picture:</label>
                    <img src="../images/<?php echo $currentPicture; ?>" alt="Profile Picture" width="150">
                    <input type="file" class="form-control-file" id="profile_picture" name="profile_picture" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Profile Picture</button>
            </form>

            <a href="../index.php" class="btn btn-secondary mt-3">Go Back</a>
        </div>
    </div>
    <script src="../js/navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php include('../includes/footer.php'); ?>
</body>

</html>