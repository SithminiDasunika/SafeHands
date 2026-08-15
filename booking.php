<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Book Caregiver | SafeHands Premium Care</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-tint": "#0053db",
                        "inverse-primary": "#b4c5ff",
                        "surface-bright": "#f9f9ff",
                        "primary": "#2563eb",
                        "status-success": "#025747",
                        "primary-container": "#2563eb",
                        "on-tertiary-fixed-variant": "#7d2d00",
                        "surface-container": "#e7eeff",
                        "surface-container-lowest": "#ffffff",
                        "on-background": "#111c2d",
                        "secondary-fixed": "#d9e2ff",
                        "on-primary": "#ffffff",
                        "tertiary": "#943700",
                        "on-tertiary-container": "#ffede6",
                        "border-subtle": "#F1F5F9",
                        "surface-container-highest": "#d8e3fb",
                        "status-warning": "#FEBB02",
                        "inverse-on-surface": "#ecf1ff",
                        "error-container": "#ffdad6",
                        "inverse-surface": "#263143",
                        "on-tertiary-fixed": "#360f00",
                        "on-secondary-container": "#113e89",
                        "tertiary-container": "#bc4800",
                        "secondary": "#375ca8",
                        "surface-muted": "#F8FAFC",
                        "on-surface-variant": "#434655",
                        "surface": "#f9f9ff",
                        "outline": "#737686",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#ffb596",
                        "on-surface": "#111c2d",
                        "secondary-fixed-dim": "#b0c6ff",
                        "on-secondary-fixed": "#001945",
                        "primary-fixed": "#dbe1ff",
                        "primary-fixed-dim": "#b4c5ff",
                        "on-secondary": "#ffffff",
                        "on-primary-fixed-variant": "#003ea8",
                        "on-tertiary": "#ffffff",
                        "secondary-container": "#8aacfe",
                        "on-primary-container": "#eeefff",
                        "surface-variant": "#d8e3fb",
                        "on-secondary-fixed-variant": "#1a438e",
                        "surface-dim": "#cfdaf2",
                        "outline-variant": "#c3c6d7",
                        "background": "#f9f9ff",
                        "on-error-container": "#93000a",
                        "surface-container-high": "#dee8ff",
                        "status-info": "#AAECF3",
                        "tertiary-fixed": "#ffdbcd",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f0f3ff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                    "spacing": {
                        "gutter": "24px",
                        "margin-mobile": "16px",
                        "margin-desktop": "40px",
                        "container-max": "1280px",
                        "base": "8px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "label-sm": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "label-md": ["Inter"],
                        "display-lg": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "letterSpacing": "-0.01em", "fontWeight": "600"}],
                        "body-md": ["16px", {"lineHeight": "24px", "fontWeight": "400"}],
                        "headline-lg-mobile": ["24px", {"lineHeight": "32px", "fontWeight": "600"}],
                        "label-md": ["14px", {"lineHeight": "20px", "letterSpacing": "0.01em", "fontWeight": "500"}],
                        "display-lg": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}]
                    }
                },
            },
        }
    </script>
<style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-on-surface selection:bg-primary-container selection:text-on-primary-container min-h-screen flex flex-col">
<!-- TopNavBar -->
<nav class="bg-white w-full top-0 sticky z-50 border-b border-slate-100 shadow-sm">
<div class="flex justify-between items-center h-16 px-margin-desktop max-w-container-max mx-auto">
<div class="flex items-center gap-8">
<span class="font-bold text-2xl text-primary cursor-pointer tracking-tight">SafeHands</span>
<div class="hidden md:flex gap-8">
<a class="font-medium text-sm text-primary border-b-2 border-primary pb-5 mt-5 cursor-pointer" href="#">Find Care</a>
<a class="font-medium text-sm text-slate-500 hover:text-primary transition-colors cursor-pointer" href="#">How it Works</a>
<a class="font-medium text-sm text-slate-500 hover:text-primary transition-colors cursor-pointer" href="#">Resources</a>
</div>
</div>
<div class="flex items-center gap-4">
<button class="font-medium text-sm text-slate-600 hover:text-primary transition-colors px-4 py-2">Sign In</button>
<button class="bg-primary text-white px-5 py-2.5 rounded-lg font-semibold text-sm hover:bg-blue-700 transition-all shadow-sm">Join as Caregiver</button>
</div>
</div>
</nav>
<!-- Main Content -->
<main class="flex-grow py-12 px-margin-mobile md:px-margin-desktop">
<div class="max-w-5xl mx-auto">
<!-- Header Section -->
<div class="mb-10 text-center">
<h1 class="font-display-lg text-4xl md:text-5xl text-slate-900 mb-4 tracking-tight">Book Caregiver</h1>
<div class="flex items-center justify-center gap-3">
<div class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-50 shadow-sm">
<img alt="Sarah Wijesinghe" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCnq8L8681knvQxWnctD6fnwdSNN29NmNo8D9wfis7pKjKD5PavuppOxASFDNg7-ex8g6J487O0DA0Rh0n5Sx5AW7kVfFPQEV4JoDzde09ExJc8UB9PcU0pbTpLPbAJkMFO_lx58zgIm_dNugWJg06mnIAA3TnLU00-UbaiUhzK0C_uxeu5LfYuge7wTmD6zb9Nbhi9usI6sJCaMAn3LTEjH1JqF9wjoBcCfSVxW_X-sdNuxPSfiu3bYLOOQV4EsQaORf9Ee2baei8"/>
</div>
<p class="text-lg text-slate-600">Booking <span class="font-semibold text-slate-900">Sarah Wijesinghe</span></p>
</div>
</div>
<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
<!-- Form Area -->
<div class="lg:col-span-8 space-y-6">
<!-- Patient Selection -->
<section class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
<label class="block text-sm font-semibold text-slate-700 mb-4 uppercase tracking-wider">Select Patient</label>
<div class="relative group">
<button class="w-full flex items-center justify-between p-4 bg-slate-50 border border-slate-200 rounded-lg hover:border-primary transition-all text-left group-focus:ring-2 group-focus:ring-primary/20">
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-primary text-xl">person</span>
<span class="font-medium text-slate-900">Sunil Mendis (Father)</span>
</div>
<span class="material-symbols-outlined text-slate-400 group-hover:text-primary transition-colors">expand_more</span>
</button>
<!-- Dropdown Mockup -->
<div class="absolute top-full left-0 right-0 mt-2 bg-white border border-slate-200 rounded-lg shadow-xl hidden group-focus-within:block z-20">
<div class="p-1">
<div class="p-3 hover:bg-slate-50 rounded-md cursor-pointer flex items-center gap-3">
<span class="material-symbols-outlined text-slate-400">person</span>
<span class="text-slate-700">Sunil Mendis (Father)</span>
</div>
<div class="p-3 hover:bg-slate-50 rounded-md cursor-pointer flex items-center gap-3">
<span class="material-symbols-outlined text-slate-400">person</span>
<span class="text-slate-700">Leela Mendis (Mother)</span>
</div>
<div class="border-t border-slate-100 my-1"></div>
<div class="p-3 hover:bg-blue-50 rounded-md cursor-pointer flex items-center gap-3 text-primary">
<span class="material-symbols-outlined">add_circle</span>
<span class="font-semibold">Add New Patient</span>
</div>
</div>
</div>
</div>
</section>
<!-- Care Session #1 -->
<section class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm">
<div class="flex justify-between items-center mb-6">
<h3 class="text-xl font-bold text-slate-900 tracking-tight">Care Session #1</h3>
<button class="text-slate-400 hover:text-error hover:bg-error-container/10 p-2 rounded-full transition-all">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-semibold text-slate-600 mb-2">Select Date</label>
<div class="relative">
<input class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-slate-900 font-medium" type="text" value="15 Jul"/>
<span class="material-symbols-outlined absolute right-3 top-3.5 text-slate-400">calendar_month</span>
</div>
</div>
<div>
<label class="block text-sm font-semibold text-slate-600 mb-2">Select Shifts</label>
<div class="flex flex-wrap gap-2">
<label class="flex-1 cursor-pointer">
<input checked="" class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:border-primary group">
<span class="material-symbols-outlined mb-1">light_mode</span>
<span class="text-xs font-bold uppercase tracking-tighter">Morning</span>
</div>
</label>
<label class="flex-1 cursor-pointer">
<input class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:bg-slate-50 hover:border-primary">
<span class="material-symbols-outlined mb-1">wb_sunny</span>
<span class="text-xs font-bold uppercase tracking-tighter">Afternoon</span>
</div>
</label>
<label class="flex-1 cursor-pointer">
<input class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:bg-slate-50 hover:border-primary">
<span class="material-symbols-outlined mb-1">dark_mode</span>
<span class="text-xs font-bold uppercase tracking-tighter">Evening</span>
</div>
</label>
</div>
</div>
</div>
</section>
<!-- Care Session #2 -->
<section class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm relative overflow-hidden">
<div class="flex justify-between items-center mb-6">
<h3 class="text-xl font-bold text-slate-900 tracking-tight">Care Session #2</h3>
<button class="text-slate-400 hover:text-error hover:bg-error-container/10 p-2 rounded-full transition-all">
<span class="material-symbols-outlined">delete</span>
</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
<div>
<label class="block text-sm font-semibold text-slate-600 mb-2">Select Date</label>
<div class="relative">
<input class="w-full p-3.5 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-slate-900 font-medium" type="text" value="16 Jul"/>
<span class="material-symbols-outlined absolute right-3 top-3.5 text-slate-400">calendar_month</span>
</div>
</div>
<div>
<label class="block text-sm font-semibold text-slate-600 mb-2">Select Shifts</label>
<div class="flex flex-wrap gap-2">
<label class="flex-1 cursor-pointer">
<input class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:bg-slate-50 hover:border-primary">
<span class="material-symbols-outlined mb-1">light_mode</span>
<span class="text-xs font-bold uppercase tracking-tighter">Morning</span>
</div>
</label>
<label class="flex-1 cursor-pointer">
<input class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:bg-slate-50 hover:border-primary">
<span class="material-symbols-outlined mb-1">wb_sunny</span>
<span class="text-xs font-bold uppercase tracking-tighter">Afternoon</span>
</div>
</label>
<label class="flex-1 cursor-pointer">
<input checked="" class="peer hidden" type="checkbox"/>
<div class="flex flex-col items-center justify-center p-3 border border-slate-200 rounded-lg peer-checked:bg-primary peer-checked:border-primary peer-checked:text-white transition-all hover:border-primary">
<span class="material-symbols-outlined mb-1">dark_mode</span>
<span class="text-xs font-bold uppercase tracking-tighter">Evening</span>
</div>
</label>
</div>
</div>
</div>
</section>
<!-- Add Button -->
<button class="w-full py-5 border-2 border-dashed border-slate-200 rounded-lg text-slate-500 hover:border-primary hover:text-primary hover:bg-blue-50/50 transition-all flex items-center justify-center gap-2 font-semibold">
<span class="material-symbols-outlined">add_circle</span>
                    Add Another Date
                </button>
</div>
<!-- Sidebar Summary -->
<aside class="lg:col-span-4 sticky top-24">
<div class="bg-white rounded-lg border border-slate-200 shadow-lg overflow-hidden">
<div class="p-6 bg-slate-50 border-b border-slate-100">
<h4 class="text-lg font-bold text-slate-900 tracking-tight">Booking Summary</h4>
</div>
<div class="p-6 space-y-5">
<div class="space-y-4">
<div class="flex justify-between items-start">
<div>
<p class="font-semibold text-slate-900 text-sm">15 Jul Morning</p>
<p class="text-xs text-slate-500">General Nursing Care</p>
</div>
<span class="font-bold text-slate-900 text-sm">Rs. 2,000</span>
</div>
<div class="flex justify-between items-start">
<div>
<p class="font-semibold text-slate-900 text-sm">16 Jul Evening</p>
<p class="text-xs text-slate-500">Emergency Observation</p>
</div>
<span class="font-bold text-slate-900 text-sm">Rs. 2,500</span>
</div>
<div class="border-t border-slate-100 pt-4 flex justify-between items-center text-slate-500">
<span class="text-sm">Platform Fee</span>
<span class="text-sm font-medium">Rs. 300</span>
</div>
</div>
<div class="bg-blue-50 p-4 rounded-lg">
<div class="flex justify-between items-center">
<span class="text-sm font-semibold text-primary">Total Amount</span>
<span class="text-2xl font-bold text-primary tracking-tight">Rs. 4,800</span>
</div>
</div>
<button
    type="button"
    onclick="window.location.href='payment.php';"
    class="w-full bg-primary text-white py-4 rounded-lg font-bold text-lg hover:bg-blue-700 active:scale-[0.98] transition-all shadow-md flex items-center justify-center gap-3">
    Continue
    <span class="material-symbols-outlined">arrow_forward</span>
</button>
<div class="flex items-center justify-center gap-2 text-status-success">
<span class="material-symbols-outlined text-[18px] fill-1">verified_user</span>
<span class="text-xs font-bold uppercase tracking-tight">Secure Checkout Powered by SafeHands</span>
</div>
</div>
</div>
<!-- Trust Indicators -->
<div class="mt-6 p-5 border border-slate-200 rounded-lg flex items-center gap-4 bg-white shadow-sm">
<div class="bg-blue-50 p-3 rounded-full">
<span class="material-symbols-outlined text-primary text-2xl">safety_check</span>
</div>
<div>
<p class="text-sm font-bold text-slate-900">Clinical Guarantee</p>
<p class="text-xs text-slate-500 leading-relaxed">100% replacement in case of emergencies.</p>
</div>
</div>
</aside>
</div>
</div>
</main>
<!-- Footer -->
<footer class="bg-white w-full mt-auto border-t border-slate-100">
<div class="flex flex-col md:flex-row justify-between items-center py-10 px-margin-desktop max-w-container-max mx-auto">
<div class="flex flex-col gap-2 items-center md:items-start mb-8 md:mb-0">
<span class="font-bold text-2xl text-primary tracking-tight">SafeHands</span>
<p class="text-sm text-slate-500 text-center md:text-left">© 2024 SafeHands Premium Caregiving. All rights reserved.</p>
</div>
<div class="flex flex-wrap justify-center gap-x-8 gap-y-4">
<a class="text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer" href="#">Privacy Policy</a>
<a class="text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer" href="#">Terms of Service</a>
<a class="text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer" href="#">Contact Us</a>
<a class="text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer" href="#">Careers</a>
<a class="text-sm font-medium text-slate-600 hover:text-primary transition-colors cursor-pointer" href="#">FAQ</a>
</div>
</div>
</footer>
<script>
    // Micro-interactions for shift selections
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const card = this.closest('label').querySelector('div');
            if (this.checked) {
                card.classList.add('bg-primary', 'border-primary', 'text-white');
                card.classList.remove('hover:bg-slate-50');
            } else {
                card.classList.remove('bg-primary', 'border-primary', 'text-white');
                card.classList.add('hover:bg-slate-50');
            }
        });
    });

    // Simple button active state visual feedback
    document.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('mousedown', () => btn.classList.add('opacity-90'));
        btn.addEventListener('mouseup', () => btn.classList.remove('opacity-90'));
        btn.addEventListener('mouseleave', () => btn.classList.remove('opacity-90'));
    });
</script>
</body></html>