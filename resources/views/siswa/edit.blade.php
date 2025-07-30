<x-app-layout>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Data Siswa') }}
            </h2>
            <br>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                @php
                    $siswas = App\Models\Siswa::findOrFail($id);
                @endphp

                    <form method="post" action="{{ route('siswa.update', $siswas->id) }}">

                        @csrf
                        @method('PATCH')

                        <div class="max-w-xl">
                            <x-input-label for="nis" value="NIS" />
                            <x-text-input id="nis" type="text" name="nis" class="mt-1 block w-full" value="{{ old('nis', $siswas->nis) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nis')" />
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="nama" value="Nama" />
                            <x-text-input id="nama" type="text" name="nama" class="mt-1 block w-full" value="{{ old('nama', $siswas->nama) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="kelas" value="Kelas" />
                            <x-text-input id="kelas" type="text" name="kelas" class="mt-1 block w-full" value="{{ old('kelas', $siswas->kelas) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('kelas')" />
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="jurusan" value="Jurusan" />
                            <x-text-input id="jurusan" type="text" name="jurusan" class="mt-1 block w-full" value="{{ old('jurusan', $siswas->jurusan) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('jurusan')" />
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="jenis_kelamin" value="Jenis Kelamin" />
                            <!-- Select the option based on the old input or the $guru model -->
                            <select id="jenis_kelamin" name="jenis_kelamin" class="mt-1 block w-full" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" {{ old('jenis_kelamin', $siswas->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $siswas->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="alamat" value="Alamat" />
                            <x-text-input id="alamat" type="text" name="alamat" class="mt-1 block w-full" value="{{ old('alamat', $siswas->alamat) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                        </div>

                        <div class="max-w-xl">
                            <x-input-label for="no_tlp" value="No Telepon" />
                            <x-text-input id="no_tlp" type="text" name="no_tlp" class="mt-1 block w-full" value="{{ old('no_telp', $siswas->no_tlp) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('no_tlp')" />
                        </div>
                        
                        <div class="max-w-xl">
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" type="text" name="email" class="mt-1 block w-full" value="{{ old('email', $siswas->user->email) }}" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <br>
                        <x-primary-button type="submit" name="simpan" value="true">
                            Simpan Data
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>