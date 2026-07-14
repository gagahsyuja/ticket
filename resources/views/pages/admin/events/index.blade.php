<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manajemen Event</h1>
            <a href="{{ route('events.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Event
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <div class="card bg-base-100 shadow-xl mb-6">
            <div class="card-body">
                <form method="GET" action="{{ route('events.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau lokasi..." class="input input-bordered w-full">
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Kategori</label>
                        <select name="kategori_id" class="select select-bordered w-full">
                            <option value="">Semua Kategori</option>
                            @foreach (\App\Models\Kategori::all() as $kategori)
                                <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Sort</label>
                        <select name="sort" class="select select-bordered w-full">
                            <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Tanggal (Terlama)</option>
                            <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Tanggal (Terbaru)</option>
                        </select>
                    </div>

                    <div class="space-y-2 flex items-end gap-2">
                        <button type="submit" class="btn btn-primary flex-1">Filter</button>
                        <a href="{{ route('events.index') }}" class="btn btn-outline flex-1">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($events as $event)
                                <tr>
                                    <td>
                                        <div class="avatar">
                                            <div class="w-16 h-16 rounded">
                                                <img src="{{ $event->image_url }}" alt="{{ $event->judul }}">
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $event->judul }}</td>
                                    <td>{{ $event->kategori->nama ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->tanggal_waktu)->format('d M Y, H:i') }}</td>
                                    <td>{{ $event->lokasi }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($event->status) {
                                                'Upcoming' => 'badge-info',
                                                'Ongoing' => 'badge-warning',
                                                'Completed' => 'badge-success',
                                                default => 'badge-ghost'
                                            };
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $event->status }}</span>
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-info">View</a>
                                            <a href="{{ route('events.edit', $event) }}" class="btn btn-sm btn-warning">Edit</a>
                                            <form action="{{ route('events.destroy', $event) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus event ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-error">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada event yang ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $events->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
