document.addEventListener("DOMContentLoaded", function () {
    const patientName = document.getElementById("patient-name");
    const patientAvatar = document.getElementById("patient-avatar");

    /*
     * Later, these values can come from PHP/database data.
     * For the UI version they use the same example patient
     * shown in the original design.
     */
    const name = patientName ? patientName.textContent.trim() : "Johnathan Doe";

    if (patientAvatar && name) {
        const initials = name
            .split(/\s+/)
            .filter(Boolean)
            .slice(0, 2)
            .map(function (word) {
                return word.charAt(0).toUpperCase();
            })
            .join("");

        patientAvatar.textContent = initials;
    }

    /*
     * Small button feedback.
     * Navigation itself is handled by normal PHP links,
     * so no framework or library is required.
     */
    document.querySelectorAll(".btn").forEach(function (button) {
        button.addEventListener("click", function () {
            button.classList.add("clicked");

            setTimeout(function () {
                button.classList.remove("clicked");
            }, 150);
        });
    });
});
