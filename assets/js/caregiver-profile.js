document.addEventListener('DOMContentLoaded', function () {

    document.body.style.opacity = '0';
    requestAnimationFrame(function () {
        document.body.style.transition = 'opacity 0.3s ease';
        document.body.style.opacity = '1';
    });

    document.querySelectorAll('.similar-item').forEach(function (card) {
        card.addEventListener('mouseenter', function () {
            card.style.transform = 'translateY(-2px)';
        });
        card.addEventListener('mouseleave', function () {
            card.style.transform = 'translateY(0)';
        });
    });

    var sections = document.querySelectorAll('.profile-main > section, .profile-sidebar > section');
    if ('IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });

        sections.forEach(function (section) {
            section.style.opacity = '0';
            section.style.transform = 'translateY(12px)';
            section.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
            observer.observe(section);
        });
    }

    document.querySelectorAll('.contact-preview-value').forEach(function (item) {
        item.addEventListener('click', function () {
            var text = item.textContent.trim();
            if (text.includes('•')) return; // don't copy masked placeholders
            navigator.clipboard.writeText(text).then(function () {
                var original = item.style.color;
                item.style.color = '#004ac6';
                setTimeout(function () {
                    item.style.color = original;
                }, 600);
            });
        });
    });

    document.querySelectorAll('a[href^="#"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            var targetId = link.getAttribute('href');
            if (targetId.length > 1) {
                var target = document.querySelector(targetId);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

});