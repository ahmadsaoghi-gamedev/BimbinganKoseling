@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <!-- Header -->
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        Data Siswa
                    </h2>
                    @if (Auth::user()->hasRole('gurubk') || Auth::user()->hasRole('admin'))
                    <a href="{{ route('siswa.create') }}" 
                       class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Tambah Data
                    </a>
                    @endif
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">NIS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jurusan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jenis Kelamin</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">No Telpon</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Alamat</th>
                                @if(!auth()->user()->hasRole('siswa') && !auth()->user()->hasRole('kepsek') && !auth()->user()->hasRole('kajur'))
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @php $num = 1; @endphp
                            @foreach ($siswa as $data)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $num++ }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->nama }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->kelas }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->jurusan }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->jenis_kelamin }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->no_tlp }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $data->alamat }}</td>
                                
                                @if (Auth::user()->hasRole('gurubk') || Auth::user()->hasRole('admin'))
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Tombol Edit -->
                                        <a href="{{ route('siswa.edit', $data->id) }}"
                                            class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs">
                                            Edit
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <button type="button"
                                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-xs"
                                            onclick="if(confirm('Apakah Anda yakin ingin menghapus data ini?')) { document.getElementById('delete-form-{{ $data->id }}').submit(); }">
                                            Hapus
                                        </button>
                                        
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('siswa.destroy', $data->id) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($siswa->isEmpty())
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada data siswa yang ditemukan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection