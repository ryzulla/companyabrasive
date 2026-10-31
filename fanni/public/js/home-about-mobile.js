/* Halaman Tentang mobile: animasi penghitung statistik. */

// Hitung naik angka statistik
document.querySelectorAll('.counter').forEach(function (c) {
    var target = +c.getAttribute('data-target'), n = 0, step = Math.max(1, target / 60);
    (function tick() { n = Math.min(target, n + step); c.textContent = Math.ceil(n); if (n < target) requestAnimationFrame(tick); })();
});
