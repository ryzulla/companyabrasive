/* Detail produk desktop: galeri, lightbox/zoom. Data dari server ada di window.PAGE. */

const GALLERY = PAGE.images;
let galleryIdx = 0;

function galleryGo(i) {
    if (!GALLERY.length) return;
    galleryIdx = (i + GALLERY.length) % GALLERY.length;
    document.getElementById('galleryMain').src = GALLERY[galleryIdx];
    document.querySelectorAll('.gallery-thumb').forEach((t, n) => t.classList.toggle('active', n === galleryIdx));
    const c = document.getElementById('galleryCount');
    if (c) c.textContent = (galleryIdx + 1) + ' / ' + GALLERY.length;
    if (lb.classList.contains('open')) lbSync();
}
function galleryStep(d) { galleryGo(galleryIdx + d); }

// ── LIGHTBOX + ZOOM
const lb = document.getElementById('lightbox');
const lbStage = document.getElementById('lbStage');
const lbImg = document.getElementById('lbImg');
const LB_MIN = 1, LB_MAX = 5;
let z = { scale: 1, x: 0, y: 0 };
const pointers = new Map();
let dragStart = null, pinchStart = null, moved = false;

function lbApply() {
    lbImg.style.transform = `translate(${z.x}px, ${z.y}px) scale(${z.scale})`;
    lbStage.classList.toggle('zoomed', z.scale > 1);
}
function lbClamp() {
    // batasi geser agar gambar tidak keluar jauh dari layar
    const mx = Math.max(0, (lbImg.clientWidth * z.scale - lbStage.clientWidth) / 2);
    const my = Math.max(0, (lbImg.clientHeight * z.scale - lbStage.clientHeight) / 2);
    z.x = Math.min(mx, Math.max(-mx, z.x));
    z.y = Math.min(my, Math.max(-my, z.y));
}
function lbReset() { z = { scale: 1, x: 0, y: 0 }; lbApply(); }
function lbSetScale(next, cx, cy) {
    next = Math.min(LB_MAX, Math.max(LB_MIN, next));
    if (cx !== undefined) {
        // zoom mengarah ke titik kursor/jari
        const r = lbStage.getBoundingClientRect();
        const px = cx - (r.left + r.width / 2), py = cy - (r.top + r.height / 2);
        const k = next / z.scale;
        z.x = px - (px - z.x) * k;
        z.y = py - (py - z.y) * k;
    }
    z.scale = next;
    if (next === 1) { z.x = 0; z.y = 0; }
    lbClamp(); lbApply();
}
function lbZoomBy(d) { lbSetScale(z.scale + d); }
function lbSync() {
    lbImg.src = GALLERY[galleryIdx];
    document.getElementById('lbCount').textContent = GALLERY.length > 1 ? (galleryIdx + 1) + ' / ' + GALLERY.length : '';
    document.querySelectorAll('#lbThumbs button').forEach((b, n) => b.classList.toggle('active', n === galleryIdx));
    lbReset();
}
function openLightbox() {
    if (!GALLERY.length) return;
    lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    lbSync();
}
function closeLightbox() {
    lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
}

lbStage.addEventListener('wheel', e => {
    e.preventDefault();
    lbSetScale(z.scale * (e.deltaY < 0 ? 1.2 : 1 / 1.2), e.clientX, e.clientY);
}, { passive: false });

lbStage.addEventListener('pointerdown', e => {
    if (e.target.closest('.lb-btn')) return;
    lbStage.setPointerCapture(e.pointerId);
    pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
    moved = false;
    if (pointers.size === 1) dragStart = { x: e.clientX, y: e.clientY, ox: z.x, oy: z.y };
    if (pointers.size === 2) {
        const [a, b] = [...pointers.values()];
        pinchStart = { d: Math.hypot(a.x - b.x, a.y - b.y), scale: z.scale };
    }
});
lbStage.addEventListener('pointermove', e => {
    if (!pointers.has(e.pointerId)) return;
    pointers.set(e.pointerId, { x: e.clientX, y: e.clientY });
    if (pointers.size === 2 && pinchStart) {
        const [a, b] = [...pointers.values()];
        lbSetScale(pinchStart.scale * Math.hypot(a.x - b.x, a.y - b.y) / pinchStart.d, (a.x + b.x) / 2, (a.y + b.y) / 2);
        moved = true;
    } else if (pointers.size === 1 && dragStart) {
        const dx = e.clientX - dragStart.x, dy = e.clientY - dragStart.y;
        if (Math.abs(dx) + Math.abs(dy) > 4) moved = true;
        if (z.scale > 1) {
            z.x = dragStart.ox + dx; z.y = dragStart.oy + dy;
            lbClamp(); lbApply();
            lbStage.classList.add('dragging');
        }
    }
});
function lbPointerEnd(e) {
    const wasSingle = pointers.size === 1;
    const start = dragStart;
    pointers.delete(e.pointerId);
    lbStage.classList.remove('dragging');
    if (pointers.size < 2) pinchStart = null;
    if (pointers.size === 0) {
        // geser horizontal cepat saat belum di-zoom = pindah foto
        if (wasSingle && start && z.scale === 1 && e.type === 'pointerup') {
            const dx = e.clientX - start.x;
            if (Math.abs(dx) > 60 && Math.abs(e.clientY - start.y) < 60) { galleryStep(dx < 0 ? 1 : -1); dragStart = null; return; }
        }
        // ketuk/klik tanpa geser = toggle zoom
        if (!moved && wasSingle && e.type === 'pointerup') lbSetScale(z.scale > 1 ? 1 : 2.5, e.clientX, e.clientY);
        dragStart = null;
    }
}
lbStage.addEventListener('pointerup', lbPointerEnd);
lbStage.addEventListener('pointercancel', lbPointerEnd);

document.addEventListener('keydown', e => {
    const open = lb.classList.contains('open');
    if (e.key === 'Escape' && open) closeLightbox();
    if (e.key === 'ArrowLeft') galleryStep(-1);
    if (e.key === 'ArrowRight') galleryStep(1);
    if (open && (e.key === '+' || e.key === '=')) lbZoomBy(0.5);
    if (open && e.key === '-') lbZoomBy(-0.5);
});
window.addEventListener('resize', () => { if (lb.classList.contains('open')) { lbClamp(); lbApply(); } });

// swipe di foto utama (layar sentuh); ketukan biasa membuka zoom lewat onclick
(() => {
    const el = document.querySelector('.gallery-main');
    let x0 = null, swiped = false;
    el.addEventListener('touchstart', e => { x0 = e.touches[0].clientX; swiped = false; }, { passive: true });
    el.addEventListener('touchend', e => {
        if (x0 === null) return;
        const dx = e.changedTouches[0].clientX - x0;
        if (Math.abs(dx) > 40) { swiped = true; galleryStep(dx < 0 ? 1 : -1); }
        x0 = null;
    });
    el.addEventListener('click', e => { if (swiped) { e.stopImmediatePropagation(); e.preventDefault(); swiped = false; } }, true);
})();
