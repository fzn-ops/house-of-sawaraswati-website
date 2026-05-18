// public/js/detail-produk.js

const WA_NUMBER = '6281211882222';
let selectedSize = null;

function selectSize(btn) {
    document.querySelectorAll('.size-btn').forEach(b => {
        b.classList.remove('border-rose-500', 'text-rose-500', 'bg-rose-50');
        b.classList.add('border-gray-300', 'text-charcoal');
    });
    btn.classList.add('border-rose-500', 'text-rose-500', 'bg-rose-50');
    btn.classList.remove('border-gray-300', 'text-charcoal');

    selectedSize = btn.dataset.size || btn.textContent.trim();

    const hint = document.getElementById('size-hint');
    if (hint) hint.classList.add('hidden');
}

function pesanViaWhatsApp(btn) {
    const name = btn.dataset.productName || '';
    const hasSizes = btn.dataset.hasSizes === '1';

    if (hasSizes && !selectedSize) {
        const hint = document.getElementById('size-hint');
        if (hint) {
            hint.classList.remove('hidden');
            hint.classList.add('text-rose-500');
        }
        return;
    }

    const sizePart = selectedSize ? ` ukuran ${selectedSize}` : '';
    const text = `Halo, saya ingin memesan ${name}${sizePart}.`;
    const url = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank', 'noopener');
}