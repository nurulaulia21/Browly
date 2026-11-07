<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    /** Tampilkan informasi akun. */
    public function show(Request $request): View
    {
        $user = $request->user()->load('lokasi');
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        $stats = $user->isAdmin() ? null : [
            'barang' => $user->barangs()->count(),
            'sewa' => $user->bookingsAsPenyewa()->count(),
            'disewa' => $user->bookingsAsPemilik()->count(),
        ];

        return view('profile.show', compact('user', 'stats', 'lokasis'));
    }

    /** Edit data profil. */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20'],
            'lokasi_id' => [$user->isAdmin() ? 'nullable' : 'required', 'exists:lokasis,id'],
            'alamat_lengkap' => ['nullable', 'string', 'max:500'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email tersebut sudah digunakan akun lain.',
            'phone.required' => 'Nomor telepon wajib diisi.',
            'lokasi_id.required' => 'Lokasi wajib dipilih.',
            'lokasi_id.exists' => 'Lokasi yang dipilih tidak valid.',
        ]);

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui.');
    }

    /** Unggah / ganti foto profil. */
    public function uploadPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ], [
            'avatar.required' => 'Pilih foto terlebih dahulu.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format foto harus JPG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto maksimal 2 MB.',
        ]);

        $user = $request->user();

        $path = $request->file('avatar')->store('avatars', 'public');

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => $path]);

        return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui.');
    }

    /** Hapus foto profil. */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return redirect()->route('profile.show')->with('success', 'Foto profil dihapus.');
    }

    /** Sajikan foto profil langsung dari storage (tidak perlu `php artisan storage:link`). */
    public function avatar(User $user): Response
    {
        $disk = Storage::disk('public');

        abort_unless($user->avatar && $disk->exists($user->avatar), 404);

        return $disk->response($user->avatar, null, ['Cache-Control' => 'public, max-age=86400']);
    }

    /** Ubah password (wajib mengisi password lama). */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:6', 'confirmed', 'different:current_password'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi.',
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak sama.',
            'password.different' => 'Password baru tidak boleh sama dengan password saat ini.',
        ]);

        $request->user()->update(['password' => $request->input('password')]);

        return redirect()->route('profile.show')->with('success', 'Password berhasil diubah.');
    }
}
