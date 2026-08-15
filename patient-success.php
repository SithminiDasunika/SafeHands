<?php
// Patient Profile Created Successfully - SafeHands
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile Created | SafeHands</title>
    <link rel="stylesheet" href="assets/css/add-patient.css">
</head>
<body>

<header class="navbar">
    <div class="nav-inner">
        <a href="dashboard.php" class="brand">SafeHands</a>

        <nav class="nav-links">
            <a href="dashboard.php">Dashboard</a>
            <a href="patients.php" class="active">Patients</a>
            <a href="../caregivers.php">Find Caregivers</a>
            <a href="booking.details.php">My Bookings</a>
        </nav>

        <div class="nav-actions">
            <button type="button" class="icon-btn" aria-label="Notifications">♢</button>
            <button type="button" class="avatar-btn" aria-label="Profile">S</button>
        </div>
    </div>
</header>

<main class="page">
    <div class="success-card">

        <div class="success-icon" aria-hidden="true">
            ✓
        </div>

        <div class="eyebrow">PATIENT PROFILE</div>

        <h1>Patient Profile Created Successfully</h1>

        <p class="intro">
            Johnathan Doe's profile is now ready. You can immediately start
            searching for matched caregivers or upload more medical history.
        </p>

        <div class="patient-summary">
            <div class="patient-avatar" id="patient-avatar">JD</div>

            <div class="patient-info">
                <h2 id="patient-name">Johnathan Doe</h2>
                <p>Patient ID: <strong>SH-2026-8842</strong></p>

                <div class="badges">
                    <span class="badge badge-blue">A+ Blood</span>
                    <span class="badge badge-green">Verified</span>
                </div>
            </div>
        </div>

        <div class="next-info">
            <div class="info-icon">✓</div>
            <div>
                <h3>What's next?</h3>
                <p>
                    Find a suitable caregiver and continue with the booking
                    process for this patient.
                </p>
            </div>
        </div>

        <div class="actions">
            <a href="../caregivers.php" class="btn btn-primary">
                Find a Caregiver
                <span>→</span>
            </a>

            <a href="patient-profile.php" class="btn btn-secondary">
                View Patient Profile
            </a>

            <a href="dashboard.php" class="btn btn-outline">
                Return to Dashboard
            </a>
        </div>

        <a href="patients.php" class="back-link">← Back to Patients</a>

    </div>
</main>

<footer class="footer">
    <div class="footer-inner">
        <strong>SafeHands</strong>

        <div class="footer-links">
            <a href="#">Terms of Service</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Escrow Terms</a>
            <a href="#">Contact Support</a>
        </div>

        <span>© 2026 SafeHands Healthcare. All rights reserved.</span>
    </div>
</footer>

<script src="assets/js/add-patient.js"></script>
</body>
</html>
