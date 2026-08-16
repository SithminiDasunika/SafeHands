<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Patient Profile | SafeHands</title>

    <link rel="stylesheet" href="assets/css/patient-profile.css">
</head>

<body>

<header class="top-nav">
    <div class="nav-container">

        <div class="nav-left">
            <a href="family/dashboard.php" class="logo">SafeHands</a>

            <nav class="nav-links">
                <a href="family/dashboard.php">Dashboard</a>
                <a href="patient-profile.php" class="active">Patients</a>
                <a href="find-caregivers.php">Find Caregivers</a>
                <a href="booking.details.php">My Bookings</a>
            </nav>
        </div>

        <div class="nav-icons">
            <button class="icon-button" type="button">Notifications</button>
            <button class="icon-button" type="button">Account</button>
        </div>

    </div>
</header>


<main class="container">

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <span>Dashboard</span>
        <span>›</span>
        <span>Patients</span>
        <span>›</span>
        <span class="current">Patient Profile</span>
    </nav>


    <!-- Patient Header -->
    <section class="profile-header">

        <div class="profile-info">

            <div class="profile-image">
                <img
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuB3rlE5jgmkVVCDJVNMPlVxNLfET355V_a56atQQ1-7CeyDKuceB899Jvhj_C4WT9jB1NEANvobidKs-ymeOmWdKkE1zf1YboP57kLu4J3c37FXnLj9L3dHn50C0PWE_lcvVcpTtw1VJr5JSYPglXOoxIg97d62U3J7uc-jsnTYTDBrCDQ1_zYtWxFLZ8dCxiUXhVzXMLEGyF5Fy-3E2_KFHZrJctBx6f3LEq-7VnYDQJScTeKln8AicIrTAPQ__jJxYiJJ1KZk74Q"
                    alt="Patient profile">
            </div>

            <div>
                <div class="name-status">
                    <h1>Mr. Silva</h1>
                    <span class="status success">
                        Currently Receiving Care
                    </span>
                </div>

                <p class="patient-summary">
                    Age: 78 • Gender: Male • Blood Group: A+ • Relationship: Father
                </p>
            </div>

        </div>


        <div class="header-actions">
            <button
                class="btn btn-secondary"
                type="button"
                onclick="window.location.href='patient-edit.php'">
                Edit Profile
            </button>

            <button
                class="btn btn-primary"
                type="button"
                onclick="window.location.href='medical-history.php'">
                View Medical History
            </button>
        </div>

    </section>


    <!-- Main Bento Grid -->
    <div class="main-grid">

        <!-- LEFT COLUMN -->
        <div class="left-column">

            <!-- About Patient -->
            <section class="card">

                <h2 class="card-title">
                    About Patient
                </h2>

                <div class="info-list">

                    <div class="info-item">
                        <span>Full Name</span>
                        <strong>Mr. Ananda Silva</strong>
                    </div>

                    <div class="info-item">
                        <span>Date of Birth</span>
                        <strong>12 May 1948</strong>
                    </div>

                    <div class="info-item">
                        <span>NIC Number</span>
                        <strong>481324567V</strong>
                    </div>

                    <div class="info-item">
                        <span>Phone</span>
                        <strong>+94 77 123 4567</strong>
                    </div>

                    <div class="info-item">
                        <span>Address</span>
                        <strong>No. 45, Flower Road, Colombo 07</strong>
                    </div>

                </div>

            </section>


            <!-- Emergency Contact -->
            <section class="card emergency-card">

                <h2 class="card-title emergency-title">
                    Emergency Contact
                </h2>

                <div class="emergency-list">

                    <div>
                        <span>Name</span>
                        <strong>Sithmini Silva</strong>
                    </div>

                    <div>
                        <span>Relationship</span>
                        <strong>Daughter</strong>
                    </div>

                    <div>
                        <span>Primary Phone</span>
                        <strong class="phone">+94 77 987 6543</strong>
                    </div>

                    <div>
                        <span>Alt Phone</span>
                        <strong>+94 11 234 5678</strong>
                    </div>

                </div>

            </section>

        </div>


        <!-- RIGHT COLUMN -->
        <div class="right-column">

            <!-- Vitals -->
            <div class="vitals-grid">

                <div class="vital-card">
                    <span class="vital-label">Weight</span>
                    <strong>68 <small>kg</small></strong>
                </div>

                <div class="vital-card">
                    <span class="vital-label">Blood Pressure</span>
                    <strong>130/85 <small>mmHg</small></strong>
                </div>

                <div class="vital-card">
                    <span class="vital-label">Condition</span>
                    <strong>Hypertension</strong>
                </div>

                <div class="vital-card">
                    <span class="vital-label">Last Check</span>
                    <strong>10 June 2026</strong>
                </div>

            </div>


            <!-- Medical Information -->
            <section class="card">

                <h2 class="card-title">
                    Medical Information
                </h2>

                <div class="medical-grid">

                    <div>
                        <span class="section-label">Active Conditions</span>

                        <div class="tags">
                            <span>Hypertension</span>
                            <span>Mild Arthritis</span>
                        </div>
                    </div>


                    <div>
                        <span class="section-label">Allergies</span>
                        <strong class="allergy">Penicillin</strong>
                    </div>


                    <div>
                        <span class="section-label">Current Medications</span>
                        <p>Lisinopril 10mg daily (Morning)</p>
                    </div>


                    <div>
                        <span class="section-label">Mobility</span>
                        <p>Independent with walking cane</p>
                    </div>


                    <div class="full-width">

                        <span class="section-label">
                            Special Care Instructions
                        </span>

                        <ul>
                            <li>Strict low salt diet (DASH diet compliant)</li>
                            <li>Assist with light morning stretches for arthritis management</li>
                            <li>Monitor fluid intake throughout the day</li>
                        </ul>

                    </div>

                </div>

            </section>


            <!-- Care History -->
            <section class="card history-card">

                <div class="card-header">
                    <h2 class="card-title">
                        Care History & Reports
                    </h2>

                    <button
                        class="text-button"
                        type="button"
                        onclick="viewAllHistory()">
                        View All History
                    </button>
                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Caregiver</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            <tr>
                                <td>08 July 2026</td>
                                <td>Nadeesha Perera</td>
                                <td>4 Hours</td>
                                <td>
                                    <span class="status success">
                                        Completed
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="text-button"
                                        onclick="viewReport('Nadeesha Perera')">
                                        View Report
                                    </button>
                                </td>
                            </tr>

                            <tr>
                                <td>05 July 2026</td>
                                <td>Sunil Jayasuriya</td>
                                <td>8 Hours</td>
                                <td>
                                    <span class="status success">
                                        Completed
                                    </span>
                                </td>
                                <td>
                                    <button
                                        class="text-button"
                                        onclick="viewReport('Sunil Jayasuriya')">
                                        View Report
                                    </button>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>


                <div class="report-snippet">

                    <span class="section-label">
                        Recent Report Snippet
                    </span>

                    <p>
                        "Patient was cooperative during the morning session.
                        Completed 15 mins of light stretches. Medication adherence
                        was perfect. Appetite was good for lunch. Slight swelling
                        noticed in ankles." - Nadeesha P.
                    </p>

                </div>

            </section>

        </div>

    </div>


    <!-- Lower Section -->
    <div class="lower-grid">

        <!-- Upcoming Sessions -->
        <section class="card">

            <h2 class="card-title">
                Upcoming Sessions
            </h2>

            <div class="timeline">

                <div class="timeline-item">

                    <div class="timeline-dot"></div>

                    <div>
                        <strong>15 July 2026</strong>

                        <p>
                            Morning Shift (08:00 AM - 12:00 PM)
                        </p>

                        <span class="caregiver-name">
                            Nadeesha Perera
                        </span>
                    </div>

                    <span class="status confirmed">
                        Confirmed
                    </span>

                </div>


                <div class="timeline-item muted">

                    <div class="timeline-dot"></div>

                    <div>
                        <strong>18 July 2026</strong>

                        <p>
                            Evening Shift (04:00 PM - 08:00 PM)
                        </p>
                    </div>

                </div>

            </div>

        </section>


        <!-- Documents -->
        <section class="card">

            <h2 class="card-title">
                Important Documents
            </h2>

            <div class="documents">

                <div class="document-item">

                    <div>
                        <strong>Medical Report - June 2026</strong>
                        <span>Uploaded 12 June • 2.4 MB</span>
                    </div>

                    <div class="document-actions">
                        <button onclick="viewDocument('Medical Report - June 2026')">
                            View
                        </button>

                        <button onclick="downloadDocument('Medical Report - June 2026')">
                            Download
                        </button>
                    </div>

                </div>


                <div class="document-item">

                    <div>
                        <strong>Doctor Prescription - Cardiac</strong>
                        <span>Uploaded 02 May • 1.1 MB</span>
                    </div>

                    <div class="document-actions">
                        <button onclick="viewDocument('Doctor Prescription - Cardiac')">
                            View
                        </button>

                        <button onclick="downloadDocument('Doctor Prescription - Cardiac')">
                            Download
                        </button>
                    </div>

                </div>


                <div class="document-item">

                    <div>
                        <strong>Lab Results - Blood Work</strong>
                        <span>Uploaded 28 Apr • 4.5 MB</span>
                    </div>

                    <div class="document-actions">
                        <button onclick="viewDocument('Lab Results - Blood Work')">
                            View
                        </button>

                        <button onclick="downloadDocument('Lab Results - Blood Work')">
                            Download
                        </button>
                    </div>

                </div>

            </div>


            <button
                class="upload-button"
                onclick="uploadDocument()">
                + Upload New Document
            </button>

        </section>

    </div>


    <!-- Bottom Actions -->
    <section class="bottom-actions">

        <div>
            <h2>Need to schedule more care?</h2>

            <p>
                Ensure Mr. Silva receives continuous professional attention.
            </p>
        </div>


        <div class="action-buttons">

            <button
                class="btn btn-primary"
                onclick="window.location.href='booking.php'">
                Book Caregiver
            </button>

            <button
                class="btn btn-secondary"
                onclick="window.location.href='edit-patient.php'">
                Edit Patient Profile
            </button>

            <button
                class="btn btn-danger"
                onclick="deletePatient()">
                Delete Patient
            </button>

        </div>

    </section>

</main>


<footer class="footer">

    <div class="footer-container">

        <div>
            <strong>SafeHands</strong>

            <p>
                © 2026 SafeHands Healthcare. All rights reserved.
            </p>
        </div>

        <div class="footer-links">
            <a href="#">Terms of Service</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Escrow Terms</a>
            <a href="#">Contact Support</a>
        </div>

    </div>

</footer>


<script src="assets/js/patient-profile.js"></script>

</body>
</html>