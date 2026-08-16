<?php

session_start();

require_once __DIR__ . '/../includes/db.php';

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
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
| HELPER
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
    : (int) ($_POST['patient_id'] ?? 0);

if ($patient_id <= 0) {
    die("Invalid patient ID.");
}


/*
|--------------------------------------------------------------------------
| GET FAMILY ID
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

$stmt->bind_param("i", $user_id);
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
| VARIABLES
|--------------------------------------------------------------------------
*/

$error_message = "";

$document_deleted =
    isset($_GET['document_deleted']) &&
    $_GET['document_deleted'] === '1';


/*
|--------------------------------------------------------------------------
| UPDATE PATIENT
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | DELETE MEDICAL DOCUMENT
    |--------------------------------------------------------------------------
    | The Delete button uses the existing main form. No nested form is used.
    */

    $delete_document_id = (int) ($_POST['delete_document_id'] ?? 0);

    if ($delete_document_id > 0) {

        $deleteSql = "
            SELECT document_path
            FROM patient_documents
            WHERE document_id = ?
              AND patient_id = ?
            LIMIT 1
        ";

        $deleteStmt = $conn->prepare($deleteSql);

        if (!$deleteStmt) {
            die("Database error while finding document: " . $conn->error);
        }

        $deleteStmt->bind_param("ii", $delete_document_id, $patient_id);
        $deleteStmt->execute();

        $deleteResult = $deleteStmt->get_result();
        $documentToDelete = $deleteResult->fetch_assoc();
        $deleteStmt->close();

        if (!$documentToDelete) {
            die("Document not found.");
        }

        $deleteSql = "
            DELETE FROM patient_documents
            WHERE document_id = ?
              AND patient_id = ?
        ";

        $deleteStmt = $conn->prepare($deleteSql);

        if (!$deleteStmt) {
            die("Database error while deleting document: " . $conn->error);
        }

        $deleteStmt->bind_param("ii", $delete_document_id, $patient_id);

        if (!$deleteStmt->execute()) {
            $deleteError = $deleteStmt->error;
            $deleteStmt->close();
            die("Unable to delete document: " . $deleteError);
        }

        $deletedRows = $deleteStmt->affected_rows;
        $deleteStmt->close();

        if ($deletedRows !== 1) {
            die("Document was not deleted from the database.");
        }

        $physicalPath =
            __DIR__ .
            '/../' .
            ltrim($documentToDelete['document_path'], '/');

        if (is_file($physicalPath)) {
            @unlink($physicalPath);
        }

        $conn->close();

        header(
            "Location: patient-edit.php?patient_id=" .
            $patient_id .
            "&document_deleted=1"
        );
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | NORMAL PATIENT UPDATE
    |--------------------------------------------------------------------------
    */

    $full_name = trim($_POST['full_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $blood_group = trim($_POST['blood_group'] ?? '');
    $nic = trim($_POST['nic'] ?? '');
    $relationship = trim($_POST['relationship'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    $medical_conditions = trim(
        $_POST['medical_conditions'] ?? ''
    );

    $allergies = trim(
        $_POST['allergies'] ?? ''
    );

    $mobility_status = trim(
        $_POST['mobility_status'] ?? ''
    );

    $weight = trim(
        $_POST['weight'] ?? ''
    );

    $blood_pressure = trim(
        $_POST['blood_pressure'] ?? ''
    );

    $current_medications = trim(
        $_POST['current_medications'] ?? ''
    );

    $special_care_requirements = trim(
        $_POST['special_care_requirements'] ?? ''
    );

    $dietary_restrictions = trim(
        $_POST['dietary_restrictions'] ?? ''
    );

    $doctors_notes = trim(
        $_POST['doctors_notes'] ?? ''
    );

    $emergency_contact_name = trim(
        $_POST['emergency_contact_name'] ?? ''
    );

    $emergency_contact_relationship = trim(
        $_POST['emergency_contact_relationship'] ?? ''
    );

    $emergency_contact_phone = trim(
        $_POST['emergency_contact_phone'] ?? ''
    );

    $emergency_alternative_phone = trim(
        $_POST['emergency_alternative_phone'] ?? ''
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $full_name === '' ||
        $date_of_birth === '' ||
        $gender === '' ||
        $relationship === ''
    ) {
        $error_message =
            "Please fill in all required fields.";
    }


    $allowed_genders = [
        'Male',
        'Female',
        'Other'
    ];

    if (
        $error_message === '' &&
        !in_array(
            $gender,
            $allowed_genders,
            true
        )
    ) {
        $error_message =
            "Invalid gender selected.";
    }


    $allowed_blood_groups = [
        'A+',
        'A-',
        'B+',
        'B-',
        'O+',
        'O-',
        'AB+',
        'AB-'
    ];

    if (
        $error_message === '' &&
        $blood_group !== '' &&
        !in_array(
            $blood_group,
            $allowed_blood_groups,
            true
        )
    ) {
        $error_message =
            "Invalid blood group.";
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    if ($error_message === '') {

        $sql = "
            UPDATE patients
            SET
                full_name = ?,
                date_of_birth = ?,
                gender = ?,
                blood_group = ?,
                nic = ?,
                relationship = ?,
                phone = ?,
                address = ?,
                medical_conditions = ?,
                allergies = ?,
                mobility_status = ?,
                weight = ?,
                blood_pressure = ?,
                current_medications = ?,
                special_care_requirements = ?,
                dietary_restrictions = ?,
                doctors_notes = ?,
                emergency_contact_name = ?,
                emergency_contact_relationship = ?,
                emergency_contact_phone = ?,
                emergency_alternative_phone = ?
            WHERE patient_id = ?
            AND family_id = ?
        ";

        $stmt = $conn->prepare($sql);

        if (!$stmt) {

            $error_message =
                "Unable to prepare update: " .
                $conn->error;

        } else {

            /*
             * 21 string values
             * 2 integer values
             */
            $stmt->bind_param(
                "sssssssssssssssssssssii",
                $full_name,
                $date_of_birth,
                $gender,
                $blood_group,
                $nic,
                $relationship,
                $phone,
                $address,
                $medical_conditions,
                $allergies,
                $mobility_status,
                $weight,
                $blood_pressure,
                $current_medications,
                $special_care_requirements,
                $dietary_restrictions,
                $doctors_notes,
                $emergency_contact_name,
                $emergency_contact_relationship,
                $emergency_contact_phone,
                $emergency_alternative_phone,
                $patient_id,
                $family_id
            );

            if (!$stmt->execute()) {

                $error_message =
                    "Unable to update patient: " .
                    $stmt->error;

            }

            $stmt->close();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | PROFILE PHOTO
    |--------------------------------------------------------------------------
    */

    if (
        $error_message === '' &&
        isset($_FILES['profile_photo']) &&
        $_FILES['profile_photo']['error']
            === UPLOAD_ERR_OK
    ) {

        $photoType = mime_content_type(
            $_FILES['profile_photo']['tmp_name']
        );

        $allowedPhotoTypes = [
            'image/jpeg',
            'image/png'
        ];

        if (
            !in_array(
                $photoType,
                $allowedPhotoTypes,
                true
            )
        ) {

            $error_message =
                "Profile photo must be JPG or PNG.";

        } elseif (
            $_FILES['profile_photo']['size']
            > 5 * 1024 * 1024
        ) {

            $error_message =
                "Profile photo must be smaller than 5 MB.";

        } else {

            $uploadDir =
                __DIR__ .
                '/../uploads/patient_profiles/';

            if (!is_dir($uploadDir)) {

                mkdir(
                    $uploadDir,
                    0755,
                    true
                );
            }

            $extension =
                strtolower(
                    pathinfo(
                        $_FILES['profile_photo']['name'],
                        PATHINFO_EXTENSION
                    )
                );

            $fileName =
                'patient_' .
                $patient_id .
                '_' .
                time() .
                '.' .
                $extension;

            $destination =
                $uploadDir .
                $fileName;

            if (
                move_uploaded_file(
                    $_FILES['profile_photo']['tmp_name'],
                    $destination
                )
            ) {

                $photoPath =
                    'uploads/patient_profiles/' .
                    $fileName;

                $photoSQL = "
                    UPDATE patients
                    SET profile_photo = ?
                    WHERE patient_id = ?
                    AND family_id = ?
                ";

                $photoStmt =
                    $conn->prepare($photoSQL);

                if ($photoStmt) {

                    $photoStmt->bind_param(
                        "sii",
                        $photoPath,
                        $patient_id,
                        $family_id
                    );

                    $photoStmt->execute();

                    $photoStmt->close();
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPLOAD NEW DOCUMENTS
    |--------------------------------------------------------------------------
    */

    if (
        $error_message === '' &&
        isset($_FILES['medical_documents'])
    ) {

        $files =
            $_FILES['medical_documents'];

        $uploadDir =
            __DIR__ .
            '/../uploads/patient_documents/';

        if (!is_dir($uploadDir)) {

            mkdir(
                $uploadDir,
                0755,
                true
            );
        }


        $allowedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png'
        ];


        for (
            $i = 0;
            $i < count($files['name']);
            $i++
        ) {

            if (
                $files['error'][$i]
                !== UPLOAD_ERR_OK
            ) {
                continue;
            }


            $tmpName =
                $files['tmp_name'][$i];

            $originalName =
                $files['name'][$i];

            $fileSize =
                (int) $files['size'][$i];

            $mimeType =
                mime_content_type($tmpName);


            if (
                !in_array(
                    $mimeType,
                    $allowedTypes,
                    true
                )
            ) {
                continue;
            }


            if (
                $fileSize >
                10 * 1024 * 1024
            ) {
                continue;
            }


            $extension =
                strtolower(
                    pathinfo(
                        $originalName,
                        PATHINFO_EXTENSION
                    )
                );


            $fileName =
                'patient_' .
                $patient_id .
                '_' .
                time() .
                '_' .
                $i .
                '.' .
                $extension;


            $destination =
                $uploadDir .
                $fileName;


            if (
                move_uploaded_file(
                    $tmpName,
                    $destination
                )
            ) {

                $documentPath =
                    'uploads/patient_documents/' .
                    $fileName;


                if (
                    $mimeType ===
                    'application/pdf'
                ) {

                    $documentType = 'pdf';

                } elseif (
                    $mimeType ===
                    'image/png'
                ) {

                    $documentType = 'png';

                } else {

                    $documentType = 'jpg';
                }


                $documentSQL = "
                    INSERT INTO patient_documents
                    (
                        patient_id,
                        document_name,
                        document_path,
                        document_type,
                        file_size
                    )
                    VALUES (?, ?, ?, ?, ?)
                ";


                $documentStmt =
                    $conn->prepare(
                        $documentSQL
                    );


                if ($documentStmt) {

                    $documentStmt->bind_param(
                        "isssi",
                        $patient_id,
                        $originalName,
                        $documentPath,
                        $documentType,
                        $fileSize
                    );

                    $documentStmt->execute();

                    $documentStmt->close();
                }
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | REDIRECT TO UPDATED PROFILE
    |--------------------------------------------------------------------------
    */

    if ($error_message === '') {

        $conn->close();

        header(
            "Location: patient-profile.php?patient_id=" .
            $patient_id .
            "&updated=1"
        );

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| GET PATIENT
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

$result =
    $stmt->get_result();

$patient =
    $result->fetch_assoc();

$stmt->close();


if (!$patient) {

    $conn->close();

    die("Patient not found.");
}


/*
|--------------------------------------------------------------------------
| GET DOCUMENTS
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
        $row =
        $result->fetch_assoc()
    ) {

        $documents[] = $row;
    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| PROFILE PHOTO
|--------------------------------------------------------------------------
*/

$profilePhoto = '';

if (
    !empty(
        $patient['profile_photo']
    )
) {

    $profilePhoto =
        '../' .
        ltrim(
            $patient['profile_photo'],
            '/'
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
        Edit Patient Profile | SafeHands
    </title>

    <link
        rel="stylesheet"
        href="../assets/css/patient-edit.css"
    >

</head>


<body>

<!-- SUCCESS MESSAGE -->

<div
    class="success-notification"
    id="success-notification"
>

    <span class="success-icon">
        ✓
    </span>

    <span>
        Patient profile updated successfully.
        Redirecting...
    </span>

</div>


<!-- NAVIGATION -->

<nav class="navbar">

    <div class="nav-left">

        <a
            href="dashboard.php"
            class="logo"
        >
            SafeHands
        </a>

        <div class="nav-links">

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

        </div>

    </div>

    <a
        href="notifications.php"
        class="notification-button"
    >
        🔔
    </a>

</nav>


<!-- MAIN -->

<main class="main-container">


    <!-- BREADCRUMB -->

    <div class="breadcrumb">

        <a href="patients.php">
            Patients
        </a>

        <span>›</span>

        <a
            href="patient-profile.php?patient_id=<?php echo $patient_id; ?>"
        >
            <?php echo e($patient['full_name']); ?>
        </a>

        <span>›</span>

        <strong>
            Edit Profile
        </strong>

    </div>


    <h1 class="page-title">
        Edit Patient Profile
    </h1>


    <?php if ($error_message !== ''): ?>

        <div class="error-message">
            <?php echo e($error_message); ?>
        </div>

    <?php endif; ?>


    <div class="edit-layout">


        <!-- ==================================================
             LEFT PATIENT PREVIEW
        =================================================== -->

        <aside class="patient-sidebar">

            <div class="patient-preview-card">


                <div class="profile-photo-wrapper">

                    <?php if ($profilePhoto !== ''): ?>

                        <img
                            src="<?php echo e($profilePhoto); ?>"
                            class="patient-photo"
                            id="profile-preview"
                            alt="Patient photo"
                        >

                    <?php else: ?>

                        <div
                            class="patient-photo placeholder-photo"
                            id="profile-placeholder"
                        >
                            👤
                        </div>

                    <?php endif; ?>


                    <label
                        for="profile_photo"
                        class="photo-edit-button"
                    >
                        ✎
                    </label>

                </div>


                <h2>
                    <?php echo e($patient['full_name']); ?>
                </h2>


                <p class="relationship">
                    <?php echo e($patient['relationship']); ?>
                </p>


                <div class="status-badge">

                    <span></span>

                    <?php
                    echo $patient['status'] === 'Active'
                        ? 'Active Patient'
                        : 'Inactive Patient';
                    ?>

                </div>


                <label
                    for="profile_photo"
                    class="change-photo-button"
                >
                    📷 &nbsp; Change Photo
                </label>


                <input
                    type="file"
                    id="profile_photo"
                    name="profile_photo"
                    accept=".jpg,.jpeg,.png"
                    form="edit-profile-form"
                    hidden
                >


                <div class="profile-stats">

                    <h3>
                        PROFILE STATS
                    </h3>


                    <div class="stat-row">

                        <span>
                            Last Updated
                        </span>

                        <strong>
                            <?php
                            echo date(
                                'd M Y',
                                strtotime(
                                    $patient['updated_at']
                                )
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="stat-row">

                        <span>
                            Blood Group
                        </span>

                        <strong>
                            <?php
                            echo e(
                                $patient['blood_group'] ?: '-'
                            );
                            ?>
                        </strong>

                    </div>


                    <div class="stat-row">

                        <span>
                            Weight
                        </span>

                        <strong>
                            <?php
                            echo e(
                                $patient['weight'] ?: '-'
                            );
                            ?>
                            <?php
                            if (
                                $patient['weight'] !== null &&
                                $patient['weight'] !== ''
                            ) {
                                echo ' kg';
                            }
                            ?>
                        </strong>

                    </div>

                </div>

            </div>

        </aside>



        <!-- ==================================================
             RIGHT FORM
        =================================================== -->

        <section class="form-wrapper">

            <form
                method="POST"
                action="patient-edit.php?patient_id=<?php echo $patient_id; ?>"
                enctype="multipart/form-data"
                id="edit-profile-form"
            >

                <input
                    type="hidden"
                    name="patient_id"
                    value="<?php echo $patient_id; ?>"
                >


                <!-- ==========================================
                     PERSONAL INFORMATION
                =========================================== -->

                <section class="form-section">

                    <div class="section-heading">

                        <span class="section-icon">
                            👤
                        </span>

                        <h3>
                            Personal Information
                        </h3>

                    </div>


                    <div class="form-grid">


                        <div class="form-field">

                            <label>
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="full_name"
                                value="<?php echo e($patient['full_name']); ?>"
                                required
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                name="date_of_birth"
                                value="<?php echo e($patient['date_of_birth']); ?>"
                                required
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Gender
                            </label>

                            <select
                                name="gender"
                                required
                            >

                                <option
                                    value="Male"
                                    <?php
                                    echo $patient['gender'] === 'Male'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Male
                                </option>

                                <option
                                    value="Female"
                                    <?php
                                    echo $patient['gender'] === 'Female'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Female
                                </option>

                                <option
                                    value="Other"
                                    <?php
                                    echo $patient['gender'] === 'Other'
                                        ? 'selected'
                                        : '';
                                    ?>
                                >
                                    Other
                                </option>

                            </select>

                        </div>


                        <div class="form-field">

                            <label>
                                Blood Group
                            </label>

                            <select name="blood_group">

                                <option value="">
                                    Select
                                </option>

                                <?php

                                $bloodGroups = [
                                    'A+',
                                    'A-',
                                    'B+',
                                    'B-',
                                    'O+',
                                    'O-',
                                    'AB+',
                                    'AB-'
                                ];

                                foreach (
                                    $bloodGroups
                                    as $group
                                ):

                                ?>

                                    <option
                                        value="<?php echo e($group); ?>"
                                        <?php
                                        echo $patient['blood_group'] === $group
                                            ? 'selected'
                                            : '';
                                        ?>
                                    >
                                        <?php echo e($group); ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="form-field">

                            <label>
                                NIC Number
                            </label>

                            <input
                                type="text"
                                name="nic"
                                value="<?php echo e($patient['nic']); ?>"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Relationship
                            </label>

                            <input
                                type="text"
                                name="relationship"
                                value="<?php echo e($patient['relationship']); ?>"
                                required
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Phone Number
                            </label>

                            <input
                                type="tel"
                                name="phone"
                                value="<?php echo e($patient['phone']); ?>"
                            >

                        </div>


                        <div class="form-field full">

                            <label>
                                Home Address
                            </label>

                            <textarea
                                name="address"
                                rows="3"
                            ><?php echo e($patient['address']); ?></textarea>

                        </div>

                    </div>

                </section>



                <!-- ==========================================
                     VITALS
                =========================================== -->

                <section class="form-section">

                    <div class="section-heading">

                        <span class="section-icon">
                            ♥
                        </span>

                        <h3>
                            Vitals
                        </h3>

                    </div>


                    <div class="form-grid">

                        <div class="form-field">

                            <label>
                                Weight (kg)
                            </label>

                            <input
                                type="number"
                                step="0.1"
                                min="0"
                                name="weight"
                                value="<?php echo e($patient['weight']); ?>"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Blood Pressure
                            </label>

                            <input
                                type="text"
                                name="blood_pressure"
                                value="<?php echo e($patient['blood_pressure']); ?>"
                                placeholder="e.g. 130/85"
                            >

                        </div>

                    </div>

                </section>



                <!-- ==========================================
                     MEDICAL INFORMATION
                =========================================== -->

                <section class="form-section">

                    <div class="section-heading">

                        <span class="section-icon">
                            ✚
                        </span>

                        <h3>
                            Medical Information
                        </h3>

                    </div>


                    <div class="form-field">

                        <label>
                            Primary Medical Conditions
                        </label>


                        <div
                            class="condition-box"
                            id="condition-box"
                        >

                            <?php

                            $conditions = [];

                            if (
                                !empty(
                                    $patient[
                                        'medical_conditions'
                                    ]
                                )
                            ) {

                                $conditions =
                                    array_filter(
                                        array_map(
                                            'trim',
                                            explode(
                                                ',',
                                                $patient[
                                                    'medical_conditions'
                                                ]
                                            )
                                        )
                                    );
                            }

                            foreach (
                                $conditions
                                as $condition
                            ):

                            ?>

                                <span class="condition-tag">

                                    <?php echo e($condition); ?>

                                    <button
                                        type="button"
                                        class="remove-condition"
                                    >
                                        ×
                                    </button>

                                </span>

                            <?php endforeach; ?>


                            <input
                                type="text"
                                id="condition-input"
                                placeholder="Type condition and press Enter"
                            >

                        </div>


                        <input
                            type="hidden"
                            name="medical_conditions"
                            id="medical_conditions"
                            value="<?php echo e($patient['medical_conditions']); ?>"
                        >

                        <small>
                            Press Enter to add another condition.
                        </small>

                    </div>


                    <div class="form-grid">


                        <div class="form-field">

                            <label>
                                Allergies
                            </label>

                            <input
                                type="text"
                                name="allergies"
                                value="<?php echo e($patient['allergies']); ?>"
                                placeholder="e.g. None"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Mobility Status
                            </label>

                            <select name="mobility_status">

                                <option value="">
                                    Select
                                </option>

                                <?php

                                $mobilityOptions = [
                                    'Independent',
                                    'Walking Assistance',
                                    'Wheelchair User',
                                    'Wheelchair Bound',
                                    'Bedridden'
                                ];

                                foreach (
                                    $mobilityOptions
                                    as $mobility
                                ):

                                ?>

                                    <option
                                        value="<?php echo e($mobility); ?>"
                                        <?php
                                        echo $patient['mobility_status'] === $mobility
                                            ? 'selected'
                                            : '';
                                        ?>
                                    >
                                        <?php echo e($mobility); ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="form-field full">

                            <label>
                                Current Medications
                            </label>

                            <textarea
                                name="current_medications"
                                rows="3"
                            ><?php echo e($patient['current_medications']); ?></textarea>

                        </div>


                        <div class="form-field">

                            <label>
                                Special Care Requirements
                            </label>

                            <textarea
                                name="special_care_requirements"
                                rows="3"
                            ><?php echo e($patient['special_care_requirements']); ?></textarea>

                        </div>


                        <div class="form-field">

                            <label>
                                Dietary Restrictions
                            </label>

                            <textarea
                                name="dietary_restrictions"
                                rows="3"
                            ><?php echo e($patient['dietary_restrictions']); ?></textarea>

                        </div>


                        <div class="form-field full">

                            <label>
                                Doctor's Notes
                            </label>

                            <textarea
                                name="doctors_notes"
                                rows="3"
                            ><?php echo e($patient['doctors_notes']); ?></textarea>

                        </div>

                    </div>

                </section>



                <!-- ==========================================
                     EMERGENCY CONTACT
                =========================================== -->

                <section class="form-section">

                    <div class="section-heading">

                        <span class="section-icon">
                            🚨
                        </span>

                        <h3>
                            Emergency Contact
                        </h3>

                    </div>


                    <div class="form-grid">


                        <div class="form-field">

                            <label>
                                Contact Name
                            </label>

                            <input
                                type="text"
                                name="emergency_contact_name"
                                value="<?php echo e($patient['emergency_contact_name']); ?>"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Relationship
                            </label>

                            <input
                                type="text"
                                name="emergency_contact_relationship"
                                value="<?php echo e($patient['emergency_contact_relationship']); ?>"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Phone Number
                            </label>

                            <input
                                type="tel"
                                name="emergency_contact_phone"
                                value="<?php echo e($patient['emergency_contact_phone']); ?>"
                            >

                        </div>


                        <div class="form-field">

                            <label>
                                Alternative Phone
                            </label>

                            <input
                                type="tel"
                                name="emergency_alternative_phone"
                                value="<?php echo e($patient['emergency_alternative_phone']); ?>"
                            >

                        </div>

                    </div>

                </section>


<!-- ==========================================
     MEDICAL DOCUMENTS
========================================== -->

<section class="form-section">

    <div class="documents-heading">

        <div class="section-heading no-margin">

            <span class="section-icon">
                📄
            </span>

            <h3>
                Medical Documents
            </h3>

        </div>

        <label
            for="medical_documents"
            class="upload-new"
        >
            + Upload New
        </label>

    </div>


    <!-- ==========================================
         EXISTING DOCUMENTS
    =========================================== -->

    <div class="documents-list">

        <?php if (!empty($documents)): ?>

            <?php foreach ($documents as $document): ?>

                <div class="document-card">


                    <!-- DOCUMENT INFORMATION -->

                    <div class="document-left">

                        <div class="document-icon">
                            📄
                        </div>


                        <div>

                            <strong>
                                <?php
                                echo e(
                                    $document['document_name']
                                );
                                ?>
                            </strong>


                            <span>

                                Uploaded on

                                <?php
                                echo date(
                                    'd M Y',
                                    strtotime(
                                        $document['uploaded_at']
                                    )
                                );
                                ?>

                                •

                                <?php
                                echo number_format(
                                    $document['file_size'] / 1024 / 1024,
                                    2
                                );
                                ?>

                                MB

                            </span>

                        </div>

                    </div>


                    <!-- ==================================
                         DOCUMENT ACTIONS
                    =================================== -->

                    <div class="document-actions">


                        <!-- VIEW -->

                        <a
                            href="../<?php echo e($document['document_path']); ?>"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="document-view"
                        >
                            View
                        </a>


                        <!-- DELETE -->

                        <button
                            type="submit"
                            name="delete_document_id"
                            value="<?php echo (int) $document['document_id']; ?>"
                            class="document-delete"
                            onclick="return confirm('Are you sure you want to delete this medical document?');"
                        >
                            Delete
                        </button>


                    </div>


                </div>


            <?php endforeach; ?>


        <?php else: ?>


            <!-- NO DOCUMENTS -->

            <div class="no-documents">

                <div class="document-icon">
                    📄
                </div>

                <p>
                    No medical documents uploaded yet.
                </p>

            </div>


        <?php endif; ?>

    </div>


    <!-- ==========================================
         UPLOAD NEW DOCUMENTS
    =========================================== -->

    <div class="upload-box">

        <input
            type="file"
            name="medical_documents[]"
            id="medical_documents"
            accept=".pdf,.jpg,.jpeg,.png"
            multiple
        >


        <label
            for="medical_documents"
        >
            📤 &nbsp;
            Choose Medical Documents
        </label>


        <p>
            PDF, JPG or PNG · Maximum 10 MB per file
        </p>


        <div
            id="selected-files"
            class="selected-files"
        ></div>

    </div>

</section>
               


                <!-- ==========================================
                     ACTION BUTTONS
                =========================================== -->

                <div class="action-bar">

                    <button
                        type="button"
                        class="reset-button"
                        id="reset-button"
                    >
                        Reset Changes
                    </button>


                    <div class="action-right">

                        <a
                            href="patient-profile.php?patient_id=<?php echo $patient_id; ?>"
                            class="cancel-button"
                        >
                            Cancel
                        </a>


                        <button
                            type="submit"
                            class="save-button"
                            id="save-button"
                        >
                            Save Changes
                        </button>

                    </div>

                </div>

            </form>

        </section>

    </div>

</main>


<script
    src="../assets/js/patient-edit.js"
></script>

</body>

</html>
