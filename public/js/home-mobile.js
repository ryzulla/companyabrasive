/* Beranda mobile: layar (tab), produk & filter, video layar penuh, FAQ, penghitung statistik. Data dari server ada di window.PAGE. */

const FEATURED   = PAGE.products;
const CATEGORIES = PAGE.categories;
const WA         = PAGE.waNumber;
const SEARCH_URL = PAGE.searchUrl;
const FALLBACK_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';

if (PAGE.openContact) document.addEventListener('DOMContentLoaded', () => showScreen('contact'));

/* ───── SCREEN NAV ───── */
function showScreen(name) {
    document.querySelectorAll('.screen').forEach(s => s.classList.remove('active'));
    document.getElementById('screen-' + name).classList.add('active');
    document.querySelectorAll('.tab').forEach(t => t.classList.toggle('active', t.dataset.screen === name));
    window.scrollTo({ top: 0, behavior: 'auto' });
    if (name === 'products' && !prodLoaded) loadProducts(true);
}

/* ───── PRODUCT CARD TEMPLATE ───── */
function cardHTML(p) {
    return `<div class="prod-card" onclick="location.href='${p.url}'">
        <div class="prod-img">
            ${p.badge ? `<span class="prod-badge">${p.badge}</span>` : ''}
            <img src="${p.img || FALLBACK_IMG}" alt="${escapeHtml(p.title)}" loading="lazy">
        </div>
        <div class="prod-body">
            <span class="prod-meta">${escapeHtml(p.meta || '')}</span>
            <h4 class="prod-title"><a href="${p.url}">${escapeHtml(p.title)}</a></h4>
            <div class="prod-foot">
                <div class="prod-spec">${escapeHtml(p.specLabel || '')}<b>${escapeHtml(p.specVal || '-')}</b></div>
                ${WA ? `<button class="prod-wa" onclick="event.stopPropagation(); waProduct('${encodeURIComponent(p.title)}')" aria-label="Tanya via WhatsApp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></button>` : ''}
            </div>
        </div>
    </div>`;
}


/* ───── TAMPILAN KOTAK / DAFTAR (disimpan di browser) ───── */
function setProdView(mode) {
    document.querySelectorAll('.prod-grid').forEach(g => g.classList.toggle('list-view', mode === 'list'));
    document.querySelectorAll('.vt-btn').forEach(b => b.classList.toggle('active', b.dataset.view === mode));
    try { localStorage.setItem('prodView', mode); } catch (e) {}
}
(function () { let m = 'grid'; try { m = localStorage.getItem('prodView') === 'list' ? 'list' : 'grid'; } catch (e) {} setProdView(m); })();

/* ───── MUAT LEBIH BANYAK: jangan sampai ketukan kedua membuka produk ─────
   Produk baru disisipkan di ATAS tombol, sehingga tombol turun jauh dan di titik jari tadi
   muncul kartu produk. Ketukan ulang di sana (atau double tap) akan membuka halaman produk.
   Solusinya: gulir ke produk baru, dan abaikan ketukan pada kartu sesaat setelah memuat. */
let navGuardUntil = 0;
function armClickGuard(ms = 900) { navGuardUntil = Date.now() + ms; }
document.addEventListener('click', e => {
    if (Date.now() < navGuardUntil && e.target.closest('.prod-card')) { e.preventDefault(); e.stopPropagation(); }
}, true);
function revealNew(container, previousCount) {
    const first = container.querySelectorAll('.prod-card')[previousCount];
    if (!first) return;
    armClickGuard(700);
    const header = document.querySelector('.app-header');
    const offset = (header ? header.getBoundingClientRect().height : 0) + 12;
    window.scrollTo({ top: first.getBoundingClientRect().top + window.scrollY - offset, behavior: 'smooth' });
}


/* ───── PRODUCTS SCREEN (live fetch + filter + search) ───── */
let curCat = 'all', curQuery = '', curPage = 1, lastPage = 1, prodLoaded = false, fetching = false, searchTimer = null;
let _resultsCache = [];
const resultsEl = document.getElementById('prodResults');
const loadMoreWrap = document.getElementById('loadMoreWrap');

function skeletons(n = 6) {
    resultsEl.innerHTML = Array.from({ length: n }, () => '<div class="skel"></div>').join('');
}

async function loadProducts(reset = true) {
    if (fetching) return;
    fetching = true;
    if (reset) { curPage = 1; skeletons(); }
    try {
        const url = new URL(SEARCH_URL, window.location.origin);
        if (curQuery) url.searchParams.set('q', curQuery);
        url.searchParams.set('category', curCat);
        url.searchParams.set('page', curPage);
        const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();
        lastPage = data.lastPage || 1;
        document.getElementById('prodCount').textContent = (data.total ?? 0) + ' produk';
        const items = data.products || [];
        if (reset) _resultsCache = items.slice();
        else _resultsCache.push(...items);
        const html = items.map(cardHTML).join('');
        if (reset) {
            resultsEl.innerHTML = html || emptyState();
        } else {
            const before = resultsEl.querySelectorAll('.prod-card').length;
            resultsEl.insertAdjacentHTML('beforeend', html);
            revealNew(resultsEl, before);
        }
        loadMoreWrap.style.display = curPage < lastPage ? 'block' : 'none';
        prodLoaded = true;
    } catch (e) {
        resultsEl.innerHTML = emptyState('Gagal memuat produk. Coba lagi.');
        loadMoreWrap.style.display = 'none';
    } finally {
        fetching = false;
    }
}
function loadMore() { armClickGuard(4000); curPage++; loadProducts(false); }
function emptyState(msg) {
    return `<div class="empty-state" style="grid-column:1/-1;"><i class="fa-solid fa-box-open"></i><p>${msg || 'Belum ada produk di kategori ini.'}</p></div>`;
}

// chips
document.getElementById('chips').addEventListener('click', e => {
    const btn = e.target.closest('.chip');
    if (!btn) return;
    document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    curCat = btn.dataset.cat;
    loadProducts(true);
});

// search (debounced)
const searchInput = document.getElementById('mSearch');
const clearBtn = document.getElementById('clearSearch');
searchInput.addEventListener('input', () => {
    curQuery = searchInput.value.trim();
    clearBtn.style.display = curQuery ? 'block' : 'none';
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => loadProducts(true), 350);
});
function clearSearch() {
    searchInput.value = ''; curQuery = ''; clearBtn.style.display = 'none';
    loadProducts(true); searchInput.focus();
}

function goCategory(id) {
    showScreen('products');
    document.querySelectorAll('.chip').forEach(c => c.classList.toggle('active', c.dataset.cat == id));
    curCat = String(id);
    loadProducts(true);
}

/* ───── VIDEO FULLSCREEN (mobile: langsung penuh layar) ───── */
const videoFs = document.getElementById('videoFs');
const videoFsInner = document.getElementById('videoFsInner');
function playVideo(id) {
    videoFsInner.innerHTML = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0&playsinline=1&fs=1" title="Video" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
    videoFs.classList.add('open');
    document.body.style.overflow = 'hidden';
    const req = videoFs.requestFullscreen || videoFs.webkitRequestFullscreen;
    if (req) { try { const r = req.call(videoFs); if (r && r.catch) r.catch(() => {}); } catch (e) {} }
}
function closeVideo() {
    videoFs.classList.remove('open');
    videoFsInner.innerHTML = '';
    document.body.style.overflow = '';
    if (document.fullscreenElement) document.exitFullscreen().catch(() => {});
    else if (document.webkitFullscreenElement) document.webkitExitFullscreen();
}
// Tutup overlay bila user keluar dari fullscreen native
document.addEventListener('fullscreenchange', () => {
    if (!document.fullscreenElement && videoFs.classList.contains('open')) closeVideo();
});
window.addEventListener('keydown', e => { if (e.key === 'Escape' && videoFs.classList.contains('open')) closeVideo(); });

/* ───── WHATSAPP ───── */
function waProduct(titleEnc, verb = 'tertarik+dengan') {
    window.open(`https://wa.me/${WA}?text=Halo%2C+saya+${verb}+${titleEnc}`, '_blank');
}

/* ───── FAQ ───── */
function toggleFaq(el) {
    const item = el.parentElement;
    const open = item.classList.contains('open');
    document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
    if (!open) item.classList.add('open');
}

/* ───── COUNTER ───── */
const counters = document.querySelectorAll('.counter');
const co = new IntersectionObserver((entries, obs) => {
    entries.forEach(en => {
        if (!en.isIntersecting) return;
        const el = en.target, target = +el.dataset.target, inc = Math.max(1, target / 60);
        const tick = () => {
            const v = +el.innerText;
            if (v < target) { el.innerText = Math.ceil(v + inc); requestAnimationFrame(tick); }
            else el.innerText = target;
        };
        tick(); obs.unobserve(el);
    });
}, { threshold: 0.4 });
counters.forEach(c => co.observe(c));

/* ───── UTIL ───── */
function escapeHtml(str) {
    return String(str ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
}
let toastTimer;
function toast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.classList.add('show');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => t.classList.remove('show'), 2200);
}


/* ───── buka layar dari hash (#products, #about, #contact), mis. dari halaman detail produk ───── */
(function () {
    var h = location.hash.replace('#', '');
    if (['products', 'about', 'contact'].indexOf(h) !== -1) showScreen(h);
})();
