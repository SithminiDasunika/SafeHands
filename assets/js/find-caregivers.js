/* ==========================================================
   FIND CAREGIVERS
   SafeHands
========================================================== */

document.addEventListener("DOMContentLoaded", function () {

    /* =====================================
       SEARCH INPUT
    ===================================== */

    const searchInput = document.querySelector(
        "input[name='search']"
    );

    if (searchInput) {

        searchInput.addEventListener("keypress", function (e) {

            if (e.key === "Enter") {

                this.form.submit();

            }

        });

    }



    /* =====================================
       AUTO SUBMIT FILTERS
    ===================================== */

    const selects = document.querySelectorAll("select");

    selects.forEach(function (item) {

        item.addEventListener("change", function () {

            if (this.form) {

                this.form.submit();

            }

        });

    });



    /* =====================================
       VERIFIED CHECKBOX
    ===================================== */

    const verified = document.querySelector(
        "input[name='verified']"
    );

    if (verified) {

        verified.addEventListener("change", function () {

            this.form.submit();

        });

    }



    /* =====================================
       EXPERIENCE
    ===================================== */

    const radios = document.querySelectorAll(
        "input[name='experience']"
    );

    radios.forEach(function (radio) {

        radio.addEventListener("change", function () {

            this.form.submit();

        });

    });



    /* =====================================
       PROFILE BUTTON
    ===================================== */

    const profileButtons =
        document.querySelectorAll(".btn-profile");

    profileButtons.forEach(function (button) {

        button.addEventListener("mouseenter", function () {

            button.style.transform = "translateY(-2px)";

        });

        button.addEventListener("mouseleave", function () {

            button.style.transform = "translateY(0px)";

        });

    });



    /* =====================================
       CAREGIVER CARD HOVER
    ===================================== */

    const cards =
        document.querySelectorAll(".caregiver-card");

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {

            card.style.transition = ".35s";

            card.style.boxShadow =
                "0 18px 35px rgba(0,0,0,.12)";

        });

        card.addEventListener("mouseleave", function () {

            card.style.boxShadow =
                "0 4px 12px rgba(0,0,0,.05)";

        });

    });



    /* =====================================
       SCROLL TO RESULTS
    ===================================== */

    if (window.location.search !== "") {

        const result =
            document.querySelector(".results");

        if (result) {

            result.scrollIntoView({

                behavior: "smooth",

                block: "start"

            });

        }

    }



    /* =====================================
       CONFIRM PROFILE BUTTON
    ===================================== */

    profileButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            button.innerHTML = "Loading...";

        });

    });



    /* =====================================
       REMOVE EMPTY GET PARAMETERS
    ===================================== */

    const form = document.querySelector("form");

    if (form) {

        form.addEventListener("submit", function () {

            const elements =
                form.querySelectorAll("input,select");

            elements.forEach(function (element) {

                if (element.value === "") {

                    element.disabled = true;

                }

            });

        });

    }



    /* =====================================
       BACK TO TOP
    ===================================== */

    const backTop = document.createElement("button");

    backTop.innerHTML = "↑";

    backTop.id = "backTop";

    document.body.appendChild(backTop);

    backTop.style.position = "fixed";
    backTop.style.bottom = "25px";
    backTop.style.right = "25px";
    backTop.style.width = "50px";
    backTop.style.height = "50px";
    backTop.style.borderRadius = "50%";
    backTop.style.border = "none";
    backTop.style.background = "#2563eb";
    backTop.style.color = "#fff";
    backTop.style.fontSize = "20px";
    backTop.style.cursor = "pointer";
    backTop.style.display = "none";
    backTop.style.boxShadow =
        "0 10px 25px rgba(0,0,0,.20)";
    backTop.style.zIndex = "999";

    window.addEventListener("scroll", function () {

        if (window.scrollY > 400) {

            backTop.style.display = "block";

        } else {

            backTop.style.display = "none";

        }

    });

    backTop.addEventListener("click", function () {

        window.scrollTo({

            top: 0,

            behavior: "smooth"

        });

    });

});