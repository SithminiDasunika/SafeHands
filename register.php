<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Your Account | SafeHands</title>

    <link
        rel="stylesheet"
        href="assets/css/register.css"
    >

</head>

<body>


<div class="register-page">


    <!-- =====================================================
         LEFT SIDE
    ====================================================== -->

    <section class="left-panel">


        <div class="left-background"></div>


        <div class="left-content">


            <a
                href="index.php"
                class="logo"
            >
                SafeHands
            </a>


            <div class="left-main">


                <h1>
                    Join SafeHands
                </h1>


                <p class="left-description">

                    Helping families connect with trusted
                    caregivers across Sri Lanka.

                    Professional healthcare starts with
                    the right hands.

                </p>


                <div class="benefits">


                    <div class="benefit-item">


                        <div class="benefit-icon">

                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <path
                                    d="M12 3L19 6V11C19 15.5 16 19.3 12 21C8 19.3 5 15.5 5 11V6L12 3Z"
                                />

                                <path
                                    d="M9 12L11 14L15 10"
                                />

                            </svg>

                        </div>


                        <span>
                            Verified Professional Caregivers
                        </span>


                    </div>



                    <div class="benefit-item">


                        <div class="benefit-icon">

                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <path
                                    d="M4 13V11C4 6.6 7.6 3 12 3C16.4 3 20 6.6 20 11V13"
                                />

                                <path
                                    d="M4 12H7V18H5C4.4 18 4 17.6 4 17V12Z"
                                />

                                <path
                                    d="M20 12H17V18H19C19.6 18 20 17.6 20 17V12Z"
                                />

                                <path
                                    d="M17 18C16.5 20 14.5 21 12 21"
                                />

                            </svg>

                        </div>


                        <span>
                            24/7 Dedicated Support Team
                        </span>


                    </div>


                </div>


            </div>


        </div>


    </section>



    <!-- =====================================================
         RIGHT SIDE
    ====================================================== -->

    <main class="right-panel">


        <div class="selection-container">


            <!-- MOBILE LOGO -->

            <a
                href="index.php"
                class="mobile-logo"
            >
                SafeHands
            </a>



            <!-- HEADER -->

            <div class="selection-header">


                <h2>
                    Create Your Account
                </h2>


                <p>
                    Choose how you would like to use SafeHands.
                </p>


            </div>



            <!-- =================================================
                 ROLE CARDS
            ================================================== -->

            <div class="role-grid">


                <!-- FAMILY MEMBER -->

                <a
                    href="family-register.php"
                    class="role-card"
                >


                    <div class="role-card-top">


                        <div class="role-icon">


                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <circle
                                    cx="8"
                                    cy="8"
                                    r="3"
                                />

                                <circle
                                    cx="16"
                                    cy="8"
                                    r="3"
                                />

                                <path
                                    d="M3 19C3 15.7 5.2 13 8 13C10.8 13 13 15.7 13 19"
                                />

                                <path
                                    d="M11 19C11 15.7 13.2 13 16 13C18.8 13 21 15.7 21 19"
                                />

                            </svg>


                        </div>


                        <div class="arrow-circle">
                            →
                        </div>


                    </div>


                    <h3>
                        Family Member
                    </h3>


                    <p>

                        I am looking for care for a loved one.

                    </p>


                </a>



                <!-- CAREGIVER -->

                <a
                    href="caregiver-register.php"
                    class="role-card"
                >


                    <div class="role-card-top">


                        <div class="role-icon">


                            <svg
                                viewBox="0 0 24 24"
                                aria-hidden="true"
                            >

                                <rect
                                    x="4"
                                    y="6"
                                    width="16"
                                    height="14"
                                    rx="2"
                                />

                                <path
                                    d="M9 6V4H15V6"
                                />

                                <path
                                    d="M12 10V16"
                                />

                                <path
                                    d="M9 13H15"
                                />

                            </svg>


                        </div>


                        <div class="arrow-circle">
                            →
                        </div>


                    </div>


                    <h3>
                        Caregiver
                    </h3>


                    <p>

                        I am a professional seeking work.

                    </p>


                </a>


            </div>



            <!-- =================================================
                 LOGIN
            ================================================== -->

            <div class="login-footer">


                <p>

                    Already have an account?

                    <a href="login.php">
                        Log In
                    </a>

                </p>


            </div>


        </div>


    </main>


</div>


</body>

</html>