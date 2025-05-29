<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel: {{ $article->title ?? 'Artikel' }} - AdminArt</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Style untuk pesan error dan sukses */
        .message-feedback { padding: 1rem; margin-bottom: 1rem; border-radius: 0.375rem; border: 1px solid transparent; }
        .success-message { background-color: #d1fae5; color: #065f46; border-color: #6ee7b7;}
        .error-message-list { background-color: #fee2e2; color: #991b1b; border-color: #fca5a5;}
        .error-message-list ul { list-style-position: inside; margin-top: 0.5rem; }
        .form-field-error { border-color: #ef4444 !important; } /* Untuk menandai field yang error */
        .error-text { color: #ef4444; font-size: 0.875em; margin-top: 0.25em; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-xl font-semibold text-gray-900 flex items-center">
                        <a href="/article_management.html" class="flex items-center text-gray-600 hover:text-gray-900 transition-colors mr-4">
                            <i class="fas fa-arrow-left mr-2"></i>
                        </a>
                        Edit Artikel
                    </h1>
                     <a href="/article_management.html" class="text-sm text-blue-600 hover:text-blue-800">
                        Kembali ke Daftar Artikel
                    </a>
                </div>
            </div>

            @if(request()->get('status') === 'success' && request()->get('message'))
                <div class="message-feedback success-message">
                    {{ urldecode(request()->get('message')) }}
                </div>
            @endif
            @if(request()->get('error_message_param'))
                 <div class="message-feedback error-message-list">{{ urldecode(request()->get('error_message_param')) }}</div>
            @endif
            @if (isset($errors) && $errors->any())
                <div class="message-feedback error-message-list">
                    <strong>Oops! Ada kesalahan pada input Anda:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="editArticleForm" action="/admin/articles/{{ $article->id }}" method="POST">
                {{ csrf_field() }} {{-- Penting untuk CSRF protection --}}
                <input type="hidden" name="_method" value="PUT"> {{-- Method spoofing untuk PUT --}}

                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <h2 class="text-lg font-semibold text-gray-700">Informasi Artikel</h2>
                        <span class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                            ID: {{ $article->id }}
                        </span>
                    </div>

                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $article->title) }}" required
                            class="w-full px-4 py-2 border @error('title') form-field-error @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Masukkan judul artikel">
                        @error('title') <p class="error-text">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                            Konten Artikel <span class="text-red-500">*</span>
                        </label>
                        <textarea id="content" name="content" required rows="10"
                            class="w-full px-4 py-2 border @error('content') form-field-error @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                            placeholder="Tulis konten artikel di sini...">{{ old('content', $article->content) }}</textarea>
                        @error('content') <p class="error-text">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Metadata</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id" required
                                class="w-full px-4 py-2 border @error('category_id') form-field-error @else border-gray-300 @enderror rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Pilih Kategori</option>
                                @if(isset($categories))
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('category_id') <p class="error-text">{{ $message }}</p> @enderror
                        </div>
                        {{-- Tambahkan field lain seperti Author, Tags, Status, dll. jika Anda sudah siapkan di database dan controller --}}
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Terakhir diupdate: {{ $article->updated_at ? Carbon\Carbon::parse($article->updated_at)->locale('id_ID')->isoFormat('D MMMM YYYY HH:mm') : 'Belum pernah' }}
                        </div>
                        <button type="submit"
                            class="flex items-center px-6 py-2.5 bg-blue-600 text-white font-medium text-sm rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            Update Artikel
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Jika Anda perlu JS spesifik untuk halaman ini, misalnya editor WYSIWYG untuk 'content',
        // Anda bisa tambahkan di sini.
    </script>
</body>
</html>