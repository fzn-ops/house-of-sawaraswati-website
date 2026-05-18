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
            <tr class="border-b border-gray-50 dark:border-gray-800 hover:bg-rose-50/30 dark:hover:bg-rose-900/10 transition-colors">
                <td class="px-4 py-3 text-center">
                    <input type="checkbox" class="row-check accent-rose-500 rounded">
                </td>
                <td class="px-4 py-3 text-sm text-charcoal dark:text-gray-100 font-medium">${row.id}</td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">${row.tanggal}</td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 max-w-xs">
                    <p class="truncate" title="${formatProdukText(row.produk)}">${formatProdukText(row.produk)}</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300 whitespace-nowrap">Rp${formatRp(row.total)}</td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass(row.metode)}">
                        ${row.metode}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm">
                    <span class="px-2.5 py-1 rounded-full text-xs font-medium ${statusBadgeClass(row.status)}">
                        ${statusLabel(row.status)}
                    </span>
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="openDetail(${start + i})" title="Detail"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </button>
                        <button onclick="editRow(${start + i})" title="Edit"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-blue-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                            </svg>
                        </button>
                        <button onclick="deleteRow(${start + i})" title="Hapus"
                                class="w-7 h-7 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>`).join('')
        : `<tr><td colspan="8" class="text-center py-12 text-gray-400 dark:text-gray-500 text-sm">Tidak ada data ditemukan</td></tr>`;

    renderPagination();
}

// ===== BADGE =====
function badgeClass(metode) {
    const map = {
        'Transfer':    'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
        'Tunai / COD': 'bg-green-50 dark:bg-green-900/20 text-green-600 dark:text-green-400',
        'QRIS':        'bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400',
    };
    return map[metode] || 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300';
}

function statusBadgeClass(status) {
    const map = {
        'paid':    'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400',
        'pending': 'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
        'failed':  'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400',
        'refunded':'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400',
        'challenge':'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400',
    };
    return map[status] || 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300';
}

function statusLabel(status) {
    const map = {
        'paid':     'Lunas',
        'pending':  'Pending',
        'failed':   'Gagal',
        'refunded': 'Refund',
        'challenge':'Challenge',
    };
    return map[status] || status;
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
    // Always re-apply date range filter first, then extra filters on top
    if (activeDateRange !== 'all') {
        setDateRange(activeDateRange);
    } else {
        filteredData = applyExtraFilters(allData);
        currentPage = 1;
        renderTable();
        updateReportingStats();
    }
}

function toggleFilter() {
    const dropdown = document.getElementById('filter-dropdown');
    const isHidden = dropdown.classList.contains('hidden');
    if (isHidden) {
        dropdown.classList.remove('hidden');
    } else {
        dropdown.classList.add('hidden');
    }
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
                <div class="flex items-center justify-between text-xs bg-gray-50 dark:bg-[#252528] rounded-lg px-3 py-1.5">
                    <span class="text-charcoal dark:text-gray-100 font-medium">${p.nama}</span>
                    <span class="text-gray-400 dark:text-gray-500">${p.ukuran} · x${p.qty}</span>
                </div>`).join('')}
           </div>`
        : `<span class="text-charcoal dark:text-gray-100">${row.produk}</span>`;

    document.getElementById('detail-content').innerHTML = `
        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
            <span class="text-gray-400 dark:text-gray-500">Order ID</span>
            <span class="font-medium text-charcoal dark:text-gray-100">${row.id}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
            <span class="text-gray-400 dark:text-gray-500">Tanggal</span>
            <span class="text-charcoal dark:text-gray-100">${row.tanggal}</span>
        </div>
        <div class="py-2 border-b border-gray-50 dark:border-gray-800">
            <span class="text-gray-400 dark:text-gray-500 block mb-1">Produk</span>
            ${produkHTML}
        </div>
        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
            <span class="text-gray-400 dark:text-gray-500">Total</span>
            <span class="font-semibold text-rose-500">Rp${formatRp(row.total)}</span>
        </div>
        <div class="flex justify-between py-2 border-b border-gray-50 dark:border-gray-800">
            <span class="text-gray-400 dark:text-gray-500">Metode</span>
            <span class="px-2.5 py-1 rounded-full text-xs font-medium ${badgeClass(row.metode)}">${row.metode}</span>
        </div>
        <div class="flex justify-between py-2">
            <span class="text-gray-400 dark:text-gray-500">Status</span>
            <span class="px-2.5 py-1 rounded-full text-xs font-medium ${statusBadgeClass(row.status)}">${statusLabel(row.status)}</span>
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
    const item = filteredData[index];
    if (!item) return;
    confirmDelete({
        title: 'Hapus data?',
        text: `Transaksi ${item.id} akan dihapus dari tabel.`,
    }).then((result) => {
        if (!result.isConfirmed) return;
        allData      = allData.filter(r => r.id !== item.id);
        filteredData = filteredData.filter(r => r.id !== item.id);
        if ((currentPage - 1) * PER_PAGE >= filteredData.length && currentPage > 1) currentPage--;
        renderTable();
        showToast('Data berhasil dihapus!', 'success');
    });
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
               class="flex-1 px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 dark:bg-[#252528] dark:text-gray-100 rounded-xl focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 transition-colors">
        <input type="text" placeholder="Ukuran" value="${ukuran}"
               id="produk-ukuran-${id}"
               class="w-20 px-3 py-2 text-sm border border-gray-200 dark:border-gray-700 dark:bg-[#252528] dark:text-gray-100 rounded-xl focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 transition-colors text-center">
        <input type="number" placeholder="Qty" min="1" value="${qty}"
               id="produk-qty-${id}"
               class="w-14 px-2 py-2 text-sm border border-gray-200 dark:border-gray-700 dark:bg-[#252528] dark:text-gray-100 rounded-xl focus:outline-none focus:border-rose-400 dark:focus:border-rose-500 transition-colors text-center">
        <button type="button" onclick="removeProdukRow(${id})"
                class="w-8 h-8 flex items-center justify-center text-gray-300 dark:text-gray-600 hover:text-rose-500 transition-colors flex-shrink-0">
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
    toast.className = `fixed top-24 right-6 z-[100] flex items-center gap-3 bg-white dark:bg-[#1e1e21] shadow-lg rounded-2xl px-6 py-4 transition-all duration-500 opacity-0 translate-x-16 border ${isError ? 'border-red-200 dark:border-red-800' : 'border-green-200 dark:border-green-800'}`;
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
            <p class="text-sm font-semibold text-[#2c2c2c] dark:text-gray-100">${msg}</p>
            <p class="text-xs ${isError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500'}">${isError ? 'Silakan periksa kembali' : 'Perubahan telah disimpan'}</p>
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

// ===== REPORTING: DATE RANGE FILTER =====
let activeDateRange = 'all';

/**
 * Parse tanggal dari format Indonesia "dd MMMM yyyy HH:mm" ke Date object.
 * Contoh: "14 April 2026 15:30"
 */
function parseIndonesianDate(str) {
    if (!str) return null;
    const months = {
        'januari': 0, 'februari': 1, 'maret': 2, 'april': 3,
        'mei': 4, 'juni': 5, 'juli': 6, 'agustus': 7,
        'september': 8, 'oktober': 9, 'november': 10, 'desember': 11,
        'january': 0, 'february': 1, 'march': 2, 'april': 3,
        'may': 4, 'june': 5, 'july': 6, 'august': 7,
        'september': 8, 'october': 9, 'november': 10, 'december': 11
    };
    const parts = str.trim().split(/\s+/);
    if (parts.length < 3) return null;
    const day   = parseInt(parts[0]);
    const month = months[parts[1].toLowerCase()];
    const year  = parseInt(parts[2]);
    let hour = 0, min = 0;
    if (parts[3] && parts[3].includes(':')) {
        const timeParts = parts[3].split(':');
        hour = parseInt(timeParts[0]) || 0;
        min  = parseInt(timeParts[1]) || 0;
    }
    if (isNaN(day) || month === undefined || isNaN(year)) return null;
    return new Date(year, month, day, hour, min);
}

function setDateRange(range) {
    activeDateRange = range;

    document.querySelectorAll('.date-range-btn').forEach(btn => {
        btn.classList.remove('bg-rose-500', 'text-white');
        btn.classList.add('text-gray-500', 'dark:text-gray-400');
    });
    const buttons = document.querySelectorAll('.date-range-btn');
    const rangeMap = { 'today': 0, '7d': 1, '30d': 2, 'month': 3, 'all': 4 };
    if (rangeMap[range] !== undefined && buttons[rangeMap[range]]) {
        buttons[rangeMap[range]].classList.add('bg-rose-500', 'text-white');
        buttons[rangeMap[range]].classList.remove('text-gray-500', 'dark:text-gray-400');
    }

    const now   = new Date();
    let dateFrom = null;
    let dateTo   = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);

    switch (range) {
        case 'today':
            dateFrom = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 0, 0, 0);
            break;
        case '7d':
            dateFrom = new Date(now);
            dateFrom.setDate(dateFrom.getDate() - 7);
            dateFrom.setHours(0, 0, 0, 0);
            break;
        case '30d':
            dateFrom = new Date(now);
            dateFrom.setDate(dateFrom.getDate() - 30);
            dateFrom.setHours(0, 0, 0, 0);
            break;
        case 'month':
            dateFrom = new Date(now.getFullYear(), now.getMonth(), 1, 0, 0, 0);
            break;
        case 'custom':
            const fromVal = document.getElementById('date-from').value;
            const toVal   = document.getElementById('date-to').value;
            if (fromVal) dateFrom = new Date(fromVal + 'T00:00:00');
            if (toVal)   dateTo   = new Date(toVal + 'T23:59:59');
            if (!fromVal && !toVal) {
                dateFrom = null;
                dateTo = null;
            }
            break;
        case 'all':
        default:
            dateFrom = null;
            dateTo   = null;
            break;
    }

    let dateFiltered;
    if (dateFrom || dateTo) {
        dateFiltered = allData.filter(row => {
            const rowDate = parseIndonesianDate(row.tanggal);
            if (!rowDate) return true;
            if (dateFrom && rowDate < dateFrom) return false;
            if (dateTo && rowDate > dateTo) return false;
            return true;
        });
    } else {
        dateFiltered = [...allData];
    }

    filteredData = applyExtraFilters(dateFiltered);

    currentPage = 1;
    renderTable();
    updateReportingStats();
}

// ===== REPORTING: STATS (hanya hitung transaksi paid/lunas) =====
function updateReportingStats() {
    const allFiltered = filteredData;
    // Statistik hanya dari transaksi yang sudah lunas (paid)
    const paidData = allFiltered.filter(row => row.status === 'paid');

    const count   = paidData.length;
    const revenue = paidData.reduce((sum, row) => sum + Number(row.total), 0);
    const avg     = count > 0 ? Math.round(revenue / count) : 0;

    // Metode terpopuler (dari paid saja)
    const methodCount = {};
    paidData.forEach(row => {
        methodCount[row.metode] = (methodCount[row.metode] || 0) + 1;
    });
    let topMethod = '-';
    let topCount  = 0;
    Object.entries(methodCount).forEach(([method, cnt]) => {
        if (cnt > topCount) { topMethod = method; topCount = cnt; }
    });

    // Hitung pending & failed untuk info tambahan
    const pendingCount = allFiltered.filter(r => r.status === 'pending').length;
    const failedCount  = allFiltered.filter(r => r.status === 'failed').length;

    // Update DOM
    document.getElementById('stat-revenue').textContent    = 'Rp' + formatRp(revenue);
    document.getElementById('stat-count').textContent      = count.toLocaleString('id-ID');
    document.getElementById('stat-avg').textContent        = 'Rp' + formatRp(avg);
    document.getElementById('stat-top-method').textContent = topMethod;

    // Update sub-labels jika ada pending/failed
    const revenueSubEl = document.getElementById('stat-revenue-sub');
    if (revenueSubEl) {
        revenueSubEl.textContent = pendingCount > 0
            ? `${pendingCount} transaksi pending`
            : '';
    }
    const countSubEl = document.getElementById('stat-count-sub');
    if (countSubEl) {
        const parts = [];
        if (pendingCount > 0) parts.push(`${pendingCount} pending`);
        if (failedCount > 0)  parts.push(`${failedCount} gagal`);
        countSubEl.textContent = parts.length > 0
            ? `+ ${parts.join(', ')}`
            : '';
    }

    // Ringkasan per metode (dari paid saja)
    renderMethodSummary(paidData, methodCount, revenue);
}

function renderMethodSummary(data, methodCount, totalRevenue) {
    const container = document.getElementById('method-summary');
    if (!container) return;

    const methods = ['Transfer', 'Tunai / COD', 'QRIS'];
    const colorMap = {
        'Transfer':    { bg: 'bg-blue-50 dark:bg-blue-900/20', text: 'text-blue-600 dark:text-blue-400', bar: 'bg-blue-500' },
        'Tunai / COD': { bg: 'bg-green-50 dark:bg-green-900/20', text: 'text-green-600 dark:text-green-400', bar: 'bg-green-500' },
        'QRIS':        { bg: 'bg-purple-50 dark:bg-purple-900/20', text: 'text-purple-600 dark:text-purple-400', bar: 'bg-purple-500' },

    };

    // Hitung revenue per metode
    const revenueByMethod = {};
    data.forEach(row => {
        revenueByMethod[row.metode] = (revenueByMethod[row.metode] || 0) + Number(row.total);
    });

    container.innerHTML = methods.map(method => {
        const count   = methodCount[method] || 0;
        const rev     = revenueByMethod[method] || 0;
        const percent = totalRevenue > 0 ? Math.round((rev / totalRevenue) * 100) : 0;
        const colors  = colorMap[method] || { bg: 'bg-gray-50 dark:bg-gray-800', text: 'text-gray-600 dark:text-gray-300', bar: 'bg-gray-400' };

        return `
            <div class="${colors.bg} rounded-xl p-3">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-medium ${colors.text}">${method}</span>
                    <span class="text-xs text-gray-400 dark:text-gray-500">${count} trx</span>
                </div>
                <p class="text-sm font-bold text-charcoal dark:text-gray-100 mb-1.5">Rp${formatRp(rev)}</p>
                <div class="w-full h-1.5 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                    <div class="${colors.bar} h-full rounded-full transition-all duration-500" style="width: ${percent}%"></div>
                </div>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">${percent}% dari total</p>
            </div>`;
    }).join('');
}

// ===== REPORTING: EXPORT CSV =====
function exportCSV() {
    const data = filteredData;
    if (data.length === 0) {
        showToast('Tidak ada data untuk di-export!', 'error');
        return;
    }

    // CSV Header
    const headers = ['Order ID', 'Tanggal', 'Produk', 'Total (Rp)', 'Metode Pembayaran', 'Status'];
    const rows = data.map(row => [
        row.id,
        row.tanggal,
        '"' + formatProdukText(row.produk).replace(/"/g, '""') + '"',
        row.total,
        row.metode,
        statusLabel(row.status || 'paid')
    ]);

    // Tambah summary di akhir (hanya hitung transaksi lunas)
    const paidRows     = data.filter(r => r.status === 'paid');
    const totalRevenue = paidRows.reduce((s, r) => s + Number(r.total), 0);
    const pendingRows  = data.filter(r => r.status === 'pending');
    const failedRows   = data.filter(r => r.status === 'failed');
    rows.push([]);
    rows.push(['RINGKASAN']);
    rows.push(['Total Transaksi (Lunas)', paidRows.length]);
    rows.push(['Total Pendapatan (Lunas)', totalRevenue]);
    rows.push(['Rata-rata per Transaksi', paidRows.length > 0 ? Math.round(totalRevenue / paidRows.length) : 0]);
    rows.push(['Transaksi Pending', pendingRows.length]);
    rows.push(['Transaksi Gagal', failedRows.length]);

    const csvContent = [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
    const BOM = '\uFEFF'; // UTF-8 BOM for Excel compatibility
    const blob = new Blob([BOM + csvContent], { type: 'text/csv;charset=utf-8;' });
    const url  = URL.createObjectURL(blob);

    const link = document.createElement('a');
    link.href     = url;
    link.download = `laporan-penjualan-${new Date().toISOString().split('T')[0]}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);

    showToast('Export CSV berhasil!', 'success');
}

// ===== HELPER: apply search + payment + status filters =====
function applyExtraFilters(data) {
    const q      = document.getElementById('search-input')?.value.toLowerCase() || '';
    const metode = document.querySelector('input[name="filter-payment"]:checked')?.value || 'Semua';
    const status = document.querySelector('input[name="filter-status"]:checked')?.value || 'Semua';

    return data.filter(row => {
        const produkText  = formatProdukText(row.produk).toLowerCase();
        const matchSearch = !q
            || row.id.toLowerCase().includes(q)
            || produkText.includes(q)
            || row.metode.toLowerCase().includes(q);
        const matchMetode = metode === 'Semua' || row.metode === metode;
        const matchStatus = status === 'Semua' || row.status === status;
        return matchSearch && matchMetode && matchStatus;
    });
}

// ===== INIT =====
renderTable();
updateReportingStats();