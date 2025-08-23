@extends('layouts.app')

@section('title', 'Data Pengaduan')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Data Pengaduan
                    </h2>
                </div>

                @hasrole('siswa')
                <!-- Form Pengaduan untuk Siswa -->
                <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Buat Pengaduan Baru</h3>
                    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIS</label>
                                <input type="text" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" id="nis" name="nis" placeholder="Masukkan NIS">
                            </div>
                            <div>
                                <label for="tgl_pengaduan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tanggal Pengaduan</label>
                                <input type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" id="tgl_pengaduan" name="tgl_pengaduan">
                            </div>
                        </div>
                        <div class="mt-6">
                            <label for="laporan_pengaduan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Laporan Pengaduan</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" id="laporan_pengaduan" name="laporan_pengaduan" rows="4" placeholder="Jelaskan pengaduan Anda..."></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                            <div>
                                <label for="jenis_pengaduan" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Pengaduan</label>
                                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" id="jenis_pengaduan" name="jenis_pengaduan">
                                    <option value="">Pilih Jenis Pengaduan</option>
                                    <option value="bullyng">Bullying</option>
                                    <option value="kenakalanremaja">Kenakalan Remaja</option>
                                </select>
                            </div>
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Gambar (Opsional)</label>
                                <input type="file" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white" id="gambar" name="gambar">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Kirim Pengaduan
                            </button>
                        </div>
                    </form>
                </div>
                @endhasrole

                @if (Auth::check() && (Auth::user()->hasRole('gurubk') || Auth::user()->hasRole('admin')))
                <!-- Tabel Pengaduan untuk Guru BK dan Admin -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Laporan Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Pengaduan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Gambar</th>
                                @hasrole('admin')
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                @endhasrole
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php $num = 1; @endphp
                            @foreach ($pengaduans as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $num++ }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->tgl_pengaduan }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ Str::limit($data->laporan_pengaduan, 100) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->jenis_pengaduan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($data->gambar)
                                    <img src="{{ asset('storage/images/' . $data->gambar) }}" alt="gambar" class="h-20 w-20 object-cover rounded">
                                    @else
                                    <span class="text-gray-500">Tidak ada gambar</span>
                                    @endif
                                </td>
                                @hasrole('admin')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('pengaduan.destroy', $data->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus pengaduan ini?');">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                                @endhasrole
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($pengaduans->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data pengaduan yang ditemukan.</p>
                </div>
                @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection