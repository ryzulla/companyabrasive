/* Detail produk mobile: carousel, lightbox/zoom. Data dari server ada di window.PAGE. */

const GALLERY = PAGE.images;
let idx = 0;

/* ───── CAROUSEL ───── */
const track = document.getElementById('pdTrack');
const dots = document.querySelectorAll('#pdDots span');
const countEl = document.getElementById('pdCount');
function setIdx(i) {
    idx = i;
    dots.forEach((d, n) => d.classList.toggle('active', n === i));
    if (countEl) countEl.textContent = (i + 1) + ' / ' + GALLERY.length;
}
track.addEventListener('scroll', () => {
    const i = Math.round(track.scrollLeft / track.clientWidth);
    if (i !== idx) setIdx(i);
}, { passive: true });

/* ───── LIGHTBOX + ZOOM ───── */
const lb = document.getElementById('lightbox');
const lbStage = document.getElementById('lbStage');
const lbImg = document.getElementById('lbImg');
const LB_MIN = 1, LB_MAX = 5;
let z = { scale: 1, x: 0, y: 0 };
const pointers = new Map();
let dragStart = null, pinchStart = null, moved = false;

function lbApply() { lbImg.style.transform = `translate(${z.x}px, ${z.y}px) scale(${z.scale})`; }
function lbClamp() {
    const mx = Math.max(0, (lbImg.clientWidth * z.scale - lbStage.clientWidth) / 2);
    const my = Math.max(0, (lbImg.clientHeight * z.scale - lbStage.clientHeight) / 2);
    z.x = Math.min(mx, Math.max(-mx, z.x));
    z.y = Math.min(my, Math.max(-my, z.y));
}
function lbReset() { z = { scale: 1, x: 0, y: 0 }; lbApply(); }
function lbSetScale(next, cx, cy) {
    next = Math.min(LB_MAX, Math.max(LB_MIN, next));
    if (cx !== undefined) {
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
    lbImg.src = GALLERY[idx];
    document.getElementById('lbCount').textContent = GALLERY.length > 1 ? (idx + 1) + ' / ' + GALLERY.length : '';
    document.querySelectorAll('#lbThumbs button').forEach((b, n) => b.classList.toggle('active', n === idx));
    lbReset();
}
function lbGo(i) {
    idx = (i + GALLERY.length) % GALLERY.length;
    lbSync();
}
function openLightbox(i) {
    if (!GALLERY.length) return;
    idx = i || 0;
    lb.classList.add('open'); lb.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
    lbSync();
}
function closeLightbox() {
    lb.classList.remove('open'); lb.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
    // samakan carousel dengan foto terakhir yang dilihat
    track.scrollTo({ left: idx * track.clientWidth, behavior: 'auto' });
    setIdx(idx);
}

lbStage.addEventListener('pointerdown', e => {
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
        }
    }
});
function lbPointerEnd(e) {
    const wasSingle = pointers.size === 1;
    const start = dragStart;
    pointers.delete(e.pointerId);
    if (pointers.size < 2) pinchStart = null;
    if (pointers.size === 0) {
        // geser horizontal saat belum di-zoom = pindah foto
        if (wasSingle && start && z.scale === 1 && e.type === 'pointerup') {
            const dx = e.clientX - start.x;
            if (Math.abs(dx) > 60 && Math.abs(e.clientY - start.y) < 60) { lbGo(idx + (dx < 0 ? 1 : -1)); dragStart = null; return; }
        }
        // ketuk tanpa geser = toggle zoom
        if (!moved && wasSingle && e.type === 'pointerup') lbSetScale(z.scale > 1 ? 1 : 2.5, e.clientX, e.clientY);
        dragStart = null;
    }
}
lbStage.addEventListener('pointerup', lbPointerEnd);
lbStage.addEventListener('pointercancel', lbPointerEnd);
window.addEventListener('keydown', e => { if (e.key === 'Escape' && lb.classList.contains('open')) closeLightbox(); });
