document.addEventListener(
    "DOMContentLoaded",
    function () {


        /*
        |--------------------------------------------------------------------------
        | CURRENT DATE
        |--------------------------------------------------------------------------
        */

        const currentDateElement =
            document.getElementById(
                "currentDate"
            );


        const today =
            new Date();


        if (currentDateElement) {

            const dateOptions = {

                weekday:
                    "long",

                year:
                    "numeric",

                month:
                    "short",

                day:
                    "numeric"

            };


            currentDateElement.textContent =
                today.toLocaleDateString(
                    "en-US",
                    dateOptions
                );

        }



        /*
        |--------------------------------------------------------------------------
        | DYNAMIC GREETING
        |--------------------------------------------------------------------------
        */

        const greetingTime =
            document.getElementById(
                "greetingTime"
            );


        if (greetingTime) {

            const hour =
                today.getHours();


            if (
                hour >= 5 &&
                hour < 12
            ) {

                greetingTime.textContent =
                    "Morning";

            }


            else if (
                hour >= 12 &&
                hour < 17
            ) {

                greetingTime.textContent =
                    "Afternoon";

            }


            else {

                greetingTime.textContent =
                    "Evening";

            }

        }



        /*
        |--------------------------------------------------------------------------
        | PROFILE DROPDOWN
        |--------------------------------------------------------------------------
        */

        const profileButton =
            document.getElementById(
                "profileButton"
            );


        const profileDropdown =
            document.getElementById(
                "profileDropdown"
            );


        if (
            profileButton &&
            profileDropdown
        ) {


            profileButton.addEventListener(
                "click",
                function (event) {

                    event.stopPropagation();


                    profileDropdown.classList.toggle(
                        "show"
                    );

                }
            );


            document.addEventListener(
                "click",
                function (event) {


                    if (
                        !profileDropdown.contains(
                            event.target
                        )
                    ) {

                        profileDropdown.classList.remove(
                            "show"
                        );

                    }


                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | MOBILE MENU
        |--------------------------------------------------------------------------
        */

        const mobileMenuButton =
            document.getElementById(
                "mobileMenuButton"
            );


        const mobileNav =
            document.getElementById(
                "mobileNav"
            );


        if (
            mobileMenuButton &&
            mobileNav
        ) {


            mobileMenuButton.addEventListener(
                "click",
                function () {


                    mobileNav.classList.toggle(
                        "show"
                    );


                    const icon =
                        mobileMenuButton.querySelector(
                            ".material-symbols-outlined"
                        );


                    if (icon) {


                        if (
                            mobileNav.classList.contains(
                                "show"
                            )
                        ) {

                            icon.textContent =
                                "close";

                        }


                        else {

                            icon.textContent =
                                "menu";

                        }


                    }


                }
            );

        }



        /*
        |--------------------------------------------------------------------------
        | BUTTON PRESS EFFECT
        |--------------------------------------------------------------------------
        */

        const buttons =
            document.querySelectorAll(
                "button"
            );


        buttons.forEach(
            function (button) {


                button.addEventListener(
                    "mousedown",
                    function () {

                        this.style.transform =
                            "scale(0.98)";

                    }
                );


                button.addEventListener(
                    "mouseup",
                    function () {

                        this.style.transform =
                            "";

                    }
                );


                button.addEventListener(
                    "mouseleave",
                    function () {

                        this.style.transform =
                            "";

                    }
                );


            }
        );

    }
);