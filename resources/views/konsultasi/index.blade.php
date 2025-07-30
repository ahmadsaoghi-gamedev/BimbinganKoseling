@include('konsultasi.delete')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Konsultasi Bimbingan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @hasrole('gurubk')
                    <a href="{{ route('konsultasi.create') }}" class="text-black border-black border-2 p-2 rounded-lg ">Tambah Data</a>
                    @endhasrole
                    <br>
                    <br>
                    <br>
                    <x-table>
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Siswa</th>
                                <th>Topik</th>
                                <th>Tanggal Konsultasi</th>
                                <th>Aksi</th>
                            </tr>
                        </x-slot>

                        @php $num = 1; @endphp
                        @foreach ($konsultasi as $data)
                        <tr>
                            <td>{{ $num++ }}</td>

                            <!-- Gunakan optional() untuk mencegah error saat siswa null -->
                            <td>{{ optional($data->siswa)->nama ?? 'Siswa tidak ditemukan' }}</td>

                            <td>{{ $data->topik }}</td>
                            <td>{{ $data->tgl_konsultasi }}</td>

                            @hasrole('gurubk')
                            <td class="flex px-4 py-2 space-x-2">

                                <!-- Tombol Hapus dengan Modal -->
                                <button type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal_{{ $data->id }}">
                                    HAPUS
                                </button>
                            </td>
                            @endhasrole
                        </tr>
                        @endforeach
                    </x-table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>