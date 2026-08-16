<?php
session_start();

/*
|--------------------------------------------------------------------------
| SafeHands - My Bookings
|--------------------------------------------------------------------------
| This is currently a UI/demo page.
| Later you can replace the sample booking arrays with MySQL data.
|--------------------------------------------------------------------------
*/

$bookings = [
    [
        'id' => 'SH-882910',
        'status' => 'active',
        'caregiver' => 'Nadeesha Perera',
        'caregiver_role' => 'Registered Nurse (RN)',
        'caregiver_image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=200&q=80',
        'patient' => 'Ananda Silva',
        'location' => 'Colombo 07',
        'date' => '15 August 2026',
        'time' => '08:00 AM - 04:00 PM',
        'started' => '08:00 AM Today',
        'progress' => 50,
        'progress_text' => '4 Hours / 8 Hours',
        'rating' => null,
        'reviewed' => false,
        'payment_status' => 'Completed'
    ],

    [
        'id' => 'SH-882911',
        'status' => 'upcoming',
        'caregiver' => 'Nadeesha Perera',
        'caregiver_role' => 'Registered Nurse (RN)',
        'caregiver_image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=200&q=80',
        'patient' => 'Ananda Silva',
        'location' => 'Colombo 07',
        'date' => '18 August 2026',
        'time' => '08:00 AM - 04:00 PM',
        'started' => null,
        'progress' => 0,
        'progress_text' => null,
        'rating' => null,
        'reviewed' => false,
        'payment_status' => 'Pending'
    ],

    [
        'id' => 'SH-882912',
        'status' => 'completed',
        'caregiver' => 'Nadeesha Perera',
        'caregiver_role' => 'Registered Nurse (RN)',
        'caregiver_image' => 'https://images.unsplash.com/photo-1559839734-2b71ea197ec2?auto=format&fit=crop&w=200&q=80',
        'patient' => 'Ananda Silva',
        'location' => 'Colombo 07',
        'date' => '15 August 2026',
        'time' => '08:00 AM - 04:00 PM',
        'started' => null,
        'progress' => 100,
        'progress_text' => '8 Hours / 8 Hours',
        'rating' => null,
        'reviewed' => false,
        'payment_status' => 'Completed'
    ],

    [
        'id' => 'SH-882913',
        'status' => 'completed',
        'caregiver' => 'Kamal Perera',
        'caregiver_role' => 'Professional Caregiver',
        'caregiver_image' => 'https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?auto=format&fit=crop&w=200&q=80',
        'patient' => 'Ananda Silva',
        'location' => 'Colombo 07',
        'date' => '10 August 2026',
        'time' => '08:00 AM - 04:00 PM',
        'started' => null,
        'progress' => 100,
        'progress_text' => '8 Hours / 8 Hours',
        'rating' => 5,
        'reviewed' => true,
        'payment_status' => 'Completed'
    ],

    [
        'id' => 'SH-882914',
        'status' => 'cancelled',
        'caregiver' => 'Sunil Jayasuriya',
        'caregiver_role' => 'Professional Caregiver',
        'caregiver_image' => 'https://images.unsplash.com/photo-1618498082410-b4aa22193b38?auto=format&fit=crop&w=200&q=80',
        'patient' => 'Ananda Silva',
        'location' => 'Colombo 07',
        'date' => '10 August 2026',
        'time' => '08:00 AM - 04:00 PM',
        'started' => null,
        'progress' => 0,
        'progress_text' => null,
        'rating' => null,
        'reviewed' => false,
        'payment_status' => 'Refunded'
    ]
];

function icon($name)
{
    $icons = [

        'calendar' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="4" width="18" height="17" rx="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg>
        ',

        'activity' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <polyline points="3 12 7 12 10 5 14 19 17 12 21 12"></polyline>
            </svg>
        ',

        'check' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="8 12 11 15 16 9"></polyline>
            </svg>
        ',

        'cancel' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <line x1="9" y1="9" x2="15" y2="15"></line>
                <line x1="15" y1="9" x2="9" y2="15"></line>
            </svg>
        ',

        'search' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="11" cy="11" r="7"></circle>
                <line x1="16.5" y1="16.5" x2="21" y2="21"></line>
            </svg>
        ',

        'filter' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <line x1="4" y1="6" x2="20" y2="6"></line>
                <line x1="7" y1="12" x2="17" y2="12"></line>
                <line x1="10" y1="18" x2="14" y2="18"></line>
            </svg>
        ',

        'clock' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="12 7 12 12 15 14"></polyline>
            </svg>
        ',

        'location' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z"></path>
                <circle cx="12" cy="10" r="2.5"></circle>
            </svg>
        ',

        'report' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M5 4h14v16H5z"></path>
                <line x1="8" y1="9" x2="16" y2="9"></line>
                <line x1="8" y1="13" x2="16" y2="13"></line>
                <line x1="8" y1="17" x2="13" y2="17"></line>
            </svg>
        ',

        'phone' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M6 3h3l2 5-2 2c1 2 3 4 5 5l2-2 5 2v3c0 1-1 2-2 2C10 20 4 14 4 5c0-1 1-2 2-2Z"></path>
            </svg>
        ',

        'warning' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M12 3 22 20H2L12 3Z"></path>
                <line x1="12" y1="9" x2="12" y2="14"></line>
                <circle cx="12" cy="17" r=".7"></circle>
            </svg>
        ',

        'star' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"></path>
            </svg>
        ',

        'card' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <line x1="7" y1="15" x2="11" y2="15"></line>
            </svg>
        ',

        'bell' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                <path d="M10 21h4"></path>
            </svg>
        ',

        'user' => '
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"></path>
            </svg>
        '
    ];

    return $icons[$name] ?? '';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>My Bookings | SafeHands</title>

    <link rel="stylesheet" href="assets/css/my-booking.css">
</head>

<body>

<header class="top-nav">

    <div class="nav-container">

        <div class="nav-left">

            <a href="family/dashboard.php" class="logo">
                SafeHands
            </a>

            <nav class="main-nav">

                <a href="family/dashboard.php">
                    Dashboard
                </a>

                <a href="patients.php">
                    Patients
                </a>

                <a href="find-caregivers.php">
                    Find Caregivers
                </a>

                <a href="my-bookings.php" class="active">
                    My Bookings
                </a>

            </nav>

        </div>

        <div class="nav-right">

            <a href="notifications.php" class="nav-icon" title="Notifications">
                <?php echo icon('bell'); ?>
            </a>

            <a href="family/profile.php" class="nav-icon" title="Profile">
                <?php echo icon('user'); ?>
            </a>

            <a href="logout.php" class="sign-out">
                Sign Out
            </a>

        </div>

    </div>

</header>


<main class="main-container">

    <!-- Breadcrumb -->

    <div class="breadcrumb">

        <a href="family/dashboard.php">
            Dashboard
        </a>

        <span>/</span>

        <span class="current">
            My Bookings
        </span>

    </div>


    <!-- Page Header -->

    <section class="page-header">

        <h1>
            My Bookings
        </h1>

        <p>
            View and manage your caregiver bookings, care sessions and payment information.
        </p>

    </section>


    <!-- Summary -->

    <section class="summary-grid">

        <div class="summary-card">

            <div>
                <span class="summary-label">
                    Upcoming
                </span>

                <strong>2</strong>
            </div>

            <div class="summary-icon blue">
                <?php echo icon('calendar'); ?>
            </div>

        </div>


        <div class="summary-card">

            <div>
                <span class="summary-label">
                    Active
                </span>

                <strong>1</strong>
            </div>

            <div class="summary-icon green">
                <?php echo icon('activity'); ?>
            </div>

        </div>


        <div class="summary-card">

            <div>
                <span class="summary-label">
                    Completed
                </span>

                <strong>5</strong>
            </div>

            <div class="summary-icon purple">
                <?php echo icon('check'); ?>
            </div>

        </div>


        <div class="summary-card">

            <div>
                <span class="summary-label">
                    Cancelled
                </span>

                <strong>1</strong>
            </div>

            <div class="summary-icon red">
                <?php echo icon('cancel'); ?>
            </div>

        </div>

    </section>


    <!-- Filters -->

    <section class="filter-section">

        <div class="filter-tabs">

            <button
                type="button"
                class="filter-tab active"
                data-filter="all">
                All
            </button>

            <button
                type="button"
                class="filter-tab"
                data-filter="upcoming">
                Upcoming
            </button>

            <button
                type="button"
                class="filter-tab"
                data-filter="active">
                Active
            </button>

            <button
                type="button"
                class="filter-tab"
                data-filter="completed">
                Completed
            </button>

            <button
                type="button"
                class="filter-tab"
                data-filter="cancelled">
                Cancelled
            </button>

        </div>


        <div class="search-wrapper">

            <div class="search-box">

                <?php echo icon('search'); ?>

                <input
                    type="search"
                    id="bookingSearch"
                    placeholder="Search bookings..."
                    autocomplete="off"
                >

            </div>

            <button
                type="button"
                class="filter-button"
                id="filterButton"
                title="Filter bookings">

                <?php echo icon('filter'); ?>

            </button>

        </div>

    </section>


    <!-- Hidden filter panel -->

    <div class="advanced-filter" id="advancedFilter">

        <div class="filter-field">

            <label for="patientFilter">
                Patient
            </label>

            <select id="patientFilter">

                <option value="all">
                    All Patients
                </option>

                <option value="Ananda Silva">
                    Ananda Silva
                </option>

            </select>

        </div>


        <div class="filter-field">

            <label for="statusFilter">
                Status
            </label>

            <select id="statusFilter">

                <option value="all">
                    All Statuses
                </option>

                <option value="upcoming">
                    Upcoming
                </option>

                <option value="active">
                    Active
                </option>

                <option value="completed">
                    Completed
                </option>

                <option value="cancelled">
                    Cancelled
                </option>

            </select>

        </div>

    </div>


    <!-- Bookings -->

    <div id="bookingList">


        <!-- ACTIVE -->

        <section
            class="booking-section"
            data-section="active">

            <h2>
                Active Care
            </h2>


            <?php foreach ($bookings as $booking): ?>

                <?php if ($booking['status'] === 'active'): ?>

                    <article
                        class="booking-card active-card"
                        data-status="active"
                        data-patient="<?php echo htmlspecialchars($booking['patient']); ?>"
                        data-caregiver="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                    >

                        <div class="status-line active-line"></div>

                        <div class="booking-main">

                            <div class="booking-person">

                                <img
                                    src="<?php echo htmlspecialchars($booking['caregiver_image']); ?>"
                                    alt="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                                >

                                <div class="booking-info">

                                    <div class="booking-name-row">

                                        <h3>
                                            <?php echo htmlspecialchars($booking['caregiver']); ?>
                                        </h3>

                                        <span class="status-badge progress">
                                            <span class="status-dot"></span>
                                            Care In Progress
                                        </span>

                                    </div>

                                    <p class="patient-line">
                                        Patient:
                                        <strong>
                                            <?php echo htmlspecialchars($booking['patient']); ?>
                                        </strong>
                                    </p>

                                    <div class="booking-meta">

                                        <span>
                                            <?php echo icon('clock'); ?>
                                            Started:
                                            <?php echo htmlspecialchars($booking['started']); ?>
                                        </span>

                                        <span>
                                            <?php echo icon('location'); ?>
                                            <?php echo htmlspecialchars($booking['location']); ?>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div class="booking-actions">

                                <a
                                    href="dailycare-report.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                    class="btn btn-primary">
                                    View Care Report
                                </a>

                                <a
                                    href="contact-caregiver.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                    class="btn btn-secondary">
                                    Contact Caregiver
                                </a>

                            </div>

                        </div>


                        <div class="progress-section">

                            <div class="progress-header">

                                <span>
                                    Session Progress
                                </span>

                                <span>
                                    <?php echo htmlspecialchars($booking['progress_text']); ?>
                                </span>

                            </div>

                            <div class="progress-track">

                                <div
                                    class="progress-fill"
                                    style="width: <?php echo (int)$booking['progress']; ?>%;">
                                </div>

                            </div>

                        </div>

                    </article>

                <?php endif; ?>

            <?php endforeach; ?>

        </section>


        <!-- UPCOMING -->

        <section
            class="booking-section"
            data-section="upcoming">

            <h2>
                Upcoming Bookings
            </h2>


            <?php foreach ($bookings as $booking): ?>

                <?php if ($booking['status'] === 'upcoming'): ?>

                    <article
                        class="booking-card"
                        data-status="upcoming"
                        data-patient="<?php echo htmlspecialchars($booking['patient']); ?>"
                        data-caregiver="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                    >

                        <div class="booking-main">

                            <div class="booking-person">

                                <img
                                    src="<?php echo htmlspecialchars($booking['caregiver_image']); ?>"
                                    alt="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                                >

                                <div class="booking-info">

                                    <div class="booking-name-row">

                                        <h3>
                                            <?php echo htmlspecialchars($booking['caregiver']); ?>
                                        </h3>

                                        <span class="status-badge confirmed">
                                            Confirmed
                                        </span>

                                    </div>

                                    <p class="patient-line">
                                        Patient:
                                        <strong>
                                            <?php echo htmlspecialchars($booking['patient']); ?>
                                        </strong>
                                    </p>

                                    <div class="booking-meta">

                                        <span class="important-meta">
                                            <?php echo icon('calendar'); ?>

                                            <?php echo htmlspecialchars($booking['date']); ?>
                                        </span>

                                        <span>
                                            <?php echo icon('clock'); ?>

                                            <?php echo htmlspecialchars($booking['time']); ?>
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <div class="booking-actions upcoming-actions">

                                <a
                                    href="booking-details.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                    class="btn btn-primary">
                                    View Booking
                                </a>

                                <a
                                    href="contact-caregiver.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                    class="btn btn-secondary">
                                    Contact
                                </a>

                                <button
                                    type="button"
                                    class="btn btn-danger cancel-booking"
                                    data-booking="<?php echo htmlspecialchars($booking['id']); ?>">
                                    Cancel
                                </button>

                            </div>

                        </div>

                    </article>

                <?php endif; ?>

            <?php endforeach; ?>

        </section>


        <!-- COMPLETED -->

        <section
            class="booking-section"
            data-section="completed">

            <h2>
                Completed Care
            </h2>


            <div class="completed-list">


                <?php foreach ($bookings as $booking): ?>

                    <?php if ($booking['status'] === 'completed'): ?>

                        <article
                            class="booking-card completed-card"
                            data-status="completed"
                            data-patient="<?php echo htmlspecialchars($booking['patient']); ?>"
                            data-caregiver="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                        >

                            <div class="completed-main">

                                <div class="booking-person">

                                    <img
                                        src="<?php echo htmlspecialchars($booking['caregiver_image']); ?>"
                                        alt="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                                    >

                                    <div class="booking-info">

                                        <h3>
                                            <?php echo htmlspecialchars($booking['caregiver']); ?>
                                        </h3>

                                        <p class="completed-date">
                                            <?php echo htmlspecialchars($booking['date']); ?>
                                            • Completed
                                        </p>

                                        <p class="patient-line small">
                                            Patient:
                                            <?php echo htmlspecialchars($booking['patient']); ?>
                                        </p>

                                    </div>

                                </div>


                                <div class="completed-actions">

                                    <a
                                        href="complaint.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                        class="small-btn secondary">
                                        <?php echo icon('warning'); ?>
                                        Complaint
                                    </a>


                                    <?php if (!$booking['reviewed']): ?>

                                        <a
                                            href="rate-review.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                            class="small-btn rate">
                                            <?php echo icon('star'); ?>
                                            Rate Session
                                        </a>

                                    <?php else: ?>

                                        <a
                                            href="rate-review.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                            class="small-btn rate">
                                            <?php echo icon('star'); ?>
                                            Rate Session
                                        </a>

                                    <?php endif; ?>


                                    <a
                                        href="payment-history.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                        class="small-btn secondary">
                                        <?php echo icon('card'); ?>
                                        Payment
                                    </a>

                                </div>

                            </div>

                        </article>

                    <?php endif; ?>

                <?php endforeach; ?>


                <!-- Cancelled -->

                <?php foreach ($bookings as $booking): ?>

                    <?php if ($booking['status'] === 'cancelled'): ?>

                        <article
                            class="booking-card cancelled-card"
                            data-status="cancelled"
                            data-patient="<?php echo htmlspecialchars($booking['patient']); ?>"
                            data-caregiver="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                        >

                            <div class="completed-main">

                                <div class="booking-person">

                                    <img
                                        src="<?php echo htmlspecialchars($booking['caregiver_image']); ?>"
                                        alt="<?php echo htmlspecialchars($booking['caregiver']); ?>"
                                    >

                                    <div class="booking-info">

                                        <h3>
                                            <?php echo htmlspecialchars($booking['caregiver']); ?>
                                        </h3>

                                        <p class="completed-date">
                                            <?php echo htmlspecialchars($booking['date']); ?>
                                            • Cancelled
                                        </p>

                                        <p class="patient-line small">
                                            Patient:
                                            <?php echo htmlspecialchars($booking['patient']); ?>
                                        </p>

                                    </div>

                                </div>


                                <div class="completed-actions">

                                    <span class="status-badge cancelled">
                                        Cancelled
                                    </span>

                                    <a
                                        href="booking-details.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                        class="small-btn secondary">
                                        View Booking
                                    </a>

                                    <a
                                        href="payment-history.php?booking_id=<?php echo urlencode($booking['id']); ?>"
                                        class="small-btn secondary">
                                        <?php echo icon('card'); ?>
                                        Payment
                                    </a>

                                </div>

                            </div>

                        </article>

                    <?php endif; ?>

                <?php endforeach; ?>

            </div>

        </section>


    </div>


    <!-- Empty State -->

    <div
        class="empty-state"
        id="emptyState"
        hidden>

        <div class="empty-icon">
            <?php echo icon('calendar'); ?>
        </div>

        <h3>
            No bookings found
        </h3>

        <p>
            No bookings match your current search or filter.
        </p>

        <button
            type="button"
            class="btn btn-primary"
            id="clearFilters">
            Clear Filters
        </button>

    </div>

</main>


<footer class="footer">

    <div class="footer-container">

        <div class="footer-brand">
            <strong>SafeHands</strong>
        </div>

        <nav class="footer-links">

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

        <div class="copyright">
            © 2026 SafeHands Healthcare. All rights reserved.
        </div>

    </div>

</footer>


<script src="assets/js/my-booking.js"></script>

</body>
</html>