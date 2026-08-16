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
| GET PATIENT ID
|--------------------------------------------------------------------------
*/

$patient_id = (int) ($_POST['patient_id'] ?? 0);

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
| patient_id AND family_id are checked together.
|
*/

$sql = "
    SELECT
        patient_id,
        full_name,
        profile_photo
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
    $conn->close();

    die("Patient not found or you do not have permission to delete this patient.");
}


/*
|--------------------------------------------------------------------------
| GET PATIENT DOCUMENTS
|--------------------------------------------------------------------------
|
| We need the paths so we can also delete the physical files.
|
*/

$documents = [];

$sql = "
    SELECT document_id, document_path
    FROM patient_documents
    WHERE patient_id = ?
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to read patient documents: " . $conn->error);
}

$stmt->bind_param(
    "i",
    $patient_id
);

$stmt->execute();

$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $documents[] = $row;
}

$stmt->close();


/*
|--------------------------------------------------------------------------
| START TRANSACTION
|--------------------------------------------------------------------------
*/

$conn->begin_transaction();


try {


    /*
    |--------------------------------------------------------------------------
    | DELETE MEDICAL DOCUMENT DATABASE RECORDS
    |--------------------------------------------------------------------------
    */

    $sql = "
        DELETE FROM patient_documents
        WHERE patient_id = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception(
            "Unable to prepare document deletion: " .
            $conn->error
        );
    }

    $stmt->bind_param(
        "i",
        $patient_id
    );

    if (!$stmt->execute()) {
        throw new Exception(
            "Unable to delete patient documents: " .
            $stmt->error
        );
    }

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | DELETE PATIENT
    |--------------------------------------------------------------------------
    */

    $sql = "
        DELETE FROM patients
        WHERE patient_id = ?
          AND family_id = ?
    ";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception(
            "Unable to prepare patient deletion: " .
            $conn->error
        );
    }

    $stmt->bind_param(
        "ii",
        $patient_id,
        $family_id
    );

    if (!$stmt->execute()) {
        throw new Exception(
            "Unable to delete patient: " .
            $stmt->error
        );
    }


    if ($stmt->affected_rows !== 1) {

        throw new Exception(
            "Patient was not deleted."
        );

    }

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | COMMIT
    |--------------------------------------------------------------------------
    */

    $conn->commit();


    /*
    |--------------------------------------------------------------------------
    | DELETE PHYSICAL MEDICAL DOCUMENT FILES
    |--------------------------------------------------------------------------
    */

    foreach ($documents as $document) {

        if (
            empty(
                $document['document_path']
            )
        ) {
            continue;
        }


        $filePath =
            __DIR__ .
            '/../' .
            ltrim(
                $document['document_path'],
                '/'
            );


        if (is_file($filePath)) {
            @unlink($filePath);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE PROFILE PHOTO
    |--------------------------------------------------------------------------
    */

    if (
        !empty(
            $patient['profile_photo']
        )
    ) {

        $photoPath =
            __DIR__ .
            '/../' .
            ltrim(
                $patient['profile_photo'],
                '/'
            );


        if (is_file($photoPath)) {
            @unlink($photoPath);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    $conn->close();

    header(
        "Location: dashboard.php?deleted=1"
    );

    exit;


} catch (Exception $e) {


    /*
    |--------------------------------------------------------------------------
    | ROLLBACK
    |--------------------------------------------------------------------------
    */

    $conn->rollback();

    $error = $e->getMessage();

    $conn->close();


    die(
        "Patient could not be deleted: " .
        htmlspecialchars(
            $error,
            ENT_QUOTES,
            'UTF-8'
        )
    );
}

?>