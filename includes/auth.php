<?php

/*
|--------------------------------------------------------------------------
| SAFEHANDS - AUTHENTICATION & AUTHORIZATION
|--------------------------------------------------------------------------
|
| File:
| includes/auth.php
|
| Purpose:
| - Check whether a user is logged in
| - Protect pages based on user role
| - Prevent Pending/Rejected caregivers from accessing
|   verified caregiver pages
|
*/


/*
|--------------------------------------------------------------------------
| START SESSION
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {

    session_start();

}


/*
|--------------------------------------------------------------------------
| REQUIRE LOGIN
|--------------------------------------------------------------------------
|
| Use this when a page should only be available to logged-in users.
|
*/

function requireLogin(): void
{

    /*
    |--------------------------------------------------------------------------
    | CHECK AUTHENTICATION SESSION
    |--------------------------------------------------------------------------
    */

    if (
        empty($_SESSION['logged_in']) ||
        empty($_SESSION['user_id']) ||
        empty($_SESSION['role'])
    ) {

        header(
            "Location: /safehands/login.php"
        );

        exit;

    }

}


/*
|--------------------------------------------------------------------------
| REQUIRE SPECIFIC ROLE
|--------------------------------------------------------------------------
|
| Example:
|
| requireRole('Family');
| requireRole('Admin');
| requireRole('Caregiver');
|
*/

function requireRole(string $requiredRole): void
{

    /*
    |--------------------------------------------------------------------------
    | FIRST CHECK LOGIN
    |--------------------------------------------------------------------------
    */

    requireLogin();


    /*
    |--------------------------------------------------------------------------
    | GET CURRENT USER ROLE
    |--------------------------------------------------------------------------
    */

    $currentRole =
        trim(
            $_SESSION['role']
        );


    /*
    |--------------------------------------------------------------------------
    | CORRECT ROLE
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $currentRole,
            $requiredRole
        ) === 0
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | WRONG ROLE
    |--------------------------------------------------------------------------
    |
    | Send the logged-in user back to the appropriate area.
    |
    */


    /*
    |--------------------------------------------------------------------------
    | FAMILY USER
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $currentRole,
            'Family'
        ) === 0
    ) {

        header(
            "Location: /safehands/family/dashboard.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN USER
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $currentRole,
            'Admin'
        ) === 0
    ) {

        header(
            "Location: /safehands/admin/dashboard.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | CAREGIVER USER
    |--------------------------------------------------------------------------
    |
    | We do not send a caregiver directly to the dashboard here.
    |
    | A caregiver may still be Pending or Rejected.
    |
    | Sending them through login allows the verification status
    | routing to determine the correct destination.
    |
    */

    if (
        strcasecmp(
            $currentRole,
            'Caregiver'
        ) === 0
    ) {

        header(
            "Location: /safehands/login.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | UNKNOWN ROLE
    |--------------------------------------------------------------------------
    */

    header(
        "Location: /safehands/login.php"
    );

    exit;

}


/*
|--------------------------------------------------------------------------
| REQUIRE VERIFIED CAREGIVER
|--------------------------------------------------------------------------
|
| Use this function on pages that should only be accessible by
| APPROVED / VERIFIED caregivers.
|
| Example:
|
| caregiver/dashboard.php
| caregiver/bookings.php
| caregiver/availability.php
| caregiver/care-reports.php
|
|
| Requirements:
|
| 1. User must be logged in.
| 2. User must have role = Caregiver.
| 3. caregiver_profiles must exist.
| 4. verification_status must be Verified.
|
*/

function requireVerifiedCaregiver(mysqli $conn): void
{

    /*
    |--------------------------------------------------------------------------
    | CHECK LOGIN
    |--------------------------------------------------------------------------
    */

    requireLogin();


    /*
    |--------------------------------------------------------------------------
    | CHECK ROLE
    |--------------------------------------------------------------------------
    */

    $currentRole =
        trim(
            $_SESSION['role']
        );


    if (
        strcasecmp(
            $currentRole,
            'Caregiver'
        ) !== 0
    ) {

        /*
        |--------------------------------------------------------------------------
        | FAMILY USER TRYING TO ACCESS CAREGIVER PAGE
        |--------------------------------------------------------------------------
        */

        if (
            strcasecmp(
                $currentRole,
                'Family'
            ) === 0
        ) {

            header(
                "Location: /safehands/family/dashboard.php"
            );

            exit;

        }


        /*
        |--------------------------------------------------------------------------
        | ADMIN TRYING TO ACCESS CAREGIVER PAGE
        |--------------------------------------------------------------------------
        */

        if (
            strcasecmp(
                $currentRole,
                'Admin'
            ) === 0
        ) {

            header(
                "Location: /safehands/admin/dashboard.php"
            );

            exit;

        }


        /*
        |--------------------------------------------------------------------------
        | UNKNOWN ROLE
        |--------------------------------------------------------------------------
        */

        header(
            "Location: /safehands/login.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | GET LOGGED-IN CAREGIVER USER ID
    |--------------------------------------------------------------------------
    */

    $userId =
        (int)$_SESSION['user_id'];


    /*
    |--------------------------------------------------------------------------
    | GET CAREGIVER VERIFICATION STATUS
    |--------------------------------------------------------------------------
    */

    $stmt =
        $conn->prepare(

            "SELECT
                verification_status

             FROM caregiver_profiles

             WHERE user_id = ?

             LIMIT 1"

        );


    /*
    |--------------------------------------------------------------------------
    | DATABASE QUERY COULD NOT BE PREPARED
    |--------------------------------------------------------------------------
    */

    if (!$stmt) {

        header(
            "Location: /safehands/login.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | BIND USER ID
    |--------------------------------------------------------------------------
    */

    $stmt->bind_param(
        "i",
        $userId
    );


    /*
    |--------------------------------------------------------------------------
    | EXECUTE QUERY
    |--------------------------------------------------------------------------
    */

    if (!$stmt->execute()) {

        $stmt->close();


        header(
            "Location: /safehands/login.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | GET RESULT
    |--------------------------------------------------------------------------
    */

    $result =
        $stmt->get_result();


    /*
    |--------------------------------------------------------------------------
    | CAREGIVER PROFILE NOT FOUND
    |--------------------------------------------------------------------------
    */

    if (
        $result->num_rows
        !== 1
    ) {

        $stmt->close();


        header(
            "Location: /safehands/login.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | GET CAREGIVER DATA
    |--------------------------------------------------------------------------
    */

    $caregiver =
        $result->fetch_assoc();


    $verificationStatus =
        trim(
            $caregiver[
                'verification_status'
            ]
        );


    $stmt->close();


    /*
    |--------------------------------------------------------------------------
    | VERIFIED CAREGIVER
    |--------------------------------------------------------------------------
    |
    | Allow the requested page to continue loading.
    |
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Verified'
        ) === 0
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | PENDING CAREGIVER
    |--------------------------------------------------------------------------
    |
    | Pending caregivers cannot access the caregiver dashboard.
    |
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Pending'
        ) === 0
    ) {

        header(
            "Location: /safehands/caregiver-application-success.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | REJECTED CAREGIVER
    |--------------------------------------------------------------------------
    */

    if (
        strcasecmp(
            $verificationStatus,
            'Rejected'
        ) === 0
    ) {

        header(
            "Location: /safehands/caregiver/application-rejected.php"
        );

        exit;

    }


    /*
    |--------------------------------------------------------------------------
    | UNKNOWN VERIFICATION STATUS
    |--------------------------------------------------------------------------
    */

    header(
        "Location: /safehands/login.php"
    );

    exit;

}

?>