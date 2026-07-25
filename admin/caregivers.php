<?php

/*
|--------------------------------------------------------------------------
| SAFEHANDS - ADMIN CAREGIVER MANAGEMENT
|--------------------------------------------------------------------------
*/

session_start();

require_once __DIR__ . '/../includes/db.php';


/*
|--------------------------------------------------------------------------
| ADMIN ACCESS PROTECTION
|--------------------------------------------------------------------------
|
| Only a logged-in Admin should access this page.
|
*/

if (
    empty($_SESSION['logged_in']) ||
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role'])
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


/*
|--------------------------------------------------------------------------
| GET FILTER VALUES
|--------------------------------------------------------------------------
*/

$selectedStatus =
    isset($_GET['status'])
        ? trim($_GET['status'])
        : 'All';


$search =
    isset($_GET['search'])
        ? trim($_GET['search'])
        : '';


$sort =
    isset($_GET['sort'])
        ? trim($_GET['sort'])
        : 'newest';


/*
|--------------------------------------------------------------------------
| VALIDATE STATUS FILTER
|--------------------------------------------------------------------------
*/

$allowedStatuses = [
    'All',
    'Pending',
    'Verified',
    'Rejected'
];


if (
    !in_array(
        $selectedStatus,
        $allowedStatuses,
        true
    )
) {

    $selectedStatus = 'All';
}


/*
|--------------------------------------------------------------------------
| VALIDATE SORT
|--------------------------------------------------------------------------
*/

if (
    !in_array(
        $sort,
        ['newest', 'oldest'],
        true
    )
) {

    $sort = 'newest';
}


/*
|--------------------------------------------------------------------------
| GET SUMMARY COUNTS
|--------------------------------------------------------------------------
*/

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


$counts = [

    'total' => 0,
    'pending' => 0,
    'verified' => 0,
    'rejected' => 0

];


if ($countResult) {

    $countRow =
        $countResult->fetch_assoc();


    $counts['total'] =
        (int) ($countRow['total'] ?? 0);

    $counts['pending'] =
        (int) ($countRow['pending'] ?? 0);

    $counts['verified'] =
        (int) ($countRow['verified'] ?? 0);

    $counts['rejected'] =
        (int) ($countRow['rejected'] ?? 0);
}


/*
|--------------------------------------------------------------------------
| BUILD CAREGIVER QUERY
|--------------------------------------------------------------------------
*/

$sql = "

    SELECT

        cp.caregiver_id,
        cp.user_id,
        cp.highest_qualification,
        cp.years_experience,
        cp.profile_photo,
        cp.verification_status,
        cp.created_at,

        u.first_name,
        u.last_name,
        u.email,
        u.phone

    FROM caregiver_profiles cp

    INNER JOIN users u
        ON cp.user_id = u.user_id

    WHERE u.role = 'Caregiver'

";


$params = [];
$types = "";


/*
|--------------------------------------------------------------------------
| STATUS FILTER
|--------------------------------------------------------------------------
*/

if ($selectedStatus !== 'All') {

    $sql .= "
        AND cp.verification_status = ?
    ";

    $params[] =
        $selectedStatus;

    $types .= "s";
}


/*
|--------------------------------------------------------------------------
| SEARCH FILTER
|--------------------------------------------------------------------------
|
| Search by:
| - First name
| - Last name
| - Full name
| - Email
| - Qualification
| - Caregiver ID
|
*/

if ($search !== '') {

    $sql .= "

        AND (

            u.first_name LIKE ?

            OR u.last_name LIKE ?

            OR CONCAT(
                u.first_name,
                ' ',
                u.last_name
            ) LIKE ?

            OR u.email LIKE ?

            OR cp.highest_qualification LIKE ?

            OR CAST(
                cp.caregiver_id AS CHAR
            ) LIKE ?

        )

    ";


    $searchValue =
        '%' . $search . '%';


    for ($i = 0; $i < 6; $i++) {

        $params[] =
            $searchValue;

        $types .= "s";
    }
}


/*
|--------------------------------------------------------------------------
| SORTING
|--------------------------------------------------------------------------
*/

if ($sort === 'oldest') {

    $sql .= "
        ORDER BY cp.created_at ASC
    ";

} else {

    $sql .= "
        ORDER BY cp.created_at DESC
    ";
}


/*
|--------------------------------------------------------------------------
| PREPARE QUERY
|--------------------------------------------------------------------------
*/

$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    die(
        "Unable to load caregiver records: "
        . htmlspecialchars(
            $conn->error
        )
    );
}


/*
|--------------------------------------------------------------------------
| BIND PARAMETERS
|--------------------------------------------------------------------------
*/

if (!empty($params)) {

    $stmt->bind_param(
        $types,
        ...$params
    );
}


$stmt->execute();


$result =
    $stmt->get_result();


$caregivers = [];


while (
    $row =
    $result->fetch_assoc()
) {

    $caregivers[] =
        $row;
}


$stmt->close();


/*
|--------------------------------------------------------------------------
| ADMIN DISPLAY NAME
|--------------------------------------------------------------------------
*/

$adminName = 'Admin User';


if (
    !empty($_SESSION['first_name'])
) {

    $adminName =
        trim(
            $_SESSION['first_name']
            . ' '
            . (
                $_SESSION['last_name']
                ?? ''
            )
        );
}


/*
|--------------------------------------------------------------------------
| HELPER - STATUS COUNT
|--------------------------------------------------------------------------
*/

function getStatusCount(
    string $status,
    array $counts
): int {

    switch ($status) {

        case 'Pending':
            return $counts['pending'];

        case 'Verified':
            return $counts['verified'];

        case 'Rejected':
            return $counts['rejected'];

        default:
            return $counts['total'];
    }
}


/*
|--------------------------------------------------------------------------
| HELPER - CREATE FILTER URL
|--------------------------------------------------------------------------
*/

function createFilterUrl(
    string $status,
    string $search,
    string $sort
): string {

    return '?'
        . http_build_query(
            [
                'status' => $status,
                'search' => $search,
                'sort' => $sort
            ]
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
        Caregiver Management | SafeHands Admin
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
        href="caregivers.css"
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


            <div class="brand-icon">

                <span class="material-symbols-outlined">
                    health_and_safety
                </span>

            </div>


            <div>

                <h1>
                    SafeHands
                </h1>

                <span>
                    HEALTHCARE ADMIN
                </span>

            </div>


        </div>



        <nav class="sidebar-nav">


            <a href="dashboard.php">

                <span class="material-symbols-outlined">
                    dashboard
                </span>

                <span>
                    Dashboard
                </span>

            </a>


            <a
                href="caregivers.php"
                class="active"
            >

                <span class="material-symbols-outlined">
                    medical_information
                </span>

                <span>
                    Caregivers
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    family_restroom
                </span>

                <span>
                    Families
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    calendar_month
                </span>

                <span>
                    Bookings
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    payments
                </span>

                <span>
                    Payments
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    report_problem
                </span>

                <span>
                    Complaints
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    monitoring
                </span>

                <span>
                    Reports
                </span>

            </a>


            <a href="#">

                <span class="material-symbols-outlined">
                    notifications
                </span>

                <span>
                    Notifications
                </span>

            </a>


        </nav>



        <div class="sidebar-bottom">


            <a href="#">

                <span class="material-symbols-outlined">
                    settings
                </span>

                <span>
                    Settings
                </span>

            </a>


            <a href="../logout.php">

                <span class="material-symbols-outlined">
                    logout
                </span>

                <span>
                    Logout
                </span>

            </a>


        </div>


    </aside>



    <!-- =====================================================
         MAIN AREA
    ====================================================== -->

    <div class="main-area">


        <!-- =================================================
             TOP BAR
        ================================================== -->

        <header class="topbar">


            <div class="topbar-left">


                <button
                    type="button"
                    class="menu-button"
                    id="menuButton"
                    aria-label="Open navigation"
                >

                    <span class="material-symbols-outlined">
                        menu
                    </span>

                </button>


                <form
                    class="global-search"
                    method="GET"
                    action="caregivers.php"
                >


                    <span class="material-symbols-outlined">
                        search
                    </span>


                    <input
                        type="text"
                        name="search"
                        value="<?= htmlspecialchars($search) ?>"
                        placeholder="Search caregivers by name, email or qualification..."
                    >


                    <input
                        type="hidden"
                        name="status"
                        value="<?= htmlspecialchars($selectedStatus) ?>"
                    >


                    <input
                        type="hidden"
                        name="sort"
                        value="<?= htmlspecialchars($sort) ?>"
                    >


                </form>


            </div>



            <div class="topbar-right">


                <button
                    type="button"
                    class="icon-button"
                    aria-label="Notifications"
                >

                    <span class="material-symbols-outlined">
                        notifications
                    </span>

                </button>


                <div class="admin-profile">


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


                    <div class="admin-info">

                        <strong>
                            <?= htmlspecialchars($adminName) ?>
                        </strong>

                        <span>
                            SYSTEM ADMIN
                        </span>

                    </div>


                </div>


            </div>


        </header>



        <!-- =================================================
             PAGE CONTENT
        ================================================== -->

        <main class="content">


            <!-- PAGE HEADER -->

            <div class="page-header">


                <div>

                    <p class="breadcrumb">

                        Admin
                        <span>/</span>
                        Caregivers

                    </p>


                    <h2>
                        Caregiver Management
                    </h2>


                    <p class="page-description">

                        Review applications, verify caregiver
                        credentials, and manage registered caregivers.

                    </p>

                </div>


                <div class="current-date">

                    <span class="material-symbols-outlined">
                        calendar_today
                    </span>

                    <?= date('d M Y') ?>

                </div>


            </div>



            <!-- =================================================
                 SUMMARY CARDS
            ================================================== -->

            <section class="summary-grid">


                <a
                    href="<?= htmlspecialchars(
                        createFilterUrl(
                            'All',
                            '',
                            $sort
                        )
                    ) ?>"
                    class="summary-card"
                >


                    <div class="summary-icon total">

                        <span class="material-symbols-outlined">
                            groups
                        </span>

                    </div>


                    <div>

                        <span class="summary-label">
                            Total Caregivers
                        </span>

                        <strong>
                            <?= $counts['total'] ?>
                        </strong>

                    </div>


                </a>



                <a
                    href="<?= htmlspecialchars(
                        createFilterUrl(
                            'Pending',
                            '',
                            $sort
                        )
                    ) ?>"
                    class="summary-card"
                >


                    <div class="summary-icon pending">

                        <span class="material-symbols-outlined">
                            pending_actions
                        </span>

                    </div>


                    <div>

                        <span class="summary-label">
                            Pending Review
                        </span>

                        <strong>
                            <?= $counts['pending'] ?>
                        </strong>

                    </div>


                </a>



                <a
                    href="<?= htmlspecialchars(
                        createFilterUrl(
                            'Verified',
                            '',
                            $sort
                        )
                    ) ?>"
                    class="summary-card"
                >


                    <div class="summary-icon verified">

                        <span class="material-symbols-outlined">
                            verified
                        </span>

                    </div>


                    <div>

                        <span class="summary-label">
                            Verified
                        </span>

                        <strong>
                            <?= $counts['verified'] ?>
                        </strong>

                    </div>


                </a>



                <a
                    href="<?= htmlspecialchars(
                        createFilterUrl(
                            'Rejected',
                            '',
                            $sort
                        )
                    ) ?>"
                    class="summary-card"
                >


                    <div class="summary-icon rejected">

                        <span class="material-symbols-outlined">
                            person_cancel
                        </span>

                    </div>


                    <div>

                        <span class="summary-label">
                            Rejected
                        </span>

                        <strong>
                            <?= $counts['rejected'] ?>
                        </strong>

                    </div>


                </a>


            </section>



            <!-- =================================================
                 CAREGIVER PANEL
            ================================================== -->

            <section class="caregiver-panel">


                <!-- FILTER AREA -->

                <div class="filter-area">


                    <div class="status-tabs">


                        <?php

                        foreach (
                            $allowedStatuses
                            as $status
                        ):

                            $activeClass =
                                $selectedStatus === $status
                                    ? 'active'
                                    : '';

                        ?>


                            <a
                                href="<?= htmlspecialchars(
                                    createFilterUrl(
                                        $status,
                                        $search,
                                        $sort
                                    )
                                ) ?>"
                                class="<?= $activeClass ?>"
                            >

                                <?= htmlspecialchars($status) ?>


                                <span>

                                    <?= getStatusCount(
                                        $status,
                                        $counts
                                    ) ?>

                                </span>


                            </a>


                        <?php endforeach; ?>


                    </div>



                    <div class="filter-controls">


                        <form
                            method="GET"
                            action="caregivers.php"
                            class="table-search"
                        >


                            <span class="material-symbols-outlined">
                                search
                            </span>


                            <input
                                type="text"
                                name="search"
                                value="<?= htmlspecialchars($search) ?>"
                                placeholder="Search caregiver..."
                            >


                            <input
                                type="hidden"
                                name="status"
                                value="<?= htmlspecialchars($selectedStatus) ?>"
                            >


                            <input
                                type="hidden"
                                name="sort"
                                value="<?= htmlspecialchars($sort) ?>"
                            >


                        </form>



                        <form
                            method="GET"
                            action="caregivers.php"
                            id="sortForm"
                        >


                            <input
                                type="hidden"
                                name="status"
                                value="<?= htmlspecialchars($selectedStatus) ?>"
                            >


                            <input
                                type="hidden"
                                name="search"
                                value="<?= htmlspecialchars($search) ?>"
                            >


                            <select
                                name="sort"
                                id="sortSelect"
                            >

                                <option
                                    value="newest"
                                    <?= $sort === 'newest'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Newest First
                                </option>


                                <option
                                    value="oldest"
                                    <?= $sort === 'oldest'
                                        ? 'selected'
                                        : '' ?>
                                >
                                    Oldest First
                                </option>


                            </select>


                        </form>


                    </div>


                </div>



                <!-- TABLE HEADER -->

                <div class="panel-heading">


                    <div>

                        <h3>
                            Registered Caregivers
                        </h3>

                        <p>

                            Caregivers who have registered
                            with SafeHands.

                        </p>

                    </div>


                    <span class="result-count">

                        <?= count($caregivers) ?>

                        result<?= count($caregivers) === 1
                            ? ''
                            : 's' ?>

                    </span>


                </div>



                <!-- =================================================
                     CAREGIVER TABLE
                ================================================== -->

                <?php if (!empty($caregivers)): ?>


                    <div class="table-wrapper">


                        <table class="caregiver-table">


                            <thead>

                                <tr>

                                    <th>
                                        Caregiver
                                    </th>

                                    <th>
                                        Caregiver ID
                                    </th>

                                    <th>
                                        Qualification
                                    </th>

                                    <th>
                                        Experience
                                    </th>

                                    <th>
                                        Submitted
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th class="action-heading">
                                        Action
                                    </th>

                                </tr>

                            </thead>



                            <tbody>


                            <?php foreach ($caregivers as $caregiver): ?>


                                <?php

                                $fullName =
                                    trim(
                                        $caregiver['first_name']
                                        . ' '
                                        . $caregiver['last_name']
                                    );


                                $initials =
                                    strtoupper(
                                        substr(
                                            $caregiver['first_name'],
                                            0,
                                            1
                                        )
                                        .
                                        substr(
                                            $caregiver['last_name'],
                                            0,
                                            1
                                        )
                                    );


                                $caregiverId =
                                    (int)
                                    $caregiver['caregiver_id'];


                                $displayCaregiverId =
                                    'CG-'
                                    . str_pad(
                                        (string) $caregiverId,
                                        4,
                                        '0',
                                        STR_PAD_LEFT
                                    );


                                $status =
                                    $caregiver[
                                        'verification_status'
                                    ];


                                $profilePhoto =
                                    trim(
                                        $caregiver[
                                            'profile_photo'
                                        ] ?? ''
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | PROFILE PHOTO PATH
                                |--------------------------------------------------------------------------
                                |
                                | This assumes profile_photo contains a path saved
                                | relative to the SafeHands project root, such as:
                                |
                                | uploads/caregivers/profile/photo.jpg
                                |
                                */

                                $profilePhotoUrl = '';

                                if ($profilePhoto !== '') {

                                    $profilePhotoUrl =
                                        '../'
                                        . ltrim(
                                            $profilePhoto,
                                            '/'
                                        );
                                }


                                if ($status === 'Pending') {

                                    $actionText =
                                        'Review Application';

                                } elseif (
                                    $status === 'Verified'
                                ) {

                                    $actionText =
                                        'View Profile';

                                } else {

                                    $actionText =
                                        'View Details';
                                }


                                $submittedDate =
                                    !empty(
                                        $caregiver[
                                            'created_at'
                                        ]
                                    )
                                        ? date(
                                            'd M Y',
                                            strtotime(
                                                $caregiver[
                                                    'created_at'
                                                ]
                                            )
                                        )
                                        : '—';

                                ?>


                                <tr>


                                    <!-- CAREGIVER -->

                                    <td>


                                        <div class="caregiver-person">


                                            <div class="profile-photo">


                                                <?php
                                                if (
                                                    $profilePhotoUrl !== ''
                                                ):
                                                ?>


                                                    <img
                                                        src="<?= htmlspecialchars(
                                                            $profilePhotoUrl
                                                        ) ?>"
                                                        alt="<?= htmlspecialchars(
                                                            $fullName
                                                        ) ?>"
                                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                    >


                                                    <span
                                                        class="profile-fallback"
                                                        style="display:none;"
                                                    >

                                                        <?= htmlspecialchars(
                                                            $initials
                                                        ) ?>

                                                    </span>


                                                <?php else: ?>


                                                    <span class="profile-fallback">

                                                        <?= htmlspecialchars(
                                                            $initials
                                                        ) ?>

                                                    </span>


                                                <?php endif; ?>


                                            </div>



                                            <div>


                                                <strong>

                                                    <?= htmlspecialchars(
                                                        $fullName
                                                    ) ?>

                                                </strong>


                                                <span>

                                                    <?= htmlspecialchars(
                                                        $caregiver[
                                                            'email'
                                                        ]
                                                    ) ?>

                                                </span>


                                            </div>


                                        </div>


                                    </td>



                                    <!-- CAREGIVER ID -->

                                    <td>

                                        <span class="caregiver-id">

                                            <?= htmlspecialchars(
                                                $displayCaregiverId
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- QUALIFICATION -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $caregiver[
                                                'highest_qualification'
                                            ]
                                            ?: 'Not provided'
                                        ) ?>

                                    </td>



                                    <!-- EXPERIENCE -->

                                    <td>

                                        <?=
                                            (int)
                                            $caregiver[
                                                'years_experience'
                                            ]
                                        ?>

                                        Year<?= (
                                            (int)
                                            $caregiver[
                                                'years_experience'
                                            ]
                                            === 1
                                        )
                                            ? ''
                                            : 's'
                                        ?>

                                    </td>



                                    <!-- SUBMITTED -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $submittedDate
                                        ) ?>

                                    </td>



                                    <!-- STATUS -->

                                    <td>

                                        <span
                                            class="status-badge <?= strtolower(
                                                htmlspecialchars(
                                                    $status
                                                )
                                            ) ?>"
                                        >

                                            <?= htmlspecialchars(
                                                $status
                                            ) ?>

                                        </span>

                                    </td>



                                    <!-- ACTION -->

                                    <td class="action-cell">


                                        <a
                                            href="caregiver-review.php?id=<?= $caregiverId ?>"
                                            class="review-button <?= strtolower(
                                                htmlspecialchars(
                                                    $status
                                                )
                                            ) ?>"
                                        >

                                            <?= htmlspecialchars(
                                                $actionText
                                            ) ?>


                                            <span class="material-symbols-outlined">

                                                arrow_forward

                                            </span>


                                        </a>


                                    </td>


                                </tr>


                            <?php endforeach; ?>


                            </tbody>


                        </table>


                    </div>


                <?php else: ?>


                    <!-- EMPTY STATE -->

                    <div class="empty-state">


                        <div class="empty-icon">

                            <span class="material-symbols-outlined">
                                person_search
                            </span>

                        </div>


                        <h3>
                            No caregivers found
                        </h3>


                        <p>

                            Try changing your search
                            or filter criteria.

                        </p>


                        <?php
                        if (
                            $search !== ''
                            ||
                            $selectedStatus !== 'All'
                        ):
                        ?>


                            <a href="caregivers.php">

                                Clear Filters

                            </a>


                        <?php endif; ?>


                    </div>


                <?php endif; ?>


            </section>


        </main>


    </div>


</div>



<!-- MOBILE OVERLAY -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>



<script src="caregivers.js"></script>


</body>

</html>