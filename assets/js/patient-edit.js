document.addEventListener("DOMContentLoaded", function () {

    /* =========================================================
       PROFILE PHOTO PREVIEW
    ========================================================= */

    const photoInput =
        document.getElementById("profile_photo");

    const photoPreview =
        document.getElementById("profile-preview");

    const photoPlaceholder =
        document.getElementById("profile-placeholder");


    if (photoInput) {

        photoInput.addEventListener("change", function () {

            const file = this.files[0];

            if (!file) {
                return;
            }


            /* Check file type */

            if (
                file.type !== "image/jpeg" &&
                file.type !== "image/png"
            ) {

                alert("Please select a JPG or PNG image.");

                this.value = "";

                return;
            }


            /* Check file size */

            if (file.size > 5 * 1024 * 1024) {

                alert(
                    "Profile photo must be smaller than 5 MB."
                );

                this.value = "";

                return;
            }


            /* Preview image */

            const reader = new FileReader();

            reader.onload = function (event) {

                if (photoPreview) {

                    photoPreview.src =
                        event.target.result;

                    photoPreview.style.display =
                        "block";
                }


                if (photoPlaceholder) {

                    photoPlaceholder.style.display =
                        "none";
                }

            };


            reader.readAsDataURL(file);

        });

    }



    /* =========================================================
       MEDICAL CONDITIONS
    ========================================================= */

    const conditionBox =
        document.getElementById("condition-box");

    const conditionInput =
        document.getElementById("condition-input");

    const conditionHidden =
        document.getElementById("medical_conditions");


    function getConditions() {

        if (!conditionBox) {
            return [];
        }


        const tags =
            conditionBox.querySelectorAll(
                ".condition-tag"
            );


        const conditions = [];


        tags.forEach(function (tag) {

            const button =
                tag.querySelector(
                    ".remove-condition"
                );


            let text =
                tag.textContent;


            if (button) {

                text =
                    text.replace(
                        button.textContent,
                        ""
                    );

            }


            text = text.trim();


            if (text !== "") {

                conditions.push(text);

            }

        });


        return conditions;
    }



    function updateConditionInput() {

        if (!conditionHidden) {
            return;
        }


        const conditions =
            getConditions();


        conditionHidden.value =
            conditions.join(", ");

    }



    function createCondition(condition) {

        condition =
            condition.trim();


        if (condition === "") {
            return;
        }


        const existing =
            getConditions()
                .map(function (item) {

                    return item.toLowerCase();

                });


        if (
            existing.includes(
                condition.toLowerCase()
            )
        ) {

            return;
        }


        const tag =
            document.createElement("span");


        tag.className =
            "condition-tag";


        const text =
            document.createTextNode(
                condition
            );


        tag.appendChild(text);


        const button =
            document.createElement("button");


        button.type = "button";

        button.className =
            "remove-condition";

        button.textContent = "×";


        button.addEventListener(
            "click",
            function () {

                tag.remove();

                updateConditionInput();

            }
        );


        tag.appendChild(button);


        if (conditionBox && conditionInput) {

            conditionBox.insertBefore(
                tag,
                conditionInput
            );

        }


        if (conditionInput) {

            conditionInput.value = "";

        }


        updateConditionInput();

    }



    if (conditionInput) {

        conditionInput.addEventListener(
            "keydown",
            function (event) {

                if (
                    event.key === "Enter" ||
                    event.key === ","
                ) {

                    event.preventDefault();

                    createCondition(
                        conditionInput.value
                    );

                }

            }
        );

    }



    /* =========================================================
       EXISTING CONDITION REMOVE BUTTONS
    ========================================================= */

    document
        .querySelectorAll(".remove-condition")
        .forEach(function (button) {

            button.addEventListener(
                "click",
                function () {

                    const tag =
                        button.closest(
                            ".condition-tag"
                        );


                    if (tag) {

                        tag.remove();

                        updateConditionInput();

                    }

                }
            );

        });



    /* =========================================================
       MEDICAL DOCUMENT UPLOAD
       
       IMPORTANT:
       The input ID is:
       new_medical_documents

       This matches patient-edit.php.
    ========================================================= */

    const documentInput =
        document.getElementById(
            "new_medical_documents"
        );


    const selectedDocuments =
        document.getElementById(
            "selected-new-documents"
        );


    if (documentInput) {

        documentInput.addEventListener(
            "change",
            function () {

                if (!selectedDocuments) {
                    return;
                }


                selectedDocuments.innerHTML =
                    "";


                const files =
                    Array.from(
                        this.files
                    );


                if (files.length === 0) {
                    return;
                }


                files.forEach(function (file) {


                    /* -----------------------------------------
                       Validate file type
                    ----------------------------------------- */

                    const allowedTypes = [
                        "application/pdf",
                        "image/jpeg",
                        "image/png"
                    ];


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        alert(
                            file.name +
                            " is not a valid file.\n\n" +
                            "Only PDF, JPG and PNG files are allowed."
                        );

                        return;
                    }


                    /* -----------------------------------------
                       Validate file size
                    ----------------------------------------- */

                    if (
                        file.size >
                        10 * 1024 * 1024
                    ) {

                        alert(
                            file.name +
                            " is larger than 10 MB."
                        );

                        return;
                    }


                    /* -----------------------------------------
                       Display selected file
                    ----------------------------------------- */

                    const item =
                        document.createElement(
                            "div"
                        );


                    item.className =
                        "selected-new-document";


                    item.textContent =
                        "📄 " +
                        file.name +
                        " (" +
                        formatFileSize(
                            file.size
                        ) +
                        ")";


                    selectedDocuments.appendChild(
                        item
                    );

                });

            }
        );

    }



    /* =========================================================
       FORM SUBMIT
    ========================================================= */

    const form =
        document.getElementById(
            "edit-profile-form"
        );


    const saveButton =
        document.getElementById(
            "save-button"
        );


    if (form) {

        form.addEventListener(
            "submit",
            function (event) {

                /* Update medical conditions */

                updateConditionInput();


                /* ---------------------------------------------
                   Validate medical documents before submitting
                --------------------------------------------- */

                if (documentInput) {

                    const files =
                        Array.from(
                            documentInput.files
                        );


                    for (
                        let i = 0;
                        i < files.length;
                        i++
                    ) {

                        const file =
                            files[i];


                        const allowedTypes = [
                            "application/pdf",
                            "image/jpeg",
                            "image/png"
                        ];


                        if (
                            !allowedTypes.includes(
                                file.type
                            )
                        ) {

                            event.preventDefault();

                            alert(
                                "Invalid medical document:\n\n" +
                                file.name +
                                "\n\n" +
                                "Only PDF, JPG and PNG files are allowed."
                            );

                            return;
                        }


                        if (
                            file.size >
                            10 * 1024 * 1024
                        ) {

                            event.preventDefault();

                            alert(
                                "Medical document is too large:\n\n" +
                                file.name +
                                "\n\n" +
                                "Maximum size is 10 MB."
                            );

                            return;
                        }

                    }

                }


                /* ---------------------------------------------
                   Disable save button
                --------------------------------------------- */

                if (saveButton) {

                    saveButton.disabled =
                        true;

                    saveButton.textContent =
                        "Saving...";

                }

            }
        );

    }



    /* =========================================================
       RESET BUTTON
    ========================================================= */

    const resetButton =
        document.getElementById(
            "reset-button"
        );


    if (resetButton) {

        resetButton.addEventListener(
            "click",
            function () {

                const confirmed =
                    confirm(
                        "Reset all changes?"
                    );


                if (confirmed) {

                    window.location.reload();

                }

            }
        );

    }



    /* =========================================================
       SUCCESS MESSAGE
    ========================================================= */

    const params =
        new URLSearchParams(
            window.location.search
        );


    if (
        params.get("updated") === "1"
    ) {

        const notification =
            document.getElementById(
                "success-notification"
            );


        if (notification) {

            notification.classList.add(
                "show"
            );


            /*
             * Remove the notification after
             * a few seconds.
             */

            setTimeout(
                function () {

                    notification.classList.remove(
                        "show"
                    );

                },
                4000
            );

        }

    }



    /* =========================================================
       DOCUMENT DELETED MESSAGE
    ========================================================= */

    if (
        params.get("document_deleted") === "1"
    ) {

        const notification =
            document.getElementById(
                "success-notification"
            );


        if (notification) {

            const message =
                notification.querySelector(
                    "span:last-child"
                );


            if (message) {

                message.textContent =
                    "Medical document deleted successfully.";

            }


            notification.classList.add(
                "show"
            );


            setTimeout(
                function () {

                    notification.classList.remove(
                        "show"
                    );

                },
                4000
            );

        }

    }

});



/* =========================================================
   FORMAT FILE SIZE
========================================================= */

function formatFileSize(bytes) {

    if (bytes < 1024) {

        return bytes + " B";

    }


    if (
        bytes <
        1024 * 1024
    ) {

        return (
            bytes / 1024
        ).toFixed(1) +
        " KB";

    }


    return (
        bytes /
        (1024 * 1024)
    ).toFixed(1) +
    " MB";

}