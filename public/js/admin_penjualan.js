// public/js/admin-penjualan.js

// ===== DATA =====
let allData      = window.REAL_DATA || [];
let filteredData = [...allData];
const PER_PAGE   = 10;
let currentPage  = 1;
let editIndex    = null;
let produkRowCount = 0;

// ===== FORMAT PRODUK UNTUK TABEL =====
function formatProdukText(produk) {
    if (!Array.isArray(produk)) return produk;
    return produk.map(p => `${p.nama} - ${p.ukuran} (x${p.qty})`).join(', ');
}

// ===== RENDER TABLE =====
function renderTable() {
    const start = (currentPage - 1) * PER_PAGE;
    const rows  = filteredData.slice(start, start + PER_PAGE);

    document.getElementById('table-body').innerHTML = rows.length
        ? rows.map((row, i) => `
            <tr class="border-b border-gray-50 hover:bg-rose-50/30 transition-colors">
                <td class="px-4 py-3 text-center">
                    <input type="checkbox" class="row-check accent-rose-500 rounded">
                </td>
                <td class="px-4 py-3 text-sm text-charcoal font-medium">${row.id}</td>
                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">${row.tanggal}</td>
                <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                    <p class="truncate" title="${formatProdukText(row.produk)}">${formatProdukText(row.produk)}</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">Rp${formatRp(row.total)}</td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass(row.metode)}">
                        ${row.metode}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="openDetail(${start + i})" title="Detail"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button onclick="editRow(${start + i})" title="Edit"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </button>
                        <button onclick="deleteRow(${start + i})" title="Hapus"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>`).join('')
        : `<tr><td colspan="7" class="text-center py-12 text-gray-400 text-sm">Tidak ada data ditemukan</td></tr>`;

    renderPagination();
}

// ===== BADGE =====
function badgeClass(metode) {
    const map = {
        'Transfer':    'bg-blue-50 text-blue-600',
        'Tunai / COD': 'bg-green-50 text-green-600',
        'QRIS':        'bg-purple-50 text-purple-600',
        'E-Wallet':    'bg-orange-50 text-orange-600',
    };
    return map[metode] || 'bg-gray-100 text-gray-600';
}

// ===== PAGINATION =====
function renderPagination() {
    const total      = filteredData.length;
    const totalPages = Math.ceil(total / PER_PAGE);
    const start      = total > 0 ? (currentPage - 1) * PER_PAGE + 1 : 0;
    const end        = Math.min(currentPage * PER_PAGE, total);

    document.getElementById('pagination-info').textContent =
        total > 0 ? `Menampilkan ${start}–${end} dari ${total} hasil` : 'Tidak ada hasil';

    document.getElementById('btn-prev').disabled = currentPage <= 1;
    document.getElementById('btn-next').disabled = currentPage >= totalPages;

    const container = document.getElementById('page-numbers');
    container.innerHTML = '';

    let pages = [];
    if (totalPages <= 5) {
        pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    } else {
        pages = [1];
        if (currentPage > 3) pages.push('...');
        for (let i = Math.max(2, currentPage - 1); i <= Math.min(totalPages - 1, currentPage + 1); i++) {
            pages.push(i);
        }
        if (currentPage < totalPages - 2) pages.push('...');
        pages.push(totalPages);
    }

    pages.forEach(p => {
        if (p === '...') {
            const span = document.createElement('span');
            span.className   = 'text-white/60 text-sm px-1';
            span.textContent = '...';
            container.appendChild(span);
        } else {
            const btn = document.createElement('button');
            btn.textContent = p;
            btn.className   = `w-8 h-8 rounded-full text-sm font-medium transition-all duration-200 ${
                p === currentPage ? 'bg-white text-rose-500 font-bold shadow' : 'text-white hover:bg-white/20'
            }`;
            btn.onclick = () => goToPage(p);
            container.appendChild(btn);
        }
    });
}

function changePage(dir) {
    const totalPages = Math.ceil(filteredData.length / PER_PAGE);
    currentPage = Math.max(1, Math.min(totalPages, currentPage + dir));
    renderTable();
}

function goToPage(p) {
    currentPage = p;
    renderTable();
}

// ===== FILTER & SEARCH =====
function applyFilter() {
    const q      = document.getElementById('search-input').value.toLowerCase();
    const metode = document.querySelector('input[name="filter-payment"]:checked')?.value || 'Semua';

    filteredData = allData.filter(row => {
        const produkText  = formatProdukText(row.produk).toLowerCase();
        const matchSearch = !q
            || row.id.toLowerCase().includes(q)
            || produkText.includes(q)
            || row.metode.toLowerCase().includes(q);
        const matchMetode = metode === 'Semua' || row.metode === metode;
        return matchSearch && matchMetode;
    });

    currentPage = 1;
    renderTable();
    document.getElementById('filter-dropdown').classList.add('hidden');
}

function toggleFilter() {
    document.getElementById('filter-dropdown').classList.toggle('hidden');
}

document.addEventListener('click', e => {
    if (!e.target.closest('#filter-dropdown') && !e.target.closest('[onclick="toggleFilter()"]')) {
        document.getElementById('filter-dropdown')?.classList.add('hidden');
    }
});

// ===== CHECK ALL =====
function toggleCheckAll(el) {
    document.querySelectorAll('.row-check').forEach(c => c.checked = el.checked);
}

// ===== DETAIL =====
function openDetail(index) {
    const row = filteredData[index];
    if (!row) return;

    const produkHTML = Array.isArray(row.produk)
        ? `<div class="space-y-1 mt-1">
            ${row.produk.map(p => `
                <div class="flex items-center justify-between text-xs bg-gray-50 rounded-lg px-3 py-1.5">
                    <span class="text-charcoal font-medium">${p.nama}</span>
                    <span class="text-gray-400">${p.ukuran} · x${p.qty}</span>
                </div>`).join('')}
           </div>`
        : `<span class="text-charcoal">${row.produk}</span>`;

    document.getElementById('detail-content').innerHTML = `
        <div class="flex justify-between py-2 border-b border-gray-50">
            <span class="text-gray-400">Order ID</span>
            <span class="font-medium text-charcoal">${row.id}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-50">
            <span class="text-gray-400">Tanggal</span>
            <span class="text-charcoal">${row.tanggal}</span>
        </div>
        <div class="py-2 border-b border-gray-50">
            <span class="text-gray-400 block mb-1">Produk</span>
            ${produkHTML}
        </div>
        <div class="flex justify-between py-2 border-b border-gray-50">
            <span class="text-gray-400">Total</span>
            <span class="font-semibold text-rose-500">Rp${formatRp(row.total)}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-400">Metode</span>
            <span class="px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass(row.metode)}">${row.metode}</span>
        </div>`;

    const modal = document.getElementById('detail-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDetailModal() {
    const modal = document.getElementById('detail-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// ===== EDIT =====
function editRow(index) {
    const row = filteredData[index];
    if (!row) return;
    editIndex = index;

    document.getElementById('modal-title').textContent = 'Edit Penjualan';
    document.getElementById('produk-list').innerHTML   = '';
    produkRowCount = 0;

    const produkArr = Array.isArray(row.produk) ? row.produk : [{ nama: row.produk, ukuran: '', qty: 1 }];
    produkArr.forEach(p => addProdukRow(p.nama, p.ukuran, p.qty));

    document.getElementById('t-total').value  = row.total;
    document.getElementById('t-metode').value = row.metode;
    document.getElementById('t-tanggal').value = '';

    const modal = document.getElementById('tambah-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

// ===== HAPUS =====
function deleteRow(index) {
    if (!confirm('Hapus data ini?')) return;
    const item  = filteredData[index];
    allData      = allData.filter(r => r.id !== item.id);
    filteredData = filteredData.filter(r => r.id !== item.id);
    if ((currentPage - 1) * PER_PAGE >= filteredData.length && currentPage > 1) currentPage--;
    renderTable();
    showToast('Data berhasil dihapus!', 'success');
}

// ===== PRODUK ROW =====
function addProdukRow(nama = '', ukuran = '', qty = 1) {
    produkRowCount++;
    const id   = produkRowCount;
    const list = document.getElementById('produk-list');
    const row  = document.createElement('div');
    row.id        = 'produk-row-' + id;
    row.className = 'flex items-center gap-2';
    row.innerHTML = `
        <input type="text" placeholder="Nama produk" value="${nama}"
               id="produk-nama-${id}"
               class="flex-1 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors">
        <input type="text" placeholder="Ukuran" value="${ukuran}"
               id="produk-ukuran-${id}"
               class="w-20 px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors text-center">
        <input type="number" placeholder="Qty" min="1" value="${qty}"
               id="produk-qty-${id}"
               class="w-14 px-2 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-rose-400 transition-colors text-center">
        <button type="button" onclick="removeProdukRow(${id})"
                class="w-8 h-8 flex items-center justify-center text-gray-300 hover:text-rose-500 transition-colors flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>`;
    list.appendChild(row);
}

function removeProdukRow(id) {
    document.getElementById('produk-row-' + id)?.remove();
}

function getProdukList() {
    const rows = [];
    document.querySelectorAll('#produk-list > div').forEach(row => {
        const id    = row.id.replace('produk-row-', '');
        const nama  = document.getElementById('produk-nama-' + id)?.value.trim();
        const ukuran= document.getElementById('produk-ukuran-' + id)?.value.trim();
        const qty   = parseInt(document.getElementById('produk-qty-' + id)?.value) || 1;
        if (nama) rows.push({ nama, ukuran: ukuran || '-', qty });
    });
    return rows;
}

// ===== MODAL TAMBAH =====
function openTambahModal() {
    editIndex = null;
    document.getElementById('modal-title').textContent  = 'Tambah Penjualan';
    document.getElementById('produk-list').innerHTML    = '';
    produkRowCount = 0;
    addProdukRow();
    document.getElementById('t-total').value   = '';
    document.getElementById('t-metode').value  = 'Transfer';
    document.getElementById('t-tanggal').value = new Date().toISOString().split('T')[0];

    const modal = document.getElementById('tambah-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTambahModal() {
    const modal = document.getElementById('tambah-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    editIndex = null;
}

function closeModalOutside(e) {
    if (e.target === document.getElementById('tambah-modal')) closeTambahModal();
}

function submitTambah() {
    const produkList = getProdukList();
    const total      = parseInt(document.getElementById('t-total').value);
    const metode     = document.getElementById('t-metode').value;
    const tanggal    = document.getElementById('t-tanggal').value;

    if (!produkList.length) { showToast('Tambahkan minimal 1 produk!', 'error'); return; }
    if (!total)              { showToast('Total wajib diisi!', 'error'); return; }

    const tgl = tanggal
        ? new Date(tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
        : new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

    if (editIndex !== null) {
        const item      = filteredData[editIndex];
        const realIndex = allData.findIndex(r => r.id === item.id);
        const updated   = { ...item, produk: produkList, total, metode, tanggal: tgl };
        if (realIndex >= 0) allData[realIndex] = updated;
        filteredData[editIndex] = updated;
        showToast('Data berhasil diperbarui!', 'success');
    } else {
        const newRow = {
            id:      '#' + Math.floor(100000 + Math.random() * 900000),
            tanggal: tgl,
            produk:  produkList,
            total,
            metode,
        };
        allData.unshift(newRow);
        filteredData.unshift(newRow);
        currentPage = 1;
        showToast('Penjualan berhasil ditambahkan!', 'success');
    }

    closeTambahModal();
    renderTable();
}

// ===== TOAST =====
function showToast(msg, type = 'success') {
    document.getElementById('toast-notif')?.remove();
    const isError = type === 'error';
    const toast   = document.createElement('div');
    toast.id        = 'toast-notif';
    toast.className = `fixed top-24 right-6 z-[100] flex items-center gap-3 bg-white shadow-lg rounded-2xl px-6 py-4 transition-all duration-500 opacity-0 translate-x-16 border ${isError ? 'border-red-200' : 'border-green-200'}`;
    toast.innerHTML = `
        <div class="w-9 h-9 rounded-full flex items-center justify-center flex-shrink-0 ${isError ? 'bg-red-100' : 'bg-green-100'}">
            ${isError
                ? `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                   </svg>`
                : `<svg class="w-5 h-5 text-green-500" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"
                             style="stroke-dasharray:20;stroke-dashoffset:20;transition:stroke-dashoffset 0.4s ease 0.1s"/>
                   </svg>`
            }
        </div>
        <div>
            <p class="text-sm font-semibold text-[#2c2c2c]">${msg}</p>
            <p class="text-xs ${isError ? 'text-red-400' : 'text-gray-400'}">${isError ? 'Silakan periksa kembali' : 'Perubahan telah disimpan'}</p>
        </div>`;
    document.body.appendChild(toast);
    requestAnimationFrame(() => requestAnimationFrame(() => {
        toast.style.opacity   = '1';
        toast.style.transform = 'translateX(0)';
        const path = toast.querySelector('[style]');
        if (path) path.style.strokeDashoffset = '0';
    }));
    setTimeout(() => {
        toast.style.opacity   = '0';
        toast.style.transform = 'translateX(16px)';
        setTimeout(() => toast.remove(), 500);
    }, 2800);
}

function formatRp(n) {
    return Number(n).toLocaleString('id-ID');
}

// ===== INIT =====
renderTable();