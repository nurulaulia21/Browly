<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaController extends Controller
{
    /**
     * Sajikan foto barang yang diunggah langsung dari storage,
     * sehingga gambar tampil tanpa perlu `php artisan storage:link`.
     * Hanya file di folder "barangs" yang bisa diakses.
     */
    public function barang(string $file): Response
    {
        $path = 'barangs/' . $file;
        $disk = Storage::disk('public');

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, null, ['Cache-Control' => 'public, max-age=86400']);
    }
}
