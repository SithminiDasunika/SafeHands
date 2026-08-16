<?php

session_start();


// Start a fresh password reset when the page is opened normally
if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_GET['step'])) {

    unset(
        $_SESSION['reset_user_id'],
        $_SESSION['reset_email'],
        $_SESSION['reset_first_name'],
        $_SESSION['reset_id'],
        $_SESSION['otp_verified'],
        $_SESSION['reset_step']
    );

    $_SESSION['reset_step'] = 1;
}


require_once 'includes/db.php';
require_once __DIR__ . '/includes/mail-config.php';
$message = "";
$messageType = "";

/*
|--------------------------------------------------------------------------
| CURRENT RESET STEP
|--------------------------------------------------------------------------
| 1 = Enter Email
| 2 = Verify OTP
| 3 = Create New Password
| 4 = Success
|--------------------------------------------------------------------------
*/

$resetStep = $_SESSION['reset_step'] ?? 1;


/*
|--------------------------------------------------------------------------
| STEP 1 - CHECK EMAIL AND GENERATE OTP
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['send_code'])
) {

    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {

        $message = "Please enter your email address.";
        $messageType = "error";
        $resetStep = 1;

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";
        $resetStep = 1;

    } else {

        /*
        |--------------------------------------------------------------------------
        | Find User
        |--------------------------------------------------------------------------
        */

        $stmt = $conn->prepare("
            SELECT
                user_id,
                first_name,
                last_name,
                email
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        if (!$stmt) {

            $message = "Something went wrong. Please try again.";
            $messageType = "error";
            $resetStep = 1;

        } else {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $user = $result->fetch_assoc();

                $userId = (int) $user['user_id'];

                /*
                |--------------------------------------------------------------------------
                | Store Reset User Information
                |--------------------------------------------------------------------------
                */

                $_SESSION['reset_user_id'] = $userId;
                $_SESSION['reset_email'] = $user['email'];
                $_SESSION['reset_first_name'] = $user['first_name'];

                /*
                |--------------------------------------------------------------------------
                | Generate 6-Digit OTP
                |--------------------------------------------------------------------------
                */

                $otp = (string) random_int(100000, 999999);

                /*
                |--------------------------------------------------------------------------
                | Hash OTP
                |--------------------------------------------------------------------------
                */

                $otpHash = password_hash(
                    $otp,
                    PASSWORD_DEFAULT
                );

                /*
                |--------------------------------------------------------------------------
                | OTP Expires in 10 Minutes
                |--------------------------------------------------------------------------
                */

                $expiresAt = date(
                    'Y-m-d H:i:s',
                    time() + 600
                );

                /*
                |--------------------------------------------------------------------------
                | Delete Previous Unused OTPs
                |--------------------------------------------------------------------------
                */

                $deleteStmt = $conn->prepare("
                    DELETE FROM password_resets
                    WHERE user_id = ?
                    AND is_used = 0
                ");

                if (!$deleteStmt) {

                    $message =
                        "Unable to prepare verification request.";

                    $messageType = "error";
                    $resetStep = 1;

                } else {

                    $deleteStmt->bind_param(
                        "i",
                        $userId
                    );

                    $deleteStmt->execute();
                    $deleteStmt->close();

                    /*
                    |--------------------------------------------------------------------------
                    | Insert New OTP
                    |--------------------------------------------------------------------------
                    */

                    $insertStmt = $conn->prepare("
                        INSERT INTO password_resets
                        (
                            user_id,
                            otp_hash,
                            expires_at,
                            is_used
                        )
                        VALUES (?, ?, ?, 0)
                    ");

                    if (!$insertStmt) {

                        $message =
                            "Unable to create verification code.";

                        $messageType = "error";
                        $resetStep = 1;

                    } else {

                        $insertStmt->bind_param(
                            "iss",
                            $userId,
                            $otpHash,
                            $expiresAt
                        );

                        if ($insertStmt->execute()) {

                            // Send the OTP to the registered email address.
                            if (!sendOTPEmail($user['email'], $otp)) {

                                // Email failed, remove the OTP just created.
                                $newResetId = $insertStmt->insert_id;

                                $cleanupStmt = $conn->prepare("
                                    DELETE FROM password_resets
                                    WHERE reset_id = ?
                                    AND user_id = ?
                                ");

                                if ($cleanupStmt) {
                                    $cleanupStmt->bind_param("ii", $newResetId, $userId);
                                    $cleanupStmt->execute();
                                    $cleanupStmt->close();
                                }

                                unset(
                                    $_SESSION['reset_user_id'],
                                    $_SESSION['reset_email'],
                                    $_SESSION['reset_first_name'],
                                    $_SESSION['reset_id'],
                                    $_SESSION['otp_verified']
                                );

                                $message = "We could not send the verification code to your email. Please try again.";
                                $messageType = "error";
                                $resetStep = 1;

                            } else {

                                $_SESSION['reset_id'] = $insertStmt->insert_id;

                                unset($_SESSION['otp_verified']);

                                $_SESSION['reset_step'] = 2;
                                $resetStep = 2;
                                $message = "";
                                $messageType = "";
                            }

                        } else {

                            $message = "Unable to create verification code.";
                            $messageType = "error";
                            $resetStep = 1;
                        }

                        $insertStmt->close();
                    }
                }

            } else {

                $message =
                    "No SafeHands account was found with this email address.";

                $messageType = "error";
                $resetStep = 1;
            }

            $stmt->close();
        }
    }
}


/*
|--------------------------------------------------------------------------
| STEP 2 - VERIFY OTP
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['verify_otp'])
) {

    if (
        !isset($_SESSION['reset_user_id'])
        ||
        !isset($_SESSION['reset_id'])
        ||
        !isset($_SESSION['reset_email'])
    ) {

        $message =
            "Your password reset session has expired. Please start again.";

        $messageType = "error";

        $_SESSION['reset_step'] = 1;

        $resetStep = 1;

    } else {

        $enteredOtp =
            trim($_POST['otp'] ?? '');

        if (empty($enteredOtp)) {

            $message =
                "Please enter the verification code.";

            $messageType = "error";
            $resetStep = 2;

        } elseif (
            !preg_match(
                '/^[0-9]{6}$/',
                $enteredOtp
            )
        ) {

            $message =
                "Please enter a valid 6-digit verification code.";

            $messageType = "error";
            $resetStep = 2;

        } else {

            $resetId =
                (int) $_SESSION['reset_id'];

            $userId =
                (int) $_SESSION['reset_user_id'];

            /*
            |--------------------------------------------------------------------------
            | Find Password Reset Record
            |--------------------------------------------------------------------------
            */

            $verifyStmt = $conn->prepare("
                SELECT
                    reset_id,
                    user_id,
                    otp_hash,
                    expires_at,
                    is_used
                FROM password_resets
                WHERE reset_id = ?
                AND user_id = ?
                LIMIT 1
            ");

            if (!$verifyStmt) {

                $message =
                    "Unable to verify the code. Please try again.";

                $messageType = "error";
                $resetStep = 2;

            } else {

                $verifyStmt->bind_param(
                    "ii",
                    $resetId,
                    $userId
                );

                $verifyStmt->execute();

                $verifyResult =
                    $verifyStmt->get_result();

                if (
                    $verifyResult->num_rows !== 1
                ) {

                    $message =
                        "Invalid password reset request. Please request a new code.";

                    $messageType = "error";
                    $resetStep = 2;

                } else {

                    $reset =
                        $verifyResult->fetch_assoc();

                    /*
                    |--------------------------------------------------------------------------
                    | Check Used
                    |--------------------------------------------------------------------------
                    */

                    if (
                        (int) $reset['is_used'] === 1
                    ) {

                        $message =
                            "This verification code has already been used.";

                        $messageType = "error";
                        $resetStep = 2;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Check Expiry
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        strtotime(
                            $reset['expires_at']
                        ) < time()
                    ) {

                        $message =
                            "Your verification code has expired. Please request a new code.";

                        $messageType = "error";
                        $resetStep = 2;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Verify OTP
                    |--------------------------------------------------------------------------
                    */

                    elseif (
                        !password_verify(
                            $enteredOtp,
                            $reset['otp_hash']
                        )
                    ) {

                        $message =
                            "The verification code you entered is incorrect.";

                        $messageType = "error";
                        $resetStep = 2;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | OTP Correct
                    |--------------------------------------------------------------------------
                    */

                    else {

                        $_SESSION['otp_verified'] = true;

                        $_SESSION['reset_step'] = 3;

                        $resetStep = 3;


                        $message = "";
                        $messageType = "";
                    }
                }

                $verifyStmt->close();
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| STEP 3 - RESET PASSWORD
|--------------------------------------------------------------------------
*/

if (
    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['reset_password'])
) {

    /*
    |--------------------------------------------------------------------------
    | Security Check
    |--------------------------------------------------------------------------
    */

    if (
        !isset($_SESSION['otp_verified'])
        ||
        $_SESSION['otp_verified'] !== true
        ||
        !isset($_SESSION['reset_user_id'])
        ||
        !isset($_SESSION['reset_id'])
    ) {

        $message =
            "Your password reset session is invalid or has expired. Please start again.";

        $messageType = "error";

        $_SESSION['reset_step'] = 1;

        $resetStep = 1;

    } else {

        $newPassword =
            $_POST['new_password'] ?? '';

        $confirmPassword =
            $_POST['confirm_password'] ?? '';

        /*
        |--------------------------------------------------------------------------
        | Validate Password
        |--------------------------------------------------------------------------
        */

        if (
            empty($newPassword)
            ||
            empty($confirmPassword)
        ) {

            $message =
                "Please enter and confirm your new password.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            strlen($newPassword) < 8
        ) {

            $message =
                "Password must contain at least 8 characters.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            !preg_match(
                '/[A-Z]/',
                $newPassword
            )
        ) {

            $message =
                "Password must contain at least one uppercase letter.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            !preg_match(
                '/[a-z]/',
                $newPassword
            )
        ) {

            $message =
                "Password must contain at least one lowercase letter.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            !preg_match(
                '/[0-9]/',
                $newPassword
            )
        ) {

            $message =
                "Password must contain at least one number.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            !preg_match(
                '/[^A-Za-z0-9]/',
                $newPassword
            )
        ) {

            $message =
                "Password must contain at least one special character.";

            $messageType = "error";
            $resetStep = 3;

        } elseif (
            $newPassword !== $confirmPassword
        ) {

            $message =
                "New password and confirm password do not match.";

            $messageType = "error";
            $resetStep = 3;

        } else {

            $userId =
                (int) $_SESSION['reset_user_id'];

            $resetId =
                (int) $_SESSION['reset_id'];

            /*
            |--------------------------------------------------------------------------
            | Re-check Password Reset Record
            |--------------------------------------------------------------------------
            */

            $checkStmt = $conn->prepare("
                SELECT
                    reset_id,
                    user_id,
                    expires_at,
                    is_used
                FROM password_resets
                WHERE reset_id = ?
                AND user_id = ?
                LIMIT 1
            ");

            if (!$checkStmt) {

                $message =
                    "Unable to process your password reset.";

                $messageType = "error";
                $resetStep = 3;

            } else {

                $checkStmt->bind_param(
                    "ii",
                    $resetId,
                    $userId
                );

                $checkStmt->execute();

                $checkResult =
                    $checkStmt->get_result();

                if (
                    $checkResult->num_rows !== 1
                ) {

                    $message =
                        "Invalid password reset request.";

                    $messageType = "error";

                    $_SESSION['reset_step'] = 1;

                    $resetStep = 1;

                } else {

                    $resetRecord =
                        $checkResult->fetch_assoc();

                    if (
                        (int) $resetRecord['is_used'] === 1
                    ) {

                        $message =
                            "This password reset request has already been used.";

                        $messageType = "error";

                        $_SESSION['reset_step'] = 1;

                        $resetStep = 1;

                    } elseif (
                        strtotime(
                            $resetRecord['expires_at']
                        ) < time()
                    ) {

                        $message =
                            "Your password reset session has expired. Please request a new code.";

                        $messageType = "error";

                        $_SESSION['reset_step'] = 1;

                        $resetStep = 1;

                    } else {

                        /*
                        |--------------------------------------------------------------------------
                        | Hash New Password
                        |--------------------------------------------------------------------------
                        */

                        $hashedPassword =
                            password_hash(
                                $newPassword,
                                PASSWORD_DEFAULT
                            );

                        /*
                        |--------------------------------------------------------------------------
                        | Begin Transaction
                        |--------------------------------------------------------------------------
                        */

                        $conn->begin_transaction();

                        try {

                            /*
                            |--------------------------------------------------------------------------
                            | Update User Password
                            |--------------------------------------------------------------------------
                            */

                            $updateStmt =
                                $conn->prepare("
                                    UPDATE users
                                    SET password = ?
                                    WHERE user_id = ?
                                ");

                            if (!$updateStmt) {

                                throw new Exception(
                                    "Unable to prepare password update."
                                );
                            }

                            $updateStmt->bind_param(
                                "si",
                                $hashedPassword,
                                $userId
                            );

                            if (
                                !$updateStmt->execute()
                            ) {

                                throw new Exception(
                                    "Unable to update password."
                                );
                            }

                            $updateStmt->close();

                            /*
                            |--------------------------------------------------------------------------
                            | Mark Reset Request as Used
                            |--------------------------------------------------------------------------
                            */

                            $usedStmt =
                                $conn->prepare("
                                    UPDATE password_resets
                                    SET is_used = 1
                                    WHERE reset_id = ?
                                    AND user_id = ?
                                ");

                            if (!$usedStmt) {

                                throw new Exception(
                                    "Unable to complete reset."
                                );
                            }

                            $usedStmt->bind_param(
                                "ii",
                                $resetId,
                                $userId
                            );

                            if (
                                !$usedStmt->execute()
                            ) {

                                throw new Exception(
                                    "Unable to complete reset."
                                );
                            }

                            $usedStmt->close();

                            /*
                            |--------------------------------------------------------------------------
                            | Commit Changes
                            |--------------------------------------------------------------------------
                            */

                            $conn->commit();

                            /*
                            |--------------------------------------------------------------------------
                            | Clear Sensitive Reset Data
                            |--------------------------------------------------------------------------
                            */

                            unset(
                                $_SESSION['reset_user_id'],
                                $_SESSION['reset_email'],
                                $_SESSION['reset_first_name'],
                                $_SESSION['reset_id'],
                                $_SESSION['otp_verified']
                            );

                            /*
                            |--------------------------------------------------------------------------
                            | Show Success Screen
                            |--------------------------------------------------------------------------
                            */

                            $_SESSION['reset_step'] = 4;

                            $resetStep = 4;

                            $message = "";
                            $messageType = "";

                        } catch (Throwable $e) {

                            $conn->rollback();

                            $message =
                                "Unable to reset your password. Please try again.";

                            $messageType = "error";

                            $resetStep = 3;
                        }
                    }
                }

                $checkStmt->close();
            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| REFRESH CURRENT STEP
|--------------------------------------------------------------------------
*/

$resetStep =
    $_SESSION['reset_step'] ?? $resetStep;

?>


<!DOCTYPE html>

<html
    class="light"
    lang="en"
>

<head>

<meta charset="utf-8"/>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
/>

<title>
Reset Your Password | SafeHands Healthcare
</title>


<script
src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
</script>


<link
href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
rel="stylesheet"
/>


<link
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
rel="stylesheet"
/>


<script>

tailwind.config = {

    theme: {

        extend: {

            colors: {

                "primary": "#004ac6",

                "primary-container": "#2563eb",

                "on-surface": "#111c2d",

                "on-surface-variant": "#434655",

                "surface": "#f9f9ff",

                "surface-container": "#e7eeff",

                "surface-container-lowest": "#ffffff",

                "background": "#f9f9ff",

                "outline": "#737686",

                "outline-variant": "#c3c6d7",

                "border-subtle": "#F1F5F9"

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


.material-symbols-outlined {

    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;

}


.clinical-shadow {

    box-shadow:
        0 4px 6px -1px rgba(30, 41, 59, 0.05),
        0 2px 4px -2px rgba(30, 41, 59, 0.05);

}


.glass-card {

    background:
        rgba(255, 255, 255, 0.82);

    backdrop-filter:
        blur(8px);

}

</style>

</head>


<body
class="bg-background text-on-surface flex flex-col min-h-screen"
>


<!-- ====================================================== -->
<!-- HEADER -->
<!-- ====================================================== -->

<header
class="bg-surface border-b border-border-subtle"
>

<div
class="flex justify-between items-center h-16 px-6 md:px-10 max-w-7xl mx-auto"
>


<div
class="text-xl md:text-2xl font-bold text-primary"
>

SafeHands 

</div>


<nav
class="hidden md:flex items-center space-x-8"
>

<a
href="index.php"
class="text-sm text-on-surface-variant hover:text-primary"
>
Home
</a>

<a
href="#"
class="text-sm text-on-surface-variant hover:text-primary"
>
About
</a>

<a
href="#"
class="text-sm text-on-surface-variant hover:text-primary"
>
Services
</a>

<a
href="#"
class="text-sm text-on-surface-variant hover:text-primary"
>
Find Caregivers
</a>

<a
href="#"
class="text-sm text-on-surface-variant hover:text-primary"
>
Contact
</a>

</nav>


<div class="flex items-center gap-3">

<a
href="login.php"
class="text-sm text-on-surface-variant hover:text-primary px-3 py-2"
>
Login
</a>


<a
href="register.php"
class="bg-primary-container text-white text-sm px-5 py-2 rounded-xl"
>
Register
</a>

</div>

</div>

</header>


<!-- ====================================================== -->
<!-- MAIN -->
<!-- ====================================================== -->

<main
class="flex-grow flex flex-col md:flex-row min-h-[calc(100vh-128px)]"
>


<!-- LEFT SIDE -->

<div
class="relative w-full md:w-1/2 min-h-[300px] md:min-h-0 overflow-hidden"
>


<div
class="absolute inset-0 w-full h-full bg-cover bg-center"
style="
background-image:
url('https://lh3.googleusercontent.com/aida-public/AB6AXuDMWZyOudCAB1Gyu56JMfnPBHZe9rINpgzz9wz38QCqK9ptXJ9AzdZSkG1pzGeRB1ECjrld-r6L91KNS5qEg7A6I3BPIcSEwRi4kmennpykmOGGRK_Q9qXCA8-m1ifT9SXrYYDK-nSf54j0zk-Xl5Y2YjGZv9aySwdf5ZtTO5TZjSiwVP5E7iczUD2uUuxhmVusN8JAQQECXMm6JgQKzHCGjTZeMu6E-BxFrmNoQDdgEqboF3SwpBzj9LSs3X_kFUDjKFZL-yO9i1A');
"
>
</div>


<div
class="absolute inset-0 flex items-center justify-center p-8 bg-black/10"
>


<div
class="glass-card clinical-shadow rounded-xl p-8 max-w-sm w-full"
>


<span
class="material-symbols-outlined text-primary text-4xl mb-4"
>
lock_reset
</span>


<h2
class="text-2xl font-semibold mb-2"
>

Secure Account Recovery

</h2>


<p
class="text-on-surface-variant"
>

We'll help you securely recover your SafeHands account.

</p>

</div>

</div>

</div>


<!-- RIGHT SIDE -->

<div
class="w-full md:w-1/2 flex items-center justify-center p-6 md:p-12 bg-surface"
>


<div
class="bg-white clinical-shadow rounded-xl p-8 md:p-10 w-full max-w-md border border-border-subtle"
>


<!-- ====================================================== -->
<!-- STEP 1 -->
<!-- ====================================================== -->

<?php if ($resetStep === 1): ?>


<div class="mb-8">

<h1
class="text-3xl font-semibold mb-3"
>
Reset Your Password
</h1>


<p
class="text-on-surface-variant"
>

Enter your registered email address to receive a verification code.

</p>

</div>


<?php if (!empty($message)): ?>

<div
class="mb-6 flex gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700"
>

<span class="material-symbols-outlined">
error
</span>

<p class="text-sm">
<?= htmlspecialchars($message) ?>
</p>

</div>

<?php endif; ?>


<form
method="POST"
action=""
class="space-y-6"
>


<div>

<label
for="email"
class="block text-sm font-medium mb-2"
>
Email Address
</label>


<input
id="email"
name="email"
type="email"
placeholder="name@example.com"
value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
required
class="w-full px-4 py-3 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
/>

</div>


<button
type="submit"
name="send_code"
class="w-full bg-[#1E88E5] text-white font-medium py-4 rounded-xl hover:opacity-95 transition-all flex justify-center items-center gap-2"
>

Send Verification Code

<span class="material-symbols-outlined text-[20px]">
send
</span>

</button>

</form>


<!-- ====================================================== -->
<!-- STEP 2 -->
<!-- ====================================================== -->

<?php elseif ($resetStep === 2): ?>


<div class="mb-7">


<div
class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-5"
>

<span
class="material-symbols-outlined text-primary text-3xl"
>
mark_email_read
</span>

</div>


<h1
class="text-3xl font-semibold mb-3"
>
Verify Your Email
</h1>


<p
class="text-on-surface-variant"
>

Enter the 6-digit verification code for

<strong>
<?= htmlspecialchars($_SESSION['reset_email'] ?? '') ?>
</strong>

</p>

</div>
<?php if (!empty($message)): ?>

<div
class="mb-5 flex gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700"
>

<span class="material-symbols-outlined">
error
</span>

<p class="text-sm">
<?= htmlspecialchars($message) ?>
</p>

</div>

<?php endif; ?>


<form
method="POST"
action=""
id="otpForm"
class="space-y-6"
>


<input
type="hidden"
name="otp"
id="otp"
/>


<div>

<label
class="block text-sm font-medium mb-3"
>
Verification Code
</label>


<div class="grid grid-cols-6 gap-2">


<?php for ($i = 1; $i <= 6; $i++): ?>

<input
type="text"
inputmode="numeric"
maxlength="1"
class="otp-box w-full aspect-square min-w-0 text-center text-xl font-bold rounded-xl border border-outline-variant focus:border-primary focus:ring-2 focus:ring-primary/20 outline-none"
/>

<?php endfor; ?>


</div>

</div>


<button
type="submit"
name="verify_otp"
class="w-full bg-[#1E88E5] text-white font-medium py-4 rounded-xl hover:opacity-95 transition-all"
>

Verify Code

</button>

</form>


<div class="mt-6 text-center">

<p class="text-sm text-on-surface-variant">
Didn't receive the code?
</p>


<form
method="POST"
action=""
class="mt-2"
>

<input
type="hidden"
name="email"
value="<?= htmlspecialchars($_SESSION['reset_email'] ?? '') ?>"
/>

<button
type="submit"
name="send_code"
class="text-primary text-sm font-semibold hover:underline"
>
Resend Verification Code
</button>

</form>

</div>


<!-- ====================================================== -->
<!-- STEP 3 -->
<!-- ====================================================== -->

<?php elseif ($resetStep === 3): ?>


<div class="mb-8">


<div
class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center mb-5"
>

<span
class="material-symbols-outlined text-primary text-3xl"
>
lock_reset
</span>

</div>


<h1
class="text-3xl font-semibold mb-3"
>
Create New Password
</h1>


<p
class="text-on-surface-variant"
>

Your email has been verified. Create a new password for your SafeHands account.

</p>

</div>


<?php if (!empty($message)): ?>

<div
class="mb-6 flex gap-3 p-4 rounded-xl bg-red-50 border border-red-200 text-red-700"
>

<span class="material-symbols-outlined">
error
</span>

<p class="text-sm">
<?= htmlspecialchars($message) ?>
</p>

</div>

<?php endif; ?>


<form
method="POST"
action=""
class="space-y-5"
>


<!-- NEW PASSWORD -->

<div>

<label
for="new_password"
class="block text-sm font-medium mb-2"
>
New Password
</label>


<div class="relative">

<input
id="new_password"
name="new_password"
type="password"
placeholder="Enter new password"
autocomplete="new-password"
required
class="w-full px-4 py-3 pr-12 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
/>


<button
type="button"
onclick="togglePassword('new_password','newPasswordIcon')"
class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary"
>

<span
id="newPasswordIcon"
class="material-symbols-outlined text-[21px]"
>
visibility
</span>

</button>

</div>

</div>


<!-- CONFIRM PASSWORD -->

<div>

<label
for="confirm_password"
class="block text-sm font-medium mb-2"
>
Confirm New Password
</label>


<div class="relative">

<input
id="confirm_password"
name="confirm_password"
type="password"
placeholder="Confirm new password"
autocomplete="new-password"
required
class="w-full px-4 py-3 pr-12 rounded-xl border border-outline-variant focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary"
/>


<button
type="button"
onclick="togglePassword('confirm_password','confirmPasswordIcon')"
class="absolute right-4 top-1/2 -translate-y-1/2 text-outline hover:text-primary"
>

<span
id="confirmPasswordIcon"
class="material-symbols-outlined text-[21px]"
>
visibility
</span>

</button>

</div>

</div>


<!-- REQUIREMENTS -->

<div
class="bg-blue-50 border border-blue-100 rounded-xl p-4"
>

<p
class="text-sm font-semibold mb-3"
>
Password must contain:
</p>


<div
class="space-y-2 text-sm text-on-surface-variant"
>

<p>✓ At least 8 characters</p>

<p>✓ At least one uppercase letter</p>

<p>✓ At least one lowercase letter</p>

<p>✓ At least one number</p>

<p>✓ At least one special character</p>

</div>

</div>


<button
type="submit"
name="reset_password"
class="w-full bg-[#1E88E5] text-white font-medium py-4 rounded-xl hover:opacity-95 transition-all flex items-center justify-center gap-2"
>

Reset Password

<span
class="material-symbols-outlined text-[20px]"
>
lock_reset
</span>

</button>

</form>


<!-- ====================================================== -->
<!-- STEP 4 -->
<!-- ====================================================== -->

<?php elseif ($resetStep === 4): ?>


<div class="text-center py-6">


<div
class="w-24 h-24 mx-auto rounded-full bg-green-50 flex items-center justify-center mb-7"
>

<span
class="material-symbols-outlined text-green-700 text-6xl"
>
check_circle
</span>

</div>


<h1
class="text-3xl font-semibold mb-3"
>
Password Reset Successful
</h1>


<p
class="text-on-surface-variant leading-7 mb-8"
>

Your SafeHands account password has been successfully changed.

You can now sign in using your new password.

</p>


<a
href="login.php"
class="w-full bg-[#1E88E5] text-white font-medium py-4 rounded-xl hover:opacity-95 transition-all flex items-center justify-center gap-2"
>

Back to Login

<span
class="material-symbols-outlined text-[20px]"
>
login
</span>

</a>


<div
class="mt-6 p-4 rounded-xl bg-green-50 border border-green-100"
>

<div
class="flex items-center justify-center gap-2 text-green-800 text-sm"
>

<span
class="material-symbols-outlined text-[19px]"
>
verified_user
</span>

Your account is secure and ready to use.

</div>

</div>

<?php endif; ?>


<!-- BACK TO LOGIN -->

<?php if ($resetStep !== 4): ?>

<div class="mt-8 text-center">

<a
href="login.php"
class="text-sm font-medium text-primary hover:underline flex items-center justify-center gap-1"
>

<span
class="material-symbols-outlined text-[18px]"
>
arrow_back
</span>

Back to Login

</a>

</div>

<?php endif; ?>


<!-- TRUST -->

<div
class="mt-10 pt-7 border-t border-border-subtle text-center opacity-60"
>

<div
class="flex justify-center gap-6 mb-3"
>

<span class="material-symbols-outlined text-outline">
verified_user
</span>

<span class="material-symbols-outlined text-outline">
shield_with_heart
</span>

<span class="material-symbols-outlined text-outline">
encrypted
</span>

</div>


<p class="text-xs text-outline">
Secure clinical platform encryption
</p>

</div>

</div>

</div>

</main>


<!-- ====================================================== -->
<!-- FOOTER -->
<!-- ====================================================== -->

<footer
class="bg-surface-container border-t border-border-subtle"
>

<div
class="flex flex-col md:flex-row justify-between items-center py-8 px-6 md:px-10 max-w-7xl mx-auto gap-4"
>

<div
class="flex flex-col md:flex-row items-center gap-2 md:gap-8"
>

<span
class="text-xl font-bold text-primary"
>
SafeHands
</span>

<p
class="text-xs text-on-surface-variant"
>
© <?= date("Y") ?> SafeHands Healthcare. All rights reserved.
</p>

</div>


<nav
class="flex flex-wrap justify-center gap-6"
>

<a
href="#"
class="text-xs text-on-surface-variant hover:text-primary"
>
Privacy Policy
</a>

<a
href="#"
class="text-xs text-on-surface-variant hover:text-primary"
>
Terms of Service
</a>

<a
href="#"
class="text-xs text-on-surface-variant hover:text-primary"
>
Cookie Policy
</a>

<a
href="#"
class="text-xs text-on-surface-variant hover:text-primary"
>
Accessibility
</a>

</nav>

</div>

</footer>


<!-- ====================================================== -->
<!-- JAVASCRIPT -->
<!-- ====================================================== -->

<script>

/*
|--------------------------------------------------------------------------
| OTP BOXES
|--------------------------------------------------------------------------
*/

const otpBoxes =
    document.querySelectorAll('.otp-box');

const otpInput =
    document.getElementById('otp');

const otpForm =
    document.getElementById('otpForm');


if (otpBoxes.length > 0) {

    otpBoxes.forEach(
        (box, index) => {

            box.addEventListener(
                'input',
                function () {

                    this.value =
                        this.value.replace(
                            /[^0-9]/g,
                            ''
                        );

                    if (
                        this.value
                        &&
                        index < otpBoxes.length - 1
                    ) {

                        otpBoxes[
                            index + 1
                        ].focus();
                    }

                }
            );


            box.addEventListener(
                'keydown',
                function (event) {

                    if (
                        event.key === 'Backspace'
                        &&
                        !this.value
                        &&
                        index > 0
                    ) {

                        otpBoxes[
                            index - 1
                        ].focus();
                    }

                }
            );


            box.addEventListener(
                'paste',
                function (event) {

                    event.preventDefault();

                    const pasted =
                        event.clipboardData
                            .getData('text')
                            .replace(/\D/g, '')
                            .slice(0, 6);

                    pasted
                        .split('')
                        .forEach(
                            (number, i) => {

                                if (otpBoxes[i]) {

                                    otpBoxes[i].value =
                                        number;
                                }

                            }
                        );

                }
            );

        }
    );


    if (
        otpForm
        &&
        otpInput
    ) {

        otpForm.addEventListener(
            'submit',
            function () {

                let fullOtp = '';

                otpBoxes.forEach(
                    box => {

                        fullOtp +=
                            box.value;

                    }
                );

                otpInput.value =
                    fullOtp;

            }
        );
    }
}


/*
|--------------------------------------------------------------------------
| SHOW / HIDE PASSWORD
|--------------------------------------------------------------------------
*/

function togglePassword(
    inputId,
    iconId
) {

    const input =
        document.getElementById(
            inputId
        );

    const icon =
        document.getElementById(
            iconId
        );

    if (!input || !icon) {
        return;
    }

    if (
        input.type === "password"
    ) {

        input.type = "text";

        icon.textContent =
            "visibility_off";

    } else {

        input.type =
            "password";

        icon.textContent =
            "visibility";

    }

}

</script>


</body>

</html>
