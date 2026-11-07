@extends('layouts.app')

@section('title', 'Profil Saya - Browly')

@section('content')
@php
    $editOpen = $errors->hasAny(['name', 'email', 'phone', 'lokasi_id', 'alamat_lengkap']);
    $inputCls = 'w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition';
    $labelCls = 'block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5';
@endphp

<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="mb-6">
        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100/70 text-emerald-800">
            <i data-lucide="user-round" class="w-3.5 h-3.5"></i> Akun Saya
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mt-1">Profil Saya</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">Informasi akun Anda dan pengaturan keamanan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        {{-- ================= KIRI: Informasi akun ================= --}}
        <div class="lg:col-span-3 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">

                {{-- Header: foto + nama + tombol edit --}}
                <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                    <div class="relative shrink-0">
                        @if($user->avatar_url)
                            <img src="{{ $user->avatar_url }}" alt="Foto profil {{ $user->name }}" class="w-20 h-20 rounded-full object-cover shadow-md border-2 border-white ring-1 ring-slate-200">
                        @else
                            <div class="w-20 h-20 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-black flex items-center justify-center text-2xl shadow-md">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        @endif

                        {{-- Ubah foto (otomatis terkirim setelah memilih file) --}}
                        <form id="photo-form" action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <label for="avatar-input" title="Ubah foto profil"
                                   class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center border-2 border-white hover:bg-emerald-600 transition cursor-pointer">
                                <i data-lucide="camera" class="w-4 h-4"></i>
                            </label>
                            <input id="avatar-input" name="avatar" type="file" accept="image/png,image/jpeg,image/webp" class="hidden"
                                   onchange="if (this.files.length) { document.getElementById('photo-form').submit(); }">
                        </form>
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="text-lg font-black text-slate-900 truncate">{{ $user->name }}</div>
                        <span class="inline-flex items-center gap-1 mt-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                            {{ $user->isAdmin() ? 'Admin' : 'Pengguna' }}
                        </span>
                        <div class="flex items-center gap-3 mt-2 text-[11px]">
                            <label for="avatar-input" class="font-bold text-emerald-700 hover:underline cursor-pointer">Ubah Foto</label>
                            @if($user->avatar)
                                <form action="{{ route('profile.photo.destroy') }}" method="POST" class="inline" onsubmit="return confirm('Hapus foto profil?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="font-bold text-rose-600 hover:underline cursor-pointer">Hapus Foto</button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <button type="button" id="edit-toggle"
                            class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-700 transition cursor-pointer shrink-0"
                            onclick="toggleEdit(true)">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit Profil
                    </button>
                </div>

                <p class="text-[11px] text-slate-400 mt-3">Foto: JPG, PNG, atau WEBP, maksimal 2 MB.</p>

                {{-- Mode lihat --}}
                <div id="view-mode" class="{{ $editOpen ? 'hidden' : '' }}">
                    <dl class="mt-6 space-y-4 text-sm">
                        <div class="flex items-start gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <dt class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Email</dt>
                                <dd class="font-semibold text-slate-800 break-all">{{ $user->email }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <dt class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Nomor Telepon</dt>
                                <dd class="font-semibold text-slate-800">{{ $user->phone ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <dt class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Lokasi</dt>
                                <dd class="font-semibold text-slate-800">{{ $user->lokasi ? $user->lokasi->kecamatan . ', ' . $user->lokasi->kota : '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="home" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <dt class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</dt>
                                <dd class="font-semibold text-slate-800">{{ $user->alamat_lengkap ?: '-' }}</dd>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400 mt-0.5"></i>
                            <div>
                                <dt class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bergabung Sejak</dt>
                                <dd class="font-semibold text-slate-800">{{ $user->created_at?->translatedFormat('d F Y') }}</dd>
                            </div>
                        </div>
                    </dl>

                    <button type="button" class="sm:hidden mt-6 w-full inline-flex justify-center items-center gap-1.5 px-3.5 py-2.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-700 transition cursor-pointer"
                            onclick="toggleEdit(true)">
                        <i data-lucide="pencil" class="w-3.5 h-3.5"></i> Edit Profil
                    </button>
                </div>

                {{-- Mode edit --}}
                <form id="edit-mode" action="{{ route('profile.update') }}" method="POST" class="mt-6 space-y-4 {{ $editOpen ? '' : 'hidden' }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="name" class="{{ $labelCls }}">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required value="{{ old('name', $user->name) }}" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="email" class="{{ $labelCls }}">Email</label>
                        <input id="email" name="email" type="email" required value="{{ old('email', $user->email) }}" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="phone" class="{{ $labelCls }}">Nomor Telepon</label>
                        <input id="phone" name="phone" type="tel" required value="{{ old('phone', $user->phone) }}" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="lokasi_id" class="{{ $labelCls }}">Lokasi</label>
                        <select id="lokasi_id" name="lokasi_id" {{ $user->isAdmin() ? '' : 'required' }} class="{{ $inputCls }}">
                            <option value="">-- Pilih lokasi --</option>
                            @foreach($lokasis as $lokasi)
                                <option value="{{ $lokasi->id }}" {{ (string) old('lokasi_id', $user->lokasi_id) === (string) $lokasi->id ? 'selected' : '' }}>
                                    {{ $lokasi->kecamatan }}, {{ $lokasi->kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="alamat_lengkap" class="{{ $labelCls }}">Alamat Lengkap</label>
                        <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" class="{{ $inputCls }}">{{ old('alamat_lengkap', $user->alamat_lengkap) }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 py-3 px-4 rounded-2xl text-xs font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition cursor-pointer">
                            <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                        </button>
                        <button type="button" onclick="toggleEdit(false)" class="py-3 px-5 rounded-2xl text-xs font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition cursor-pointer">
                            Batal
                        </button>
                    </div>
                </form>
            </div>

            @if($stats)
                <div class="grid grid-cols-3 gap-3">
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 text-center shadow-xs">
                        <div class="text-xl font-black text-slate-900">{{ $stats['barang'] }}</div>
                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5">Barang Disewakan</div>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 text-center shadow-xs">
                        <div class="text-xl font-black text-slate-900">{{ $stats['sewa'] }}</div>
                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5">Transaksi Menyewa</div>
                    </div>
                    <div class="p-4 bg-white rounded-2xl border border-slate-200 text-center shadow-xs">
                        <div class="text-xl font-black text-slate-900">{{ $stats['disewa'] }}</div>
                        <div class="text-[11px] font-semibold text-slate-400 mt-0.5">Barang Anda Disewa</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ================= KANAN: Ubah password ================= --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-1">
                    <i data-lucide="lock-keyhole" class="w-4 h-4 text-emerald-600"></i>
                    <h2 class="text-sm font-black text-slate-900">Ubah Password</h2>
                </div>
                <p class="text-xs text-slate-500 mb-5">Isi hanya jika Anda ingin mengganti password.</p>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="current_password" class="{{ $labelCls }}">Password Saat Ini</label>
                        <input id="current_password" name="current_password" type="password" required autocomplete="current-password" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="password" class="{{ $labelCls }}">Password Baru</label>
                        <input id="password" name="password" type="password" required minlength="6" autocomplete="new-password" placeholder="Minimal 6 karakter" class="{{ $inputCls }}">
                    </div>
                    <div>
                        <label for="password_confirmation" class="{{ $labelCls }}">Ulangi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required minlength="6" autocomplete="new-password" class="{{ $inputCls }}">
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-2xl text-xs font-extrabold text-white bg-slate-900 hover:bg-slate-700 transition cursor-pointer">
                        <i data-lucide="save" class="w-4 h-4"></i>
                        <span>Simpan Password Baru</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEdit(open) {
        document.getElementById('view-mode').classList.toggle('hidden', open);
        document.getElementById('edit-mode').classList.toggle('hidden', !open);
        var btn = document.getElementById('edit-toggle');
        if (btn) {
            btn.classList.toggle('hidden', open);
            btn.classList.toggle('sm:inline-flex', !open);
        }
        if (open) document.getElementById('name').focus();
    }
    @if($editOpen)
        document.addEventListener('DOMContentLoaded', function () { toggleEdit(true); });
    @endif
</script>
@endsection
