<?php

require 'includes/db.php';

$message = "";
$messageType = "";

if(isset($_POST['register']))
{

    $first_name = trim($_POST['first_name']);
    $last_name = trim($_POST['last_name']);
    $nic = trim($_POST['nic']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Empty validation
    if(
        empty($first_name) ||
        empty($last_name) ||
        empty($nic) ||
        empty($phone) ||
        empty($email) ||
        empty($address) ||
        empty($password) ||
        empty($confirm_password)
    )
    {

        $message = "Please fill in all required fields.";
        $messageType = "error";

    }

    // Password validation
    elseif($password != $confirm_password)
    {

        $message = "Passwords do not match.";
        $messageType = "error";

    }

    else
    {

        // Check Email
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email=?");
        $stmt->bind_param("s",$email);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result->num_rows > 0)
        {

            $message = "Email already exists.";
            $messageType = "error";

        }

        else
        {

            // Check NIC

            $stmt = $conn->prepare("SELECT user_id FROM users WHERE nic=?");
            $stmt->bind_param("s",$nic);
            $stmt->execute();
            $result = $stmt->get_result();

            if($result->num_rows > 0)
            {

                $message = "NIC already exists.";
                $messageType = "error";

            }

            else
            {

                // Encrypt Password

                $hashedPassword = password_hash($password,PASSWORD_DEFAULT);

                // Insert User

                $stmt = $conn->prepare("

                INSERT INTO users

                (first_name,last_name,nic,phone,email,address,password,role,status)

                VALUES

                (?,?,?,?,?,?,?,?,?)

                ");

                $role="Family";

                $status="Active";

                $stmt->bind_param(

                    "sssssssss",

                    $first_name,

                    $last_name,

                    $nic,

                    $phone,

                    $email,

                    $address,

                    $hashedPassword,

                    $role,

                    $status

                );

                if($stmt->execute())
                {

                    $message="Registration Successful!";
                    $messageType="success";

                    $_SESSION['success'] = "Registration successful! Please login.";
                    header("Location: login.php");
                    exit();

                }
                else
                {

                    $message="Something went wrong!";
                    $messageType="error";

                }

            }

        }

    }

}

?>

<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Register Family Member | SafeHands</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "inverse-surface": "#263143",
                        "secondary-container": "#8aacfe",
                        "on-surface-variant": "#434655",
                        "secondary": "#375ca8",
                        "surface-tint": "#0053db",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-fixed-variant": "#1a438e",
                        "surface-bright": "#f9f9ff",
                        "primary": "#004ac6",
                        "tertiary-fixed": "#ffdbcd",
                        "on-primary-container": "#eeefff",
                        "secondary-fixed": "#d9e2ff",
                        "secondary-fixed-dim": "#b0c6ff",
                        "on-tertiary-fixed": "#360f00",
                        "primary-fixed-dim": "#b4c5ff",
                        "inverse-on-surface": "#ecf1ff",
                        "error-container": "#ffdad6",
                        "surface-variant": "#d8e3fb",
                        "surface-dim": "#cfdaf2",
                        "on-background": "#111c2d",
                        "status-warning": "#FEBB02",
                        "primary-container": "#2563eb",
                        "error": "#ba1a1a",
                        "tertiary-fixed-dim": "#ffb596",
                        "surface-container-low": "#f0f3ff",
                        "tertiary-container": "#bc4800",
                        "on-surface": "#111c2d",
                        "on-primary-fixed-variant": "#003ea8",
                        "border-subtle": "#F1F5F9",
                        "on-error": "#ffffff",
                        "primary-fixed": "#dbe1ff",
                        "on-primary": "#ffffff",
                        "inverse-primary": "#b4c5ff",
                        "on-secondary-container": "#113e89",
                        "background": "#f9f9ff",
                        "status-info": "#AAECF3",
                        "on-secondary": "#ffffff",
                        "surface-container": "#e7eeff",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "outline-variant": "#c3c6d7",
                        "on-tertiary": "#ffffff",
                        "on-error-container": "#93000a",
                        "tertiary": "#943700",
                        "on-tertiary-container": "#ffede6",
                        "surface-container-highest": "#d8e3fb",
                        "status-success": "#025747",
                        "on-secondary-fixed": "#001945",
                        "surface-container-high": "#dee8ff",
                        "on-primary-fixed": "#00174b",
                        "surface-muted": "#F8FAFC",
                        "surface": "#f9f9ff",
                        "outline": "#737686"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "margin-desktop": "40px",
                        "base": "8px",
                        "gutter": "24px",
                        "container-max": "1280px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "label-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-md": ["Inter"],
                        "display-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "label-md": ["14px", { "lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500" }],
                        "label-sm": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "body-md": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "display-lg": ["48px", { "lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                    }
                },
            },
        }
    </script>
<style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .form-input-focus:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }
    </style>
</head>
<body class="bg-background text-on-surface selection:bg-primary-container selection:text-on-primary-container">
<!-- TopNavBar -->
<header class="fixed top-0 w-full z-50 bg-surface/90 backdrop-blur-md border-b border-subtle shadow-sm">
<nav class="flex justify-between items-center h-20 px-margin-desktop max-w-container-max mx-auto">
<div class="flex items-center gap-8">
<span class="text-headline-md font-headline-md font-bold text-primary">SafeHands</span>
<div class="hidden md:flex gap-6">
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#">Home</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#">About</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#">Services</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#">Find Caregivers</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors" href="#">Contact</a>
</div>
</div>
<div class="flex items-center gap-4">
<a class="font-label-md text-label-md text-on-surface-variant hover:bg-surface-muted px-4 py-2 rounded-lg transition-all" href="#">Login</a>
<a class="bg-primary text-on-primary font-label-md text-label-md px-6 py-2.5 rounded-xl hover:opacity-90 transition-all shadow-md" href="#">Register</a>
</div>
</nav>
</header>
<main class="pt-20 h-[calc(100vh-80px)] flex flex-col lg:flex-row overflow-hidden"><!-- Left Side: Form Section -->
<section class="flex-1 px-margin-mobile md:px-margin-desktop py-6 lg:py-8 flex flex-col justify-center max-w-2xl mx-auto lg:mx-0 lg:max-w-none lg:w-1/2 overflow-y-auto"><div class="max-w-xl mx-auto lg:ml-auto lg:mr-margin-desktop">
<!-- Breadcrumb -->
<nav class="flex items-center gap-2 mb-6 text-on-surface-variant font-label-md text-label-md">
<span class="hover:text-primary cursor-pointer">Register</span>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-primary font-bold">Family Member</span>
</nav>
<h1 class="font-headline-lg text-headline-lg text-on-surface mb-4">Create Your Family Member Account</h1>
<p class="font-body-md text-body-md text-on-surface-variant mb-10 leading-relaxed">
                    Create your account to find trusted caregivers, manage patient profiles, book caregiving services, and receive daily care updates.
                </p>
<!-- Registration Form Card -->
<div class="bg-surface-container-lowest border border-subtle p-6 rounded-xl shadow-md"><?php if($message!=""){ ?>

<div class="mb-6 rounded-xl p-4

<?php

if($messageType=="success")

echo "bg-green-100 border border-green-400 text-green-700";

else

echo "bg-red-100 border border-red-400 text-red-700";

?>

">

<?= $message ?>

</div>

<?php } ?>
<form class="space-y-4" method="POST" action="">
<!-- First Name & Last Name -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <!-- First Name -->
    <div class="space-y-1.5">
        <label class="font-label-md text-label-md text-on-surface">
            First Name *
        </label>

        <input
            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
            type="text"
            name="first_name"
            placeholder="Enter your first name"
            required>
    </div>

    <!-- Last Name -->
    <div class="space-y-1.5">
        <label class="font-label-md text-label-md text-on-surface">
            Last Name *
        </label>

        <input
            class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
            type="text"
            name="last_name"
            placeholder="Enter your last name"
            required>
    </div>

</div>
<!-- NIC -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">National Identity Card (NIC) *</label>
<input
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    type="text"
    name="nic"
    placeholder="Enter your NIC"
    required></div>
<!-- Phone -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">Phone Number *</label>
<input
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    type="tel"
    name="phone"
    placeholder="+94 77 123 4567"
    required>
</div>

<!-- Email -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">Email Address *</label>
<input
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    type="email"
    name="email"
    placeholder="Enter your email"
    required>
<span class="material-symbols-outlined text-[14px]">check_circle</span> ✓ Valid email address
                            
</div>
<!-- Home Address -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">Home Address *</label>
<textarea
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    name="address"
    rows="3"
    placeholder="Enter your home address"
    required></textarea>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<!-- Password -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">Password *</label>
<input
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    type="password"
    name="password"
    placeholder="Enter your password"
    required>
<p class="text-on-surface-variant font-label-sm text-label-sm mt-1">Password must contain at least 8 characters.</p>
</div>
<!-- Confirm Password -->
<div class="space-y-1.5">
<label class="font-label-md text-label-md text-on-surface">Confirm Password *</label>
<input
    class="w-full px-4 py-3 rounded-xl border border-outline-variant bg-surface-bright text-body-md form-input-focus transition-all"
    type="password"
    name="confirm_password"
    placeholder="Confirm your password"
    required>
</div>
</div>
<!-- Terms -->
<div class="flex items-start gap-3">
<input type="checkbox" id="terms" name="terms" required>

<label class="font-body-md text-body-md text-on-surface-variant" for="terms">
                                I agree to the <a class="text-primary hover:underline" href="#">Terms &amp; Conditions</a> and <a class="text-primary hover:underline" href="#">Privacy Policy</a>.
                            </label>
</div>
<!-- Submit -->
<div class="space-y-4 pt-4">
<button
type="submit"
name="register"
class="mt-8 w-full bg-blue-600 hover:bg-blue-700 text-white py-4 rounded-xl font-semibold">
    Create Account
</button>                           
<p class="text-center font-body-md text-body-md text-on-surface-variant">
                                Already have an account? <a class="text-primary font-bold hover:underline" href="#">Login</a>
</p>
</div>
</form>
</div>
</div>
</section>
<!-- Right Side: Image & Quote Section -->
<section class="hidden lg:flex flex-1 relative bg-surface-container-high overflow-hidden min-h-screen">
<img
    src="assets/images/backgrounds/family-register.jpg"
    alt="Caregiver helping senior"
    class="absolute inset-0 w-full h-full object-cover">
<!-- Dark Overlay for better contrast -->
<div class="absolute inset-0 bg-gradient-to-t from-on-surface/40 via-transparent to-transparent"></div>
<!-- Quote Card Overlay -->
<div class="absolute bottom-12 left-12 right-12">
<div class="bg-surface/90 backdrop-blur-lg p-8 rounded-2xl shadow-2xl border border-white/20 max-w-md transform hover:-translate-y-1 transition-all duration-500">
<span class="material-symbols-outlined text-primary text-[40px] mb-4" style="font-variation-settings: 'FILL' 1;">format_quote</span>
<p class="font-headline-md text-headline-md text-on-surface italic leading-snug">
                        "Finding trusted care for your loved ones has never been easier."
                    </p>
<div class="mt-6 flex items-center gap-4">
<div class="w-12 h-12 rounded-full bg-primary-container flex items-center justify-center">
<span class="material-symbols-outlined text-on-primary-container">verified_user</span>
</div>
<div>
<p class="font-label-md text-label-md text-on-surface font-bold">SafeHands Certified</p>
<p class="font-label-sm text-label-sm text-on-surface-variant">Trusted by 10,000+ families</p>
</div>
</div>
</div>
</div>
</section>
</main>
<!-- Footer -->
<footer class="bg-surface-muted border-t border-subtle w-full py-6">
<div class="grid grid-cols-1 md:grid-cols-4 gap-gutter px-margin-desktop max-w-container-max mx-auto">
<div class="space-y-4">
<span class="text-headline-md font-headline-md font-bold text-on-surface">SafeHands</span>
<p class="text-on-surface-variant font-body-md text-body-md max-w-xs">Professional healthcare SaaS providing clinical-grade care solutions for families and institutions.</p>
</div>
<div class="flex flex-col gap-3">
<p class="font-label-md text-label-md text-primary font-bold mb-2">Quick Links</p>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Services</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Find Caregivers</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Contact Support</a>
</div>
<div class="flex flex-col gap-3">
<p class="font-label-md text-label-md text-primary font-bold mb-2">Legal</p>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Privacy Policy</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Terms of Service</a>
<a class="text-on-surface-variant hover:text-primary transition-colors font-label-sm text-label-sm" href="#">Cookie Settings</a>
</div>
<div class="flex flex-col gap-3">
<p class="font-label-md text-label-md text-primary font-bold mb-2">Social</p>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center hover:bg-primary hover:text-on-primary transition-all" href="#">
<span class="material-symbols-outlined">face_nod</span>
</a>
<a class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center hover:bg-primary hover:text-on-primary transition-all" href="#">
<span class="material-symbols-outlined">brand_awareness</span>
</a>
<a class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center hover:bg-primary hover:text-on-primary transition-all" href="#">
<span class="material-symbols-outlined">chat</span>
</a>
</div>
<p class="mt-6 text-on-surface-variant font-label-sm text-label-sm opacity-60">© 2024 SafeHands. All rights reserved.</p>
</div>
</div>
</footer>
<script>
        // Micro-interactions: Input highlight and simple float animations
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.querySelector('label').classList.add('text-primary');
            });
            input.addEventListener('blur', () => {
                input.parentElement.querySelector('label').classList.remove('text-primary');
            });
        });

        // Background floating elements simulation via JS for clinical calm atmosphere
        const createAtmosphere = () => {
            const container = document.body;
            for(let i=0; i<3; i++) {
                const blur = document.createElement('div');
                blur.className = `fixed -z-10 bg-primary/5 rounded-full blur-[120px] pointer-events-none`;
                blur.style.width = Math.random() * 400 + 300 + 'px';
                blur.style.height = blur.style.width;
                blur.style.left = Math.random() * 80 + '%';
                blur.style.top = Math.random() * 80 + '%';
                container.appendChild(blur);
            }
        };
        createAtmosphere();
    </script>
</body></html>