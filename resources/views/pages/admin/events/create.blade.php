<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6">
        <div class="mb-6">
            <a href="{{ route('events.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Tambah Event Baru</h2>

                <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Judul Event</span>
                                <span class="text-error">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ old('judul') }}" class="input input-bordered w-full @error('judul') input-error @enderror" required>
                            @error('judul')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Kategori</span>
                                <span class="text-error">*</span>
                            </label>
                            <select name="kategori_id" class="select select-bordered w-full @error('kategori_id') select-error @enderror" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Lokasi</span>
                                <span class="text-error">*</span>
                            </label>
                            <select name="lokasi_id" class="select select-bordered w-full @error('lokasi_id') select-error @enderror" required>
                                <option value="">Pilih Lokasi</option>
                                @foreach ($lokasis as $lokasi)
                                    <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                        {{ $lokasi->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Tanggal & Waktu</span>
                                <span class="text-error">*</span>
                            </label>
                            <input type="datetime-local" name="tanggal_waktu" value="{{ old('tanggal_waktu') }}" class="input input-bordered w-full @error('tanggal_waktu') input-error @enderror" required>
                            @error('tanggal_waktu')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Gambar (max 2MB)</span>
                            </label>
                            <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg" class="file-input file-input-bordered w-full @error('gambar') file-input-error @enderror" onchange="previewImage(event)">
                            @error('gambar')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2" id="imagePreviewContainer" style="display: none;">
                            <label class="block">
                                <span class="text-sm font-medium">Preview Gambar</span>
                            </label>
                            <img id="imagePreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block">
                                <span class="text-sm font-medium">Deskripsi</span>
                                <span class="text-error">*</span>
                            </label>
                            <textarea name="deskripsi" rows="4" class="textarea textarea-bordered w-full @error('deskripsi') textarea-error @enderror" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold">Tiket</h3>
                            <button type="button" onclick="addTicket()" class="btn btn-sm btn-primary">Tambah Tiket</button>
                        </div>

                        <div id="ticketsContainer"></div>
                    </div>

                    <div class="flex gap-4">
                        <button type="submit" class="btn btn-primary">Simpan Event</button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let ticketCount = 0;

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                }
                reader.readAsDataURL(file);
            }
        }

        function addTicket() {
            ticketCount++;
            const container = document.getElementById('ticketsContainer');
            const ticketHTML = `
                <div class="card bg-base-200 mb-4" id="ticket-${ticketCount}">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-semibold">Tiket #${ticketCount}</h4>
                            <button type="button" onclick="removeTicket(${ticketCount})" class="btn btn-sm btn-error">Hapus</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Tipe Tiket</span>
                                    <span class="text-error">*</span>
                                </label>
                                <select name="tikets[${ticketCount}][tipe]" class="select select-bordered w-full" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="reguler">Reguler</option>
                                    <option value="premium">Premium</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Harga</span>
                                    <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tikets[${ticketCount}][harga]" min="0" class="input input-bordered w-full" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Stok</span>
                                    <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tikets[${ticketCount}][stok]" min="0" class="input input-bordered w-full" required>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', ticketHTML);
        }

        function removeTicket(id) {
            const ticket = document.getElementById('ticket-' + id);
            if (ticket) {
                ticket.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            addTicket();
        });
    </script>
</x-app-layout>
