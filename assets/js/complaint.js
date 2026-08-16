/* =========================================================
   SAFEHANDS - COMPLAINT PAGE
   PURE VANILLA JAVASCRIPT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {


    /* =====================================================
       DESCRIPTION CHARACTER COUNTER
    ====================================================== */

    const description =
        document.getElementById("description");

    const characterCount =
        document.getElementById("characterCount");


    if (description && characterCount) {

        function updateCharacterCount() {

            const length =
                description.value.length;

            characterCount.textContent =
                length + " / 1000";


            if (length >= 900) {

                characterCount.style.color =
                    "#ba1a1a";

            } else {

                characterCount.style.color =
                    "";

            }

        }


        description.addEventListener(
            "input",
            updateCharacterCount
        );


        updateCharacterCount();

    }



    /* =====================================================
       FILE UPLOAD
    ====================================================== */

    const fileInput =
        document.getElementById("attachments");

    const fileList =
        document.getElementById("fileList");


    if (fileInput && fileList) {

        fileInput.addEventListener(
            "change",
            function () {

                fileList.innerHTML = "";


                const files =
                    Array.from(fileInput.files);


                files.forEach(function (file) {


                    const fileItem =
                        document.createElement("div");

                    fileItem.className =
                        "file-item";


                    const fileName =
                        document.createElement("span");

                    fileName.className =
                        "file-item-name";

                    fileName.textContent =
                        file.name;


                    const fileSize =
                        document.createElement("small");

                    fileSize.textContent =
                        formatFileSize(file.size);


                    const removeButton =
                        document.createElement("button");

                    removeButton.type =
                        "button";

                    removeButton.className =
                        "file-remove";

                    removeButton.textContent =
                        "×";


                    removeButton.addEventListener(
                        "click",
                        function () {

                            fileItem.remove();

                        }
                    );


                    fileItem.appendChild(fileName);

                    fileItem.appendChild(fileSize);

                    fileItem.appendChild(removeButton);


                    fileList.appendChild(fileItem);

                });

            }
        );

    }



    /* =====================================================
       FORM VALIDATION
    ====================================================== */

    const form =
        document.getElementById("complaintForm");


    if (form) {

        form.addEventListener(
            "submit",
            function (event) {

                const subject =
                    document.getElementById("subject");

                const description =
                    document.getElementById("description");


                if (
                    !subject.value.trim() ||
                    !description.value.trim()
                ) {

                    event.preventDefault();

                    alert(
                        "Please complete the required fields."
                    );

                    return;

                }


                if (description.value.length > 1000) {

                    event.preventDefault();

                    alert(
                        "Description cannot exceed 1000 characters."
                    );

                    return;

                }


                const urgent =
                    document.querySelector(
                        'input[name="urgency"][value="urgent"]:checked'
                    );


                if (urgent) {

                    const confirmed =
                        confirm(
                            "You selected Urgent. Are you sure this complaint requires urgent attention?"
                        );


                    if (!confirmed) {

                        event.preventDefault();

                        return;

                    }

                }

            }
        );

    }



    /* =====================================================
       FILE SIZE + TYPE VALIDATION
    ====================================================== */

    if (fileInput) {

        fileInput.addEventListener(
            "change",
            function () {

                const allowedTypes = [
                    "image/png",
                    "image/jpeg",
                    "image/svg+xml",
                    "application/pdf"
                ];


                const maxSize =
                    10 * 1024 * 1024;


                for (
                    let i = 0;
                    i < fileInput.files.length;
                    i++
                ) {

                    const file =
                        fileInput.files[i];


                    if (file.size > maxSize) {

                        alert(
                            file.name +
                            " is larger than 10MB."
                        );

                        fileInput.value = "";

                        fileList.innerHTML = "";

                        return;

                    }


                    if (
                        !allowedTypes.includes(
                            file.type
                        )
                    ) {

                        alert(
                            file.name +
                            " is not a supported file type."
                        );

                        fileInput.value = "";

                        fileList.innerHTML = "";

                        return;

                    }

                }

            }
        );

    }



    /* =====================================================
       HELPER
    ====================================================== */

    function formatFileSize(bytes) {

        if (bytes < 1024) {

            return bytes + " B";

        }


        if (bytes < 1024 * 1024) {

            return (
                (bytes / 1024).toFixed(1) +
                " KB"
            );

        }


        return (
            (bytes / (1024 * 1024)).toFixed(1) +
            " MB"
        );

    }

});