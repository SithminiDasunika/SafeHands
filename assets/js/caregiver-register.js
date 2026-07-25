document.addEventListener("DOMContentLoaded", function () {

    // =====================================================
    // 1. SHOW / HIDE PASSWORD
    // =====================================================

    const toggleButtons =
        document.querySelectorAll(".password-toggle");

    toggleButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const targetId =
                this.getAttribute("data-target");

            const passwordInput =
                document.getElementById(targetId);

            if (passwordInput.type === "password") {

                passwordInput.type = "text";
                this.textContent = "Hide";

            } else {

                passwordInput.type = "password";
                this.textContent = "Show";

            }

        });

    });


    // =====================================================
    // 2. CHECK WHETHER PASSWORDS MATCH
    // =====================================================

    const password =
        document.getElementById("password");

    const confirmPassword =
        document.getElementById("confirmPassword");


    function checkPasswordMatch() {

        if (
            confirmPassword.value !== "" &&
            password.value !== confirmPassword.value
        ) {

            confirmPassword.setCustomValidity(
                "Passwords do not match."
            );

        } else {

            confirmPassword.setCustomValidity("");

        }

    }


    password.addEventListener(
        "input",
        checkPasswordMatch
    );

    confirmPassword.addEventListener(
        "input",
        checkPasswordMatch
    );


    // =====================================================
    // 3. PREVENT FUTURE DATE OF BIRTH
    // =====================================================

    const dob =
        document.getElementById("dob");

    if (dob) {

        const today =
            new Date();

        const year =
            today.getFullYear();

        const month =
            String(
                today.getMonth() + 1
            ).padStart(2, "0");

        const day =
            String(
                today.getDate()
            ).padStart(2, "0");

        dob.max =
            year + "-" + month + "-" + day;

    }

});