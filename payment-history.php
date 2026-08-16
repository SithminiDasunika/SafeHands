<?php
session_start();

/*
|--------------------------------------------------------------------------
| SafeHands - Payment History
|--------------------------------------------------------------------------
| UI version.
| Later, replace these sample values with MySQL data.
|--------------------------------------------------------------------------
*/

$booking = [
    'booking_id' => 'SH-882910',
    'caregiver' => 'Nadeesha Perera',
    'caregiver_role' => 'RN',
    'caregiver_rating' => '4.9',
    'patient' => 'Ananda Silva',
    'date' => '15 Aug 2026',
    'duration' => '8 Hours',

    'service_fee' => 8000.00,
    'platform_fee' => 400.00,
    'total_paid' => 8400.00,

    'transaction_id' => 'TXN-SH-2026-008291',
    'payment_method' => 'Card ending in 4521 (Visa)',

    'payment_status' => 'Completed'
];

$payment_history = [
    [
        'date' => '15 Aug 2026, 08:00 AM',
        'action' => 'Payment Initiated',
        'status' => 'Authorized',
        'status_class' => 'authorized'
    ],
    [
        'date' => '15 Aug 2026, 08:05 AM',
        'action' => 'Payment Held in Escrow',
        'status' => 'In Escrow',
        'status_class' => 'escrow'
    ],
    [
        'date' => '15 Aug 2026, 04:30 PM',
        'action' => 'Payment Released to Caregiver',
        'status' => 'Completed',
        'status_class' => 'completed'
    ]
];

function icon($name)
{
    $icons = [

        'bell' => '
            <svg viewBox="0 0 24 24">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                <path d="M10 21h4"></path>
            </svg>
        ',

        'user' => '
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="8" r="3.5"></circle>
                <path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"></path>
            </svg>
        ',

        'star' => '
            <svg viewBox="0 0 24 24">
                <path d="m12 3 2.8 5.7 6.2.9-4.5 4.4 1.1 6.2-5.6-3-5.6 3 1.1-6.2L3 9.6l6.2-.9L12 3Z"></path>
            </svg>
        ',

        'check' => '
            <svg viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="9"></circle>
                <polyline points="8 12 11 15 16 9"></polyline>
            </svg>
        ',

        'verified' => '
            <svg viewBox="0 0 24 24">
                <path d="M12 3l2 1 2.2-.2 1.2 1.8 2 1 .2 2.2 1 2-1 2 .2 2.2-2 1-1.2 1.8-2.2-.2-2 1-2-1-2.2.2-1.2-1.8-2-1-.2-2.2-1-2 1-2-.2-2.2 2-1L7.8 3.8 10 4l2-1Z"></path>
                <polyline points="8 12 11 15 16 9"></polyline>
            </svg>
        ',

        'card' => '
            <svg viewBox="0 0 24 24">
                <rect x="3" y="5" width="18" height="14" rx="2"></rect>
                <line x1="3" y1="10" x2="21" y2="10"></line>
                <line x1="7" y1="15" x2="11" y2="15"></line>
            </svg>
        ',

        'lock' => '
            <svg viewBox="0 0 24 24">
                <rect x="5" y="10" width="14" height="10" rx="2"></rect>
                <path d="M8 10V7a4 4 0 0 1 8 0v3"></path>
            </svg>
        ',

        'download' => '
            <svg viewBox="0 0 24 24">
                <path d="M12 3v12"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <path d="M5 21h14"></path>
            </svg>
        ',

        'arrow-left' => '
            <svg viewBox="0 0 24 24">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Payment History | SafeHands
    </title>

    <link
        rel="stylesheet"
        href="assets/css/payment-history.css"
    >

</head>


<body>


<!-- =====================================================
     NAVIGATION
====================================================== -->

<nav class="top-nav">

    <div class="nav-container">


        <div class="nav-left">

            <a
                href="family/dashboard.php"
                class="logo"
            >
                SafeHands
            </a>


            <div class="main-nav">

                <a href="family/dashboard.php">
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

            </div>

        </div>


        <div class="nav-right">

            <a
                href="notifications.php"
                class="nav-icon"
                title="Notifications"
            >
                <?php echo icon('bell'); ?>
            </a>


            <a
                href="family/profile.php"
                class="nav-icon"
                title="Profile"
            >
                <?php echo icon('user'); ?>
            </a>


            <a
                href="logout.php"
                class="sign-out"
            >
                Sign Out
            </a>

        </div>

    </div>

</nav>



<!-- =====================================================
     MAIN
====================================================== -->

<main class="main-container">


    <!-- Breadcrumb -->

    <nav class="breadcrumb">

        <a href="family/dashboard.php">
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
            Payment History
        </span>

    </nav>



    <!-- Page Header -->

    <header class="page-header">

        <div>

            <h1>
                Payment History
            </h1>

            <p>
                View payment details and transaction history for this care booking.
            </p>

        </div>


        <div class="payment-completed">

            <?php echo icon('check'); ?>

            Payment Completed

        </div>

    </header>



    <!-- =================================================
         TWO COLUMN LAYOUT
    ================================================== -->

    <div class="payment-layout">


        <!-- =================================================
             LEFT COLUMN
        ================================================== -->

        <aside class="left-column">


            <!-- Caregiver Card -->

            <section class="caregiver-card">


                <div class="caregiver-header">

                    <img
                        src="assets/images/caregiver-placeholder.jpg"
                        alt="Caregiver profile"
                        class="caregiver-image"
                    >


                    <div>

                        <h2>
                            <?php echo htmlspecialchars($booking['caregiver']); ?>
                        </h2>

                        <p class="caregiver-rating">

                            <?php echo htmlspecialchars($booking['caregiver_role']); ?>

                            <span>•</span>

                            <span class="star">
                                <?php echo icon('star'); ?>
                            </span>

                            <?php echo htmlspecialchars($booking['caregiver_rating']); ?>

                        </p>

                    </div>

                </div>


                <hr>


                <div class="booking-details">


                    <div class="detail-row">

                        <span>
                            Patient
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($booking['patient']); ?>
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Booking ID
                        </span>

                        <strong>
                            #<?php echo htmlspecialchars($booking['booking_id']); ?>
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Date
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($booking['date']); ?>
                        </strong>

                    </div>


                    <div class="detail-row">

                        <span>
                            Duration
                        </span>

                        <strong>
                            <?php echo htmlspecialchars($booking['duration']); ?>
                        </strong>

                    </div>


                </div>


                <div class="session-completed">

                    <?php echo icon('verified'); ?>

                    Care Session Completed

                </div>


            </section>


        </aside>



        <!-- =================================================
             RIGHT COLUMN
        ================================================== -->

        <section class="right-column">


            <!-- Payment Overview -->

            <section class="payment-overview">

                <div>

                    <span class="section-label">
                        Total Paid
                    </span>

                    <div class="total-price">
                        Rs. <?php echo number_format($booking['total_paid'], 2); ?>
                    </div>

                </div>


                <div class="fee-details">

                    <div>

                        <span>
                            Service Fee
                        </span>

                        <strong>
                            Rs. <?php echo number_format($booking['service_fee'], 2); ?>
                        </strong>

                    </div>


                    <div>

                        <span>
                            Platform Fee
                        </span>

                        <strong>
                            Rs. <?php echo number_format($booking['platform_fee'], 2); ?>
                        </strong>

                    </div>

                </div>

            </section>



            <!-- Transaction + Escrow -->

            <div class="two-small-cards">


                <!-- Transaction -->

                <section class="info-card">

                    <h2>
                        Transaction Details
                    </h2>


                    <div class="transaction-details">

                        <div>

                            <span class="section-label">
                                Transaction ID
                            </span>

                            <strong class="transaction-id">
                                <?php echo htmlspecialchars($booking['transaction_id']); ?>
                            </strong>

                        </div>


                        <div>

                            <span class="section-label">
                                Payment Method
                            </span>


                            <strong class="payment-method">

                                <?php echo icon('card'); ?>

                                <?php echo htmlspecialchars($booking['payment_method']); ?>

                            </strong>

                        </div>

                    </div>

                </section>



                <!-- Escrow -->

                <section class="escrow-card">

                    <div class="escrow-title">

                        <?php echo icon('lock'); ?>

                        Secure Escrow Payment

                    </div>


                    <p>

                        Funds are held securely and only released to
                        the caregiver after the care session is completed
                        and verified.

                    </p>

                </section>


            </div>



            <!-- Payment Status -->

            <section class="history-card">


                <div class="history-header">

                    <h2>
                        Payment Status
                    </h2>

                </div>


                <div class="table-wrapper">

                    <table>

                        <thead>

                            <tr>

                                <th>
                                    Date & Time
                                </th>

                                <th>
                                    Action
                                </th>

                                <th>
                                    Status
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php foreach ($payment_history as $payment): ?>

                                <tr>

                                    <td>
                                        <?php echo htmlspecialchars($payment['date']); ?>
                                    </td>

                                    <td class="action-cell">
                                        <?php echo htmlspecialchars($payment['action']); ?>
                                    </td>

                                    <td>

                                        <span
                                            class="table-status <?php echo htmlspecialchars($payment['status_class']); ?>"
                                        >
                                            <?php echo htmlspecialchars($payment['status']); ?>
                                        </span>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </section>



            <!-- Actions -->

            <div class="page-actions">


                <a
                    href="my-bookings.php"
                    class="back-button"
                >

                    <?php echo icon('arrow-left'); ?>

                    Back to My Bookings

                </a>


                <button
                    type="button"
                    id="downloadReceipt"
                    class="download-button"
                >

                    <?php echo icon('download'); ?>

                    Download Receipt

                </button>


            </div>


        </section>

    </div>

</main>



<!-- =====================================================
     FOOTER
====================================================== -->

<footer class="footer">

    <div class="footer-container">


        <strong class="footer-logo">
            SafeHands
        </strong>


        <div class="footer-links">

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Terms of Service
            </a>

            <a href="#">
                Contact Us
            </a>

        </div>


        <p>
            © 2026 SafeHands Healthcare. All rights reserved.
        </p>


    </div>

</footer>



<script src="assets/js/payment-history.js"></script>

</body>

</html>