document.addEventListener("DOMContentLoaded", function () {

    initializeDocumentCards();

});


/* =========================
   DOCUMENT CARD EFFECT
========================= */

function initializeDocumentCards() {

    const cards = document.querySelectorAll(".document-item");

    cards.forEach(function (card) {

        card.addEventListener("mouseenter", function () {
            card.style.boxShadow = "0 6px 15px rgba(0, 74, 198, 0.08)";
            card.style.borderColor = "#004ac6";
        });

        card.addEventListener("mouseleave", function () {
            card.style.boxShadow = "none";
            card.style.borderColor = "#f1f5f9";
        });

    });

}


/* =========================
   VIEW HISTORY
========================= */

function viewAllHistory() {

    alert("Full care history will be displayed here.");

}


/* =========================
   VIEW REPORT
========================= */

function viewReport(caregiver) {

    alert("Opening care report for " + caregiver);

}


/* =========================
   VIEW DOCUMENT
========================= */

function viewDocument(documentName) {

    alert("Opening: " + documentName);

}


/* =========================
   DOWNLOAD DOCUMENT
========================= */

function downloadDocument(documentName) {

    alert("Downloading: " + documentName);

}


/* =========================
   UPLOAD DOCUMENT
========================= */

function uploadDocument() {

    alert("Document upload feature will be connected here.");

}


/* =========================
   DELETE PATIENT
========================= */

function deletePatient() {

    const confirmed = confirm(
        "Are you sure you want to delete this patient profile?"
    );

    if (confirmed) {

        alert("Patient profile deleted.");

        // Later connect this to your PHP delete process.
        // Example:
        // window.location.href = "delete-patient.php?id=123";

    }

}