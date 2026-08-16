document.addEventListener('DOMContentLoaded', () => {

    // --- 1. Real-Time Patient Search Filter ---
    const searchInput = document.getElementById('patient-search');
    const patientCards = document.querySelectorAll('.patient-card');

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();

            patientCards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                if (name.includes(query)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }

    // --- 2. Add Patient Modal Functionality ---
    const addPatientBtn = document.getElementById('add-patient-btn');
    const patientModal = document.getElementById('patient-modal');
    const closeModalBtn = document.getElementById('close-modal-btn');
    const cancelModalBtn = document.getElementById('cancel-modal-btn');
    const addPatientForm = document.getElementById('add-patient-form');

    function openModal() {
        if (patientModal) patientModal.classList.add('active');
    }

    function closeModal() {
        if (patientModal) patientModal.classList.remove('active');
        if (addPatientForm) addPatientForm.reset();
    }

    if (addPatientBtn) addPatientBtn.addEventListener('click', openModal);
    if (closeModalBtn) closeModalBtn.addEventListener('click', closeModal);
    if (cancelModalBtn) cancelModalBtn.addEventListener('click', closeModal);

    // Close modal when clicking on background overlay
    if (patientModal) {
        patientModal.addEventListener('click', (e) => {
            if (e.target === patientModal) closeModal();
        });
    }

    // Handle form submission
    if (addPatientForm) {
        addPatientForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const fullName = document.getElementById('full_name').value;
            alert(`Patient "${fullName}" added successfully!`);
            closeModal();
        });
    }

    // --- 3. View Report Action Handlers ---
    const reportButtons = document.querySelectorAll('.view-report-btn');
    reportButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const reportId = btn.getAttribute('data-report-id');
            alert(`Opening report details for Report ID: #${reportId}`);
        });
    });

});