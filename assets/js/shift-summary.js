document.addEventListener('DOMContentLoaded', () => {
    const cardObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.card-animated').forEach(card => {
        cardObserver.observe(card);
    });

    document.querySelectorAll('.vital-item').forEach(item => {
        item.addEventListener('mouseenter', () => item.classList.add('active-hover'));
        item.addEventListener('mouseleave', () => item.classList.remove('active-hover'));
    });

    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', () => {
            alert('Preparing PDF export of Arthur Miller\'s daily report...');
        });
    }

    const shareBtn = document.getElementById('shareBtn');
    if (shareBtn) {
        shareBtn.addEventListener('click', () => {
            if (navigator.share) {
                navigator.share({
                    title: 'SafeHands Care Report',
                    text: 'Daily Care Shift Summary for Arthur Miller',
                    url: window.location.href
                }).catch(() => {});
            } else {
                alert('Report link copied to clipboard!');
            }
        });
    }

    const reportIncidentBtn = document.getElementById('reportIncidentBtn');
    if (reportIncidentBtn) {
        reportIncidentBtn.addEventListener('click', () => {
            if (confirm('Do you wish to escalate an incident to 24/7 Clinical Support?')) {
                alert('Support team notified. A supervisor will call immediately.');
            }
        });
    }

    const contactCaregiverBtn = document.getElementById('contactCaregiverBtn');
    if (contactCaregiverBtn) {
        contactCaregiverBtn.addEventListener('click', () => {
            alert('Opening secure messaging with Sarah Jenkins...');
        });
    }
});