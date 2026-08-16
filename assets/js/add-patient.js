/*
|--------------------------------------------------------------------------
| SafeHands - Add Patient
|--------------------------------------------------------------------------
| Handles:
| 1. Multi-step navigation
| 2. Step validation
| 3. Progress tracker
| 4. Medical condition selection
| 5. Profile photo validation
| 6. Medical document validation
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| CURRENT STEP
|--------------------------------------------------------------------------
*/

var currentStep = 1;


/*
|--------------------------------------------------------------------------
| GO TO STEP
|--------------------------------------------------------------------------
*/

function goToStep(stepNumber) {

    stepNumber = parseInt(stepNumber);


    /*
    | Only allow steps 1, 2 and 3
    */

    if (
        stepNumber < 1 ||
        stepNumber > 3
    ) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | When moving FORWARD,
    | validate the current step first.
    |--------------------------------------------------------------------------
    */

    if (stepNumber > currentStep) {

        if (
            !validateStep(currentStep)
        ) {
            return;
        }

    }


    /*
    |--------------------------------------------------------------------------
    | Hide all step sections
    |--------------------------------------------------------------------------
    */

    var steps =
        document.querySelectorAll(
            '[id^="step-"][id$="-content"]'
        );


    steps.forEach(
        function (section) {

            section.classList.add(
                "hidden"
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Show requested step
    |--------------------------------------------------------------------------
    */

    var target =
        document.getElementById(
            "step-" +
            stepNumber +
            "-content"
        );


    if (!target) {

        console.error(
            "Step " +
            stepNumber +
            " not found."
        );

        return;

    }


    target.classList.remove(
        "hidden"
    );


    /*
    |--------------------------------------------------------------------------
    | Update current step
    |--------------------------------------------------------------------------
    */

    currentStep =
        stepNumber;


    /*
    |--------------------------------------------------------------------------
    | Update progress tracker
    |--------------------------------------------------------------------------
    */

    updateTracker(
        currentStep
    );


    /*
    |--------------------------------------------------------------------------
    | Scroll to top of form
    |--------------------------------------------------------------------------
    */

    var form =
        document.getElementById(
            "patientForm"
        );


    if (form) {

        window.scrollTo({
            top:
                form.offsetTop - 100,

            behavior:
                "smooth"
        });

    }

}



/*
|--------------------------------------------------------------------------
| VALIDATE STEP
|--------------------------------------------------------------------------
*/

function validateStep(stepNumber) {

    /*
    |--------------------------------------------------------------------------
    | STEP 1
    |--------------------------------------------------------------------------
    */

    if (stepNumber === 1) {

        var requiredFields = [
            {
                id: "full_name",
                name: "Full Name"
            },
            {
                id: "date_of_birth",
                name: "Date of Birth"
            },
            {
                id: "gender",
                name: "Gender"
            },
            {
                id: "relationship",
                name: "Relationship"
            }
        ];


        for (
            var i = 0;
            i < requiredFields.length;
            i++
        ) {

            var field =
                document.getElementById(
                    requiredFields[i].id
                );


            if (!field) {
                continue;
            }


            /*
            | Check empty
            */

            if (
                field.value.trim() === ""
            ) {

                alert(
                    "Please complete " +
                    requiredFields[i].name +
                    " before continuing."
                );


                field.focus();


                /*
                | Highlight field
                */

                field.classList.add(
                    "input-error"
                );


                setTimeout(
                    function (element) {

                        element.classList.remove(
                            "input-error"
                        );

                    },
                    2000,
                    field
                );


                return false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Validate profile photo if selected
        |--------------------------------------------------------------------------
        */

        var photo =
            document.getElementById(
                "profile_photo"
            );


        if (
            photo &&
            photo.files &&
            photo.files.length > 0
        ) {

            var photoFile =
                photo.files[0];


            var allowedPhotoTypes = [
                "image/jpeg",
                "image/png"
            ];


            if (
                allowedPhotoTypes.indexOf(
                    photoFile.type
                ) === -1
            ) {

                alert(
                    "Profile photo must be JPG or PNG."
                );


                photo.value = "";


                return false;

            }


            /*
            | Maximum 2 MB
            */

            if (
                photoFile.size >
                2 * 1024 * 1024
            ) {

                alert(
                    "Profile photo must be smaller than 2 MB."
                );


                photo.value = "";


                return false;

            }

        }


        return true;

    }



    /*
    |--------------------------------------------------------------------------
    | STEP 2
    |--------------------------------------------------------------------------
    |
    | All fields on Step 2 are currently optional
    | in your PHP form.
    |
    */

    if (stepNumber === 2) {

        return true;

    }



    /*
    |--------------------------------------------------------------------------
    | STEP 3
    |--------------------------------------------------------------------------
    */

    if (stepNumber === 3) {

        /*
        | Emergency fields are currently optional,
        | so allow the user to continue.
        */

        return true;

    }


    return true;

}



/*
|--------------------------------------------------------------------------
| UPDATE PROGRESS TRACKER
|--------------------------------------------------------------------------
*/

function updateTracker(stepNumber) {


    for (
        var i = 1;
        i <= 3;
        i++
    ) {

        var stepItem =
            document.getElementById(
                "step-item-" + i
            );


        var label =
            document.getElementById(
                "label-" + i
            );


        if (!stepItem || !label) {
            continue;
        }


        /*
        |--------------------------------------------------------------------------
        | STEP COMPLETED
        |--------------------------------------------------------------------------
        */

        if (i < stepNumber) {

            stepItem.classList.add(
                "completed"
            );

            stepItem.classList.remove(
                "active"
            );


            label.innerHTML =
                "✓";

        }


        /*
        |--------------------------------------------------------------------------
        | CURRENT STEP
        |--------------------------------------------------------------------------
        */

        else if (
            i === stepNumber
        ) {

            stepItem.classList.add(
                "active"
            );

            stepItem.classList.remove(
                "completed"
            );


            label.innerHTML =
                i;

        }


        /*
        |--------------------------------------------------------------------------
        | FUTURE STEP
        |--------------------------------------------------------------------------
        */

        else {

            stepItem.classList.remove(
                "active"
            );

            stepItem.classList.remove(
                "completed"
            );


            label.innerHTML =
                i;

        }

    }

}



/*
|--------------------------------------------------------------------------
| MEDICAL CONDITIONS
|--------------------------------------------------------------------------
*/

function setupMedicalConditions() {

    var container =
        document.getElementById(
            "medicalConditionsTags"
        );


    var hiddenInput =
        document.getElementById(
            "medical_conditions"
        );


    if (
        !container ||
        !hiddenInput
    ) {
        return;
    }


    var buttons =
        container.querySelectorAll(
            ".condition-tag"
        );


    buttons.forEach(
        function (button) {

            button.addEventListener(
                "click",
                function () {

                    /*
                    | Toggle selected state
                    */

                    button.classList.toggle(
                        "selected"
                    );


                    updateMedicalConditions();

                }
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Add condition button
    |--------------------------------------------------------------------------
    */

    var addButton =
        document.getElementById(
            "addConditionButton"
        );


    if (addButton) {

        addButton.addEventListener(
            "click",
            function () {

                var condition =
                    prompt(
                        "Enter a medical condition:"
                    );


                if (
                    !condition ||
                    condition.trim() === ""
                ) {
                    return;
                }


                condition =
                    condition.trim();


                /*
                | Prevent duplicate condition
                */

                var existing =
                    container.querySelector(
                        '[data-condition="' +
                        condition +
                        '"]'
                    );


                if (existing) {

                    alert(
                        "This condition already exists."
                    );


                    return;

                }


                /*
                | Create new condition button
                */

                var newButton =
                    document.createElement(
                        "button"
                    );


                newButton.type =
                    "button";


                newButton.className =
                    "tag condition-tag selected";


                newButton.setAttribute(
                    "data-condition",
                    condition
                );


                newButton.textContent =
                    condition;


                /*
                | Insert before Add Condition
                */

                container.insertBefore(
                    newButton,
                    addButton
                );


                /*
                | Add click event
                */

                newButton.addEventListener(
                    "click",
                    function () {

                        newButton.classList.toggle(
                            "selected"
                        );


                        updateMedicalConditions();

                    }
                );


                updateMedicalConditions();

            }
        );

    }


    updateMedicalConditions();

}



/*
|--------------------------------------------------------------------------
| UPDATE MEDICAL CONDITIONS HIDDEN INPUT
|--------------------------------------------------------------------------
*/

function updateMedicalConditions() {

    var container =
        document.getElementById(
            "medicalConditionsTags"
        );


    var hiddenInput =
        document.getElementById(
            "medical_conditions"
        );


    if (
        !container ||
        !hiddenInput
    ) {
        return;
    }


    var selected =
        container.querySelectorAll(
            ".condition-tag.selected"
        );


    var conditions = [];


    selected.forEach(
        function (button) {

            var condition =
                button.getAttribute(
                    "data-condition"
                );


            if (
                condition &&
                condition.trim() !== ""
            ) {

                conditions.push(
                    condition.trim()
                );

            }

        }
    );


    hiddenInput.value =
        conditions.join(", ");

}



/*
|--------------------------------------------------------------------------
| MEDICAL DOCUMENT
|--------------------------------------------------------------------------
*/

function setupMedicalDocument() {

    var input =
        document.getElementById(
            "medical_document"
        );


    var nameDisplay =
        document.getElementById(
            "medical-document-name"
        );


    if (!input) {
        return;
    }


    input.addEventListener(
        "change",
        function () {

            if (
                !this.files ||
                !this.files.length
            ) {

                if (nameDisplay) {

                    nameDisplay.textContent =
                        "No document selected";

                }


                return;

            }


            var file =
                this.files[0];


            var allowedExtensions = [
                "pdf",
                "jpg",
                "jpeg",
                "png"
            ];


            var extension =
                file.name
                    .split(".")
                    .pop()
                    .toLowerCase();


            /*
            | Validate extension
            */

            if (
                allowedExtensions.indexOf(
                    extension
                ) === -1
            ) {

                alert(
                    "Only PDF, JPG, JPEG and PNG files are allowed."
                );


                this.value = "";


                if (nameDisplay) {

                    nameDisplay.textContent =
                        "No document selected";

                }


                return;

            }


            /*
            | Maximum 10 MB
            */

            if (
                file.size >
                10 * 1024 * 1024
            ) {

                alert(
                    "Medical document must be smaller than 10 MB."
                );


                this.value = "";


                if (nameDisplay) {

                    nameDisplay.textContent =
                        "No document selected";

                }


                return;

            }


            /*
            | Show selected filename
            */

            if (nameDisplay) {

                nameDisplay.textContent =
                    file.name +
                    " (" +
                    formatFileSize(
                        file.size
                    ) +
                    ")";

            }

        }
    );

}



/*
|--------------------------------------------------------------------------
| FORM SUBMISSION
|--------------------------------------------------------------------------
*/

function setupFormSubmission() {

    var form =
        document.getElementById(
            "patientForm"
        );


    if (!form) {
        return;
    }


    form.addEventListener(
        "submit",
        function (event) {

            /*
            | Make sure Step 3 is valid
            */

            if (
                !validateStep(3)
            ) {

                event.preventDefault();

                return;

            }


            /*
            | Make sure medical conditions
            | are updated
            */

            updateMedicalConditions();


            /*
            | Show loading state
            */

            var loading =
                document.getElementById(
                    "loading-state"
                );


            if (loading) {

                loading.classList.remove(
                    "hidden"
                );

            }


            /*
            | Disable Create button
            */

            var createButton =
                document.getElementById(
                    "createPatientButton"
                );


            if (createButton) {

                createButton.disabled =
                    true;


                createButton.textContent =
                    "Creating...";

            }

        }
    );

}



/*
|--------------------------------------------------------------------------
| FILE SIZE
|--------------------------------------------------------------------------
*/

function formatFileSize(bytes) {

    if (
        bytes <
        1024
    ) {

        return (
            bytes +
            " B"
        );

    }


    if (
        bytes <
        1024 * 1024
    ) {

        return (
            (bytes / 1024)
                .toFixed(1) +
            " KB"
        );

    }


    return (
        (bytes / 1024 / 1024)
            .toFixed(2) +
        " MB"
    );

}



/*
|--------------------------------------------------------------------------
| INITIALIZE
|--------------------------------------------------------------------------
*/

document.addEventListener(
    "DOMContentLoaded",
    function () {

        /*
        | Make sure Step 1 is visible
        */

        var step1 =
            document.getElementById(
                "step-1-content"
            );


        var step2 =
            document.getElementById(
                "step-2-content"
            );


        var step3 =
            document.getElementById(
                "step-3-content"
            );


        if (step1) {

            step1.classList.remove(
                "hidden"
            );

        }


        if (step2) {

            step2.classList.add(
                "hidden"
            );

        }


        if (step3) {

            step3.classList.add(
                "hidden"
            );

        }


        currentStep = 1;


        updateTracker(
            1
        );


        setupMedicalConditions();

        setupMedicalDocument();

        setupFormSubmission();

    }
);