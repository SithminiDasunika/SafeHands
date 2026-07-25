<?php

session_start();

/*
|--------------------------------------------------------------------------
| SAFEHANDS CAREGIVER REGISTRATION
| STEP 2 - PROFESSIONAL INFORMATION
|--------------------------------------------------------------------------
*/

$error = "";


/*
|--------------------------------------------------------------------------
| Protect Step 2
|--------------------------------------------------------------------------
| Step 1 must be completed before accessing this page.
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION["caregiver_registration"]) ||
    empty($_SESSION["caregiver_registration"]["step_1_complete"])
) {

    header("Location: caregiver-register.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Restore previously entered Step 2 data
|--------------------------------------------------------------------------
*/

$professionalData =
    $_SESSION["caregiver_professional"] ?? [];


/*
|--------------------------------------------------------------------------
| Process form when NEXT is clicked
|--------------------------------------------------------------------------
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /*
    |--------------------------------------------------------------------------
    | Get submitted values
    |--------------------------------------------------------------------------
    */

    $qualification =
        trim($_POST["qualification"] ?? "");

    $experience =
        trim($_POST["experience"] ?? "");

    $certifications =
        trim($_POST["certifications"] ?? "");

    $languages =
        trim($_POST["languages"] ?? "");

    $serviceAreas =
        trim($_POST["service_areas"] ?? "");

    $dailyRate =
        trim($_POST["daily_rate"] ?? "");

    $biography =
        trim($_POST["biography"] ?? "");


    /*
    |--------------------------------------------------------------------------
    | Keep values if validation fails
    |--------------------------------------------------------------------------
    */

    $professionalData = [

        "qualification" =>
            $qualification,

        "experience" =>
            $experience,

        "certifications" =>
            $certifications,

        "languages" =>
            $languages,

        "service_areas" =>
            $serviceAreas,

        "daily_rate" =>
            $dailyRate,

        "biography" =>
            $biography,

        "profile_photo" =>
            $_SESSION[
                "caregiver_professional"
            ]["profile_photo"]
            ?? ""

    ];


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (
        $qualification === "" ||
        $experience === "" ||
        $certifications === "" ||
        $languages === "" ||
        $serviceAreas === "" ||
        $biography === ""
    ) {

        $error =
            "Please complete all required fields.";

    } elseif (
        !is_numeric($experience) ||
        (int)$experience < 0 ||
        (int)$experience > 60
    ) {

        $error =
            "Please enter a valid number of years of experience.";

    } elseif (
        $dailyRate !== "" &&
        (
            !is_numeric($dailyRate) ||
            (float)$dailyRate < 0
        )
    ) {

        $error =
            "Please enter a valid expected daily rate.";

    }


    /*
    |--------------------------------------------------------------------------
    | Profile Photo Upload
    |--------------------------------------------------------------------------
    */

    if (
        $error === "" &&
        isset($_FILES["profile_photo"]) &&
        $_FILES["profile_photo"]["error"]
            !== UPLOAD_ERR_NO_FILE
    ) {

        /*
        | Check upload error
        */

        if (
            $_FILES["profile_photo"]["error"]
            !== UPLOAD_ERR_OK
        ) {

            $error =
                "There was a problem uploading your profile photo.";

        }

        /*
        | Maximum file size = 2MB
        */

        elseif (
            $_FILES["profile_photo"]["size"]
            > 2 * 1024 * 1024
        ) {

            $error =
                "Profile photo must be 2MB or smaller.";

        }

        else {

            /*
            |--------------------------------------------------------------------------
            | Allowed image types
            |--------------------------------------------------------------------------
            */

            $allowedTypes = [

                "image/jpeg" => "jpg",

                "image/png" => "png"

            ];


            $fileInfo =
                finfo_open(
                    FILEINFO_MIME_TYPE
                );


            $mimeType =
                finfo_file(
                    $fileInfo,
                    $_FILES[
                        "profile_photo"
                    ]["tmp_name"]
                );


            finfo_close(
                $fileInfo
            );


            if (
                !isset(
                    $allowedTypes[
                        $mimeType
                    ]
                )
            ) {

                $error =
                    "Profile photo must be a JPG or PNG image.";

            } else {

                /*
                |--------------------------------------------------------------------------
                | Upload folder
                |--------------------------------------------------------------------------
                */

                $uploadDirectory =
                    __DIR__ .
                    "/uploads/caregiver-profiles/";


                if (
                    !is_dir(
                        $uploadDirectory
                    )
                ) {

                    if (
                        !mkdir(
                            $uploadDirectory,
                            0755,
                            true
                        )
                    ) {

                        $error =
                            "Unable to create the profile photo upload folder.";

                    }

                }


                /*
                |--------------------------------------------------------------------------
                | Save photo
                |--------------------------------------------------------------------------
                */

                if ($error === "") {

                    $extension =
                        $allowedTypes[
                            $mimeType
                        ];


                    $fileName =
                        "caregiver_" .
                        bin2hex(
                            random_bytes(8)
                        ) .
                        "." .
                        $extension;


                    $destination =
                        $uploadDirectory .
                        $fileName;


                    if (
                        move_uploaded_file(
                            $_FILES[
                                "profile_photo"
                            ]["tmp_name"],
                            $destination
                        )
                    ) {

                        /*
                        | Delete old photo if replacing
                        */

                        $oldPhoto =
                            $_SESSION[
                                "caregiver_professional"
                            ]["profile_photo"]
                            ?? "";


                        if ($oldPhoto !== "") {

                            $oldPhotoPath =
                                __DIR__ .
                                "/" .
                                $oldPhoto;


                            if (
                                is_file(
                                    $oldPhotoPath
                                )
                            ) {

                                @unlink(
                                    $oldPhotoPath
                                );

                            }

                        }


                        /*
                        | Store relative path
                        */

                        $professionalData[
                            "profile_photo"
                        ] =
                            "uploads/caregiver-profiles/" .
                            $fileName;

                    } else {

                        $error =
                            "Unable to save your profile photo.";

                    }

                }

            }

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Save Step 2 into PHP Session
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        $_SESSION[
            "caregiver_professional"
        ] = [

            "qualification" =>
                $qualification,

            "experience" =>
                (int)$experience,

            "certifications" =>
                $certifications,

            "languages" =>
                $languages,

            "service_areas" =>
                $serviceAreas,

            "daily_rate" =>
                $dailyRate,

            "biography" =>
                $biography,

            "profile_photo" =>
                $professionalData[
                    "profile_photo"
                ]
                ?? "",

            "step_2_complete" =>
                true

        ];


        /*
        |--------------------------------------------------------------------------
        | Redirect to Step 3
        |--------------------------------------------------------------------------
        */

        header(
            "Location: caregiver-verification.php"
        );

        exit;

    }

}


/*
|--------------------------------------------------------------------------
| Qualification Options
|--------------------------------------------------------------------------
*/

$qualifications = [

    "Caregiving Certificate",

    "NVQ Level 3 - Caregiver",

    "NVQ Level 4 - Caregiver",

    "Diploma in Caregiving",

    "Diploma in Nursing",

    "BSc Nursing",

    "First Aid / Healthcare Training",

    "Other"

];

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
        Professional Information | SafeHands
    </title>


    <!--
        Step 2 CSS.
        This CSS imports caregiver-register.css,
        so Step 1 and Step 2 use the same design.
    -->

    <link
        rel="stylesheet"
        href="assets/css/caregiver-professional.css?v=3"
    >

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="main-header">

    <div class="header-container">


        <!-- LOGO -->

        <a
            href="index.php"
            class="logo"
        >

            SafeHands

        </a>


        <!-- NAVIGATION -->

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


        <!-- LOGIN -->

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


        <!-- =================================================
             BREADCRUMB
        ================================================== -->

        <div class="breadcrumb">

            <a href="register.php">

                Register

            </a>

            <span>

                ›

            </span>

            <strong>

                Become a Caregiver

            </strong>

        </div>



        <!-- =================================================
             PAGE HEADING
        ================================================== -->

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



        <!-- =================================================
             PROGRESS
        ================================================== -->

        <section class="progress-container">


            <!-- STEP 1 - COMPLETED -->

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



            <!-- STEP 2 - ACTIVE -->

            <div class="progress-step active">


                <div class="step-circle">

                    2

                </div>


                <div class="step-info">

                    <small>

                        STEP 2

                    </small>

                    <strong>

                        Professional Info

                    </strong>

                </div>


            </div>



            <div class="progress-line"></div>



            <!-- STEP 3 -->

            <div class="progress-step">


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



        <!-- =================================================
             PROFESSIONAL FORM CARD
        ================================================== -->

        <section class="form-card">


            <!-- ERROR MESSAGE -->

            <?php if ($error !== ""): ?>


                <div class="message error-message">


                    <div class="message-icon">

                        !

                    </div>


                    <div>


                        <strong>

                            Please check your information

                        </strong>


                        <p>

                            <?= htmlspecialchars($error) ?>

                        </p>


                    </div>


                </div>


            <?php endif; ?>



            <!-- =================================================
                 FORM
            ================================================== -->

            <form

                action="caregiver-professional.php"

                method="POST"

                enctype="multipart/form-data"

                id="professionalForm"

            >


                <!-- =================================================
                     PROFESSIONAL DETAILS
                ================================================== -->

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

                                <rect
                                    x="3"
                                    y="7"
                                    width="18"
                                    height="13"
                                    rx="2"
                                ></rect>

                                <path
                                    d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
                                ></path>

                                <path
                                    d="M3 12h18"
                                ></path>

                            </svg>


                        </div>


                        <div>


                            <h2>

                                Professional Details

                            </h2>


                            <p>

                                Tell us about your qualifications,
                                experience and caregiving skills.

                            </p>


                        </div>


                    </div>



                    <div class="form-grid">


                        <!-- HIGHEST QUALIFICATION -->

                        <div class="form-group">


                            <label for="qualification">

                                Highest Qualification

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select

                                id="qualification"

                                name="qualification"

                                required

                            >


                                <option

                                    value=""

                                    disabled

                                    <?= empty(
                                        $professionalData[
                                            "qualification"
                                        ]
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Select Qualification

                                </option>



                                <?php foreach (
                                    $qualifications
                                    as
                                    $qualificationOption
                                ): ?>


                                    <option

                                        value="<?=
                                            htmlspecialchars(
                                                $qualificationOption
                                            )
                                        ?>"

                                        <?= (
                                            (
                                                $professionalData[
                                                    "qualification"
                                                ]
                                                ?? ""
                                            )
                                            ===
                                            $qualificationOption
                                        )
                                            ? "selected"
                                            : ""
                                        ?>

                                    >

                                        <?=
                                            htmlspecialchars(
                                                $qualificationOption
                                            )
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>



                        <!-- YEARS OF EXPERIENCE -->

                        <div class="form-group">


                            <label for="experience">

                                Years of Experience

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="number"

                                id="experience"

                                name="experience"

                                min="0"

                                max="60"

                                placeholder="e.g. 5"

                                value="<?=
                                    htmlspecialchars(
                                        $professionalData[
                                            "experience"
                                        ]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- CERTIFICATIONS -->

                        <div class="form-group">


                            <label for="certifications">

                                Professional Certifications

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="text"

                                id="certifications"

                                name="certifications"

                                placeholder="e.g. CPR, First Aid"

                                value="<?=
                                    htmlspecialchars(
                                        $professionalData[
                                            "certifications"
                                        ]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                            <small class="field-help">

                                Separate multiple certifications
                                using commas.

                            </small>


                        </div>



                        <!-- LANGUAGES -->

                        <div class="form-group">


                            <label for="languages">

                                Languages Spoken

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="text"

                                id="languages"

                                name="languages"

                                placeholder="e.g. Sinhala, English, Tamil"

                                value="<?=
                                    htmlspecialchars(
                                        $professionalData[
                                            "languages"
                                        ]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                            <small class="field-help">

                                Separate multiple languages
                                using commas.

                            </small>


                        </div>



                        <!-- SERVICE AREAS -->

                        <div class="form-group">


                            <label for="serviceAreas">

                                Service Areas / Districts

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="text"

                                id="serviceAreas"

                                name="service_areas"

                                placeholder="e.g. Colombo, Gampaha"

                                value="<?=
                                    htmlspecialchars(
                                        $professionalData[
                                            "service_areas"
                                        ]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                            <small class="field-help">

                                Enter the areas where you can
                                provide caregiving services.

                            </small>


                        </div>



                        <!-- DAILY RATE -->

                        <div class="form-group">


                            <label for="dailyRate">

                                Expected Daily Rate

                            </label>


                            <div class="rate-input-wrapper">


                                <span class="currency-prefix">

                                    LKR

                                </span>


                                <input

                                    type="number"

                                    id="dailyRate"

                                    name="daily_rate"

                                    min="0"

                                    step="100"

                                    placeholder="e.g. 5000"

                                    value="<?=
                                        htmlspecialchars(
                                            $professionalData[
                                                "daily_rate"
                                            ]
                                            ?? ""
                                        )
                                    ?>"

                                >


                            </div>


                            <small class="field-help">

                                Optional. You can update this later.

                            </small>


                        </div>


                    </div>


                </section>



                <!-- =================================================
                     PROFESSIONAL BIOGRAPHY
                ================================================== -->

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
                                    d="M4 4h16v16H4z"
                                ></path>

                                <path
                                    d="M8 9h8"
                                ></path>

                                <path
                                    d="M8 13h8"
                                ></path>

                                <path
                                    d="M8 17h5"
                                ></path>

                            </svg>


                        </div>


                        <div>


                            <h2>

                                Professional Biography

                            </h2>


                            <p>

                                Introduce yourself to families
                                looking for trusted caregiving support.

                            </p>


                        </div>


                    </div>



                    <div class="form-grid">


                        <div
                            class="form-group full-width"
                        >


                            <label for="biography">

                                Short Professional Biography

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <textarea

                                id="biography"

                                name="biography"

                                rows="5"

                                maxlength="1000"

                                placeholder="Tell families about your caregiving experience, skills, strengths and passion for providing care..."

                                required

                            ><?= htmlspecialchars(
                                $professionalData[
                                    "biography"
                                ]
                                ?? ""
                            ) ?></textarea>


                            <div class="biography-footer">


                                <small class="field-help">

                                    Briefly describe your experience
                                    and professional strengths.

                                </small>


                                <small
                                    class="character-counter"
                                    id="bioCounter"
                                >

                                    0 / 1000

                                </small>


                            </div>


                        </div>


                    </div>


                </section>



                <!-- =================================================
                     PROFILE PHOTO
                ================================================== -->

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

                                <circle
                                    cx="12"
                                    cy="8"
                                    r="4"
                                ></circle>

                                <path
                                    d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"
                                ></path>

                            </svg>


                        </div>


                        <div>


                            <h2>

                                Profile Photo

                            </h2>


                            <p>

                                Add a clear and professional photo
                                for your caregiver profile.

                            </p>


                        </div>


                    </div>



                    <div class="profile-photo-area">


                        <!-- PHOTO PREVIEW -->

                        <div class="photo-preview-wrapper">


                            <div
                                class="photo-preview"
                                id="photoPreview"
                            >


                                <?php if (
                                    !empty(
                                        $professionalData[
                                            "profile_photo"
                                        ]
                                    )
                                ): ?>


                                    <img

                                        src="<?=
                                            htmlspecialchars(
                                                $professionalData[
                                                    "profile_photo"
                                                ]
                                            )
                                        ?>"

                                        alt="Caregiver profile photo"

                                        id="previewImage"

                                    >


                                <?php else: ?>


                                    <div
                                        class="photo-placeholder"
                                        id="photoPlaceholder"
                                    >


                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        >

                                            <circle
                                                cx="12"
                                                cy="8"
                                                r="4"
                                            ></circle>

                                            <path
                                                d="M4 21c0-4.4 3.6-8 8-8s8 3.6 8 8"
                                            ></path>

                                        </svg>


                                    </div>


                                <?php endif; ?>



                                <div class="photo-camera-badge">


                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    >

                                        <path
                                            d="M4 7h3l2-3h6l2 3h3a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V9a2 2 0 0 1 2-2z"
                                        ></path>

                                        <circle
                                            cx="12"
                                            cy="13"
                                            r="4"
                                        ></circle>

                                    </svg>


                                </div>


                            </div>


                        </div>



                        <!-- PHOTO INFORMATION -->

                        <div class="photo-upload-content">


                            <h3>

                                Upload your profile photo

                            </h3>


                            <p>

                                Choose a recent photo where your face
                                is clearly visible. This photo will
                                appear on your caregiver profile after
                                your account is verified.

                            </p>



                            <div class="photo-points">


                                <span>

                                    <b>✓</b>
                                    Clear face

                                </span>


                                <span>

                                    <b>✓</b>
                                    Good lighting

                                </span>


                                <span>

                                    <b>✓</b>
                                    Professional appearance

                                </span>


                            </div>



                            <!-- HIDDEN FILE INPUT -->

                            <input

                                type="file"

                                id="profilePhoto"

                                name="profile_photo"

                                accept=".jpg,.jpeg,.png,image/jpeg,image/png"

                                class="profile-file-input"

                            >



                            <div class="photo-upload-row">


                                <label

                                    for="profilePhoto"

                                    class="photo-select-button"

                                >

                                    Choose Photo

                                </label>



                                <span
                                    class="photo-file-name"
                                    id="photoFileName"
                                >

                                    <?php if (
                                        !empty(
                                            $professionalData[
                                                "profile_photo"
                                            ]
                                        )
                                    ): ?>

                                        Photo uploaded

                                    <?php else: ?>

                                        No file selected

                                    <?php endif; ?>

                                </span>


                            </div>



                            <small class="photo-format">

                                JPG or PNG • Maximum 2MB

                            </small>


                        </div>


                    </div>



                    <!-- PHOTO TIP -->

                    <div class="photo-tip-box">


                        <span class="photo-tip-icon">

                            i

                        </span>


                        <p>


                            <strong>

                                Photo tip:

                            </strong>


                            Use a recent front-facing photo with
                            a simple background. Avoid filters,
                            sunglasses and group photos.


                        </p>


                    </div>


                </section>



                <!-- =================================================
                     BUTTONS
                     SAME STRUCTURE AS STEP 1
                ================================================== -->

                <div class="form-actions">


                    <a

                        href="caregiver-register.php"

                        class="cancel-button"

                    >

                        Back

                    </a>


                    <button

                        type="submit"

                        class="next-button"

                        id="nextButton"

                    >

                        Next

                        <span>

                            →

                        </span>

                    </button>


                </div>


            </form>


        </section>



        <!-- =================================================
             WHY JOIN SAFEHANDS
             SAME DESIGN AS STEP 1
        ================================================== -->

        <section class="why-card">


            <!-- LEFT CONTENT -->

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


                    <!-- BENEFIT 1 -->

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



                    <!-- BENEFIT 2 -->

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



                    <!-- BENEFIT 3 -->

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



            <!-- RIGHT IMAGE -->

            <div class="why-image">


                <img

                    src="assets/images/malefeamle_caregiver.jpeg"

                    alt="Professional male and female SafeHands caregivers"

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



        <!-- =================================================
             PRIVACY NOTE
        ================================================== -->

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



<!-- =========================================================
     FOOTER
========================================================= -->

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



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
        |--------------------------------------------------------------------------
        | Biography Character Counter
        |--------------------------------------------------------------------------
        */

        const biography =
            document.getElementById(
                "biography"
            );

        const bioCounter =
            document.getElementById(
                "bioCounter"
            );


        function updateBioCounter() {

            if (
                biography &&
                bioCounter
            ) {

                bioCounter.textContent =
                    biography.value.length +
                    " / 1000";

            }

        }


        if (biography) {

            biography.addEventListener(
                "input",
                updateBioCounter
            );

            updateBioCounter();

        }



        /*
        |--------------------------------------------------------------------------
        | Profile Photo Preview
        |--------------------------------------------------------------------------
        */

        const photoInput =
            document.getElementById(
                "profilePhoto"
            );

        const photoPreview =
            document.getElementById(
                "photoPreview"
            );

        const photoFileName =
            document.getElementById(
                "photoFileName"
            );


        if (
            photoInput &&
            photoPreview &&
            photoFileName
        ) {

            photoInput.addEventListener(
                "change",
                function () {

                    const file =
                        this.files[0];


                    if (!file) {

                        return;

                    }


                    /*
                    | Validate image type
                    */

                    const allowedTypes = [

                        "image/jpeg",

                        "image/png"

                    ];


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        alert(
                            "Please select a JPG or PNG image."
                        );

                        this.value = "";

                        photoFileName.textContent =
                            "No file selected";

                        return;

                    }


                    /*
                    | Validate maximum 2MB
                    */

                    if (
                        file.size >
                        2 * 1024 * 1024
                    ) {

                        alert(
                            "Profile photo must be 2MB or smaller."
                        );

                        this.value = "";

                        photoFileName.textContent =
                            "No file selected";

                        return;

                    }


                    /*
                    | Display filename
                    */

                    photoFileName.textContent =
                        file.name;


                    /*
                    | Create image preview
                    */

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            const oldImage =
                                photoPreview.querySelector(
                                    "#previewImage"
                                );

                            const placeholder =
                                photoPreview.querySelector(
                                    "#photoPlaceholder"
                                );


                            if (oldImage) {

                                oldImage.remove();

                            }


                            if (placeholder) {

                                placeholder.remove();

                            }


                            const image =
                                document.createElement(
                                    "img"
                                );


                            image.src =
                                event.target.result;

                            image.id =
                                "previewImage";

                            image.alt =
                                "Selected profile photo";


                            photoPreview.insertBefore(
                                image,
                                photoPreview.firstChild
                            );

                        };


                    reader.readAsDataURL(
                        file
                    );

                }
            );

        }

    }
);

</script>


</body>

</html>