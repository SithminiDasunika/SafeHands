<?php

session_start();

/*
|--------------------------------------------------------------------------
| Login state
|--------------------------------------------------------------------------
| We do not need a database connection just to display the homepage.
| If a user is logged in, their session information is used.
*/

$isLoggedIn = isset($_SESSION['user_id']);

$firstName = $_SESSION['first_name'] ?? '';
$role      = $_SESSION['role'] ?? '';

/*
|--------------------------------------------------------------------------
| Decide dashboard according to role
|--------------------------------------------------------------------------
*/

$dashboardUrl = '#';

if ($role === 'Family' || $role === 'family') {
    $dashboardUrl = 'family/dashboard.php';
} elseif ($role === 'Caregiver' || $role === 'caregiver') {
    $dashboardUrl = 'caregiver/dashboard.php';
} elseif ($role === 'Admin' || $role === 'admin') {
    $dashboardUrl = 'admin/dashboard.php';
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
        SafeHands | Trusted Care for Your Loved Ones
    </title>

    <link
        rel="stylesheet"
        href="assets/css/home.css"
    >

</head>

<body>


<!-- =========================================================
     NAVIGATION
========================================================= -->

<header class="site-header">

    <div class="container navbar">

        <a
            href="index.php"
            class="logo"
        >
            SafeHands
        </a>


        <nav
            class="nav-menu"
            id="navMenu"
        >

            <a
                href="#home"
                class="nav-link active"
            >
                Home
            </a>

            <a
                href="#about"
                class="nav-link"
            >
                About
            </a>

            <a
                href="#services"
                class="nav-link"
            >
                Services
            </a>

            <a
                href="find-caregivers.php"
                class="nav-link"
            >
                Find Caregivers
            </a>

            <a
                href="#contact"
                class="nav-link"
            >
                Contact
            </a>

        </nav>


        <div class="nav-actions">

            <?php if (!$isLoggedIn): ?>

                <a
                    href="login.php"
                    class="login-button"
                >
                    Login
                </a>

                <a
                    href="register.php"
                    class="register-button"
                >
                    Register
                </a>

            <?php else: ?>

                <span class="welcome-user">

                    Hi,
                    <?= htmlspecialchars($firstName) ?>

                </span>

                <a
                    href="<?= htmlspecialchars($dashboardUrl) ?>"
                    class="login-button"
                >
                    Dashboard
                </a>

                <a
                    href="logout.php"
                    class="register-button"
                >
                    Logout
                </a>

            <?php endif; ?>

        </div>


        <button
            type="button"
            class="mobile-menu-button"
            id="mobileMenuButton"
            aria-label="Open menu"
        >
            ☰
        </button>

    </div>

</header>



<main>


<!-- =========================================================
     HERO
========================================================= -->

<section
    class="hero-section"
    id="home"
>

    <div class="container hero-container">


        <div class="hero-content">

            <div class="verification-badge">

                <span class="small-icon">
                    ✓
                </span>

                Verified Clinical Excellence

            </div>


            <h1>

                Trusted Care for Your

                <span>
                    Loved Ones
                </span>

            </h1>


            <p class="hero-text">

                Find verified caregivers, book care services
                with confidence, and stay connected through
                secure daily updates.

            </p>


            <div class="hero-buttons">

                <a
                    href="find-caregivers.php"
                    class="primary-button large-button"
                >
                    Find a Caregiver

                    <span>
                        ›
                    </span>
                </a>


                <a
                    href="register.php"
                    class="secondary-button large-button"
                >
                    Become a Caregiver
                </a>

            </div>


            <div class="hero-checks">

                <div class="check-item">

                    <span class="check-circle">
                        ✓
                    </span>

                    Verified Caregivers

                </div>


                <div class="check-item">

                    <span class="check-circle">
                        ✓
                    </span>

                    Secure Booking

                </div>


                <div class="check-item">

                    <span class="check-circle">
                        ✓
                    </span>

                    Daily Care Reports

                </div>


                <div class="check-item">

                    <span class="check-circle">
                        ✓
                    </span>

                    OTP Arrival Verification

                </div>

            </div>

        </div>



        <div class="hero-image-area">

            <div class="hero-decoration decoration-one"></div>

            <div class="hero-decoration decoration-two"></div>

            <div class="hero-image-card">

                <img
                    src="assets/images/home/hero-caregiver.jpg"
                    alt="Professional caregiver assisting an elderly person"
                >

            </div>

        </div>


    </div>

</section>



<!-- =========================================================
     TRUST BADGES
========================================================= -->

<section class="trust-section">

    <div class="container trust-grid">


        <article class="trust-item">

            <div class="feature-icon">
                ✓
            </div>

            <h3>
                Verified Caregivers
            </h3>

            <p>
                Every professional undergoes a rigorous
                7-point background check.
            </p>

        </article>



        <article class="trust-item">

            <div class="feature-icon">
                $
            </div>

            <h3>
                Secure Payment
            </h3>

            <p>
                Encrypted transactions with transparent
                billing and no hidden fees.
            </p>

        </article>



        <article class="trust-item">

            <div class="feature-icon">
                🔑
            </div>

            <h3>
                OTP Verified Arrival
            </h3>

            <p>
                Real-time verification for every visit
                to ensure your family's safety.
            </p>

        </article>



        <article class="trust-item">

            <div class="feature-icon">
                ☎
            </div>

            <h3>
                Emergency Support
            </h3>

            <p>
                24/7 access to our clinical support team
                for any urgent needs.
            </p>

        </article>


    </div>

</section>



<!-- =========================================================
     ABOUT / WHY SAFEHANDS
========================================================= -->

<section
    class="about-section"
    id="about"
>

    <div class="container about-container">


        <div class="about-image-card">

            <img
                src="assets/images/home/why-safehands.jpg"
                alt="SafeHands caregiver and patient care dashboard"
            >

        </div>



        <div class="about-content">

            <h2>
                Why SafeHands?
            </h2>

            <p class="section-description">

                We believe that caregiving should be transparent,
                safe, and easily accessible. SafeHands bridges
                the gap between professional caregivers and
                families through a platform built on rigorous
                verification and real-time communication.

            </p>


            <div class="about-feature">

                <div class="about-feature-icon">
                    ↔
                </div>

                <div>

                    <h3>
                        Total Transparency
                    </h3>

                    <p>
                        Live updates and care logs keep you informed
                        of your loved one's status throughout the day.
                    </p>

                </div>

            </div>


            <div class="about-feature">

                <div class="about-feature-icon">
                    🛡
                </div>

                <div>

                    <h3>
                        Unmatched Safety
                    </h3>

                    <p>
                        Every caregiver undergoes a 7-point
                        background check and clinical skill
                        assessment before joining our network.
                    </p>

                </div>

            </div>

        </div>


    </div>

</section>



<!-- =========================================================
     SERVICES
========================================================= -->

<section
    class="services-section"
    id="services"
>

    <div class="container">


        <div class="section-heading">

            <h2>
                Comprehensive Care Solutions
            </h2>

            <p>
                Providing a safe ecosystem for specialized
                medical assistance and home care services.
            </p>

        </div>



        <div class="services-grid">


            <article class="service-card">

                <div class="service-icon">
                    ♙
                </div>

                <h3>
                    Verified Caregivers
                </h3>

                <p>
                    Expertly vetted professionals with verified
                    credentials and specialized medical backgrounds.
                </p>

            </article>



            <article class="service-card">

                <div class="service-icon">
                    ▣
                </div>

                <h3>
                    Flexible Shift Booking
                </h3>

                <p>
                    Schedule care precisely when you need it,
                    from hourly visits to 24/7 specialized
                    nursing support.
                </p>

            </article>



            <article class="service-card">

                <div class="service-icon">
                    ▤
                </div>

                <h3>
                    Daily Care Reports
                </h3>

                <p>
                    Receive detailed digital reports on medication,
                    nutrition, and activities directly through
                    SafeHands.
                </p>

            </article>



            <article class="service-card">

                <div class="service-icon">
                    ✓
                </div>

                <h3>
                    Secure OTP Verification
                </h3>

                <p>
                    Arrivals and departures are verified via OTP
                    to ensure authorized access at all times.
                </p>

            </article>



            <article class="service-card">

                <div class="service-icon">
                    ✚
                </div>

                <h3>
                    Emergency Assistance
                </h3>

                <p>
                    Immediate emergency support and ambulance
                    coordination for critical situations.
                </p>

            </article>



            <article class="service-card">

                <div class="service-icon">
                    $
                </div>

                <h3>
                    Secure Payments
                </h3>

                <p>
                    Simplified billing and secure payment
                    processing for peace of mind.
                </p>

            </article>


        </div>

    </div>

</section>



<!-- =========================================================
     HOW IT WORKS
========================================================= -->

<section
    class="how-section"
    id="how-it-works"
>

    <div class="container">

        <div class="section-heading">

            <h2>
                How It Works
            </h2>

        </div>


        <div class="steps-wrapper">

            <div class="steps-line"></div>


            <article class="step-card">

                <div class="step-circle">

                    <span class="step-number">
                        1
                    </span>

                    <span class="step-main-icon">
                        ⌕
                    </span>

                </div>

                <h3>
                    Browse Caregivers
                </h3>

                <p>
                    Filter by expertise, location, and
                    availability to find your match.
                </p>

            </article>



            <article class="step-card">

                <div class="step-circle">

                    <span class="step-number">
                        2
                    </span>

                    <span class="step-main-icon">
                        ♙+
                    </span>

                </div>

                <h3>
                    Create Account
                </h3>

                <p>
                    Quickly set up your profile and
                    care requirements.
                </p>

            </article>



            <article class="step-card">

                <div class="step-circle">

                    <span class="step-number">
                        3
                    </span>

                    <span class="step-main-icon">
                        ✓
                    </span>

                </div>

                <h3>
                    Book Available Shift
                </h3>

                <p>
                    Confirm your booking with our
                    secure scheduling system.
                </p>

            </article>



            <article class="step-card">

                <div class="step-circle">

                    <span class="step-number">
                        4
                    </span>

                    <span class="step-main-icon">
                        ♢
                    </span>

                </div>

                <h3>
                    Receive Care Updates
                </h3>

                <p>
                    Stay updated with real-time reports
                    throughout the session.
                </p>

            </article>


        </div>

    </div>

</section>



<!-- =========================================================
     TESTIMONIALS
========================================================= -->

<section class="testimonials-section">

    <div class="container">


        <div class="section-heading">

            <h2>
                Trusted by Families
            </h2>

        </div>


        <div class="testimonials-grid">


            <article class="testimonial-card">

                <div class="stars">
                    ★★★★★
                </div>

                <p class="testimonial-text">

                    "SafeHands has completely changed how we
                    manage my father's care. The daily reports
                    give me such peace of mind while I'm at work."

                </p>


                <div class="reviewer">

                    <img
                        src="assets/images/home/testimonial-1.jpg"
                        alt="Sarah Mitchell"
                    >

                    <div>

                        <h4>
                            Sarah Mitchell
                        </h4>

                        <span>
                            Family Member
                        </span>

                    </div>

                </div>

            </article>



            <article class="testimonial-card">

                <div class="stars">
                    ★★★★★
                </div>

                <p class="testimonial-text">

                    "The OTP verification system makes me feel
                    so much safer. I always know exactly who is
                    entering my mother's home and when."

                </p>


                <div class="reviewer">

                    <img
                        src="assets/images/home/testimonial-2.jpg"
                        alt="David Chen"
                    >

                    <div>

                        <h4>
                            David Chen
                        </h4>

                        <span>
                            Family Member
                        </span>

                    </div>

                </div>

            </article>



            <article class="testimonial-card">

                <div class="stars">
                    ★★★★★
                </div>

                <p class="testimonial-text">

                    "Finding qualified caregivers used to take
                    weeks. With SafeHands, I found a fantastic
                    nurse for my husband within 24 hours."

                </p>


                <div class="reviewer">

                    <img
                        src="assets/images/home/testimonial-3.jpg"
                        alt="Elena Rodriguez"
                    >

                    <div>

                        <h4>
                            Elena Rodriguez
                        </h4>

                        <span>
                            Family Member
                        </span>

                    </div>

                </div>

            </article>


        </div>

    </div>

</section>



<!-- =========================================================
     FAQ
========================================================= -->

<section class="faq-section">

    <div class="faq-container">


        <div class="section-heading">

            <h2>
                Frequently Asked Questions
            </h2>

        </div>



        <div class="faq-item active">

            <button
                type="button"
                class="faq-question"
            >

                <span>
                    How are caregivers verified?
                </span>

                <span class="faq-arrow">
                    ▲
                </span>

            </button>


            <div class="faq-answer">

                <p>

                    Every caregiver goes through a rigorous
                    7-point background check, including ID
                    verification, criminal record checks,
                    reference checks, and qualification
                    verification.

                </p>

            </div>

        </div>



        <div class="faq-item">

            <button
                type="button"
                class="faq-question"
            >

                <span>
                    Can I book care for a single shift?
                </span>

                <span class="faq-arrow">
                    ▼
                </span>

            </button>


            <div class="faq-answer">

                <p>

                    Yes. SafeHands is designed for flexible
                    care bookings. Families can book available
                    caregivers according to their required
                    date and shift.

                </p>

            </div>

        </div>



        <div class="faq-item">

            <button
                type="button"
                class="faq-question"
            >

                <span>
                    What is OTP verification?
                </span>

                <span class="faq-arrow">
                    ▼
                </span>

            </button>


            <div class="faq-answer">

                <p>

                    OTP verification uses a unique one-time code
                    to confirm the caregiver's arrival. This helps
                    ensure that the assigned caregiver is present
                    before the care session begins.

                </p>

            </div>

        </div>


    </div>

</section>



<!-- =========================================================
     CONTACT
========================================================= -->

<section
    class="contact-section"
    id="contact"
>

    <div class="container contact-container">


        <div class="contact-information">

            <h2>
                Get in Touch
            </h2>

            <p class="contact-introduction">

                Our care coordinators are available to answer
                your questions and help you find the right
                caregiver.

            </p>


            <div class="contact-details">


                <div class="contact-detail">

                    <span class="contact-icon">
                        ☎
                    </span>

                    <span>
                        +94 XX XXX XXXX
                    </span>

                </div>


                <div class="contact-detail">

                    <span class="contact-icon">
                        ✉
                    </span>

                    <span>
                        support@safehands.com
                    </span>

                </div>


                <div class="contact-detail">

                    <span class="contact-icon">
                        ●
                    </span>

                    <span>
                        Colombo, Sri Lanka
                    </span>

                </div>


            </div>


            <div class="map-card">

                <img
                    src="assets/images/home/map.jpg"
                    alt="SafeHands location map"
                >

            </div>

        </div>



        <div class="contact-form-card">


            <form
                id="contactForm"
                action=""
                method="POST"
            >


                <div class="form-group">

                    <label for="contactName">
                        Your Name
                    </label>

                    <input
                        type="text"
                        id="contactName"
                        name="name"
                        placeholder="Full Name"
                    >

                </div>



                <div class="form-group">

                    <label for="contactEmail">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="contactEmail"
                        name="email"
                        placeholder="email@example.com"
                    >

                </div>



                <div class="form-group">

                    <label for="contactMessage">
                        Message
                    </label>

                    <textarea
                        id="contactMessage"
                        name="message"
                        rows="5"
                        placeholder="How can we help you?"
                    ></textarea>

                </div>



                <button
                    type="submit"
                    class="primary-button send-button"
                >
                    Send Message
                </button>


            </form>


        </div>


    </div>

</section>


</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="footer">

    <div class="container footer-grid">


        <div class="footer-about">

            <a
                href="index.php"
                class="footer-logo"
            >
                SafeHands
            </a>

            <p>
                Elevating home care through technology,
                trust, and verified clinical excellence.
            </p>

        </div>



        <div class="footer-column">

            <h3>
                Quick Links
            </h3>

            <a href="#how-it-works">
                Find Caregivers
            </a>

            <a href="#">
                Safety Standards
            </a>

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Terms of Service
            </a>

        </div>



        <div class="footer-column">

            <h3>
                Company
            </h3>

            <a href="#about">
                About Us
            </a>

            <a href="#">
                Careers
            </a>

            <a href="#">
                Newsroom
            </a>

            <a href="#contact">
                Contact
            </a>

        </div>



        <div class="footer-column">

            <h3>
                Connect
            </h3>

            <div class="social-links">

                <a href="#">
                    ◎
                </a>

                <a href="#">
                    ◉
                </a>

                <a href="#">
                    ↗
                </a>

            </div>


            <p class="copyright">

                © <?= date('Y') ?> SafeHands.
                All rights reserved.

            </p>

        </div>


    </div>

</footer>


<script src="assets/js/home.js"></script>

</body>

</html>