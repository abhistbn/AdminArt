class ArticleManager {
    constructor() {
        this.articles = [];
        this.categories = []; // Masih digunakan jika modal "Add" tetap ada
        // this.editingId = null; // Tidak lagi dikelola utama di sini untuk edit via tabel
        this.apiBaseUrl = 'http://localhost:8001/api';

        // Elemen DOM untuk modal (jika masih dipakai untuk Tambah Artikel dari halaman ini)
        this.modal = document.getElementById('articleModal');
        this.modalTitle = document.getElementById('modalTitle');
        this.articleForm = document.getElementById('articleFormModal');
        this.articleIdInput = document.getElementById('articleId'); 
        this.titleInput = document.getElementById('modalTitleInput');
        this.categorySelect = document.getElementById('modalCategorySelect');
        this.contentInput = document.getElementById('modalContentTextarea');
        
        this.articleTableBody = document.getElementById('articleTableBody');
        this.loadingIndicator = document.getElementById('loadingIndicator');
        this.apiStatusDiv = document.getElementById('apiStatus');
        this.notificationContainer = document.getElementById('notificationContainer');

        // Tombol
        this.addNewArticleBtn = document.getElementById('addNewArticleBtn'); // Tombol "Add New Article"
        this.refreshArticlesBtn = document.getElementById('refreshArticlesBtn');

        if (!this.articleTableBody) {
            console.error("Elemen articleTableBody tidak ditemukan.");
            return; 
        }
        this.init();
    }

    init() {
        this.bindGlobalEventListeners();
        // Muat kategori jika modal untuk Tambah masih digunakan
        if (this.modal && this.categorySelect) { 
            this.loadCategoriesForModal(); 
        }
        this.loadArticles();
        this.checkApiStatus();
        setInterval(() => this.checkApiStatus(), 30000);
    }

    bindGlobalEventListeners() {
        // Tombol "Add New Article" diubah di HTML menjadi link ke /admin/articles/create
        // Jadi event listener di sini tidak lagi membuka modal untuk add.
        // Jika Anda ingin tombol ini tetap membuka modal dari halaman ini:
        if (this.addNewArticleBtn && this.modal) { // Pastikan modalnya ada
             this.addNewArticleBtn.addEventListener('click', (event) => {
                event.preventDefault(); // Mencegah perilaku default jika ini adalah link
                this.showModal(); // Buka modal untuk artikel baru
             });
        }

        if (this.refreshArticlesBtn) {
            this.refreshArticlesBtn.addEventListener('click', () => this.loadArticles());
        }

        // Form submit di modal hanya untuk TAMBAH ARTIKEL BARU
        if (this.articleForm) {
            this.articleForm.addEventListener('submit', (e) => this.handleSubmitForModal(e));
        }
        
        if (this.modal) {
            this.modal.addEventListener('click', (e) => {
                if (e.target === this.modal) {
                    this.hideModal();
                }
            });
            const closeButton = this.modal.querySelector('.close-button');
            if(closeButton) {
                closeButton.addEventListener('click', () => this.hideModal());
            }
            // Menyesuaikan selector untuk tombol cancel di modal
            const cancelButton = this.articleForm ? this.articleForm.querySelector('button.btn-secondary') : null;
            if(cancelButton) { 
                cancelButton.addEventListener('click', (e) => {
                    e.preventDefault(); 
                    this.hideModal();
                });
            }
        }
    }

    async checkApiStatus() { 
        if (!this.apiStatusDiv) return;
        try {
            await fetch(`${this.apiBaseUrl}/categories`);
            this.apiStatusDiv.className = 'api-status connected';
            this.apiStatusDiv.textContent = '● API Connected';
        } catch (error) {
            this.apiStatusDiv.className = 'api-status disconnected';
            this.apiStatusDiv.textContent = '● API Disconnected';
        }
    }
    
    showLoading(show) { 
        if (this.loadingIndicator) {
            this.loadingIndicator.style.display = show ? 'block' : 'none';
        }
        const tableContainer = document.querySelector('.table-container');
        if (tableContainer) {
            tableContainer.style.opacity = show ? '0.5' : '1';
        }
    }

    async loadCategoriesForModal() {
        if (!this.categorySelect) return;
        this.categorySelect.innerHTML = '<option value="">Memuat kategori...</option>';
        this.categorySelect.disabled = true;
        try {
            const response = await fetch(`${this.apiBaseUrl}/categories`);
            const data = await response.json();

            if (!response.ok || !data.categories) {
                throw new Error(data.error || 'Gagal memuat daftar kategori.');
            }
            this.categories = data.categories || []; 
            
            if (this.categories.length > 0) {
                this.categorySelect.innerHTML = '<option value="">-- Pilih Kategori --</option>';
                this.categories.forEach(cat => {
                    const opt = document.createElement("option");
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    this.categorySelect.appendChild(opt);
                });
                this.categorySelect.disabled = false;
            } else {
                this.categorySelect.innerHTML = '<option value="">Tidak ada kategori. Tambah dulu!</option>';
            }
        } catch (error) {
            console.error('Error loading categories for modal:', error);
            this.categorySelect.innerHTML = '<option value="">Gagal memuat kategori.</option>';
            this.showNotification(`Error memuat kategori untuk modal: ${error.message}`, 'error');
        }
    }

    async loadArticles() { 
        this.showLoading(true);
        try {
            const response = await fetch(`${this.apiBaseUrl}/articles`);
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.error || `Gagal memuat artikel: Status ${response.status}`);
            }
            const data = await response.json();
            this.articles = data.articles || [];
            this.renderArticles();
        } catch (error) {
            console.error('Error loading articles:', error);
            if (this.articleTableBody) {
                this.articleTableBody.innerHTML = `<tr><td colspan="5" class="error-message">Error: ${error.message}</td></tr>`;
            }
            this.showNotification(`Error memuat artikel: ${error.message}`, 'error');
        } finally {
            this.showLoading(false);
        }
    }

    renderArticles() { // PERUBAHAN UTAMA DI SINI UNTUK TOMBOL EDIT
        if (!this.articleTableBody) return;
        this.articleTableBody.innerHTML = ''; 
        if (this.articles.length === 0) {
            this.articleTableBody.innerHTML = `<tr><td colspan="5" style="text-align:center;">Tidak ada artikel. Klik "Add New Article" untuk menambah.</td></tr>`;
            return;
        }

        this.articles.forEach((article, index) => {
            const row = this.articleTableBody.insertRow();
            const categoryName = article.category_name || 'Tanpa Kategori';
            const categoryClass = categoryName.toLowerCase().replace(/\s+/g, '-') || 'other';
            const contentPreview = this.truncateText(article.content || '', 50);

            // Tombol Edit SEKARANG menjadi link ke halaman edit Blade
            const editLink = `/admin/articles/${article.id}/edit`; 

            row.innerHTML = `
                <td class="row-number">${index + 1}</td>
                <td class="article-title">${this.escapeHtml(article.title)}</td>
                <td><span class="category-badge category-${this.escapeHtml(categoryClass)}">${this.escapeHtml(categoryName)}</span></td>
                <td class="content-preview">${this.escapeHtml(contentPreview)}</td>
                <td>
                    <div class="actions">
                        <a href="${editLink}" class="btn btn-edit">Edit</a> 
                        <button class="btn btn-delete" onclick="articleManager.deleteArticle(${article.id}, '${this.escapeHtml(article.title)}')">Delete</button>
                    </div>
                </td>
            `;
        });
    }

    // showModal() hanya untuk Tambah Artikel Baru (jika tombol Add New Article di HTML memanggil ini)
    showModal() { 
        if (!this.modal || !this.articleForm || !this.titleInput || !this.categorySelect || !this.contentInput) {
            this.showNotification("Komponen modal tidak siap untuk menambah artikel.", "error");
            return;
        }
        this.articleForm.reset(); 
        this.editingId = null; // Pastikan ini null untuk mode Add
        if (this.articleIdInput) this.articleIdInput.value = ''; 

        this.modalTitle.textContent = 'Add New Article'; 
        
        if (this.categories.length > 0) { // Jika kategori sudah ada
            this.categorySelect.disabled = false;
            this.categorySelect.value = ""; 
        } else if (!this.categorySelect.disabled) { // Jika kategori belum dimuat, coba muat
             this.loadCategoriesForModal();
        }
        
        this.modal.style.display = 'block'; 
        this.titleInput.focus();
    }

    hideModal() { 
        if (!this.modal) return;
        this.modal.style.display = 'none';
        this.editingId = null; 
        if (this.articleForm) this.articleForm.reset();
    }

    // handleSubmitForModal() sekarang HANYA untuk Tambah Artikel Baru via Modal
    async handleSubmitForModal(e) {
        e.preventDefault();
        
        if (this.editingId) {
            // Fungsi ini seharusnya tidak lagi menangani edit jika edit via halaman Blade
            console.error("handleSubmitForModal dipanggil dalam mode edit, seharusnya tidak terjadi.");
            this.hideModal();
            return;
        }

        const title = this.titleInput.value.trim();
        const content = this.contentInput.value.trim();
        const category_id = this.categorySelect.value;

        if (!title || !content || !category_id) {
            this.showNotification('Error: Judul, Konten, dan Kategori harus diisi!', 'error');
            return;
        }

        const articleData = {
            title,
            content,
            category_id: parseInt(category_id, 10)
        };

        const url = `${this.apiBaseUrl}/articles`; // Selalu POST untuk add dari modal
        const method = 'POST';
        
        const submitButton = this.articleForm.querySelector('button[type="submit"]');
        const originalButtonText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.textContent = 'Menyimpan...';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(articleData)
            });

            const result = await response.json();

            if (!response.ok) {
                 const errorMsg = result.error || (result.errors ? Object.values(result.errors).flat().join('; ') : `Error: ${response.status}`);
                throw new Error(errorMsg);
            }

            this.showNotification(result.message || 'Artikel berhasil ditambahkan!', 'success');
            this.hideModal();
            this.loadArticles(); 
        } catch (error) {
            console.error('Error submitting new article (modal):', error);
            this.showNotification(`Error saat menyimpan: ${error.message}`, 'error');
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = originalButtonText; // Kembalikan teks asli tombol
        }
    }

    // Method editArticle(id) yang lama (menampilkan modal) tidak lagi dipanggil dari tabel.
    // Dihapus karena tombol edit di tabel sekarang berupa link.
    
    async deleteArticle(id, title = "ini") { 
        if (!confirm(`Apakah Anda yakin ingin menghapus artikel "${this.escapeHtml(title)}"?`)) {
            return;
        }
        try {
            const response = await fetch(`${this.apiBaseUrl}/articles/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.error || `Gagal menghapus artikel: Status ${response.status}`);
            }
            this.showNotification(result.message || 'Artikel berhasil dihapus!', 'success');
            this.loadArticles();
        } catch (error) {
            console.error('Error deleting article:', error);
            this.showNotification(`Error: ${error.message}`, 'error');
        }
    }

    showNotification(message, type = 'success') {
        // ... (kode showNotification sama seperti sebelumnya) ...
        const notification = document.createElement('div');
        notification.className = `notification ${type}`; 
        notification.textContent = message;
        const container = this.notificationContainer || document.body;
        if (document.body.contains(container)) {
             container.appendChild(notification);
        } else {
            document.body.appendChild(notification);
        }
        setTimeout(() => {
            notification.classList.add('slideOut'); 
            setTimeout(() => {
                if (container.contains(notification)) {
                    container.removeChild(notification);
                }
            }, 500); 
        }, 3000); 
    }

    escapeHtml(text) {
        // ... (kode escapeHtml sama seperti sebelumnya) ...
        if (text === null || typeof text === 'undefined') return '';
        const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    truncateText(text, length) {
        // ... (kode truncateText sama seperti sebelumnya) ...
        if (!text) return '';
        const sub = text.length > length ? text.substring(0, length) + '...' : text;
        return this.escapeHtml(sub);
    }
}