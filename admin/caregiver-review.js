document.addEventListener(
    "DOMContentLoaded",
    function () {

        /* =====================================================
           SIDEBAR
        ====================================================== */

        const sidebar =
            document.getElementById(
                "sidebar"
            );

        const menuButton =
            document.getElementById(
                "menuButton"
            );

        const sidebarOverlay =
            document.getElementById(
                "sidebarOverlay"
            );


        function openSidebar() {

            if (!sidebar) {
                return;
            }

            sidebar.classList.add(
                "open"
            );

            if (sidebarOverlay) {

                sidebarOverlay.classList.add(
                    "show"
                );
            }
        }


        function closeSidebar() {

            if (!sidebar) {
                return;
            }

            sidebar.classList.remove(
                "open"
            );

            if (sidebarOverlay) {

                sidebarOverlay.classList.remove(
                    "show"
                );
            }
        }


        if (menuButton) {

            menuButton.addEventListener(
                "click",
                function () {

                    if (
                        sidebar.classList.contains(
                            "open"
                        )
                    ) {

                        closeSidebar();

                    } else {

                        openSidebar();
                    }
                }
            );
        }


        if (sidebarOverlay) {

            sidebarOverlay.addEventListener(
                "click",
                closeSidebar
            );
        }


        /* =====================================================
           MODAL FUNCTIONS
        ====================================================== */

        function openModal(modal) {

            if (!modal) {
                return;
            }

            modal.classList.add(
                "show"
            );

            document.body.classList.add(
                "modal-open"
            );
        }


        function closeModal(modal) {

            if (!modal) {
                return;
            }

            modal.classList.remove(
                "show"
            );

            document.body.classList.remove(
                "modal-open"
            );
        }


        /* =====================================================
           APPROVE MODAL
        ====================================================== */

        const approveModal =
            document.getElementById(
                "approveModal"
            );

        const openApproveModal =
            document.getElementById(
                "openApproveModal"
            );


        if (
            approveModal &&
            openApproveModal
        ) {

            openApproveModal.addEventListener(
                "click",
                function () {

                    openModal(
                        approveModal
                    );
                }
            );
        }


        /* =====================================================
           REJECT MODAL
        ====================================================== */

        const rejectModal =
            document.getElementById(
                "rejectModal"
            );

        const openRejectModal =
            document.getElementById(
                "openRejectModal"
            );


        if (
            rejectModal &&
            openRejectModal
        ) {

            openRejectModal.addEventListener(
                "click",
                function () {

                    openModal(
                        rejectModal
                    );

                    const textarea =
                        document.getElementById(
                            "rejectionReason"
                        );


                    if (textarea) {

                        setTimeout(
                            function () {

                                textarea.focus();

                            },
                            150
                        );
                    }
                }
            );
        }


        /* =====================================================
           CLOSE BUTTONS
        ====================================================== */

        const closeButtons =
            document.querySelectorAll(
                "[data-close-modal]"
            );


        closeButtons.forEach(
            function (button) {

                button.addEventListener(
                    "click",
                    function () {

                        const modalId =
                            button.getAttribute(
                                "data-close-modal"
                            );


                        const modal =
                            document.getElementById(
                                modalId
                            );


                        closeModal(
                            modal
                        );
                    }
                );
            }
        );


        /* =====================================================
           CLICK OUTSIDE MODAL
        ====================================================== */

        document
            .querySelectorAll(
                ".modal-overlay"
            )
            .forEach(
                function (modal) {

                    modal.addEventListener(
                        "click",
                        function (event) {

                            if (
                                event.target
                                === modal
                            ) {

                                closeModal(
                                    modal
                                );
                            }
                        }
                    );
                }
            );


        /* =====================================================
           ESCAPE KEY
        ====================================================== */

        document.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key
                    !== "Escape"
                ) {

                    return;
                }


                document
                    .querySelectorAll(
                        ".modal-overlay.show"
                    )
                    .forEach(
                        function (modal) {

                            closeModal(
                                modal
                            );
                        }
                    );
            }
        );


        /* =====================================================
           REJECTION REASON COUNTER
        ====================================================== */

        const rejectionReason =
            document.getElementById(
                "rejectionReason"
            );

        const reasonCount =
            document.getElementById(
                "reasonCount"
            );

        const reasonError =
            document.getElementById(
                "reasonError"
            );

        const rejectForm =
            document.getElementById(
                "rejectForm"
            );


        function updateReasonCounter() {

            if (!rejectionReason) {
                return;
            }


            const length =
                rejectionReason
                    .value
                    .trim()
                    .length;


            if (reasonCount) {

                reasonCount.textContent =
                    rejectionReason
                        .value
                        .length;
            }


            if (reasonError) {

                if (
                    length > 0 &&
                    length < 10
                ) {

                    reasonError.classList.add(
                        "invalid"
                    );

                    reasonError.textContent =
                        "Please enter at least 10 characters.";

                } else {

                    reasonError.classList.remove(
                        "invalid"
                    );

                    reasonError.textContent =
                        "Minimum 10 characters required.";
                }
            }
        }


        if (rejectionReason) {

            rejectionReason.addEventListener(
                "input",
                updateReasonCounter
            );

            updateReasonCounter();
        }


        /* =====================================================
           REJECT VALIDATION
        ====================================================== */

        if (
            rejectForm &&
            rejectionReason
        ) {

            rejectForm.addEventListener(
                "submit",
                function (event) {

                    const reason =
                        rejectionReason
                            .value
                            .trim();


                    if (
                        reason.length < 10
                    ) {

                        event.preventDefault();


                        if (reasonError) {

                            reasonError.classList.add(
                                "invalid"
                            );

                            reasonError.textContent =
                                "Please provide a clear reason of at least 10 characters.";
                        }


                        rejectionReason.focus();
                    }
                }
            );
        }


        /* =====================================================
           RESPONSIVE RESET
        ====================================================== */

        window.addEventListener(
            "resize",
            function () {

                if (
                    window.innerWidth > 820
                ) {

                    closeSidebar();
                }
            }
        );

    }
);