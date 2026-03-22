/**
 * Lovemad Theme - JavaScript
 * Daraz-Style E-Commerce Theme
 */
document.addEventListener('DOMContentLoaded', function () {

    // ── Auto-dismiss alerts ──
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ── Smooth scroll for anchor links ──
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ── Back to top button (lazy init) ──
    const btnTop = document.createElement('button');
    btnTop.innerHTML = '<i class="bi bi-arrow-up"></i>';
    btnTop.className = 'btn btn-primary btn-sm rounded-circle shadow-lg position-fixed';
    btnTop.style.cssText = 'bottom:30px;right:30px;width:40px;height:40px;z-index:9999;display:none;border-radius:50%!important;font-size:16px;';
    document.body.appendChild(btnTop);

    window.addEventListener('scroll', () => {
        btnTop.style.display = window.scrollY > 300 ? 'flex' : 'none';
        btnTop.style.alignItems = 'center';
        btnTop.style.justifyContent = 'center';
    });
    btnTop.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

});
