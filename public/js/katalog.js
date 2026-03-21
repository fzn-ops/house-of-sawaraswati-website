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
    const harga    = document.querySelector('input[name="harga"]:checked')?.value || '';
    const sort     = document.getElementById('sort-select').value;

    const grid  = document.getElementById('product-grid');
    const items = Array.from(document.querySelectorAll('.product-item'));
    let visible = 0;

    items.forEach(item => {
        const name  = item.querySelector('h3').textContent.toLowerCase();
        const price = parseInt(item.querySelector('p').textContent.replace(/[^0-9]/g, ''));

        const matchSearch   = name.includes(search);
        const matchKategori = !kategori || name.toLowerCase().includes(kategori.split(' ')[1]?.toLowerCase() || '');
        const matchStok     = !stok || stok === 'Semua'
            || (stok === 'Tersedia' && !item.querySelector('.absolute.top-3'))
            || (stok === 'Habis'    &&  item.querySelector('.absolute.top-3'));
        const matchHarga    = !harga
            || (harga === '0-300k'    && price <= 300000)
            || (harga === '300k-500k' && price > 300000 && price <= 500000)
            || (harga === '500k+'     && price > 500000);

        if (matchSearch && matchKategori && matchStok && matchHarga) {
            item.classList.remove('hidden');
            visible++;
        } else {
            item.classList.add('hidden');
        }
    });

    // Sort
    const visibleItems = items.filter(item => !item.classList.contains('hidden'));

    visibleItems.sort((a, b) => {
        const nameA  = a.querySelector('h3').textContent.toLowerCase();
        const nameB  = b.querySelector('h3').textContent.toLowerCase();
        const priceA = parseInt(a.querySelector('p').textContent.replace(/[^0-9]/g, ''));
        const priceB = parseInt(b.querySelector('p').textContent.replace(/[^0-9]/g, ''));

        if (sort === 'harga-asc')  return priceA - priceB;
        if (sort === 'harga-desc') return priceB - priceA;
        if (sort === 'nama')       return nameA.localeCompare(nameB);
        return 0; // terbaru = urutan asli
    });

    // Re-render urutan di DOM
    visibleItems.forEach(item => grid.appendChild(item));

    document.getElementById('empty-state').classList.toggle('hidden', visible > 0);
}

// Realtime search
document.getElementById('search-input').addEventListener('input', filterProducts);

// Reset filter
function resetFilter() {
    document.querySelectorAll('input[type="radio"]').forEach(r => r.checked = false);
    document.getElementById('search-input').value = '';
    filterProducts();
}