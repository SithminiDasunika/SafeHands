<?php

session_start();

/*
|--------------------------------------------------------------------------
| SAFEHANDS CAREGIVER REGISTRATION
| STEP 1 - PERSONAL INFORMATION
|--------------------------------------------------------------------------
*/

$error = "";


/*
|--------------------------------------------------------------------------
| Restore previously entered/session data
|--------------------------------------------------------------------------
| This allows the data to remain if the caregiver goes to Step 2
| and then comes back to Step 1.
|--------------------------------------------------------------------------
*/

$formData = $_SESSION["caregiver_registration"] ?? [];


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

    $fullName = trim($_POST["full_name"] ?? "");
    $nic = trim($_POST["nic"] ?? "");
    $dob = trim($_POST["dob"] ?? "");
    $gender = trim($_POST["gender"] ?? "");

    $phone = trim($_POST["phone"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $address = trim($_POST["address"] ?? "");
    $district = trim($_POST["district"] ?? "");

    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";


    /*
    |--------------------------------------------------------------------------
    | Keep entered values if validation fails
    |--------------------------------------------------------------------------
    */

    $formData = [

        "full_name" => $fullName,

        "nic" => $nic,

        "dob" => $dob,

        "gender" => $gender,

        "phone" => $phone,

        "email" => $email,

        "address" => $address,

        "district" => $district

    ];


    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    if (
        $fullName === "" ||
        $nic === "" ||
        $dob === "" ||
        $gender === "" ||
        $phone === "" ||
        $email === "" ||
        $address === "" ||
        $district === "" ||
        $password === "" ||
        $confirmPassword === ""
    ) {

        $error = "Please complete all required fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email address.";

    } elseif (strlen($password) < 8) {

        $error =
            "Password must contain at least 8 characters.";

    } elseif ($password !== $confirmPassword) {

        $error = "Passwords do not match.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | Save Step 1 temporarily in PHP Session
        |--------------------------------------------------------------------------
        | We do NOT insert into the database yet.
        |
        | Step 1 → Session
        | Step 2 → Session
        | Step 3 → Verification
        | Final Submit → Database
        |--------------------------------------------------------------------------
        */

        $_SESSION["caregiver_registration"] = [

            "full_name" => $fullName,

            "nic" => $nic,

            "dob" => $dob,

            "gender" => $gender,

            "phone" => $phone,

            "email" => $email,

            "address" => $address,

            "district" => $district,

            /*
            | Never store the plain password.
            | Store only the secure password hash.
            */

            "password_hash" => password_hash(
                $password,
                PASSWORD_DEFAULT
            ),

            /*
            | Step 2 uses this to confirm
            | Step 1 has been completed.
            */

            "step_1_complete" => true

        ];


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO STEP 2
        |--------------------------------------------------------------------------
        */

        header("Location: caregiver-professional.php");

        exit;

    }

}


/*
|--------------------------------------------------------------------------
| Sri Lankan Districts
|--------------------------------------------------------------------------
*/

$districts = [

    "Ampara",
    "Anuradhapura",
    "Badulla",
    "Batticaloa",
    "Colombo",
    "Galle",
    "Gampaha",
    "Hambantota",
    "Jaffna",
    "Kalutara",
    "Kandy",
    "Kegalle",
    "Kilinochchi",
    "Kurunegala",
    "Mannar",
    "Matale",
    "Matara",
    "Monaragala",
    "Mullaitivu",
    "Nuwara Eliya",
    "Polonnaruwa",
    "Puttalam",
    "Ratnapura",
    "Trincomalee",
    "Vavuniya"

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
        Become a Caregiver | SafeHands
    </title>


    <!-- CAREGIVER REGISTRATION CSS -->

    <link
        rel="stylesheet"
        href="assets/css/caregiver-register.css?v=2"
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


            <!-- STEP 1 -->

            <div class="progress-step active">


                <div class="step-circle">

                    1

                </div>


                <div class="step-info">

                    <small>

                        STEP 1

                    </small>

                    <strong>

                        Personal Info

                    </strong>

                </div>


            </div>



            <div class="progress-line"></div>



            <!-- STEP 2 -->

            <div class="progress-step">


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
             REGISTRATION FORM CARD
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

                action="caregiver-register.php"

                method="POST"

                id="caregiverForm"

            >


                <!-- =================================================
                     PERSONAL DETAILS
                ================================================== -->

                <section class="form-section first-section">


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

                                Personal Details

                            </h2>


                            <p>

                                Enter your basic personal information.

                            </p>


                        </div>


                    </div>



                    <div class="form-grid">


                        <!-- FULL NAME -->

                        <div class="form-group">


                            <label for="fullName">

                                Full Name

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="text"

                                id="fullName"

                                name="full_name"

                                placeholder="e.g. Anjali Perera"

                                value="<?=
                                    htmlspecialchars(
                                        $formData["full_name"]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- NIC -->

                        <div class="form-group">


                            <label for="nic">

                                NIC Number

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="text"

                                id="nic"

                                name="nic"

                                placeholder="e.g. 200012345678"

                                value="<?=
                                    htmlspecialchars(
                                        $formData["nic"]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- DATE OF BIRTH -->

                        <div class="form-group">


                            <label for="dob">

                                Date of Birth

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="date"

                                id="dob"

                                name="dob"

                                value="<?=
                                    htmlspecialchars(
                                        $formData["dob"]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- GENDER -->

                        <div class="form-group">


                            <label for="gender">

                                Gender

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select

                                id="gender"

                                name="gender"

                                required

                            >


                                <option

                                    value=""

                                    disabled

                                    <?= empty(
                                        $formData["gender"]
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Select Gender

                                </option>



                                <option

                                    value="Male"

                                    <?= (
                                        (
                                            $formData["gender"]
                                            ?? ""
                                        )
                                        === "Male"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Male

                                </option>



                                <option

                                    value="Female"

                                    <?= (
                                        (
                                            $formData["gender"]
                                            ?? ""
                                        )
                                        === "Female"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Female

                                </option>



                                <option

                                    value="Other"

                                    <?= (
                                        (
                                            $formData["gender"]
                                            ?? ""
                                        )
                                        === "Other"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Other

                                </option>



                                <option

                                    value="Prefer not to say"

                                    <?= (
                                        (
                                            $formData["gender"]
                                            ?? ""
                                        )
                                        === "Prefer not to say"
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Prefer not to say

                                </option>


                            </select>


                        </div>


                    </div>


                </section>



                <!-- =================================================
                     CONTACT & ADDRESS
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
                                    d="M3 10.5L12 3l9 7.5"
                                ></path>

                                <path
                                    d="M5 9.5V21h14V9.5"
                                ></path>

                                <path
                                    d="M9 21v-7h6v7"
                                ></path>

                            </svg>


                        </div>


                        <div>


                            <h2>

                                Contact & Address

                            </h2>


                            <p>

                                Provide your contact and residential
                                details.

                            </p>


                        </div>


                    </div>



                    <div class="form-grid">


                        <!-- PHONE -->

                        <div class="form-group">


                            <label for="phone">

                                Phone Number

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="tel"

                                id="phone"

                                name="phone"

                                placeholder="+94 7x xxx xxxx"

                                value="<?=
                                    htmlspecialchars(
                                        $formData["phone"]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- EMAIL -->

                        <div class="form-group">


                            <label for="email">

                                Email Address

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <input

                                type="email"

                                id="email"

                                name="email"

                                placeholder="anjali@example.com"

                                value="<?=
                                    htmlspecialchars(
                                        $formData["email"]
                                        ?? ""
                                    )
                                ?>"

                                required

                            >


                        </div>



                        <!-- ADDRESS -->

                        <div class="form-group full-width">


                            <label for="address">

                                Home Address

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <textarea

                                id="address"

                                name="address"

                                rows="3"

                                placeholder="Street name, City, Postal Code"

                                required

                            ><?= htmlspecialchars(
                                $formData["address"]
                                ?? ""
                            ) ?></textarea>


                        </div>



                        <!-- DISTRICT -->

                        <div class="form-group">


                            <label for="district">

                                District

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <select

                                id="district"

                                name="district"

                                required

                            >


                                <option

                                    value=""

                                    disabled

                                    <?= empty(
                                        $formData["district"]
                                    )
                                        ? "selected"
                                        : ""
                                    ?>

                                >

                                    Select District

                                </option>



                                <?php foreach (
                                    $districts
                                    as
                                    $district
                                ): ?>


                                    <option

                                        value="<?=
                                            htmlspecialchars(
                                                $district
                                            )
                                        ?>"

                                        <?= (
                                            (
                                                $formData[
                                                    "district"
                                                ]
                                                ?? ""
                                            )
                                            === $district
                                        )
                                            ? "selected"
                                            : ""
                                        ?>

                                    >

                                        <?=
                                            htmlspecialchars(
                                                $district
                                            )
                                        ?>

                                    </option>


                                <?php endforeach; ?>


                            </select>


                        </div>


                    </div>


                </section>



                <!-- =================================================
                     ACCOUNT SECURITY
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

                                <rect
                                    x="4"
                                    y="10"
                                    width="16"
                                    height="11"
                                    rx="2"
                                ></rect>

                                <path
                                    d="M8 10V7a4 4 0 0 1 8 0v3"
                                ></path>

                            </svg>


                        </div>


                        <div>


                            <h2>

                                Account Security

                            </h2>


                            <p>

                                Create a secure password for your
                                account.

                            </p>


                        </div>


                    </div>



                    <div class="form-grid">


                        <!-- PASSWORD -->

                        <div class="form-group">


                            <label for="password">

                                Password

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <div class="password-wrapper">


                                <input

                                    type="password"

                                    id="password"

                                    name="password"

                                    placeholder="Minimum 8 characters"

                                    minlength="8"

                                    required

                                >


                                <button

                                    type="button"

                                    class="password-toggle"

                                    data-target="password"

                                >

                                    Show

                                </button>


                            </div>


                        </div>



                        <!-- CONFIRM PASSWORD -->

                        <div class="form-group">


                            <label for="confirmPassword">

                                Confirm Password

                                <span class="required">

                                    *

                                </span>

                            </label>


                            <div class="password-wrapper">


                                <input

                                    type="password"

                                    id="confirmPassword"

                                    name="confirm_password"

                                    placeholder="Repeat your password"

                                    minlength="8"

                                    required

                                >


                                <button

                                    type="button"

                                    class="password-toggle"

                                    data-target="confirmPassword"

                                >

                                    Show

                                </button>


                            </div>


                        </div>


                    </div>


                </section>



                <!-- =================================================
                     BUTTONS
                ================================================== -->

                <div class="form-actions">


                    <a

                        href="register.php"

                        class="cancel-button"

                    >

                        Cancel

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

<script src="assets/js/caregiver-register.js?v=2"></script>


</body>

</html>