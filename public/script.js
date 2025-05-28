// public/script.js

document.addEventListener('DOMContentLoaded', () => {
    const categoryList = document.getElementById('categoryList');
    const addCategoryForm = document.getElementById('addCategoryForm');
    const categoryNameInput = document.getElementById('categoryName');
    const messageArea = document.getElementById('messageArea');

    // Fungsi untuk menampilkan pesan
    function showMessage(message, type = 'success') {
        messageArea.textContent = message;
        messageArea.className = `message-area ${type}`; // Mengatur kelas untuk styling
        
        // Hapus pesan setelah beberapa detik
        setTimeout(() => {
            messageArea.textContent = '';
            messageArea.className = 'message-area';
        }, 3000);
    }

    // Fungsi untuk mengambil dan menampilkan kategori
    async function fetchAndDisplayCategories() {
        try {
            // Tampilkan placeholder loading
            categoryList.innerHTML = '<li class="loading-placeholder">Memuat kategori...</li>';

            const response = await fetch('http://localhost:8001/api/categories');
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            
            categoryList.innerHTML = ''; // Kosongkan list sebelum mengisi

            if (data.categories && data.categories.length > 0) {
                data.categories.forEach(category => {
                    const listItem = document.createElement('li');
                    listItem.textContent = `${category.name} (ID: ${category.id})`;
                    listItem.dataset.categoryId = category.id; // Menyimpan ID kategori
                    categoryList.appendChild(listItem);
                });
            } else {
                categoryList.innerHTML = '<li class="loading-placeholder">Belum ada kategori.</li>';
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            categoryList.innerHTML = '<li class="loading-placeholder" style="color: red;">Gagal memuat kategori.</li>';
            showMessage(`Error: ${error.message}`, 'error');
        }
    }

    // Event listener untuk form tambah kategori
    if (addCategoryForm) {
        addCategoryForm.addEventListener('submit', async (event) => {
            event.preventDefault(); // Mencegah reload halaman standar form

            const categoryName = categoryNameInput.value.trim();
            if (!categoryName) {
                showMessage('Nama kategori tidak boleh kosong.', 'error');
                return;
            }

            try {
                const response = await fetch('http://localhost:8001/api/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name: categoryName }),
                });

                const result = await response.json(); // Selalu coba parse JSON

                if (!response.ok) {
                    // Tangani error dari server (misalnya, nama duplikat, validasi)
                    throw new Error(result.error || `HTTP error! status: ${response.status}`);
                }
                
                showMessage(result.message || 'Kategori berhasil ditambahkan!', 'success');
                categoryNameInput.value = ''; // Kosongkan input field
                fetchAndDisplayCategories(); // Muat ulang daftar kategori
            
            } catch (error) {
                console.error('Error adding category:', error);
                showMessage(`Error: ${error.message}`, 'error');
            }
        });
    } else {
        console.error("Form dengan ID 'addCategoryForm' tidak ditemukan.");
    }

    // Panggil fungsi untuk memuat kategori saat halaman pertama kali dimuat
    fetchAndDisplayCategories();
});
