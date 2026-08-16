<?php

session_start();

require_once __DIR__ . '/../includes/db.php';


/*
|--------------------------------------------------------------------------
| LOGIN CHECK
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    strtolower(trim($_SESSION['role'])) !== 'family'
) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];


/*
|--------------------------------------------------------------------------
| HELPER FUNCTION
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}


/*
|--------------------------------------------------------------------------
| GET PATIENT ID
|--------------------------------------------------------------------------
*/

$patient_id = isset($_GET['patient_id'])
    ? (int) $_GET['patient_id']
    : 0;

if ($patient_id <= 0) {
    die("Invalid patient ID.");
}


/*
|--------------------------------------------------------------------------
| GET FAMILY PROFILE
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT family_id
    FROM family_profiles
    WHERE user_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param(
    "i",
    $user_id
);

$stmt->execute();

$result = $stmt->get_result();

$family = $result->fetch_assoc();

$stmt->close();


if (!$family) {
    die("Family profile not found.");
}

$family_id = (int) $family['family_id'];


/*
|--------------------------------------------------------------------------
| GET PATIENT
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We check BOTH patient_id and family_id.
| This prevents one family member from viewing another
| family's patient.
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT *
    FROM patients
    WHERE patient_id = ?
    AND family_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param(
    "ii",
    $patient_id,
    $family_id
);

$stmt->execute();

$result = $stmt->get_result();

$patient = $result->fetch_assoc();

$stmt->close();


if (!$patient) {
    die("Patient not found.");
}


/*
|--------------------------------------------------------------------------
| CALCULATE AGE
|--------------------------------------------------------------------------
*/

$age = '';

if (!empty($patient['date_of_birth'])) {

    try {

        $dob = new DateTime(
            $patient['date_of_birth']
        );

        $today = new DateTime();

        $age = $today->diff($dob)->y;

    } catch (Exception $e) {

        $age = '';

    }
}


/*
|--------------------------------------------------------------------------
| MEDICAL CONDITIONS
|--------------------------------------------------------------------------
*/

$medicalConditions = [];

if (!empty($patient['medical_conditions'])) {

    $medicalConditions = array_filter(
        array_map(
            'trim',
            explode(
                ',',
                $patient['medical_conditions']
            )
        )
    );
}


/*
|--------------------------------------------------------------------------
| PROFILE PHOTO
|--------------------------------------------------------------------------
*/

$profilePhoto = '';

if (!empty($patient['profile_photo'])) {

    $profilePhoto =
        '../' .
        ltrim(
            $patient['profile_photo'],
            '/'
        );
}


/*
|--------------------------------------------------------------------------
| PATIENT DOCUMENTS
|--------------------------------------------------------------------------
*/

$documents = [];

$sql = "
    SELECT
        document_id,
        document_name,
        document_path,
        document_type,
        file_size,
        uploaded_at
    FROM patient_documents
    WHERE patient_id = ?
    ORDER BY uploaded_at DESC
";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param(
        "i",
        $patient_id
    );

    $stmt->execute();

    $result =
        $stmt->get_result();

    while (
        $document =
        $result->fetch_assoc()
    ) {

        $documents[] =
            $document;

    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| DOCUMENT ICON
|--------------------------------------------------------------------------
*/

function getDocumentIcon($type)
{
    $type = strtolower(
        trim((string) $type)
    );

    if ($type === 'pdf') {
        return 'PDF';
    }

    if (
        $type === 'jpg' ||
        $type === 'jpeg' ||
        $type === 'png'
    ) {
        return 'IMG';
    }

    return 'DOC';
}


/*
|--------------------------------------------------------------------------
| FILE SIZE
|--------------------------------------------------------------------------
*/

function formatFileSize($bytes)
{
    $bytes = (int) $bytes;

    if ($bytes <= 0) {
        return 'Unknown size';
    }

    if ($bytes < 1024) {

        return $bytes . ' B';

    }

    if ($bytes < 1024 * 1024) {

        return number_format(
            $bytes / 1024,
            1
        ) . ' KB';

    }

    return number_format(
        $bytes / (1024 * 1024),
        1
    ) . ' MB';
}


/*
|--------------------------------------------------------------------------
| PATIENT STATUS
|--------------------------------------------------------------------------
*/

$status =
    $patient['status'] ?? 'Active';

if (
    strtolower($status) === 'active'
) {

    $statusText =
        'Currently Receiving Care';

} else {

    $statusText =
        'Inactive';

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
        Patient Profile | SafeHands
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/patient-profile.css"
    >

</head>


<body>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="top-nav">

    <div class="nav-container">

        <a
            href="dashboard.php"
            class="logo"
        >
            SafeHands
        </a>


        <nav class="nav-links">

            <a href="dashboard.php">
                Dashboard
            </a>

            <a
                href="patients.php"
                class="active"
            >
                Patients
            </a>

            <a href="../find-caregiver.php">
                Find Caregivers
            </a>

            <a href="my-booking.php">
                My Bookings
            </a>

        </nav>


        <div class="nav-actions">

            <a
                href="notifications.php"
                class="nav-icon"
                title="Notifications"
            >
                ♢
            </a>

            <a
                href="profile.php"
                class="nav-icon"
                title="Account"
            >
                ◉
            </a>

        </div>

    </div>

</header>


<!-- =========================================================
     MAIN
========================================================= -->

<main class="page-container">


    <!-- =====================================================
         BREADCRUMB
    ====================================================== -->

    <nav class="breadcrumb">

        <a href="dashboard.php">
            Dashboard
        </a>

        <span>›</span>

        <a href="patients.php">
            Patients
        </a>

        <span>›</span>

        <strong>
            Patient Profile
        </strong>

    </nav>



    <!-- =====================================================
         PATIENT PROFILE HEADER
    ====================================================== -->

    <section class="profile-header">


        <div class="profile-main">


            <!-- PROFILE PHOTO -->

            <div class="profile-photo">

                <?php if ($profilePhoto !== ''): ?>

                    <img
                        src="<?php echo e($profilePhoto); ?>"
                        alt="Patient profile photo"
                    >

                <?php else: ?>

                    <div class="profile-placeholder">
                        👤
                    </div>

                <?php endif; ?>

            </div>


            <!-- PATIENT NAME -->

            <div class="profile-heading">

                <div class="profile-name-row">

                    <h1>
                        <?php
                        echo e(
                            $patient['full_name']
                        );
                        ?>
                    </h1>


                    <span class="status-badge">

                        <?php
                        echo e($statusText);
                        ?>

                    </span>

                </div>


                <p>

                    Age:
                    <?php
                    echo $age !== ''
                        ? e($age)
                        : 'N/A';
                    ?>

                    <span>•</span>

                    Gender:
                    <?php
                    echo e(
                        $patient['gender']
                    );
                    ?>

                    <span>•</span>

                    Blood Group:
                    <?php
                    echo e(
                        $patient['blood_group']
                        ?: 'N/A'
                    );
                    ?>

                    <span>•</span>

                    Relationship:
                    <?php
                    echo e(
                        $patient['relationship']
                    );
                    ?>

                </p>

            </div>

        </div>


        <!-- HEADER BUTTONS -->

        <div class="profile-actions">

            <button
                type="button"
                class="btn btn-outline"
                onclick="editPatient(<?php echo $patient_id; ?>)"
            >
                Edit Profile
            </button>


            <button
                type="button"
                class="btn btn-primary"
                onclick="viewMedicalHistory(<?php echo $patient_id; ?>)"
            >
                View Medical History
            </button>

        </div>

    </section>



    <!-- =====================================================
         BENTO GRID
    ====================================================== -->

    <div class="content-grid">


        <!-- =================================================
             LEFT COLUMN
        ================================================== -->

        <div class="left-column">


            <!-- ABOUT PATIENT -->

            <section class="card">

                <h2 class="card-title">

                    <span class="title-icon">
                        ♙
                    </span>

                    About Patient

                </h2>


                <div class="about-list">


                    <div class="about-item">

                        <span>
                            Full Name
                        </span>

                        <strong>
                            <?php
                            echo e(
                                $patient['full_name']
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="about-item">

                        <span>
                            Date of Birth
                        </span>

                        <strong>

                            <?php

                            if (
                                !empty(
                                    $patient['date_of_birth']
                                )
                            ) {

                                echo e(
                                    date(
                                        'd M Y',
                                        strtotime(
                                            $patient[
                                                'date_of_birth'
                                            ]
                                        )
                                    )
                                );

                            } else {

                                echo 'Not specified';

                            }

                            ?>

                        </strong>

                    </div>


                    <div class="about-item">

                        <span>
                            NIC Number
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient['nic']
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="about-item">

                        <span>
                            Phone
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient['phone']
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>


                    <div class="about-item">

                        <span>
                            Address
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient['address']
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>

                </div>

            </section>



            <!-- EMERGENCY CONTACT -->

            <section class="card emergency-card">

                <h2 class="card-title emergency-title">

                    <span class="title-icon">
                        !
                    </span>

                    Emergency Contact

                </h2>


                <div class="emergency-list">


                    <div>

                        <span>
                            Name
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient[
                                    'emergency_contact_name'
                                ]
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Relationship
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient[
                                    'emergency_contact_relationship'
                                ]
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Primary Phone
                        </span>

                        <strong class="phone">

                            <?php
                            echo e(
                                $patient[
                                    'emergency_contact_phone'
                                ]
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>


                    <div>

                        <span>
                            Alt Phone
                        </span>

                        <strong>

                            <?php
                            echo e(
                                $patient[
                                    'emergency_alternative_phone'
                                ]
                                ?: 'Not specified'
                            );
                            ?>

                        </strong>

                    </div>

                </div>

            </section>

        </div>



        <!-- =================================================
             RIGHT COLUMN
        ================================================== -->

        <div class="right-column">


            <!-- =================================================
                 VITALS
            ================================================== -->

            <section class="vitals-grid">


                <!-- WEIGHT -->

                <div class="vital-card">

                    <span class="vital-icon">
                        ⚖
                    </span>

                    <span class="vital-label">
                        Weight
                    </span>

                    <strong>

                        <?php
                        echo !empty(
                            $patient['weight']
                        )
                            ? e(
                                $patient['weight']
                            )
                            : 'N/A';
                        ?>

                        <?php
                        if (
                            !empty(
                                $patient['weight']
                            )
                        ):
                        ?>

                            <small>
                                kg
                            </small>

                        <?php endif; ?>

                    </strong>

                </div>


                <!-- BLOOD PRESSURE -->

                <div class="vital-card">

                    <span class="vital-icon danger">
                        ♥
                    </span>

                    <span class="vital-label">
                        BP
                    </span>

                    <strong>

                        <?php
                        echo !empty(
                            $patient[
                                'blood_pressure'
                            ]
                        )
                            ? e(
                                $patient[
                                    'blood_pressure'
                                ]
                            )
                            : 'N/A';
                        ?>

                        <?php
                        if (
                            !empty(
                                $patient[
                                    'blood_pressure'
                                ]
                            )
                        ):
                        ?>

                            <small>
                                mmHg
                            </small>

                        <?php endif; ?>

                    </strong>

                </div>


                <!-- CONDITION -->

                <div class="vital-card">

                    <span class="vital-icon warning">
                        ✚
                    </span>

                    <span class="vital-label">
                        Condition
                    </span>

                    <strong class="condition-text">

                        <?php

                        if (
                            !empty(
                                $medicalConditions
                            )
                        ) {

                            echo e(
                                $medicalConditions[0]
                            );

                        } else {

                            echo 'None';

                        }

                        ?>

                    </strong>

                </div>


                <!-- LAST CHECK -->

                <div class="vital-card">

                    <span class="vital-icon secondary">
                        ◷
                    </span>

                    <span class="vital-label">
                        Last Check
                    </span>

                    <strong>

                        <?php

                        if (
                            !empty(
                                $patient['updated_at']
                            )
                        ) {

                            echo e(
                                date(
                                    'd M Y',
                                    strtotime(
                                        $patient[
                                            'updated_at'
                                        ]
                                    )
                                )
                            );

                        } else {

                            echo 'N/A';

                        }

                        ?>

                    </strong>

                </div>

            </section>



            <!-- =================================================
                 MEDICAL INFORMATION
            ================================================== -->

            <section class="card medical-card">

                <h2 class="card-title">

                    <span class="title-icon">
                        ✚
                    </span>

                    Medical Information

                </h2>


                <div class="medical-grid">


                    <!-- CONDITIONS -->

                    <div>

                        <span class="field-label">
                            Active Conditions
                        </span>


                        <div class="condition-tags">

                            <?php

                            if (
                                !empty(
                                    $medicalConditions
                                )
                            ):

                                foreach (
                                    $medicalConditions
                                    as $condition
                                ):

                            ?>

                                <span class="condition-tag">

                                    <?php
                                    echo e(
                                        $condition
                                    );
                                    ?>

                                </span>

                            <?php

                                endforeach;

                            else:

                            ?>

                                <span class="muted">
                                    No conditions recorded
                                </span>

                            <?php endif; ?>

                        </div>

                    </div>



                    <!-- ALLERGIES -->

                    <div>

                        <span class="field-label allergy-label">
                            Allergies
                        </span>

                        <p class="allergy-text">

                            <?php
                            echo e(
                                $patient['allergies']
                                ?: 'No known allergies'
                            );
                            ?>

                        </p>

                    </div>



                    <!-- MEDICATIONS -->

                    <div>

                        <span class="field-label">
                            Current Medications
                        </span>

                        <p>

                            <?php

                            echo nl2br(
                                e(
                                    $patient[
                                        'current_medications'
                                    ]
                                    ?: 'No medications recorded'
                                )
                            );

                            ?>

                        </p>

                    </div>



                    <!-- MOBILITY -->

                    <div>

                        <span class="field-label">
                            Mobility
                        </span>

                        <p>

                            <?php
                            echo e(
                                $patient[
                                    'mobility_status'
                                ]
                                ?: 'Not specified'
                            );
                            ?>

                        </p>

                    </div>



                    <!-- SPECIAL CARE -->

                    <div class="full-width">

                        <span class="field-label">
                            Special Care Instructions
                        </span>


                        <?php

                        $specialCare =
                            $patient[
                                'special_care_requirements'
                            ] ?? '';


                        if (
                            trim(
                                $specialCare
                            ) !== ''
                        ):

                            $careItems =
                                preg_split(
                                    '/\r\n|\r|\n|,/',
                                    $specialCare
                                );

                        ?>

                            <ul class="care-list">

                                <?php

                                foreach (
                                    $careItems
                                    as $item
                                ):

                                    $item =
                                        trim($item);

                                    if (
                                        $item === ''
                                    ) {
                                        continue;
                                    }

                                ?>

                                    <li>

                                        <?php
                                        echo e(
                                            $item
                                        );
                                        ?>

                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        <?php else: ?>

                            <p class="muted">
                                No special care instructions recorded.
                            </p>

                        <?php endif; ?>

                    </div>

                </div>

            </section>



            <!-- =================================================
                 CARE HISTORY & REPORTS
            ================================================== -->

            <section class="care-history-card">


                <div class="care-history-header">

                    <h2 class="card-title">

                        <span class="title-icon">
                            ◴
                        </span>

                        Care History & Reports

                    </h2>


                    <button
                        type="button"
                        class="text-button"
                        onclick="viewCareHistory(<?php echo $patient_id; ?>)"
                    >
                        View All History
                    </button>

                </div>


                <div class="history-table-wrapper">

                    <table class="history-table">

                        <thead>

                            <tr>

                                <th>
                                    Date
                                </th>

                                <th>
                                    Caregiver
                                </th>

                                <th>
                                    Duration
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <!--
                                Care history will be connected
                                to the booking/care report tables
                                later.
                            -->

                            <tr>

                                <td colspan="5">

                                    <div class="empty-history">

                                        <span>
                                            No care history available yet.
                                        </span>

                                        <small>
                                            Completed caregiver sessions
                                            and reports will appear here.
                                        </small>

                                    </div>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>


                <div class="report-snippet">

                    <span class="field-label">
                        Recent Report Snippet
                    </span>

                    <p>
                        Care reports will appear here
                        after a completed care session.
                    </p>

                </div>

            </section>



            <!-- =================================================
                 IMPORTANT DOCUMENTS
                 DIRECTLY UNDER CARE HISTORY
            ================================================== -->

            <section class="card documents-card">


                <h2 class="card-title">

                    <span class="title-icon">
                        ▣
                    </span>

                    Important Documents

                </h2>


                <div class="documents-list">


                    <?php if (!empty($documents)): ?>


                        <?php foreach (
                            $documents as $document
                        ): ?>


                            <?php

                            $documentPath =
                                '../' .
                                ltrim(
                                    $document[
                                        'document_path'
                                    ],
                                    '/'
                                );


                            $icon =
                                getDocumentIcon(
                                    $document[
                                        'document_type'
                                    ]
                                );

                            ?>


                            <div
                                class="document-card"
                                data-document-card
                            >


                                <div class="document-left">


                                    <div class="document-icon">

                                        <?php
                                        echo e($icon);
                                        ?>

                                    </div>


                                    <div class="document-details">

                                        <strong>

                                            <?php
                                            echo e(
                                                $document[
                                                    'document_name'
                                                ]
                                            );
                                            ?>

                                        </strong>


                                        <span>

                                            Uploaded

                                            <?php
                                            echo e(
                                                date(
                                                    'd M Y',
                                                    strtotime(
                                                        $document[
                                                            'uploaded_at'
                                                        ]
                                                    )
                                                )
                                            );
                                            ?>

                                            •

                                            <?php
                                            echo e(
                                                formatFileSize(
                                                    $document[
                                                        'file_size'
                                                    ]
                                                )
                                            );
                                            ?>

                                        </span>

                                    </div>

                                </div>


                                <div class="document-actions">


                                    <a
                                        href="<?php echo e($documentPath); ?>"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="document-action"
                                    >
                                        View
                                    </a>


                                    <a
                                        href="<?php echo e($documentPath); ?>"
                                        download
                                        class="document-action"
                                    >
                                        Download
                                    </a>

                                </div>

                            </div>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <div class="empty-documents">


                            <div class="empty-document-icon">
                                ▣
                            </div>


                            <strong>
                                No medical documents yet
                            </strong>


                            <p>
                                Upload prescriptions,
                                laboratory reports,
                                medical reports
                                or other important
                                documents.
                            </p>


                        </div>


                    <?php endif; ?>


                </div>



                <!-- =================================================
                     ADD MORE DOCUMENTS
                ================================================== -->

                <form
                    action="add-patient-documents.php"
                    method="POST"
                    enctype="multipart/form-data"
                    id="additionalDocumentsForm"
                >

                    <input
                        type="hidden"
                        name="patient_id"
                        value="<?php echo (int) $patient_id; ?>"
                    >

                    <input
                        type="file"
                        name="medical_documents[]"
                        id="additionalDocumentsInput"
                        accept=".pdf,.jpg,.jpeg,.png"
                        multiple
                        hidden
                    >

                    <label
                        for="additionalDocumentsInput"
                        class="upload-document-button"
                        id="uploadDocumentButton"
                    >
                        <span>+</span>
                        Upload New Document
                    </label>

                    <div
                        id="selectedDocuments"
                        class="selected-documents"
                    ></div>

                    <button
                        type="submit"
                        id="submitDocumentsButton"
                        class="upload-document-submit"
                        hidden
                    >
                        Upload Selected Documents
                    </button>

                </form>

            </section>

        </div>

    </div>



    <!-- =====================================================
         NEED TO SCHEDULE MORE CARE
         
         KEEPING THIS SECTION
         EXACTLY AT THE BOTTOM
    ====================================================== -->

    <section class="bottom-actions">


        <div>

            <h2>
                Need to schedule more care?
            </h2>


            <p>

                Ensure
                <?php
                echo e(
                    $patient['full_name']
                );
                ?>
                receives continuous professional attention.

            </p>

        </div>


        <div class="bottom-buttons">


            <!-- BOOK CAREGIVER -->

            <button
                type="button"
                class="btn btn-primary"
                onclick="window.location.href='../find-caregiver.php?patient_id=<?php echo $patient_id; ?>'"
            >
                Book Caregiver
            </button>


            <!-- EDIT PATIENT -->

            <button
                type="button"
                class="btn btn-outline"
                onclick="editPatient(<?php echo $patient_id; ?>)"
            >
                Edit Patient Profile
            </button>


            <!-- DELETE PATIENT -->

            <form
    method="POST"
    action="delete-patient.php"
    onsubmit="return confirm(
        'Are you sure you want to permanently delete this patient? This will also delete their medical documents and profile photo.'
    );"
    style="display:inline;"
>

    <input
        type="hidden"
        name="patient_id"
        value="<?php echo (int) $patient_id; ?>"
    >

    <button
        type="submit"
        class="btn btn-danger"
    >
        Delete Patient
    </button>

</form>

        </div>

    </section>

</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">


    <div class="footer-inner">


        <div>

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
                Terms of Service
            </a>

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Escrow Terms
            </a>

            <a href="#">
                Contact Support
            </a>

        </div>

    </div>

</footer>



<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('additionalDocumentsInput');
    const selected = document.getElementById('selectedDocuments');
    const form = document.getElementById('additionalDocumentsForm');
    const submit = document.getElementById('submitDocumentsButton');

    if (!input || !selected || !form || !submit) {
        return;
    }

    input.addEventListener('change', function () {

        selected.innerHTML = '';

        if (!input.files || input.files.length === 0) {
            submit.hidden = true;
            return;
        }

        const list = document.createElement('div');
        list.className = 'selected-document-list';

        Array.from(input.files).forEach(function (file) {

            const item = document.createElement('div');
            item.className = 'selected-document-item';

            item.textContent =
                file.name +
                ' (' +
                (file.size / 1024 / 1024).toFixed(2) +
                ' MB)';

            list.appendChild(item);
        });

        selected.appendChild(list);

        submit.hidden = false;
    });

    form.addEventListener('submit', function () {

        submit.disabled = true;
        submit.textContent = 'Uploading...';

    });

});
</script>

<script
    src="../assets/js/patient-profile.js"
></script>


</body>

</html>
