<?php

/*
|--------------------------------------------------------------------------
| SAFEHANDS - CAREGIVER APPLICATION REJECTED
|--------------------------------------------------------------------------
*/

require_once __DIR__ . '/../includes/db.php';

session_start();


/*
|--------------------------------------------------------------------------
| REQUIRE LOGIN
|--------------------------------------------------------------------------
*/

if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role'])
) {

    header(
        "Location: ../login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| REQUIRE CAREGIVER ROLE
|--------------------------------------------------------------------------
*/

$role =
    trim(
        $_SESSION['role']
    );


if (
    strcasecmp(
        $role,
        'Caregiver'
    ) !== 0
) {

    /*
    |--------------------------------------------------------------------------
    | FAMILY
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $role,
            'Family'
        ) === 0
    ) {

        header(
            "Location: ../family/dashboard.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $role,
            'Admin'
        ) === 0
    ) {

        header(
            "Location: ../admin/dashboard.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | UNKNOWN ROLE
    |--------------------------------------------------------------------------
    */

    header(
        "Location: ../login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| GET LOGGED-IN USER ID
|--------------------------------------------------------------------------
*/

$userId =
    (int) $_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| GET CAREGIVER APPLICATION DATA
|--------------------------------------------------------------------------
*/

$stmt =
    $conn->prepare(

        "SELECT
            caregiver_id,
            verification_status,
            rejection_reason

         FROM caregiver_profiles

         WHERE user_id = ?

         LIMIT 1"

    );


if (!$stmt) {

    die(
        "Unable to load your caregiver application."
    );

}


$stmt->bind_param(
    "i",
    $userId
);


$stmt->execute();


$result =
    $stmt->get_result();


/*
|--------------------------------------------------------------------------
| CAREGIVER PROFILE NOT FOUND
|--------------------------------------------------------------------------
*/

if (
    $result->num_rows !== 1
) {

    $stmt->close();

    header(
        "Location: ../login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| GET CAREGIVER RECORD
|--------------------------------------------------------------------------
*/

$caregiver =
    $result->fetch_assoc();


$stmt->close();


$verificationStatus =
    trim(
        $caregiver[
            'verification_status'
        ] ?? ''
    );


$rejectionReason =
    trim(
        $caregiver[
            'rejection_reason'
        ] ?? ''
    );


/*
|--------------------------------------------------------------------------
| VERIFIED CAREGIVER
|--------------------------------------------------------------------------
|
| A caregiver whose application was later approved should no longer
| be able to remain on the rejected page.
|
*/

if (
    strcasecmp(
        $verificationStatus,
        'Verified'
    ) === 0
) {

    header(
        "Location: dashboard.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| PENDING CAREGIVER
|--------------------------------------------------------------------------
*/

if (
    strcasecmp(
        $verificationStatus,
        'Pending'
    ) === 0
) {

    header(
        "Location: ../caregiver-application-success.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| UNKNOWN STATUS
|--------------------------------------------------------------------------
*/

if (
    strcasecmp(
        $verificationStatus,
        'Rejected'
    ) !== 0
) {

    header(
        "Location: ../login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| DEFAULT REJECTION REASON
|--------------------------------------------------------------------------
|
| If an old rejected application has no rejection_reason saved,
| display a professional fallback message.
|
*/

if (
    $rejectionReason === ''
) {

    $rejectionReason =
        "Your application did not meet the current verification requirements. "
        . "Please contact SafeHands Support if you need more information "
        . "about this decision.";

}


/*
|--------------------------------------------------------------------------
| CAREGIVER NAME
|--------------------------------------------------------------------------
*/

$firstName =
    trim(
        $_SESSION['first_name'] ?? ''
    );


$caregiverName =
    $firstName !== ''
        ? $firstName
        : 'Caregiver';

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
        Application Status | SafeHands
    </title>


    <!-- INTER FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- MATERIAL SYMBOLS -->

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet"
    >


    <!-- PAGE CSS -->

    <link
        rel="stylesheet"
        href="caregiver-rejected.css"
    >

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="top-header">

    <nav class="navbar">


        <!-- BRAND -->

        <a
            href="../index.php"
            class="brand"
        >

            <span class="brand-icon">

                <span class="material-symbols-outlined">

                    health_and_safety

                </span>

            </span>


            <span>

                SafeHands

            </span>

        </a>


        <!-- NAVIGATION ACTIONS -->

        <div class="nav-actions">


            <a
                href="../index.php"
                class="home-link"
            >

                <span class="material-symbols-outlined">

                    home

                </span>

                <span class="home-text">

                    Home

                </span>

            </a>


            <a
                href="../logout.php"
                class="logout-button"
            >

                <span class="material-symbols-outlined">

                    logout

                </span>

                Logout

            </a>


        </div>


    </nav>

</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="page-container">


    <!-- =====================================================
         MAIN STATUS CARD
    ====================================================== -->

    <section class="status-card">


        <!-- STATUS ICON -->

        <div class="status-icon-wrapper">

            <div class="status-icon">

                <span class="material-symbols-outlined">

                    cancel

                </span>

            </div>

        </div>


        <!-- STATUS LABEL -->

        <span class="status-label">

            APPLICATION STATUS

        </span>


        <!-- MAIN TITLE -->

        <h1>

            Your Caregiver Application
            Was Not Approved

        </h1>


        <!-- INTRODUCTION -->

        <p class="intro-text">

            Hi
            <?= htmlspecialchars(
                $caregiverName
            ) ?>,
            thank you for your interest in joining SafeHands.
            After reviewing your application and submitted
            verification information, we're unable to approve
            your caregiver account at this time.

        </p>



        <!-- =================================================
             APPLICATION STATUS BOX
        ================================================== -->

        <div class="decision-box">


            <div class="decision-icon">

                <span class="material-symbols-outlined">

                    assignment_late

                </span>

            </div>


            <div class="decision-content">


                <span class="rejected-badge">

                    NOT APPROVED

                </span>


                <h2>

                    Application Review Completed

                </h2>


                <p>

                    Your caregiver application has been reviewed
                    by the SafeHands verification team and was
                    not approved.

                </p>


            </div>


        </div>



        <!-- =================================================
             REJECTION REASON
        ================================================== -->

        <section class="reason-section">


            <div class="section-title-row">


                <span class="material-symbols-outlined">

                    info

                </span>


                <h2>

                    Reason for Decision

                </h2>


            </div>


            <div class="reason-box">


                <div class="reason-indicator"></div>


                <p>

                    <?= nl2br(
                        htmlspecialchars(
                            $rejectionReason
                        )
                    ) ?>

                </p>


            </div>


        </section>



        <!-- =================================================
             WHAT CAN I DO NEXT?
        ================================================== -->

        <section class="next-section">


            <div class="section-title-row">


                <span class="material-symbols-outlined">

                    route

                </span>


                <h2>

                    What can I do next?

                </h2>


            </div>



            <div class="next-grid">


                <!-- CONTACT SUPPORT -->

                <article class="next-card">


                    <div class="next-icon">

                        <span class="material-symbols-outlined">

                            support_agent

                        </span>

                    </div>


                    <h3>

                        Contact Support

                    </h3>


                    <p>

                        Contact the SafeHands support team
                        if you need clarification about
                        the decision.

                    </p>


                </article>



                <!-- REVIEW INFORMATION -->

                <article class="next-card">


                    <div class="next-icon">

                        <span class="material-symbols-outlined">

                            description

                        </span>

                    </div>


                    <h3>

                        Review Your Information

                    </h3>


                    <p>

                        Check that your personal details,
                        qualifications, NIC and verification
                        documents are accurate and valid.

                    </p>


                </article>



                <!-- REAPPLY -->

                <article class="next-card">


                    <div class="next-icon">

                        <span class="material-symbols-outlined">

                            assignment_return

                        </span>

                    </div>


                    <h3>

                        Reapply if Eligible

                    </h3>


                    <p>

                        If reapplication is permitted,
                        correct the required information
                        and submit a new application.

                    </p>


                </article>


            </div>


        </section>



        <!-- =================================================
             IMPORTANT NOTICE
        ================================================== -->

        <div class="notice-box">


            <div class="notice-icon">

                <span class="material-symbols-outlined">

                    shield_lock

                </span>

            </div>


            <div>


                <strong>

                    Account Access Restricted

                </strong>


                <p>

                    Your caregiver account cannot access
                    caregiver services, bookings, availability
                    management, care reports, or earnings while
                    the application is not approved.

                </p>


            </div>


        </div>



        <!-- =================================================
             ACTION BUTTONS
        ================================================== -->

        <div class="action-buttons">


            <button
                type="button"
                class="primary-button"
                id="contactSupportButton"
            >

                <span class="material-symbols-outlined">

                    support_agent

                </span>

                Contact Support

            </button>


            <a
                href="../index.php"
                class="secondary-button"
            >

                <span class="material-symbols-outlined">

                    home

                </span>

                Back to Home

            </a>


        </div>


        <!-- LOGOUT -->

        <div class="logout-section">


            <a
                href="../logout.php"
                class="logout-link"
            >

                <span class="material-symbols-outlined">

                    logout

                </span>

                Logout from SafeHands

            </a>


        </div>


    </section>



    <!-- =====================================================
         SUPPORT MESSAGE
    ====================================================== -->

    <div class="support-message">


        <span class="material-symbols-outlined">

            help

        </span>


        <p>

            Need help understanding your application status?

            <button
                type="button"
                id="supportTextButton"
            >

                Contact SafeHands Support

            </button>

        </p>


    </div>


</main>



<!-- =========================================================
     SUPPORT MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="supportModal"
    aria-hidden="true"
>


    <div
        class="support-modal"
        role="dialog"
        aria-modal="true"
        aria-labelledby="supportModalTitle"
    >


        <button
            type="button"
            class="modal-close"
            id="closeSupportModal"
            aria-label="Close support window"
        >

            <span class="material-symbols-outlined">

                close

            </span>

        </button>


        <div class="modal-icon">

            <span class="material-symbols-outlined">

                support_agent

            </span>

        </div>


        <h2 id="supportModalTitle">

            Contact SafeHands Support

        </h2>


        <p>

            If you need clarification about your caregiver
            application decision, please contact the SafeHands
            support team.

        </p>


        <div class="support-info">


            <div>

                <span class="material-symbols-outlined">

                    mail

                </span>


                <div>

                    <small>

                        EMAIL SUPPORT

                    </small>

                    <strong>

                        support@safehands.lk

                    </strong>

                </div>

            </div>


            <div>

                <span class="material-symbols-outlined">

                    schedule

                </span>


                <div>

                    <small>

                        SUPPORT HOURS

                    </small>

                    <strong>

                        Monday – Friday,
                        8:30 AM – 5:00 PM

                    </strong>

                </div>

            </div>


        </div>


        <button
            type="button"
            class="modal-done-button"
            id="modalDoneButton"
        >

            Done

        </button>


    </div>


</div>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">


    <div class="footer-content">


        <div>


            <strong>

                SafeHands

            </strong>


            <p>

                © <?= date('Y') ?>
                SafeHands Healthcare.
                All rights reserved.

            </p>


        </div>


        <div class="footer-links">


            <a href="#">

                Privacy Policy

            </a>


            <a href="#">

                Terms

            </a>


            <a
                href="#"
                id="footerHelpLink"
            >

                Help Center

            </a>


        </div>


    </div>


</footer>



<!-- PAGE JAVASCRIPT -->

<script src="caregiver-rejected.js"></script>


</body>

</html>