<?php

session_start();

require_once __DIR__ . '/includes/db.php';

$message = "";


/*
|--------------------------------------------------------------------------
| CAREGIVER REDIRECT FUNCTION
|--------------------------------------------------------------------------
*/

function redirectCaregiver(mysqli $conn, int $userId): string
{
    $stmt = $conn->prepare(
        "SELECT verification_status
         FROM caregiver_profiles
         WHERE user_id = ?
         LIMIT 1"
    );

    if (!$stmt) {
        return "Unable to check caregiver verification status.";
    }

    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {

        $stmt->close();

        return "Unable to check caregiver verification status.";
    }

    $result = $stmt->get_result();


    /*
    |--------------------------------------------------------------------------
    | CAREGIVER PROFILE NOT FOUND
    |--------------------------------------------------------------------------
    */

    if ($result->num_rows !== 1) {

        $stmt->close();

        return "Your caregiver profile could not be found. Please contact support.";
    }


    /*
    |--------------------------------------------------------------------------
    | GET VERIFICATION STATUS
    |--------------------------------------------------------------------------
    */

    $caregiver = $result->fetch_assoc();

    $verificationStatus = trim(
        $caregiver['verification_status']
    );

    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | PENDING
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Pending'
        ) === 0
    ) {

        header(
            "Location: caregiver-application-success.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | VERIFIED
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Verified'
        ) === 0
    ) {

        header(
            "Location: caregiver/dashboard.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | REJECTED
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Rejected'
        ) === 0
    ) {

        header(
            "Location: caregiver/application-rejected.php"
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | UNKNOWN STATUS
    |--------------------------------------------------------------------------
    */

    return "Your caregiver verification status is unavailable. Please contact support.";
}


/*
|--------------------------------------------------------------------------
| PROCESS LOGIN FORM
|--------------------------------------------------------------------------
|
| IMPORTANT:
| We do NOT automatically redirect just because a session already exists.
| Therefore login.php will always display normally when opened.
|
*/

if ($_SERVER["REQUEST_METHOD"] === "POST") {


    /*
    |--------------------------------------------------------------------------
    | GET FORM DATA
    |--------------------------------------------------------------------------
    */

    $email = trim(
        $_POST['email'] ?? ''
    );

    $password =
        $_POST['password'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | BASIC VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        $email === '' ||
        $password === ''
    ) {

        $message =
            "Please enter your email and password.";
    }


    elseif (
        !filter_var(
            $email,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $message =
            "Please enter a valid email address.";
    }


    else {


        /*
        |--------------------------------------------------------------------------
        | FIND USER BY EMAIL
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare(
            "SELECT
                user_id,
                first_name,
                last_name,
                email,
                password,
                role,
                status
             FROM users
             WHERE email = ?
             LIMIT 1"
        );


        if (!$stmt) {

            $message =
                "Unable to process login. Please try again.";
        }


        else {


            $stmt->bind_param(
                "s",
                $email
            );


            /*
            |--------------------------------------------------------------------------
            | EXECUTE QUERY
            |--------------------------------------------------------------------------
            */

            if (!$stmt->execute()) {

                $message =
                    "Unable to process login. Please try again.";

                $stmt->close();
            }


            else {


                $result =
                    $stmt->get_result();


                /*
                |--------------------------------------------------------------------------
                | USER NOT FOUND
                |--------------------------------------------------------------------------
                */

                if ($result->num_rows !== 1) {

                    $message =
                        "Invalid email or password.";

                    $stmt->close();
                }


                else {


                    /*
                    |--------------------------------------------------------------------------
                    | GET USER
                    |--------------------------------------------------------------------------
                    */

                    $user =
                        $result->fetch_assoc();

                    $stmt->close();


                    /*
                    |--------------------------------------------------------------------------
                    | VERIFY PASSWORD
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !password_verify(
                            $password,
                            $user['password']
                        )
                    ) {

                        $message =
                            "Invalid email or password.";
                    }


                    else {


                        /*
                        |--------------------------------------------------------------------------
                        | CHECK ACCOUNT STATUS
                        |--------------------------------------------------------------------------
                        */

                        $accountStatus =
                            trim(
                                $user['status']
                            );


                        if (
                            strcasecmp(
                                $accountStatus,
                                'Active'
                            ) !== 0
                        ) {

                            $message =
                                "Your account is currently inactive.";
                        }


                        else {


                            /*
                            |--------------------------------------------------------------------------
                            | LOGIN SUCCESSFUL
                            |--------------------------------------------------------------------------
                            */

                            session_regenerate_id(true);


                            /*
                            |--------------------------------------------------------------------------
                            | REMOVE OLD REGISTRATION SESSION DATA
                            |--------------------------------------------------------------------------
                            |
                            | Do not destroy the whole session.
                            | We only remove temporary registration information.
                            |
                            */

                            unset(
                                $_SESSION[
                                    'caregiver_registration'
                                ],
                                $_SESSION[
                                    'caregiver_professional'
                                ],
                                $_SESSION[
                                    'caregiver_verification'
                                ]
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | CREATE AUTHENTICATED SESSION
                            |--------------------------------------------------------------------------
                            */

                            $_SESSION['user_id'] =
                                (int)$user['user_id'];

                            $_SESSION['first_name'] =
                                $user['first_name'];

                            $_SESSION['last_name'] =
                                $user['last_name'];

                            $_SESSION['email'] =
                                $user['email'];

                            $_SESSION['role'] =
                                trim(
                                    $user['role']
                                );

                            $_SESSION['logged_in'] =
                                true;


                            /*
                            |--------------------------------------------------------------------------
                            | GET ROLE
                            |--------------------------------------------------------------------------
                            */

                            $role =
                                trim(
                                    $user['role']
                                );


                            /*
                            |--------------------------------------------------------------------------
                            | FAMILY
                            |--------------------------------------------------------------------------
                            */

                            if (
                                strcasecmp(
                                    $role,
                                    'Family'
                                ) === 0
                            ) {

                                header(
                                    "Location: family/dashboard.php"
                                );

                                exit;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | ADMIN
                            |--------------------------------------------------------------------------
                            */

                            elseif (
                                strcasecmp(
                                    $role,
                                    'Admin'
                                ) === 0
                            ) {

                                header(
                                    "Location: admin/dashboard.php"
                                );

                                exit;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | CAREGIVER
                            |--------------------------------------------------------------------------
                            */

                            elseif (
                                strcasecmp(
                                    $role,
                                    'Caregiver'
                                ) === 0
                            ) {

                                /*
                                |--------------------------------------------------------------------------
                                | DO NOT SEND CAREGIVER DIRECTLY TO DASHBOARD
                                |--------------------------------------------------------------------------
                                |
                                | First check caregiver_profiles.verification_status
                                |
                                */

                                $caregiverError =
                                    redirectCaregiver(
                                        $conn,
                                        (int)$user['user_id']
                                    );


                                /*
                                |--------------------------------------------------------------------------
                                | If redirectCaregiver did not redirect,
                                | something went wrong.
                                |--------------------------------------------------------------------------
                                */

                                $message =
                                    $caregiverError;
                            }


                            /*
                            |--------------------------------------------------------------------------
                            | UNKNOWN ROLE
                            |--------------------------------------------------------------------------
                            */

                            else {


                                /*
                                | Clear authentication data
                                */

                                unset(
                                    $_SESSION['user_id'],
                                    $_SESSION['first_name'],
                                    $_SESSION['last_name'],
                                    $_SESSION['email'],
                                    $_SESSION['role'],
                                    $_SESSION['logged_in']
                                );


                                $message =
                                    "Invalid account role.";
                            }

                        }

                    }

                }

            }

        }

    }

}

?>

<!DOCTYPE html>

<html
    class="light"
    lang="en"
>

<head>

<meta charset="utf-8"/>

<meta
    content="width=device-width, initial-scale=1.0"
    name="viewport"
/>

<title>
    SafeHands - Login
</title>


<!-- TAILWIND -->

<script
    src="https://cdn.tailwindcss.com?plugins=forms,container-queries"
></script>


<!-- INTER FONT -->

<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet"
/>


<!-- MATERIAL ICONS -->

<link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet"
/>


<script>

tailwind.config = {

    darkMode: "class",

    theme: {

        extend: {

            colors: {

                "primary": "#0053db",

                "surface": "#f9f9ff",

                "on-surface": "#111c2d",

                "on-surface-variant": "#434655"

            }

        }

    }

};

</script>


<style>

body {

    font-family:
        'Inter',
        sans-serif;

}

</style>


</head>


<body
    class="
        min-h-screen
        bg-surface
        text-on-surface
    "
>


<div
    class="
        min-h-screen
        grid
        lg:grid-cols-2
    "
>


<!-- =========================================================
     LEFT SIDE
========================================================= -->

<section
    class="
        hidden
        lg:flex
        relative
        overflow-hidden
        bg-primary
        text-white
        p-16
        flex-col
        justify-between
    "
>


    <!-- LOGO -->

    <div class="relative z-10">


        <a
            href="index.php"
            class="
                inline-flex
                items-center
                gap-3
                text-white
                no-underline
            "
        >


            <span
                class="
                    material-symbols-outlined
                    text-4xl
                "
            >

                health_and_safety

            </span>


            <span
                class="
                    text-2xl
                    font-bold
                "
            >

                SafeHands

            </span>


        </a>


    </div>



    <!-- HERO TEXT -->

    <div
        class="
            relative
            z-10
            max-w-xl
        "
    >


        <span
            class="
                inline-flex
                items-center
                gap-2
                px-4
                py-2
                rounded-full
                bg-white/10
                text-sm
                font-semibold
                mb-6
            "
        >


            <span
                class="
                    material-symbols-outlined
                    text-lg
                "
            >

                verified_user

            </span>


            Trusted Care Platform


        </span>



        <h1
            class="
                text-5xl
                font-bold
                leading-tight
                mb-6
            "
        >

            Care you can trust,
            whenever you need it.

        </h1>



        <p
            class="
                text-lg
                text-white/80
                leading-relaxed
            "
        >

            SafeHands connects families with verified
            professional caregivers while providing secure,
            reliable and transparent care management.

        </p>


    </div>



    <!-- COPYRIGHT -->

    <div
        class="
            relative
            z-10
            text-sm
            text-white/70
        "
    >

        © 2026 SafeHands Healthcare Services

    </div>


</section>



<!-- =========================================================
     RIGHT SIDE
========================================================= -->

<section
    class="
        flex
        items-center
        justify-center
        px-6
        py-12
        lg:px-16
    "
>


    <div
        class="
            w-full
            max-w-md
        "
    >


        <!-- MOBILE LOGO -->

        <a
            href="index.php"
            class="
                lg:hidden
                inline-flex
                items-center
                gap-2
                mb-10
                text-primary
                no-underline
            "
        >


            <span
                class="
                    material-symbols-outlined
                    text-3xl
                "
            >

                health_and_safety

            </span>


            <span
                class="
                    text-xl
                    font-bold
                "
            >

                SafeHands

            </span>


        </a>



        <!-- HEADING -->

        <div class="mb-8">


            <span
                class="
                    text-primary
                    text-xs
                    font-bold
                    tracking-widest
                "
            >

                WELCOME BACK

            </span>


            <h2
                class="
                    text-3xl
                    font-bold
                    mt-2
                    mb-3
                "
            >

                Sign in to SafeHands

            </h2>


            <p
                class="
                    text-on-surface-variant
                    text-sm
                    leading-relaxed
                "
            >

                Enter your registered email address and password
                to access your SafeHands account.

            </p>


        </div>



        <!-- =================================================
             ERROR MESSAGE
        ================================================== -->

        <?php if ($message !== ""): ?>


            <div
                class="
                    mb-6
                    px-4
                    py-3
                    rounded-xl
                    bg-red-50
                    border
                    border-red-200
                    text-red-700
                    text-sm
                "
            >


                <div
                    class="
                        flex
                        items-start
                        gap-2
                    "
                >


                    <span
                        class="
                            material-symbols-outlined
                            text-xl
                        "
                    >

                        error

                    </span>


                    <span>

                        <?= htmlspecialchars(
                            $message
                        ) ?>

                    </span>


                </div>


            </div>


        <?php endif; ?>



        <!-- =================================================
             LOGIN FORM
        ================================================== -->

        <form
            action="login.php"
            method="POST"
            class="space-y-5"
        >


            <!-- EMAIL -->

            <div>


                <label
                    for="email"
                    class="
                        block
                        text-sm
                        font-semibold
                        mb-2
                    "
                >

                    Email Address

                </label>


                <div class="relative">


                    <span
                        class="
                            material-symbols-outlined
                            absolute
                            left-4
                            top-1/2
                            -translate-y-1/2
                            text-gray-400
                        "
                    >

                        mail

                    </span>


                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars(
                            $_POST['email'] ?? ''
                        ) ?>"
                        placeholder="Enter your email"
                        autocomplete="email"
                        required
                        class="
                            w-full
                            rounded-xl
                            border-gray-300
                            pl-12
                            pr-4
                            py-3.5
                            focus:border-primary
                            focus:ring-primary
                        "
                    >


                </div>


            </div>



            <!-- PASSWORD -->

            <div>


                <div
                    class="
                        flex
                        justify-between
                        items-center
                        mb-2
                    "
                >


                    <label
                        for="password"
                        class="
                            text-sm
                            font-semibold
                        "
                    >

                        Password

                    </label>


                    <a
                        href="forgot-password.php"
                        class="
                            text-sm
                            text-primary
                            font-semibold
                            hover:underline
                        "
                    >

                        Forgot Password?

                    </a>


                </div>



                <div class="relative">


                    <span
                        class="
                            material-symbols-outlined
                            absolute
                            left-4
                            top-1/2
                            -translate-y-1/2
                            text-gray-400
                        "
                    >

                        lock

                    </span>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        required
                        class="
                            w-full
                            rounded-xl
                            border-gray-300
                            pl-12
                            pr-12
                            py-3.5
                            focus:border-primary
                            focus:ring-primary
                        "
                    >


                    <button
                        type="button"
                        id="togglePassword"
                        class="
                            absolute
                            right-4
                            top-1/2
                            -translate-y-1/2
                            text-gray-400
                            hover:text-primary
                        "
                        aria-label="Show or hide password"
                    >


                        <span
                            class="
                                material-symbols-outlined
                            "
                            id="passwordIcon"
                        >

                            visibility

                        </span>


                    </button>


                </div>


            </div>



            <!-- =================================================
                 SIGN IN BUTTON
            ================================================== -->

            <button
                type="submit"
                class="
                    w-full
                    bg-primary
                    text-white
                    rounded-xl
                    py-3.5
                    font-semibold
                    flex
                    items-center
                    justify-center
                    gap-2
                    hover:opacity-90
                    transition
                "
            >


                Sign In


                <span
                    class="
                        material-symbols-outlined
                        text-xl
                    "
                >

                    arrow_forward

                </span>


            </button>


        </form>



        <!-- =================================================
             REGISTER
        ================================================== -->

        <div
            class="
                mt-8
                pt-6
                border-t
                border-gray-200
                text-center
            "
        >


            <p
                class="
                    text-sm
                    text-on-surface-variant
                "
            >

                Don't have an account?


                <a
                    href="register.php"
                    class="
                        text-primary
                        font-bold
                        hover:underline
                    "
                >

                    Create an account

                </a>


            </p>


        </div>



        <!-- =================================================
             BACK HOME
        ================================================== -->

        <div
            class="
                mt-6
                text-center
            "
        >


            <a
                href="index.php"
                class="
                    inline-flex
                    items-center
                    gap-1
                    text-sm
                    text-on-surface-variant
                    hover:text-primary
                "
            >


                <span
                    class="
                        material-symbols-outlined
                        text-lg
                    "
                >

                    arrow_back

                </span>


                Back to Home


            </a>


        </div>


    </div>


</section>


</div>



<!-- =========================================================
     SHOW / HIDE PASSWORD
========================================================= -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {


        const passwordInput =
            document.getElementById(
                "password"
            );


        const togglePassword =
            document.getElementById(
                "togglePassword"
            );


        const passwordIcon =
            document.getElementById(
                "passwordIcon"
            );


        if (
            passwordInput &&
            togglePassword &&
            passwordIcon
        ) {


            togglePassword.addEventListener(
                "click",
                function () {


                    if (
                        passwordInput.type
                        === "password"
                    ) {


                        passwordInput.type =
                            "text";


                        passwordIcon.textContent =
                            "visibility_off";


                    }


                    else {


                        passwordInput.type =
                            "password";


                        passwordIcon.textContent =
                            "visibility";


                    }


                }
            );


        }


    }
);

</script>


</body>

</html>