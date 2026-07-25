document.addEventListener(
    "DOMContentLoaded",
    function () {


        /*
        |--------------------------------------------------------------------------
        | GET ELEMENTS
        |--------------------------------------------------------------------------
        */

        const supportModal =
            document.getElementById(
                "supportModal"
            );


        const contactSupportButton =
            document.getElementById(
                "contactSupportButton"
            );


        const supportTextButton =
            document.getElementById(
                "supportTextButton"
            );


        const footerHelpLink =
            document.getElementById(
                "footerHelpLink"
            );


        const closeSupportModal =
            document.getElementById(
                "closeSupportModal"
            );


        const modalDoneButton =
            document.getElementById(
                "modalDoneButton"
            );



        /*
        |--------------------------------------------------------------------------
        | OPEN SUPPORT MODAL
        |--------------------------------------------------------------------------
        */

        function openModal() {


            if (!supportModal) {

                return;

            }


            supportModal.classList.add(
                "show"
            );


            supportModal.setAttribute(
                "aria-hidden",
                "false"
            );


            document.body.classList.add(
                "modal-open"
            );


        }



        /*
        |--------------------------------------------------------------------------
        | CLOSE SUPPORT MODAL
        |--------------------------------------------------------------------------
        */

        function closeModal() {


            if (!supportModal) {

                return;

            }


            supportModal.classList.remove(
                "show"
            );


            supportModal.setAttribute(
                "aria-hidden",
                "true"
            );


            document.body.classList.remove(
                "modal-open"
            );


        }



        /*
        |--------------------------------------------------------------------------
        | CONTACT SUPPORT BUTTON
        |--------------------------------------------------------------------------
        */

        if (contactSupportButton) {


            contactSupportButton.addEventListener(
                "click",
                function () {

                    openModal();

                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | SUPPORT TEXT LINK
        |--------------------------------------------------------------------------
        */

        if (supportTextButton) {


            supportTextButton.addEventListener(
                "click",
                function () {

                    openModal();

                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | FOOTER HELP LINK
        |--------------------------------------------------------------------------
        */

        if (footerHelpLink) {


            footerHelpLink.addEventListener(
                "click",
                function (event) {


                    event.preventDefault();


                    openModal();


                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | CLOSE BUTTON
        |--------------------------------------------------------------------------
        */

        if (closeSupportModal) {


            closeSupportModal.addEventListener(
                "click",
                function () {

                    closeModal();

                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | DONE BUTTON
        |--------------------------------------------------------------------------
        */

        if (modalDoneButton) {


            modalDoneButton.addEventListener(
                "click",
                function () {

                    closeModal();

                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | CLICK OUTSIDE MODAL
        |--------------------------------------------------------------------------
        */

        if (supportModal) {


            supportModal.addEventListener(
                "click",
                function (event) {


                    if (
                        event.target ===
                        supportModal
                    ) {

                        closeModal();

                    }


                }
            );


        }



        /*
        |--------------------------------------------------------------------------
        | ESCAPE KEY CLOSE
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            "keydown",
            function (event) {


                if (
                    event.key ===
                    "Escape"
                ) {

                    closeModal();

                }


            }
        );


    }
);