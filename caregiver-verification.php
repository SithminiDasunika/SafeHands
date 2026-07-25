<?php

session_start();

require_once __DIR__ . "/includes/db.php";


/*
|--------------------------------------------------------------------------
| SAFEHANDS CAREGIVER REGISTRATION
| STEP 3 - VERIFICATION + FINAL DATABASE SAVE
|--------------------------------------------------------------------------
*/

$error = "";


/*
|--------------------------------------------------------------------------
| PROTECT STEP 3
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["caregiver_registration"]) ||
    empty($_SESSION["caregiver_registration"]["step_1_complete"])
) {

    header("Location: caregiver-register.php");
    exit;
}


if (
    !isset($_SESSION["caregiver_professional"]) ||
    empty($_SESSION["caregiver_professional"]["step_2_complete"])
) {

    header("Location: caregiver-professional.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| RESTORE PREVIOUS VERIFICATION DATA
|--------------------------------------------------------------------------
*/

$verificationData =
    $_SESSION["caregiver_verification"] ?? [];


/*
|--------------------------------------------------------------------------
| HELPER FUNCTION - UPLOAD DOCUMENT
|--------------------------------------------------------------------------
*/

function uploadDocument(
    $inputName,
    $required,
    $oldFile = ""
) {

    /*
    |--------------------------------------------------------------------------
    | No new file selected
    |--------------------------------------------------------------------------
    */

    if (
        !isset($_FILES[$inputName]) ||
        $_FILES[$inputName]["error"] === UPLOAD_ERR_NO_FILE
    ) {

        if (
            $required &&
            $oldFile === ""
        ) {

            return [
                "success" => false,
                "error" => "required",
                "path" => "",
                "new_upload" => false
            ];

        }


        return [
            "success" => true,
            "error" => "",
            "path" => $oldFile,
            "new_upload" => false
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Upload error
    |--------------------------------------------------------------------------
    */

    if (
        $_FILES[$inputName]["error"]
        !== UPLOAD_ERR_OK
    ) {

        return [
            "success" => false,
            "error" => "upload",
            "path" => "",
            "new_upload" => false
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Maximum 5MB
    |--------------------------------------------------------------------------
    */

    if (
        $_FILES[$inputName]["size"]
        > 5 * 1024 * 1024
    ) {

        return [
            "success" => false,
            "error" => "size",
            "path" => "",
            "new_upload" => false
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Allowed file types
    |--------------------------------------------------------------------------
    */

    $allowedTypes = [

        "image/jpeg" => "jpg",

        "image/png" => "png",

        "application/pdf" => "pdf"

    ];


    $fileInfo =
        finfo_open(
            FILEINFO_MIME_TYPE
        );


    if (!$fileInfo) {

        return [
            "success" => false,
            "error" => "type",
            "path" => "",
            "new_upload" => false
        ];

    }


    $mimeType =
        finfo_file(
            $fileInfo,
            $_FILES[$inputName]["tmp_name"]
        );


    finfo_close($fileInfo);


    if (
        !isset(
            $allowedTypes[$mimeType]
        )
    ) {

        return [
            "success" => false,
            "error" => "type",
            "path" => "",
            "new_upload" => false
        ];

    }


    /*
    |--------------------------------------------------------------------------
    | Create upload folder
    |--------------------------------------------------------------------------
    */

    $uploadDirectory =
        __DIR__ .
        "/uploads/caregiver-documents/";


    if (
        !is_dir($uploadDirectory)
    ) {

        if (
            !mkdir(
                $uploadDirectory,
                0755,
                true
            )
        ) {

            return [
                "success" => false,
                "error" => "folder",
                "path" => "",
                "new_upload" => false
            ];

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Create secure filename
    |--------------------------------------------------------------------------
    */

    $extension =
        $allowedTypes[$mimeType];


    $fileName =
        $inputName .
        "_" .
        bin2hex(
            random_bytes(8)
        ) .
        "." .
        $extension;


    $destination =
        $uploadDirectory .
        $fileName;


    /*
    |--------------------------------------------------------------------------
    | Move uploaded file
    |--------------------------------------------------------------------------
    */

    if (
        !move_uploaded_file(
            $_FILES[$inputName]["tmp_name"],
            $destination
        )
    ) {

        return [
            "success" => false,
            "error" => "save",
            "path" => "",
            "new_upload" => false
        ];

    }


    return [

        "success" => true,

        "error" => "",

        "path" =>
            "uploads/caregiver-documents/" .
            $fileName,

        "new_upload" => true

    ];

}


/*
|--------------------------------------------------------------------------
| DELETE NEW FILES IF FINAL DATABASE SAVE FAILS
|--------------------------------------------------------------------------
*/

function deleteNewUploads(
    $paths
) {

    foreach ($paths as $relativePath) {

        if ($relativePath === "") {
            continue;
        }


        $fullPath =
            __DIR__ .
            "/" .
            $relativePath;


        if (
            is_file($fullPath)
        ) {

            @unlink($fullPath);

        }

    }

}


/*
|--------------------------------------------------------------------------
| PROCESS FINAL SUBMISSION
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"]
    === "POST"
) {

    /*
    |--------------------------------------------------------------------------
    | Declaration
    |--------------------------------------------------------------------------
    */

    $declaration =
        isset(
            $_POST["declaration"]
        );


    if (!$declaration) {

        $error =
            "Please confirm that all uploaded documents are genuine and accurate.";

    }


    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    $documents = [

        "nic_front" => [
            "label" => "NIC Front",
            "required" => true
        ],

        "nic_back" => [
            "label" => "NIC Back",
            "required" => true
        ],

        "qualification_certificate" => [
            "label" => "Qualification Certificate",
            "required" => true
        ],

        "police_clearance" => [
            "label" => "Police Clearance",
            "required" => true
        ],

        "first_aid_certificate" => [
            "label" => "First Aid Certificate",
            "required" => false
        ],

        "experience_letter" => [
            "label" => "Experience Letter",
            "required" => false
        ],

        "medical_fitness" => [
            "label" => "Medical Fitness Certificate",
            "required" => false
        ]

    ];


    /*
    |--------------------------------------------------------------------------
    | Start with previously uploaded document paths
    |--------------------------------------------------------------------------
    */

    $newVerificationData =
        $verificationData;


    /*
    |--------------------------------------------------------------------------
    | Track only files uploaded during this request
    |--------------------------------------------------------------------------
    */

    $newlyUploadedFiles = [];


    /*
    |--------------------------------------------------------------------------
    | Upload / validate documents
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        foreach (
            $documents
            as
            $inputName =>
            $document
        ) {

            $oldFile =
                $verificationData[
                    $inputName
                ]
                ?? "";


            $result =
                uploadDocument(

                    $inputName,

                    $document[
                        "required"
                    ],

                    $oldFile

                );


            if (
                !$result[
                    "success"
                ]
            ) {

                switch (
                    $result["error"]
                ) {

                    case "required":

                        $error =
                            "Please upload " .
                            $document["label"] .
                            ".";

                        break;


                    case "size":

                        $error =
                            $document["label"] .
                            " must be 5MB or smaller.";

                        break;


                    case "type":

                        $error =
                            $document["label"] .
                            " must be a JPG, PNG, or PDF file.";

                        break;


                    case "folder":

                        $error =
                            "Unable to create the document upload folder.";

                        break;


                    default:

                        $error =
                            "There was a problem uploading " .
                            $document["label"] .
                            ".";

                        break;

                }


                break;

            }


            /*
            |--------------------------------------------------------------------------
            | Store document path
            |--------------------------------------------------------------------------
            */

            $newVerificationData[
                $inputName
            ] =
                $result["path"];


            /*
            |--------------------------------------------------------------------------
            | Remember newly uploaded file for cleanup if DB fails
            |--------------------------------------------------------------------------
            */

            if (
                !empty(
                    $result["new_upload"]
                )
            ) {

                $newlyUploadedFiles[] =
                    $result["path"];

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | GET STEP 1 + STEP 2 SESSION DATA
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        $step1 =
            $_SESSION[
                "caregiver_registration"
            ];


        $step2 =
            $_SESSION[
                "caregiver_professional"
            ];


        /*
        |--------------------------------------------------------------------------
        | Step 1 values
        |--------------------------------------------------------------------------
        */

        $fullName =
            trim(
                $step1["full_name"]
                ?? ""
            );


        $nic =
            trim(
                $step1["nic"]
                ?? ""
            );


        $dob =
            trim(
                $step1["dob"]
                ?? ""
            );


        $gender =
            trim(
                $step1["gender"]
                ?? ""
            );


        $phone =
            trim(
                $step1["phone"]
                ?? ""
            );


        $email =
            trim(
                $step1["email"]
                ?? ""
            );


        $address =
            trim(
                $step1["address"]
                ?? ""
            );


        $district =
            trim(
                $step1["district"]
                ?? ""
            );


        $passwordHash =
            $step1[
                "password_hash"
            ]
            ?? "";


        /*
        |--------------------------------------------------------------------------
        | Split full name
        |--------------------------------------------------------------------------
        */

        $nameParts =
            preg_split(
                "/\s+/",
                $fullName,
                2
            );


        $firstName =
            $nameParts[0]
            ?? "";


        $lastName =
            $nameParts[1]
            ?? "";


        /*
        |--------------------------------------------------------------------------
        | Combine address + district
        |--------------------------------------------------------------------------
        */

        $fullAddress =
            $address;


        if (
            $district !== ""
        ) {

            $fullAddress .=
                (
                    $fullAddress !== ""
                    ? ", "
                    : ""
                )
                .
                $district;

        }


        /*
        |--------------------------------------------------------------------------
        | Step 2 values
        |--------------------------------------------------------------------------
        */

        $qualification =
            trim(
                $step2[
                    "qualification"
                ]
                ?? ""
            );


        $experience =
            (int)(
                $step2[
                    "experience"
                ]
                ?? 0
            );


        $certifications =
            trim(
                $step2[
                    "certifications"
                ]
                ?? ""
            );


        $languages =
            trim(
                $step2[
                    "languages"
                ]
                ?? ""
            );


        $serviceAreas =
            trim(
                $step2[
                    "service_areas"
                ]
                ?? ""
            );


        $dailyRateRaw =
            $step2[
                "daily_rate"
            ]
            ?? "";


        $dailyRate =
            (
                $dailyRateRaw === ""
                ||
                $dailyRateRaw === null
            )
            ? null
            : (float)$dailyRateRaw;


        $biography =
            trim(
                $step2[
                    "biography"
                ]
                ?? ""
            );


        $profilePhoto =
            trim(
                $step2[
                    "profile_photo"
                ]
                ?? ""
            );


        /*
        |--------------------------------------------------------------------------
        | Validate session information
        |--------------------------------------------------------------------------
        */

        if (
            $firstName === "" ||
            $nic === "" ||
            $dob === "" ||
            $gender === "" ||
            $phone === "" ||
            $email === "" ||
            $fullAddress === "" ||
            $passwordHash === ""
        ) {

            $error =
                "Some personal information is missing. Please return to Step 1 and complete the form again.";

        }


        if (
            $error === "" &&
            (
                $qualification === "" ||
                $languages === "" ||
                $serviceAreas === "" ||
                $biography === ""
            )
        ) {

            $error =
                "Some professional information is missing. Please return to Step 2 and complete the form again.";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | CHECK DUPLICATE EMAIL / NIC
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        $checkStatement =
            $conn->prepare(

                "SELECT user_id
                 FROM users
                 WHERE email = ?
                    OR nic = ?
                 LIMIT 1"

            );


        if (!$checkStatement) {

            $error =
                "Unable to check existing accounts: " .
                $conn->error;

        } else {

            $checkStatement->bind_param(
                "ss",
                $email,
                $nic
            );


            if (
                !$checkStatement->execute()
            ) {

                $error =
                    "Unable to check existing accounts.";

            } else {

                $checkStatement
                    ->store_result();


                if (
                    $checkStatement
                        ->num_rows
                    > 0
                ) {

                    $error =
                        "An account already exists with this email address or NIC number.";

                }

            }


            $checkStatement
                ->close();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SAVE EVERYTHING TO DATABASE
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        try {

            /*
            |--------------------------------------------------------------------------
            | Start transaction
            |--------------------------------------------------------------------------
            */

            $conn
                ->begin_transaction();


            /*
            |--------------------------------------------------------------------------
            | 1. INSERT USER
            |--------------------------------------------------------------------------
            */

            $role =
                "Caregiver";


            $status =
                "Active";


            $userStatement =
                $conn->prepare(

                    "INSERT INTO users
                    (
                        first_name,
                        last_name,
                        nic,
                        phone,
                        email,
                        address,
                        password,
                        role,
                        status
                    )
                    VALUES
                    (
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?,
                        ?
                    )"

                );


            if (!$userStatement) {

                throw new Exception(
                    "Unable to prepare the user account: " .
                    $conn->error
                );

            }


            $userStatement
                ->bind_param(

                    "sssssssss",

                    $firstName,
                    $lastName,
                    $nic,
                    $phone,
                    $email,
                    $fullAddress,
                    $passwordHash,
                    $role,
                    $status

                );


            if (
                !$userStatement
                    ->execute()
            ) {

                throw new Exception(
                    "Unable to create the user account: " .
                    $userStatement->error
                );

            }


            $userId =
                $conn->insert_id;


            $userStatement
                ->close();


            /*
            |--------------------------------------------------------------------------
            | 2. INSERT CAREGIVER PROFILE
            |--------------------------------------------------------------------------
            */

            $verificationStatus =
                "Pending";


            /*
            |--------------------------------------------------------------------------
            | Use NULL daily rate safely
            |--------------------------------------------------------------------------
            | bind_param("d") converts null awkwardly on some setups,
            | so use two query versions.
            |--------------------------------------------------------------------------
            */

            if ($dailyRate === null) {

                $profileStatement =
                    $conn->prepare(

                        "INSERT INTO caregiver_profiles
                        (
                            user_id,
                            gender,
                            date_of_birth,
                            highest_qualification,
                            years_experience,
                            certifications,
                            languages,
                            service_areas,
                            daily_rate,
                            biography,
                            profile_photo,
                            verification_status
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            NULL,
                            ?,
                            ?,
                            ?
                        )"

                    );


                if (!$profileStatement) {

                    throw new Exception(
                        "Unable to prepare the caregiver profile: " .
                        $conn->error
                    );

                }


                $profileStatement
                    ->bind_param(

                        "isssissssss",

                        $userId,
                        $gender,
                        $dob,
                        $qualification,
                        $experience,
                        $certifications,
                        $languages,
                        $serviceAreas,
                        $biography,
                        $profilePhoto,
                        $verificationStatus

                    );

            } else {

                $profileStatement =
                    $conn->prepare(

                        "INSERT INTO caregiver_profiles
                        (
                            user_id,
                            gender,
                            date_of_birth,
                            highest_qualification,
                            years_experience,
                            certifications,
                            languages,
                            service_areas,
                            daily_rate,
                            biography,
                            profile_photo,
                            verification_status
                        )
                        VALUES
                        (
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?,
                            ?
                        )"

                    );


                if (!$profileStatement) {

                    throw new Exception(
                        "Unable to prepare the caregiver profile: " .
                        $conn->error
                    );

                }


                $profileStatement
                    ->bind_param(

                        "isssisssdsss",

                        $userId,
                        $gender,
                        $dob,
                        $qualification,
                        $experience,
                        $certifications,
                        $languages,
                        $serviceAreas,
                        $dailyRate,
                        $biography,
                        $profilePhoto,
                        $verificationStatus

                    );

            }


            if (
                !$profileStatement
                    ->execute()
            ) {

                throw new Exception(
                    "Unable to create the caregiver profile: " .
                    $profileStatement->error
                );

            }


            $caregiverId =
                $conn->insert_id;


            $profileStatement
                ->close();


            /*
            |--------------------------------------------------------------------------
            | 3. INSERT CAREGIVER DOCUMENTS
            |--------------------------------------------------------------------------
            */

            $documentMap = [

                "nic_front" =>
                    "NIC_Front",

                "nic_back" =>
                    "NIC_Back",

                "qualification_certificate" =>
                    "Qualification",

                "police_clearance" =>
                    "Police_Clearance",

                "first_aid_certificate" =>
                    "First_Aid",

                "experience_letter" =>
                    "Experience_Letter",

                "medical_fitness" =>
                    "Medical_Fitness"

            ];


            $documentStatement =
                $conn->prepare(

                    "INSERT INTO caregiver_documents
                    (
                        caregiver_id,
                        document_type,
                        file_path
                    )
                    VALUES (?, ?, ?)"

                );


            if (!$documentStatement) {

                throw new Exception(
                    "Unable to prepare caregiver documents: " .
                    $conn->error
                );

            }


            foreach (
                $documentMap
                as
                $sessionKey =>
                $documentType
            ) {

                $filePath =
                    $newVerificationData[
                        $sessionKey
                    ]
                    ?? "";


                /*
                |--------------------------------------------------------------------------
                | Skip optional document if not uploaded
                |--------------------------------------------------------------------------
                */

                if ($filePath === "") {

                    continue;

                }


                $documentStatement
                    ->bind_param(

                        "iss",

                        $caregiverId,
                        $documentType,
                        $filePath

                    );


                if (
                    !$documentStatement
                        ->execute()
                ) {

                    throw new Exception(
                        "Unable to save " .
                        $documentType .
                        ": " .
                        $documentStatement->error
                    );

                }

            }


            $documentStatement
                ->close();


            /*
            |--------------------------------------------------------------------------
            | Commit database transaction
            |--------------------------------------------------------------------------
            */

            $conn->commit();


            /*
            |--------------------------------------------------------------------------
            | Delete replaced old document files AFTER successful DB save
            |--------------------------------------------------------------------------
            */

            foreach (
                $documents
                as
                $inputName =>
                $document
            ) {

                $oldFile =
                    $verificationData[
                        $inputName
                    ]
                    ?? "";


                $newFile =
                    $newVerificationData[
                        $inputName
                    ]
                    ?? "";


                if (
                    $oldFile !== "" &&
                    $newFile !== "" &&
                    $oldFile !== $newFile
                ) {

                    $oldPath =
                        __DIR__ .
                        "/" .
                        $oldFile;


                    if (
                        is_file($oldPath)
                    ) {

                        @unlink($oldPath);

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Save completion data for success page
            |--------------------------------------------------------------------------
            */

            $newVerificationData[
                "declaration"
            ] = true;


            $newVerificationData[
                "verification_status"
            ] = "Pending";


            $newVerificationData[
                "step_3_complete"
            ] = true;


            $newVerificationData[
                "user_id"
            ] = $userId;


            $newVerificationData[
                "caregiver_id"
            ] = $caregiverId;


            $_SESSION[
                "caregiver_verification"
            ] =
                $newVerificationData;


            /*
            |--------------------------------------------------------------------------
            | Redirect to success page
            |--------------------------------------------------------------------------
            */

            header(
                "Location: caregiver-application-success.php"
            );

            exit;


        } catch (Throwable $exception) {

            /*
            |--------------------------------------------------------------------------
            | Undo database inserts
            |--------------------------------------------------------------------------
            */

            try {

                $conn->rollback();

            } catch (Throwable $ignored) {

            }


            /*
            |--------------------------------------------------------------------------
            | Delete only files newly uploaded during failed submission
            |--------------------------------------------------------------------------
            */

            deleteNewUploads(
                $newlyUploadedFiles
            );


            $error =
                "Registration could not be completed. " .
                $exception->getMessage();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | If validation failed after new uploads, remove those new files
    |--------------------------------------------------------------------------
    */

    if (
        $error !== "" &&
        !empty($newlyUploadedFiles)
    ) {

        deleteNewUploads(
            $newlyUploadedFiles
        );


        /*
        |--------------------------------------------------------------------------
        | Restore old verification paths for display
        |--------------------------------------------------------------------------
        */

        $newVerificationData =
            $verificationData;

    }

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
        Verification | SafeHands
    </title>


    <link
        rel="stylesheet"
        href="assets/css/caregiver-verification.css?v=1"
    >

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="main-header">

    <div class="header-container">


        <a
            href="index.php"
            class="logo"
        >
            SafeHands
        </a>


        <nav class="main-nav">

            <a href="#">
                Find Jobs
            </a>

            <a href="#">
                Resources
            </a>

            <a href="#">
                About Us
            </a>

            <a
                href="register.php"
                class="active"
            >
                Register
            </a>

        </nav>


        <a
            href="login.php"
            class="login-link"
        >
            Login
        </a>


    </div>

</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="page-main">

    <div class="page-container">


        <!-- BREADCRUMB -->

        <div class="breadcrumb">

            <a href="register.php">
                Register
            </a>

            <span>›</span>

            <strong>
                Become a Caregiver
            </strong>

        </div>



        <!-- PAGE HEADING -->

        <section class="page-heading">

            <span class="heading-label">
                CAREGIVER APPLICATION
            </span>

            <h1>
                Become a SafeHands Caregiver
            </h1>

            <p>
                Complete the following steps to apply as a
                verified caregiver and start your professional
                journey with us.
            </p>

        </section>



        <!-- PROGRESS -->

        <section class="progress-container">


            <div class="progress-step completed">

                <div class="step-circle">
                    ✓
                </div>

                <div class="step-info">

                    <small>
                        COMPLETED
                    </small>

                    <strong>
                        Personal Info
                    </strong>

                </div>

            </div>


            <div
                class="progress-line completed-line"
            ></div>


            <div class="progress-step completed">

                <div class="step-circle">
                    ✓
                </div>

                <div class="step-info">

                    <small>
                        COMPLETED
                    </small>

                    <strong>
                        Professional Info
                    </strong>

                </div>

            </div>


            <div
                class="progress-line completed-line"
            ></div>


            <div class="progress-step active">

                <div class="step-circle">
                    3
                </div>

                <div class="step-info">

                    <small>
                        STEP 3
                    </small>

                    <strong>
                        Verification
                    </strong>

                </div>

            </div>


        </section>



        <!-- VERIFICATION FORM -->

        <section class="form-card">


            <?php if ($error !== ""): ?>

                <div class="message error-message">

                    <div class="message-icon">
                        !
                    </div>

                    <div>

                        <strong>
                            Please check your application
                        </strong>

                        <p>
                            <?= htmlspecialchars($error) ?>
                        </p>

                    </div>

                </div>

            <?php endif; ?>



            <form
                action="caregiver-verification.php"
                method="POST"
                enctype="multipart/form-data"
                id="verificationForm"
            >


                <!-- REQUIRED DOCUMENTS -->

                <section
                    class="form-section first-section"
                >


                    <div class="section-heading">


                        <div class="section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path
                                    d="M12 3l7 3v5c0 5-3 8.5-7 10-4-1.5-7-5-7-10V6l7-3z"
                                ></path>

                                <path
                                    d="M9 12l2 2 4-4"
                                ></path>

                            </svg>

                        </div>


                        <div>

                            <h2>
                                Verification Documents
                            </h2>

                            <p>
                                Upload the required documents
                                so SafeHands can verify your
                                caregiver application.
                            </p>

                        </div>


                    </div>



                    <div class="document-category-title">

                        <span>
                            REQUIRED DOCUMENTS
                        </span>

                        <small>
                            *
                        </small>

                    </div>



                    <div class="document-grid">


                        <!-- NIC FRONT -->

                        <div class="document-item">

                            <label>

                                NIC (Front)

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                id="nicFront"
                                name="nic_front"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                                <?= empty(
                                    $verificationData["nic_front"]
                                )
                                    ? "required"
                                    : ""
                                ?>
                            >


                            <label
                                for="nicFront"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        ></rect>

                                        <circle
                                            cx="8"
                                            cy="11"
                                            r="2"
                                        ></circle>

                                        <path
                                            d="M5.5 16c.8-1.8 4.2-1.8 5 0"
                                        ></path>

                                        <path
                                            d="M13 9h5"
                                        ></path>

                                        <path
                                            d="M13 13h5"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        NIC Front Side
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="nicFrontName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "nic_front"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>


                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>



                        <!-- NIC BACK -->

                        <div class="document-item">

                            <label>

                                NIC (Back)

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                id="nicBack"
                                name="nic_back"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                                <?= empty(
                                    $verificationData["nic_back"]
                                )
                                    ? "required"
                                    : ""
                                ?>
                            >


                            <label
                                for="nicBack"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <rect
                                            x="3"
                                            y="5"
                                            width="18"
                                            height="14"
                                            rx="2"
                                        ></rect>

                                        <path
                                            d="M6 9h12"
                                        ></path>

                                        <path
                                            d="M6 13h8"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        NIC Back Side
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="nicBackName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "nic_back"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>



                        <!-- QUALIFICATION -->

                        <div class="document-item">

                            <label>

                                Qualification Certificate

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                id="qualificationCertificate"
                                name="qualification_certificate"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                                <?= empty(
                                    $verificationData[
                                        "qualification_certificate"
                                    ]
                                )
                                    ? "required"
                                    : ""
                                ?>
                            >


                            <label
                                for="qualificationCertificate"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path
                                            d="M3 9l9-5 9 5-9 5-9-5z"
                                        ></path>

                                        <path
                                            d="M7 12v4c3 2 7 2 10 0v-4"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        Qualification Certificate
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="qualificationCertificateName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "qualification_certificate"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>



                        <!-- POLICE CLEARANCE -->

                        <div class="document-item">

                            <label>

                                Police Clearance

                                <span class="required">
                                    *
                                </span>

                            </label>


                            <input
                                type="file"
                                id="policeClearance"
                                name="police_clearance"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                                <?= empty(
                                    $verificationData[
                                        "police_clearance"
                                    ]
                                )
                                    ? "required"
                                    : ""
                                ?>
                            >


                            <label
                                for="policeClearance"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path
                                            d="M6 3h9l4 4v14H6z"
                                        ></path>

                                        <path
                                            d="M15 3v5h5"
                                        ></path>

                                        <path
                                            d="M9 13h6"
                                        ></path>

                                        <path
                                            d="M9 17h6"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        Police Clearance
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="policeClearanceName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "police_clearance"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>


                    </div>


                </section>



                <!-- OPTIONAL DOCUMENTS -->

                <section class="form-section">


                    <div class="section-heading">

                        <div class="section-icon">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >

                                <path
                                    d="M6 3h9l4 4v14H6z"
                                ></path>

                                <path
                                    d="M15 3v5h5"
                                ></path>

                                <path
                                    d="M9 13h6"
                                ></path>

                                <path
                                    d="M9 17h4"
                                ></path>

                            </svg>

                        </div>


                        <div>

                            <h2>
                                Optional Documents
                            </h2>

                            <p>
                                Add supporting documents that may
                                strengthen your caregiver profile.
                            </p>

                        </div>

                    </div>



                    <div
                        class="document-category-title optional-title"
                    >

                        <span>
                            SUPPORTING DOCUMENTS
                        </span>

                        <small>
                            OPTIONAL
                        </small>

                    </div>



                    <div class="document-grid">


                        <!-- FIRST AID -->

                        <div class="document-item">

                            <label>
                                First Aid Certificate
                            </label>


                            <input
                                type="file"
                                id="firstAid"
                                name="first_aid_certificate"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                            >


                            <label
                                for="firstAid"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path
                                            d="M9 3h6v6h6v6h-6v6H9v-6H3V9h6z"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        First Aid Certificate
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="firstAidName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "first_aid_certificate"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>



                        <!-- EXPERIENCE LETTER -->

                        <div class="document-item">

                            <label>
                                Experience Letter
                            </label>


                            <input
                                type="file"
                                id="experienceLetter"
                                name="experience_letter"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                            >


                            <label
                                for="experienceLetter"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                    >

                                        <path
                                            d="M6 3h9l4 4v14H6z"
                                        ></path>

                                        <path
                                            d="M15 3v5h5"
                                        ></path>

                                        <path
                                            d="M9 12h6"
                                        ></path>

                                        <path
                                            d="M9 16h6"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        Experience Letter
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="experienceLetterName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "experience_letter"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>



                        <!-- MEDICAL FITNESS -->

                        <div class="document-item">

                            <label>
                                Medical Fitness Certificate
                            </label>


                            <input
                                type="file"
                                id="medicalFitness"
                                name="medical_fitness"
                                accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf"
                                class="document-file-input"
                            >


                            <label
                                for="medicalFitness"
                                class="document-upload-box"
                            >

                                <div class="document-icon">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.7"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path
                                            d="M12 3l7 3v5c0 5-3 8.5-7 10-4-1.5-7-5-7-10V6l7-3z"
                                        ></path>

                                        <path
                                            d="M9 12h6"
                                        ></path>

                                        <path
                                            d="M12 9v6"
                                        ></path>

                                    </svg>

                                </div>


                                <div class="document-upload-info">

                                    <strong>
                                        Medical Fitness Certificate
                                    </strong>

                                    <span
                                        class="document-file-name"
                                        id="medicalFitnessName"
                                    >

                                        <?php if (
                                            !empty(
                                                $verificationData[
                                                    "medical_fitness"
                                                ]
                                            )
                                        ): ?>

                                            File uploaded

                                        <?php else: ?>

                                            No file selected

                                        <?php endif; ?>

                                    </span>

                                </div>


                                <span class="upload-button">
                                    Upload File
                                </span>

                            </label>


                            <small class="document-help">
                                JPG, PNG or PDF • Maximum 5MB
                            </small>

                        </div>


                    </div>


                </section>



                <!-- DECLARATION -->

                <section class="form-section">

                    <div class="declaration-box">

                        <label
                            for="declaration"
                            class="declaration-label"
                        >

                            <input
                                type="checkbox"
                                id="declaration"
                                name="declaration"
                                value="1"
                                required
                            >


                            <span class="declaration-content">

                                <strong>
                                    Document Declaration
                                </strong>

                                <span>
                                    I confirm that all uploaded
                                    documents are genuine, accurate
                                    and belong to me. I understand
                                    that SafeHands may review these
                                    documents before approving my
                                    caregiver account.
                                </span>

                            </span>

                        </label>

                    </div>

                </section>



                <!-- BUTTONS -->

                <div class="form-actions">

                    <a
                        href="caregiver-professional.php"
                        class="cancel-button"
                    >
                        Back
                    </a>


                    <button
                        type="submit"
                        class="next-button submit-application-button"
                    >

                        Submit Application

                        <span>
                            →
                        </span>

                    </button>

                </div>


            </form>


        </section>



        <!-- WHY JOIN SAFEHANDS -->

        <section class="why-card">


            <div class="why-content">

                <span class="why-label">
                    YOUR CAREER WITH SAFEHANDS
                </span>

                <h2>
                    Why join SafeHands?
                </h2>

                <p class="why-intro">
                    Join a trusted community built to support
                    professional caregivers and connect you with
                    families who need compassionate care.
                </p>



                <div class="benefit-list">


                    <div class="benefit-item">

                        <span class="check-circle">
                            ✓
                        </span>

                        <div>

                            <strong>
                                Secure payments
                            </strong>

                            <p>
                                Receive payments safely for
                                completed caregiving services.
                            </p>

                        </div>

                    </div>



                    <div class="benefit-item">

                        <span class="check-circle">
                            ✓
                        </span>

                        <div>

                            <strong>
                                Flexible availability
                            </strong>

                            <p>
                                Manage your availability and accept
                                caregiving jobs that suit your schedule.
                            </p>

                        </div>

                    </div>



                    <div class="benefit-item">

                        <span class="check-circle">
                            ✓
                        </span>

                        <div>

                            <strong>
                                Build a trusted profile
                            </strong>

                            <p>
                                Showcase your qualifications,
                                experience and verified caregiver status.
                            </p>

                        </div>

                    </div>


                </div>


            </div>



            <div class="why-image">

                <img
                    src="assets/images/malefeamle_caregiver.jpeg"
                    alt="Professional SafeHands caregivers"
                >

                <div class="image-overlay"></div>


                <div class="image-badge">

                    <span class="badge-icon">
                        ✓
                    </span>

                    <div>

                        <strong>
                            Join our caregiver community
                        </strong>

                        <small>
                            Professional • Trusted • Compassionate
                        </small>

                    </div>

                </div>

            </div>


        </section>



        <!-- PRIVACY -->

        <div class="privacy-note">

            <span class="privacy-icon">
                ✓
            </span>

            <p>

                <strong>
                    Your information is protected.
                </strong>

                Your personal information and verification
                documents are used only for SafeHands account
                registration and caregiver verification.

            </p>

        </div>


    </div>

</main>



<!-- FOOTER -->

<footer class="main-footer">


    <div>

        <a
            href="index.php"
            class="footer-logo"
        >
            SafeHands
        </a>

        <p>
            © 2026 SafeHands Healthcare Services.
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
            Help Center
        </a>

        <a href="#">
            Contact Support
        </a>

    </div>


</footer>



<!-- FILE NAME JAVASCRIPT -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const fileInputs = [

            {
                input: "nicFront",
                name: "nicFrontName"
            },

            {
                input: "nicBack",
                name: "nicBackName"
            },

            {
                input: "qualificationCertificate",
                name: "qualificationCertificateName"
            },

            {
                input: "policeClearance",
                name: "policeClearanceName"
            },

            {
                input: "firstAid",
                name: "firstAidName"
            },

            {
                input: "experienceLetter",
                name: "experienceLetterName"
            },

            {
                input: "medicalFitness",
                name: "medicalFitnessName"
            }

        ];


        fileInputs.forEach(
            function (item) {

                const input =
                    document.getElementById(
                        item.input
                    );

                const nameElement =
                    document.getElementById(
                        item.name
                    );


                if (
                    !input ||
                    !nameElement
                ) {
                    return;
                }


                input.addEventListener(
                    "change",
                    function () {

                        const file =
                            this.files[0];


                        if (!file) {

                            nameElement.textContent =
                                "No file selected";

                            return;

                        }


                        if (
                            file.size >
                            5 * 1024 * 1024
                        ) {

                            alert(
                                "Please select a file smaller than 5MB."
                            );

                            this.value = "";

                            nameElement.textContent =
                                "No file selected";

                            return;

                        }


                        const allowedTypes = [

                            "image/jpeg",

                            "image/png",

                            "application/pdf"

                        ];


                        if (
                            !allowedTypes.includes(
                                file.type
                            )
                        ) {

                            alert(
                                "Please select a JPG, PNG or PDF file."
                            );

                            this.value = "";

                            nameElement.textContent =
                                "No file selected";

                            return;

                        }


                        nameElement.textContent =
                            file.name;

                    }

                );

            }

        );

    }

);

</script>


</body>

</html>