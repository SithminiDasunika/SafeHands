document.addEventListener("DOMContentLoaded", function () {

    const form =
        document.getElementById("professionalForm");

    const photoInput =
        document.getElementById("profilePhoto");

    const uploadBox =
        document.getElementById("uploadBox");

    const previewWrapper =
        document.getElementById("photoPreviewWrapper");

    const uploadTitle =
        document.getElementById("uploadTitle");

    const biography =
        document.getElementById("biography");

    const bioCounter =
        document.getElementById("bioCounter");


    // =====================================================
    // BIOGRAPHY CHARACTER COUNTER
    // =====================================================

    function updateBioCounter() {

        if (!biography || !bioCounter) {
            return;
        }

        bioCounter.textContent =
            biography.value.length +
            " / 1000";

    }


    if (biography) {

        biography.addEventListener(
            "input",
            updateBioCounter
        );

        updateBioCounter();

    }


    // =====================================================
    // PROFILE PHOTO PREVIEW
    // =====================================================

    function handlePhoto(file) {

        if (!file) {
            return;
        }


        const allowedTypes = [

            "image/jpeg",

            "image/png",

            "image/gif"

        ];


        if (!allowedTypes.includes(file.type)) {

            alert(
                "Please select a JPG, PNG or GIF image."
            );

            photoInput.value = "";

            return;

        }


        if (file.size > 2 * 1024 * 1024) {

            alert(
                "Profile photo must be 2MB or smaller."
            );

            photoInput.value = "";

            return;

        }


        const reader =
            new FileReader();


        reader.onload =
            function (event) {

                previewWrapper.innerHTML = "";


                const image =
                    document.createElement("img");


                image.src =
                    event.target.result;


                image.alt =
                    "Profile photo preview";


                image.className =
                    "profile-preview";


                previewWrapper.appendChild(
                    image
                );


                uploadTitle.textContent =
                    file.name;

            };


        reader.readAsDataURL(file);

    }


    if (photoInput) {

        photoInput.addEventListener(
            "change",
            function () {

                handlePhoto(
                    this.files[0]
                );

            }
        );

    }


    // =====================================================
    // DRAG AND DROP
    // =====================================================

    if (uploadBox) {

        uploadBox.addEventListener(
            "dragover",
            function (event) {

                event.preventDefault();

                uploadBox.classList.add(
                    "dragging"
                );

            }
        );


        uploadBox.addEventListener(
            "dragleave",
            function () {

                uploadBox.classList.remove(
                    "dragging"
                );

            }
        );


        uploadBox.addEventListener(
            "drop",
            function (event) {

                event.preventDefault();

                uploadBox.classList.remove(
                    "dragging"
                );


                const files =
                    event.dataTransfer.files;


                if (files.length > 0) {

                    const dataTransfer =
                        new DataTransfer();


                    dataTransfer.items.add(
                        files[0]
                    );


                    photoInput.files =
                        dataTransfer.files;


                    handlePhoto(
                        files[0]
                    );

                }

            }
        );

    }


    // =====================================================
    // FORM SUBMISSION
    // =====================================================

    if (form) {

        form.addEventListener(
            "submit",
            function () {

                const button =
                    document.getElementById(
                        "continueButton"
                    );


                if (
                    button &&
                    form.checkValidity()
                ) {

                    button.disabled = true;

                    button.innerHTML =
                        "Saving... <span>→</span>";

                }

                /*
                IMPORTANT:

                We DO NOT call:

                event.preventDefault();

                The form must submit normally
                to caregiver-professional.php.
                */

            }
        );

    }

});