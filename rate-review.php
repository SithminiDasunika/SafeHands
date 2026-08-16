<?php

session_start();

/*
|--------------------------------------------------------------------------
| SAFEHANDS - RATE & REVIEW CAREGIVER
|--------------------------------------------------------------------------
| For now, booking information is sample data.
| Later we will retrieve this using booking_id from MySQL.
|--------------------------------------------------------------------------
*/

$booking = [
    'booking_id' => 'SH-882910',
    'caregiver' => 'SafeHands Caregiver',
    'caregiver_role' => 'Registered Nurse (RN)',
    'caregiver_rating' => '4.9',
    'review_count' => '124',
    'patient' => 'Robert Wilson',
    'duration' => 'Oct 12 - Oct 19 (7 days)',
    'care_completed' => 'All medication tasks completed'
];

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $overall_rating = isset($_POST['overall_rating'])
        ? (int) $_POST['overall_rating']
        : 0;

    $punctuality = isset($_POST['punctuality'])
        ? (int) $_POST['punctuality']
        : 0;

    $communication = isset($_POST['communication'])
        ? (int) $_POST['communication']
        : 0;

    $empathy = isset($_POST['empathy'])
        ? (int) $_POST['empathy']
        : 0;

    $technical = isset($_POST['technical'])
        ? (int) $_POST['technical']
        : 0;

    $review = trim($_POST['review'] ?? '');

    $recommend = $_POST['recommend'] ?? '';

    $anonymous = isset($_POST['anonymous'])
        ? 1
        : 0;


    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if ($overall_rating < 1 || $overall_rating > 5) {

        $error = 'Please select an overall rating.';

    } elseif ($punctuality < 1) {

        $error = 'Please rate the caregiver\'s punctuality.';

    } elseif ($communication < 1) {

        $error = 'Please rate the caregiver\'s communication.';

    } elseif ($empathy < 1) {

        $error = 'Please rate the caregiver\'s empathy.';

    } elseif ($technical < 1) {

        $error = 'Please rate the caregiver\'s technical skill.';

    } elseif (strlen($review) < 20) {

        $error = 'Your written review must contain at least 20 characters.';

    } elseif ($recommend !== 'yes' && $recommend !== 'no') {

        $error = 'Please tell us whether you recommend this caregiver.';

    } else {

        /*
        |--------------------------------------------------------------------------
        | DATABASE INSERT WILL GO HERE
        |--------------------------------------------------------------------------
        |
        | Later we will insert:
        |
        | booking_id
        | caregiver_id
        | family_member_id
        | patient_id
        | overall_rating
        | punctuality
        | communication
        | empathy
        | technical_skill
        | review
        | recommend
        | anonymous
        |
        */

        $success = true;
    }
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
        Rate Your Caregiver | SafeHands
    </title>

    <link
        rel="stylesheet"
        href="assets/css/rate-review.css"
    >

</head>


<body>


<!-- =====================================================
     HEADER
====================================================== -->

<header class="top-header">

    <div class="header-container">


        <div class="header-left">


            <a
                href="dashboard.php"
                class="logo"
            >
                SafeHands
            </a>


            <nav class="main-nav">

                <a href="dashboard.php">
                    Dashboard
                </a>

                <a href="patients.php">
                    Patients
                </a>

                <a href="find-caregivers.php">
                    Find Caregivers
                </a>

                <a
                    href="my-bookings.php"
                    class="active"
                >
                    My Bookings
                </a>

            </nav>


        </div>



        <div class="header-right">


            <div class="search-box">

                <span class="search-icon">
                    ⌕
                </span>

                <input
                    type="text"
                    placeholder="Search appointments..."
                >

            </div>


            <a
                href="notifications.php"
                class="header-button"
                title="Notifications"
            >
                ♧
            </a>


            <a
                href="profile.php"
                class="header-button"
                title="Profile"
            >
                ◯
            </a>


        </div>

    </div>

</header>



<!-- =====================================================
     MAIN
====================================================== -->

<main class="main-container">


    <!-- =================================================
         BREADCRUMB
    ================================================== -->

    <nav class="breadcrumb">

        <a href="dashboard.php">
            Dashboard
        </a>

        <span>›</span>

        <a href="my-bookings.php">
            My Bookings
        </a>

        <span>›</span>

        <span class="current">
            Post-Care Review
        </span>

    </nav>



    <!-- =================================================
         MAIN GRID
    ================================================== -->

    <div class="review-layout">


        <!-- =================================================
             LEFT CAREGIVER CARD
        ================================================== -->

        <aside class="caregiver-sidebar">


            <div class="caregiver-card">


                <div class="caregiver-profile">


                    <div class="profile-image-wrapper">

                        <img
                            src="../assets/images/caregiver-placeholder.jpg"
                            alt="Caregiver"
                            class="profile-image"
                        >

                        <span
                            class="verified-badge"
                            title="Verified Professional"
                        >
                            ✓
                        </span>

                    </div>


                    <h2>
                        <?php
                        echo htmlspecialchars(
                            $booking['caregiver']
                        );
                        ?>
                    </h2>


                    <p class="caregiver-role">

                        <?php
                        echo htmlspecialchars(
                            $booking['caregiver_role']
                        );
                        ?>

                    </p>


                    <!-- CURRENT RATING -->

                    <div class="current-rating">

                        <div class="rating-stars">

                            <span class="filled-star">
                                ★
                            </span>

                            <span class="filled-star">
                                ★
                            </span>

                            <span class="filled-star">
                                ★
                            </span>

                            <span class="filled-star">
                                ★
                            </span>

                            <span class="filled-star">
                                ★
                            </span>

                        </div>


                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $booking['caregiver_rating']
                            );
                            ?>
                        </strong>


                        <span>
                            (
                            <?php
                            echo htmlspecialchars(
                                $booking['review_count']
                            );
                            ?>
                            reviews)
                        </span>

                    </div>


                </div>



                <!-- BOOKING DETAILS -->

                <div class="booking-details">


                    <div class="detail-item">

                        <span class="detail-label">
                            Patient
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $booking['patient']
                            );
                            ?>
                        </strong>

                    </div>



                    <div class="detail-item">

                        <span class="detail-label">
                            Booking Reference
                        </span>

                        <strong>
                            #
                            <?php
                            echo htmlspecialchars(
                                $booking['booking_id']
                            );
                            ?>
                        </strong>

                    </div>



                    <div class="detail-item">

                        <span class="detail-label">
                            Duration
                        </span>

                        <strong>
                            <?php
                            echo htmlspecialchars(
                                $booking['duration']
                            );
                            ?>
                        </strong>

                    </div>



                    <div class="completed-task">

                        <span class="check-icon">
                            ✓
                        </span>

                        <span>
                            <?php
                            echo htmlspecialchars(
                                $booking['care_completed']
                            );
                            ?>
                        </span>

                    </div>


                </div>


            </div>

        </aside>



        <!-- =================================================
             RIGHT REVIEW FORM
        ================================================== -->

        <section class="review-section">


            <div class="review-card">


                <!-- HEADER -->

                <div class="review-header">

                    <h1>
                        How was your care experience?
                    </h1>

                    <p>
                        Your feedback is confidential and helps us
                        maintain our high standard of professional
                        care. It will be shared with the caregiver
                        to help them improve.
                    </p>

                </div>



                <?php if ($error): ?>

                    <div class="error-message">

                        <?php
                        echo htmlspecialchars($error);
                        ?>

                    </div>

                <?php endif; ?>



                <!-- =================================================
                     FORM
                ================================================== -->

                <form
                    method="POST"
                    action=""
                    id="feedbackForm"
                >


                    <!-- =================================================
                         OVERALL RATING
                    ================================================== -->

                    <section class="form-section">


                        <h3 class="section-title">
                            Overall Experience
                        </h3>


                        <div class="overall-rating-container">


                            <div
                                class="star-rating"
                                id="overallRating"
                            >

                                <button
                                    type="button"
                                    class="rating-star"
                                    data-value="1"
                                >
                                    ★
                                </button>

                                <button
                                    type="button"
                                    class="rating-star"
                                    data-value="2"
                                >
                                    ★
                                </button>

                                <button
                                    type="button"
                                    class="rating-star"
                                    data-value="3"
                                >
                                    ★
                                </button>

                                <button
                                    type="button"
                                    class="rating-star"
                                    data-value="4"
                                >
                                    ★
                                </button>

                                <button
                                    type="button"
                                    class="rating-star"
                                    data-value="5"
                                >
                                    ★
                                </button>

                            </div>


                            <span
                                id="ratingDescriptor"
                                class="rating-descriptor"
                            >
                                Select a rating
                            </span>


                        </div>


                        <input
                            type="hidden"
                            name="overall_rating"
                            id="overallRatingInput"
                            value="0"
                        >


                    </section>



                    <!-- =================================================
                         DETAILED ASSESSMENT
                    ================================================== -->

                    <section class="form-section">


                        <h3 class="section-title">
                            Detailed Assessment
                        </h3>


                        <div class="assessment-grid">


                            <!-- PUNCTUALITY -->

                            <div class="assessment-item">


                                <div class="assessment-header">

                                    <strong>
                                        Punctuality
                                    </strong>

                                    <span>
                                        Arrival & timeliness
                                    </span>

                                </div>


                                <div
                                    class="mini-rating"
                                    data-category="punctuality"
                                >

                                    <button
                                        type="button"
                                        data-value="1"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="2"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="3"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="4"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="5"
                                    >
                                        ★
                                    </button>

                                </div>


                                <input
                                    type="hidden"
                                    name="punctuality"
                                    value="0"
                                >

                            </div>



                            <!-- COMMUNICATION -->

                            <div class="assessment-item">


                                <div class="assessment-header">

                                    <strong>
                                        Communication
                                    </strong>

                                    <span>
                                        Clarity and updates
                                    </span>

                                </div>


                                <div
                                    class="mini-rating"
                                    data-category="communication"
                                >

                                    <button
                                        type="button"
                                        data-value="1"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="2"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="3"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="4"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="5"
                                    >
                                        ★
                                    </button>

                                </div>


                                <input
                                    type="hidden"
                                    name="communication"
                                    value="0"
                                >

                            </div>



                            <!-- EMPATHY -->

                            <div class="assessment-item">


                                <div class="assessment-header">

                                    <strong>
                                        Empathy
                                    </strong>

                                    <span>
                                        Patient rapport
                                    </span>

                                </div>


                                <div
                                    class="mini-rating"
                                    data-category="empathy"
                                >

                                    <button
                                        type="button"
                                        data-value="1"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="2"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="3"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="4"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="5"
                                    >
                                        ★
                                    </button>

                                </div>


                                <input
                                    type="hidden"
                                    name="empathy"
                                    value="0"
                                >

                            </div>



                            <!-- TECHNICAL -->

                            <div class="assessment-item">


                                <div class="assessment-header">

                                    <strong>
                                        Technical Skill
                                    </strong>

                                    <span>
                                        Medical proficiency
                                    </span>

                                </div>


                                <div
                                    class="mini-rating"
                                    data-category="technical"
                                >

                                    <button
                                        type="button"
                                        data-value="1"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="2"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="3"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="4"
                                    >
                                        ★
                                    </button>

                                    <button
                                        type="button"
                                        data-value="5"
                                    >
                                        ★
                                    </button>

                                </div>


                                <input
                                    type="hidden"
                                    name="technical"
                                    value="0"
                                >

                            </div>


                        </div>

                    </section>



                    <!-- =================================================
                         WRITTEN REVIEW
                    ================================================== -->

                    <section class="form-section">


                        <label
                            for="review"
                            class="section-title"
                        >
                            Written Review
                        </label>


                        <div class="textarea-wrapper">

                            <textarea
                                name="review"
                                id="review"
                                rows="6"
                                maxlength="1000"
                                placeholder="Share more details about the quality of care provided..."
                            ></textarea>


                            <span id="reviewCounter">
                                Minimum 20 characters
                            </span>

                        </div>


                    </section>



                    <!-- =================================================
                         RECOMMENDATION
                    ================================================== -->

                    <section class="recommendation-section">


                        <div class="recommend-box">


                            <p>
                                Would you recommend this caregiver
                                to others?
                            </p>


                            <div class="recommend-options">


                                <label>

                                    <input
                                        type="radio"
                                        name="recommend"
                                        value="yes"
                                    >

                                    <span>
                                        Yes
                                    </span>

                                </label>


                                <label>

                                    <input
                                        type="radio"
                                        name="recommend"
                                        value="no"
                                    >

                                    <span>
                                        No
                                    </span>

                                </label>


                            </div>


                        </div>



                        <!-- ANONYMOUS -->

                        <label class="anonymous-option">

                            <input
                                type="checkbox"
                                name="anonymous"
                                value="1"
                            >


                            <span class="custom-checkbox">
                            </span>


                            <span class="anonymous-text">

                                <strong>
                                    Post this review anonymously
                                </strong>

                                <small>
                                    Your name will be hidden from
                                    the caregiver and other users.
                                </small>

                            </span>

                        </label>


                    </section>



                    <!-- =================================================
                         ACTIONS
                    ================================================== -->

                    <div class="form-actions">


                        <a
                            href="my-bookings.php"
                            class="discard-button"
                        >
                            Discard
                        </a>


                        <button
                            type="submit"
                            class="submit-button"
                        >
                            Submit Feedback
                        </button>


                    </div>


                </form>


            </div>

        </section>


    </div>

</main>



<!-- =====================================================
     SUCCESS OVERLAY
====================================================== -->

<?php if ($success): ?>

<div
    class="success-overlay active"
    id="successOverlay"
>


    <div class="success-modal">


        <div class="success-icon">
            ✓
        </div>


        <h2>
            Feedback Submitted!
        </h2>


        <p>

            Thank you for helping us maintain the highest
            quality of healthcare service. Your review has
            been saved successfully.

        </p>


        <div class="success-actions">


            <a
                href="dashboard.php"
                class="return-button"
            >
                Return to Dashboard
            </a>


            <a
                href="my-bookings.php"
                class="history-button"
            >
                View My History
            </a>


        </div>


    </div>

</div>

<?php endif; ?>



<!-- =====================================================
     FOOTER
====================================================== -->

<footer class="footer">


    <div class="footer-container">


        <div class="footer-brand">

            <strong>
                SafeHands
            </strong>

            <p>
                © 2026 SafeHands Healthcare.
                All rights reserved.
            </p>

        </div>


        <nav>

            <a href="#">
                Privacy Policy
            </a>

            <a href="#">
                Terms of Service
            </a>

            <a href="#">
                Contact Us
            </a>

        </nav>


    </div>

</footer>



<script
    src="assets/js/rate-review.js"
></script>


</body>

</html>