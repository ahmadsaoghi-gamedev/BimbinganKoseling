<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @hasrole('siswa')
                    <form method="POST" action="{{ route('pengaduan.store') }}" enctype="multipart/form-data"
                            class="row g-3">
                            @csrf
                        <div class="col-md-6">
                            <label for="nis" class="form-label">Nis</label>
                            <input type="text" class="form-control" id="nis" name="nis" placeholder="">
                        </div>
                        <div class="col-md-6">
                            <label for="tgl_pengaduan" class="form-label">Tanggal Pengaduan</label>
                            <input type="date" class="form-control" id="tgl_pengaduan" name="tgl_pengaduan">
                        </div>
                        <div class="col-12">
                            <label for="laporan_pengaduan" class="form-label">Laporan Pengaduan</label>
                            <textarea type="text" class="form-control" id="laporan_pengaduan" name="laporan_pengaduan"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="inputState" class="form-label font-bold">Jenis Pengaduan</label>
                            <select id="jenis_pengaduan" name="jenis_pengaduan" class="form-select" placeholder="jenis_pengaduan">
                                <option selected>Jenis Pengaduan</option>
                                <option value="bullyng">Bullying</option>
                                <option value="kenakalanremaja">Kenakalan Remaja</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="gambar" class="form-label font-bold">Gambar</label>
                            <input type="file" id="gambar" name="gambar">
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" name="save" value="true">Kirim</button>
                        </div>
                    </form>
                    @endhasrole

                    @if (Auth::check())
@if (Auth::user()->hasRole('gurubk') || Auth::user()->hasRole('admin'))
                    <div class="p-6 text-gray-900">
                        <x-table :tableId="'myTable_' . uniqid()">
                            <x-slot name="header">
                                <tr class="">
                                    <th>No</th>
                                    <th>Nis</th>
                                    <th>Tanggal Pengaduan</th>
                                    <th>Laporan Pengaduan</th>
                                    <th>Jenis Pengaduan</th>
                                    <th>Gambar</th>
                                </tr>
                            </x-slot>
                           
                            @php $num = 1; @endphp
                            @foreach ($pengaduans as $data)
                            <tr class="text-center">
                                <td>{{ $num++ }}</td>
                                <td>{{ $data->nis}}</td>
                                <td>{{ $data->tgl_pengaduan}}</td>
                                <td>{{ $data->laporan_pengaduan}}</td>
                                <td>{{ $data->jenis_pengaduan }}</td>
                                <td>
                                    <img src="{{ asset('storage/images/' . $data->gambar) }}" alt="gambar" class="h-20">
                                </td>
                                <td>
                                    @hasrole('admin')
                                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#hapusModal_{{ $data->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                    @endhasrole
                                </td>
                            </tr>
                            @endforeach
                           
                        </x-table>
                    </div>
                    @endif
@endif
                </div>
            </div>
        </div>
</x-app-layout>