<?php

session_start();

require_once __DIR__ . '/../includes/db.php';


/* =========================================================
   ADMIN ACCESS PROTECTION
========================================================= */

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


/* =========================================================
   GET CAREGIVER ID
========================================================= */

$caregiverId =
    isset($_GET['id'])
        ? (int) $_GET['id']
        : 0;


if ($caregiverId <= 0) {

    header(
        "Location: caregivers.php"
    );

    exit;
}


/* =========================================================
   LOAD CAREGIVER
========================================================= */

$sql = "

    SELECT

        cp.caregiver_id,
        cp.user_id,
        cp.gender,
        cp.date_of_birth,
        cp.highest_qualification,
        cp.years_experience,
        cp.certifications,
        cp.languages,
        cp.service_areas,
        cp.daily_rate,
        cp.biography,
        cp.profile_photo,
        cp.verification_status,
        cp.rejection_reason,
        cp.verified_at,
        cp.created_at,
        cp.updated_at,

        u.first_name,
        u.last_name,
        u.nic,
        u.phone,
        u.email,
        u.address,
        u.status AS user_status

    FROM caregiver_profiles cp

    INNER JOIN users u
        ON cp.user_id = u.user_id

    WHERE
        cp.caregiver_id = ?

        AND u.role = 'Caregiver'

    LIMIT 1

";


$stmt =
    $conn->prepare($sql);


if (!$stmt) {

    die(
        "Unable to load caregiver: "
        . htmlspecialchars(
            $conn->error
        )
    );
}


$stmt->bind_param(
    "i",
    $caregiverId
);


$stmt->execute();


$result =
    $stmt->get_result();


$caregiver =
    $result->fetch_assoc();


$stmt->close();


if (!$caregiver) {

    header(
        "Location: caregivers.php"
    );

    exit;
}


/* =========================================================
   LOAD CAREGIVER DOCUMENTS
========================================================= */

$documents = [];


$documentStmt =
    $conn->prepare(
        "
        SELECT

            document_id,
            document_type,
            file_path,
            uploaded_at

        FROM caregiver_documents

        WHERE caregiver_id = ?

        ORDER BY uploaded_at ASC
        "
    );


if ($documentStmt) {

    $documentStmt->bind_param(
        "i",
        $caregiverId
    );


    $documentStmt->execute();


    $documentResult =
        $documentStmt->get_result();


    while (
        $document =
        $documentResult->fetch_assoc()
    ) {

        $documents[] =
            $document;
    }


    $documentStmt->close();
}


/* =========================================================
   FLASH MESSAGE
========================================================= */

$successMessage =
    $_SESSION[
        'caregiver_review_success'
    ] ?? '';


$errorMessage =
    $_SESSION[
        'caregiver_review_error'
    ] ?? '';


unset(
    $_SESSION[
        'caregiver_review_success'
    ],
    $_SESSION[
        'caregiver_review_error'
    ]
);


/* =========================================================
   HANDLE APPROVE / REJECT
========================================================= */

if (
    $_SERVER['REQUEST_METHOD']
    === 'POST'
) {

    /*
    ---------------------------------------------------------
    Only Pending applications can be changed.
    ---------------------------------------------------------
    */

    if (
        $caregiver[
            'verification_status'
        ] !== 'Pending'
    ) {

        $_SESSION[
            'caregiver_review_error'
        ] =
            'This application has already been reviewed.';

        header(
            "Location: caregiver-review.php?id="
            . $caregiverId
        );

        exit;
    }


    $action =
        $_POST['action']
        ?? '';


    /* =====================================================
       APPROVE
    ====================================================== */

    if ($action === 'approve') {

        $updateStmt =
            $conn->prepare(
                "
                UPDATE caregiver_profiles

                SET
                    verification_status = 'Verified',
                    rejection_reason = NULL,
                    verified_at = NOW()

                WHERE
                    caregiver_id = ?

                    AND verification_status = 'Pending'
                "
            );


        if (!$updateStmt) {

            $_SESSION[
                'caregiver_review_error'
            ] =
                'Unable to process the application.';

        } else {

            $updateStmt->bind_param(
                "i",
                $caregiverId
            );


            $updateStmt->execute();


            if (
                $updateStmt->affected_rows > 0
            ) {

                $_SESSION[
                    'caregiver_review_success'
                ] =
                    'Caregiver application approved successfully.';

            } else {

                $_SESSION[
                    'caregiver_review_error'
                ] =
                    'The application could not be approved.';
            }


            $updateStmt->close();
        }


        header(
            "Location: caregiver-review.php?id="
            . $caregiverId
        );

        exit;
    }


    /* =====================================================
       REJECT
    ====================================================== */

    if ($action === 'reject') {

        $rejectionReason =
            trim(
                $_POST[
                    'rejection_reason'
                ] ?? ''
            );


        if ($rejectionReason === '') {

            $_SESSION[
                'caregiver_review_error'
            ] =
                'Please enter a reason before rejecting the application.';


            header(
                "Location: caregiver-review.php?id="
                . $caregiverId
            );

            exit;
        }


        if (
            strlen(
                $rejectionReason
            ) < 10
        ) {

            $_SESSION[
                'caregiver_review_error'
            ] =
                'Please provide a clear rejection reason of at least 10 characters.';


            header(
                "Location: caregiver-review.php?id="
                . $caregiverId
            );

            exit;
        }


        $rejectStmt =
            $conn->prepare(
                "
                UPDATE caregiver_profiles

                SET
                    verification_status = 'Rejected',
                    rejection_reason = ?,
                    verified_at = NULL

                WHERE
                    caregiver_id = ?

                    AND verification_status = 'Pending'
                "
            );


        if (!$rejectStmt) {

            $_SESSION[
                'caregiver_review_error'
            ] =
                'Unable to reject the application.';

        } else {

            $rejectStmt->bind_param(
                "si",
                $rejectionReason,
                $caregiverId
            );


            $rejectStmt->execute();


            if (
                $rejectStmt->affected_rows > 0
            ) {

                $_SESSION[
                    'caregiver_review_success'
                ] =
                    'Caregiver application rejected successfully.';

            } else {

                $_SESSION[
                    'caregiver_review_error'
                ] =
                    'The application could not be rejected.';
            }


            $rejectStmt->close();
        }


        header(
            "Location: caregiver-review.php?id="
            . $caregiverId
        );

        exit;
    }
}


/* =========================================================
   HELPER FUNCTIONS
========================================================= */

function safeValue(
    $value,
    $fallback = 'Not provided'
) {

    $value =
        trim(
            (string) $value
        );


    return $value !== ''
        ? $value
        : $fallback;
}


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


function documentLabel($type)
{

    $labels = [

        'NIC_Front'
            => 'NIC - Front Side',

        'NIC_Back'
            => 'NIC - Back Side',

        'Qualification'
            => 'Qualification Certificate',

        'Police_Clearance'
            => 'Police Clearance Certificate',

        'Police'
            => 'Police Clearance Certificate',

        'Certificate'
            => 'Professional Certificate'

    ];


    if (
        isset(
            $labels[$type]
        )
    ) {

        return $labels[$type];
    }


    return ucwords(
        str_replace(
            '_',
            ' ',
            $type
        )
    );
}


function documentIcon($type)
{

    $type =
        strtolower($type);


    if (
        str_contains(
            $type,
            'nic'
        )
    ) {

        return 'badge';
    }


    if (
        str_contains(
            $type,
            'qualification'
        )
        ||
        str_contains(
            $type,
            'certificate'
        )
    ) {

        return 'workspace_premium';
    }


    if (
        str_contains(
            $type,
            'police'
        )
    ) {

        return 'policy';
    }


    return 'description';
}


/* =========================================================
   PREPARE DISPLAY VALUES
========================================================= */

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


$displayCaregiverId =
    caregiverCode(
        $caregiverId
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


$profilePhotoUrl = '';


if ($profilePhoto !== '') {

    $profilePhotoUrl =
        '../'
        . ltrim(
            $profilePhoto,
            '/'
        );
}


$applicationDate =
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


$verifiedDate =
    !empty(
        $caregiver[
            'verified_at'
        ]
    )
        ? date(
            'd M Y, h:i A',
            strtotime(
                $caregiver[
                    'verified_at'
                ]
            )
        )
        : '—';


$dateOfBirth =
    !empty(
        $caregiver[
            'date_of_birth'
        ]
    )
        ? date(
            'd M Y',
            strtotime(
                $caregiver[
                    'date_of_birth'
                ]
            )
        )
        : 'Not provided';


$adminName =
    trim(
        (
            $_SESSION[
                'first_name'
            ] ?? 'Admin'
        )
        . ' '
        . (
            $_SESSION[
                'last_name'
            ] ?? ''
        )
    );

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
        Caregiver Application Review | SafeHands
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
        href="caregiver-review.css"
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


            <a href="dashboard.php">

                <span class="material-symbols-outlined">
                    dashboard
                </span>

                Dashboard

            </a>


            <a
                href="caregivers.php"
                class="active"
            >

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
                    type="button"
                    class="menu-button"
                    id="menuButton"
                >

                    <span class="material-symbols-outlined">
                        menu
                    </span>

                </button>


                <div class="page-location">

                    <span class="material-symbols-outlined">
                        verified_user
                    </span>

                    Caregiver Verification

                </div>


            </div>



            <div class="topbar-right">


                <button
                    type="button"
                    class="notification-button"
                >

                    <span class="material-symbols-outlined">
                        notifications
                    </span>

                </button>



                <div class="admin-profile">


                    <div class="admin-details">

                        <strong>

                            <?= htmlspecialchars(
                                $adminName
                            ) ?>

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
             PAGE CONTENT
        ================================================== -->

        <main class="content">


            <!-- BACK -->

            <a
                href="caregivers.php"
                class="back-link"
            >

                <span class="material-symbols-outlined">
                    arrow_back
                </span>

                Back to Caregivers

            </a>



            <!-- =================================================
                 PAGE TITLE
            ================================================== -->

            <div class="page-header">


                <div>

                    <p class="breadcrumb">

                        Admin
                        <span>/</span>
                        Caregivers
                        <span>/</span>
                        Application Review

                    </p>


                    <h2>
                        Caregiver Application Review
                    </h2>


                    <p>

                        Review the caregiver's information
                        and verification documents before
                        making a decision.

                    </p>

                </div>


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


            </div>



            <!-- MESSAGES -->

            <?php if ($successMessage !== ''): ?>

                <div class="alert success-alert">

                    <span class="material-symbols-outlined">
                        check_circle
                    </span>

                    <?= htmlspecialchars(
                        $successMessage
                    ) ?>

                </div>

            <?php endif; ?>


            <?php if ($errorMessage !== ''): ?>

                <div class="alert error-alert">

                    <span class="material-symbols-outlined">
                        error
                    </span>

                    <?= htmlspecialchars(
                        $errorMessage
                    ) ?>

                </div>

            <?php endif; ?>



            <!-- =================================================
                 CAREGIVER PROFILE SUMMARY
            ================================================== -->

            <section class="profile-card">


                <div class="profile-main">


                    <div class="large-profile-photo">


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
                                onerror="
                                    this.style.display='none';
                                    this.nextElementSibling.style.display='flex';
                                "
                            >


                            <span
                                class="profile-initials"
                                style="display:none;"
                            >

                                <?= htmlspecialchars(
                                    $initials
                                ) ?>

                            </span>


                        <?php else: ?>


                            <span class="profile-initials">

                                <?= htmlspecialchars(
                                    $initials
                                ) ?>

                            </span>


                        <?php endif; ?>


                    </div>



                    <div class="profile-heading">


                        <div class="profile-name-row">

                            <h3>

                                <?= htmlspecialchars(
                                    $fullName
                                ) ?>

                            </h3>


                            <?php
                            if (
                                $status === 'Verified'
                            ):
                            ?>

                                <span
                                    class="material-symbols-outlined verified-icon"
                                    title="Verified caregiver"
                                >
                                    verified
                                </span>

                            <?php endif; ?>

                        </div>


                        <p>

                            <?= htmlspecialchars(
                                safeValue(
                                    $caregiver[
                                        'highest_qualification'
                                    ]
                                )
                            ) ?>

                        </p>


                        <div class="profile-meta">


                            <span>

                                <span class="material-symbols-outlined">
                                    badge
                                </span>

                                <?= htmlspecialchars(
                                    $displayCaregiverId
                                ) ?>

                            </span>


                            <span>

                                <span class="material-symbols-outlined">
                                    schedule
                                </span>

                                Applied
                                <?= htmlspecialchars(
                                    $applicationDate
                                ) ?>

                            </span>


                        </div>


                    </div>


                </div>



                <div class="profile-summary">


                    <div>

                        <span>
                            Experience
                        </span>

                        <strong>

                            <?= (int)
                                $caregiver[
                                    'years_experience'
                                ]
                            ?>

                            Year<?=

                                (int)
                                $caregiver[
                                    'years_experience'
                                ] === 1

                                ? ''

                                : 's'

                            ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Daily Rate
                        </span>

                        <strong>

                            <?php
                            if (
                                $caregiver[
                                    'daily_rate'
                                ] !== null
                            ):
                            ?>

                                LKR
                                <?= number_format(
                                    (float)
                                    $caregiver[
                                        'daily_rate'
                                    ],
                                    2
                                ) ?>

                            <?php else: ?>

                                Not set

                            <?php endif; ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Documents
                        </span>

                        <strong>

                            <?= count(
                                $documents
                            ) ?>

                            Uploaded

                        </strong>

                    </div>


                </div>


            </section>



            <!-- =================================================
                 TWO COLUMN INFORMATION
            ================================================== -->

            <div class="information-grid">


                <!-- PERSONAL -->

                <section class="info-card">


                    <div class="card-heading">

                        <div class="heading-icon">

                            <span class="material-symbols-outlined">
                                person
                            </span>

                        </div>


                        <div>

                            <h3>
                                Personal Information
                            </h3>

                            <p>
                                Caregiver's personal and contact details.
                            </p>

                        </div>

                    </div>



                    <div class="details-grid">


                        <div class="detail-item">

                            <span>
                                Full Name
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $fullName
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                NIC Number
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'nic'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Gender
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'gender'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Date of Birth
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    $dateOfBirth
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Phone Number
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'phone'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Email Address
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'email'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item full-width">

                            <span>
                                Address
                            </span>

                            <strong>

                                <?= nl2br(
                                    htmlspecialchars(
                                        safeValue(
                                            $caregiver[
                                                'address'
                                            ]
                                        )
                                    )
                                ) ?>

                            </strong>

                        </div>


                    </div>


                </section>



                <!-- PROFESSIONAL -->

                <section class="info-card">


                    <div class="card-heading">

                        <div class="heading-icon">

                            <span class="material-symbols-outlined">
                                medical_information
                            </span>

                        </div>


                        <div>

                            <h3>
                                Professional Information
                            </h3>

                            <p>
                                Qualifications and caregiving experience.
                            </p>

                        </div>

                    </div>



                    <div class="details-grid">


                        <div class="detail-item full-width">

                            <span>
                                Highest Qualification
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'highest_qualification'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Years of Experience
                            </span>

                            <strong>

                                <?= (int)
                                    $caregiver[
                                        'years_experience'
                                    ]
                                ?>

                                Year<?=

                                    (int)
                                    $caregiver[
                                        'years_experience'
                                    ] === 1

                                    ? ''

                                    : 's'

                                ?>

                            </strong>

                        </div>


                        <div class="detail-item">

                            <span>
                                Daily Rate
                            </span>

                            <strong>

                                <?php
                                if (
                                    $caregiver[
                                        'daily_rate'
                                    ] !== null
                                ):
                                ?>

                                    LKR
                                    <?= number_format(
                                        (float)
                                        $caregiver[
                                            'daily_rate'
                                        ],
                                        2
                                    ) ?>

                                <?php else: ?>

                                    Not provided

                                <?php endif; ?>

                            </strong>

                        </div>


                        <div class="detail-item full-width">

                            <span>
                                Certifications
                            </span>

                            <strong>

                                <?= nl2br(
                                    htmlspecialchars(
                                        safeValue(
                                            $caregiver[
                                                'certifications'
                                            ],
                                            'No additional certifications provided'
                                        )
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item full-width">

                            <span>
                                Languages
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'languages'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                        <div class="detail-item full-width">

                            <span>
                                Service Areas
                            </span>

                            <strong>

                                <?= htmlspecialchars(
                                    safeValue(
                                        $caregiver[
                                            'service_areas'
                                        ]
                                    )
                                ) ?>

                            </strong>

                        </div>


                    </div>


                </section>


            </div>



            <!-- =================================================
                 BIOGRAPHY
            ================================================== -->

            <section class="info-card biography-card">


                <div class="card-heading">

                    <div class="heading-icon">

                        <span class="material-symbols-outlined">
                            description
                        </span>

                    </div>


                    <div>

                        <h3>
                            Caregiver Biography
                        </h3>

                        <p>
                            Information provided by the caregiver.
                        </p>

                    </div>

                </div>


                <div class="biography-text">

                    <?= nl2br(
                        htmlspecialchars(
                            safeValue(
                                $caregiver[
                                    'biography'
                                ]
                            )
                        )
                    ) ?>

                </div>


            </section>



            <!-- =================================================
                 DOCUMENTS
            ================================================== -->

            <section class="documents-card">


                <div class="card-heading">

                    <div class="heading-icon">

                        <span class="material-symbols-outlined">
                            folder_open
                        </span>

                    </div>


                    <div>

                        <h3>
                            Verification Documents
                        </h3>

                        <p>
                            Review all documents uploaded by the caregiver.
                        </p>

                    </div>

                </div>



                <?php if (!empty($documents)): ?>


                    <div class="documents-list">


                        <?php
                        foreach (
                            $documents
                            as $document
                        ):
                        ?>


                            <?php

                            $filePath =
                                trim(
                                    $document[
                                        'file_path'
                                    ]
                                );


                            $fileUrl =
                                '../'
                                . ltrim(
                                    $filePath,
                                    '/'
                                );


                            $fileName =
                                basename(
                                    $filePath
                                );


                            $uploadedDate =
                                !empty(
                                    $document[
                                        'uploaded_at'
                                    ]
                                )
                                    ? date(
                                        'd M Y',
                                        strtotime(
                                            $document[
                                                'uploaded_at'
                                            ]
                                        )
                                    )
                                    : '—';

                            ?>


                            <div class="document-row">


                                <div class="document-left">


                                    <div class="document-icon">

                                        <span class="material-symbols-outlined">

                                            <?= htmlspecialchars(
                                                documentIcon(
                                                    $document[
                                                        'document_type'
                                                    ]
                                                )
                                            ) ?>

                                        </span>

                                    </div>


                                    <div>


                                        <strong>

                                            <?= htmlspecialchars(
                                                documentLabel(
                                                    $document[
                                                        'document_type'
                                                    ]
                                                )
                                            ) ?>

                                        </strong>


                                        <span>

                                            <?= htmlspecialchars(
                                                $fileName
                                            ) ?>

                                            · Uploaded

                                            <?= htmlspecialchars(
                                                $uploadedDate
                                            ) ?>

                                        </span>


                                    </div>


                                </div>



                                <a
                                    href="<?= htmlspecialchars(
                                        $fileUrl
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="view-document-button"
                                >

                                    <span class="material-symbols-outlined">
                                        visibility
                                    </span>

                                    View Document

                                </a>


                            </div>


                        <?php endforeach; ?>


                    </div>


                <?php else: ?>


                    <div class="no-documents">


                        <span class="material-symbols-outlined">
                            folder_off
                        </span>


                        <h4>
                            No documents found
                        </h4>


                        <p>

                            This caregiver does not have
                            any uploaded verification documents.

                        </p>


                    </div>


                <?php endif; ?>


            </section>



            <!-- =================================================
                 DECISION AREA
            ================================================== -->

            <section class="decision-card">


                <?php if ($status === 'Pending'): ?>


                    <div class="decision-content">


                        <div>


                            <h3>
                                Ready to make a decision?
                            </h3>


                            <p>

                                Confirm that you have reviewed
                                the caregiver's information and
                                uploaded documents before approving
                                or rejecting this application.

                            </p>


                        </div>



                        <div class="decision-actions">


                            <button
                                type="button"
                                class="reject-button"
                                id="openRejectModal"
                            >

                                <span class="material-symbols-outlined">
                                    close
                                </span>

                                Reject Application

                            </button>



                            <button
                                type="button"
                                class="approve-button"
                                id="openApproveModal"
                            >

                                <span class="material-symbols-outlined">
                                    verified
                                </span>

                                Approve & Verify

                            </button>


                        </div>


                    </div>


                <?php elseif ($status === 'Verified'): ?>


                    <div class="decision-result verified-result">


                        <div class="decision-result-icon">

                            <span class="material-symbols-outlined">
                                verified
                            </span>

                        </div>


                        <div>

                            <h3>
                                Caregiver Verified
                            </h3>


                            <p>

                                This application has been approved.
                                The caregiver now has verified status.

                            </p>


                            <span>

                                Verified on:
                                <?= htmlspecialchars(
                                    $verifiedDate
                                ) ?>

                            </span>

                        </div>


                    </div>


                <?php else: ?>


                    <div class="decision-result rejected-result">


                        <div class="decision-result-icon">

                            <span class="material-symbols-outlined">
                                person_cancel
                            </span>

                        </div>


                        <div>

                            <h3>
                                Application Rejected
                            </h3>


                            <p>

                                This caregiver application
                                has been rejected.

                            </p>


                            <div class="rejection-reason-box">

                                <span>
                                    Rejection Reason
                                </span>

                                <strong>

                                    <?= nl2br(
                                        htmlspecialchars(
                                            safeValue(
                                                $caregiver[
                                                    'rejection_reason'
                                                ],
                                                'No rejection reason recorded.'
                                            )
                                        )
                                    ) ?>

                                </strong>

                            </div>


                        </div>


                    </div>


                <?php endif; ?>


            </section>


        </main>


    </div>


</div>



<!-- =========================================================
     APPROVE MODAL
========================================================= -->

<?php if ($status === 'Pending'): ?>


<div
    class="modal-overlay"
    id="approveModal"
>


    <div class="modal-card">


        <button
            type="button"
            class="modal-close"
            data-close-modal="approveModal"
        >

            <span class="material-symbols-outlined">
                close
            </span>

        </button>


        <div class="modal-icon approve-modal-icon">

            <span class="material-symbols-outlined">
                verified
            </span>

        </div>


        <h3>
            Approve Caregiver?
        </h3>


        <p>

            You are about to verify

            <strong>
                <?= htmlspecialchars(
                    $fullName
                ) ?>
            </strong>.

            After approval, this caregiver will
            receive verified status and can access
            the verified caregiver dashboard.

        </p>


        <form
            method="POST"
            class="modal-actions"
        >


            <input
                type="hidden"
                name="action"
                value="approve"
            >


            <button
                type="button"
                class="cancel-modal-button"
                data-close-modal="approveModal"
            >
                Cancel
            </button>


            <button
                type="submit"
                class="confirm-approve-button"
            >

                <span class="material-symbols-outlined">
                    check
                </span>

                Confirm Approval

            </button>


        </form>


    </div>


</div>



<!-- =========================================================
     REJECT MODAL
========================================================= -->

<div
    class="modal-overlay"
    id="rejectModal"
>


    <div class="modal-card reject-modal-card">


        <button
            type="button"
            class="modal-close"
            data-close-modal="rejectModal"
        >

            <span class="material-symbols-outlined">
                close
            </span>

        </button>


        <div class="modal-icon reject-modal-icon">

            <span class="material-symbols-outlined">
                person_cancel
            </span>

        </div>


        <h3>
            Reject Application
        </h3>


        <p>

            Explain why

            <strong>
                <?= htmlspecialchars(
                    $fullName
                ) ?>
            </strong>

            cannot be verified.

            This reason will be stored with
            the caregiver's application.

        </p>



        <form
            method="POST"
            id="rejectForm"
        >


            <input
                type="hidden"
                name="action"
                value="reject"
            >


            <label
                for="rejectionReason"
                class="textarea-label"
            >

                Reason for rejection

                <span>
                    *
                </span>

            </label>


            <textarea
                name="rejection_reason"
                id="rejectionReason"
                rows="5"
                maxlength="1000"
                placeholder="Example: The uploaded qualification certificate could not be verified."
                required
            ></textarea>


            <div class="textarea-footer">

                <span id="reasonError">

                    Minimum 10 characters required.

                </span>


                <span>

                    <span id="reasonCount">
                        0
                    </span>

                    / 1000

                </span>

            </div>



            <div class="modal-actions">


                <button
                    type="button"
                    class="cancel-modal-button"
                    data-close-modal="rejectModal"
                >
                    Cancel
                </button>


                <button
                    type="submit"
                    class="confirm-reject-button"
                >

                    <span class="material-symbols-outlined">
                        close
                    </span>

                    Reject Application

                </button>


            </div>


        </form>


    </div>


</div>


<?php endif; ?>



<!-- MOBILE OVERLAY -->

<div
    class="sidebar-overlay"
    id="sidebarOverlay"
></div>


<script src="caregiver-review.js"></script>


</body>

</html>