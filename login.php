<?php

session_start();
require 'includes/db.php';

$message = "";

require 'includes/db.php';

// If user is already logged in, optionally redirect them
if (isset($_SESSION['user_id'], $_SESSION['role'])) {

    if ($_SESSION['role'] === 'Family') {
        header("Location: family/dashboard.php");
        exit();
    }

    if ($_SESSION['role'] === 'Caregiver') {
        header("Location: caregiver/dashboard.php");
        exit();
    }

    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin/dashboard.php");
        exit();
    }
}


// Handle Login Form
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Check empty fields
    if (empty($email) || empty($password)) {

        $message = "Please enter your email and password.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        // Find user using email
        $stmt = $conn->prepare("
            SELECT 
                user_id,
                first_name,
                last_name,
                email,
                password,
                role,
                status
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        if (!$stmt) {

            $message = "Unable to process login. Please try again.";

        } else {

            $stmt->bind_param("s", $email);
            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows === 1) {

                $user = $result->fetch_assoc();

                // Verify hashed password
                if (password_verify($password, $user['password'])) {

                    // Check account status
                    if ($user['status'] !== 'Active') {

                        $message = "Your account is currently inactive.";

                    } else {

                        // Regenerate session ID after successful login
                        session_regenerate_id(true);

                        // Store user information in session
                        $_SESSION['user_id'] = $user['user_id'];
                        $_SESSION['first_name'] = $user['first_name'];
                        $_SESSION['last_name'] = $user['last_name'];
                        $_SESSION['email'] = $user['email'];
                        $_SESSION['role'] = $user['role'];

                        // Redirect according to user role
                        if ($user['role'] === 'Family') {

                            header("Location: family/dashboard.php");
                            exit();

                        } elseif ($user['role'] === 'Caregiver') {

                            header("Location: caregiver/dashboard.php");
                            exit();

                        } elseif ($user['role'] === 'Admin') {

                            header("Location: admin/dashboard.php");
                            exit();

                        } else {

                            // Remove invalid session
                            session_unset();
                            session_destroy();

                            $message = "Invalid account role.";
                        }
                    }

                } else {

                    $message = "Invalid email or password.";
                }

            } else {

                $message = "Invalid email or password.";
            }

            $stmt->close();
        }
    }
}

?>

<!DOCTYPE html>

<html class="light" lang="en">

<head>

<meta charset="utf-8"/>

<meta
    content="width=device-width, initial-scale=1.0"
    name="viewport"
/>

<title>SafeHands - Premium Login</title>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet"
/>

<link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
    rel="stylesheet"
/>


<script id="tailwind-config">

tailwind.config = {

    darkMode: "class",

    theme: {

        extend: {

            colors: {

                "on-secondary-fixed": "#001945",
                "on-error": "#ffffff",
                "tertiary-fixed-dim": "#ffb596",
                "tertiary-fixed": "#ffdbcd",
                "secondary-fixed-dim": "#b0c6ff",
                "on-error-container": "#93000a",
                "surface-tint": "#0053db",
                "on-tertiary": "#ffffff",
                "primary-fixed": "#dbe1ff",
                "on-primary-fixed": "#00174b",
                "surface-container-lowest": "#ffffff",
                "on-primary-container": "#eeefff",
                "secondary-fixed": "#d9e2ff",
                "surface-container": "#e7eeff",
                "on-secondary-container": "#113e89",
                "tertiary": "#943700",
                "on-surface-variant": "#434655",
                "surface": "#f9f9ff",
                "secondary": "#375ca8",
                "on-secondary-fixed-variant": "#1a438e",
                "surface-bright": "#f9f9ff",
                "inverse-on-surface": "#ecf1ff",
                "on-background": "#111c2d",
                "on-primary": "#ffffff",
                "error-container": "#ffdad6",
                "secondary-container": "#8aacfe",
                "primary-fixed-dim": "#b4c5ff",
                "on-tertiary-container": "#ffede6",
                "on-surface": "#111c2d",
                "status-success": "#025747",
                "outline-variant": "#c3c6d7",
                "primary": "#004ac6",
                "surface-container-highest": "#d8e3fb",
                "status-info": "#AAECF3",
                "surface-variant": "#d8e3fb",
                "primary-container": "#2563eb",
                "tertiary-container": "#bc4800",
                "surface-container-high": "#dee8ff",
                "surface-muted": "#F8FAFC",
                "surface-dim": "#cfdaf2",
                "surface-container-low": "#f0f3ff",
                "background": "#f9f9ff",
                "border-subtle": "#F1F5F9",
                "inverse-primary": "#b4c5ff",
                "error": "#ba1a1a",
                "on-tertiary-fixed": "#360f00",
                "on-secondary": "#ffffff",
                "on-tertiary-fixed-variant": "#7d2d00",
                "outline": "#737686",
                "on-primary-fixed-variant": "#003ea8",
                "inverse-surface": "#263143",
                "status-warning": "#FEBB02"

            },

            borderRadius: {

                DEFAULT: "0.25rem",
                lg: "0.5rem",
                xl: "0.75rem",
                full: "9999px"

            },

            spacing: {

                "margin-desktop": "40px",
                "margin-mobile": "16px",
                "container-max": "1280px",
                "base": "8px",
                "gutter": "24px"

            },

            fontFamily: {

                "label-md": ["Inter"],
                "label-sm": ["Inter"],
                "headline-lg-mobile": ["Inter"],
                "headline-lg": ["Inter"],
                "headline-md": ["Inter"],
                "display-lg": ["Inter"],
                "body-md": ["Inter"],
                "body-lg": ["Inter"]

            },

            fontSize: {

                "label-md": [
                    "14px",
                    {
                        lineHeight: "20px",
                        letterSpacing: "0.01em",
                        fontWeight: "500"
                    }
                ],

                "label-sm": [
                    "12px",
                    {
                        lineHeight: "16px",
                        letterSpacing: "0.05em",
                        fontWeight: "600"
                    }
                ],

                "headline-lg-mobile": [
                    "24px",
                    {
                        lineHeight: "32px",
                        fontWeight: "600"
                    }
                ],

                "headline-lg": [
                    "32px",
                    {
                        lineHeight: "40px",
                        letterSpacing: "-0.01em",
                        fontWeight: "600"
                    }
                ],

                "headline-md": [
                    "24px",
                    {
                        lineHeight: "32px",
                        fontWeight: "600"
                    }
                ],

                "display-lg": [
                    "48px",
                    {
                        lineHeight: "56px",
                        letterSpacing: "-0.02em",
                        fontWeight: "700"
                    }
                ],

                "body-md": [
                    "16px",
                    {
                        lineHeight: "24px",
                        fontWeight: "400"
                    }
                ],

                "body-lg": [
                    "18px",
                    {
                        lineHeight: "28px",
                        fontWeight: "400"
                    }
                ]

            }

        }

    }

}

</script>


<style>

body {

    font-family: 'Inter', sans-serif;
    background-color: #ffffff;

}

.material-symbols-outlined {

    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;

}

.glass-card {

    background: rgba(255, 255, 255, 0.75);

    backdrop-filter: blur(12px);

    -webkit-backdrop-filter: blur(12px);

    border: 1px solid rgba(255, 255, 255, 0.3);

}

</style>

</head>


<body class="text-on-surface">


<!-- ========================================== -->
<!-- TOP NAVIGATION -->
<!-- ========================================== -->

<header
    class="fixed top-0 left-0 right-0 z-50 bg-surface/80 backdrop-blur-md shadow-sm h-20"
>

<div
    class="flex justify-between items-center h-full px-margin-desktop max-w-container-max mx-auto"
>


<!-- Logo -->

<div class="flex items-center gap-4">

<img
    alt="SafeHands Logo"
    class="h-10 w-auto"
    src="https://lh3.googleusercontent.com/aida/AP1WRLt0c3CUeHpP9NZc8QBf8z5t3qKTR4QIJkprm12x2j1jCcEsriPnneYFl3sA8_F_5URbyjwrYAomu6NMvUrhDWaSIDWszHzsBzhoCsvzixOWkUC604-clsLRm7R5H_p0DwV4szkWFwzJyqachKUnDhB4jbsIQBcl4KRfcwGGeBMIeVudjJyKlFND35Uq8YJqkiS13MspbUMqRlN6TfRkUf_JmfqGcyEWvb9TeWeXS8IqenBbn4Xyl0TUQbA"
/>

<span
    class="font-headline-md text-headline-md font-bold text-primary"
>
SafeHands
</span>

</div>


<!-- Navigation -->

<nav class="hidden md:flex items-center gap-8">

<a
    class="text-on-surface-variant font-medium hover:text-primary transition-colors"
    href="index.php"
>
Home
</a>

<a
    class="text-on-surface-variant font-medium hover:text-primary transition-colors"
    href="#"
>
About
</a>

<a
    class="text-on-surface-variant font-medium hover:text-primary transition-colors"
    href="#"
>
Services
</a>

<a
    class="text-on-surface-variant font-medium hover:text-primary transition-colors"
    href="#"
>
Contact
</a>

</nav>


<!-- Login / Register -->

<div class="flex items-center gap-4">

<a
    class="text-primary font-bold border-b-2 border-primary pb-1"
    href="login.php"
>
Login
</a>

<a
    href="register.php"
    class="bg-primary text-white px-6 py-2.5 rounded-xl font-label-md hover:opacity-90 transition-opacity"
>
Register
</a>

</div>


</div>

</header>


<!-- ========================================== -->
<!-- MAIN LOGIN AREA -->
<!-- ========================================== -->

<main class="min-h-screen pt-20 flex">


<!-- ========================================== -->
<!-- LEFT SIDE -->
<!-- ========================================== -->

<section
    class="hidden lg:flex lg:w-1/2 relative overflow-hidden"
>

<div class="absolute inset-0 z-0">

<img
    class="w-full h-full object-cover"
    alt="SafeHands caregiver helping an elderly person"
    src="https://lh3.googleusercontent.com/aida/AP1WRLuG8h9DAztOEhR8s-ufQUpWjwHRGJFj8TLy5WjvqUL5qIePMF0TH4L93pyuZwlWhd7EctnkPsNvHFAV6rc017NUVa6mAx2RtdLX8aodyeEs65GA9UgoUwwwkYGZjRhORg0V83yc6-fuI79ZgIOPfzA5r6Utu84QXgMfYOtHuoI-AAHg8jrSNcKh5XjiwylD5CiV--MAK_oiNtL_CFQT6OT1iT8LV1ITOG6FY55AIAGeA0W8wTNnYAH-g68"
/>

<div
    class="absolute inset-0 bg-gradient-to-r from-on-background/30 to-transparent"
></div>

</div>


<div
    class="relative z-10 w-full flex items-center px-16"
>

<div
    class="glass-card p-10 rounded-xl max-w-lg shadow-xl animate-in fade-in slide-in-from-left-8 duration-700"
>

<h1
    class="font-display-lg text-display-lg text-on-background mb-4"
>
Welcome Back to SafeHands
</h1>

<p
    class="font-body-lg text-body-lg text-on-surface-variant"
>
Sign in to manage caregiving services, bookings, and care updates
for your loved ones or your professional practice.
</p>


<div class="mt-8 flex gap-4">

<div class="flex -space-x-3">

<div
    class="w-10 h-10 rounded-full border-2 border-white bg-secondary-container flex items-center justify-center"
>
<span
    class="material-symbols-outlined text-white text-sm"
>
person
</span>
</div>


<div
    class="w-10 h-10 rounded-full border-2 border-white bg-primary-container flex items-center justify-center"
>
<span
    class="material-symbols-outlined text-white text-sm"
>
medical_services
</span>
</div>


<div
    class="w-10 h-10 rounded-full border-2 border-white bg-tertiary-container flex items-center justify-center"
>
<span
    class="material-symbols-outlined text-white text-sm"
>
favorite
</span>
</div>

</div>


<p
    class="text-label-sm font-label-sm self-center text-on-surface-variant"
>
Trusted by 5,000+ Families
</p>

</div>

</div>

</div>

</section>


<!-- ========================================== -->
<!-- RIGHT SIDE LOGIN -->
<!-- ========================================== -->

<section
    class="w-full lg:w-1/2 flex items-center justify-center bg-surface p-6 md:p-12"
>

<div
    class="w-full max-w-md bg-white p-10 rounded-xl shadow-lg border border-border-subtle animate-in fade-in zoom-in-95 duration-500"
>


<div class="mb-10 text-center lg:text-left">

<h2
    class="font-headline-lg text-headline-lg text-on-surface mb-2"
>
Welcome Back
</h2>

<p
    class="font-body-md text-body-md text-on-surface-variant"
>
Sign in to your SafeHands account.
</p>

</div>


<!-- ========================================== -->
<!-- LOGIN FORM -->
<!-- ========================================== -->

<form
    class="space-y-6"
    id="loginForm"
    method="POST"
    action=""
>


<!-- Email -->

<div class="space-y-2">

<label
    class="block font-label-md text-label-md text-on-surface"
    for="email"
>
Email Address
</label>


<div class="relative">

<span
    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline"
>
mail
</span>


<input
    class="w-full pl-10 pr-4 py-3 border border-border-subtle rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none bg-surface-muted"
    id="email"
    name="email"
    placeholder="Enter your email address"
    type="email"
    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
    autocomplete="email"
    required
/>

</div>

</div>


<!-- Password -->

<div class="space-y-2">

<label
    class="block font-label-md text-label-md text-on-surface"
    for="password"
>
Password
</label>


<div class="relative">

<span
    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline"
>
lock
</span>


<input
    class="w-full pl-10 pr-12 py-3 border border-border-subtle rounded-xl focus:ring-2 focus:ring-primary focus:border-primary transition-all outline-none bg-surface-muted"
    id="password"
    name="password"
    placeholder="••••••••"
    type="password"
    autocomplete="current-password"
    required
/>


<button
    class="absolute right-3 top-1/2 -translate-y-1/2 text-outline hover:text-primary"
    onclick="togglePassword()"
    type="button"
    aria-label="Show or hide password"
>

<span
    class="material-symbols-outlined"
    id="eyeIcon"
>
visibility
</span>

</button>

</div>

</div>


<!-- ========================================== -->
<!-- REMEMBER / FORGOT PASSWORD -->
<!-- ========================================== -->

<div class="flex items-center justify-between">

<label
    class="flex items-center gap-2 cursor-pointer"
>

<input
    class="w-4 h-4 rounded text-primary focus:ring-primary border-outline-variant"
    type="checkbox"
    name="remember"
    value="1"
/>

<span
    class="text-label-md font-label-md text-on-surface-variant"
>
Remember Me
</span>

</label>


<!-- Opens NEW forgot-password.php page -->

<a
    class="text-label-md font-label-md text-primary hover:underline transition-all"
    href="forgot-password.php"
>
Forgot Password?
</a>

</div>


<!-- ========================================== -->
<!-- LOGIN ERROR -->
<!-- ========================================== -->

<?php if (!empty($message)): ?>

<div
    class="p-4 rounded-lg bg-error-container text-on-error-container text-label-md"
    role="alert"
>

<?= htmlspecialchars($message) ?>

</div>

<?php endif; ?>


<!-- Login Button -->

<button
    class="w-full bg-[#1E88E5] text-white py-4 rounded-xl font-label-md text-label-md font-bold hover:bg-primary transition-colors shadow-md active:scale-95 transform duration-150"
    type="submit"
>
Login
</button>


<!-- Divider -->

<div
    class="relative flex items-center gap-4 py-4"
>

<div
    class="flex-grow h-px bg-border-subtle"
></div>

<span
    class="text-label-sm font-label-sm text-outline uppercase tracking-wider"
>
OR
</span>

<div
    class="flex-grow h-px bg-border-subtle"
></div>

</div>


<!-- Register -->

<p
    class="text-center text-body-md text-on-surface-variant"
>

Don't have an account?

<a
    class="text-primary font-bold hover:underline"
    href="register.php"
>
Register Now
</a>

</p>


</form>

</div>

</section>

</main>


<!-- ========================================== -->
<!-- PASSWORD VISIBILITY SCRIPT -->
<!-- ========================================== -->

<script>

function togglePassword() {

    const passwordInput =
        document.getElementById('password');

    const eyeIcon =
        document.getElementById('eyeIcon');


    if (passwordInput.type === 'password') {

        passwordInput.type = 'text';

        eyeIcon.innerText = 'visibility_off';

    } else {

        passwordInput.type = 'password';

        eyeIcon.innerText = 'visibility';

    }

}

</script>


</body>

</html>