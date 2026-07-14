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

        @if ($hasSales)
            <div class="alert alert-warning mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>Event ini sudah memiliki penjualan tiket. Beberapa field mungkin tidak dapat diubah.</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Edit Event</h2>

                <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Judul Event</span>
                                <span class="text-error">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ old('judul', $event->judul) }}" class="input input-bordered w-full @error('judul') input-error @enderror" required>
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
                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $event->kategori_id) == $kategori->id ? 'selected' : '' }}>
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
                            <input type="text" name="lokasi" value="{{ old('lokasi', $event->lokasi) }}" class="input input-bordered w-full @error('lokasi') input-error @enderror" required>
                            @error('lokasi')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Tanggal & Waktu</span>
                                <span class="text-error">*</span>
                                @if ($hasSales)
                                    <span class="badge badge-warning badge-sm ml-2">Tidak dapat diubah</span>
                                @endif
                            </label>
                            <input type="datetime-local" name="tanggal_waktu" value="{{ old('tanggal_waktu', \Carbon\Carbon::parse($event->tanggal_waktu)->format('Y-m-d\TH:i')) }}" class="input input-bordered w-full @error('tanggal_waktu') input-error @enderror" {{ $hasSales ? 'readonly' : '' }} required>
                            @error('tanggal_waktu')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Gambar Saat Ini</span>
                            </label>
                            <img src="{{ $event->image_url }}" alt="{{ $event->judul }}" class="w-full h-48 object-cover rounded-lg">
                        </div>

                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Gambar Baru (max 2MB)</span>
                            </label>
                            <input type="file" name="gambar" accept="image/jpeg,image/png,image/jpg" class="file-input file-input-bordered w-full @error('gambar') file-input-error @enderror" onchange="previewImage(event)">
                            <span class="text-sm text-gray-500">Kosongkan jika tidak ingin mengubah gambar</span>
                            @error('gambar')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                            <div id="imagePreviewContainer" style="display: none;" class="mt-2">
                                <img id="imagePreview" src="" alt="Preview" class="w-full h-48 object-cover rounded-lg">
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="block">
                                <span class="text-sm font-medium">Deskripsi</span>
                                <span class="text-error">*</span>
                            </label>
                            <textarea name="deskripsi" rows="4" class="textarea textarea-bordered w-full @error('deskripsi') textarea-error @enderror" required>{{ old('deskripsi', $event->deskripsi) }}</textarea>
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
                        <button type="submit" class="btn btn-primary">Update Event</button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let ticketCount = 0;
        const existingTickets = @json($tikets);
        const hasSales = {{ $hasSales ? 'true' : 'false' }};

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

        function getNextTicketNumber() {
            const container = document.getElementById('ticketsContainer');
            const tickets = container.querySelectorAll('.card');
            return tickets.length + 1;
        }

        function updateTicketNumbers() {
            const container = document.getElementById('ticketsContainer');
            const tickets = container.querySelectorAll('.card');
            tickets.forEach((ticket, index) => {
                const header = ticket.querySelector('.ticket-header-title');
                if (header) {
                    const badgeHTML = ticket.querySelector('.badge') ? ticket.querySelector('.badge').outerHTML : '';
                    header.innerHTML = `<h4 class="font-semibold">Tiket #${index + 1}</h4>${badgeHTML}`;
                }
            });
        }

        function addTicket(existingData = null) {
            ticketCount++;
            const container = document.getElementById('ticketsContainer');
            const isExisting = existingData !== null;
            const ticketId = isExisting ? existingData.id : null;
            const displayCount = getNextTicketNumber();
            
            const hasSalesBadge = hasSales && isExisting ? '<span class="badge badge-success badge-sm">Sudah Terjual</span>' : '';
            const deleteButton = hasSales && isExisting 
                ? `<span class="text-sm text-gray-500">Tidak dapat dihapus</span>`
                : `<button type="button" onclick="removeTicket(${ticketCount})" class="btn btn-sm btn-error">Hapus</button>`;

            const ticketHTML = `
                <div class="card bg-base-200 mb-4" id="ticket-${ticketCount}" data-ticket-id="${ticketCount}">
                    <div class="card-body">
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center gap-2 ticket-header-title">
                                <h4 class="font-semibold">Tiket #${displayCount}</h4>
                                ${hasSalesBadge}
                            </div>
                            ${deleteButton}
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            ${isExisting ? `<input type="hidden" name="tikets[${ticketCount}][id]" value="${ticketId}">` : ''}
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Tipe Tiket</span>
                                    <span class="text-error">*</span>
                                </label>
                                <select name="tikets[${ticketCount}][tipe]" class="select select-bordered w-full" required>
                                    <option value="">Pilih Tipe</option>
                                    <option value="reguler" ${isExisting && existingData.tipe === 'reguler' ? 'selected' : ''}>Reguler</option>
                                    <option value="premium" ${isExisting && existingData.tipe === 'premium' ? 'selected' : ''}>Premium</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Harga</span>
                                    <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tikets[${ticketCount}][harga]" value="${isExisting ? existingData.harga : ''}" min="0" class="input input-bordered w-full" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block">
                                    <span class="text-sm font-medium">Stok</span>
                                    <span class="text-error">*</span>
                                </label>
                                <input type="number" name="tikets[${ticketCount}][stok]" value="${isExisting ? existingData.stok : ''}" min="0" class="input input-bordered w-full" required>
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
                updateTicketNumbers();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            existingTickets.forEach((ticket) => {
                addTicket(ticket);
            });
        });
    </script>
</x-app-layout>
