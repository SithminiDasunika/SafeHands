/* =========================================================
   SAFEHANDS NOTIFICATIONS
   Pure JavaScript - No Library
========================================================= */

document.addEventListener("DOMContentLoaded", function () {


    const filterTabs =
        document.querySelectorAll(".filter-tab");

    const notificationCards =
        document.querySelectorAll(".notification-card");

    const markAllReadButton =
        document.getElementById("markAllRead");

    const unreadCountElement =
        document.querySelector(".unread-count");

    const emptyState =
        document.getElementById("emptyState");


    /* =====================================================
       FILTER NOTIFICATIONS
    ====================================================== */

    filterTabs.forEach(function (tab) {

        tab.addEventListener("click", function () {


            /* Remove active state */

            filterTabs.forEach(function (item) {

                item.classList.remove("active");

            });


            /* Add active state */

            tab.classList.add("active");


            const selectedType =
                tab.getAttribute("data-filter");


            let visibleCount = 0;


            notificationCards.forEach(function (card) {

                const cardType =
                    card.getAttribute("data-type");


                if (
                    selectedType === "all" ||
                    cardType === selectedType
                ) {

                    card.style.display = "flex";

                    visibleCount++;

                } else {

                    card.style.display = "none";

                }

            });


            /* Show empty state */

            if (visibleCount === 0) {

                emptyState.classList.remove("hidden");

            } else {

                emptyState.classList.add("hidden");

            }


            /* Hide empty notification groups */

            document
                .querySelectorAll(".notification-group")
                .forEach(function (group) {

                    const visibleCards =
                        group.querySelectorAll(
                            ".notification-card:not([style*='display: none'])"
                        );

                    if (visibleCards.length === 0) {

                        group.style.display = "none";

                    } else {

                        group.style.display = "block";

                    }

                });

        });

    });



    /* =====================================================
       MARK ALL AS READ
    ====================================================== */

    if (markAllReadButton) {

        markAllReadButton.addEventListener(
            "click",
            function () {


                notificationCards.forEach(
                    function (card) {


                        card.classList.remove("unread");

                        card.classList.add("read");


                        const dot =
                            card.querySelector(".unread-dot");


                        if (dot) {

                            dot.remove();

                        }


                    }
                );


                updateUnreadCount();


                markAllReadButton.innerHTML =
                    "<span>✓</span> All notifications read";


                markAllReadButton.disabled = true;


                setTimeout(function () {

                    markAllReadButton.disabled = false;

                }, 1500);

            }
        );

    }



    /* =====================================================
       CLICK NOTIFICATION
    ====================================================== */

    notificationCards.forEach(function (card) {

        card.addEventListener(
            "click",
            function (event) {


                /*
                 * If user clicked the action link,
                 * allow the link to work normally.
                 */

                if (
                    event.target.closest(
                        ".notification-action"
                    )
                ) {

                    return;

                }


                /* Mark notification as read */

                if (
                    card.classList.contains("unread")
                ) {

                    card.classList.remove("unread");

                    card.classList.add("read");


                    const dot =
                        card.querySelector(".unread-dot");


                    if (dot) {

                        dot.remove();

                    }


                    updateUnreadCount();

                }

            }
        );

    });



    /* =====================================================
       UPDATE UNREAD COUNT
    ====================================================== */

    function updateUnreadCount() {


        const unreadCards =
            document.querySelectorAll(
                ".notification-card.unread"
            );


        const count =
            unreadCards.length;


        if (unreadCountElement) {

            unreadCountElement.textContent =
                count + " Unread";

        }


        /*
         * Update notification bell dot
         */

        const notificationButton =
            document.querySelector(
                ".notification-button"
            );


        const existingDot =
            notificationButton
                ? notificationButton.querySelector(
                    ".notification-dot"
                )
                : null;


        if (count === 0) {

            if (existingDot) {

                existingDot.remove();

            }

        } else {

            if (!existingDot && notificationButton) {

                const dot =
                    document.createElement("span");

                dot.className =
                    "notification-dot";

                notificationButton.appendChild(dot);

            }

        }

    }



    /* =====================================================
       INITIAL COUNT
    ====================================================== */

    updateUnreadCount();


});