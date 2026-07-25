<?php

session_start();

require_once __DIR__ . '/includes/db.php';


/*
|--------------------------------------------------------------------------
| DEFAULT PAGE MODE
|--------------------------------------------------------------------------
|
| "submitted" = caregiver has just completed registration
| "pending"   = caregiver logged in later while waiting for approval
|
*/

$pageMode = "submitted";

$firstName = "";
$verificationStatus = "Pending";


/*
|--------------------------------------------------------------------------
| CHECK IF USER IS LOGGED IN
|--------------------------------------------------------------------------
*/

$isLoggedIn =
    isset(
        $_SESSION['user_id'],
        $_SESSION['role']
    );


/*
|--------------------------------------------------------------------------
| CASE 1: LOGGED-IN USER
|--------------------------------------------------------------------------
|
| If the caregiver comes here after logging in,
| verify their real status from the database.
|
*/

if ($isLoggedIn) {

    /*
    |--------------------------------------------------------------------------
    | ONLY CAREGIVERS SHOULD ACCESS THIS STATUS PAGE
    |--------------------------------------------------------------------------
    */

    if ($_SESSION['role'] !== 'Caregiver') {

        /*
        | Family users
        */

        if ($_SESSION['role'] === 'Family') {

            header(
                "Location: family/dashboard.php"
            );

            exit();

        }


        /*
        | Admin users
        */

        if ($_SESSION['role'] === 'Admin') {

            header(
                "Location: admin/dashboard.php"
            );

            exit();

        }


        /*
        | Unknown role
        */

        header(
            "Location: index.php"
        );

        exit();

    }


    /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN CAREGIVER ID
    |--------------------------------------------------------------------------
    */

    $userId =
        (int)$_SESSION['user_id'];


    $firstName =
        $_SESSION['first_name']
        ?? 'Caregiver';


    /*
    |--------------------------------------------------------------------------
    | GET CAREGIVER VERIFICATION STATUS
    |--------------------------------------------------------------------------
    */

    $stmt =
        $conn->prepare(

            "SELECT
                verification_status

             FROM caregiver_profiles

             WHERE user_id = ?

             LIMIT 1"

        );


    if (!$stmt) {

        die(
            "Unable to load your application status."
        );

    }


    $stmt->bind_param(
        "i",
        $userId
    );


    $stmt->execute();


    $result =
        $stmt->get_result();


    /*
    |--------------------------------------------------------------------------
    | CAREGIVER PROFILE NOT FOUND
    |--------------------------------------------------------------------------
    */

    if ($result->num_rows !== 1) {

        $stmt->close();


        header(
            "Location: index.php"
        );

        exit();

    }


    $caregiver =
        $result->fetch_assoc();


    $verificationStatus =
        $caregiver[
            'verification_status'
        ];


    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | VERIFIED CAREGIVER
    |--------------------------------------------------------------------------
    |
    | A verified caregiver no longer needs to see this page.
    |
    */

    if (
        $verificationStatus
        === 'Verified'
    ) {

        header(
            "Location: caregiver/dashboard.php"
        );

        exit();

    }


    /*
    |--------------------------------------------------------------------------
    | REJECTED CAREGIVER
    |--------------------------------------------------------------------------
    */

    if (
        $verificationStatus
        === 'Rejected'
    ) {

        header(
            "Location: caregiver/application-rejected.php"
        );

        exit();

    }


    /*
    |--------------------------------------------------------------------------
    | PENDING CAREGIVER
    |--------------------------------------------------------------------------
    */

    if (
        $verificationStatus
        === 'Pending'
    ) {

        $pageMode =
            "pending";

    }


    /*
    |--------------------------------------------------------------------------
    | UNKNOWN STATUS
    |--------------------------------------------------------------------------
    */

    else {

        header(
            "Location: index.php"
        );

        exit();

    }

}


/*
|--------------------------------------------------------------------------
| CASE 2: JUST COMPLETED REGISTRATION
|--------------------------------------------------------------------------
|
| The caregiver may arrive here immediately after submitting
| the registration form before actually logging in.
|
| Your registration process may store temporary registration
| information in the session.
|
*/

else {

    /*
    |--------------------------------------------------------------------------
    | GET NAME FROM REGISTRATION SESSION IF AVAILABLE
    |--------------------------------------------------------------------------
    */

    if (
        isset(
            $_SESSION[
                'caregiver_registration'
            ][
                'first_name'
            ]
        )
    ) {

        $firstName =
            $_SESSION[
                'caregiver_registration'
            ][
                'first_name'
            ];

    }


    elseif (
        isset(
            $_SESSION[
                'caregiver_personal'
            ][
                'first_name'
            ]
        )
    ) {

        $firstName =
            $_SESSION[
                'caregiver_personal'
            ][
                'first_name'
            ];

    }


    else {

        $firstName =
            "Caregiver";

    }


    $pageMode =
        "submitted";

}


/*
|--------------------------------------------------------------------------
| PAGE CONTENT
|--------------------------------------------------------------------------
*/

if (
    $pageMode
    === "pending"
) {

    $pageLabel =
        "APPLICATION STATUS";


    $pageTitle =
        "Your Application Is Under Review";


    $pageDescription =
        "Thank you for choosing SafeHands. Your caregiver application is currently being reviewed by our verification team.";


    $statusTitle =
        "Pending Verification";


    $statusDescription =
        "Our admin team is reviewing your identity, qualifications and submitted verification documents.";


    $mainButtonText =
        "Back to Home";


    $mainButtonLink =
        "index.php";

}

else {

    $pageLabel =
        "APPLICATION SUBMITTED";


    $pageTitle =
        "Application Submitted Successfully";


    $pageDescription =
        "Thank you for applying to become a SafeHands caregiver. Your application has been received successfully.";


    $statusTitle =
        "Pending Verification";


    $statusDescription =
        "Your application has been sent to our admin team for review. You will be able to access caregiver features after your account is verified.";


    $mainButtonText =
        "Go to Login";


    $mainButtonLink =
        "login.php";

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
        Caregiver Application | SafeHands
    </title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >


    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >


    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined"
        rel="stylesheet"
    >


<style>


/* =========================================================
   RESET
========================================================= */

* {

    box-sizing: border-box;

    margin: 0;

    padding: 0;

}


body {

    font-family:
        'Inter',
        Arial,
        sans-serif;

    background:
        #f7f9ff;

    color:
        #111c2d;

    min-height:
        100vh;

}


/* =========================================================
   HEADER
========================================================= */

.site-header {

    background:
        #ffffff;

    border-bottom:
        1px solid #e4e8f0;

    height:
        76px;

    display:
        flex;

    align-items:
        center;

}


.header-container {

    width:
        min(
            1180px,
            calc(100% - 40px)
        );

    margin:
        0 auto;

    display:
        flex;

    align-items:
        center;

    justify-content:
        space-between;

}


.logo {

    text-decoration:
        none;

    color:
        #0053db;

    font-size:
        25px;

    font-weight:
        700;

    display:
        flex;

    align-items:
        center;

    gap:
        9px;

}


.logo-icon {

    width:
        38px;

    height:
        38px;

    border-radius:
        11px;

    background:
        #0053db;

    color:
        white;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

}


.header-link {

    text-decoration:
        none;

    color:
        #434655;

    font-size:
        14px;

    font-weight:
        600;

    transition:
        0.2s;

}


.header-link:hover {

    color:
        #0053db;

}


/* =========================================================
   MAIN PAGE
========================================================= */

.page-main {

    min-height:
        calc(100vh - 76px);

    padding:
        65px 20px 80px;

}


.page-container {

    width:
        100%;

    max-width:
        850px;

    margin:
        0 auto;

}


/* =========================================================
   SUCCESS CARD
========================================================= */

.success-card {

    background:
        #ffffff;

    border:
        1px solid #e3e8f2;

    border-radius:
        24px;

    overflow:
        hidden;

    box-shadow:
        0 18px 55px
        rgba(
            17,
            28,
            45,
            0.08
        );

}


/* =========================================================
   TOP SECTION
========================================================= */

.success-top {

    text-align:
        center;

    padding:
        55px 50px 42px;

}


.success-icon {

    width:
        82px;

    height:
        82px;

    margin:
        0 auto 25px;

    border-radius:
        50%;

    background:
        #e9f7ef;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    color:
        #198754;

}


.success-icon .material-symbols-outlined {

    font-size:
        45px;

}


.page-label {

    display:
        inline-block;

    font-size:
        12px;

    font-weight:
        700;

    letter-spacing:
        1.6px;

    color:
        #0053db;

    margin-bottom:
        14px;

}


.success-top h1 {

    font-size:
        34px;

    line-height:
        1.2;

    margin-bottom:
        16px;

    color:
        #111c2d;

}


.success-top > p {

    max-width:
        640px;

    margin:
        0 auto;

    color:
        #5c6370;

    line-height:
        1.7;

    font-size:
        15px;

}


/* =========================================================
   STATUS BOX
========================================================= */

.status-area {

    padding:
        0 50px 45px;

}


.status-box {

    background:
        #f4f7ff;

    border:
        1px solid #dce5ff;

    border-radius:
        18px;

    padding:
        24px;

    display:
        flex;

    gap:
        18px;

    align-items:
        flex-start;

}


.status-icon {

    width:
        48px;

    height:
        48px;

    flex-shrink:
        0;

    border-radius:
        13px;

    background:
        #dbe6ff;

    color:
        #0053db;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

}


.status-icon
.material-symbols-outlined {

    font-size:
        27px;

}


.status-content {

    flex:
        1;

}


.status-label {

    display:
        inline-flex;

    align-items:
        center;

    gap:
        6px;

    padding:
        6px 11px;

    background:
        #fff4d6;

    color:
        #8a6100;

    border-radius:
        50px;

    font-size:
        11px;

    font-weight:
        700;

    margin-bottom:
        11px;

}


.status-content h2 {

    font-size:
        19px;

    margin-bottom:
        8px;

}


.status-content p {

    color:
        #5c6370;

    font-size:
        14px;

    line-height:
        1.65;

}


/* =========================================================
   WHAT HAPPENS NEXT
========================================================= */

.next-section {

    border-top:
        1px solid #edf0f5;

    padding:
        40px 50px;

}


.next-section h3 {

    font-size:
        19px;

    margin-bottom:
        27px;

}


.steps {

    display:
        grid;

    grid-template-columns:
        repeat(
            3,
            1fr
        );

    gap:
        18px;

}


.step {

    background:
        #fafbff;

    border:
        1px solid #e7ebf2;

    border-radius:
        16px;

    padding:
        21px;

}


.step-number {

    width:
        34px;

    height:
        34px;

    border-radius:
        10px;

    background:
        #e6edff;

    color:
        #0053db;

    display:
        flex;

    align-items:
        center;

    justify-content:
        center;

    font-weight:
        700;

    margin-bottom:
        15px;

}


.step strong {

    display:
        block;

    font-size:
        14px;

    margin-bottom:
        8px;

}


.step p {

    font-size:
        13px;

    line-height:
        1.6;

    color:
        #666d7a;

}


/* =========================================================
   INFORMATION NOTE
========================================================= */

.info-section {

    padding:
        0 50px 35px;

}


.info-box {

    background:
        #fffaf0;

    border:
        1px solid #f2dfb3;

    border-radius:
        15px;

    padding:
        17px 19px;

    display:
        flex;

    gap:
        13px;

    align-items:
        flex-start;

}


.info-box
.material-symbols-outlined {

    color:
        #a76d00;

    font-size:
        22px;

}


.info-box p {

    color:
        #6a5a36;

    font-size:
        13px;

    line-height:
        1.65;

}


/* =========================================================
   ACTIONS
========================================================= */

.action-section {

    border-top:
        1px solid #edf0f5;

    padding:
        30px 50px;

    display:
        flex;

    justify-content:
        center;

    gap:
        14px;

}


.primary-button {

    min-width:
        180px;

    padding:
        13px 22px;

    background:
        #0053db;

    color:
        #ffffff;

    border-radius:
        11px;

    text-decoration:
        none;

    font-size:
        14px;

    font-weight:
        600;

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

    gap:
        8px;

    transition:
        0.2s;

}


.primary-button:hover {

    background:
        #0044b5;

}


.secondary-button {

    min-width:
        150px;

    padding:
        13px 22px;

    background:
        #ffffff;

    color:
        #334155;

    border:
        1px solid #d7dce5;

    border-radius:
        11px;

    text-decoration:
        none;

    font-size:
        14px;

    font-weight:
        600;

    display:
        inline-flex;

    align-items:
        center;

    justify-content:
        center;

}


.secondary-button:hover {

    background:
        #f7f8fb;

}


/* =========================================================
   FOOTER TEXT
========================================================= */

.footer-note {

    text-align:
        center;

    margin-top:
        25px;

    font-size:
        12px;

    color:
        #858b96;

}


.footer-note a {

    color:
        #0053db;

    text-decoration:
        none;

    font-weight:
        600;

}


/* =========================================================
   RESPONSIVE
========================================================= */

@media
(max-width: 760px) {

    .page-main {

        padding:
            35px 15px
            60px;

    }


    .success-top {

        padding:
            42px 25px
            32px;

    }


    .success-top h1 {

        font-size:
            27px;

    }


    .status-area,
    .next-section,
    .info-section {

        padding-left:
            25px;

        padding-right:
            25px;

    }


    .steps {

        grid-template-columns:
            1fr;

    }


    .action-section {

        padding:
            25px;

        flex-direction:
            column;

    }


    .primary-button,
    .secondary-button {

        width:
            100%;

    }


    .status-box {

        flex-direction:
            column;

    }

}


</style>

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="site-header">

    <div class="header-container">


        <a
            href="index.php"
            class="logo"
        >

            <span class="logo-icon">

                <span
                    class="material-symbols-outlined"
                >

                    health_and_safety

                </span>

            </span>

            SafeHands

        </a>


        <?php if ($isLoggedIn): ?>

            <a
                href="logout.php"
                class="header-link"
            >

                Logout

            </a>

        <?php else: ?>

            <a
                href="login.php"
                class="header-link"
            >

                Login

            </a>

        <?php endif; ?>


    </div>

</header>



<!-- =========================================================
     MAIN
========================================================= -->

<main class="page-main">


    <div class="page-container">


        <section class="success-card">


            <!-- =================================================
                 SUCCESS HEADER
            ================================================== -->

            <div class="success-top">


                <div class="success-icon">

                    <span
                        class="material-symbols-outlined"
                    >

                        check_circle

                    </span>

                </div>


                <span class="page-label">

                    <?= htmlspecialchars(
                        $pageLabel
                    ) ?>

                </span>


                <h1>

                    <?= htmlspecialchars(
                        $pageTitle
                    ) ?>

                </h1>


                <p>

                    <?php if (
                        $firstName !== ""
                        &&
                        $firstName !== "Caregiver"
                    ): ?>

                        Hi
                        <?= htmlspecialchars(
                            $firstName
                        ) ?>,

                    <?php endif; ?>

                    <?= htmlspecialchars(
                        $pageDescription
                    ) ?>

                </p>


            </div>



            <!-- =================================================
                 STATUS
            ================================================== -->

            <div class="status-area">


                <div class="status-box">


                    <div class="status-icon">

                        <span
                            class="material-symbols-outlined"
                        >

                            hourglass_top

                        </span>

                    </div>


                    <div class="status-content">


                        <span class="status-label">

                            <span
                                class="material-symbols-outlined"
                                style="font-size:15px;"
                            >

                                schedule

                            </span>

                            PENDING

                        </span>


                        <h2>

                            <?= htmlspecialchars(
                                $statusTitle
                            ) ?>

                        </h2>


                        <p>

                            <?= htmlspecialchars(
                                $statusDescription
                            ) ?>

                        </p>


                    </div>


                </div>


            </div>



            <!-- =================================================
                 WHAT HAPPENS NEXT
            ================================================== -->

            <div class="next-section">


                <h3>

                    What happens next?

                </h3>


                <div class="steps">


                    <!-- STEP 1 -->

                    <div class="step">


                        <div class="step-number">

                            1

                        </div>


                        <strong>

                            Application Review

                        </strong>


                        <p>

                            Our admin team reviews your
                            personal and professional
                            information.

                        </p>


                    </div>



                    <!-- STEP 2 -->

                    <div class="step">


                        <div class="step-number">

                            2

                        </div>


                        <strong>

                            Document Verification

                        </strong>


                        <p>

                            Your NIC, qualifications and
                            verification documents are
                            checked.

                        </p>


                    </div>



                    <!-- STEP 3 -->

                    <div class="step">


                        <div class="step-number">

                            3

                        </div>


                        <strong>

                            Account Decision

                        </strong>


                        <p>

                            Once approved, your caregiver
                            account becomes verified and
                            dashboard access is enabled.

                        </p>


                    </div>


                </div>


            </div>



            <!-- =================================================
                 IMPORTANT NOTE
            ================================================== -->

            <div class="info-section">


                <div class="info-box">


                    <span
                        class="material-symbols-outlined"
                    >

                        info

                    </span>


                    <p>

                        <strong>
                            Please note:
                        </strong>

                        You cannot access verified caregiver
                        features while your application is
                        pending. Please log in again after your
                        application has been reviewed. Once
                        approved, SafeHands will automatically
                        direct you to your caregiver dashboard.

                    </p>


                </div>


            </div>



            <!-- =================================================
                 BUTTONS
            ================================================== -->

            <div class="action-section">


                <a
                    href="<?= htmlspecialchars(
                        $mainButtonLink
                    ) ?>"
                    class="primary-button"
                >

                    <?= htmlspecialchars(
                        $mainButtonText
                    ) ?>

                    <span
                        class="material-symbols-outlined"
                    >

                        arrow_forward

                    </span>

                </a>


                <?php if ($isLoggedIn): ?>


                    <a
                        href="logout.php"
                        class="secondary-button"
                    >

                        Logout

                    </a>


                <?php else: ?>


                    <a
                        href="index.php"
                        class="secondary-button"
                    >

                        Back to Home

                    </a>


                <?php endif; ?>


            </div>


        </section>



        <p class="footer-note">

            Need help with your application?

            <a href="#">

                Contact SafeHands Support

            </a>

        </p>


    </div>


</main>


</body>

</html>