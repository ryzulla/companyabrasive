/* Daftar produk desktop: pencarian, filter, grid, dan paginasi. Data dari server ada di window.PAGE. */

// ── CONFIG
const SEARCH_URL = PAGE.searchUrl;
const WA_NUMBER  = PAGE.waNumber;
const DEFAULT_IMG = 'https://images.unsplash.com/photo-1620912189865-1e8a33da4c5e?auto=format&fit=crop&w=400&q=80';

// ── STATE
let currentProducts = PAGE.products; // initial from server
let state = {
    q:        PAGE.q,
    category: PAGE.category,
    page:     PAGE.page,
};

// ── ELEMENTS
const searchInput  = document.getElementById('searchInput');
const clearBtn     = document.getElementById('clearSearch');
const grid         = document.getElementById('productGrid');
const resultCount  = document.getElementById('resultCount');
const filterBar    = document.getElementById('activeFilterBar');
const filterText   = document.getElementById('activeFilterText');
const pagWrapper   = document.getElementById('paginationWrapper');
const pagInfo      = document.getElementById('paginationInfo');

// ── INITIAL RENDER (dari data server)
renderGrid(currentProducts);
renderPagination(PAGE.pagination);

// ── SEARCH INPUT
searchInput.addEventListener('input', debounce(function () {
    state.q    = this.value.trim();
    state.page = 1;
    clearBtn.style.display = state.q ? 'flex' : 'none';
    fetchProducts();
}, 380));

clearBtn.addEventListener('click', function () {
    searchInput.value  = '';
    state.q            = '';
    state.page         = 1;
    this.style.display = 'none';
    fetchProducts();
    searchInput.focus();
});

// ── CATEGORY PILLS
document.getElementById('categoryPills').addEventListener('click', function (e) {
    const pill = e.target.closest('.category-pill');
    if (!pill) return;
    document.querySelectorAll('.category-pill').forEach(p => p.classList.remove('active'));
    pill.classList.add('active');
    state.category = pill.dataset.cat;
    state.page     = 1;
    fetchProducts();
});

// ── FETCH
async function fetchProducts(pushState = true) {
    grid.classList.add('loading');

    const params = new URLSearchParams();
    if (state.q)        params.set('q', state.q);
    if (state.category) params.set('category', state.category);
    if (state.page > 1) params.set('page', state.page);

    try {
        const res  = await fetch(`${SEARCH_URL}?${params}`);
        const data = await res.json();

        currentProducts = data.products;
        renderGrid(data.products);
        renderPagination(data);
        resultCount.innerHTML = `<strong>${data.total}</strong> produk`;
        updateFilterBar();

        if (pushState) {
            const url = new URL(window.location.href);
            ['q','category','page'].forEach(k => url.searchParams.delete(k));
            if (state.q)        url.searchParams.set('q', state.q);
            if (state.category) url.searchParams.set('category', state.category);
            if (state.page > 1) url.searchParams.set('page', state.page);
            history.pushState({ ...state }, '', url);
        }
    } catch (err) {
        console.error('Fetch error:', err);
    } finally {
        grid.classList.remove('loading');
    }
}

// ── RENDER GRID
function renderGrid(products) {
    if (!products.length) {
        grid.innerHTML = `
            <div class="empty-state">
                <i class="fa-solid fa-box-open"></i>
                <h3>Produk tidak ditemukan</h3>
                <p>Coba ubah kata kunci atau pilih kategori lain. <button onclick="resetFilter()" style="color:var(--accent);background:none;border:none;cursor:pointer;font-weight:600;font-family:inherit;">Reset filter</button></p>
            </div>`;
        return;
    }
    grid.innerHTML = products.map(p => `
        <div class="product-card" onclick="location.href='${p.url}'">
            <div class="product-img-wrapper">
                ${p.badge ? `<span class="product-badge">${p.badge}</span>` : ''}
                ${p.categoryTitle ? `<span class="product-category-tag">${p.categoryTitle}</span>` : ''}
                <img src="${p.img || DEFAULT_IMG}" alt="${p.title}" loading="lazy">
            </div>
            <div class="product-info">
                <div class="product-meta">${p.meta}</div>
                <h3 class="product-title">${p.title}</h3>
                <p class="product-desc">${p.desc}</p>
                <div class="product-footer">
                    <div class="product-spec">${p.specLabel}:<span>${p.specVal}</span></div>
                    <div class="card-actions">
                        ${WA_NUMBER ? `<button class="btn-icon btn-icon-wa" data-title="${p.title.replace(/"/g,'&quot;')}"
                            onclick="event.stopPropagation();openWA(this.dataset.title)"
                            title="Tanya via WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </button>` : ''}
                        <button class="btn-icon btn-icon-info" title="Detail Produk">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>`).join('');
}

// ── RENDER PAGINATION
function renderPagination(data) {
    if (data.lastPage <= 1) { pagWrapper.innerHTML = ''; pagInfo.textContent = ''; return; }

    let html = '';
    // Prev
    html += data.currentPage > 1
        ? `<a href="#" onclick="goPage(${data.currentPage - 1});return false;"><i class="fa-solid fa-chevron-left"></i></a>`
        : `<span class="disabled"><i class="fa-solid fa-chevron-left"></i></span>`;

    // Pages
    for (let p = 1; p <= data.lastPage; p++) {
        if (p === data.currentPage) {
            html += `<span class="active-page">${p}</span>`;
        } else if (p === 1 || p === data.lastPage || Math.abs(p - data.currentPage) <= 2) {
            html += `<a href="#" onclick="goPage(${p});return false;">${p}</a>`;
        } else if (Math.abs(p - data.currentPage) === 3) {
            html += `<span class="dots">…</span>`;
        }
    }

    // Next
    html += data.currentPage < data.lastPage
        ? `<a href="#" onclick="goPage(${data.currentPage + 1});return false;"><i class="fa-solid fa-chevron-right"></i></a>`
        : `<span class="disabled"><i class="fa-solid fa-chevron-right"></i></span>`;

    pagWrapper.innerHTML = html;
    pagInfo.textContent  = data.from && data.to
        ? `Menampilkan ${data.from}–${data.to} dari ${data.total} produk`
        : '';
}

// ── ACTIVE FILTER BAR
function updateFilterBar() {
    if (!state.q && !state.category) {
        filterBar.style.display = 'none';
        return;
    }
    let text = 'Menampilkan';
    if (state.q) text += ` untuk "<strong>${state.q}</strong>"`;
    if (state.category) {
        const name = document.querySelector(`.category-pill[data-cat="${state.category}"]`)?.textContent.trim();
        if (name) text += ` dalam <strong>${name}</strong>`;
    }
    filterText.innerHTML = text;
    filterBar.style.display = 'flex';
}


// ── TAMPILAN THUMBNAIL / DAFTAR (disimpan di browser; default thumbnail)
function setProdView(mode) {
    document.getElementById('productGrid').classList.toggle('list-view', mode === 'list');
    document.querySelectorAll('.vt-btn').forEach(b => b.classList.toggle('active', b.dataset.view === mode));
    try { localStorage.setItem('prodView', mode); } catch (e) {}
}
(function () { let m = 'grid'; try { m = localStorage.getItem('prodView') === 'list' ? 'list' : 'grid'; } catch (e) {} setProdView(m); })();

// ── HELPERS
function goPage(page) {
    state.page = page;
    fetchProducts();
    window.scrollTo({ top: grid.offsetTop - 120, behavior: 'smooth' });
}

window.resetFilter = function () {
    searchInput.value      = '';
    clearBtn.style.display = 'none';
    state = { q: '', category: '', page: 1 };
    document.querySelectorAll('.category-pill').forEach((p, i) => p.classList.toggle('active', i === 0));
    fetchProducts();
};

function openWA(title) {
    window.open(`https://wa.me/${WA_NUMBER}?text=${encodeURIComponent('Halo, saya tertarik dengan ' + title)}`, '_blank');
}

function debounce(fn, delay) {
    let timer;
    return function (...args) { clearTimeout(timer); timer = setTimeout(() => fn.apply(this, args), delay); };
}

// ── MOBILE NAV

// ── BROWSER BACK/FORWARD
window.addEventListener('popstate', e => {
    if (e.state) {
        state = e.state;
        searchInput.value = state.q || '';
        clearBtn.style.display = state.q ? 'flex' : 'none';
        document.querySelectorAll('.category-pill').forEach(p => {
            p.classList.toggle('active', p.dataset.cat === (state.category || ''));
        });
        fetchProducts(false);
    }
});
