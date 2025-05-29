document.addEventListener('DOMContentLoaded', () => {
    // Referensi elemen form
    const addArticleForm = document.getElementById('addArticleForm');
    const articleTitleInput = document.getElementById('articleTitle');
    const articleContentInput = document.getElementById('articleContent');
    const articleCategorySelect = document.getElementById('articleCategory');
    const messageArea = document.getElementById('messageArea');
    const saveButton = document.getElementById('saveButton');

    // API URL - PASTIKAN PORT 8001 SUDAH BENAR!
    const API_URL = 'http://localhost:8001/api';

    // Fungsi untuk menampilkan pesan
    function showMessage(message, type = 'success') {
        messageArea.textContent = message;
        messageArea.className = `message-area ${type}`;
        setTimeout(() => {
            messageArea.textContent = '';
            messageArea.className = 'message-area';
        }, 4000); // Pesan tampil lebih lama
    }

    // Fungsi untuk mengambil dan mengisi dropdown kategori
    async function populateCategoriesDropdown() {
        articleCategorySelect.innerHTML = '<option value="">Memuat kategori...</option>';
        articleCategorySelect.disabled = true;
        saveButton.disabled = true;

        try {
            const response = await fetch(`${API_URL}/categories`);
            if (!response.ok) {
                throw new Error('Gagal memuat kategori.');
            }
            const data = await response.json();

            if (data.categories && data.categories.length > 0) {
                articleCategorySelect.innerHTML = '<option value="">-- Pilih Kategori --</option>'; // Opsi default
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    articleCategorySelect.appendChild(option);
                });
                articleCategorySelect.disabled = false;
                saveButton.disabled = false;
            } else {
                articleCategorySelect.innerHTML = '<option value="">Tidak ada kategori, tambah dulu!</option>';
            }
        } catch (error) {
            console.error('Error fetching categories:', error);
            articleCategorySelect.innerHTML = `<option value="">Error: ${error.message}</option>`;
            showMessage(`Error memuat kategori: ${error.message}`, 'error');
        }
    }

    // Panggil fungsi untuk mengisi dropdown saat halaman dimuat
    populateCategoriesDropdown();

    // Event listener untuk form submit
    addArticleForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Mencegah reload

        const title = articleTitleInput.value.trim();
        const content = articleContentInput.value.trim();
        const category_id = articleCategorySelect.value;

        if (!title || !content || !category_id) {
            showMessage('Semua field harus diisi!', 'error');
            return;
        }

        saveButton.disabled = true;
        saveButton.textContent = 'Menyimpan...';
        showMessage('', ''); // Hapus pesan lama

        try {
            const response = await fetch(`${API_URL}/articles`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    title: title,
                    content: content,
                    category_id: category_id,
                }),
            });

            const result = await response.json();

            if (!response.ok) {
                // Tangani error, termasuk validasi dari Lumen
                const errorMsg = result.error || (result.errors ? Object.values(result.errors)[0][0] : `Error: ${response.status}`);
                throw new Error(errorMsg);
            }

            showMessage('Artikel berhasil disimpan!', 'success');
            // Kosongkan form
            addArticleForm.reset(); 
            // Pilih ulang opsi default dropdown
            articleCategorySelect.value = ""; 

        } catch (error) {
            console.error('Error adding article:', error);
            showMessage(`Error: ${error.message}`, 'error');
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = 'Simpan Artikel';
        }
    });
});