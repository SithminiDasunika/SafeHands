/*
|--------------------------------------------------------------------------
| SAFEHANDS - PATIENT PROFILE
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function () {

        setupDocumentUpload();

        setupDocumentHover();

    }
);


/*
|--------------------------------------------------------------------------
| ADD MORE DOCUMENTS
|--------------------------------------------------------------------------
*/

function setupDocumentUpload() {

    var button =
        document.getElementById(
            "uploadDocumentButton"
        );

    var input =
        document.getElementById(
            "additionalDocumentsInput"
        );

    var form =
        document.getElementById(
            "additionalDocumentsForm"
        );

    var selectedContainer =
        document.getElementById(
            "selectedDocuments"
        );


    if (
        !button ||
        !input ||
        !form
    ) {
        return;
    }


    /*
     * Open file picker
     */

    button.addEventListener(
        "click",
        function () {

            input.click();

        }
    );


    /*
     * File selected
     */

    input.addEventListener(
        "change",
        function () {

            if (
                !this.files ||
                this.files.length === 0
            ) {

                return;

            }


            var allowedTypes = [
                "pdf",
                "jpg",
                "jpeg",
                "png"
            ];


            var maxSize =
                10 *
                1024 *
                1024;


            if (
                selectedContainer
            ) {

                selectedContainer.innerHTML =
                    "";

            }


            /*
             * Validate every file
             */

            for (
                var i = 0;
                i < this.files.length;
                i++
            ) {

                var file =
                    this.files[i];


                var extension =
                    file.name
                        .split(".")
                        .pop()
                        .toLowerCase();


                if (
                    allowedTypes.indexOf(
                        extension
                    ) === -1
                ) {

                    alert(
                        "Invalid file: " +
                        file.name +
                        "\n\nAllowed files: PDF, JPG, JPEG and PNG."
                    );

                    this.value =
                        "";

                    if (
                        selectedContainer
                    ) {

                        selectedContainer.innerHTML =
                            "";

                    }

                    return;

                }


                if (
                    file.size >
                    maxSize
                ) {

                    alert(
                        file.name +
                        " is larger than 10 MB."
                    );

                    this.value =
                        "";

                    if (
                        selectedContainer
                    ) {

                        selectedContainer.innerHTML =
                            "";

                    }

                    return;

                }

            }


            /*
             * Show selected files
             */

            if (
                selectedContainer
            ) {

                for (
                    var j = 0;
                    j < this.files.length;
                    j++
                ) {

                    var selectedFile =
                        document.createElement(
                            "div"
                        );


                    selectedFile.className =
                        "selected-file";


                    selectedFile.textContent =
                        "✓ " +
                        this.files[j].name;


                    selectedContainer.appendChild(
                        selectedFile
                    );

                }

            }


            /*
             * Submit automatically
             */

            var confirmed =
                confirm(
                    "Upload " +
                    this.files.length +
                    " document(s)?"
                );


            if (!confirmed) {

                this.value =
                    "";

                if (
                    selectedContainer
                ) {

                    selectedContainer.innerHTML =
                        "";

                }

                return;

            }


            button.disabled =
                true;


            button.innerHTML =
                "<span>...</span> Uploading...";


            form.submit();

        }
    );

}


/*
|--------------------------------------------------------------------------
| DOCUMENT HOVER EFFECT
|--------------------------------------------------------------------------
*/

function setupDocumentHover() {

    var cards =
        document.querySelectorAll(
            "[data-document-card]"
        );


    for (
        var i = 0;
        i < cards.length;
        i++
    ) {

        cards[i].addEventListener(
            "mouseenter",
            function () {

                this.classList.add(
                    "document-hover"
                );

            }
        );


        cards[i].addEventListener(
            "mouseleave",
            function () {

                this.classList.remove(
                    "document-hover"
                );

            }
        );

    }

}


/*
|--------------------------------------------------------------------------
| EDIT PATIENT
|--------------------------------------------------------------------------
*/

function editPatient(
    patientId
) {

    if (!patientId) {
        return;
    }


    window.location.href =
        "patient-edit.php?patient_id=" +
        encodeURIComponent(
            patientId
        );

}


/*
|--------------------------------------------------------------------------
| MEDICAL HISTORY
|--------------------------------------------------------------------------
*/

function viewMedicalHistory(
    patientId
) {

    if (!patientId) {
        return;
    }


    window.location.href =
        "medical-history.php?patient_id=" +
        encodeURIComponent(
            patientId
        );

}


/*
|--------------------------------------------------------------------------
| CARE HISTORY
|--------------------------------------------------------------------------
*/

function viewCareHistory(
    patientId
) {

    if (!patientId) {
        return;
    }


    window.location.href =
        "dailycare-report.php?patient_id=" +
        encodeURIComponent(
            patientId
        );

}


/*
|--------------------------------------------------------------------------
| BOOK CAREGIVER
|--------------------------------------------------------------------------
*/

function bookCaregiver(
    patientId
) {

    if (!patientId) {
        return;
    }


    window.location.href =
        "booking.php?patient_id=" +
        encodeURIComponent(
            patientId
        );

}


/*
|--------------------------------------------------------------------------
| DELETE PATIENT
|--------------------------------------------------------------------------
*/

function deletePatient(
    patientId
) {

    if (!patientId) {
        return;
    }


    var confirmed =
        confirm(
            "Are you sure you want to delete this patient?\n\n" +
            "This action cannot be undone."
        );


    if (!confirmed) {
        return;
    }


    window.location.href =
        "delete-patient.php?patient_id=" +
        encodeURIComponent(
            patientId
        );

}