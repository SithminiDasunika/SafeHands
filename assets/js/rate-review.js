/* =========================================================
   SAFEHANDS - RATE & REVIEW
   VANILLA JAVASCRIPT
   NO LIBRARIES
========================================================= */

document.addEventListener(
    "DOMContentLoaded",
    function () {


        /* =================================================
           OVERALL RATING
        ================================================= */

        const overallStars =
            document.querySelectorAll(
                ".rating-star"
            );

        const overallInput =
            document.getElementById(
                "overallRatingInput"
            );

        const descriptor =
            document.getElementById(
                "ratingDescriptor"
            );


        const descriptors = [

            "Poor",

            "Fair",

            "Good",

            "Very Good",

            "Excellent"

        ];


        overallStars.forEach(
            function (star) {

                star.addEventListener(
                    "click",
                    function () {

                        const value =
                            parseInt(
                                this.dataset.value
                            );


                        overallInput.value =
                            value;


                        overallStars.forEach(
                            function (
                                currentStar
                            ) {

                                const starValue =
                                    parseInt(
                                        currentStar
                                            .dataset
                                            .value
                                    );


                                if (
                                    starValue <= value
                                ) {

                                    currentStar.classList.add(
                                        "active"
                                    );

                                } else {

                                    currentStar.classList.remove(
                                        "active"
                                    );

                                }

                            }
                        );


                        descriptor.textContent =
                            descriptors[value - 1];


                        descriptor.classList.add(
                            "selected"
                        );

                    }
                );

            }
        );



        /* =================================================
           MINI RATINGS
        ================================================= */

        const miniRatingGroups =
            document.querySelectorAll(
                ".mini-rating"
            );


        miniRatingGroups.forEach(
            function (group) {


                const stars =
                    group.querySelectorAll(
                        "button"
                    );


                const category =
                    group.dataset.category;


                const hiddenInput =
                    group.parentElement.querySelector(
                        'input[type="hidden"]'
                    );


                stars.forEach(
                    function (star) {

                        star.addEventListener(
                            "click",
                            function () {

                                const value =
                                    parseInt(
                                        this.dataset.value
                                    );


                                if (
                                    hiddenInput
                                ) {

                                    hiddenInput.value =
                                        value;

                                }


                                stars.forEach(
                                    function (
                                        currentStar
                                    ) {

                                        const starValue =
                                            parseInt(
                                                currentStar
                                                    .dataset
                                                    .value
                                            );


                                        if (
                                            starValue <= value
                                        ) {

                                            currentStar.classList.add(
                                                "active"
                                            );

                                        } else {

                                            currentStar.classList.remove(
                                                "active"
                                            );

                                        }

                                    }
                                );

                            }
                        );

                    }
                );

            }
        );



        /* =================================================
           WRITTEN REVIEW COUNTER
        ================================================= */

        const review =
            document.getElementById(
                "review"
            );


        const reviewCounter =
            document.getElementById(
                "reviewCounter"
            );


        if (
            review &&
            reviewCounter
        ) {

            review.addEventListener(
                "input",
                function () {

                    const length =
                        review.value.length;


                    if (length === 0) {

                        reviewCounter.textContent =
                            "Minimum 20 characters";

                        reviewCounter.style.color =
                            "";

                    } else {

                        reviewCounter.textContent =
                            length +
                            " / 1000 characters";


                        if (length < 20) {

                            reviewCounter.style.color =
                                "#ba1a1a";

                        } else {

                            reviewCounter.style.color =
                                "#025747";

                        }

                    }

                }
            );

        }



        /* =================================================
           FORM VALIDATION
        ================================================= */

        const form =
            document.getElementById(
                "feedbackForm"
            );


        if (form) {

            form.addEventListener(
                "submit",
                function (event) {


                    const overall =
                        parseInt(
                            overallInput.value
                        );


                    if (
                        overall < 1 ||
                        overall > 5
                    ) {

                        event.preventDefault();

                        alert(
                            "Please select your overall rating."
                        );

                        return;

                    }


                    const miniInputs =
                        form.querySelectorAll(
                            '.mini-rating + input[type="hidden"]'
                        );


                    for (
                        let i = 0;
                        i < miniInputs.length;
                        i++
                    ) {

                        if (
                            parseInt(
                                miniInputs[i].value
                            ) < 1
                        ) {

                            event.preventDefault();

                            alert(
                                "Please complete all detailed ratings."
                            );

                            return;

                        }

                    }


                    if (
                        !review ||
                        review.value.trim().length < 20
                    ) {

                        event.preventDefault();

                        alert(
                            "Please write at least 20 characters in your review."
                        );

                        review.focus();

                        return;

                    }


                    const recommend =
                        form.querySelector(
                            'input[name="recommend"]:checked'
                        );


                    if (!recommend) {

                        event.preventDefault();

                        alert(
                            "Please tell us whether you recommend this caregiver."
                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | If everything is correct,
                    | PHP will receive the form.
                    |--------------------------------------------------------------------------
                    */

                }
            );

        }

    }
);