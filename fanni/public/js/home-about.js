/* Halaman Tentang desktop: animasi penghitung statistik. */

// Milestone counter animation
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if (!entry.isIntersecting) return;
        const counter = entry.target;
        const target = +counter.getAttribute('data-target');
        const inc = Math.max(1, target / 100);
        const update = () => {
            const count = +counter.innerText;
            if (count < target) { counter.innerText = Math.ceil(count + inc); requestAnimationFrame(update); }
            else counter.innerText = target;
        };
        update();
        observer.unobserve(counter);
    });
}, { threshold: 0.5 });
counters.forEach(c => counterObserver.observe(c));
