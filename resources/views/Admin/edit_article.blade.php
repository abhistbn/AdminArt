<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel - Admin</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <a href="#" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Kembali ke Daftar Artikel
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button onclick="previewArticle()" class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                            <i class="fas fa-eye mr-2"></i>
                            Preview
                        </button>
                    </div>
                </div>
            </div>

            <!-- Form Edit Artikel -->
            <form id="editArticleForm" action="#" method="POST">
                <!-- CSRF Token (untuk Laravel) -->
                <!-- <input type="hidden" name="_token" value=""> -->
                <input type="hidden" name="_method" value="PUT">

                <!-- Informasi Artikel -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <h2 class="text-xl font-semibold text-gray-900">Edit Artikel</h2>
                        <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">
                            ID: 1
                        </span>
                    </div>

                    <!-- Alert Message -->
                    <div id="alertMessage" class="hidden mb-4 p-4 rounded-lg"></div>

                    <!-- Title -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Artikel *
                        </label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="Panduan Lengkap React untuk Pemula"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan judul artikel"
                        >
                    </div>

                    <!-- Excerpt -->
                    <div class="mb-6">
                        <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                            Ringkasan Artikel
                        </label>
                        <textarea
                            id="excerpt"
                            name="excerpt"
                            rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ringkasan singkat artikel"
                        >Pelajari konsep-konsep dasar React JavaScript library untuk membangun user interface yang interaktif dan modern.</textarea>
                    </div>

                    <!-- Content -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Konten Artikel *
                        </label>
                        <textarea
                            id="content"
                            name="content"
                            required
                            rows="15"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                            placeholder="Tulis konten artikel di sini..."
                        >React adalah library JavaScript yang dikembangkan oleh Facebook untuk membangun user interface yang interaktif dan dinamis. Dalam artikel ini, kita akan membahas konsep-konsep dasar React yang perlu dipahami oleh pemula.

## Apa itu React?

React adalah library JavaScript yang fokus pada pembuatan komponen UI yang dapat digunakan kembali. React menggunakan konsep Virtual DOM yang membuat aplikasi menjadi lebih cepat dan efisien.

## Konsep Dasar React

### 1. Components
Components adalah blok bangunan utama dalam React. Setiap component adalah fungsi JavaScript yang mengembalikan JSX.

### 2. Props
Props adalah cara untuk mengirim data dari parent component ke child component.

### 3. State
State adalah data yang dapat berubah dalam component dan mempengaruhi rendering.

## Kesimpulan

React adalah tool yang powerful untuk membangun aplikasi web modern. Dengan memahami konsep-konsep dasar ini, Anda sudah siap untuk mulai membangun aplikasi React pertama Anda.</textarea>
                    </div>
                </div>

                <!-- Metadata Artikel -->
                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-tags mr-2"></i>
                        Metadata Artikel
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Author -->
                        <div>
                            <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1"></i>
                                Penulis *
                            </label>
                            <input
                                type="text"
                                id="author"
                                name="author"
                                value="Ahmad Fadhil"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Nama penulis"
                            >
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                Kategori *
                            </label>
                            <select
                                id="category"
                                name="category"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="">Pilih Kategori</option>
                                <option value="Programming" selected>Programming</option>
                                <option value="Design">Design</option>
                                <option value="Technology">Technology</option>
                                <option value="Business">Business</option>
                                <option value="Lifestyle">Lifestyle</option>
                            </select>
                        </div>

                        <!-- Tags -->
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                                Tags
                            </label>
                            <input
                                type="text"
                                id="tags"
                                name="tags"
                                value="React, JavaScript, Frontend, Tutorial"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Tag1, Tag2, Tag3"
                            >
                            <p class="text-xs text-gray-500 mt-1">Pisahkan tags dengan koma</p>
                        </div>

                        <!-- Published Date -->
                        <div>
                            <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1"></i>
                                Tanggal Publikasi
                            </label>
                            <input
                                type="date"
                                id="published_at"
                                name="published_at"
                                value="2024-03-15"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>

                        <!-- Featured Image -->
                        <div class="md:col-span-2">
                            <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                URL Gambar Utama
                            </label>
                            <input
                                type="url"
                                id="featured_image"
                                name="featured_image"
                                value="https://via.placeholder.com/800x400/3b82f6/ffffff?text=React+Tutorial"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="https://example.com/image.jpg"
                            >
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status Artikel *
                            </label>
                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                                <option value="draft">Draft</option>
                                <option value="published" selected>Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Terakhir diupdate: <span id="lastUpdated"></span>
                        </div>
                        <button
                            type="submit"
                            id="submitBtn"
                            class="flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            <i class="fas fa-save mr-2"></i>
                            <span id="submitText">Update Artikel</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Set last updated time
        document.getElementById('lastUpdated').textContent = new Date().toLocaleString('id-ID');

        // Handle form submission
        document.getElementById('editArticleForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const alertMessage = document.getElementById('alertMessage');
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitText.textContent = 'Menyimpan...';
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                alertMessage.className = 'mb-4 p-4 rounded-lg bg-green-50 text-green-800 border border-green-200';
                alertMessage.textContent = 'Artikel berhasil diupdate!';
                alertMessage.classList.remove('hidden');
                
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitText.textContent = 'Update Artikel';
                
                // Update last modified time
                document.getElementById('lastUpdated').textContent = new Date().toLocaleString('id-ID');
                
                // Hide message after 3 seconds
                setTimeout(() => {
                    alertMessage.classList.add('hidden');
                }, 3000);
                
                // Here you would normally send the data to your Lumen API
                console.log('Form data would be sent to API:', new FormData(this));
            }, 1000);
        });

        // Preview function
        function previewArticle() {
            alert('Fitur preview akan membuka tab baru dengan tampilan artikel');
            // You can implement actual preview functionality here
        }

        // Auto-save functionality (optional)
        let autoSaveTimer;
        const formInputs = document.querySelectorAll('input, textarea, select');
        
        formInputs.forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    console.log('Auto-saving draft...');
                    // Implement auto-save to API here
                }, 2000);
            });
        });
    </script>
</body>
</html>