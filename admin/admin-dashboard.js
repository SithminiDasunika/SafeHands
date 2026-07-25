document.addEventListener(
    "DOMContentLoaded",
    function () {

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