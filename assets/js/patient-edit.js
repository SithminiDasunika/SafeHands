document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('edit-profile-form');
    const successNotify = document.getElementById('success-notification');
    const saveBtn = document.getElementById('save-btn');
    const addConditionBtn = document.getElementById('btn-add-condition');
    const conditionsList = document.getElementById('conditions-list');

    // Handle Form Submission
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            // Disable submit button & show loading state
            saveBtn.disabled = true;
            saveBtn.innerHTML = `
                <span style="display: flex; align-items: center; gap: 0.5rem; justify-content: center;">
                    <span class="spinner"></span> Saving...
                </span>
            `;

            // Simulate server network delay
            setTimeout(() => {
                // Display success banner
                if (successNotify) {
                    successNotify.classList.add('show');
                }

                // Simulate redirect after 2.5 seconds
                setTimeout(() => {
                    alert('Simulated Redirect: Navigating back to Patient Profile View...');
                    location.reload();
                }, 2500);

            }, 800);
        });
    }

    // Dynamic Medical Condition Tag Removal
    if (conditionsList) {
        conditionsList.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('.btn-tag-remove');
            if (removeBtn) {
                const tag = removeBtn.closest('.tag');
                if (tag) {
                    tag.remove();
                }
            }
        });
    }

    // Dynamic Add Medical Condition
    if (addConditionBtn) {
        addConditionBtn.addEventListener('click', () => {
            const conditionName = prompt('Enter new medical condition:');
            if (conditionName && conditionName.trim() !== '') {
                const tag = document.createElement('span');
                tag.className = 'tag';
                tag.innerHTML = `
                    ${escapeHTML(conditionName.trim())}
                    <button type="button" class="btn-tag-remove" aria-label="Remove ${escapeHTML(conditionName.trim())}">
                        <span class="material-symbols-outlined icon-xs">close</span>
                    </button>
                `;
                conditionsList.insertBefore(tag, addConditionBtn);
            }
        });
    }

    // Utility to prevent XSS injection in dynamic inputs
    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
        );
    }
});