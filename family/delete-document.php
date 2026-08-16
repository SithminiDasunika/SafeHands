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
| POST ONLY
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: patients.php");
    exit;
}


$document_id =
    (int) ($_POST['document_id'] ?? 0);

$patient_id =
    (int) ($_POST['patient_id'] ?? 0);


if (
    $document_id <= 0 ||
    $patient_id <= 0
) {
    die("Invalid document information.");
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

$result =
    $stmt->get_result();

$family =
    $result->fetch_assoc();

$stmt->close();


if (!$family) {
    die("Family profile not found.");
}

$family_id =
    (int) $family['family_id'];


/*
|--------------------------------------------------------------------------
| GET DOCUMENT
|--------------------------------------------------------------------------
|
| Make sure the document belongs to:
| - this patient
| - this family
|
*/

$sql = "
    SELECT
        d.document_id,
        d.document_path
    FROM patient_documents d

    INNER JOIN patients p
        ON p.patient_id = d.patient_id

    WHERE d.document_id = ?
      AND d.patient_id = ?
      AND p.family_id = ?

    LIMIT 1
";

$stmt =
    $conn->prepare($sql);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param(
    "iii",
    $document_id,
    $patient_id,
    $family_id
);

$stmt->execute();

$result =
    $stmt->get_result();

$document =
    $result->fetch_assoc();

$stmt->close();


if (!$document) {
    die("Document not found.");
}


/*
|--------------------------------------------------------------------------
| DELETE DATABASE RECORD
|--------------------------------------------------------------------------
*/

$sql = "
    DELETE FROM patient_documents
    WHERE document_id = ?
      AND patient_id = ?
";

$stmt =
    $conn->prepare($sql);

if (!$stmt) {
    die("Unable to prepare delete: " . $conn->error);
}

$stmt->bind_param(
    "ii",
    $document_id,
    $patient_id
);


if (!$stmt->execute()) {

    $error =
        $stmt->error;

    $stmt->close();

    die(
        "Unable to delete document: " .
        htmlspecialchars($error)
    );
}


$deleted =
    $stmt->affected_rows;

$stmt->close();


if ($deleted !== 1) {
    die("Document was not deleted.");
}


/*
|--------------------------------------------------------------------------
| DELETE PHYSICAL FILE
|--------------------------------------------------------------------------
*/

if (
    !empty(
        $document['document_path']
    )
) {

    $filePath =
        __DIR__ .
        '/../' .
        ltrim(
            $document['document_path'],
            '/'
        );


    if (is_file($filePath)) {

        if (!unlink($filePath)) {

            /*
             * Database record is already deleted.
             * Don't restore it just because the
             * physical file cannot be removed.
             */

        }

    }
}


/*
|--------------------------------------------------------------------------
| RETURN TO EDIT PAGE
|--------------------------------------------------------------------------
*/

$conn->close();


header(
    "Location: patient-edit.php?patient_id=" .
    $patient_id .
    "&document_deleted=1"
);

exit;

?>