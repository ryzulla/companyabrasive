/* Beranda desktop: filter produk, modal video, menu mobile, FAQ, penghitung statistik. Data dari server ada di window.PAGE. */

const categoriesData = PAGE.categories;

let currentFilter = 'all';

function renderFilterButtons() {
    const container = document.getElementById('filter-container');
    if (!container) return;
    let html = `<button class="filter-btn active" data-filter="all">Semua Produk</button>`;
    categoriesData.forEach(cat => {
        html += `<button class="filter-btn" data-filter="${escapeHtml(cat.id)}">${escapeHtml(cat.title)}</button>`;
    });
    container.innerHTML = html;
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            e.currentTarget.classList.add('active');
            currentFilter = e.currentTarget.getAttribute('data-filter');
            renderProducts(currentFilter);
        });
    });
}

function triggerFilter(type) {
    document.querySelector('#products').scrollIntoView();
    document.querySelectorAll('.filter-btn').forEach(b => {
        b.classList.remove('active');
        if(b.getAttribute('data-filter') === String(type)) b.classList.add('active');
    });
    currentFilter = type;
    renderProducts(type);
}

// ── TAMPILAN THUMBNAIL / DAFTAR (disimpan di browser; default thumbnail)
function setProdView(mode) {
    document.getElementById('product-container').classList.toggle('list-view', mode === 'list');
    document.querySelectorAll('.vt-btn').forEach(b => b.classList.toggle('active', b.dataset.view === mode));
    try { localStorage.setItem('prodView', mode); } catch (e) {}
}
(function () { let m = 'grid'; try { m = localStorage.getItem('prodView') === 'list' ? 'list' : 'grid'; } catch (e) {} setProdView(m); })();

// Beranda hanya memuat 8 produk terbaru. Untuk kategori tertentu, produknya diambil dari server
// supaya kategori yang produknya tidak termasuk 8 terbaru tidak tampil kosong.
const SEARCH_URL = PAGE.searchUrl;
const WA_NUMBER  = PAGE.waNumber;
const FALLBACK_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';
const catCache = {};
let renderToken = 0;

function productCardHTML(p) {
    const wa = WA_NUMBER ? `<a class="btn-sm" href="https://wa.me/${WA_NUMBER}?text=Halo%2C+saya+tertarik+dengan+${encodeURIComponent(p.title)}" target="_blank" rel="noopener" aria-label="Tanya ${escapeAttr(p.title)} via WhatsApp" onclick="event.stopPropagation()"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></a>` : '';
    return `<div class="product-card fetched" data-type="${escapeAttr(p.type)}" onclick="location.href='${escapeAttr(p.url)}'">
        <div class="product-img-wrapper">
            ${p.badge ? `<span class="product-badge">${escapeHtml(p.badge)}</span>` : ''}
            <img src="${escapeAttr(p.img || FALLBACK_IMG)}" alt="${escapeAttr(p.title)}" loading="lazy">
        </div>
        <div class="product-info">
            <div class="product-meta">${escapeHtml(p.meta)}</div>
            <h3 class="product-title"><a href="${escapeAttr(p.url)}">${escapeHtml(p.title)}</a></h3>
            <p class="product-desc">${escapeHtml(p.desc)}</p>
            <div class="product-footer">
                <div class="product-spec">${escapeHtml(p.specLabel)}:<span>${escapeHtml(p.specVal)}</span></div>
                ${wa}
            </div>
        </div>
    </div>`;
}

async function renderProducts(filterType) {
    const container = document.getElementById('product-container');
    const empty = document.getElementById('product-empty');
    const token = ++renderToken;
    const allLink = document.getElementById('allProductsLink');
    if (allLink) allLink.href = PAGE.allProductsUrl + (filterType === 'all' ? '' : '?category=' + encodeURIComponent(filterType));
    const initial = container.querySelectorAll('.product-card:not(.fetched)');
    container.querySelectorAll('.product-card.fetched').forEach(el => el.remove());

    if (filterType === 'all') {
        initial.forEach(card => card.style.display = '');
        empty.style.display = initial.length ? 'none' : 'block';
        return;
    }

    initial.forEach(card => card.style.display = 'none');
    empty.style.display = 'none';
    container.classList.add('loading');
    try {
        if (!catCache[filterType]) {
            const res = await fetch(SEARCH_URL + '?category=' + encodeURIComponent(filterType), { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            catCache[filterType] = (await res.json()).products || [];
        }
        if (token !== renderToken) return; // pengguna sudah pindah kategori
        const items = catCache[filterType];
        container.insertAdjacentHTML('beforeend', items.map(productCardHTML).join(''));
        empty.style.display = items.length ? 'none' : 'block';
    } catch (e) {
        if (token !== renderToken) return;
        empty.textContent = 'Gagal memuat produk. Coba lagi.';
        empty.style.display = 'block';
    } finally {
        if (token === renderToken) container.classList.remove('loading');
    }
}

// ── VIDEO MODAL (desktop: tampil lebih besar) ──
const videosData  = PAGE.videos;
const videoModal  = document.getElementById('videoModal');
const videoPlayer = document.getElementById('videoModalPlayer');
window.openVideoModal = function(id) {
    const v = videosData.find(x => x.id === String(id));
    videoPlayer.innerHTML = `<iframe src="https://www.youtube.com/embed/${id}?autoplay=1&rel=0" title="${v ? escapeAttr(v.title) : ''}" allow="autoplay; encrypted-media; picture-in-picture; fullscreen" allowfullscreen></iframe>`;
    document.getElementById('videoModalTitle').textContent = v ? v.title : '';
    videoModal.classList.add('show');
    document.body.style.overflow = 'hidden';
};
function closeVideoModal() { videoModal.classList.remove('show'); videoPlayer.innerHTML = ''; document.body.style.overflow = ''; }
document.getElementById('closeVideoModal').onclick = closeVideoModal;

function escapeHtml(s) { return String(s ?? '').replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m])); }
function escapeAttr(s) { return escapeHtml(s); }

// ── CLOSE HANDLERS (klik luar + Escape) modal video ──
window.onclick = (e) => {
    if (e.target == videoModal) { closeVideoModal(); }
};
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    if (videoModal.classList.contains('show')) closeVideoModal();
    document.body.style.overflow = '';
});

function toggleMobileNav() {
    var nav = document.getElementById('mobileNav');
    var btn = document.getElementById('hamburger');
    var backdrop = document.getElementById('navBackdrop');
    var isOpen = nav.classList.contains('open');
    if (isOpen) {
        nav.classList.remove('open');
        btn.classList.remove('open');
        backdrop.classList.remove('open');
        document.body.style.overflow = '';
    } else {
        nav.classList.add('open');
        btn.classList.add('open');
        backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
}
function closeMobileNav() {
    document.getElementById('mobileNav').classList.remove('open');
    document.getElementById('hamburger').classList.remove('open');
    document.getElementById('navBackdrop').classList.remove('open');
    document.body.style.overflow = '';
}
// Tutup menu saat scroll cukup jauh (bukan setiap pixel — hindari false trigger di iOS)
var _navScrollY = 0;
window.addEventListener('scroll', function() {
    if (document.getElementById('mobileNav').classList.contains('open')) {
        if (Math.abs(window.scrollY - _navScrollY) > 60) closeMobileNav();
    } else {
        _navScrollY = window.scrollY;
    }
}, { passive: true });

window.toggleFaq = function(element) {
    const parent = element.parentElement;
    const wasActive = parent.classList.contains('active');
    document.querySelectorAll('.faq-item').forEach(item => item.classList.remove('active'));
    if(!wasActive) parent.classList.add('active');
}

// Milestone counter animation
const counters = document.querySelectorAll('.counter');
const counterObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
        if(entry.isIntersecting) {
            const counter = entry.target;
            const target = +counter.getAttribute('data-target');
            const inc = target / 200;
            const updateCount = () => {
                const count = +counter.innerText;
                if(count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 10);
                } else {
                    counter.innerText = target;
                }
            };
            updateCount();
            observer.unobserve(counter);
        }
    });
}, { threshold: 0.5 });
counters.forEach(c => counterObserver.observe(c));

document.addEventListener('DOMContentLoaded', () => {
    renderFilterButtons();
});
