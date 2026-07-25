document.addEventListener(
    "DOMContentLoaded",
    function () {

        const familyCard =
            document.getElementById(
                "familyCard"
            );

        const caregiverCard =
            document.getElementById(
                "caregiverCard"
            );

        const roleInput =
            document.getElementById(
                "role"
            );

        const registrationForm =
            document.getElementById(
                "registrationForm"
            );

        const pageTitle =
            document.getElementById(
                "pageTitle"
            );

        const pageSubtitle =
            document.getElementById(
                "pageSubtitle"
            );

        const caregiverNote =
            document.getElementById(
                "caregiverNote"
            );

        const submitButton =
            document.getElementById(
                "submitButton"
            );


        /*
        ========================================================
        SELECT ACCOUNT TYPE
        ========================================================
        */

        function selectRole(role) {

            familyCard.classList.remove(
                "selected"
            );

            caregiverCard.classList.remove(
                "selected"
            );


            if (role === "Family") {

                familyCard.classList.add(
                    "selected"
                );

                roleInput.value =
                    "Family";


                pageTitle.textContent =
                    "Family Registration";


                pageSubtitle.textContent =
                    "Create an account to find trusted care for your loved one.";


                caregiverNote.classList.remove(
                    "show"
                );


                submitButton.textContent =
                    "Create Family Account";

            }


            if (role === "Caregiver") {

                caregiverCard.classList.add(
                    "selected"
                );

                roleInput.value =
                    "Caregiver";


                pageTitle.textContent =
                    "Caregiver Registration";


                pageSubtitle.textContent =
                    "Create your account and begin your SafeHands caregiver journey.";


                caregiverNote.classList.add(
                    "show"
                );


                submitButton.textContent =
                    "Create Caregiver Account";

            }


            registrationForm.classList.add(
                "show"
            );


            setTimeout(
                function () {

                    registrationForm.scrollIntoView(
                        {
                            behavior:
                                "smooth",

                            block:
                                "start"
                        }
                    );

                },

                100
            );

        }


        familyCard.addEventListener(
            "click",
            function () {

                selectRole(
                    "Family"
                );

            }
        );


        caregiverCard.addEventListener(
            "click",
            function () {

                selectRole(
                    "Caregiver"
                );

            }
        );


        /*
        ========================================================
        RESTORE SELECTED ROLE AFTER PHP VALIDATION ERROR
        ========================================================
        */

        if (
            roleInput.value === "Family"
        ) {

            selectRole(
                "Family"
            );

        } else if (
            roleInput.value === "Caregiver"
        ) {

            selectRole(
                "Caregiver"
            );

        }


        /*
        ========================================================
        SHOW / HIDE PASSWORD
        ========================================================
        */

        const passwordButtons =
            document.querySelectorAll(
                ".password-toggle"
            );


        passwordButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const targetId =
                            button.getAttribute(
                                "data-target"
                            );

                        const input =
                            document.getElementById(
                                targetId
                            );


                        if (
                            input.type ===
                            "password"
                        ) {

                            input.type =
                                "text";

                            button.textContent =
                                "Hide";

                        } else {

                            input.type =
                                "password";

                            button.textContent =
                                "Show";

                        }

                    }
                );

            }
        );


        /*
        ========================================================
        CLIENT-SIDE PASSWORD MATCH CHECK
        ========================================================
        */

        const password =
            document.getElementById(
                "password"
            );

        const confirmPassword =
            document.getElementById(
                "confirmPassword"
            );


        function validatePasswords() {

            if (
                confirmPassword.value !== "" &&
                password.value !==
                confirmPassword.value
            ) {

                confirmPassword.setCustomValidity(
                    "Passwords do not match."
                );

            } else {

                confirmPassword.setCustomValidity(
                    ""
                );

            }

        }


        password.addEventListener(
            "input",
            validatePasswords
        );


        confirmPassword.addEventListener(
            "input",
            validatePasswords
        );


        /*
        ========================================================
        PREVENT SUBMISSION WITHOUT ACCOUNT TYPE
        ========================================================
        */

        registrationForm.addEventListener(
            "submit",
            function (event) {

                if (
                    roleInput.value !== "Family" &&
                    roleInput.value !== "Caregiver"
                ) {

                    event.preventDefault();

                    alert(
                        "Please select Family Member or Caregiver."
                    );

                    return;
                }


                validatePasswords();

            }
        );

    }
);