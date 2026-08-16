<?php
session_start();

/*
|--------------------------------------------------------------------------
| SafeHands - Family Member Notifications
|--------------------------------------------------------------------------
| UI version using sample notifications.
| Later we can connect this page to MySQL.
|--------------------------------------------------------------------------
*/

// Temporary sample data
$notifications = [
    [
        'id' => 1,
        'type' => 'emergency',
        'title' => 'Emergency Alert',
        'message' => 'An emergency alert was triggered for Ananda Silva.',
        'time' => '10:42 AM',
        'group' => 'Today',
        'read' => false,
        'action_text' => 'View Emergency',
        'action_url' => 'dashboard.php#emergency'
    ],
    [
        'id' => 2,
        'type' => 'care',
        'title' => 'Daily Care Report Available',
        'message' => 'A new care report for Ananda Silva is available.',
        'time' => '08:15 AM',
        'group' => 'Today',
        'read' => false,
        'action_text' => 'View Report',
        'action_url' => 'dailycare-report.php'
    ],
    [
        'id' => 3,
        'type' => 'review',
        'title' => 'How was your care experience?',
        'message' => 'Your care session with Nadeesha Perera has been completed. Please rate your caregiver.',
        'time' => '07:00 AM',
        'group' => 'Today',
        'read' => false,
        'action_text' => 'Rate Caregiver',
        'action_url' => 'rate-review.php'
    ],
    [
        'id' => 4,
        'type' => 'booking',
        'title' => 'Booking Approved',
        'message' => 'Your booking with Nadeesha Perera for Ananda Silva has been approved.',
        'time' => '04:30 PM',
        'group' => 'Yesterday',
        'read' => true,
        'action_text' => 'View Booking',
        'action_url' => 'my-booking.php'
    ],
    [
        'id' => 5,
        'type' => 'payment',
        'title' => 'Payment Successful',
        'message' => 'Your payment for booking #SH-882910 has been successfully received.',
        'time' => '02:15 PM',
        'group' => 'Yesterday',
        'read' => true,
        'action_text' => 'View Payment',
        'action_url' => 'payment-history.php'
    ],
    [
        'id' => 6,
        'type' => 'complaint',
        'title' => 'Complaint Updated',
        'message' => 'Our support team has updated the status of your complaint regarding booking #SH-882910.',
        'time' => 'Oct 12',
        'group' => 'Earlier',
        'read' => true,
        'action_text' => 'View Complaint',
        'action_url' => 'complaint.php'
    ]
];

$unreadCount = 0;

foreach ($notifications as $notification) {
    if (!$notification['read']) {
        $unreadCount++;
    }
}

/*
|--------------------------------------------------------------------------
| Notification icon mapping
|--------------------------------------------------------------------------
*/

$icons = [
    'emergency' => '!',
    'care' => '▣',
    'review' => '★',
    'booking' => '✓',
    'payment' => '$',
    'complaint' => '?'
];

$groups = [
    'Today',
    'Yesterday',
    'Earlier'
];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Notifications | SafeHands</title>

    <link
        rel="stylesheet"
        href="assets/css/family-notification.css"
    >

</head>

<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="top-header">

    <div class="header-inner">


        <!-- Logo -->

        <a
            href="family/dashboard.php"
            class="logo"
        >
            SafeHands
        </a>


        <!-- Navigation -->

        <nav class="main-nav">

            <a href="family/dashboard.php">
                Dashboard
            </a>

            <a href="patients.php">
                Patients
            </a>

            <a href="#">
                Find Caregivers
            </a>

            <a href="my-booking.php">
                My Bookings
            </a>

        </nav>


        <!-- Header Actions -->

        <div class="header-actions">

            <button
                type="button"
                class="notification-button active"
                aria-label="Notifications"
            >

                <span class="bell-icon">
                    ♧
                </span>

                <?php if ($unreadCount > 0): ?>

                    <span class="notification-dot"></span>

                <?php endif; ?>

            </button>


            <button
                type="button"
                class="profile-button"
                aria-label="Profile"
            >
                ●
            </button>

        </div>


    </div>

</header>



<!-- =========================================================
     MAIN
========================================================= -->

<main class="page-container">


    <!-- Breadcrumb -->

    <nav class="breadcrumb">

        <a href="family/dashboard.php">
            Dashboard
        </a>

        <span>
            ›
        </span>

        <span class="current">
            Notifications
        </span>

    </nav>



    <!-- Page Header -->

    <section class="page-header">


        <div>

            <h1>
                Notifications
            </h1>

            <p>
                Stay updated about your patients, caregivers,
                bookings and payments.
            </p>

        </div>


        <div class="header-controls">


            <span class="unread-count">

                <?php echo $unreadCount; ?> Unread

            </span>


            <button
                type="button"
                id="markAllRead"
                class="mark-read-button"
            >

                <span>
                    ✓
                </span>

                Mark all as read

            </button>


        </div>


    </section>



    <!-- =====================================================
         FILTER TABS
    ====================================================== -->

    <div class="filter-container">

        <button
            class="filter-tab active"
            data-filter="all"
        >
            All
        </button>

        <button
            class="filter-tab"
            data-filter="booking"
        >
            Bookings
        </button>

        <button
            class="filter-tab"
            data-filter="care"
        >
            Care
        </button>

        <button
            class="filter-tab"
            data-filter="payment"
        >
            Payments
        </button>

        <button
            class="filter-tab"
            data-filter="review"
        >
            Reviews
        </button>

        <button
            class="filter-tab"
            data-filter="complaint"
        >
            Complaints
        </button>

        <button
            class="filter-tab"
            data-filter="emergency"
        >
            Emergency
        </button>

    </div>



    <!-- =====================================================
         NOTIFICATIONS
    ====================================================== -->

    <div class="notifications-container">


        <?php foreach ($groups as $group): ?>

            <?php

            $groupNotifications = array_filter(
                $notifications,
                function ($notification) use ($group) {
                    return $notification['group'] === $group;
                }
            );

            ?>

            <?php if (!empty($groupNotifications)): ?>


                <section class="notification-group">


                    <h2>
                        <?php echo htmlspecialchars($group); ?>
                    </h2>


                    <div class="notification-list">


                        <?php foreach ($groupNotifications as $notification): ?>

                            <article
                                class="notification-card
                                <?php echo !$notification['read'] ? 'unread' : 'read'; ?>
                                <?php echo $notification['type'] === 'emergency' ? 'emergency-card' : ''; ?>"
                                data-type="<?php echo htmlspecialchars($notification['type']); ?>"
                                data-id="<?php echo $notification['id']; ?>"
                            >


                                <?php if (!$notification['read']): ?>

                                    <span class="unread-dot"></span>

                                <?php endif; ?>


                                <!-- Icon -->

                                <div class="notification-icon <?php echo $notification['type']; ?>">

                                    <?php echo $icons[$notification['type']] ?? '•'; ?>

                                </div>


                                <!-- Content -->

                                <div class="notification-content">


                                    <div class="notification-top">


                                        <h3>

                                            <?php echo htmlspecialchars($notification['title']); ?>

                                        </h3>


                                        <span class="notification-time">

                                            <?php echo htmlspecialchars($notification['time']); ?>

                                        </span>


                                    </div>


                                    <p>

                                        <?php echo htmlspecialchars($notification['message']); ?>

                                    </p>


                                    <a
                                        href="<?php echo htmlspecialchars($notification['action_url']); ?>"
                                        class="notification-action"
                                    >

                                        <?php echo htmlspecialchars($notification['action_text']); ?>

                                        <span>
                                            →
                                        </span>

                                    </a>


                                </div>


                            </article>

                        <?php endforeach; ?>


                    </div>


                </section>


            <?php endif; ?>


        <?php endforeach; ?>


        <!-- Empty state -->

        <div
            id="emptyState"
            class="empty-state hidden"
        >

            <div class="empty-icon">
                ♧
            </div>

            <h3>
                You're all caught up
            </h3>

            <p>
                You don't have any notifications in this category.
            </p>

        </div>


    </div>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="footer-inner">


        <div class="footer-brand">

            <strong>
                SafeHands
            </strong>

            <p>
                © 2026 SafeHands Healthcare.
                All rights reserved.
            </p>

        </div>


        <div class="footer-links">

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Terms of Service
            </a>

            <a href="#">
                Contact Support
            </a>

        </div>


    </div>

</footer>



<script src="assets/js/family-notification.js"></script>

</body>

</html>