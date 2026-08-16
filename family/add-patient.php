<?php
session_start();

$pageTitle = "Add New Patient | SafeHands";

/*
|--------------------------------------------------------------------------
| Check Family Member Login
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['logged_in']) ||
    $_SESSION['logged_in'] !== true ||
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    strtolower(trim($_SESSION['role'])) !== 'family'
) {
    header("Location: ../login.php");
    exit;
}

$user_id = (int) $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title><?php echo htmlspecialchars($pageTitle); ?></title>

    <link
        rel="stylesheet"
        href="../assets/css/add-patient.css"
    >

</head>

<body>


<!-- =========================================================
     NAVIGATION HEADER
========================================================= -->

<header class="top-nav">

    <div class="nav-container">

        <div
            style="
                display:flex;
                align-items:center;
                gap:48px;
            "
        >

            <a
                href="dashboard.php"
                class="brand"
            >
                SafeHands
            </a>


            <nav class="nav-links">

                <a
                    href="dashboard.php"
                    class="nav-link"
                >
                    Dashboard
                </a>

                <a
                    href="patients.php"
                    class="nav-link active"
                >
                    Patients
                </a>

                <a
                    href="find-caregivers.php"
                    class="nav-link"
                >
                    Find Caregivers
                </a>

                <a
                    href="my-booking.php"
                    class="nav-link"
                >
                    My Bookings
                </a>

            </nav>

        </div>


        <div class="nav-icons">

            <button
                type="button"
                class="icon-btn"
                aria-label="Notifications"
                onclick="window.location.href='notifications.php'"
            >
                &#128276;
            </button>

            <button
                type="button"
                class="icon-btn"
                aria-label="Account Profile"
            >
                &#128100;
            </button>

        </div>

    </div>

</header>



<!-- =========================================================
     MAIN CONTENT
========================================================= -->

<main class="main-content">


    <!-- Breadcrumb -->

    <nav class="breadcrumb">

        <a href="dashboard.php">
            Dashboard
        </a>

        <span>&rsaquo;</span>

        <a href="patients.php">
            Patients
        </a>

        <span>&rsaquo;</span>

        <span class="active-crumb">
            Add New Patient
        </span>

    </nav>



    <!-- Page Header -->

    <div class="page-header">

        <h1>
            Add New Patient
        </h1>

        <p>
            Create a patient profile to start booking
            professional caregiver services.
        </p>

    </div>



    <!-- =====================================================
         PROGRESS TRACKER
    ====================================================== -->

    <div
        class="progress-tracker"
        id="progress-tracker"
    >

        <div class="tracker-line"></div>

        <div class="tracker-steps">


            <div
                class="step-item active"
                id="step-item-1"
            >

                <div
                    class="dot"
                    id="label-1"
                >
                    1
                </div>

                <span class="step-label">
                    Personal Information
                </span>

            </div>


            <div
                class="step-item"
                id="step-item-2"
            >

                <div
                    class="dot"
                    id="label-2"
                >
                    2
                </div>

                <span class="step-label">
                    Medical Information
                </span>

            </div>


            <div
                class="step-item"
                id="step-item-3"
            >

                <div
                    class="dot"
                    id="label-3"
                >
                    3
                </div>

                <span class="step-label">
                    Emergency &amp; Docs
                </span>

            </div>

        </div>

    </div>



    <!-- =====================================================
         PATIENT FORM
    ====================================================== -->

    <form
        id="patientForm"
        action="process-add-patient.php"
        method="POST"
        enctype="multipart/form-data"
        novalidate
    >


        <div class="form-container">


            <!-- =================================================
                 STEP 1
            ================================================== -->

            <section
                id="step-1-content"
                class="step-transition"
            >

                <div class="card">


                    <!-- Profile Photo -->

                    <div class="profile-upload">

                        <div class="avatar-placeholder">

                            <span style="font-size:28px;">
                                &#128247;
                            </span>

                            <span class="upload-text">
                                UPLOAD PHOTO
                            </span>

                            <input
                                type="file"
                                name="profile_photo"
                                id="profile_photo"
                                accept=".jpg,.jpeg,.png"
                                style="
                                    display:block;
                                    margin-top:10px;
                                "
                            >

                        </div>

                        <p class="upload-hint">
                            Recommended size: 512x512px.
                            Max 2MB.
                        </p>

                    </div>



                    <div class="form-grid">


                        <!-- Full Name -->

                        <div class="form-group">

                            <label for="full_name">
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                class="form-control"
                                placeholder="e.g. Johnathan Doe"
                                required
                            >

                        </div>



                        <!-- Date of Birth -->

                        <div class="form-group">

                            <label for="date_of_birth">
                                Date of Birth
                            </label>

                            <input
                                type="date"
                                id="date_of_birth"
                                name="date_of_birth"
                                class="form-control"
                                required
                            >

                        </div>



                        <!-- Gender -->

                        <div class="form-group">

                            <label for="gender">
                                Gender
                            </label>

                            <select
                                id="gender"
                                name="gender"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Select Gender
                                </option>

                                <option value="Male">
                                    Male
                                </option>

                                <option value="Female">
                                    Female
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>



                        <!-- Relationship -->

                        <div class="form-group">

                            <label for="relationship">
                                Relationship
                            </label>

                            <select
                                id="relationship"
                                name="relationship"
                                class="form-control"
                                required
                            >

                                <option value="">
                                    Select Relationship
                                </option>

                                <option value="Father">
                                    Father
                                </option>

                                <option value="Mother">
                                    Mother
                                </option>

                                <option value="Grandfather">
                                    Grandfather
                                </option>

                                <option value="Grandmother">
                                    Grandmother
                                </option>

                                <option value="Spouse">
                                    Spouse
                                </option>

                                <option value="Self">
                                    Self
                                </option>

                                <option value="Other">
                                    Other
                                </option>

                            </select>

                        </div>



                        <!-- NIC -->

                        <div class="form-group">

                            <label for="nic">
                                NIC Number / ID
                            </label>

                            <input
                                type="text"
                                id="nic"
                                name="nic"
                                class="form-control"
                                placeholder="e.g. 200012345678"
                            >

                        </div>



                        <!-- Phone -->

                        <div class="form-group">

                            <label for="phone">
                                Phone Number
                            </label>

                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                placeholder="+94 77 123 4567"
                            >

                        </div>



                        <!-- Address -->

                        <div class="form-group span-full">

                            <label for="address">
                                Home Address
                            </label>

                            <textarea
                                id="address"
                                name="address"
                                class="form-control"
                                rows="3"
                                placeholder="Street address, City, Apartment, Postal Code"
                            ></textarea>

                        </div>


                    </div>



                    <!-- Buttons -->

                    <div class="button-group">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="window.location.href='patients.php'"
                        >
                            Cancel
                        </button>


                        <button
    type="button"
    class="btn btn-primary"
    onclick="goToStep(2)"
>
    Next
</button>
</button>

                    </div>


                </div>

            </section>



            <!-- =================================================
                 STEP 2
            ================================================== -->

            <section
                id="step-2-content"
                class="step-transition hidden"
            >

                <div class="card">


                    <h3
                        style="
                            font-size:24px;
                            margin-bottom:32px;
                        "
                    >
                        Clinical Profile
                    </h3>


                    <div class="form-grid">


                        <!-- Blood Group -->

                        <div class="form-group">

                            <label for="blood_group">
                                Blood Group
                            </label>

                            <select
                                id="blood_group"
                                name="blood_group"
                                class="form-control"
                            >

                                <option value="">
                                    Select Blood Group
                                </option>

                                <option value="A+">A+</option>
                                <option value="A-">A-</option>
                                <option value="B+">B+</option>
                                <option value="B-">B-</option>
                                <option value="AB+">AB+</option>
                                <option value="AB-">AB-</option>
                                <option value="O+">O+</option>
                                <option value="O-">O-</option>

                            </select>

                        </div>



                        <!-- Mobility -->

                        <div class="form-group">

                            <label for="mobility_status">
                                Mobility Status
                            </label>

                            <select
                                id="mobility_status"
                                name="mobility_status"
                                class="form-control"
                            >

                                <option value="">
                                    Select Mobility Status
                                </option>

                                <option value="Independent">
                                    Independent
                                </option>

                                <option value="Walking Assistance">
                                    Walking Assistance
                                </option>

                                <option value="Wheelchair Bound">
                                    Wheelchair Bound
                                </option>

                                <option value="Bedridden">
                                    Bedridden
                                </option>

                            </select>

                        </div>



                    <!-- Weight -->

<div class="form-group">

<label for="weight">
    Weight (kg)
</label>

<input
    type="number"
    id="weight"
    name="weight"
    class="form-control"
    placeholder="e.g. 68.5"
    min="1"
    max="500"
    step="0.1"
>

</div>


<!-- Blood Pressure -->

<div class="form-group">

<label for="blood_pressure">
    Blood Pressure
</label>

<input
    type="text"
    id="blood_pressure"
    name="blood_pressure"
    class="form-control"
    placeholder="e.g. 130/85"
    maxlength="20"
>

</div>
                    
                        <!-- Medical Conditions -->
                        <div class="form-group span-full">

<label>
    Medical Conditions
</label>

<div class="tags-container" id="medicalConditionsTags">

    <button
        type="button"
        class="tag condition-tag"
        data-condition="Diabetes"
    >
        Diabetes
    </button>

    <button
        type="button"
        class="tag condition-tag"
        data-condition="Hypertension"
    >
        Hypertension
    </button>

    <button
        type="button"
        class="tag condition-tag"
        data-condition="Dementia"
    >
        Dementia
    </button>

    <button
        type="button"
        class="tag condition-tag"
        data-condition="Asthma"
    >
        Asthma
    </button>

    <button
        type="button"
        class="tag condition-tag"
        data-condition="Arthritis"
    >
        Arthritis
    </button>

    <button
        type="button"
        class="tag add-tag"
        id="addConditionButton"
    >
        + Add Condition
    </button>

</div>


<!-- This is what PHP receives -->

<input
    type="hidden"
    name="medical_conditions"
    id="medical_conditions"
    value=""
>

<small
    style="
        display:block;
        margin-top:10px;
        color:#6b7280;
    "
>
    Select all conditions that apply.
</small>

</div>
                        

                        <!-- Allergies -->

                        <div class="form-group">

                            <label for="allergies">
                                Allergies
                            </label>

                            <input
                                type="text"
                                id="allergies"
                                name="allergies"
                                class="form-control"
                                placeholder="e.g. Penicillin, Nuts"
                            >

                        </div>



                        <!-- Dietary -->

                        <div class="form-group">

                            <label for="dietary_restrictions">
                                Dietary Restrictions
                            </label>

                            <input
                                type="text"
                                id="dietary_restrictions"
                                name="dietary_restrictions"
                                class="form-control"
                                placeholder="e.g. Low sodium, Vegetarian"
                            >

                        </div>



                        <!-- Medications -->

                        <div class="form-group span-full">

                            <label for="current_medications">
                                Current Medications
                            </label>

                            <textarea
                                id="current_medications"
                                name="current_medications"
                                class="form-control"
                                rows="2"
                                placeholder="List all prescribed medicines and dosages"
                            ></textarea>

                        </div>



                        <!-- Special Care -->

                        <div class="form-group span-full">

                            <label for="special_care_requirements">
                                Special Care Requirements
                            </label>

                            <textarea
                                id="special_care_requirements"
                                name="special_care_requirements"
                                class="form-control"
                                rows="2"
                                placeholder="Any specific needs or behavioral observations"
                            ></textarea>

                        </div>



                        <!-- Doctor Notes -->

                        <div class="form-group span-full">

                            <label for="doctors_notes">
                                Doctor's Notes
                            </label>

                            <textarea
                                id="doctors_notes"
                                name="doctors_notes"
                                class="form-control"
                                rows="3"
                                placeholder="Additional notes from the patient's doctor"
                            ></textarea>

                        </div>


                    </div>



                    <div class="button-group">

                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="goToStep(1)"
                        >
                            Back
                        </button>


                        <button
                            type="button"
                            class="btn btn-primary"
                            onclick="goToStep(3)"
                        >
                            Next
                        </button>

                    </div>


                </div>

            </section>



            <!-- =================================================
                 STEP 3
            ================================================== -->

            <section
                id="step-3-content"
                class="step-transition hidden"
            >

                <div class="card">


                    <h3
                        style="
                            font-size:24px;
                            margin-bottom:32px;
                        "
                    >
                        Safety &amp; Documentation
                    </h3>


                    <div
                        style="
                            display:flex;
                            flex-direction:column;
                            gap:32px;
                        "
                    >


                        <!-- Emergency Contact -->

                        <div>

                            <div class="section-title">
                                Emergency Contact
                            </div>


                            <div class="form-grid">


                                <div class="form-group">

                                    <label for="emergency_contact_name">
                                        Contact Name
                                    </label>

                                    <input
                                        type="text"
                                        id="emergency_contact_name"
                                        name="emergency_contact_name"
                                        class="form-control"
                                        placeholder="Emergency Person Name"
                                    >

                                </div>


                                <div class="form-group">

                                    <label for="emergency_contact_relationship">
                                        Relationship
                                    </label>

                                    <input
                                        type="text"
                                        id="emergency_contact_relationship"
                                        name="emergency_contact_relationship"
                                        class="form-control"
                                        placeholder="e.g. Sibling, Friend"
                                    >

                                </div>


                                <div class="form-group">

                                    <label for="emergency_contact_phone">
                                        Primary Phone
                                    </label>

                                    <input
                                        type="tel"
                                        id="emergency_contact_phone"
                                        name="emergency_contact_phone"
                                        class="form-control"
                                        placeholder="+94 77 123 4567"
                                    >

                                </div>


                                <div class="form-group">

                                    <label for="emergency_alternative_phone">
                                        Secondary Phone (Optional)
                                    </label>

                                    <input
                                        type="tel"
                                        id="emergency_alternative_phone"
                                        name="emergency_alternative_phone"
                                        class="form-control"
                                        placeholder="+94 77 123 4567"
                                    >

                                </div>


                            </div>

                        </div>


<!-- Medical Documents -->

<div>

    <div class="section-title">
        Medical Document
    </div>


    <div class="dropzone">

        <span
            style="
                font-size:32px;
                color:var(--primary);
                margin-bottom:8px;
            "
        >
            &#9729;
        </span>


        <p style="font-weight:700;">
            Initial medical document
        </p>


        <p
            style="
                font-size:12px;
                color:var(--on-surface-variant);
            "
        >
            PDF, JPG, PNG
        </p>


        <p
            style="
                font-size:12px;
                color:var(--on-surface-variant);
            "
        >
            Prescription, Lab Report, Medical Report, etc.
        </p>


        <input
            type="file"
            name="medical_document"
            id="medical_document"
            accept=".pdf,.jpg,.jpeg,.png"
            style="margin-top:12px;"
        >


        <small
            id="medical-document-name"
            style="
                display:block;
                margin-top:10px;
                color:var(--on-surface-variant);
            "
        >
            No document selected
        </small>

    </div>

</div>
                        </div>


                    </div>



                    <div class="button-group">


                        <button
                            type="button"
                            class="btn btn-secondary"
                            onclick="goToStep(2)"
                        >
                            Back
                        </button>


                        <button
                            type="submit"
                            class="btn btn-primary"
                            id="createPatientButton"
                        >
                            Create Patient Profile
                        </button>


                    </div>


                </div>

            </section>



            <!-- =================================================
                 LOADING
            ================================================== -->

            <section
                id="loading-state"
                class="spinner-container hidden"
            >

                <div class="spinner"></div>

                <h2
                    style="
                        font-size:24px;
                        margin-top:24px;
                    "
                >
                    Creating patient profile...
                </h2>

                <p
                    style="
                        color:var(--on-surface-variant);
                    "
                >
                    Saving patient information securely.
                </p>

            </section>


        </div>

    </form>

</main>



<!-- =========================================================
     FOOTER
========================================================= -->

<footer class="site-footer">

    <div class="footer-container">

        <span class="brand">
            SafeHands
        </span>


        <div class="footer-links">

            <a
                href="#"
                class="footer-link"
            >
                Terms of Service
            </a>

            <a
                href="#"
                class="footer-link"
            >
                Privacy Policy
            </a>

            <a
                href="#"
                class="footer-link"
            >
                Escrow Terms
            </a>

            <a
                href="#"
                class="footer-link"
            >
                Contact Support
            </a>

        </div>


        <p
            style="
                color:var(--on-surface-variant);
                font-size:14px;
            "
        >
            &copy;
            <?php echo date("Y"); ?>
            SafeHands Healthcare.
            All rights reserved.
        </p>

    </div>

</footer>



<script src="../assets/js/add-patient.js"></script>

</body>

</html>