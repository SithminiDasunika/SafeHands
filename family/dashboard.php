<?php

session_start();

/*
|--------------------------------------------------------------------------
| Protect Family Dashboard
|--------------------------------------------------------------------------
| User must be logged in and must have the Family role.
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Family') {
    header("Location: ../login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| Logged-in User Information
|--------------------------------------------------------------------------
*/

$firstName = $_SESSION['first_name'] ?? 'Family Member';
$lastName  = $_SESSION['last_name'] ?? '';
$email     = $_SESSION['email'] ?? '';

$profileInitial = strtoupper(
    substr($firstName, 0, 1)
);

// Current date
$currentDate = date("d F Y");

?>

<!DOCTYPE html>

<html lang="en" class="light">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>Family Dashboard | SafeHands</title>


<!-- Google Fonts -->

<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>


<!-- Material Icons -->

<link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=block"
    rel="stylesheet"
>


<!-- Tailwind CSS -->

<script
    src="https://cdn.tailwindcss.com?plugins=forms,container-queries">
</script>


<!-- Tailwind Configuration -->

<script>

tailwind.config = {

    darkMode: "class",

    theme: {

        extend: {

            colors: {

                "on-surface-variant": "#434655",
                "on-background": "#111c2d",
                "on-secondary": "#ffffff",
                "tertiary-fixed": "#ffdbcd",
                "surface": "#f9f9ff",
                "inverse-primary": "#b4c5ff",
                "on-secondary-container": "#113e89",
                "status-success": "#025747",
                "on-primary-fixed-variant": "#003ea8",
                "error": "#ba1a1a",
                "surface-container-highest": "#d8e3fb",
                "on-tertiary-fixed": "#360f00",
                "outline": "#737686",
                "on-surface": "#111c2d",
                "surface-variant": "#d8e3fb",
                "primary-fixed-dim": "#b4c5ff",
                "secondary-container": "#8aacfe",
                "surface-muted": "#F8FAFC",
                "secondary": "#375ca8",
                "surface-container": "#e7eeff",
                "surface-container-lowest": "#ffffff",
                "status-info": "#AAECF3",
                "background": "#f9f9ff",
                "on-error": "#ffffff",
                "on-tertiary-container": "#ffede6",
                "error-container": "#ffdad6",
                "on-tertiary": "#ffffff",
                "on-secondary-fixed-variant": "#1a438e",
                "status-warning": "#FEBB02",
                "border-subtle": "#F1F5F9",
                "primary-fixed": "#dbe1ff",
                "surface-bright": "#f9f9ff",
                "on-primary": "#ffffff",
                "tertiary-container": "#bc4800",
                "surface-dim": "#cfdaf2",
                "on-error-container": "#93000a",
                "on-secondary-fixed": "#001945",
                "surface-tint": "#0053db",
                "inverse-surface": "#263143",
                "on-primary-fixed": "#00174b",
                "primary": "#004ac6",
                "inverse-on-surface": "#ecf1ff",
                "secondary-fixed": "#d9e2ff",
                "primary-container": "#2563eb",
                "tertiary-fixed-dim": "#ffb596",
                "on-primary-container": "#eeefff",
                "surface-container-high": "#dee8ff",
                "secondary-fixed-dim": "#b0c6ff",
                "on-tertiary-fixed-variant": "#7d2d00",
                "surface-container-low": "#f0f3ff",
                "outline-variant": "#c3c6d7",
                "tertiary": "#943700"

            },

            borderRadius: {

                DEFAULT: "0.25rem",
                lg: "0.5rem",
                xl: "0.75rem",
                full: "9999px"

            },

            spacing: {

                "margin-mobile": "16px",
                "margin-desktop": "40px",
                "container-max": "1280px",
                "base": "8px",
                "gutter": "24px"

            },

            fontFamily: {

                "label-sm": ["Inter"],
                "headline-md": ["Inter"],
                "headline-lg": ["Inter"],
                "display-lg": ["Inter"],
                "body-lg": ["Inter"],
                "headline-lg-mobile": ["Inter"],
                "label-md": ["Inter"],
                "body-md": ["Inter"]

            }

        }

    }

};

</script>


<style>

body {
    font-family: 'Inter', sans-serif;
    background-color: #f9f9ff;
}

.material-symbols-outlined {

    font-variation-settings:
        'FILL' 0,
        'wght' 400,
        'GRAD' 0,
        'opsz' 24;

}

</style>

</head>


<body class="text-on-surface">


<!-- ====================================================== -->
<!-- TOP NAVIGATION -->
<!-- ====================================================== -->

<nav
    class="bg-surface-container-lowest sticky top-0 z-50 shadow-sm"
>

<div
    class="max-w-container-max mx-auto px-margin-desktop h-16 flex justify-between items-center"
>


<!-- LEFT NAV -->

<div class="flex items-center gap-8">


<!-- LOGO -->

<div class="flex items-center gap-3">

<img
    src="https://lh3.googleusercontent.com/aida/AP1WRLsHre5ZqwErKJnNBsZ6bCroC0CrKI88tQjyuuw-wlNlZdLT6OPlvXka60xhFCWMOFhH3DxzrCp9GOekzE7nKnjZkzge0ho4sN1tMqf4mfQLAuKXG4cRYajR_mf9Z3HfFVPlYrYAalBJR8ld5bGBwtM1P1bLJKsYF_a4zaCRy2Vl52sseHoKiXufQJJMdz-bT0T_uLLq8RX7cj0epzMAfkn2mUlq-8tDJ4YeKrlKEMHzThTFxUQ_LvLOtiE"
    alt="SafeHands Logo"
    class="w-10 h-10 object-contain rounded-md"
>

<span
    class="text-2xl font-bold text-primary"
>
SafeHands
</span>

</div>


<!-- MAIN NAVIGATION -->

<div class="hidden md:flex items-center gap-6">

<a
    href="dashboard.php"
    class="font-medium text-sm text-primary border-b-2 border-primary pb-1"
>
Dashboard
</a>


<a
 href="../patients.php"
    class="font-medium text-sm text-on-surface-variant hover:text-primary transition-colors"
>
Patients
</a>

<a
    href="/safehands/find-caregivers.php"
    class="font-medium text-sm text-on-surface-variant hover:text-primary transition-colors"
>
    Find Caregivers
</a>

<a
    href="#"
    class="font-medium text-sm text-on-surface-variant hover:text-primary transition-colors"
>
My Bookings
</a>

</div>

</div>


<!-- RIGHT NAV -->

<div class="flex items-center gap-4">


<!-- NOTIFICATIONS -->

<button
    class="material-symbols-outlined text-on-surface-variant hover:text-primary transition-colors p-2 rounded-full hover:bg-surface-container"
    type="button"
>
notifications
</button>


<!-- USER INITIAL -->

<div
    class="w-10 h-10 rounded-full bg-primary-container text-white flex items-center justify-center font-bold"
    title="<?= htmlspecialchars($firstName . ' ' . $lastName) ?>"
>

<?= htmlspecialchars($profileInitial) ?>

</div>


<!-- LOGOUT -->

<a
    href="/safehands/logout.php"
    class="flex items-center gap-2 text-on-surface-variant hover:text-primary transition-colors p-2 rounded-lg hover:bg-surface-container"
    title="Logout"
>
    <span class="material-symbols-outlined">
        logout
    </span>

    <span class="font-medium text-sm">
        Logout
    </span>
</a>
</div>

</div>

</nav>


<!-- ====================================================== -->
<!-- MAIN DASHBOARD -->
<!-- ====================================================== -->

<main
    class="max-w-container-max mx-auto px-margin-desktop py-8 relative"
>


<!-- ====================================================== -->
<!-- HEADER -->
<!-- ====================================================== -->

<header
    class="flex flex-col md:flex-row md:justify-between md:items-end gap-2 mb-8"
>

<div>

<h1
    class="text-3xl font-semibold text-on-background"
>

Welcome Back,
<?= htmlspecialchars($firstName) ?> 👋

</h1>


<p
    class="text-on-surface-variant text-base"
>

Manage your family's health and care schedule seamlessly.

</p>

</div>


<!-- CURRENT DATE -->

<div
    class="bg-surface-container-low px-4 py-2 rounded-xl flex items-center gap-2 border border-border-subtle"
>

<span
    class="material-symbols-outlined text-primary"
>
calendar_today
</span>

<span
    class="font-medium text-sm text-on-surface"
>

<?= htmlspecialchars($currentDate) ?>

</span>

</div>

</header>


<!-- ====================================================== -->
<!-- DASHBOARD GRID -->
<!-- ====================================================== -->

<div
    class="grid grid-cols-1 md:grid-cols-12 gap-gutter"
>


<!-- ====================================================== -->
<!-- LEFT COLUMN -->
<!-- ====================================================== -->

<div
    class="md:col-span-8 space-y-gutter"
>


<!-- ====================================================== -->
<!-- QUICK ACTIONS -->
<!-- ====================================================== -->

<div
    class="grid grid-cols-2 lg:grid-cols-4 gap-4"
>


<!-- ADD PATIENT -->



<div
    class="bg-surface-container-lowest p-4 rounded-xl border border-border-subtle shadow-sm hover:shadow-md transition-shadow cursor-pointer group"
    onclick="window.location.href='add-patient.php'"
>

<div
    class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-colors"
>

<span class="material-symbols-outlined">
person_add
</span>

</div>

<h3 class="font-medium text-sm mb-1">
Add Patient
</h3>

<p
    class="text-on-surface-variant text-[10px] leading-tight"
>
Register a new family member.
</p>

</div>


<!-- FIND CAREGIVER -->

<div
    class="bg-surface-container-lowest p-4 rounded-xl border border-border-subtle shadow-sm hover:shadow-md transition-shadow cursor-pointer group" 
     onclick="window.location.href='../find-caregivers.php'"
    >


<div
    class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-colors"
>

<span class="material-symbols-outlined">
person_search
</span>

</div>

<h3 class="font-medium text-sm mb-1">
Find Caregiver
</h3>

<p
    class="text-on-surface-variant text-[10px] leading-tight"
>
Search professional caregivers.
</p>

</div>


<!-- MY BOOKINGS -->

<div
    class="bg-surface-container-lowest p-4 rounded-xl border border-border-subtle shadow-sm hover:shadow-md transition-shadow cursor-pointer group" 
     onclick="window.location.href='../my-booking.php'"
    >

<div
    class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-colors"
>

<span class="material-symbols-outlined">
book_online
</span>

</div>

<h3 class="font-medium text-sm mb-1">
My Bookings
</h3>

<p
    class="text-on-surface-variant text-[10px] leading-tight"
>
Manage your care sessions.
</p>

</div>


<!-- NOTIFICATIONS -->

<div
    class="bg-surface-container-lowest p-4 rounded-xl border border-border-subtle shadow-sm hover:shadow-md transition-shadow cursor-pointer group" 
     onclick="window.location.href='../family-notification.php'"
    >

<div
    class="w-10 h-10 bg-primary/10 text-primary rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary group-hover:text-white transition-colors"
>

<span class="material-symbols-outlined">
notifications_active
</span>

</div>

<h3 class="font-medium text-sm mb-1">
Notifications
</h3>

<p
    class="text-on-surface-variant text-[10px] leading-tight"
>
Stay updated on reports.
</p>

</div>



</div>


<!-- ====================================================== -->
<!-- STATISTICS -->
<!-- ====================================================== -->

<!--
IMPORTANT:
These numbers are currently UI placeholders.
We will connect them to the database after creating
patients and bookings tables.
-->

<div
    class="grid grid-cols-2 lg:grid-cols-4 gap-4 bg-surface-muted p-6 rounded-xl border border-border-subtle"
>


<div>

<p
    class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"
>
Registered
</p>

<p
    class="text-2xl font-bold text-on-background"
>
2
</p>

<p class="text-[10px] text-on-surface-variant">
Patients
</p>

</div>


<div>

<p
    class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"
>
Upcoming
</p>

<p
    class="text-2xl font-bold text-on-background"
>
3
</p>

<p class="text-[10px] text-on-surface-variant">
Sessions
</p>

</div>


<div>

<p
    class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"
>
Active
</p>

<p
    class="text-2xl font-bold text-on-background"
>
2
</p>

<p class="text-[10px] text-on-surface-variant">
Bookings
</p>

</div>


<div>

<p
    class="text-xs text-on-surface-variant uppercase tracking-wider mb-1"
>
Completed
</p>

<p
    class="text-2xl font-bold text-on-background"
>
18
</p>

<p class="text-[10px] text-on-surface-variant">
Sessions
</p>

</div>

</div>


<!-- ====================================================== -->
<!-- UPCOMING CARE SESSIONS -->
<!-- ====================================================== -->

<section>


<div
    class="flex justify-between items-center mb-4"
>

<h2
    class="text-2xl font-semibold"
>
Upcoming Care Sessions
</h2>

<button
    class="text-primary font-medium text-sm hover:underline"
    type="button"
>
View Calendar
</button>

</div>


<div class="space-y-4">


<!-- SESSION 1 -->

<div
    class="bg-surface-container-lowest p-6 rounded-xl border border-border-subtle flex flex-col md:flex-row md:items-center gap-6 shadow-sm"
>


<div
    class="flex flex-col items-center justify-center bg-primary-fixed w-20 h-20 rounded-xl"
>

<span
    class="text-xs font-semibold text-primary uppercase"
>
July
</span>

<span
    class="text-3xl font-semibold leading-tight"
>
15
</span>

</div>


<div class="flex-grow">


<div
    class="flex items-center gap-2 mb-1"
>

<span
    class="bg-status-success/10 text-status-success text-[10px] font-bold px-2 py-0.5 rounded-full uppercase"
>
Tomorrow
</span>

<span
    class="text-on-surface-variant font-medium text-sm"
>
Morning Shift
</span>

</div>


<h3
    class="text-xl font-semibold"
>
Nadeesha Perera
</h3>


<p
    class="text-on-surface-variant"
>

Assisting

<span
    class="font-semibold text-on-surface"
>
Mr. Silva
</span>

</p>

</div>


<div class="flex gap-2">

<button
    class="px-4 py-2 border border-border-subtle rounded-lg font-medium text-sm hover:bg-surface transition-colors"
    type="button"
>
Details
</button>

<button
    class="px-4 py-2 bg-primary text-white rounded-lg font-medium text-sm hover:opacity-90 transition-opacity"
    type="button"
>
Message
</button>

</div>

</div>


<!-- SESSION 2 -->

<div
    class="bg-surface-container-lowest p-6 rounded-xl border border-border-subtle flex flex-col md:flex-row md:items-center gap-6 shadow-sm opacity-80 hover:opacity-100 transition-opacity"
>


<div
    class="flex flex-col items-center justify-center bg-surface-variant w-20 h-20 rounded-xl"
>

<span
    class="text-xs text-on-surface-variant uppercase"
>
July
</span>

<span
    class="text-3xl font-semibold leading-tight"
>
16
</span>

</div>


<div class="flex-grow">

<div
    class="flex items-center gap-2 mb-1"
>

<span
    class="text-on-surface-variant font-medium text-sm"
>
Evening Shift
</span>

</div>


<h3
    class="text-xl font-semibold"
>
Chamari Silva
</h3>


<p
    class="text-on-surface-variant"
>

Assisting

<span
    class="font-semibold text-on-surface"
>
Mrs. Kumari
</span>

</p>

</div>


<div class="flex gap-2">

<button
    class="px-4 py-2 border border-border-subtle rounded-lg font-medium text-sm hover:bg-surface transition-colors"
    type="button"
>
Details
</button>

</div>

</div>

</div>

</section>


<!-- ====================================================== -->
<!-- EMERGENCY SUPPORT -->
<!-- ====================================================== -->

<div
    class="bg-primary-container p-6 rounded-xl text-white shadow-lg shadow-primary/20 relative mb-gutter"
>


<div
    class="absolute top-0 right-0 opacity-10"
>

<span
    class="material-symbols-outlined text-8xl"
>
emergency
</span>

</div>


<div class="relative z-10">


<h3
    class="text-2xl font-semibold mb-2"
>
Emergency Support
</h3>


<p
    class="text-white text-base mb-6 opacity-90"
>
Urgent assistance needed? Our 24/7 medical response team is a tap away.
</p>


<div
    class="relative group w-full"
>


<button
    class="w-full py-4 bg-white text-primary font-bold rounded-xl flex items-center justify-center gap-2 hover:bg-opacity-95 transition-all"
    type="button"
>

<span class="material-symbols-outlined">
call
</span>

Call Helpline

<span
    class="material-symbols-outlined ml-auto"
>
expand_more
</span>

</button>


<div
    class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-xl border border-border-subtle overflow-hidden opacity-0 invisible group-focus-within:opacity-100 group-focus-within:visible group-hover:opacity-100 group-hover:visible transition-all z-50"
>


<div class="p-2 space-y-1">


<a
    href="tel:1990"
    class="flex items-center justify-between px-4 py-3 rounded-lg bg-primary/5 text-primary hover:bg-primary/10 transition-colors"
>

<span class="font-bold text-sm">
Ambulance (Suwa Seriya)
</span>

<span class="text-xs font-bold">
1990
</span>

</a>


<a
    href="tel:119"
    class="flex items-center justify-between px-4 py-3 rounded-lg text-on-surface hover:bg-surface-container transition-colors"
>

<span class="font-medium text-sm">
Local Police
</span>

<span class="text-xs text-on-surface-variant">
119
</span>

</a>

</div>

</div>

</div>

</div>

</div>


</div>


<!-- ====================================================== -->
<!-- RIGHT COLUMN -->
<!-- ====================================================== -->

<div
    class="md:col-span-4 space-y-gutter"
>


<!-- ====================================================== -->
<!-- PATIENT OVERVIEW -->
<!-- ====================================================== -->

<section>


<h2
    class="font-medium text-sm mb-4 uppercase tracking-widest text-on-surface-variant"
>
Patient Overview
</h2>


<div class="space-y-4">


<!-- PATIENT 1 -->

<div
    class="bg-surface-container-lowest p-5 rounded-xl border border-border-subtle shadow-sm relative overflow-hidden"
>

<div
    class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -mr-8 -mt-8"
>
</div>


<div
    class="flex items-start gap-4 mb-4"
>

<div
    class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary font-bold"
>
MS
</div>


<div>

<h3
    class="font-semibold text-sm"
>
Mr. Silva
</h3>

<p
    class="text-xs text-on-surface-variant"
>
Age 78 • Hypertension
</p>

</div>

</div>


<div
    class="flex items-center justify-between"
>

<span
    class="text-status-success text-xs font-semibold flex items-center gap-1"
>

<span
    class="w-2 h-2 rounded-full bg-status-success"
>
</span>

Stable

</span>


<button
    class="text-primary font-medium text-sm font-bold"
    type="button"
    onclick="window.location.href='../patient-profile.php'"
>
    View Patient
</button>

</div>

</div>


<!-- PATIENT 2 -->

<div
    class="bg-surface-container-lowest p-5 rounded-xl border border-border-subtle shadow-sm relative overflow-hidden"
>

<div
    class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-full -mr-8 -mt-8"
>
</div>


<div
    class="flex items-start gap-4 mb-4"
>

<div
    class="w-12 h-12 rounded-lg bg-surface-container flex items-center justify-center text-primary font-bold"
>
MK
</div>


<div>

<h3
    class="font-semibold text-sm"
>
Mrs. Kumari
</h3>

<p
    class="text-xs text-on-surface-variant"
>
Age 72 • Post-Op Recovery
</p>

</div>

</div>


<div
    class="flex items-center justify-between"
>

<span
    class="text-status-warning text-xs font-semibold flex items-center gap-1"
>

<span
    class="w-2 h-2 rounded-full bg-status-warning"
>
</span>

Recovering

</span>


<button
    class="text-primary font-medium text-sm font-bold"
    type="button"
>
View Patient
</button>

</div>

</div>

</div>

</section>


<!-- ====================================================== -->
<!-- BOOKING STATUS -->
<!-- ====================================================== -->

<div
    class="bg-surface-muted p-6 rounded-xl border border-border-subtle"
>


<h3
    class="font-semibold text-sm mb-4"
>
Booking Status
</h3>


<div class="space-y-4">


<!-- Confirmed -->

<div class="space-y-2">

<div
    class="flex justify-between text-xs"
>

<span>
Confirmed
</span>

<span class="font-bold">
45%
</span>

</div>


<div
    class="w-full bg-surface-container h-2 rounded-full"
>

<div
    class="bg-primary h-full rounded-full"
    style="width:45%;">
</div>

</div>

</div>


<!-- In Progress -->

<div class="space-y-2">

<div
    class="flex justify-between text-xs"
>

<span>
In Progress
</span>

<span class="font-bold">
20%
</span>

</div>


<div
    class="w-full bg-surface-container h-2 rounded-full"
>

<div
    class="bg-status-success h-full rounded-full"
    style="width:20%;">
</div>

</div>

</div>


<!-- Awaiting OTP -->

<div class="space-y-2">

<div
    class="flex justify-between text-xs"
>

<span>
Awaiting OTP
</span>

<span class="font-bold">
10%
</span>

</div>


<div
    class="w-full bg-surface-container h-2 rounded-full"
>

<div
    class="bg-status-warning h-full rounded-full"
    style="width:10%;">
</div>

</div>

</div>


<!-- Completed -->

<div class="space-y-2">

<div
    class="flex justify-between text-xs"
>

<span>
Completed
</span>

<span class="font-bold">
25%
</span>

</div>


<div
    class="w-full bg-surface-container h-2 rounded-full"
>

<div
    class="bg-secondary h-full rounded-full"
    style="width:25%;">
</div>

</div>

</div>

</div>

</div>


<!-- ====================================================== -->
<!-- RECENT ACTIVITY -->
<!-- ====================================================== -->

<section
    class="bg-surface-container-lowest p-6 rounded-xl border border-border-subtle shadow-sm"
>


<h2
    class="font-semibold text-sm mb-6"
>
Recent Activity
</h2>


<div
    class="space-y-6 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[1px] before:bg-border-subtle"
>


<!-- ACTIVITY 1 -->

<div
    class="flex gap-4 relative"
>

<div
    class="z-10 w-6 h-6 rounded-full bg-primary text-white flex items-center justify-center"
>

<span
    class="material-symbols-outlined text-xs"
>
check
</span>

</div>


<div>

<h4
    class="font-medium text-sm"
>
Booking Confirmed
</h4>

<p
    class="text-xs text-on-surface-variant"
>
Shift with Nadeesha P. for Mr. Silva
</p>

<span
    class="text-[10px] text-outline"
>
10:45 AM, Today
</span>

</div>

</div>


<!-- ACTIVITY 2 -->

<div
    class="flex gap-4 relative"
>

<div
    class="z-10 w-6 h-6 rounded-full bg-status-success text-white flex items-center justify-center"
>

<span
    class="material-symbols-outlined text-xs"
>
payments
</span>

</div>


<div>

<h4
    class="font-medium text-sm"
>
Payment Received
</h4>

<p
    class="text-xs text-on-surface-variant"
>
Invoice #SH-9821 successfully paid.
</p>

<span
    class="text-[10px] text-outline"
>
09:12 AM, Today
</span>

</div>

</div>


<!-- ACTIVITY 3 -->

<div
    class="flex gap-4 relative"
>

<div
    class="z-10 w-6 h-6 rounded-full bg-secondary text-white flex items-center justify-center"
>

<span
    class="material-symbols-outlined text-xs"
>
description
</span>

</div>


<div>

<h4
    class="font-medium text-sm"
>
Care Report Available
</h4>

<p
    class="text-xs text-on-surface-variant"
>
Evening shift report for Mrs. Kumari.
</p>

<span
    class="text-[10px] text-outline"
>
Yesterday, 11:30 PM
</span>

</div>

</div>

</div>

</section>

</div>

</div>


<!-- ====================================================== -->
<!-- TRUST BANNER -->
<!-- ====================================================== -->

<div
    class="mt-gutter rounded-2xl overflow-hidden relative h-64 shadow-xl border border-border-subtle group"
>


<div
    class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-1000"
    style="background-image:url('https://lh3.googleusercontent.com/aida/AP1WRLsIiI-OtxLoMZ9dktx0G0FoAJGN4tkykrZJMroqCe5p0CRDi_uF95p3eQI9jyXSrieJJwxzIDEJtnUeVs1rgI1k-qMoRV-xLjYcGXJRuGuwPw2c5hHBBegsuImdbesgmsX6d18aJVIiGzVTbezIE5AKeq2zyjtv3bfsTfDF_9QWksyDxjiu6hw5XIi-jQnpvlGxUFoRETucUWxMWOAYcIoK22SHZG5zH3JWDxX02GDVrEyrp4LE5KYgqQQ');"
>
</div>


<div
    class="absolute inset-0 bg-gradient-to-r from-background/95 via-background/40 to-transparent"
>
</div>


<div
    class="absolute inset-0 flex items-center px-12"
>

<div
    class="max-w-md"
>

<h2
    class="text-3xl font-semibold mb-2 text-on-background"
>
Professional Care for Your Loved Ones
</h2>


<p
    class="text-on-surface-variant text-base mb-6"
>
SafeHands ensures every caregiver is verified, trained, and matched
specifically to your family's unique medical requirements.
</p>


<button
    class="px-6 py-3 bg-primary text-white rounded-xl font-bold flex items-center gap-2 hover:shadow-lg transition-shadow"
    type="button"
>

Learn about our Verification Process

<span class="material-symbols-outlined">
arrow_forward
</span>

</button>

</div>

</div>

</div>

</main>


<!-- ====================================================== -->
<!-- FOOTER -->
<!-- ====================================================== -->

<footer
    class="bg-surface-muted w-full py-8 mt-16"
>

<div
    class="max-w-container-max mx-auto px-margin-desktop flex flex-col md:flex-row justify-between items-center gap-4"
>


<div
    class="flex items-center gap-2"
>

<span
    class="font-bold text-primary"
>
SafeHands
</span>

<span
    class="text-on-surface-variant text-xs"
>
© <?= date("Y") ?> SafeHands. All rights reserved.
</span>

</div>


<div
    class="flex gap-6"
>

<a
    class="text-xs text-on-surface-variant hover:text-primary transition-colors"
    href="#"
>
Privacy Policy
</a>

<a
    class="text-xs text-on-surface-variant hover:text-primary transition-colors"
    href="#"
>
Terms of Service
</a>

<a
    class="text-xs text-on-surface-variant hover:text-primary transition-colors"
    href="#"
>
Contact Support
</a>

</div>

</div>

</footer>


<!-- ====================================================== -->
<!-- CARD HOVER EFFECT -->
<!-- ====================================================== -->

<script>

document
    .querySelectorAll('.bg-surface-container-lowest')
    .forEach(card => {

        card.addEventListener(
            'mouseenter',
            () => {

                card.style.transform =
                    'translateY(-4px)';

                card.style.transition =
                    'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';

            }
        );


        card.addEventListener(
            'mouseleave',
            () => {

                card.style.transform =
                    'translateY(0)';

            }
        );

    });

</script>


</body>

</html>