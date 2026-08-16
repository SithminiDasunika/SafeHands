/*
|--------------------------------------------------------------------------
| SafeHands - My Bookings
|--------------------------------------------------------------------------
| Pure Vanilla JavaScript
|--------------------------------------------------------------------------
*/

document.addEventListener("DOMContentLoaded", function () {

    const tabs = document.querySelectorAll(".filter-tab");
    const searchInput = document.getElementById("bookingSearch");

    const bookingCards = document.querySelectorAll(".booking-card");
    const bookingSections = document.querySelectorAll(".booking-section");

    const emptyState = document.getElementById("emptyState");
    const clearFilters = document.getElementById("clearFilters");

    const filterButton = document.getElementById("filterButton");
    const advancedFilter = document.getElementById("advancedFilter");

    const patientFilter = document.getElementById("patientFilter");
    const statusFilter = document.getElementById("statusFilter");


    let currentStatus = "all";


    /* =====================================================
       FILTER TABS
    ===================================================== */

    tabs.forEach(function (tab) {

        tab.addEventListener("click", function () {

            tabs.forEach(function (item) {
                item.classList.remove("active");
            });

            tab.classList.add("active");

            currentStatus = tab.dataset.filter;

            if (statusFilter) {
                statusFilter.value = currentStatus;
            }

            applyFilters();

        });

    });


    /* =====================================================
       SEARCH
    ===================================================== */

    if (searchInput) {

        searchInput.addEventListener("input", function () {

            applyFilters();

        });

    }


    /* =====================================================
       ADVANCED FILTER BUTTON
    ===================================================== */

    if (filterButton && advancedFilter) {

        filterButton.addEventListener("click", function () {

            advancedFilter.classList.toggle("show");

        });

    }


    /* =====================================================
       PATIENT FILTER
    ===================================================== */

    if (patientFilter) {

        patientFilter.addEventListener("change", function () {

            applyFilters();

        });

    }


    /* =====================================================
       STATUS FILTER
    ===================================================== */

    if (statusFilter) {

        statusFilter.addEventListener("change", function () {

            currentStatus = statusFilter.value;

            tabs.forEach(function (tab) {

                tab.classList.toggle(
                    "active",
                    tab.dataset.filter === currentStatus
                );

            });

            applyFilters();

        });

    }


    /* =====================================================
       APPLY ALL FILTERS
    ===================================================== */

    function applyFilters() {

        const searchTerm =
            searchInput
                ? searchInput.value.toLowerCase().trim()
                : "";

        const selectedPatient =
            patientFilter
                ? patientFilter.value
                : "all";


        let visibleCount = 0;


        bookingCards.forEach(function (card) {

            const cardStatus =
                card.dataset.status || "";

            const patient =
                (card.dataset.patient || "").toLowerCase();

            const caregiver =
                (card.dataset.caregiver || "").toLowerCase();


            /* Status */

            const statusMatches =
                currentStatus === "all" ||
                cardStatus === currentStatus;


            /* Patient */

            const patientMatches =
                selectedPatient === "all" ||
                card.dataset.patient === selectedPatient;


            /* Search */

            const searchMatches =
                searchTerm === "" ||
                patient.includes(searchTerm) ||
                caregiver.includes(searchTerm) ||
                card.textContent.toLowerCase().includes(searchTerm);


            const shouldShow =
                statusMatches &&
                patientMatches &&
                searchMatches;


            if (shouldShow) {

                card.classList.remove("hidden");

                visibleCount++;

            } else {

                card.classList.add("hidden");

            }

        });


        /* =================================================
           HIDE EMPTY SECTIONS
        ================================================= */

        bookingSections.forEach(function (section) {

            const cards =
                section.querySelectorAll(".booking-card");

            let sectionVisible = false;


            cards.forEach(function (card) {

                if (!card.classList.contains("hidden")) {
                    sectionVisible = true;
                }

            });


            if (sectionVisible) {

                section.style.display = "";

            } else {

                section.style.display = "none";

            }

        });


        /* =================================================
           EMPTY STATE
        ================================================= */

        if (visibleCount === 0) {

            emptyState.hidden = false;

        } else {

            emptyState.hidden = true;

        }

    }


    /* =====================================================
       CLEAR FILTERS
    ===================================================== */

    if (clearFilters) {

        clearFilters.addEventListener("click", function () {

            currentStatus = "all";


            tabs.forEach(function (tab) {

                tab.classList.toggle(
                    "active",
                    tab.dataset.filter === "all"
                );

            });


            if (searchInput) {
                searchInput.value = "";
            }


            if (patientFilter) {
                patientFilter.value = "all";
            }


            if (statusFilter) {
                statusFilter.value = "all";
            }


            applyFilters();

        });

    }


    /* =====================================================
       CANCEL BOOKING
    ===================================================== */

    const cancelButtons =
        document.querySelectorAll(".cancel-booking");


    cancelButtons.forEach(function (button) {

        button.addEventListener("click", function () {

            const bookingId =
                button.dataset.booking;


            const confirmed =
                window.confirm(
                    "Are you sure you want to cancel booking #" +
                    bookingId +
                    "?"
                );


            if (!confirmed) {
                return;
            }


            /*
             * UI demonstration only.
             *
             * Later replace this with a PHP request:
             *
             * fetch("cancel-booking.php", {
             *     method: "POST",
             *     body: ...
             * });
             */


            alert(
                "Booking #" +
                bookingId +
                " cancellation request submitted."
            );

        });

    });


    /* =====================================================
       INITIAL FILTER
    ===================================================== */

    applyFilters();

});