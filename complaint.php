<?php
session_start();

/*
|--------------------------------------------------------------------------
| SafeHands - Submit Complaint
|--------------------------------------------------------------------------
| Currently using sample booking data.
| Later we will load this information from MySQL using booking_id.
|--------------------------------------------------------------------------
*/

$booking = [
    'booking_id' => 'SH-882910',
    'caregiver' => 'Nadeesha Perera',
    'caregiver_role' => 'Registered Nurse',
    'patient' => 'Mr. Ananda Silva',
    'date' => '15 Aug 2026',
    'time' => '09:00 AM - 01:00 PM'
];

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $category = trim($_POST['category'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $incident_time = trim($_POST['incident_time'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $urgency = trim($_POST['urgency'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    if (
        empty($category) ||
        empty($subject) ||
        empty($description) ||
        empty($urgency) ||
        empty($contact)
    ) {

        $error_message = 'Please complete all required fields.';

    } elseif (strlen($description) > 1000) {

        $error_message = 'Description cannot exceed 1000 characters.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | DATABASE INSERT WILL GO HERE
        |--------------------------------------------------------------------------
        |
        | Later:
        |
        | INSERT INTO complaints (...)
        |
        */

        $success_message =
            'Your complaint has been submitted successfully.';

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Submit Complaint | SafeHands
    </title>

    <link
        rel="stylesheet"
        href="assets/css/complaint.css"
    >

</head>


<body>


<!-- =====================================================
     HEADER
====================================================== -->

<header class="top-header">

    <div class="header-container">


        <div class="header-left">

            <a
                href="dashboard.php"
                class="logo"
            >
                SafeHands
            </a>


            <nav class="desktop-nav">

                <a href="dashboard.php">
                    Dashboard
                </a>

                <a href="patients.php">
                    Patients
                </a>

                <a href="find-caregivers.php">
                    Find Caregivers
                </a>

                <a
                    href="my-bookings.php"
                    class="active"
                >
                    My Bookings
                </a>

            </nav>

        </div>


        <div class="header-right">


            <a
                href="notifications.php"
                class="header-icon"
                title="Notifications"
            >

                <svg viewBox="0 0 24 24">

                    <path
                        d="M18 8A6 6 0 0 0 6 8c0 7-3 7-3 9h18c0-2-3-2-3-9"
                    />

                    <path
                        d="M10 21h4"
                    />

                </svg>

            </a>


            <a
                href="profile.php"
                class="header-icon"
                title="Profile"
            >

                <svg viewBox="0 0 24 24">

                    <circle
                        cx="12"
                        cy="8"
                        r="3.5"
                    />

                    <path
                        d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"
                    />

                </svg>

            </a>


            <a
                href="../logout.php"
                class="sign-out"
            >
                Sign Out
            </a>


        </div>

    </div>

</header>



<!-- =====================================================
     MAIN
====================================================== -->

<main class="main-container">


    <!-- Breadcrumb -->

    <nav class="breadcrumb">

        <a href="dashboard.php">
            Dashboard
        </a>

        <span>›</span>

        <a href="my-bookings.php">
            My Bookings
        </a>

        <span>›</span>

        <a
            href="booking-details.php?booking_id=<?php echo urlencode($booking['booking_id']); ?>"
        >
            Booking Details
        </a>

        <span>›</span>

        <span class="current">
            Submit Complaint
        </span>

    </nav>



    <!-- =================================================
         PAGE CONTENT
    ================================================== -->

    <div class="complaint-layout">


        <!-- =================================================
             LEFT BOOKING CARD
        ================================================== -->

        <aside class="booking-sidebar">


            <div class="booking-card">


                <div class="booking-card-header">


                    <div class="reference-row">

                        <span>
                            Reference Booking
                        </span>

                        <strong>
                            #<?php echo htmlspecialchars($booking['booking_id']); ?>
                        </strong>

                    </div>


                    <div class="caregiver">

                        <div class="caregiver-image">

                            <img
                                src="../assets/images/caregiver-placeholder.jpg"
                                alt="Caregiver"
                            >

                        </div>


                        <div>

                            <h2>
                                <?php echo htmlspecialchars($booking['caregiver']); ?>
                            </h2>

                            <p>

                                <svg viewBox="0 0 24 24">

                                    <rect
                                        x="4"
                                        y="5"
                                        width="16"
                                        height="15"
                                        rx="2"
                                    />

                                    <path
                                        d="M9 5V3h6v2"
                                    />

                                </svg>

                                <?php echo htmlspecialchars($booking['caregiver_role']); ?>

                            </p>

                        </div>

                    </div>


                </div>



                <div class="booking-card-body">


                    <div class="booking-info">


                        <div class="info-icon">

                            <svg viewBox="0 0 24 24">

                                <circle
                                    cx="12"
                                    cy="8"
                                    r="3"
                                />

                                <path
                                    d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"
                                />

                            </svg>

                        </div>


                        <div>

                            <span>
                                Patient
                            </span>

                            <strong>
                                <?php echo htmlspecialchars($booking['patient']); ?>
                            </strong>

                        </div>

                    </div>



                    <div class="booking-info">


                        <div class="info-icon">

                            <svg viewBox="0 0 24 24">

                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="16"
                                    rx="2"
                                />

                                <line
                                    x1="16"
                                    y1="3"
                                    x2="16"
                                    y2="7"
                                />

                                <line
                                    x1="8"
                                    y1="3"
                                    x2="8"
                                    y2="7"
                                />

                                <line
                                    x1="3"
                                    y1="10"
                                    x2="21"
                                    y2="10"
                                />

                            </svg>

                        </div>


                        <div>

                            <span>
                                Session Date
                            </span>

                            <strong>
                                <?php echo htmlspecialchars($booking['date']); ?>
                            </strong>

                            <small>
                                <?php echo htmlspecialchars($booking['time']); ?>
                            </small>

                        </div>

                    </div>



                    <div class="session-status">

                        <svg viewBox="0 0 24 24">

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <polyline
                                points="8 12 11 15 16 9"
                            />

                        </svg>

                        Care Session Completed

                    </div>


                </div>

            </div>

        </aside>



        <!-- =================================================
             COMPLAINT FORM
        ================================================== -->

        <section class="complaint-section">


            <div class="complaint-card">


                <div class="form-header">

                    <h1>
                        Report a Care Issue
                    </h1>

                    <p>

                        We take all concerns seriously.
                        Please provide details about your experience
                        regarding booking

                        <strong>
                            #<?php echo htmlspecialchars($booking['booking_id']); ?>
                        </strong>

                        so our quality assurance team can investigate.

                    </p>

                </div>



                <?php if (!empty($success_message)): ?>

                    <div class="message success-message">

                        <svg viewBox="0 0 24 24">

                            <circle
                                cx="12"
                                cy="12"
                                r="9"
                            />

                            <polyline
                                points="8 12 11 15 16 9"
                            />

                        </svg>

                        <?php echo htmlspecialchars($success_message); ?>

                    </div>

                <?php endif; ?>



                <?php if (!empty($error_message)): ?>

                    <div class="message error-message">

                        <?php echo htmlspecialchars($error_message); ?>

                    </div>

                <?php endif; ?>



                <form
                    id="complaintForm"
                    method="POST"
                    action=""
                    enctype="multipart/form-data"
                >


                    <!-- CATEGORY -->

                    <fieldset>

                        <legend>
                            Category of Concern
                        </legend>


                        <div class="category-grid">


                            <label class="category-option">

                                <input
                                    type="radio"
                                    name="category"
                                    value="service"
                                    checked
                                >

                                <span class="category-box">

                                    <span class="category-icon">
                                        ✚
                                    </span>

                                    <span>
                                        Caregiver Service
                                    </span>

                                </span>

                            </label>



                            <label class="category-option">

                                <input
                                    type="radio"
                                    name="category"
                                    value="safety"
                                >

                                <span class="category-box">

                                    <span class="category-icon">
                                        !
                                    </span>

                                    <span>
                                        Safety Concern
                                    </span>

                                </span>

                            </label>



                            <label class="category-option">

                                <input
                                    type="radio"
                                    name="category"
                                    value="booking"
                                >

                                <span class="category-box">

                                    <span class="category-icon">
                                        □
                                    </span>

                                    <span>
                                        Booking Issue
                                    </span>

                                </span>

                            </label>



                            <label class="category-option">

                                <input
                                    type="radio"
                                    name="category"
                                    value="other"
                                >

                                <span class="category-box">

                                    <span class="category-icon">
                                        •••
                                    </span>

                                    <span>
                                        Other
                                    </span>

                                </span>

                            </label>


                        </div>

                    </fieldset>



                    <!-- SUBJECT + TIME -->

                    <div class="form-grid">


                        <div class="form-group">

                            <label for="subject">
                                Subject
                            </label>

                            <input
                                type="text"
                                id="subject"
                                name="subject"
                                placeholder="Briefly describe the issue"
                                maxlength="150"
                                required
                            >

                        </div>



                        <div class="form-group">

                            <label for="incident_time">
                                Approximate Time of Incident
                            </label>

                            <div class="input-with-icon">

                                <span>
                                    ◷
                                </span>

                                <input
                                    type="time"
                                    id="incident_time"
                                    name="incident_time"
                                >

                            </div>

                        </div>


                    </div>



                    <!-- DESCRIPTION -->

                    <div class="form-group">


                        <div class="label-row">

                            <label for="description">
                                Tell us what happened
                            </label>

                            <span id="characterCount">
                                0 / 1000
                            </span>

                        </div>


                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            maxlength="1000"
                            placeholder="Please provide specific details about your concern..."
                            required
                        ></textarea>


                    </div>



                    <!-- ATTACHMENTS -->

                    <div class="form-group">

                        <label>
                            Attachments (Optional)
                        </label>

                        <p class="help-text">

                            Upload any photos, documents, or screenshots
                            that support your concern.

                        </p>


                        <label
                            for="attachments"
                            class="upload-box"
                        >

                            <div class="upload-icon">
                                ↑
                            </div>

                            <strong>
                                Click to upload
                            </strong>

                            <span>
                                SVG, PNG, JPG or PDF (max. 10MB)
                            </span>

                        </label>


                        <input
                            type="file"
                            id="attachments"
                            name="attachments[]"
                            multiple
                            accept=".svg,.png,.jpg,.jpeg,.pdf"
                            hidden
                        >


                        <div
                            id="fileList"
                            class="file-list"
                        ></div>


                    </div>



                    <hr>



                    <!-- URGENCY + CONTACT -->

                    <div class="bottom-grid">


                        <!-- URGENCY -->

                        <fieldset>

                            <legend>
                                Urgency Level
                            </legend>


                            <div class="radio-list">


                                <label class="simple-radio">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="normal"
                                        checked
                                    >

                                    <span></span>

                                    Normal

                                </label>



                                <label class="simple-radio">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="important"
                                    >

                                    <span></span>

                                    Important

                                </label>



                                <label class="simple-radio urgent-option">

                                    <input
                                        type="radio"
                                        name="urgency"
                                        value="urgent"
                                    >

                                    <span></span>

                                    <strong>
                                        Urgent
                                    </strong>

                                    <b>
                                        !
                                    </b>

                                </label>


                            </div>

                        </fieldset>



                        <!-- CONTACT -->

                        <fieldset>

                            <legend>
                                Preferred Follow-up Method
                            </legend>


                            <div class="radio-list">


                                <label class="simple-radio">

                                    <input
                                        type="radio"
                                        name="contact"
                                        value="email"
                                        checked
                                    >

                                    <span></span>

                                    <span class="contact-symbol">
                                        ✉
                                    </span>

                                    Email

                                </label>



                                <label class="simple-radio">

                                    <input
                                        type="radio"
                                        name="contact"
                                        value="phone"
                                    >

                                    <span></span>

                                    <span class="contact-symbol">
                                        ☎
                                    </span>

                                    Phone Call

                                </label>


                            </div>

                        </fieldset>


                    </div>



                    <!-- BUTTONS -->

                    <div class="form-actions">


                        <a
                            href="my-bookings.php"
                            class="cancel-button"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="submit-button"
                        >

                            Submit Complaint

                            <span>
                                →
                            </span>

                        </button>


                    </div>


                </form>

            </div>

        </section>


    </div>

</main>



<!-- =====================================================
     FOOTER
====================================================== -->

<footer class="footer">

    <div class="footer-container">


        <strong>
            SafeHands
        </strong>


        <nav>

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Terms of Service
            </a>

            <a href="#">
                Contact Us
            </a>

        </nav>


        <p>
            © 2026 SafeHands Healthcare. All rights reserved.
        </p>


    </div>

</footer>



<script src="assets/js/complaint.js"></script>

</body>

</html>