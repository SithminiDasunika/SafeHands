<?php

session_start();

require_once __DIR__ . '/includes/db.php';

/*
|--------------------------------------------------------------------------
| CHECK WHETHER A FAMILY MEMBER IS LOGGED IN
|--------------------------------------------------------------------------
*/

$isFamilyLoggedIn = false;

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id']) &&
    isset($_SESSION['role']) &&
    strcasecmp($_SESSION['role'], 'Family') === 0
) {
    $isFamilyLoggedIn = true;
}


/*
|--------------------------------------------------------------------------
| HELPER FUNCTION
|--------------------------------------------------------------------------
*/

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}


/*
|--------------------------------------------------------------------------
| GET CAREGIVER ID
|--------------------------------------------------------------------------
*/

$caregiverId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($caregiverId <= 0) {
    die("Invalid caregiver.");
}


/*
|--------------------------------------------------------------------------
| GET CAREGIVER PROFILE
|--------------------------------------------------------------------------
*/

$sql = "
SELECT
    cp.*,
    u.first_name,
    u.last_name,
    u.email,
    u.phone
FROM caregiver_profiles cp
INNER JOIN users u
ON cp.user_id = u.user_id
WHERE cp.caregiver_id = ?
LIMIT 1
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $caregiverId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Caregiver not found.");
}

$caregiver = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| DYNAMIC VARIABLES
|--------------------------------------------------------------------------
*/

$fullName =
    trim(
        ($caregiver['first_name'] ?? '') .
        ' ' .
        ($caregiver['last_name'] ?? '')
    );

$photo =
    !empty($caregiver['profile_photo'])
        ? $caregiver['profile_photo']
        : 'assets/images/default-caregiver.jpg';

$experience =
    $caregiver['experience'] ?? 'N/A';

$languages =
    $caregiver['languages'] ?? 'N/A';

$qualification =
    $caregiver['qualification'] ?? '';

$biography =
    $caregiver['biography'] ?? '';

$dailyRate =
    $caregiver['daily_rate'] ?? '';

$serviceAreas =
    $caregiver['service_areas'] ?? '';

$certifications =
    $caregiver['certifications'] ?? '';

$email =
    $caregiver['email'];

$phone =
    $caregiver['phone'];

?>


<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= e($fullName) ?> | SafeHands </title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(241, 245, 249, 1);
        }
        .timeline-dot::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 24px;
            bottom: -24px;
            width: 2px;
            background: #F1F5F9;
            transform: translateX(-50%);
        }
        .timeline-item:last-child .timeline-dot::after {
            display: none;
        }
    </style>
<script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "tertiary-container": "#bc4800",
                      "secondary-container": "#8aacfe",
                      "tertiary": "#943700",
                      "surface-container-high": "#dee8ff",
                      "secondary-fixed-dim": "#b0c6ff",
                      "on-tertiary-fixed": "#360f00",
                      "surface-container": "#e7eeff",
                      "inverse-surface": "#263143",
                      "error-container": "#ffdad6",
                      "on-primary-fixed": "#00174b",
                      "primary": "#004ac6",
                      "on-background": "#111c2d",
                      "secondary-fixed": "#d9e2ff",
                      "surface-container-low": "#f0f3ff",
                      "error": "#ba1a1a",
                      "surface-dim": "#cfdaf2",
                      "status-warning": "#FEBB02",
                      "outline": "#737686",
                      "surface-variant": "#d8e3fb",
                      "on-error-container": "#93000a",
                      "on-primary-container": "#eeefff",
                      "on-secondary": "#ffffff",
                      "on-surface-variant": "#434655",
                      "on-primary-fixed-variant": "#003ea8",
                      "surface": "#f9f9ff",
                      "inverse-primary": "#b4c5ff",
                      "on-tertiary-fixed-variant": "#7d2d00",
                      "secondary": "#375ca8",
                      "primary-container": "#2563eb",
                      "status-success": "#025747",
                      "surface-muted": "#F8FAFC",
                      "on-tertiary": "#ffffff",
                      "surface-bright": "#f9f9ff",
                      "primary-fixed-dim": "#b4c5ff",
                      "tertiary-fixed-dim": "#ffb596",
                      "on-secondary-fixed": "#001945",
                      "tertiary-fixed": "#ffdbcd",
                      "on-secondary-fixed-variant": "#1a438e",
                      "surface-tint": "#0053db",
                      "surface-container-lowest": "#ffffff",
                      "on-surface": "#111c2d",
                      "on-error": "#ffffff",
                      "on-secondary-container": "#113e89",
                      "on-primary": "#ffffff",
                      "background": "#f9f9ff",
                      "on-tertiary-container": "#ffede6",
                      "surface-container-highest": "#d8e3fb",
                      "primary-fixed": "#dbe1ff",
                      "outline-variant": "#c3c6d7",
                      "status-info": "#AAECF3",
                      "border-subtle": "#F1F5F9",
                      "inverse-on-surface": "#ecf1ff"
              },
              "borderRadius": {
                      "DEFAULT": "0.25rem",
                      "lg": "0.5rem",
                      "xl": "0.75rem",
                      "full": "9999px"
              },
              "spacing": {
                      "container-max": "1280px",
                      "margin-mobile": "16px",
                      "gutter": "24px",
                      "base": "8px",
                      "margin-desktop": "40px"
              },
              "fontFamily": {
                      "label-sm": ["Inter"],
                      "display-lg": ["Inter"],
                      "body-md": ["Inter"],
                      "body-lg": ["Inter"],
                      "headline-lg": ["Inter"],
                      "label-md": ["Inter"],
                      "headline-lg-mobile": ["Inter"],
                      "headline-md": ["Inter"]
              },
              "fontSize": {
                      "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                      "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                      "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                      "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                      "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                      "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                      "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                      "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}]
              }
            },
          },
        }
      </script>
</head>
<body class="bg-background text-on-surface font-body-md">
<!-- Top Navigation Bar -->
<header class="bg-surface border-b border-subtle docked full-width top-0 sticky z-50">
<div class="flex justify-between items-center w-full h-16 px-margin-desktop max-w-container-max mx-auto">
<div class="font-headline-md text-headline-md font-bold text-primary">SafeHands </div>
<nav class="hidden md:flex items-center gap-gutter">
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Home</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">About</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Services</a>
<a class="font-label-md text-label-md text-primary border-b-2 border-primary pb-1" href="#">Find Caregivers</a>
<a class="font-label-md text-label-md text-on-surface-variant hover:text-primary transition-colors duration-200" href="#">Contact</a>
</nav>
<div class="flex items-center gap-4">
<button class="font-label-md text-label-md text-primary px-4 py-2 hover:bg-surface-container transition-all">Login</button>
<button class="font-label-md text-label-md bg-primary text-on-primary px-6 py-2.5 rounded-lg hover:opacity-90 transition-all">Register</button>
</div>
</div>
</header>
<main class="max-w-container-max mx-auto px-margin-mobile md:px-margin-desktop py-8">
<!-- Breadcrumb & Back Link -->
<nav class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
<div class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm">
<a class="hover:text-primary" href="#">Home</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<a class="hover:text-primary" href="#">Find Caregivers</a>
<span class="material-symbols-outlined text-[16px]">chevron_right</span>
<span class="text-primary font-bold">Caregiver Profile</span>
</div>
<a class="flex items-center gap-2 text-primary font-label-md text-label-md hover:underline" href="#">
<span class="material-symbols-outlined">arrow_back</span>
                Back to Caregivers
            </a>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter">
<!-- Left Column: Primary Profile Info -->
<div class="lg:col-span-8 space-y-gutter">
<!-- Profile Header Card -->
<section class="bg-surface-container-lowest rounded-xl border border-subtle shadow-sm p-6 md:p-8">
<div class="flex flex-col md:flex-row gap-8 items-start">
<div class="relative w-full md:w-48 h-48 rounded-xl overflow-hidden shrink-0 border border-subtle">
<img class="w-full h-full object-cover" data-alt="A professional studio portrait of a friendly Southeast Asian female caregiver in her late 30s. She is wearing a soft blue clinical uniform and has a warm, trustworthy expression. The lighting is bright and airy, set against a clean, minimalist medical background. The overall aesthetic is clinical, professional, and compassionate." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAYFEBbAEcdXXoqzKeMrssXcGMFE_x6eRW5LUicx0_8Juvi80zaZnsyVE6K-QhUHnzmq6nMqHYP3RNXtoxLo0ucDwBi8jMqGHrftxwzYuQfx3a-o1VeNY22SLe7OWmcGX6ewR2Nrv9Pp_B0lOmNJBBLW74iDZ77f_w96Qs2VT0MKqRibd54b6FvXif8Uv0vGdwYwGPbKn81_1pzlU_gv4Ml50ATNObn3jQPNTEUjPSRJMxs6l_epy1kHRCYGQ_PZKxWp0wy2PsRJUE"/>
</div>
<div class="flex-grow">
<div class="flex flex-wrap items-center gap-3 mb-2">
<h1 class="font-headline-lg text-headline-lg text-on-surface"><?= e($fullName) ?> </h1>
<span class="bg-status-success/10 text-status-success px-3 py-1 rounded-full text-label-sm flex items-center gap-1">
<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: 'FILL' 1;">verified</span>
                                    Verified
                                </span>
</div>
<p class="text-body-lg font-body-lg text-on-surface-variant mb-4">Senior Elderly Caregiver</p>
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 py-6 border-y border-subtle">
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-outline mb-1 uppercase tracking-wider">Rating</span>
<span class="flex items-center gap-1 font-bold text-on-surface">
<span class="material-symbols-outlined text-status-warning" style="font-variation-settings: 'FILL' 1;">star</span>
                                        4.9 <span class="text-outline font-normal">(148)</span>
</span>
</div>
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-outline mb-1 uppercase tracking-wider">Experience</span>
<span class="font-bold text-on-surface">6+<?= e($experience) ?> Years</span>
</div>
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-outline mb-1 uppercase tracking-wider">Location</span>
<span class="font-bold text-on-surface">Colombo District</span>
</div>
<div class="flex flex-col">
<span class="text-label-sm font-label-sm text-outline mb-1 uppercase tracking-wider">Languages</span>
<span class="font-bold text-on-surface"><?= e($languages) ?>
</div>
</div>
<div class="mt-6 flex flex-wrap gap-3">
<span class="bg-surface-muted border border-subtle px-3 py-1.5 rounded-lg text-label-md flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">medical_services</span>
                                    Medical Assistant
                                </span>
<span class="bg-surface-muted border border-subtle px-3 py-1.5 rounded-lg text-label-md flex items-center gap-2">
<span class="material-symbols-outlined text-primary text-[18px]">car_rental</span>
                                    Valid Driving License
                                </span>
</div>
</div>
</div>
</section>
<!-- About Me Section -->
<section class="bg-surface-container-lowest rounded-xl border border-subtle p-6 md:p-8">
<h2 class="font-headline-md text-headline-md text-on-surface mb-4">About Me</h2>
<div class="space-y-4 text-on-surface-variant text-body-md leading-relaxed">
<p>
    <?= nl2br(e($biography)) ?>
</p>
</div>
</section><section class="bg-surface-container-lowest rounded-xl border border-subtle p-6 md:p-8">

<h2 class="font-headline-md text-headline-md text-on-surface mb-6">
    Contact Information
</h2>

<?php if ($isFamilyLoggedIn): ?>

    <!-- Logged-in Family Members -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="flex items-center gap-4 p-4 bg-surface-muted rounded-lg border border-subtle">

            <span class="material-symbols-outlined text-primary">
                call
            </span>

            <div>

                <p class="text-label-sm text-outline uppercase tracking-wider">
                    Phone Number
                </p>

                <p class="font-bold text-on-surface">
                    <?= e($phone) ?>
                </p>

            </div>

        </div>


        <div class="flex items-center gap-4 p-4 bg-surface-muted rounded-lg border border-subtle">

            <span class="material-symbols-outlined text-primary">
                mail
            </span>

            <div>

                <p class="text-label-sm text-outline uppercase tracking-wider">
                    Email Address
                </p>

                <p class="font-bold text-on-surface">
                    <?= e($email) ?>
                </p>

            </div>

        </div>

    </div>

    <div class="bg-green-50 border border-green-200 rounded-xl p-5">

        <div class="flex items-center gap-3">

            <span class="material-symbols-outlined text-green-600">
                verified_user
            </span>

            <p class="text-green-700">

                You are logged in as a Family Member.

                Contact details are now available.

            </p>

        </div>

    </div>

<?php else: ?>

    <!-- Guests -->

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="flex items-center gap-4 p-4 bg-surface-muted rounded-lg border border-subtle">

            <span class="material-symbols-outlined text-outline">
                call
            </span>

            <div>

                <p class="text-label-sm text-outline uppercase tracking-wider">
                    Phone Number
                </p>

                <p class="font-bold text-on-surface-variant">
                    +94 ••• ••• •••
                </p>

            </div>

        </div>


        <div class="flex items-center gap-4 p-4 bg-surface-muted rounded-lg border border-subtle">

            <span class="material-symbols-outlined text-outline">
                mail
            </span>

            <div>

                <p class="text-label-sm text-outline uppercase tracking-wider">
                    Email Address
                </p>

                <p class="font-bold text-on-surface-variant">
                    s••••••@email.com
                </p>

            </div>

        </div>

    </div>


    <div class="bg-primary/5 border border-primary/20 rounded-xl p-6 flex flex-col md:flex-row items-center justify-between gap-6">

        <div class="flex gap-4">

            <span class="material-symbols-outlined text-primary"
                  style="font-variation-settings:'FILL' 1;">
                lock
            </span>

            <p class="text-body-md text-on-primary-fixed-variant">

                Contact information is only available to
                registered Family Members.

            </p>

        </div>

        <a href="login.php"
           class="bg-primary text-white px-6 py-3 rounded-lg hover:opacity-90 transition">

            Login to View Contact Details

        </a>

    </div>

<?php endif; ?>

</section>
<!-- Professional Skills Bento -->
<section>
<h2 class="font-headline-md text-headline-md text-on-surface mb-6">Professional Skills</h2>
<div class="flex flex-wrap gap-2">
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Medication Assistance</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Personal Hygiene Support</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Meal Preparation</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Mobility Assistance</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Companionship</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Dementia Care</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Blood Pressure Monitoring</span>
<span class="bg-primary/5 text-primary border border-primary/20 px-4 py-2 rounded-xl text-label-md font-medium">Emergency Response</span>
</div>
</section>
<!-- Experience Timeline -->
<section class="bg-surface-container-lowest rounded-xl border border-subtle p-6 md:p-8">
<h2 class="font-headline-md text-headline-md text-on-surface mb-8">Work Experience</h2>
<div class="space-y-12">
<!-- Timeline Item 1 -->
<div class="flex gap-6 timeline-item relative">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0 timeline-dot relative z-10">
<span class="material-symbols-outlined text-primary">home_health</span>
</div>
<div class="pt-1">
<h3 class="font-bold text-on-surface text-body-lg">Senior Home Care Specialist</h3>
<p class="text-primary font-medium mb-2">ABC Home Care</p>
<span class="bg-surface-muted px-2 py-1 rounded text-label-sm text-outline">2022 - Present</span>
<p class="mt-4 text-on-surface-variant text-body-md">Managing 24/7 care for high-needs elderly patients, including medication administration and rehabilitative exercises.</p>
</div>
</div>
<!-- Timeline Item 2 -->
<div class="flex gap-6 timeline-item relative">
<div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center shrink-0 timeline-dot relative z-10">
<span class="material-symbols-outlined text-primary">wb_sunny</span>
</div>
<div class="pt-1">
<h3 class="font-bold text-on-surface text-body-lg">Certified Care Assistant</h3>
<p class="text-primary font-medium mb-2">Sunrise Elder Care</p>
<span class="bg-surface-muted px-2 py-1 rounded text-label-sm text-outline">2019 - 2022</span>
<p class="mt-4 text-on-surface-variant text-body-md">Assisted residents with daily living activities, monitored vital signs, and supported social engagement programs.</p>
</div>
</div>
</div>
</section>
<!-- Reviews -->
<section class="space-y-6">
<div class="flex items-center justify-between">
<h2 class="font-headline-md text-headline-md text-on-surface">Client Reviews</h2>
<button class="text-primary font-label-md text-label-md hover:underline flex items-center gap-1">
                            View All 148 Reviews <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<!-- Review 1 -->
<div class="bg-surface-container-lowest border border-subtle p-6 rounded-xl flex flex-col h-full">
<div class="flex items-center gap-1 text-status-warning mb-3">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface-variant italic mb-6 flex-grow">"Sarah was incredible with my mother. Her professionalism and kind heart made a difficult transition so much easier for our family."</p>
<div>
<p class="font-bold text-on-surface">Amali Perera</p>
<p class="text-label-sm text-outline">October 2023</p>
</div>
</div>
<!-- Review 2 -->
<div class="bg-surface-container-lowest border border-subtle p-6 rounded-xl flex flex-col h-full">
<div class="flex items-center gap-1 text-status-warning mb-3">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface-variant italic mb-6 flex-grow">"Extremely punctual and organized. She handles medication reminders perfectly. Highly recommended for clinical home care."</p>
<div>
<p class="font-bold text-on-surface">Ranjan Silva</p>
<p class="text-label-sm text-outline">August 2023</p>
</div>
</div>
<!-- Review 3 -->
<div class="bg-surface-container-lowest border border-subtle p-6 rounded-xl flex flex-col h-full">
<div class="flex items-center gap-1 text-status-warning mb-3">
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined text-[18px]" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface-variant italic mb-6 flex-grow">"Sarah's expertise in dementia care was evident from day one. She knew exactly how to de-escalate stressful situations."</p>
<div>
<p class="font-bold text-on-surface">Dinali K.</p>
<p class="text-label-sm text-outline">July 2023</p>
</div>
</div>
</div>
</section>
</div>
<!-- Right Column: Sidebar Actions & Details -->
<aside class="lg:col-span-4 space-y-gutter">
<!-- Notice Section -->
<?php if (!$isFamilyLoggedIn): ?>

<section class="bg-primary/5 border border-primary/20 rounded-xl p-6 flex gap-4">

    <span
        class="material-symbols-outlined text-primary"
        style="font-variation-settings:'FILL' 1;">

        lock

    </span>

    <p class="text-label-md text-on-primary-fixed-variant">

        Available work shifts, booking functionality and
        caregiver contact information are only available
        to registered Family Members.

    </p>

</section>

<?php endif; ?>
<!-- Call to Action Card -->
<section class="bg-primary p-8 rounded-xl shadow-lg text-on-primary">

<?php if ($isFamilyLoggedIn): ?>

    <h2 class="font-headline-md text-headline-md mb-4">
        Ready to Book This Caregiver?
    </h2>

    <p class="opacity-90 mb-8 text-body-md">

        You can now request this caregiver directly through
        SafeHands.

    </p>

    <a
        href="book-caregiver.php?id=<?= $caregiverId ?>"
        class="w-full bg-white text-primary font-bold py-4 rounded-lg hover:bg-gray-100 transition-all text-center block">

        Book Caregiver

    </a>

<?php else: ?>

    <h2 class="font-headline-md text-headline-md mb-4">
        Ready to Book This Caregiver?
    </h2>

    <p class="opacity-90 mb-8 text-body-md">

        Create a SafeHands account or sign in to view available
        shifts and request a booking.

    </p>

    <div class="flex flex-col gap-3">

        <a
            href="login.php"
            class="w-full bg-white text-primary font-bold py-4 rounded-lg hover:bg-gray-100 transition-all text-center">

            Login

        </a>

        <a
            href="register.php"
            class="w-full bg-primary-container text-on-primary border border-white/30 font-bold py-4 rounded-lg hover:bg-white/10 transition-all text-center">

            Create Account

        </a>

    </div>

<?php endif; ?>

</section>
<!-- Qualifications -->
<section class="bg-surface-container-lowest rounded-xl border border-subtle p-6 space-y-4">
<h2 class="font-bold text-on-surface text-body-lg">Qualifications</h2>
<div class="grid grid-cols-1 gap-3">
<div class="flex items-center gap-3 p-3 bg-surface-muted rounded-lg border border-subtle">
<span class="material-symbols-outlined text-primary">school</span>
<span class="text-label-md font-medium"><?= e($qualification) ?></span>
</div>
<div class="flex items-center gap-3 p-3 bg-surface-muted rounded-lg border border-subtle">
<span class="material-symbols-outlined text-primary">card_membership</span>
<span class="text-label-md font-medium"><?= e($certifications) ?></span>
</div>
<div class="flex items-center gap-3 p-3 bg-surface-muted rounded-lg border border-subtle">
<span class="material-symbols-outlined text-primary">medical_information</span>
<span class="text-label-md font-medium">First Aid Certified</span>
</div>
<div class="flex items-center gap-3 p-3 bg-surface-muted rounded-lg border border-subtle">
<span class="material-symbols-outlined text-primary">verified_user</span>
<span class="text-label-md font-medium">Police Clearance Verified</span>
</div>
</div>
</section>

<section class="bg-surface-container-lowest rounded-xl border border-subtle p-6 space-y-4">

    <h2 class="font-bold text-on-surface text-body-lg">
        Daily Rate
    </h2>

    <div class="flex items-center gap-3 p-3 bg-surface-muted rounded-lg border border-subtle">

        <span class="material-symbols-outlined text-primary">
            payments
        </span>

        <span class="text-label-md font-medium">

            <?php if (!empty($dailyRate)): ?>

                LKR <?= number_format((float)$dailyRate) ?> per day

            <?php else: ?>

                Negotiable

            <?php endif; ?>

        </span>

    </div>

</section>
<!-- Similar Caregivers -->
<section class="space-y-4">
<h2 class="font-bold text-on-surface text-body-lg">Similar Caregivers</h2>
<div class="space-y-4">
<!-- Similar 1 -->
<div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-xl border border-subtle hover:shadow-md transition-all cursor-pointer">
<img class="w-16 h-16 rounded-lg object-cover shrink-0" data-alt="A professional headshot of a middle-aged male caregiver with a warm smile, wearing a professional uniform. The lighting is bright and clean, indicating a premium healthcare service environment. The style is minimalist and high-end digital photography." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDODNy7WSLY_iFlBYLhqjoqGCzmkyNhczS86gVcOgR2ZTPAslzfFe2aw2NwMd2SOjdfnQdvJ2bCpBgQz1jmiiwo-M_Wq21ocaW86Tmmp7kD7WWGwNs2tpy0todVTejeVG0pBYd0lagl6ctGW6TDOKLikpvtQd3JwypWfHgLqjxrvBmjDlnGsKXIXjfC0Lj8eazvhJyRzKrMKmRzGtlI72zMMSph97OdPgUHXgQaDhlRdzcmyS6L4pwduRixDSewbLt5YKcB5RHlWYc"/>
<div>
<h3 class="font-bold text-on-surface">Kumara P.</h3>
<div class="flex items-center gap-1 text-label-sm">
<span class="material-symbols-outlined text-status-warning text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    4.8 <span class="text-outline mx-1">•</span> 8 years exp
                                </div>
</div>
</div>
<!-- Similar 2 -->
<div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-xl border border-subtle hover:shadow-md transition-all cursor-pointer">
<img class="w-16 h-16 rounded-lg object-cover shrink-0" data-alt="A studio portrait of a young female medical professional with her hair tied back, wearing dark blue scrubs. She has a confident and gentle expression. Soft lighting, high-key background, minimalist and premium aesthetic." src="https://lh3.googleusercontent.com/aida-public/AB6AXuBi7IhKfzT9lZGY8osVysf9AOu6rRr7kiTVcx02G4L064aSJx9ofi_fCyA3r2WGpssWnFL8REuVv_ezRsQ-sqkaMClX8dTfQCJIig6kFy2eyEsPCNu0GrTbj1GRfdEU4TDKmok2MteByKU0buAFcME1zziIAXRG0TO3m2dnpXszxg93AwS6Q9BXzQQCrohR6Zk4X3DrVD7rA9DSbyY7BTP5MB502mwV4SvlGX4oN6f_XO3jABYg9hgnF6matoDJwWmvm3vN4oXQ6AA"/>
<div>
<h3 class="font-bold text-on-surface">Nilmini S.</h3>
<div class="flex items-center gap-1 text-label-sm">
<span class="material-symbols-outlined text-status-warning text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    5.0 <span class="text-outline mx-1">•</span> 5 years exp
                                </div>
</div>
</div>
<!-- Similar 3 -->
<div class="flex items-center gap-4 bg-surface-container-lowest p-4 rounded-xl border border-subtle hover:shadow-md transition-all cursor-pointer">
<img class="w-16 h-16 rounded-lg object-cover shrink-0" data-alt="A friendly senior male caregiver with silver hair and glasses, wearing a light grey professional tunic. He looks experienced and empathetic. The image is crisp, bright, and maintains a clinical yet warm tone." src="https://lh3.googleusercontent.com/aida-public/AB6AXuDlNY2rxrCPoUjCol40goTI7iicxU3yqfWZDcDDegy_BKeih_Vtx8z7eX-LS5LyNJKCbDwy9fjQ2xhDrJWJhMHO49qF0Rr0Ftqvm5pE7B_gzRafpEJv2BF428qb7AuUtdgS2O_uc9f_AgL-8aOZlgiRKT8J_WYDB9Vr1aZw_5DajDi9U983clso50iF-t18HNwYHJyx_CX_lEmM8EQCmO9tZddLgwc4HZNrYhfaJhJ2TsC5r6u907FbaeWKLIDvkdhh9YWRrfmdFRo"/>
<div>
<h3 class="font-bold text-on-surface">Anthony F.</h3>
<div class="flex items-center gap-1 text-label-sm">
<span class="material-symbols-outlined text-status-warning text-[14px]" style="font-variation-settings: 'FILL' 1;">star</span>
                                    4.7 <span class="text-outline mx-1">•</span> 12 years exp
                                </div>
</div>
</div>
</div>
</section>
</aside>
</div>
</main>
<!-- Footer -->
<footer class="bg-surface-container border-t border-subtle mt-16">
<div class="flex flex-col md:flex-row justify-between items-center py-12 px-margin-desktop w-full max-w-container-max mx-auto gap-8">
<div class="flex flex-col items-center md:items-start gap-2">
<div class="font-headline-md text-headline-md text-primary font-bold">SafeHands</div>
<p class="font-label-sm text-label-sm text-on-surface-variant">© 2024 SafeHands . All rights reserved.</p>
</div>
<div class="flex flex-wrap justify-center gap-8">
<a class="font-label-sm text-label-sm text-on-surface-variant hover:underline hover:text-primary transition-colors" href="#">Privacy Policy</a>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:underline hover:text-primary transition-colors" href="#">Terms of Service</a>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:underline hover:text-primary transition-colors" href="#">Cookie Policy</a>
<a class="font-label-sm text-label-sm text-on-surface-variant hover:underline hover:text-primary transition-colors" href="#">Accessibility</a>
</div>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary hover:bg-primary hover:text-on-primary transition-all" href="#">
<span class="material-symbols-outlined">share</span>
</a>
<a class="w-10 h-10 rounded-full bg-surface-container-highest flex items-center justify-center text-primary hover:bg-primary hover:text-on-primary transition-all" href="#">
<span class="material-symbols-outlined">mail</span>
</a>
</div>
</div>
</footer>
<script>
        // Micro-interaction for similar caregiver cards
        document.querySelectorAll('.cursor-pointer').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
            });
        });
    </script>
</body></html>
