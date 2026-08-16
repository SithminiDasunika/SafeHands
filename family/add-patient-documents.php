<?php

session_start();

require_once __DIR__ . '/../includes/db.php';


/*
|--------------------------------------------------------------------------
| Check Family Member Login
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
| POST only
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: patients.php");

    exit;
}


/*
|--------------------------------------------------------------------------
| Patient ID
|--------------------------------------------------------------------------
*/

$patient_id =
    isset($_POST['patient_id'])
        ? (int) $_POST['patient_id']
        : 0;


if ($patient_id <= 0) {

    die("Invalid patient ID.");

}


/*
|--------------------------------------------------------------------------
| Get Family ID
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
    die("Database error.");
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
| Verify Patient belongs to this Family
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT patient_id
    FROM patients
    WHERE patient_id = ?
      AND family_id = ?
    LIMIT 1
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database error.");
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

    die("Patient not found.");

}


/*
|--------------------------------------------------------------------------
| Check files
|--------------------------------------------------------------------------
*/

if (
    !isset($_FILES['medical_documents']) ||
    !is_array($_FILES['medical_documents']['name'])
) {

    header(
        "Location: patient-profile.php?patient_id="
        . $patient_id
    );

    exit;
}


/*
|--------------------------------------------------------------------------
| Upload directory
|--------------------------------------------------------------------------
*/

$uploadDirectory =
    __DIR__ .
    '/../uploads/medical_documents/';


if (!is_dir($uploadDirectory)) {

    mkdir(
        $uploadDirectory,
        0755,
        true
    );

}


/*
|--------------------------------------------------------------------------
| Allowed files
|--------------------------------------------------------------------------
*/

$allowedExtensions = [
    'pdf',
    'jpg',
    'jpeg',
    'png'
];


$fileCount =
    count(
        $_FILES['medical_documents']['name']
    );


/*
|--------------------------------------------------------------------------
| Process each file
|--------------------------------------------------------------------------
*/

for (
    $i = 0;
    $i < $fileCount;
    $i++
) {

    if (
        $_FILES['medical_documents']['error'][$i]
        !== UPLOAD_ERR_OK
    ) {
        continue;
    }


    $originalName =
        $_FILES['medical_documents']['name'][$i];


    $temporaryPath =
        $_FILES['medical_documents']['tmp_name'][$i];


    $fileSize =
        (int)
        $_FILES['medical_documents']['size'][$i];


    /*
     * Maximum 10 MB
     */

    if (
        $fileSize <= 0 ||
        $fileSize > 10 * 1024 * 1024
    ) {
        continue;
    }


    /*
     * Get extension
     */

    $extension =
        strtolower(
            pathinfo(
                $originalName,
                PATHINFO_EXTENSION
            )
        );


    if (
        !in_array(
            $extension,
            $allowedExtensions,
            true
        )
    ) {
        continue;
    }


    /*
     * Validate images
     */

    if (
        in_array(
            $extension,
            ['jpg', 'jpeg', 'png'],
            true
        )
    ) {

        if (
            getimagesize(
                $temporaryPath
            ) === false
        ) {

            continue;

        }

    }


    /*
     * Generate unique filename
     */

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
        $uploadDirectory .
        $fileName;


    /*
     * Move file
     */

    if (
        !move_uploaded_file(
            $temporaryPath,
            $destination
        )
    ) {
        continue;
    }


    /*
     * Database path
     */

    $databasePath =
        'uploads/medical_documents/' .
        $fileName;


    /*
     * Insert document record
     */

    $sql = "
        INSERT INTO patient_documents (
            patient_id,
            document_name,
            document_path,
            document_type,
            file_size
        )
        VALUES (?, ?, ?, ?, ?)
    ";


    $documentStmt =
        $conn->prepare($sql);


    if (!$documentStmt) {
        continue;
    }


    $documentStmt->bind_param(
        "isssi",
        $patient_id,
        $originalName,
        $databasePath,
        $extension,
        $fileSize
    );


    $documentStmt->execute();

    $documentStmt->close();

}


$conn->close();


/*
|--------------------------------------------------------------------------
| Return to patient profile
|--------------------------------------------------------------------------
*/

header(
    "Location: patient-profile.php?patient_id="
    . $patient_id
    . "&documents_added=1"
);

exit;

?>