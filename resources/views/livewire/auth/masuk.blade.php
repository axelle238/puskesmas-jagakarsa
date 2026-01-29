<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 bg-utama rounded-full flex items-center justify-center text-white font-bold text-2xl">
                PJ
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Masuk ke Sistem
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Khusus Pegawai & Staff Medis
            </p>
        </div>
        <form class="mt-8 space-y-6" wire:submit.prevent="masuk">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Alamat Email</label>
                    <input wire:model="email" id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none rounded-t-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-utama focus:border-utama focus:z-10 sm:text-sm" placeholder="Alamat Email">
                </div>
                <div>
                    <label for="sandi" class="sr-only">Kata Sandi</label>
                    <input wire:model="sandi" id="sandi" name="sandi" type="password" autocomplete="current-password" required class="appearance-none rounded-none rounded-b-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-utama focus:border-utama focus:z-10 sm:text-sm" placeholder="Kata Sandi">
                </div>
            </div>

            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            @error('sandi') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input wire:model="ingat_saya" id="ingat-saya" name="ingat-saya" type="checkbox" class="h-4 w-4 text-utama focus:ring-utama border-gray-300 rounded">
                    <label for="ingat-saya" class="ml-2 block text-sm text-gray-900">
                        Ingat Saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-utama hover:text-utama-gelap">
                        Lupa sandi?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-utama hover:bg-utama-gelap focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-utama">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-300 group-hover:text-blue-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Masuk
                </button>
            </div>
            
            <div wire:loading wire:target="masuk" class="text-center text-sm text-gray-500">
                Memproses kredensial...
            </div>
        </form>
    </div>
</div>
