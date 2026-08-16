/* =========================================================
   SAFEHANDS - PAYMENT HISTORY
   Pure Vanilla JavaScript
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const downloadButton =
        document.getElementById("downloadReceipt");


    /*
    |--------------------------------------------------------------------------
    | Download Receipt
    |--------------------------------------------------------------------------
    | For now this creates a simple receipt text file.
    |
    | Later, when your database/payment system is ready,
    | this can be changed to:
    |
    | window.location.href = "download-receipt.php?booking_id=...";
    |--------------------------------------------------------------------------
    */

    if (downloadButton) {

        downloadButton.addEventListener(
            "click",
            function () {

                const receipt = `
SAFEHANDS
Healthcare Caregiver Service

----------------------------------------

PAYMENT RECEIPT

Booking ID:
#SH-882910

Caregiver:
Nadeesha Perera

Patient:
Ananda Silva

Date:
15 Aug 2026

Duration:
8 Hours

----------------------------------------

Service Fee:
Rs. 8,000.00

Platform Fee:
Rs. 400.00

TOTAL PAID:
Rs. 8,400.00

----------------------------------------

Transaction ID:
TXN-SH-2026-008291

Payment Method:
Card ending in 4521 (Visa)

Payment Status:
Completed

----------------------------------------

Thank you for using SafeHands.
        `.trim();


                const blob =
                    new Blob(
                        [receipt],
                        {
                            type: "text/plain"
                        }
                    );


                const url =
                    URL.createObjectURL(blob);


                const link =
                    document.createElement("a");


                link.href = url;

                link.download =
                    "SafeHands-Payment-Receipt-SH-882910.txt";


                document.body.appendChild(link);

                link.click();

                document.body.removeChild(link);


                URL.revokeObjectURL(url);

            }
        );

    }

});