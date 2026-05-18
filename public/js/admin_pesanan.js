// public/js/admin_pesanan.js

let orderItems = {};
const PAJAK_PERSEN = 0.01;

// ===== KODE DISKON STATIS =====
const DISCOUNT_CODES = {
    'DISKON10':  { type: 'percent', value: 10,    label: 'Diskon 10%' },
    'DISKON20':  { type: 'percent', value: 20,    label: 'Diskon 20%' },
    'PROMO50':   { type: 'percent', value: 50,    label: 'Diskon 50%' },
    'HEMAT10K':  { type: 'fixed',   value: 10000, label: 'Potongan Rp10.000' },
    'HEMAT25K':  { type: 'fixed',   value: 25000, label: 'Potongan Rp25.000' },
    'HEMAT50K':  { type: 'fixed',   value: 50000, label: 'Potongan Rp50.000' },
};

let appliedDiscount = null;   // { type, value, label, code }
let currentSubtotal = 0;      // subtotal terakhir (sebelum pajak/diskon)

// ===== SHOW SIZE POPUP =====
function showSizePopup(id) {
    // Tutup semua popup lain dulu
    document.querySelectorAll('[id^="size-popup-"]').forEach(p => p.classList.add('hidden'));
    document.getElementById('size-popup-' + id)?.classList.remove('hidden');
}

function hideSizePopup(id) {
    document.getElementById('size-popup-' + id)?.classList.add('hidden');
}

// Tutup popup kalau klik di luar card
document.addEventListener('click', function(e) {
    if (!e.target.closest('.admin-product-card')) {
        document.querySelectorAll('[id^="size-popup-"]').forEach(p => p.classList.add('hidden'));
    }
});

// ===== PILIH UKURAN =====
function selectSize(id, ukuran, stok) {
    if (stok <= 0) return;

    hideSizePopup(id);

    const card  = document.querySelector(`.admin-product-card[data-id="${id}"]`);
    const name  = card?.dataset.name  || '';
    const price = parseInt(card?.dataset.price) || 0;
    const image = card?.dataset.image || '';

    const key = `${id}_${ukuran}`;

    if (orderItems[key]) {
        if (orderItems[key].qty >= stok) {
            showToast(`Stok ${name} (${ukuran}) hanya ${stok}.`, 'error');
            return;
        }
        orderItems[key].qty += 1;
    } else {
        orderItems[key] = { id, key, name, price, image, ukuran, stok, qty: 1 };
    }

    updateActionButton(id);
    renderOrderSummary();
}

// ===== UPDATE TOMBOL DI CARD =====
function updateActionButton(id) {
    const container = document.getElementById('action-' + id);
    if (!container) return;

    // Ambil semua item dari produk ini
    const items = Object.values(orderItems).filter(i => i.id == id);
    const totalQty = items.reduce((s, i) => s + i.qty, 0);

    if (totalQty === 0) {
        resetActionButton(id);
        return;
    }

    container.innerHTML = `
        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-xl px-3 py-2">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold text-rose-500">Dipilih</span>
                <button onclick="showSizePopup(${id})" class="text-xs text-rose-400 hover:text-rose-600 underline">+ Ukuran</button>
            </div>
            ${items.map(item => `
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs text-gray-600 dark:text-gray-300">${item.ukuran}</span>
                <div class="flex items-center gap-1.5">
                    <button onclick="decreaseQty('${item.key}')"
                            class="w-4 h-4 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-rose-500 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <span class="text-xs font-semibold text-charcoal dark:text-gray-100 w-4 text-center">${item.qty}</span>
                    <button onclick="increaseQty('${item.key}')"
                            class="w-4 h-4 flex items-center justify-center text-gray-400 dark:text-gray-500 hover:text-rose-500 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>`).join('')}
        </div>`;
}

function resetActionButton(id) {
    const container = document.getElementById('action-' + id);
    if (!container) return;
    container.innerHTML = `
        <button onclick="showSizePopup(${id})"
                class="pilih-btn w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 hover:border-rose-400 dark:hover:border-rose-500 hover:text-rose-500 transition-all duration-200">
            Pilih Produk
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
        </button>`;
}

// ===== QTY =====
function decreaseQty(key) {
    if (!orderItems[key]) return;
    orderItems[key].qty -= 1;
    if (orderItems[key].qty <= 0) {
        const id = orderItems[key].id;
        delete orderItems[key];
        // Cek apakah produk ini masih punya item lain
        const remaining = Object.values(orderItems).filter(i => i.id == id);
        if (remaining.length === 0) resetActionButton(id);
        else updateActionButton(id);
    } else {
        updateActionButton(orderItems[key].id);
    }
    renderOrderSummary();
}

function increaseQty(key) {
    if (!orderItems[key]) return;
    const item = orderItems[key];
    if (item.stok !== undefined && item.qty >= item.stok) {
        showToast(`Stok ${item.name} (${item.ukuran}) hanya ${item.stok}.`, 'error');
        return;
    }
    item.qty += 1;
    updateActionButton(item.id);
    renderOrderSummary();
}

// ===== HAPUS DARI RINGKASAN =====
function removeItem(key) {
    const id = orderItems[key]?.id;
    delete orderItems[key];
    if (id !== undefined) {
        const remaining = Object.values(orderItems).filter(i => i.id == id);
        if (remaining.length === 0) resetActionButton(id);
        else updateActionButton(id);
    }
    renderOrderSummary();
}

// ===== RENDER RINGKASAN =====
function renderOrderSummary() {
    const items      = Object.values(orderItems);
    const container  = document.getElementById('order-items');
    const totalLabel = document.getElementById('total-produk-label');
    const totalQty   = items.reduce((s, i) => s + i.qty, 0);

    totalLabel.textContent = `Total Produk (${totalQty})`;

    if (items.length === 0) {
        container.innerHTML = '<p class="text-xs text-gray-400 dark:text-gray-500 text-center py-4">Belum ada produk dipilih</p>';
        updatePayment(0);
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="flex items-center gap-3">
            <img src="${item.image}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" alt="${item.name}">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-charcoal dark:text-gray-100 truncate">${item.name}</p>
                <p class="text-xs text-gray-400 dark:text-gray-500">${item.ukuran} · (Rp${formatRp(item.price)}) x ${item.qty}</p>
            </div>
            <button onclick="removeItem('${item.key}')" class="flex-shrink-0 text-rose-400 hover:text-rose-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>`).join('');

    const subtotal = items.reduce((s, i) => s + i.price * i.qty, 0);
    updatePayment(subtotal);
}

function updatePayment(subtotal) {
    currentSubtotal = subtotal;

    const pajak = subtotal > 0 ? Math.round(subtotal * PAJAK_PERSEN) : 0;

    // Hitung diskon berdasarkan kode yang diterapkan
    let diskon = 0;
    if (appliedDiscount && subtotal > 0) {
        if (appliedDiscount.type === 'percent') {
            diskon = Math.round(subtotal * appliedDiscount.value / 100);
        } else {
            diskon = appliedDiscount.value;
        }
    }

    const total = Math.max(0, subtotal + pajak - diskon);

    document.getElementById('subtotal').textContent    = 'Rp' + formatRp(subtotal);
    document.getElementById('pajak').textContent       = 'Rp' + formatRp(pajak);
    document.getElementById('diskon').textContent      = diskon > 0 ? '- Rp' + formatRp(diskon) : 'Rp0';
    document.getElementById('total-bayar').textContent = 'Rp' + formatRp(total);

    // Update kembalian jika metode tunai aktif
    updateKembalian();
}

// ===== KODE DISKON =====
function applyDiscountCode() {
    const input    = document.getElementById('discount-code-input');
    const feedback = document.getElementById('discount-feedback');
    const code     = (input.value || '').trim().toUpperCase();

    if (!code) {
        clearDiscount();
        feedback.textContent = '';
        feedback.className   = 'text-xs mt-1.5 hidden';
        return;
    }

    const disc = DISCOUNT_CODES[code];
    if (disc) {
        appliedDiscount = { ...disc, code };
        feedback.textContent = '\u2713 ' + disc.label + ' berhasil diterapkan!';
        feedback.className   = 'text-xs mt-1.5 text-green-600';
        input.classList.remove('border-red-300');
        input.classList.add('border-green-400');
    } else {
        appliedDiscount = null;
        feedback.textContent = '\u2717 Kode diskon tidak valid';
        feedback.className   = 'text-xs mt-1.5 text-red-500';
        input.classList.remove('border-green-400');
        input.classList.add('border-red-300');
    }

    // Recalculate
    updatePayment(currentSubtotal);
}

function clearDiscount() {
    appliedDiscount = null;
    const input    = document.getElementById('discount-code-input');
    const feedback = document.getElementById('discount-feedback');
    if (input) {
        input.value = '';
        input.classList.remove('border-green-400', 'border-red-300');
    }
    if (feedback) {
        feedback.textContent = '';
        feedback.className   = 'text-xs mt-1.5 hidden';
    }
}

// ===== INPUT UANG TUNAI & KEMBALIAN =====
function updateKembalian() {
    const cashSection = document.getElementById('cash-input-section');
    const kembalianEl = document.getElementById('kembalian-display');
    const confirmBtn  = document.getElementById('btn-tambah-pesanan');

    // Hanya tampilkan jika metode COD/Tunai dipilih
    if (selectedPayment !== 'cod') {
        if (cashSection)  cashSection.classList.add('hidden');
        if (kembalianEl)  kembalianEl.classList.add('hidden');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
        return;
    }

    if (cashSection) cashSection.classList.remove('hidden');

    const cashInput = document.getElementById('cash-amount-input');
    const uangDiterima = parseInt((cashInput?.value || '').replace(/\D/g, '')) || 0;

    // Hitung total akhir (sama seperti di updatePayment)
    const pajak = currentSubtotal > 0 ? Math.round(currentSubtotal * PAJAK_PERSEN) : 0;
    let diskon = 0;
    if (appliedDiscount && currentSubtotal > 0) {
        if (appliedDiscount.type === 'percent') {
            diskon = Math.round(currentSubtotal * appliedDiscount.value / 100);
        } else {
            diskon = appliedDiscount.value;
        }
    }
    const totalBayar = Math.max(0, currentSubtotal + pajak - diskon);

    if (kembalianEl) kembalianEl.classList.remove('hidden');

    const kembalianValue = document.getElementById('kembalian-value');
    const kembalianWarn  = document.getElementById('kembalian-warning');

    if (uangDiterima === 0 && (!cashInput || cashInput.value === '')) {
        // Belum diisi
        if (kembalianValue) kembalianValue.textContent = 'Rp0';
        if (kembalianWarn)  kembalianWarn.classList.add('hidden');
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
        return;
    }

    const kembalian = uangDiterima - totalBayar;

    if (kembalian < 0) {
        // Uang kurang
        if (kembalianValue) {
            kembalianValue.textContent = '- Rp' + formatRp(Math.abs(kembalian));
            kembalianValue.classList.add('text-red-500');
            kembalianValue.classList.remove('text-green-600');
        }
        if (kembalianWarn) {
            kembalianWarn.textContent = 'Uang tidak mencukupi! Kurang Rp' + formatRp(Math.abs(kembalian));
            kembalianWarn.classList.remove('hidden');
        }
        if (confirmBtn) {
            confirmBtn.disabled = true;
            confirmBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    } else {
        // Uang cukup
        if (kembalianValue) {
            kembalianValue.textContent = 'Rp' + formatRp(kembalian);
            kembalianValue.classList.remove('text-red-500');
            kembalianValue.classList.add('text-green-600');
        }
        if (kembalianWarn) kembalianWarn.classList.add('hidden');
        if (confirmBtn) {
            confirmBtn.disabled = false;
            confirmBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
}

function formatCashInput(input) {
    // Simpan posisi cursor
    let raw = input.value.replace(/\D/g, '');
    if (raw === '') {
        input.value = '';
    } else {
        input.value = parseInt(raw).toLocaleString('id-ID');
    }
    updateKembalian();
}

function formatRp(n) {
    return n.toLocaleString('id-ID');
}

// ===== FILTER TAB =====
function filterTab(btn, tab) {
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.classList.remove('bg-rose-500', 'text-white');
        b.classList.add('bg-white', 'text-gray-500', 'border', 'border-gray-200');
    });
    btn.classList.add('bg-rose-500', 'text-white');
    btn.classList.remove('bg-white', 'text-gray-500', 'border', 'border-gray-200');

    document.querySelectorAll('.admin-product-card').forEach(card => {
        const match = tab === 'Semua Produk' || card.dataset.kategori === tab;
        card.classList.toggle('hidden', !match);
    });
}

// ===== SEARCH =====
document.getElementById('admin-search').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.admin-product-card').forEach(card => {
        card.classList.toggle('hidden', !card.dataset.name.toLowerCase().includes(q));
    });
});

// ===== TAMBAHKAN PESANAN =====
// ===== TOAST =====
function showToast(msg, type = 'success') {
    document.getElementById('success-toast')?.remove();

    const isError = type === 'error';
    const toast = document.createElement('div');
    toast.id        = 'success-toast';
    toast.className = `fixed top-22 right-2 z-[100] flex items-center gap-3 bg-white dark:bg-[#1e1e21] border shadow-lg rounded-2xl px-6 py-4 transition-all duration-500 opacity-0 -translate-y-4 ${isError ? 'border-red-200 dark:border-red-800' : 'border-green-200 dark:border-green-800'}`;
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
            <p class="text-xs ${isError ? 'text-red-400' : 'text-gray-400 dark:text-gray-500'}">${isError ? 'Silakan periksa kembali' : 'Pesanan telah disimpan'}</p>
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

function tambahkanPesanan() {
    const items = Object.values(orderItems);
    if (items.length === 0) {
        showToast('Pilih produk terlebih dahulu!', 'error');
        return;
    }
    if (!selectedPayment) {
        showToast('Pilih metode pembayaran!', 'error');
        return;
    }

    // Format data untuk backend
    const payload = {
        payment_method: selectedPayment,
        items: items.map(i => ({
            product_id: i.id,
            size: i.ukuran,
            quantity: i.qty
        }))
    };

    fetch('/admin/transaksi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(async response => {
        const data = await response.json();
        if (!response.ok) {
            throw new Error(data.error || 'Gagal menyimpan pesanan');
        }
        return data;
    })
    .then(data => {
        if (data.is_cash) {
            showToast('Pesanan berhasil dibuat! (Tunai)', 'success');
            showReceipt(data.order_id);
            resetAllOrderState();
        } else if (data.snap_token) {
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    updatePaymentStatus(data.order_id, 'paid');
                    showToast('Pembayaran berhasil!', 'success');
                    showReceipt(data.order_id);
                    resetAllOrderState();
                },
                onPending: function(result) {
                    console.log('Payment Pending:', result);
                    updatePaymentStatus(data.order_id, 'pending');
                    resetAllOrderState();
                    showToast('Menunggu pembayaran...', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                },
                onError: function(result) {
                    console.error('Payment Error:', result);
                    updatePaymentStatus(data.order_id, 'failed');
                    showToast('Pembayaran gagal!', 'error');
                },
                onClose: function() {
                    console.log('Snap popup closed');
                    showToast('Pembayaran belum selesai', 'error');
                }
            });
        } else {
            showToast('Pesanan berhasil dibuat!', 'success');
            showReceipt(data.order_id);
            resetAllOrderState();
        }
    })
    .catch(error => {
        console.error(error);
        showToast(error.message || 'Gagal memproses pesanan!', 'error');
    });
}

// ===== UPDATE PAYMENT STATUS KE BACKEND =====
function updatePaymentStatus(orderId, status) {
    fetch('/admin/transaksi/update-payment', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ order_id: orderId, status: status })
    })
    .then(res => res.json())
    .then(data => console.log('Payment status updated:', data))
    .catch(err => console.error('Failed to update payment status:', err));
}

// ===== RESET ALL ORDER STATE =====
function resetAllOrderState() {
    orderItems = {};

    // Reset semua tombol card ke kondisi awal
    document.querySelectorAll('[id^="action-"]').forEach(el => {
        const id = parseInt(el.id.replace('action-', ''));
        resetActionButton(id);
    });

    // Reset metode pembayaran
    selectedPayment = '';
    resetAllPaymentButtons();
    const label = document.getElementById('selected-payment-label');
    if (label) label.classList.add('hidden');

    // Reset kode diskon
    clearDiscount();

    // Reset input uang tunai
    const cashInput = document.getElementById('cash-amount-input');
    if (cashInput) cashInput.value = '';
    const cashSection = document.getElementById('cash-input-section');
    if (cashSection) cashSection.classList.add('hidden');
    const kembalianEl = document.getElementById('kembalian-display');
    if (kembalianEl) kembalianEl.classList.add('hidden');

    // Reset ringkasan
    renderOrderSummary();
}

// ===== METODE PEMBAYARAN =====
let selectedPayment = '';

const paymentLabels = {
    'transfer': 'Transfer Bank',
    'cod':      'Tunai / COD',
    'qris':     'QRIS',
};

function resetAllPaymentButtons() {
    document.querySelectorAll('.payment-btn').forEach(b => {
        b.classList.remove('border-rose-500', 'bg-rose-50');
        b.classList.add('border-gray-200');
        const svg = b.querySelector('svg');
        const span = b.querySelector('span');
        if (svg) {
            svg.classList.remove('text-rose-500');
            svg.classList.add('text-gray-400');
        }
        if (span) {
            span.classList.remove('text-rose-500');
            span.classList.add('text-gray-600');
        }
    });
}

function activatePaymentButton(btn) {
    btn.classList.add('border-rose-500', 'bg-rose-50');
    btn.classList.remove('border-gray-200');
    const svg = btn.querySelector('svg');
    const span = btn.querySelector('span');
    if (svg) {
        svg.classList.add('text-rose-500');
        svg.classList.remove('text-gray-400');
    }
    if (span) {
        span.classList.add('text-rose-500');
        span.classList.remove('text-gray-600');
    }
}

function selectPayment(btn, method) {
    // Toggle: jika metode yang sama diklik lagi, deselect
    if (selectedPayment === method) {
        resetAllPaymentButtons();
        selectedPayment = '';
        const label = document.getElementById('selected-payment-label');
        if (label) label.classList.add('hidden');
        updateKembalian();
        return;
    }

    // Reset semua tombol, lalu aktifkan yang dipilih
    resetAllPaymentButtons();
    activatePaymentButton(btn);

    selectedPayment = method;

    const label = document.getElementById('selected-payment-label');
    if (label) {
        label.textContent = '\u2713 ' + (paymentLabels[method] || method) + ' dipilih';
        label.classList.remove('hidden');
    }

    // Reset input tunai saat ganti metode
    const cashInput = document.getElementById('cash-amount-input');
    if (cashInput) cashInput.value = '';

    // Tampilkan/sembunyikan section tunai + update kembalian
    updateKembalian();
}

// ===== STRUK / RECEIPT =====
function showReceipt(orderId) {
    const items = Object.values(orderItems);
    const now = new Date();
    const dateStr = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
        + ' ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });

    const paymentLabels = { 'transfer': 'Transfer Bank', 'cod': 'Tunai / COD', 'qris': 'QRIS' };

    document.getElementById('receipt-date').textContent = dateStr;
    document.getElementById('receipt-order-id').textContent = orderId || '-';
    document.getElementById('receipt-kasir').textContent = document.querySelector('header .text-sm')?.textContent?.trim() || 'Kasir';
    document.getElementById('receipt-metode').textContent = paymentLabels[selectedPayment] || selectedPayment;

    // Items
    const itemsHTML = items.map(item => `
        <div class="flex justify-between text-xs">
            <span class="text-gray-600">${item.name} (${item.ukuran}) x${item.qty}</span>
            <span class="text-charcoal font-medium">Rp${(item.price * item.qty).toLocaleString('id-ID')}</span>
        </div>
    `).join('');
    document.getElementById('receipt-items').innerHTML = itemsHTML;

    // Totals
    const subtotal = items.reduce((sum, i) => sum + (i.price * i.qty), 0);
    const pajak = Math.round(subtotal * PAJAK_PERSEN);
    let diskon = 0;
    if (appliedDiscount) {
        diskon = appliedDiscount.type === 'percent'
            ? Math.round(subtotal * appliedDiscount.value / 100)
            : appliedDiscount.value;
    }
    const total = subtotal + pajak - diskon;

    document.getElementById('receipt-subtotal').textContent = 'Rp' + subtotal.toLocaleString('id-ID');
    document.getElementById('receipt-pajak').textContent = 'Rp' + pajak.toLocaleString('id-ID');

    const diskonRow = document.getElementById('receipt-diskon-row');
    if (diskon > 0) {
        document.getElementById('receipt-diskon').textContent = '-Rp' + diskon.toLocaleString('id-ID');
        diskonRow.classList.remove('hidden');
    } else {
        diskonRow.classList.add('hidden');
    }

    document.getElementById('receipt-total').textContent = 'Rp' + total.toLocaleString('id-ID');

    // Bayar & kembalian (hanya untuk tunai)
    const bayarRow = document.getElementById('receipt-bayar-row');
    const kembalianRow = document.getElementById('receipt-kembalian-row');
    if (selectedPayment === 'cod') {
        const cashInput = document.getElementById('cash-amount-input');
        const cashAmount = parseInt(cashInput?.value.replace(/\D/g, '')) || 0;
        const kembalian = cashAmount - total;
        document.getElementById('receipt-bayar').textContent = 'Rp' + cashAmount.toLocaleString('id-ID');
        document.getElementById('receipt-kembalian').textContent = 'Rp' + Math.max(0, kembalian).toLocaleString('id-ID');
        bayarRow.classList.remove('hidden');
        kembalianRow.classList.remove('hidden');
    } else {
        bayarRow.classList.add('hidden');
        kembalianRow.classList.add('hidden');
    }

    const modal = document.getElementById('receipt-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeReceipt() {
    const modal = document.getElementById('receipt-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    window.location.reload();
}

function printReceipt() {
    const content = document.getElementById('receipt-content').innerHTML;
    const printWindow = window.open('', '_blank', 'width=350,height=600');
    printWindow.document.write(`
        <html>
        <head>
            <title>Struk - House of Saraswati</title>
            <style>
                * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Courier New', monospace; }
                body { padding: 10px; font-size: 12px; width: 280px; }
                .text-center { text-align: center; }
                .text-xs { font-size: 11px; }
                .text-sm { font-size: 12px; }
                .text-base { font-size: 13px; }
                .font-bold { font-weight: bold; }
                .font-medium { font-weight: 600; }
                .text-charcoal { color: #2c2c2c; }
                .text-gray-400, .text-gray-500 { color: #888; }
                .text-gray-600 { color: #555; }
                .mb-1 { margin-bottom: 2px; }
                .mb-2 { margin-bottom: 4px; }
                .mb-3 { margin-bottom: 8px; }
                .mb-4 { margin-bottom: 12px; }
                .mt-1 { margin-top: 2px; }
                .mt-2 { margin-top: 4px; }
                .mt-3 { margin-top: 8px; }
                .my-1 { margin: 2px 0; }
                .p-6 { padding: 15px; }
                .space-y-1 > * + * { margin-top: 2px; }
                .space-y-1\\.5 > * + * { margin-top: 4px; }
                hr { border: none; border-top: 1px dashed #ccc; margin: 8px 0; }
                .flex { display: flex; }
                .justify-between { justify-content: space-between; }
                .hidden { display: none; }
                @media print { body { width: 100%; } }
            </style>
        </head>
        <body>${content}</body>
        </html>
    `);
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => { printWindow.print(); printWindow.close(); }, 300);
}