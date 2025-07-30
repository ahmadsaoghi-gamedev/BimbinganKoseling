<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
        @if(auth()->user()->hasRole('gurubk'))
            {{ __('Data Rekap Bimbingan') }}
        @else
            {{ __('Bimbingan Konseling') }}  
        @endif  
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form  
                        action="{{ route('rekap.store') }}" method="POST">
                        @csrf
                        
                        @if(auth()->user()->hasRole('gurubk'))
                        <a href="{{ route('rekap.index') }}">Kembali</a>
                        @else
                        <a href="{{ route('dashboard') }}">Kembali</a>
                        @endif

                        <div class="grid grid-cols-1 gap-4 mb-4">
                            <div class="max-w-xl">
                                <!-- Label untuk Select -->
                                

                                <!-- Select Input -->
                                @if(auth()->user()->hasRole('gurubk'))
                                <x-input-label for="id_siswa" value="Siswa" />
                                <select id="id_siswa" name="id_siswa" class="mt-1 block w-full" required>
                                    <option value="" selected>Pilih Siswa</option>
                                    @foreach (\App\Models\Siswa::all() as $siswa)
                                    <option value="{{ $siswa->id }}">{{ $siswa->nama }}</option>
                                    @endforeach
                                </select>
                                @else
                                @php $idi = auth()->user()->id; @endphp
                                @php $idid = auth()->user()->id-1; @endphp
                                <input type="hidden" name="id_siswa" value="{{ $idid }}">
                                @endif

                                <!-- Error Message -->
                                <x-input-error class="mt-2" :messages="$errors->get('id_siswa')" />
                            </div>


                            <div class="max-w-xl">
                                <x-input-label for="jenis_bimbingan" value="Jenis Bimbingan" />
                                <x-select-input id="jenis_bimbingan" name="jenis_bimbingan" class="mt-1 block w-full" required>
                                    <option value="" selected>Pilih Jenis Bimbingan</option>
                                    <option value="Sosial">Sosial</option>
                                    <option value="Akademik">Akademik</option>
                                    <option value="Pribadi">Pribadi</option>
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('jenis_bimbingan')" />
                            </div>

                            <div class="max-w-xl">
                                <label for="tgl_bimbingan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Tanggal Bimbingan:
                                </label>
                                <input type="date" id="tgl_bimbingan" name="tgl_bimbingan" class="mt-1 block w-full rounded-md text-black" required />
                                <x-input-error class="mt-2" :messages="$errors->get('tgl_bimbingan')" />
                            </div>

                            <div class="max-w-xl">
                                <x-input-label for="keterangan" value="Keterangan" />
                                <x-text-input id="keterangan" type="text" name="keterangan" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                            </div>
                        </div>
                        <button value="true" type="submit" name="save" class="btn btn-light align-right">Simpan
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>