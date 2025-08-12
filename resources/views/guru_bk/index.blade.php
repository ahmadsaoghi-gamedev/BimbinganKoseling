@include('guru_bk.delete')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Data Guru BK') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @hasrole('admin')
                    <a href="{{ route('guru_bk.create') }}" class="text-black border-black border-2 p-2 rounded-lg ">Tambah Data</a>
                    @endhasrole
                    <br>
                    <x-table>
                        <br>
                        <x-slot name="header">
                            <tr>
                                <th>No</th>
                                <th>Nip</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>No Telpon</th>
                                <th>Alamat</th>
                                @if(auth()->user()->hasRole('admin'))
                                <th>Aksi</th>
                                @else
                                @endif
                            </tr>
                        </x-slot>

                        @php $num = 1; @endphp
                        @foreach ($guru_bk as $data)
                        <tr class="">
                            <td>{{ $num++ }}</td>
                            <td>{{ $data->nip }}</td>
                            <td>
                                <a href="{{ route('guru_bk.show', $data->id) }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                    {{ $data->nama }}
                                </a>
                            </td>
                            <td>{{ $data->jenis_kelamin }}</td>
                            <td>{{ $data->no_tlp }}</td>
                            <td>{{ $data->alamat }}</td>
                            
                            @if (Auth::user()->hasRole('admin'))
                            <td class="flex px-4 py-2 space-x-2">
                                <!-- Tombol Edit -->
                                <a href="{{ route('guru_bk.edit', $data->id) }}"
                                    class="bg-black hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    EDIT
                                </a>

                                <!-- Tombol Hapus dengan Modal -->
                                <button type="button"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hapusModal_{{ $data->id }}">
                                    HAPUS
                                </button>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </x-table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>