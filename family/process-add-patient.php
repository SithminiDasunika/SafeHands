<?php

session_start();

require_once __DIR__ . '/../includes/db.php';


/*
|--------------------------------------------------------------------------
| Check Login
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
| POST Only
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: add-patient.php");

    exit;
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

    die(
        "Family profile not found. Please contact the administrator."
    );

}

$family_id =
    (int) $family['family_id'];


/*
|--------------------------------------------------------------------------
| Get Form Values
|--------------------------------------------------------------------------
*/

$full_name =
    trim($_POST['full_name'] ?? '');

$date_of_birth =
    trim($_POST['date_of_birth'] ?? '');

$gender =
    trim($_POST['gender'] ?? '');

$blood_group =
    trim($_POST['blood_group'] ?? '');

$nic =
    trim($_POST['nic'] ?? '');

$relationship =
    trim($_POST['relationship'] ?? '');

$phone =
    trim($_POST['phone'] ?? '');

$address =
    trim($_POST['address'] ?? '');

$medical_conditions =
    trim($_POST['medical_conditions'] ?? '');

$allergies =
    trim($_POST['allergies'] ?? '');

$mobility_status =
    trim($_POST['mobility_status'] ?? '');

$weight =
    trim($_POST['weight'] ?? '');

$blood_pressure =
    trim($_POST['blood_pressure'] ?? '');

$current_medications =
    trim($_POST['current_medications'] ?? '');

$special_care_requirements =
    trim($_POST['special_care_requirements'] ?? '');

$dietary_restrictions =
    trim($_POST['dietary_restrictions'] ?? '');

$doctors_notes =
    trim($_POST['doctors_notes'] ?? '');

$emergency_contact_name =
    trim($_POST['emergency_contact_name'] ?? '');

$emergency_contact_relationship =
    trim($_POST['emergency_contact_relationship'] ?? '');

$emergency_contact_phone =
    trim($_POST['emergency_contact_phone'] ?? '');

$emergency_alternative_phone =
    trim($_POST['emergency_alternative_phone'] ?? '');


/*
|--------------------------------------------------------------------------
| Required Fields
|--------------------------------------------------------------------------
*/

if (
    $full_name === '' ||
    $date_of_birth === '' ||
    $gender === '' ||
    $relationship === ''
) {

    die(
        "Please fill in all required patient information."
    );

}


/*
|--------------------------------------------------------------------------
| Validate Gender
|--------------------------------------------------------------------------
*/

$allowed_genders = [
    'Male',
    'Female',
    'Other'
];

if (
    !in_array(
        $gender,
        $allowed_genders,
        true
    )
) {

    die("Invalid gender selected.");

}


/*
|--------------------------------------------------------------------------
| Validate Blood Group
|--------------------------------------------------------------------------
*/

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
    $blood_group !== '' &&
    !in_array(
        $blood_group,
        $allowed_blood_groups,
        true
    )
) {

    die(
        "Invalid blood group selected."
    );

}


/*
|--------------------------------------------------------------------------
| Validate Weight
|--------------------------------------------------------------------------
*/

if ($weight !== '') {

    if (!is_numeric($weight)) {

        die(
            "Weight must be a valid number."
        );

    }

    $weightNumber =
        (float) $weight;

    if (
        $weightNumber <= 0 ||
        $weightNumber > 500
    ) {

        die(
            "Weight must be between 1 and 500 kg."
        );

    }

}


/*
|--------------------------------------------------------------------------
| Validate Blood Pressure
|--------------------------------------------------------------------------
*/

if ($blood_pressure !== '') {

    if (
        !preg_match(
            '/^\d{2,3}\/\d{2,3}$/',
            $blood_pressure
        )
    ) {

        die(
            "Blood pressure must be in format 130/85."
        );

    }

}


/*
|--------------------------------------------------------------------------
| Create Patient
|--------------------------------------------------------------------------
*/

$sql = "
    INSERT INTO patients (
        family_id,
        full_name,
        date_of_birth,
        gender,
        blood_group,
        nic,
        relationship,
        phone,
        address,
        medical_conditions,
        allergies,
        mobility_status,
        weight,
        blood_pressure,
        current_medications,
        special_care_requirements,
        dietary_restrictions,
        doctors_notes,
        emergency_contact_name,
        emergency_contact_relationship,
        emergency_contact_phone,
        emergency_alternative_phone,
        status
    )
    VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
        ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active'
    )
";


$stmt =
    $conn->prepare($sql);

if (!$stmt) {

    die(
        "Unable to prepare patient record: " .
        $conn->error
    );

}


/*
|--------------------------------------------------------------------------
| IMPORTANT
|
| 22 bound variables:
|
| 1 integer
| 21 strings
|--------------------------------------------------------------------------
*/

$stmt->bind_param(
    "isssssssssssssssssssss",
    $family_id,
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
    $emergency_alternative_phone
);


/*
|--------------------------------------------------------------------------
| Execute Patient Insert
|--------------------------------------------------------------------------
*/

if (!$stmt->execute()) {

    $error =
        $stmt->error;

    $stmt->close();

    $conn->close();

    die(
        "Unable to create patient: " .
        htmlspecialchars($error)
    );

}


$patient_id =
    $stmt->insert_id;

$stmt->close();


/*
|--------------------------------------------------------------------------
| PROFILE PHOTO
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['profile_photo']) &&
    $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK
) {

    $photo = $_FILES['profile_photo'];

    if ($photo['size'] > 2 * 1024 * 1024) {
        die("Profile photo must be smaller than 2 MB.");
    }

    $imageInfo = getimagesize($photo['tmp_name']);

    if ($imageInfo === false) {
        die("Invalid profile photo.");
    }

    if ($imageInfo['mime'] === 'image/jpeg') {
        $photoExtension = 'jpg';
    } elseif ($imageInfo['mime'] === 'image/png') {
        $photoExtension = 'png';
    } else {
        die("Only JPG, JPEG and PNG profile photos are allowed.");
    }

    $uploadsDirectory = dirname(__DIR__) . '/uploads/';
    $photoDirectory = $uploadsDirectory . 'patients/';

    if (file_exists($uploadsDirectory) && !is_dir($uploadsDirectory)) {
        die(
            "ERROR: safehands/uploads exists but it is not a folder. " .
            "Create an uploads folder."
        );
    }

    if (!is_dir($uploadsDirectory)) {
        if (!mkdir($uploadsDirectory, 0755, true)) {
            die("Unable to create the uploads folder.");
        }
    }

    if (file_exists($photoDirectory) && !is_dir($photoDirectory)) {
        die(
            "ERROR: safehands/uploads/patients exists but it is not a folder. " .
            "Create a patients folder."
        );
    }

    if (!is_dir($photoDirectory)) {
        if (!mkdir($photoDirectory, 0755, true)) {
            die("Unable to create uploads/patients folder.");
        }
    }

    if (!is_writable($photoDirectory)) {
        die("The uploads/patients folder is not writable.");
    }

    $photoFileName =
        'patient_' .
        $patient_id .
        '_' .
        bin2hex(random_bytes(8)) .
        '.' .
        $photoExtension;

    $photoFullPath =
        $photoDirectory .
        $photoFileName;

    if (!move_uploaded_file(
        $photo['tmp_name'],
        $photoFullPath
    )) {
        die("Unable to save profile photo.");
    }

    $photoDatabasePath =
        'uploads/patients/' .
        $photoFileName;

    $photoSQL = "
        UPDATE patients
        SET profile_photo = ?
        WHERE patient_id = ?
          AND family_id = ?
    ";

    $photoStmt = $conn->prepare($photoSQL);

    if (!$photoStmt) {
        if (is_file($photoFullPath)) {
            unlink($photoFullPath);
        }
        die(
            "Unable to update profile photo: " .
            $conn->error
        );
    }

    $photoStmt->bind_param(
        "sii",
        $photoDatabasePath,
        $patient_id,
        $family_id
    );

    if (!$photoStmt->execute()) {
        $photoError = $photoStmt->error;
        $photoStmt->close();

        if (is_file($photoFullPath)) {
            unlink($photoFullPath);
        }

        die(
            "Unable to update profile photo: " .
            htmlspecialchars($photoError, ENT_QUOTES, 'UTF-8')
        );
    }

    $photoStmt->close();
}


/*
|--------------------------------------------------------------------------
| INITIAL MEDICAL DOCUMENT
|
| Only ONE document during patient creation
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| INITIAL MEDICAL DOCUMENT
|
| Only ONE document during patient creation
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['medical_document']) &&
    $_FILES['medical_document']['error']
        === UPLOAD_ERR_OK
) {

    $document =
        $_FILES['medical_document'];


    /*
     * Maximum 10 MB
     */

    if (
        $document['size'] >
        10 * 1024 * 1024
    ) {

        die(
            "Medical document must be smaller than 10 MB."
        );

    }


    /*
     * Allowed extensions
     */

    $allowedDocumentExtensions = [
        'pdf',
        'jpg',
        'jpeg',
        'png'
    ];


    $documentExtension =
        strtolower(
            pathinfo(
                $document['name'],
                PATHINFO_EXTENSION
            )
        );


    if (
        !in_array(
            $documentExtension,
            $allowedDocumentExtensions,
            true
        )
    ) {

        die(
            "Only PDF, JPG, JPEG and PNG documents are allowed."
        );

    }


    /*
     * Validate image documents
     */

    if (
        in_array(
            $documentExtension,
            [
                'jpg',
                'jpeg',
                'png'
            ],
            true
        )
    ) {

        if (
            getimagesize(
                $document['tmp_name']
            ) === false
        ) {

            die(
                "Invalid medical document."
            );

        }

    }


    /*
     * Create directory
     */

    $documentDirectory =
        dirname(__DIR__) .
        '/uploads/patient_documents/';


    if (
        file_exists($documentDirectory) &&
        !is_dir($documentDirectory)
    ) {
        die(
            "ERROR: uploads/patient_documents exists but it is not a folder."
        );
    }

    if (!is_dir($documentDirectory)) {
        if (!mkdir($documentDirectory, 0755, true)) {
            die("Unable to create uploads/patient_documents folder.");
        }
    }

    if (!is_writable($documentDirectory)) {
        die("The uploads/patient_documents folder is not writable.");
    }


    /*
     * Generate unique filename
     */

    $documentFileName =
        'patient_' .
        $patient_id .
        '_' .
        bin2hex(
            random_bytes(8)
        ) .
        '.' .
        $documentExtension;


    $documentFullPath =
        $documentDirectory .
        $documentFileName;


    /*
     * Move document
     */

    if (
        !move_uploaded_file(
            $document['tmp_name'],
            $documentFullPath
        )
    ) {

        die(
            "Unable to save medical document."
        );

    }


    /*
     * Database path
     */

    $documentDatabasePath =
        'uploads/patient_documents/' .
        $documentFileName;


    /*
     * Insert document
     */

    $documentSQL = "
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
        $conn->prepare(
            $documentSQL
        );


    if (!$documentStmt) {

        die(
            "Unable to save medical document record: " .
            $conn->error
        );

    }


    $originalDocumentName =
        $document['name'];

    $documentFileSize =
        (int) $document['size'];


    $documentStmt->bind_param(
        "isssi",
        $patient_id,
        $originalDocumentName,
        $documentDatabasePath,
        $documentExtension,
        $documentFileSize
    );


    if (
        !$documentStmt->execute()
    ) {

        $documentStmt->close();

        die(
            "Unable to save medical document record."
        );

    }


    $documentStmt->close();

}


/*
|--------------------------------------------------------------------------
| Close Database
|--------------------------------------------------------------------------
*/

$conn->close();


/*
|--------------------------------------------------------------------------
| Redirect to Patient Profile
|--------------------------------------------------------------------------
*/

header(
    "Location: patient-profile.php?patient_id=" .
    $patient_id .
    "&created=1"
);

exit;

?>
