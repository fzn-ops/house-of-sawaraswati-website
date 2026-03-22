
let variantCount = 0;
let editMode     = false;
let editCardEl   = null;

// ===== MODAL OPEN (Tambah) =====
function openModal() {
    editMode   = false;
    editCardEl = null;
    document.getElementById('modal-title').textContent  = 'Tambah Produk';
    document.getElementById('btn-submit').textContent   = 'Tambah Produk';
    resetForm(false);
    showOverlay();
}

// ===== MODAL OPEN (Edit) =====
function openEditModal(btn) {
    const card    = btn.closest('.produk-card');
    editMode      = true;
    editCardEl    = card;

    document.getElementById('modal-title').textContent = 'Edit Produk';
    document.getElementById('btn-submit').textContent  = 'Simpan Perubahan';

    // Isi form dari data card
    document.getElementById('input-nama').value      = card.dataset.name   || '';
    document.getElementById('input-harga').value     = card.dataset.price  || '';
    document.getElementById('input-deskripsi').value = card.dataset.desc   || '';
    document.getElementById('input-panduan').value   = card.dataset.panduan|| '';
    document.getElementById('input-kategori').value  = card.dataset.kategori|| 'Gamis';

    // Gambar
    const imgSrc = card.querySelector('img')?.src || '';
    if (imgSrc) {
        document.getElementById('preview-img').src = imgSrc;
        document.getElementById('preview-img').classList.remove('hidden');
        document.getElementById('upload-placeholder').classList.add('hidden');
        document.getElementById('prev-image').src = imgSrc;
        document.getElementById('prev-image').classList.remove('hidden');
        document.getElementById('prev-image-placeholder').classList.add('hidden');
    }

    // Varian
    document.getElementById('variant-list').innerHTML = '';
    variantCount = 0;
    const variants = JSON.parse(card.dataset.variants || '[]');
    if (variants.length) {
        variants.forEach(v => addVariant(v.ukuran, v.stok));
    } else {
        addVariant();
    }

    updatePreview();
    showOverlay();
}

function showOverlay() {
    const overlay = document.getElementById('modal-overlay');
    overlay.classList.remove('hidden');
    overlay.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const overlay = document.getElementById('modal-overlay');
    overlay.classList.add('hidden');
    overlay.classList.remove('flex');
    document.body.style.overflow = '';
    clearErrors();
}

function closeModalOutside(e) {
    if (e.target === document.getElementById('modal-overlay')) closeModal();
}

// ===== FILTER TAB =====
function filterTab(btn, tab) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-rose-500', 'text-white');
        b.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200');
    });
    btn.classList.add('bg-rose-500', 'text-white');
    btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200');

    document.querySelectorAll('.produk-card').forEach(card => {
        const match = tab === 'Semua Produk' || card.dataset.kategori === tab;
        card.classList.toggle('hidden', !match);
    });
}

// ===== PREVIEW IMAGE =====
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    const url = URL.createObjectURL(file);

    document.getElementById('upload-placeholder').classList.add('hidden');
    document.getElementById('preview-img').src = url;
    document.getElementById('preview-img').classList.remove('hidden');
    document.getElementById('prev-image').src = url;
    document.getElementById('prev-image').classList.remove('hidden');
    document.getElementById('prev-image-placeholder').classList.add('hidden');
}

// ===== TAMBAH VARIAN =====
function addVariant(ukuranVal = '', stokVal = '') {
    variantCount++;
    const id   = variantCount;
    const list = document.getElementById('variant-list');

    const row = document.createElement('div');
    row.id        = 'variant-' + id;
    row.className = 'flex items-center gap-2 animate-fade-row';
    row.innerHTML = `
        <div class="flex-1">
            <input type="text" placeholder="Ukuran (cth. Size 1, M, XL)"
                   id="ukuran-${id}" value="${ukuranVal}"
                   oninput="updatePreview(); clearFieldError('ukuran-${id}')"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
        </div>
        <div class="w-24">
            <input type="number" placeholder="Stok" min="0"
                   id="stok-${id}" value="${stokVal}"
                   oninput="updatePreview()"
                   class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 text-center transition-colors">
        </div>
        <button type="button" onclick="removeVariant(${id})"
                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-rose-500 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    list.appendChild(row);
    updatePreview();
}

function removeVariant(id) {
    document.getElementById('variant-' + id)?.remove();
    updatePreview();
}

function getVariants() {
    const variants = [];
    document.querySelectorAll('#variant-list > div').forEach(row => {
        const id     = row.id.replace('variant-', '');
        const ukuran = document.getElementById('ukuran-' + id)?.value.trim();
        const stok   = document.getElementById('stok-' + id)?.value;
        if (ukuran) variants.push({ ukuran, stok: parseInt(stok) || 0 });
    });
    return variants;
}

// ===== UPDATE PREVIEW =====
function updatePreview() {
    const nama      = document.getElementById('input-nama').value      || '–';
    const harga     = document.getElementById('input-harga').value;
    const deskripsi = document.getElementById('input-deskripsi').value || '–';
    const panduan   = document.getElementById('input-panduan').value   || '–';
    const variants  = getVariants();

    document.getElementById('prev-nama').textContent      = nama;
    document.getElementById('prev-deskripsi').textContent = deskripsi;
    document.getElementById('prev-panduan').textContent   = panduan;
    document.getElementById('prev-harga').textContent     = harga
        ? 'Rp' + parseInt(harga).toLocaleString('id-ID') : '–';

    document.getElementById('prev-ukuran').innerHTML = variants.length
        ? variants.map(v => `
            <div class="text-center">
                <span class="inline-block px-3 py-1 text-xs border border-gray-200 rounded text-gray-700">${v.ukuran}</span>
                <p class="text-xs text-gray-400 mt-0.5">Stok: ${v.stok}</p>
            </div>`).join('')
        : '<span class="text-xs text-gray-300">Belum ada ukuran</span>';
}

// ===== VALIDASI & ERROR UI =====
function setFieldError(id, msg) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.add('border-rose-400', 'bg-rose-50');
    el.classList.remove('border-gray-200');

    let errEl = document.getElementById('err-' + id);
    if (!errEl) {
        errEl = document.createElement('p');
        errEl.id        = 'err-' + id;
        errEl.className = 'text-xs text-rose-500 mt-1 flex items-center gap-1';
        el.parentNode.appendChild(errEl);
    }
    errEl.innerHTML = `<svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>${msg}`;
}

function clearFieldError(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.classList.remove('border-rose-400', 'bg-rose-50');
    el.classList.add('border-gray-200');
    document.getElementById('err-' + id)?.remove();
}

function clearErrors() {
    ['input-nama','input-harga','input-deskripsi'].forEach(clearFieldError);
}

function validate() {
    let valid = true;
    const nama    = document.getElementById('input-nama').value.trim();
    const harga   = document.getElementById('input-harga').value;
    const variants = getVariants();

    clearErrors();

    if (!nama) {
        setFieldError('input-nama', 'Nama produk wajib diisi');
        valid = false;
    }
    if (!harga || parseInt(harga) <= 0) {
        setFieldError('input-harga', 'Harga wajib diisi');
        valid = false;
    }
    if (!variants.length) {
        const list = document.getElementById('variant-list');
        let err = document.getElementById('err-variant');
        if (!err) {
            err = document.createElement('p');
            err.id        = 'err-variant';
            err.className = 'text-xs text-rose-500 mt-1 flex items-center gap-1';
            list.parentNode.appendChild(err);
        }
        err.innerHTML = `<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>Tambahkan minimal 1 ukuran`;
        valid = false;
    } else {
        document.getElementById('err-variant')?.remove();
    }

    return valid;
}

// ===== SUBMIT =====
function submitProduk() {
    if (!validate()) return;

    const nama     = document.getElementById('input-nama').value.trim();
    const harga    = document.getElementById('input-harga').value;
    const kategori = document.getElementById('input-kategori').value;
    const deskripsi= document.getElementById('input-deskripsi').value;
    const panduan  = document.getElementById('input-panduan').value;
    const variants = getVariants();
    const imgSrc   = document.getElementById('prev-image').src || '';

    if (editMode && editCardEl) {
        // Update card yang ada
        editCardEl.dataset.name     = nama;
        editCardEl.dataset.price    = harga;
        editCardEl.dataset.kategori = kategori;
        editCardEl.dataset.desc     = deskripsi;
        editCardEl.dataset.panduan  = panduan;
        editCardEl.dataset.variants = JSON.stringify(variants);

        if (imgSrc) editCardEl.querySelector('img').src = imgSrc;
        editCardEl.querySelector('.prod-name').textContent  = nama;
        editCardEl.querySelector('.prod-price').textContent = 'Rp' + parseInt(harga).toLocaleString('id-ID');
    } else {
        // Tambah card baru ke grid
        addCardToGrid({ nama, harga, kategori, imgSrc, variants, deskripsi, panduan });
    }

    closeModal();
    showSuccessToast(editMode ? 'Produk berhasil diperbarui!' : 'Produk berhasil ditambahkan!');
    resetForm(false);
}

// ===== TAMBAH CARD KE GRID =====
function addCardToGrid({ nama, harga, kategori, imgSrc, variants, deskripsi, panduan }) {
    const grid = document.getElementById('produk-grid');
    const id   = Date.now();

    const card = document.createElement('div');
    card.className = 'produk-card bg-white rounded-2xl overflow-hidden border border-gray-100 hover:border-rose-200 transition-all duration-200';
    card.dataset.kategori = kategori;
    card.dataset.name     = nama;
    card.dataset.price    = harga;
    card.dataset.desc     = deskripsi;
    card.dataset.panduan  = panduan;
    card.dataset.variants = JSON.stringify(variants);

    card.innerHTML = `
        <div class="bg-gray-50 overflow-hidden">
            <img src="${imgSrc || ''}" alt="${nama}"
                 class="w-full aspect-square object-cover hover:scale-105 transition-transform duration-300"
                 onerror="this.src=''">
        </div>
        <div class="p-3">
            <p class="prod-name text-sm font-medium text-[#2c2c2c] truncate">${nama}</p>
            <p class="prod-price text-xs text-gray-500 mt-0.5 mb-2">Rp${parseInt(harga).toLocaleString('id-ID')}</p>
            <button onclick="openEditModal(this)"
                    class="w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all">
                Edit Produk
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
            </button>
        </div>`;
    grid.prepend(card);
}

// ===== TOAST SUKSES =====
function showSuccessToast(msg = 'Berhasil!') {
    // Hapus toast lama jika ada
    document.getElementById('success-toast')?.remove();

    const toast = document.createElement('div');
    toast.id        = 'success-toast';
    toast.className = 'fixed top-22 right-2 z-[100] flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-2xl px-6 py-4 transition-all duration-500 opacity-0 -translate-y-4';
    toast.innerHTML = `
        <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-green-500 checkmark" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path class="check-path" stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"
                      style="stroke-dasharray:20;stroke-dashoffset:20;transition:stroke-dashoffset 0.4s ease 0.1s"/>
            </svg>
        </div>
        <div>
            <p class="text-sm font-semibold text-[#2c2c2c]">${msg}</p>
            <p class="text-xs text-gray-400">Perubahan telah disimpan</p>
        </div>`;
    document.body.appendChild(toast);

    // Animasi masuk
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateX(-10%) translateY(0)';
            toast.querySelector('path').style.strokeDashoffset = '0';
            // Animasi centang
            const path = toast.querySelector('.check-path');
            if (path) path.style.strokeDashoffset = '0';
        });
    });

    // Animasi keluar setelah 2.8 detik
    setTimeout(() => {
        toast.style.opacity     = '0';
        toast.style.transform   = 'translateX(-10%) translateY(16px)';
        setTimeout(() => toast.remove(), 500);
    }, 2800);
}

// ===== RESET FORM =====
function resetForm(addDefaultVariant = true) {
    ['input-nama','input-harga','input-deskripsi','input-panduan'].forEach(id => {
        document.getElementById(id).value = '';
        clearFieldError(id);
    });
    document.getElementById('err-variant')?.remove();
    document.getElementById('input-kategori').value = 'Hijab';
    document.getElementById('variant-list').innerHTML = '';
    variantCount = 0;

    // Reset gambar
    document.getElementById('upload-image').value = '';
    document.getElementById('preview-img').classList.add('hidden');
    document.getElementById('preview-img').src = '';
    document.getElementById('upload-placeholder').classList.remove('hidden');
    document.getElementById('prev-image').classList.add('hidden');
    document.getElementById('prev-image-placeholder').classList.remove('hidden');

    if (addDefaultVariant) addVariant();
    updatePreview();
}

// Input listeners untuk clear error on type
document.addEventListener('DOMContentLoaded', () => {
    ['input-nama','input-harga'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', () => clearFieldError(id));
    });
    // Pasang edit handler ke existing cards
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => openEditModal(btn));
    });
    // Tambah varian default saat load
    addVariant();
});