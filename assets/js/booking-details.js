document.addEventListener("DOMContentLoaded", () => {
    // --- Tab Switching Logic ---
    window.switchTab = function(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });

        // Show target content
        const activeContent = document.getElementById('content-' + tabId);
        if (activeContent) {
            activeContent.classList.add('active');
        }

        // Deactivate all buttons
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.setAttribute('aria-selected', 'false');
        });

        // Activate target button
        const activeBtn = document.getElementById('tab-' + tabId);
        if (activeBtn) {
            activeBtn.classList.add('active');
            activeBtn.setAttribute('aria-selected', 'true');
        }
    };

    // --- Dynamic OTP Generation Logic ---
    const otpButton = document.getElementById('generate-otp-btn');
    if (otpButton) {
        otpButton.addEventListener('click', function() {
            this.disabled = true;
            this.innerHTML = `
                <svg class="icon spin" viewBox="0 0 24 24">
                    <path d="M12 4V2A10 10 0 0 0 2 12h2a8 8 0 0 1 8-8z"/>
                </svg>
                <span>Generating...</span>
            `;

            setTimeout(() => {
                this.innerHTML = `<span style="font-size:18px; letter-spacing: 0.15em; font-weight: 900;">OTP: 8821</span>`;
                this.style.backgroundColor = 'var(--status-success)';
                this.disabled = false;
            }, 1200);
        });
    }
});