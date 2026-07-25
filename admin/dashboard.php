<?php

session_start();

require_once __DIR__ . '/../includes/db.php';


/* =========================================================
   ADMIN ACCESS CHECK
========================================================= */

/*
   We only require:
   - user_id
   - role = Admin

   We DO NOT check $_SESSION['logged_in']
   because your existing login may not create that variable.
*/

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role'])
) {
    header("Location: ../login.php");
    exit;
}


if (
    strcasecmp(
        trim($_SESSION['role']),
        'Admin'
    ) !== 0
) {
    header("Location: ../login.php");
    exit;
}


/* =========================================================
   GET ADMIN INFORMATION
========================================================= */

$adminId = (int) $_SESSION['user_id'];

$adminName = "Administrator";
$adminEmail = "";


$adminStmt = $conn->prepare(
    "
    SELECT
        first_name,
        last_name,
        email
    FROM users
    WHERE user_id = ?
      AND role = 'Admin'
    LIMIT 1
    "
);


if ($adminStmt) {

    $adminStmt->bind_param(
        "i",
        $adminId
    );

    $adminStmt->execute();

    $adminResult =
        $adminStmt->get_result();

    if (
        $adminRow =
        $adminResult->fetch_assoc()
    ) {

        $adminName =
            trim(
                $adminRow['first_name']
                . ' '
                . $adminRow['last_name']
            );

        $adminEmail =
            $adminRow['email'];
    }

    $adminStmt->close();
}


/* =========================================================
   CAREGIVER COUNTS
========================================================= */

$caregiverCounts = [
    'total' => 0,
    'pending' => 0,
    'verified' => 0,
    'rejected' => 0
];


$countSql = "
    SELECT

        COUNT(*) AS total,

        SUM(
            CASE
                WHEN cp.verification_status = 'Pending'
                THEN 1
                ELSE 0
            END
        ) AS pending,

        SUM(
            CASE
                WHEN cp.verification_status = 'Verified'
                THEN 1
                ELSE 0
            END
        ) AS verified,

        SUM(
            CASE
                WHEN cp.verification_status = 'Rejected'
                THEN 1
                ELSE 0
            END
        ) AS rejected

    FROM caregiver_profiles cp

    INNER JOIN users u
        ON cp.user_id = u.user_id

    WHERE u.role = 'Caregiver'
";


$countResult =
    $conn->query($countSql);


if ($countResult) {

    $row =
        $countResult->fetch_assoc();

    $caregiverCounts['total'] =
        (int) ($row['total'] ?? 0);

    $caregiverCounts['pending'] =
        (int) ($row['pending'] ?? 0);

    $caregiverCounts['verified'] =
        (int) ($row['verified'] ?? 0);

    $caregiverCounts['rejected'] =
        (int) ($row['rejected'] ?? 0);
}


/* =========================================================
   LATEST PENDING CAREGIVERS
========================================================= */

$pendingCaregivers = [];


$pendingSql = "
    SELECT

        cp.caregiver_id,
        cp.highest_qualification,
        cp.profile_photo,
        cp.created_at,

        u.first_name,
        u.last_name,
        u.email

    FROM caregiver_profiles cp

    INNER JOIN users u
        ON cp.user_id = u.user_id

    WHERE
        u.role = 'Caregiver'

        AND cp.verification_status = 'Pending'

    ORDER BY
        cp.created_at DESC

    LIMIT 5
";


$pendingResult =
    $conn->query($pendingSql);


if ($pendingResult) {

    while (
        $row =
        $pendingResult->fetch_assoc()
    ) {

        $pendingCaregivers[] =
            $row;
    }
}


/* =========================================================
   RECENT CAREGIVER ACTIVITY
========================================================= */

$recentCaregivers = [];


$activitySql = "
    SELECT

        cp.caregiver_id,
        cp.verification_status,
        cp.created_at,
        cp.updated_at,

        u.first_name,
        u.last_name

    FROM caregiver_profiles cp

    INNER JOIN users u
        ON cp.user_id = u.user_id

    WHERE u.role = 'Caregiver'

    ORDER BY cp.updated_at DESC

    LIMIT 5
";


$activityResult =
    $conn->query($activitySql);


if ($activityResult) {

    while (
        $row =
        $activityResult->fetch_assoc()
    ) {

        $recentCaregivers[] =
            $row;
    }
}


/* =========================================================
   HELPER FUNCTIONS
========================================================= */

function caregiverCode($id)
{
    return 'CG-'
        . str_pad(
            (string) $id,
            4,
            '0',
            STR_PAD_LEFT
        );
}


function formatDateTime($date)
{
    if (empty($date)) {
        return '—';
    }

    return date(
        'd M Y, h:i A',
        strtotime($date)
    );
}


function getInitials(
    $firstName,
    $lastName
) {

    return strtoupper(
        substr($firstName, 0, 1)
        .
        substr($lastName, 0, 1)
    );
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
        Admin Dashboard | SafeHands
    </title>


    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="admin-dashboard.css"
    >

</head>


<body>


<div class="admin-layout">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside
        class="sidebar"
        id="sidebar"
    >


        <div class="sidebar-brand">

            <div>

                <h1>
                    SafeHands
                </h1>

                <p>
                    HEALTHCARE ADMIN
                </p>

            </div>

        </div>



        <nav class="sidebar-nav">


            <a
                href="dashboard.php"
                class="active"
            >

                <span class="material-symbols-outlined">
                    dashboard
                </span>

                Dashboard

            </a>


            <a href="caregivers.php">

                <span class="material-symbols-outlined">
                    medical_services
                </span>

                Caregivers

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    family_restroom
                </span>

                Families

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    calendar_today
                </span>

                Bookings

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    payments
                </span>

                Payments

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    report_problem
                </span>

                Complaints

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    assessment
                </span>

                Reports

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    notifications
                </span>

                Notifications

            </a>


        </nav>



        <div class="sidebar-bottom">


            <a href="#">

                <span class="material-symbols-outlined">
                    settings
                </span>

                Settings

            </a>


            <a href="../logout.php">

                <span class="material-symbols-outlined">
                    logout
                </span>

                Logout

            </a>


        </div>


    </aside>



    <!-- =====================================================
         MAIN AREA
    ====================================================== -->

    <div class="main-area">


        <!-- TOP BAR -->

        <header class="topbar">


            <div class="topbar-left">


                <button
                    class="menu-button"
                    id="menuButton"
                    type="button"
                >

                    <span class="material-symbols-outlined">
                        menu
                    </span>

                </button>


                <div class="search-box">

                    <span class="material-symbols-outlined">
                        search
                    </span>

                    <input
                        type="text"
                        placeholder="Search caregivers, families, or transactions..."
                        disabled
                    >

                </div>


            </div>



            <div class="topbar-right">


                <button class="notification-button">

                    <span class="material-symbols-outlined">
                        notifications
                    </span>

                </button>



                <div class="admin-profile">


                    <div class="admin-details">

                        <strong>
                            <?= htmlspecialchars($adminName) ?>
                        </strong>

                        <span>
                            SYSTEM ADMIN
                        </span>

                    </div>


                    <div class="admin-avatar">

                        <?= htmlspecialchars(
                            strtoupper(
                                substr(
                                    $adminName,
                                    0,
                                    1
                                )
                            )
                        ) ?>

                    </div>


                </div>


            </div>


        </header>



        <!-- =================================================
             CONTENT
        ================================================== -->

        <main class="content">


            <!-- HEADER -->

            <div class="page-header">


                <div>

                    <h2>
                        Welcome Back, Administrator
                    </h2>

                    <p>
                        Today's platform overview and pending tasks.
                    </p>

                </div>


                <div class="date-box">

                    <span class="material-symbols-outlined">
                        calendar_month
                    </span>

                    <span id="currentDate">

                        <?= date('F d, Y') ?>

                    </span>

                </div>


            </div>



            <!-- =================================================
                 MAIN GRID
            ================================================== -->

            <div class="dashboard-grid">


                <!-- LEFT -->

                <div class="dashboard-main">


                    <!-- ACTION CARDS -->

                    <section class="action-grid">


                        <!-- PENDING CAREGIVERS -->

                        <a
                            href="caregivers.php?status=Pending"
                            class="action-card"
                        >


                            <div class="action-card-top">


                                <div class="action-icon warning">

                                    <span class="material-symbols-outlined">
                                        person_add
                                    </span>

                                </div>


                                <?php
                                if (
                                    $caregiverCounts['pending'] > 0
                                ):
                                ?>

                                    <span class="action-label warning">
                                        ACTION REQUIRED
                                    </span>

                                <?php else: ?>

                                    <span class="action-label success">
                                        UP TO DATE
                                    </span>

                                <?php endif; ?>


                            </div>


                            <h3>

                                <?= $caregiverCounts['pending'] ?>

                                Caregiver Application<?=

                                    $caregiverCounts['pending'] === 1
                                    ? ''
                                    : 's'

                                ?>

                            </h3>


                            <span class="action-link">

                                Review Applications

                                <span class="material-symbols-outlined">
                                    arrow_forward
                                </span>

                            </span>


                        </a>



                        <!-- TOTAL CAREGIVERS -->

                        <a
                            href="caregivers.php"
                            class="action-card"
                        >


                            <div class="action-card-top">


                                <div class="action-icon primary">

                                    <span class="material-symbols-outlined">
                                        groups
                                    </span>

                                </div>


                                <span class="action-label primary">
                                    CAREGIVERS
                                </span>


                            </div>


                            <h3>

                                <?= $caregiverCounts['total'] ?>

                                Registered Caregiver<?=

                                    $caregiverCounts['total'] === 1
                                    ? ''
                                    : 's'

                                ?>

                            </h3>


                            <span class="action-link">

                                Manage Caregivers

                                <span class="material-symbols-outlined">
                                    arrow_forward
                                </span>

                            </span>


                        </a>



                        <!-- VERIFIED -->

                        <a
                            href="caregivers.php?status=Verified"
                            class="action-card"
                        >


                            <div class="action-card-top">


                                <div class="action-icon success">

                                    <span class="material-symbols-outlined">
                                        verified
                                    </span>

                                </div>


                                <span class="action-label success">
                                    VERIFIED
                                </span>


                            </div>


                            <h3>

                                <?= $caregiverCounts['verified'] ?>

                                Verified Caregiver<?=

                                    $caregiverCounts['verified'] === 1
                                    ? ''
                                    : 's'

                                ?>

                            </h3>


                            <span class="action-link">

                                View Verified

                                <span class="material-symbols-outlined">
                                    arrow_forward
                                </span>

                            </span>


                        </a>



                        <!-- REJECTED -->

                        <a
                            href="caregivers.php?status=Rejected"
                            class="action-card"
                        >


                            <div class="action-card-top">


                                <div class="action-icon rejected">

                                    <span class="material-symbols-outlined">
                                        person_cancel
                                    </span>

                                </div>


                                <span class="action-label rejected">
                                    REJECTED
                                </span>


                            </div>


                            <h3>

                                <?= $caregiverCounts['rejected'] ?>

                                Rejected Application<?=

                                    $caregiverCounts['rejected'] === 1
                                    ? ''
                                    : 's'

                                ?>

                            </h3>


                            <span class="action-link">

                                View Applications

                                <span class="material-symbols-outlined">
                                    arrow_forward
                                </span>

                            </span>


                        </a>


                    </section>



                    <!-- =================================================
                         VERIFICATION QUEUE
                    ================================================== -->

                    <section class="panel">


                        <div class="panel-header">


                            <div>

                                <h3>
                                    Verification Queue
                                </h3>

                                <p>
                                    Latest caregiver applications waiting for review.
                                </p>

                            </div>


                            <a href="caregivers.php?status=Pending">
                                View All
                            </a>


                        </div>



                        <?php
                        if (
                            !empty($pendingCaregivers)
                        ):
                        ?>


                            <div class="table-wrapper">


                                <table>


                                    <thead>

                                        <tr>

                                            <th>
                                                Caregiver
                                            </th>

                                            <th>
                                                Qualification
                                            </th>

                                            <th>
                                                Submitted
                                            </th>

                                            <th></th>

                                        </tr>

                                    </thead>


                                    <tbody>


                                    <?php
                                    foreach (
                                        $pendingCaregivers
                                        as $caregiver
                                    ):
                                    ?>


                                        <?php

                                        $fullName =
                                            trim(
                                                $caregiver['first_name']
                                                . ' '
                                                . $caregiver['last_name']
                                            );


                                        $initials =
                                            getInitials(
                                                $caregiver['first_name'],
                                                $caregiver['last_name']
                                            );


                                        $code =
                                            caregiverCode(
                                                $caregiver['caregiver_id']
                                            );

                                        ?>


                                        <tr>


                                            <td>


                                                <div class="caregiver-info">


                                                    <div class="caregiver-avatar">

                                                        <?= htmlspecialchars(
                                                            $initials
                                                        ) ?>

                                                    </div>


                                                    <div>

                                                        <strong>

                                                            <?= htmlspecialchars(
                                                                $fullName
                                                            ) ?>

                                                        </strong>


                                                        <span>

                                                            <?= htmlspecialchars(
                                                                $code
                                                            ) ?>

                                                        </span>

                                                    </div>


                                                </div>


                                            </td>



                                            <td>

                                                <?= htmlspecialchars(
                                                    $caregiver[
                                                        'highest_qualification'
                                                    ]
                                                    ?: 'Not provided'
                                                ) ?>

                                            </td>



                                            <td>

                                                <?= htmlspecialchars(
                                                    date(
                                                        'd M Y',
                                                        strtotime(
                                                            $caregiver[
                                                                'created_at'
                                                            ]
                                                        )
                                                    )
                                                ) ?>

                                            </td>



                                            <td class="table-action">


                                                <a
                                                    href="caregiver-review.php?id=<?= (int) $caregiver['caregiver_id'] ?>"
                                                >

                                                    Review

                                                </a>


                                            </td>


                                        </tr>


                                    <?php endforeach; ?>


                                    </tbody>


                                </table>


                            </div>


                        <?php else: ?>


                            <div class="empty-queue">


                                <span class="material-symbols-outlined">
                                    task_alt
                                </span>


                                <h4>
                                    No pending applications
                                </h4>


                                <p>

                                    There are currently no caregiver
                                    applications waiting for verification.

                                </p>


                            </div>


                        <?php endif; ?>


                    </section>


                </div>



                <!-- =================================================
                     RIGHT COLUMN
                ================================================== -->

                <aside class="dashboard-side">


                    <!-- ACTIVITY -->

                    <section class="side-panel">


                        <div class="side-panel-header">

                            <h3>
                                Recent Caregiver Activity
                            </h3>

                        </div>


                        <?php
                        if (
                            !empty($recentCaregivers)
                        ):
                        ?>


                            <div class="activity-list">


                                <?php
                                foreach (
                                    $recentCaregivers
                                    as $activity
                                ):
                                ?>


                                    <?php

                                    $activityName =
                                        trim(
                                            $activity['first_name']
                                            . ' '
                                            . $activity['last_name']
                                        );


                                    $activityStatus =
                                        $activity[
                                            'verification_status'
                                        ];


                                    if (
                                        $activityStatus === 'Verified'
                                    ) {

                                        $activityText =
                                            'Caregiver profile verified';

                                        $activityClass =
                                            'success';

                                    } elseif (
                                        $activityStatus === 'Rejected'
                                    ) {

                                        $activityText =
                                            'Caregiver application rejected';

                                        $activityClass =
                                            'rejected';

                                    } else {

                                        $activityText =
                                            'Caregiver application submitted';

                                        $activityClass =
                                            'primary';
                                    }

                                    ?>


                                    <div class="activity-item">


                                        <div
                                            class="activity-dot <?= $activityClass ?>"
                                        ></div>


                                        <div>


                                            <span class="activity-time">

                                                <?= htmlspecialchars(
                                                    formatDateTime(
                                                        $activity[
                                                            'updated_at'
                                                        ]
                                                    )
                                                ) ?>

                                            </span>


                                            <strong>

                                                <?= htmlspecialchars(
                                                    $activityName
                                                ) ?>

                                            </strong>


                                            <p>

                                                <?= htmlspecialchars(
                                                    $activityText
                                                ) ?>

                                            </p>


                                        </div>


                                    </div>


                                <?php endforeach; ?>


                            </div>


                        <?php else: ?>


                            <div class="small-empty">

                                No caregiver activity yet.

                            </div>


                        <?php endif; ?>


                    </section>



                    <!-- SHORTCUTS -->

                    <section>


                        <h3 class="section-title">
                            Quick Access
                        </h3>


                        <div class="shortcut-grid">


                            <a href="caregivers.php">

                                <span class="material-symbols-outlined">
                                    manage_accounts
                                </span>

                                <strong>
                                    Manage Caregivers
                                </strong>

                            </a>


                            <a href="caregivers.php?status=Pending">

                                <span class="material-symbols-outlined">
                                    pending_actions
                                </span>

                                <strong>
                                    Review Applications
                                </strong>

                            </a>


                            <a href="caregivers.php?status=Verified">

                                <span class="material-symbols-outlined">
                                    verified
                                </span>

                                <strong>
                                    Verified Caregivers
                                </strong>

                            </a>


                            <a href="caregivers.php?status=Rejected">

                                <span class="material-symbols-outlined">
                                    person_cancel
                                </span>

                                <strong>
                                    Rejected Applications
                                </strong>

                            </a>


                        </div>


                    </section>


                </aside>


            </div>



            <!-- =================================================
                 PLATFORM OVERVIEW
            ================================================== -->

            <section class="overview-section">


                <div class="section-heading">

                    <div>

                        <h3>
                            Caregiver Overview
                        </h3>

                        <p>
                            Current caregiver verification status across SafeHands.
                        </p>

                    </div>

                </div>



                <div class="overview-grid">


                    <div class="overview-item">

                        <span class="material-symbols-outlined">
                            groups
                        </span>

                        <div>

                            <strong>
                                <?= $caregiverCounts['total'] ?>
                            </strong>

                            <span>
                                Total Caregivers
                            </span>

                        </div>

                    </div>



                    <div class="overview-item">

                        <span class="material-symbols-outlined">
                            pending_actions
                        </span>

                        <div>

                            <strong>
                                <?= $caregiverCounts['pending'] ?>
                            </strong>

                            <span>
                                Awaiting Review
                            </span>

                        </div>

                    </div>



                    <div class="overview-item">

                        <span class="material-symbols-outlined">
                            verified_user
                        </span>

                        <div>

                            <strong>
                                <?= $caregiverCounts['verified'] ?>
                            </strong>

                            <span>
                                Verified
                            </span>

                        </div>

                    </div>



                    <div class="overview-item">

                        <span class="material-symbols-outlined">
                            block
                        </span>

                        <div>

                            <strong>
                                <?= $caregiverCounts['rejected'] ?>
                            </strong>

                            <span>
                                Rejected
                            </span>

                        </div>

                    </div>


                </div>


            </section>


        </main>



        <!-- FOOTER -->

        <footer class="footer">

            <span>
                © <?= date('Y') ?> SafeHands Healthcare Platform
            </span>

            <div>

                <span class="system-dot"></span>

                System Operational

            </div>

        </footer>


    </div>


</div>



<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


<script src="admin-dashboard.js"></script>


</body>

</html>