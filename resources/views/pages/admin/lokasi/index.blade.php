<x-app-layout>
    <div class="max-w-7xl mx-auto py-8 px-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold">Manajemen Lokasi</h1>
            <a href="{{ route('lokasi.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Lokasi
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
                <form method="GET" action="{{ route('lokasi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul atau lokasi..." class="input input-bordered w-full">
                    </div>


                    <div class="flex gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('lokasi.index') }}" class="btn btn-outline">Reset</a>
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
                                <th>Nama</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($lokasis as $lokasi)
                            <tr class="hover:bg-base-200 cursor-pointer transition-colors">
                                <td>{{ $lokasi->nama_lokasi }}</td>
                                <td onclick="lokasi.stopPropagation()" class="text-right">
                                    <div class="flex gap-2 justify-end">
                                        <a href="{{ route('lokasi.edit', $lokasi) }}" class="btn btn-sm btn-ghost btn-square" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </a>
                                        <form action="{{ route('lokasi.destroy', $lokasi) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lokasi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-error" title="Delete">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada lokasi yang ditemukan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $lokasis->appends(request()->except('page'))->links() }}
                </div>
            </div>
        </div>
    </div>

    <style>
        nav[role="navigation"] a,
        nav[role="navigation"] span {
            background-color: #ffffff !important;
            border-color: #d1d1d1 !important;
            color: #000000 !important;
        }

        nav[role="navigation"] span[aria-current="page"]>span,
        nav[role="navigation"] span[aria-current="page"]>button {
            background-color: #422ad5 !important;
            border-color: #d1d1d1 !important;
            color: #ffffff !important;
        }

        nav[role="navigation"] a:hover:not([aria-disabled="true"]) {
            background-color: #422ad5 !important;
            border-color: #d1d1d1 !important;
            color: #ffffff !important;
        }
    </style>
</x-app-layout>