<?php

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

/*
|--------------------------------------------------------------------------
| PROTECT CAREGIVER DASHBOARD
|--------------------------------------------------------------------------
*/

requireVerifiedCaregiver($conn);


/*
|--------------------------------------------------------------------------
| LOGGED-IN CAREGIVER
|--------------------------------------------------------------------------
|
| For now we use the name stored during login.
| Later we will connect bookings, earnings, availability,
| reports and notifications to the database.
|
*/

$firstName = $_SESSION['first_name'] ?? 'Caregiver';

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
        SafeHands | Caregiver Dashboard
    </title>


    <!-- INTER FONT -->

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- MATERIAL ICONS -->

    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet"
    >


    <!-- DASHBOARD CSS -->

    <link
        rel="stylesheet"
        href="caregiver-dashboard.css"
    >

</head>


<body>


<!-- =========================================================
     TOP NAVIGATION
========================================================= -->

<header class="top-header">

    <nav class="navbar">


        <!-- BRAND -->

        <a
            href="dashboard.php"
            class="brand"
        >

            SafeHands

        </a>


        <!-- DESKTOP NAVIGATION -->

        <div class="nav-links">

            <a
                href="dashboard.php"
                class="nav-link active"
            >

                Dashboard

            </a>


            <a
                href="schedule.php"
                class="nav-link"
            >

                My Schedule

            </a>


            <a
                href="availability.php"
                class="nav-link"
            >

                Availability

            </a>


            <a
                href="earnings.php"
                class="nav-link"
            >

                Earnings

            </a>


            <a
                href="profile.php"
                class="nav-link"
            >

                Profile

            </a>

        </div>


        <!-- NAVIGATION ACTIONS -->

        <div class="nav-actions">


            <button
                type="button"
                class="icon-button"
                aria-label="Notifications"
            >

                <span class="material-symbols-outlined">

                    notifications

                </span>

            </button>


            <button
                type="button"
                class="icon-button"
                aria-label="Help"
            >

                <span class="material-symbols-outlined">

                    help

                </span>

            </button>


            <!-- PROFILE -->

            <div class="profile-wrapper">


                <button
                    type="button"
                    class="profile-button"
                    id="profileButton"
                >

                    <div class="profile-avatar">

                        <?= htmlspecialchars(
                            strtoupper(
                                substr(
                                    $firstName,
                                    0,
                                    1
                                )
                            )
                        ) ?>

                    </div>

                </button>


                <!-- PROFILE DROPDOWN -->

                <div
                    class="profile-dropdown"
                    id="profileDropdown"
                >

                    <div class="dropdown-user">

                        <strong>

                            <?= htmlspecialchars(
                                $firstName
                            ) ?>

                        </strong>

                        <span>

                            Verified Caregiver

                        </span>

                    </div>


                    <a href="profile.php">

                        <span class="material-symbols-outlined">
                            person
                        </span>

                        My Profile

                    </a>


                    <a href="../logout.php">

                        <span class="material-symbols-outlined">
                            logout
                        </span>

                        Logout

                    </a>

                </div>


            </div>


            <!-- MOBILE MENU -->

            <button
                type="button"
                class="mobile-menu-button"
                id="mobileMenuButton"
            >

                <span class="material-symbols-outlined">

                    menu

                </span>

            </button>


        </div>


    </nav>


    <!-- MOBILE NAV -->

    <div
        class="mobile-nav"
        id="mobileNav"
    >

        <a href="dashboard.php">
            Dashboard
        </a>

        <a href="schedule.php">
            My Schedule
        </a>

        <a href="availability.php">
            Availability
        </a>

        <a href="earnings.php">
            Earnings
        </a>

        <a href="profile.php">
            Profile
        </a>

    </div>


</header>



<!-- =========================================================
     MAIN DASHBOARD
========================================================= -->

<main class="dashboard-container">


    <!-- =====================================================
         GREETING
    ====================================================== -->

    <section class="dashboard-heading">


        <div>

            <h1>

                Good <span id="greetingTime">Morning</span>,
                <?= htmlspecialchars($firstName) ?> 👋

            </h1>


            <p>

                Manage your daily care schedule,
                bookings and earnings from one place.

            </p>

        </div>


        <div class="current-date">

            <span class="material-symbols-outlined">

                calendar_today

            </span>

            <span id="currentDate">

                Loading date...

            </span>

        </div>


    </section>



    <!-- =====================================================
         QUICK ACTIONS
    ====================================================== -->

    <section class="quick-actions">


        <!-- TODAY SCHEDULE -->

        <a
            href="schedule.php"
            class="quick-card"
        >

            <div class="quick-icon primary-icon">

                <span class="material-symbols-outlined">

                    calendar_month

                </span>

            </div>

            <span>

                Today's Schedule

            </span>

        </a>



        <!-- AVAILABILITY -->

        <a
            href="availability.php"
            class="quick-card"
        >

            <div class="quick-icon secondary-icon">

                <span class="material-symbols-outlined">

                    event_note

                </span>

            </div>

            <span>

                Manage Availability

            </span>

        </a>



        <!-- REPORTS -->

        <a
            href="reports.php"
            class="quick-card"
        >

            <div class="quick-icon warning-icon">

                <span class="material-symbols-outlined">

                    description

                </span>

            </div>

            <span>

                Pending Reports

            </span>

        </a>



        <!-- EARNINGS -->

        <a
            href="earnings.php"
            class="quick-card"
        >

            <div class="quick-icon success-icon">

                <span class="material-symbols-outlined">

                    payments

                </span>

            </div>

            <span>

                View Earnings

            </span>

        </a>


    </section>



    <!-- =====================================================
         DASHBOARD GRID
    ====================================================== -->

    <div class="dashboard-grid">


        <!-- =================================================
             LEFT COLUMN
        ================================================== -->

        <div class="main-column">


            <!-- =============================================
                 TODAY'S SCHEDULE
            ============================================== -->

            <section class="dashboard-section">


                <div class="section-heading">

                    <h2>

                        Today's Schedule

                    </h2>


                    <a href="schedule.php">

                        View Calendar

                    </a>

                </div>



                <!-- BOOKING CARD -->

                <article class="booking-card">


                    <div class="booking-status-line"></div>


                    <div class="patient-avatar">

                        MS

                    </div>


                    <div class="booking-information">


                        <div class="booking-top">


                            <div>

                                <h3>

                                    Mr. Silva - Post-Op Care

                                </h3>


                                <div class="booking-meta">


                                    <span>

                                        <span class="material-symbols-outlined">

                                            schedule

                                        </span>

                                        8:00 AM – 12:00 PM

                                    </span>


                                    <span>

                                        <span class="material-symbols-outlined">

                                            location_on

                                        </span>

                                        Colombo 07

                                    </span>


                                </div>

                            </div>


                            <span class="status-badge confirmed">

                                Confirmed

                            </span>


                        </div>


                        <div class="booking-actions">


                            <button
                                type="button"
                                class="primary-button"
                            >

                                Start Service

                            </button>


                            <button
                                type="button"
                                class="secondary-button"
                            >

                                View Details

                            </button>


                        </div>


                    </div>


                </article>


            </section>



            <!-- =============================================
                 AVAILABILITY SUMMARY
            ============================================== -->

            <section class="dashboard-section">


                <div class="section-heading">

                    <h2>

                        Availability Summary

                    </h2>


                    <a href="availability.php">

                        Manage Availability

                    </a>

                </div>



                <div class="availability-card">


                    <div class="week-grid">


                        <!-- MONDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                MON
                            </span>

                            <div class="day-status available">

                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                            </div>

                            <span class="available-text">

                                AVAILABLE

                            </span>

                        </div>



                        <!-- TUESDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                TUE
                            </span>

                            <div class="day-status full">

                                <span class="material-symbols-outlined">
                                    block
                                </span>

                            </div>

                            <span class="full-text">

                                FULL

                            </span>

                        </div>



                        <!-- WEDNESDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                WED
                            </span>

                            <div class="day-status available">

                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                            </div>

                            <span class="available-text">

                                AVAILABLE

                            </span>

                        </div>



                        <!-- THURSDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                THU
                            </span>

                            <div class="day-status available">

                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                            </div>

                            <span class="available-text">

                                AVAILABLE

                            </span>

                        </div>



                        <!-- FRIDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                FRI
                            </span>

                            <div class="day-status available">

                                <span class="material-symbols-outlined">
                                    check_circle
                                </span>

                            </div>

                            <span class="available-text">

                                AVAILABLE

                            </span>

                        </div>



                        <!-- SATURDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                SAT
                            </span>

                            <div class="day-status off">

                                <span class="material-symbols-outlined">
                                    event_busy
                                </span>

                            </div>

                            <span class="off-text">

                                OFF

                            </span>

                        </div>



                        <!-- SUNDAY -->

                        <div class="day-item">

                            <span class="day-name">
                                SUN
                            </span>

                            <div class="day-status off">

                                <span class="material-symbols-outlined">
                                    event_busy
                                </span>

                            </div>

                            <span class="off-text">

                                OFF

                            </span>

                        </div>


                    </div>


                </div>


            </section>



            <!-- =============================================
                 UPCOMING BOOKINGS
            ============================================== -->

            <section class="dashboard-section">


                <div class="section-heading">

                    <h2>

                        Upcoming Bookings

                    </h2>


                    <a href="schedule.php">

                        View All

                    </a>

                </div>



                <div class="table-card">


                    <div class="table-scroll">


                        <table>


                            <thead>

                                <tr>

                                    <th>
                                        Client
                                    </th>

                                    <th>
                                        Date & Time
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="action-column">
                                        Action
                                    </th>

                                </tr>

                            </thead>


                            <tbody>


                                <!-- BOOKING 1 -->

                                <tr>

                                    <td>

                                        <div class="client-cell">

                                            <div class="small-avatar">

                                                MP

                                            </div>

                                            <span>

                                                Mrs. Perera

                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        <strong>
                                            Oct 25, 2024
                                        </strong>

                                        <small>
                                            09:00 AM – 1:00 PM
                                        </small>

                                    </td>


                                    <td>

                                        <span class="status-badge confirmed">

                                            Confirmed

                                        </span>

                                    </td>


                                    <td class="action-column">

                                        <button
                                            type="button"
                                            class="text-button"
                                        >

                                            Details

                                        </button>

                                    </td>

                                </tr>



                                <!-- BOOKING 2 -->

                                <tr>

                                    <td>

                                        <div class="client-cell">

                                            <div class="small-avatar">

                                                MF

                                            </div>

                                            <span>

                                                Mr. Fernando

                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        <strong>
                                            Oct 26, 2024
                                        </strong>

                                        <small>
                                            2:00 PM – 6:00 PM
                                        </small>

                                    </td>


                                    <td>

                                        <span class="status-badge confirmed">

                                            Confirmed

                                        </span>

                                    </td>


                                    <td class="action-column">

                                        <button
                                            type="button"
                                            class="text-button"
                                        >

                                            Details

                                        </button>

                                    </td>

                                </tr>



                                <!-- BOOKING 3 -->

                                <tr>

                                    <td>

                                        <div class="client-cell">

                                            <div class="small-avatar">

                                                MJ

                                            </div>

                                            <span>

                                                Ms. Jayamaha

                                            </span>

                                        </div>

                                    </td>


                                    <td>

                                        <strong>
                                            Oct 27, 2024
                                        </strong>

                                        <small>
                                            10:00 AM – 2:00 PM
                                        </small>

                                    </td>


                                    <td>

                                        <span class="status-badge confirmed">

                                            Confirmed

                                        </span>

                                    </td>


                                    <td class="action-column">

                                        <button
                                            type="button"
                                            class="text-button"
                                        >

                                            Details

                                        </button>

                                    </td>

                                </tr>


                            </tbody>


                        </table>


                    </div>


                </div>


            </section>


        </div>



        <!-- =================================================
             RIGHT SIDEBAR
        ================================================== -->

        <aside class="sidebar">


            <!-- =============================================
                 EARNINGS
            ============================================== -->

            <section class="side-card">


                <div class="side-card-heading">

                    <h3>

                        Earnings Summary

                    </h3>


                    <span class="material-symbols-outlined heading-icon">

                        trending_up

                    </span>

                </div>



                <div class="earning-grid">


                    <div class="stat-box">

                        <span>
                            RELEASED
                        </span>

                        <strong class="success-text">

                            LKR 42,500

                        </strong>

                    </div>


                    <div class="stat-box">

                        <span>
                            HELD
                        </span>

                        <strong>

                            LKR 8,200

                        </strong>

                    </div>


                    <div class="stat-box">

                        <span>
                            THIS MONTH
                        </span>

                        <strong class="primary-text">

                            LKR 94,800

                        </strong>

                    </div>


                    <div class="stat-box">

                        <span>
                            SESSIONS
                        </span>

                        <strong>

                            24 Total

                        </strong>

                    </div>


                </div>


            </section>



            <!-- =============================================
                 PERFORMANCE
            ============================================== -->

            <section class="side-card">


                <h3 class="side-title">

                    Performance

                </h3>


                <div class="performance-row">

                    <span>
                        Rating
                    </span>

                    <strong class="rating">

                        4.9

                        <span class="material-symbols-outlined">

                            star

                        </span>

                    </strong>

                </div>


                <div class="rating-progress">

                    <div></div>

                </div>


                <div class="performance-row">

                    <span>
                        Response Rate
                    </span>

                    <strong>
                        100%
                    </strong>

                </div>


                <div class="performance-row">

                    <span>
                        Completion Rate
                    </span>

                    <strong>
                        96%
                    </strong>

                </div>


            </section>



            <!-- =============================================
                 PENDING REPORTS
            ============================================== -->

            <section class="side-card">


                <h3 class="side-title">

                    Pending Reports

                </h3>


                <div class="report-item">


                    <div class="report-heading">

                        <div>

                            <strong>

                                Mrs. Abeywickrama

                            </strong>

                            <small>

                                Yesterday, 4:00 PM

                            </small>

                        </div>


                        <span class="material-symbols-outlined warning-text">

                            pending_actions

                        </span>

                    </div>


                    <button
                        type="button"
                        class="outline-primary-button"
                    >

                        Complete Report

                    </button>


                </div>



                <div class="report-item">


                    <div class="report-heading">

                        <div>

                            <strong>

                                Mr. Samaranayake

                            </strong>

                            <small>

                                Oct 22, 10:00 AM

                            </small>

                        </div>


                        <span class="material-symbols-outlined warning-text">

                            pending_actions

                        </span>

                    </div>


                    <button
                        type="button"
                        class="outline-primary-button"
                    >

                        Complete Report

                    </button>


                </div>


            </section>



            <!-- =============================================
                 NOTIFICATIONS
            ============================================== -->

            <section class="side-card">


                <h3 class="side-title">

                    Notifications

                </h3>


                <div class="notification-item">


                    <div class="notification-icon success-notification">

                        <span class="material-symbols-outlined">

                            check_circle

                        </span>

                    </div>


                    <div>

                        <strong>

                            Payout Released

                        </strong>

                        <p>

                            Your earnings for last week
                            have been released.

                        </p>

                    </div>


                </div>



                <div class="notification-item">


                    <div class="notification-icon info-notification">

                        <span class="material-symbols-outlined">

                            new_releases

                        </span>

                    </div>


                    <div>

                        <strong>

                            New Booking Request

                        </strong>

                        <p>

                            Mr. Perera requested a session
                            for Oct 30.

                        </p>

                    </div>


                </div>


            </section>



            <!-- =============================================
                 SUPPORT
            ============================================== -->

            <section class="support-card">


                <h3>

                    Need assistance?

                </h3>


                <p>

                    Our support team is available to help
                    you with your care services.

                </p>


                <button
                    type="button"
                    class="support-button"
                >

                    <span class="material-symbols-outlined">

                        support_agent

                    </span>

                    Contact Support

                </button>


            </section>


        </aside>


    </div>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">


    <div class="footer-container">


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

            <a href="#">
                Help Center
            </a>

        </div>


    </div>


</footer>



<script src="caregiver-dashboard.js"></script>


</body>

</html>