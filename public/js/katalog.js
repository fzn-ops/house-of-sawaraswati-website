// Toggle filter accordion
function toggleFilter(id) {
    const list = document.getElementById('list-' + id);
    const icon = document.getElementById('icon-' + id);
    list.classList.toggle('hidden');
    icon.style.transform = list.classList.contains('hidden') ? 'rotate(180deg)' : 'rotate(0deg)';
}

// Filter & sort produk (frontend filtering)
function filterProducts() {
    const search   = document.getElementById('search-input').value.toLowerCase();
    const kategori = document.querySelector('input[name="kategori"]:checked')?.value || '';
    const stok     = document.querySelector('input[name="stok"]:checked')?.value || '';
    const sort     = document.getElementById('sort-select').value;

    const items = document.querySelectorAll('.product-item');
    let visible = 0;

    items.forEach(item => {
        const name = item.querySelector('h3').textContent.toLowerCase();
        const matchSearch   = name.includes(search);
        const matchKategori = !kategori || name.toLowerCase().includes(kategori.split(' ')[1]?.toLowerCase() || '');
        const matchStok     = !stok || stok === 'Semua'
            || (stok === 'Tersedia' && !item.querySelector('.absolute.top-3'))
            || (stok === 'Habis'    &&  item.querySelector('.absolute.top-3'));

        if (matchSearch && matchKategori && matchStok) {
            item.classList.remove('hidden');
            visible++;
        } else {
            item.classList.add('hidden');
        }
    });

    document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
}

// Realtime search
document.getElementById('search-input').addEventListener('input', filterProducts);