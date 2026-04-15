// public/js/admin_pesanan.js

let orderItems = {};
const PAJAK_PERSEN = 0.01;
const DISKON = 100000;

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

    // Key unik per produk+ukuran
    const key = `${id}_${ukuran}`;

    if (orderItems[key]) {
        orderItems[key].qty += 1;
    } else {
        orderItems[key] = { id, key, name, price, image, ukuran, qty: 1 };
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
        <div class="bg-rose-50 rounded-xl px-3 py-2">
            <div class="flex items-center justify-between mb-1.5">
                <span class="text-xs font-semibold text-rose-500">Dipilih</span>
                <button onclick="showSizePopup(${id})" class="text-xs text-rose-400 hover:text-rose-600 underline">+ Ukuran</button>
            </div>
            ${items.map(item => `
            <div class="flex items-center justify-between mb-1">
                <span class="text-xs text-gray-600">${item.ukuran}</span>
                <div class="flex items-center gap-1.5">
                    <button onclick="decreaseQty('${item.key}')"
                            class="w-4 h-4 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-colors">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <span class="text-xs font-semibold text-charcoal w-4 text-center">${item.qty}</span>
                    <button onclick="increaseQty('${item.key}')"
                            class="w-4 h-4 flex items-center justify-center text-gray-400 hover:text-rose-500 transition-colors">
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
                class="pilih-btn w-full flex items-center justify-between px-3 py-1.5 rounded-full border border-gray-200 text-xs text-gray-500 hover:border-rose-400 hover:text-rose-500 transition-all duration-200">
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
    orderItems[key].qty += 1;
    updateActionButton(orderItems[key].id);
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
        container.innerHTML = '<p class="text-xs text-gray-400 text-center py-4">Belum ada produk dipilih</p>';
        updatePayment(0);
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="flex items-center gap-3">
            <img src="${item.image}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" alt="${item.name}">
            <div class="flex-1 min-w-0">
                <p class="text-xs font-medium text-charcoal truncate">${item.name}</p>
                <p class="text-xs text-gray-400">${item.ukuran} · (Rp${formatRp(item.price)}) x ${item.qty}</p>
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
    const pajak  = subtotal > 0 ? Math.round(subtotal * PAJAK_PERSEN) : 0;
    const diskon = subtotal > 0 ? DISKON : 0;
    const total  = subtotal + pajak - diskon;

    document.getElementById('subtotal').textContent    = 'Rp' + formatRp(subtotal);
    document.getElementById('pajak').textContent       = 'Rp' + formatRp(pajak);
    document.getElementById('diskon').textContent      = 'Rp' + formatRp(diskon);
    document.getElementById('total-bayar').textContent = 'Rp.' + formatRp(Math.max(0, total));
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
    toast.className = `fixed top-22 right-2 z-[100] flex items-center gap-3 bg-white border border-green-200 shadow-lg rounded-2xl px-6 py-4 transition-all duration-500 opacity-0 -translate-y-4 ${isError ? 'border-red-200' : 'border-green-200'}`;
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
            <p class="text-xs ${isError ? 'text-red-400' : 'text-gray-400'}">${isError ? 'Silakan periksa kembali' : 'Pesanan telah disimpan'}</p>
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
            // Pembayaran tunai — langsung sukses
            resetAllOrderState();
            showToast('Pesanan berhasil dibuat! (Tunai)', 'success');
            setTimeout(() => window.location.reload(), 1500);
        } else if (data.snap_token) {
            // Pembayaran online — buka Midtrans Snap
            window.snap.pay(data.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment Success:', result);
                    updatePaymentStatus(data.order_id, 'paid');
                    resetAllOrderState();
                    showToast('Pembayaran berhasil!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
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
            resetAllOrderState();
            showToast('Pesanan berhasil dibuat!', 'success');
            setTimeout(() => window.location.reload(), 1500);
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
    document.querySelectorAll('.payment-btn').forEach(b => {
        b.classList.remove('border-rose-500', 'bg-rose-50');
        b.classList.add('border-gray-200');
        b.querySelector('svg').classList.remove('text-rose-500');
        b.querySelector('svg').classList.add('text-gray-400');
        b.querySelector('span').classList.remove('text-rose-500');
        b.querySelector('span').classList.add('text-gray-600');
    });
    document.getElementById('selected-payment-label')?.classList.add('hidden');

    // Reset ringkasan
    renderOrderSummary();
}

// ===== METODE PEMBAYARAN =====
let selectedPayment = '';

function selectPayment(btn, method) {
    // Reset semua tombol
    document.querySelectorAll('.payment-btn').forEach(b => {
        b.classList.remove('border-rose-500', 'bg-rose-50');
        b.classList.add('border-gray-200');
        b.querySelector('svg').classList.remove('text-rose-500');
        b.querySelector('svg').classList.add('text-gray-400');
        b.querySelector('span').classList.remove('text-rose-500');
        b.querySelector('span').classList.add('text-gray-600');
    });

    // Aktifkan tombol yang dipilih
    btn.classList.add('border-rose-500', 'bg-rose-50');
    btn.classList.remove('border-gray-200');
    btn.querySelector('svg').classList.add('text-rose-500');
    btn.querySelector('svg').classList.remove('text-gray-400');
    btn.querySelector('span').classList.add('text-rose-500');
    btn.querySelector('span').classList.remove('text-gray-600');

    selectedPayment = method;

    const labels = {
        'transfer': 'Transfer Bank',
        'cod':      'Tunai / COD',
        'qris':     'QRIS',
        'ewallet':  'E-Wallet',
    };
    const label = document.getElementById('selected-payment-label');
    label.textContent = '✓ ' + labels[method] + ' dipilih';
    label.classList.remove('hidden');
}