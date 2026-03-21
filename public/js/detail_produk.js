// public/js/detail-produk.js

function selectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b => {
        b.classList.remove('border-rose-500', 'text-rose-500', 'bg-rose-50');
        b.classList.add('border-gray-300', 'text-charcoal');
    });
    btn.classList.add('border-rose-500', 'text-rose-500', 'bg-rose-50');
    btn.classList.remove('border-gray-300', 'text-charcoal');
}