<?php
session_start();
require_once 'includes/db.php';

/* Only logged in Family members */
if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'Family'
) {
    header("Location: login.php");
    exit();
}

/* Get Caregiver ID */
$caregiverId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($caregiverId <= 0) {
    die("Invalid caregiver.");
}

/* Load caregiver */
$sql = "SELECT
            cp.*,
            u.first_name,
            u.last_name,
            u.email,
            u.phone
        FROM caregiver_profiles cp
        INNER JOIN users u
        ON cp.user_id = u.user_id
        WHERE cp.caregiver_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $caregiverId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Caregiver not found.");
}

$caregiver = $result->fetch_assoc();

function e($value)
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html><html class="light" lang="en" style=""><head><meta charset="utf-8"><meta content="width=device-width, initial-scale=1.0" name="viewport"><link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=block" rel="stylesheet"><script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script><script id="tailwind-config">try{
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          "colors": {
                  "background": "#f9f9ff",
                  "surface-container-high": "#dee8ff",
                  "tertiary": "#943700",
                  "on-primary": "#ffffff",
                  "on-secondary-fixed-variant": "#1a438e",
                  "on-secondary-container": "#113e89",
                  "tertiary-fixed": "#ffdbcd",
                  "primary-fixed-dim": "#b4c5ff",
                  "error": "#ba1a1a",
                  "surface-container": "#e7eeff",
                  "primary-fixed": "#dbe1ff",
                  "secondary-fixed": "#d9e2ff",
                  "secondary-fixed-dim": "#b0c6ff",
                  "tertiary-fixed-dim": "#ffb596",
                  "on-tertiary-fixed": "#360f00",
                  "inverse-surface": "#263143",
                  "surface-container-lowest": "#ffffff",
                  "surface-muted": "#F8FAFC",
                  "surface-container-highest": "#d8e3fb",
                  "on-surface": "#111c2d",
                  "primary": "#004ac6",
                  "inverse-primary": "#b4c5ff",
                  "surface-dim": "#cfdaf2",
                  "on-secondary-fixed": "#001945",
                  "surface-tint": "#0053db",
                  "on-tertiary-fixed-variant": "#7d2d00",
                  "status-info": "#AAECF3",
                  "surface": "#f9f9ff",
                  "surface-variant": "#d8e3fb",
                  "on-background": "#111c2d",
                  "error-container": "#ffdad6",
                  "on-primary-fixed": "#00174b",
                  "on-secondary": "#ffffff",
                  "secondary": "#375ca8",
                  "on-tertiary-container": "#ffede6",
                  "surface-bright": "#f9f9ff",
                  "primary-container": "#2563eb",
                  "on-tertiary": "#ffffff",
                  "status-warning": "#FEBB02",
                  "inverse-on-surface": "#ecf1ff",
                  "surface-container-low": "#f0f3ff",
                  "secondary-container": "#8aacfe",
                  "on-surface-variant": "#434655",
                  "border-subtle": "#F1F5F9",
                  "on-primary-container": "#eeefff",
                  "on-primary-fixed-variant": "#003ea8",
                  "status-success": "#025747",
                  "outline-variant": "#c3c6d7",
                  "on-error": "#ffffff",
                  "on-error-container": "#93000a",
                  "tertiary-container": "#bc4800",
                  "outline": "#737686"
          },
          "borderRadius": {
                  "DEFAULT": "0.25rem",
                  "lg": "0.5rem",
                  "xl": "0.75rem",
                  "full": "9999px"
          },
          "spacing": {
                  "base": "8px",
                  "gutter": "24px",
                  "margin-mobile": "16px",
                  "margin-desktop": "40px",
                  "container-max": "1280px"
          },
          "fontFamily": {
                  "label-sm": ["Inter"],
                  "label-md": ["Inter"],
                  "display-lg": ["Inter"],
                  "headline-lg-mobile": ["Inter"],
                  "body-lg": ["Inter"],
                  "headline-md": ["Inter"],
                  "body-md": ["Inter"],
                  "headline-lg": ["Inter"]
          },
          "fontSize": {
                  "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                  "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                  "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                  "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                  "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                  "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                  "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                  "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}]
          }
        },
      },
    }
  }catch(_e){}</script></head><body class="bg-background text-on-surface min-h-screen flex flex-col">
<!-- TopNavBar Navigation Shell -->
<header class="w-full top-0 sticky bg-surface dark:bg-on-surface border-b border-subtle dark:border-outline-variant shadow-sm z-50">
<div class="flex justify-between items-center h-20 px-margin-desktop max-w-container-max mx-auto">
<div class="flex items-center gap-8">
<span class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim cursor-pointer">SafeHands</span>
<nav class="hidden lg:flex items-center gap-6">
<a class="text-on-surface-variant dark:text-outline hover:text-primary transition-colors duration-200 font-label-md text-label-md" href="#">Dashboard</a>
<a class="text-on-surface-variant dark:text-outline hover:text-primary transition-colors duration-200 font-label-md text-label-md" href="#">Patients</a>
<a class="text-primary dark:text-primary-fixed-dim font-bold border-b-2 border-primary font-label-md text-label-md" href="#">Find Caregivers</a>
<a class="text-on-surface-variant dark:text-outline hover:text-primary transition-colors duration-200 font-label-md text-label-md" href="#">My Bookings</a>
</nav>
</div>
<div class="flex items-center gap-4">
<button class="material-symbols-outlined text-on-surface-variant p-2 hover:bg-surface-container rounded-full transition-all" data-original-icon="notifications">notifications</button>
<div class="flex items-center gap-2 pl-4 border-l border-subtle">
<div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center text-on-primary-container font-bold text-label-md">AM</div>
<span class="font-label-md text-label-md hidden md:block">Aditya Mendis</span>
<span class="material-symbols-outlined text-on-surface-variant" data-original-icon="expand_more">expand_more</span>
</div>
</div>
</div>
</header>
<main class="flex-grow max-w-container-max mx-auto px-margin-desktop py-8 w-full">
<!-- Breadcrumb -->
<nav class="flex items-center gap-2 text-on-surface-variant font-label-sm text-label-sm mb-6">
<a class="hover:text-primary transition-colors" href="#">Dashboard</a>
<span class="material-symbols-outlined text-[16px]" data-original-icon="chevron_right">chevron_right</span>
<a class="hover:text-primary transition-colors" href="#">Find Caregivers</a>
<span class="material-symbols-outlined text-[16px]" data-original-icon="chevron_right">chevron_right</span>
<span class="text-primary font-medium">Caregiver Profile</span>
</nav>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-gutter items-start">
<!-- Main Content Column -->
<div class="lg:col-span-8 flex flex-col gap-8">
<!-- Profile Header Card -->
<section class="bg-surface-container-lowest border border-subtle rounded-xl shadow-sm overflow-hidden p-8">
<div class="flex flex-col md:flex-row gap-8 items-start md:items-center">
<div class="relative">
<div class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-surface-container bg-cover bg-center" data-alt="A professional headshot of a female caregiver in her early 30s, Sarah Wijesinghe, wearing a soft-toned clinical polo shirt. She has a friendly, compassionate smile and clear skin, positioned against a bright, airy medical office background with natural light." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAH59sN1fCNksvG4BbFOI8z0tyNKJH5FXminxQgcPgb7tWohCfvhw1lzJsPVQ7KZ_Y-erYnGm1Rwnq0dGvSXao5xPH8VcWslm3aB5WXzjBYmq39IbunE4gLWd0PP0_chFKrUbJosnK7RYubqiG1C45v5OVKi7M4JsEkby3KU7G_mjB9Kkk20I2eu9ldXde-Bk1N19JgGyeiTAGFQqOm2dANHeys592Bwo_XCgrK-SOzM5yqmKxcSEQUmPhwn8K2Loe1Ple-KBG8Qjc')"></div>
<div class="absolute bottom-1 right-1 bg-white p-1 rounded-full shadow-sm">
<span class="material-symbols-outlined text-primary" style="font-variation-settings: 'FILL' 1;">verified</span>
</div>
</div>
<div class="flex-grow">
<div class="flex flex-wrap items-center gap-3 mb-2">
<h1 class="font-headline-lg text-headline-lg text-on-surface">Sarah Wijesinghe</h1>
<span class="px-3 py-1 bg-status-success/10 text-status-success text-label-sm font-label-sm rounded-full flex items-center gap-1">
<span class="w-2 h-2 rounded-full bg-status-success animate-pulse"></span>
                  Currently Accepting Bookings
                </span>
</div>
<p class="text-on-surface-variant font-body-lg text-body-lg mb-4">Senior Elderly Caregiver</p>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-status-warning" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="font-label-md text-label-md">4.9 (148 Reviews)</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline">location_on</span>
<span class="font-label-md text-label-md">Colombo District</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline">translate</span>
<span class="font-label-md text-label-md">Sinhala, English</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-outline">history</span>
<span class="font-label-md text-label-md">6+ Years Exp.</span>
</div>
</div>
</div>
</div>
</section>

<!-- Full 30-Day Availability Calendar Replacement -->
<section class="bg-surface-container-lowest border border-subtle rounded-xl p-8 shadow-sm">
  <div class="flex items-center justify-between mb-8">
    <h2 class="font-headline-md text-headline-md">Availability Calendar</h2>
    <div class="flex items-center gap-4">
      <h3 class="font-headline-md text-headline-md text-primary">July 2026</h3>
      <div class="flex items-center border border-subtle rounded-lg overflow-hidden">
        <button class="p-2 hover:bg-surface-container transition-colors" title="Previous Month">
          <span class="material-symbols-outlined">chevron_left</span>
        </button>
        <button class="p-2 hover:bg-surface-container transition-colors border-l border-subtle" title="Next Month">
          <span class="material-symbols-outlined">chevron_right</span>
        </button>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-7 gap-px bg-outline-variant border border-outline-variant rounded-xl overflow-hidden shadow-sm">
    <!-- Header -->
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Mon</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Tue</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Wed</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Thu</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Fri</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Sat</div>
    <div class="bg-surface-container-low py-3 text-center text-label-sm font-bold text-on-surface-variant uppercase tracking-wider">Sun</div>

    <!-- Empty slots for previous month (June) -->
    <div class="bg-surface-muted/30 h-32 p-3 text-right text-label-sm text-outline">29</div>
    <div class="bg-surface-muted/30 h-32 p-3 text-right text-label-sm text-outline">30</div>

    <!-- Day 1 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2">1 Jul</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> AFTERNOON
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 2 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">2</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> AFTERNOON
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 3 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">3</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> AFTERNOON
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 4 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">4</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> AFTERNOON
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 5 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">5</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> AFTERNOON
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-outline-variant/10 text-outline rounded text-[10px] font-bold border border-outline-variant/20">
          <span class="w-1.5 h-1.5 rounded-full bg-outline"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 6 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">6</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> MORNING
        </div>
      </div>
    </div>

    <!-- Day 7 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">7</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 8 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">8</span>
    </div>

    <!-- Day 9 -->
    <div class="bg-white h-32 p-3 flex flex-col group hover:bg-surface-container-low transition-colors">
      <span class="text-label-sm font-bold mb-2 text-on-surface">9</span>
      <div class="space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> MORNING
        </div>
        <div class="flex items-center gap-1.5 px-2 py-1 bg-status-success/10 text-status-success rounded text-[10px] border border-status-success/20 font-medium">
          <span class="w-1.5 h-1.5 rounded-full bg-status-success"></span> EVENING
        </div>
      </div>
    </div>

    <!-- Day 10 (OFF) -->
    <div class="bg-white h-32 p-3 flex flex-col relative">
      <span class="absolute top-3 right-3 w-7 h-7 bg-error text-white rounded-full flex items-center justify-center text-label-sm font-bold shadow-sm">10</span>
      <div class="mt-10 space-y-1.5">
        <div class="flex items-center gap-1.5 px-2 py-1 bg-error/10 text-error rounded text-[10px] font-bold border border-error/20">
          <span class="w-1.5 h-1.5 rounded-full bg-error"></span> OFF DUTY
        </div>
      </div>
    </div>

    <!-- Additional days for visualization -->
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">11</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">12</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">13</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">14</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">15</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">16</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">17</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">18</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">19</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">20</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">21</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">22</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">23</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">24</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">25</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">26</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">27</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">28</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">29</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">30</span></div>
    <div class="bg-white h-32 p-3 flex flex-col"><span class="text-label-sm font-bold text-on-surface">31</span></div>
  </div>

  <div class="flex flex-wrap gap-8 mt-8 justify-center border-t border-subtle pt-6">
    <div class="flex items-center gap-2.5">
      <span class="w-4 h-4 rounded-full bg-status-success shadow-sm"></span>
      <span class="text-label-sm font-bold text-on-surface-variant">Available</span>
    </div>
    <div class="flex items-center gap-2.5">
      <span class="w-4 h-4 rounded-full bg-outline shadow-sm"></span>
      <span class="text-label-sm font-bold text-on-surface-variant">Booked</span>
    </div>
    <div class="flex items-center gap-2.5">
      <span class="w-4 h-4 rounded-full bg-error shadow-sm"></span>
      <span class="text-label-sm font-bold text-on-surface-variant">Off Duty</span>
    </div>
  </div>
</section><div class="mt-8 flex justify-center">
  <button class="w-full md:w-2/3 h-14 bg-primary hover:bg-primary-container text-white font-headline-md text-headline-md rounded-xl transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-3 hover:shadow-xl" >
  <a href="booking.php" class="w-full md:w-2/3 h-14 bg-primary hover:bg-primary-container text-white font-headline-md text-headline-md rounded-xl transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-3 hover:shadow-xl">
    <span class="material-symbols-outlined">calendar_month</span>
    Book Sarah Now
</a>
  </button>
  
</div>

<!-- About Me -->
<section class="bg-surface-container-lowest border border-subtle rounded-xl p-8 shadow-sm">
<h2 class="font-headline-md text-headline-md mb-4">About Me</h2>
<p class="text-on-surface-variant font-body-md text-body-md leading-relaxed">
            With over 6 years of dedicated experience in geriatric care, I specialize in providing compassionate and professional support to elderly individuals. My approach centers on preserving the dignity and independence of my patients while ensuring their medical and emotional needs are met with the highest standards of clinical excellence. 
            <br><br>
            I have extensive experience managing chronic conditions, medication schedules, and post-operative recovery. My background in nursing allows me to observe subtle changes in health and act proactively.
          </p>
</section>
<!-- Qualifications -->
<section>
<h2 class="font-headline-md text-headline-md mb-6">Qualifications</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
<div class="p-4 bg-surface-container-low border border-subtle rounded-xl flex items-start gap-4 hover:shadow-md transition-shadow cursor-default">
<div class="p-2 bg-primary-container/20 rounded-lg">
<span class="material-symbols-outlined text-primary">school</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">BSc Nursing</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm">University of Colombo</p>
</div>
</div>
<div class="p-4 bg-surface-container-low border border-subtle rounded-xl flex items-start gap-4 hover:shadow-md transition-shadow cursor-default">
<div class="p-2 bg-primary-container/20 arounded-lg">
<span class="material-symbols-outlined text-primary">workspace_premium</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Certified Elderly Caregiver</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm">SLF Institute of Healthcare</p>
</div>
</div>
<div class="p-4 bg-surface-container-low border border-subtle rounded-xl flex items-start gap-4 hover:shadow-md transition-shadow cursor-default">
<div class="p-2 bg-primary-container/20 rounded-lg">
<span class="material-symbols-outlined text-primary">medical_services</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">First Aid &amp; CPR</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm">Red Cross Certified</p>
</div>
</div>
<div class="p-4 bg-surface-container-low border border-subtle rounded-xl flex items-start gap-4 hover:shadow-md transition-shadow cursor-default">
<div class="p-2 bg-primary-container/20 rounded-lg">
<span class="material-symbols-outlined text-primary">policy</span>
</div>
<div>
<h4 class="font-label-md text-label-md text-on-surface">Police Clearance</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm">Verified 2024</p>
</div>
</div>
</div>
</section>
<!-- Professional Skills -->
<section>
<h2 class="font-headline-md text-headline-md mb-4">Professional Skills</h2>
<div class="flex flex-wrap gap-2">
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Medication Assistance</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Personal Hygiene</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Wound Care</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Mobility Support</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Dementia Care</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Vitals Monitoring</span>
<span class="px-4 py-2 bg-surface-container-highest text-on-surface rounded-full font-label-md text-label-md">Nutritional Support</span>
</div>
</section>
<!-- Experience Timeline -->
<section>
<h2 class="font-headline-md text-headline-md mb-6">Experience Timeline</h2>
<div class="space-y-8 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-outline-variant gap-4">
<div class="relative pl-10">
<div class="absolute left-0 top-1.5 w-[24px] h-[24px] bg-primary rounded-full border-4 border-white shadow-sm"></div>
<h4 class="font-label-md text-label-md text-on-surface">Senior Caregiver, City Medical Center</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm mb-2">2020 - Present</p>
<p class="text-on-surface-variant font-body-md text-body-md">Leading a team of 4 junior caregivers for residential elderly care. specialized in Alzheimer's ward support.</p>
</div>
<div class="relative pl-10">
<div class="absolute left-0 top-1.5 w-[24px] h-[24px] bg-outline-variant rounded-full border-4 border-white shadow-sm"></div>
<h4 class="font-label-md text-label-md text-on-surface">Private Home Nurse, Independent</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm mb-2">2018 - 2020</p>
<p class="text-on-surface-variant font-body-md text-body-md">Provided 24/7 care for post-operative orthopedic patients in home settings.</p>
</div>
</div>
</section>
<!-- Ratings & Reviews -->
<section class="bg-surface-container-lowest border border-subtle rounded-xl p-8 shadow-sm">
<div class="flex justify-between items-center mb-8">
<h2 class="font-headline-md text-headline-md">Ratings &amp; Reviews</h2>
<button class="text-primary font-label-md text-label-md hover:underline">See all 148 reviews</button>
</div>
<div class="flex items-center gap-8 mb-10 pb-8 border-b border-subtle">
<div class="text-center">
<div class="font-display-lg text-display-lg text-on-surface">4.9</div>
<div class="flex justify-center text-status-warning">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star_half</span>
</div>
<p class="text-on-surface-variant text-label-sm font-label-sm mt-1">Out of 5.0</p>
</div>
<div class="flex-grow space-y-2">
<div class="flex items-center gap-3">
<span class="text-label-sm font-label-sm w-4">5</span>
<div class="flex-grow h-2 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary" style="width: 92%"></div>
</div>
</div>
<div class="flex items-center gap-3">
<span class="text-label-sm font-label-sm w-4">4</span>
<div class="flex-grow h-2 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary" style="width: 7%"></div>
</div>
</div>
<div class="flex items-center gap-3">
<span class="text-label-sm font-label-sm w-4">3</span>
<div class="flex-grow h-2 bg-surface-container rounded-full overflow-hidden">
<div class="h-full bg-primary" style="width: 1%"></div>
</div>
</div>
</div>
</div>
<div class="space-y-8">
<div class="pb-6 border-b border-subtle last:border-0 last:pb-0">
<div class="flex justify-between mb-2">
<h5 class="font-label-md text-label-md">Kamal Perera</h5>
<span class="text-on-surface-variant text-label-sm font-label-sm">2 days ago</span>
</div>
<div class="flex text-status-warning mb-2 scale-75 origin-left">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface-variant font-body-md text-body-md italic">"Sarah was an absolute godsend for my father. Her calm demeanor and clinical expertise made the recovery process so much easier for our entire family."</p>
</div>
<div class="pb-6 border-b border-subtle last:border-0 last:pb-0">
<div class="flex justify-between mb-2">
<h5 class="font-label-md text-label-md">Shalini D.</h5>
<span class="text-on-surface-variant text-label-sm font-label-sm">1 week ago</span>
</div>
<div class="flex text-status-warning mb-2 scale-75 origin-left">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
</div>
<p class="text-on-surface-variant font-body-md text-body-md italic">"Incredibly professional and punctual. She immediately noticed a medication interaction that we had missed. Highly recommended."</p>
</div>
</div>
</section>
</div>
<!-- Sidebar Column -->
<aside class="lg:col-span-4 flex flex-col gap-gutter">
<!-- Booking Panel Card -->


<!-- Booking Information Card -->

</aside>
</div>
<!-- Similar Caregivers -->
<section class="mt-16">
<h2 class="font-headline-md text-headline-md mb-8">Similar Caregivers nearby</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
<div class="bg-surface-container-lowest border border-subtle rounded-xl p-5 flex gap-4 hover:shadow-md transition-all cursor-pointer group">
<div class="w-16 h-16 rounded-full bg-cover bg-center" data-alt="Close-up profile photo of a male caregiver in professional attire, smiling warmly." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAC1bDdkWPV5aPiosTq95MeTUaSm6g5IMOEMQju1_cRdKdiBSI-x9r5xT2M2Vvxs4d7AyWc8BEjqxUat99ZnPP7ZuUjjFicJNWqxx0FJwOGJOnRYva70NPt2Ug_XpxrmKJF7p4dcCrOMRQRLPAh0SrJ6Kbl0zlp4yCIWFZta4lMqiyW4fJERWdZYR55ZOHblg2IH88glil4GimMiZwtbvRG0UusLjTZrBoPf9MEpExCLu4gRELGkArdCIgHyDkw1WBPGL448BAhmUw')"></div>
<div>
<h4 class="font-label-md text-label-md text-on-surface group-hover:text-primary transition-colors">Arjuna K.</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm mb-2">Clinical Assistant</p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-status-warning text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-[12px] font-medium">4.8</span>
</div>
</div>
</div>
<div class="bg-surface-container-lowest border border-subtle rounded-xl p-5 flex gap-4 hover:shadow-md transition-all cursor-pointer group">
<div class="w-16 h-16 rounded-full bg-cover bg-center" data-alt="Headshot of a middle-aged female nurse with a gentle expression, wearing a navy blue medical uniform." style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuC4m8BStddRlozlw0DlLlotSlKuByHGqtAiKfoFIESFzneUExxStvaj5xYkMkxhpD2K1it4kF020uRVBWbvwOCTuo19BeJAGvt4ANUqJ7ePskE9YTB__X3Bag0MqI9gNSx2fRMmLD7IHowJiC68X8TtwV6sRn77fHSwne9ygbhveFvnNOB0VXXu94wMTzJNS6f0iehTEeWbaz_HMYEjgBy3m2DzFMgHX8joQpycGFXYRHLtnzQ977Ihw61KLcq4OTDgdsL7T07Sdck')"></div>
<div>
<h4 class="font-label-md text-label-md text-on-surface group-hover:text-primary transition-colors">Nilanthi S.</h4>
<p class="text-on-surface-variant text-label-sm font-label-sm mb-2">Elderly Care Expert</p>
<div class="flex items-center gap-1">
<span class="material-symbols-outlined text-status-warning text-[16px]" style="font-variation-settings: 'FILL' 1;">star</span>
<span class="text-[12px] font-medium">4.7</span>
</div>
</div>
</div>

</div>
</section>
</main>
<!-- Footer Navigation Shell -->
<footer class="w-full mt-auto bg-surface-muted dark:bg-inverse-surface border-t border-subtle dark:border-outline-variant py-8">
<div class="flex flex-col md:flex-row justify-between items-center px-margin-desktop max-w-container-max mx-auto gap-4">
<span class="font-headline-md text-headline-md font-bold text-on-surface dark:text-inverse-on-surface">SafeHands</span>
<div class="flex flex-wrap justify-center gap-6">
<a class="text-on-surface-variant dark:text-outline font-label-md text-label-md hover:text-primary underline transition-all" href="#">Privacy Policy</a>
<a class="text-on-surface-variant dark:text-outline font-label-md text-label-md hover:text-primary underline transition-all" href="#">Terms of Service</a>
<a class="text-on-surface-variant dark:text-outline font-label-md text-label-md hover:text-primary underline transition-all" href="#">Cookie Policy</a>
<a class="text-on-surface-variant dark:text-outline font-label-md text-label-md hover:text-primary underline transition-all" href="#">Accessibility</a>
<a class="text-on-surface-variant dark:text-outline font-label-md text-label-md hover:text-primary underline transition-all" href="#">Sitemap</a>
</div>
<p class="text-on-surface-variant dark:text-outline font-label-md text-label-md text-center">
        © 2024 SafeHands Healthcare Services. All rights reserved.
      </p>
</div>
</footer>
<script class="">
    // Micro-interaction for shift selection
    const shift</script>

</body></html>
