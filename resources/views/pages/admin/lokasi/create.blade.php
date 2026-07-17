<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6">
        <div class="mb-6">
            <a href="{{ route('lokasi.index') }}" class="btn btn-outline">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Tambah Lokasi Baru</h2>

                <form action="{{ route('lokasi.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label class="block">
                                <span class="text-sm font-medium">Nama Lokasi</span>
                                <span class="text-error">*</span>
                            </label>
                            <input type="text" name="nama_lokasi" value="{{ old('nama_lokasi') }}" class="input input-bordered w-full @error('nama_lokasi') input-error @enderror" required>
                            @error('nama_lokasi')
                            <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div class="flex gap-4">
                        <button type="submit" class="btn btn-primary">Simpan Lokasi</button>
                        <a href="{{ route('lokasi.index') }}" class="btn btn-outline">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>