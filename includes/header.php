<nav class="custom-navbar">
    <a class="custom-navbar-brand" href="index.php">
        <i class="fas fa-home"></i> Attendance Management System
    </a>
    <button class="custom-navbar-toggler" type="button" aria-controls="customNavbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="custom-toggler-icon"></span>
        <span class="custom-toggler-icon"></span>
        <span class="custom-toggler-icon"></span>
    </button>
    <div class="custom-navbar-collapse" id="customNavbarNav">
        <ul class="custom-navbar-nav mb-0">
            <?php if (isset($_SESSION['AdminID'])): ?>
                <!-- Admin Links -->
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/admin_dashboard.php">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/manage_attendance.php">
                        <i class="fas fa-tasks"></i> Attendance
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/generate_report.php">
                        <i class="fas fa-file-alt"></i>Report
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/leave_approval.php">
                        <i class="fas fa-check-circle"></i>Leaves
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/grading_system.php">
                        <i class="fas fa-graduation-cap"></i>Grading
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            <?php elseif (isset($_SESSION['UserID'])): ?>
                <!-- User Links -->
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/mark_attendance.php">
                        <i class="fas fa-check-square"></i> Mark Attendance
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/view_attendance.php">
                        <i class="fas fa-eye"></i> View Attendance
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/leave_request.php">
                        <i class="fas fa-paper-plane"></i> Leave Request
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/edit_profile.php">
                        <i class="fas fa-user-edit"></i> Edit Profile
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/php/logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            <?php else: ?>
                <!-- Public Links -->
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="user_login.php">
                        <i class="fas fa-user"></i> User Login
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="admin_login.php">
                        <i class="fas fa-user-shield"></i> Admin Login
                    </a>
                </li>
                <li class="custom-nav-item">
                    <a class="custom-nav-link" href="../attendance/sign_In.php">
                        <i class="fas fa-user-plus"></i> Student Register
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
