@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $booking->booking_code . ' - Browly')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kode Transaksi:</span>
                <span class="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded text-sm">{{ $booking->booking_code }}</span>
            </div>
            <h1 class="text-xl font-bold text-slate-900">
                Penyewaan: {{ $booking->barang->nama_barang }}
            </h1>
        </div>

        <div class="flex items-center gap-2">
            @php
                $badgeClasses = match($booking->status) {
                    'MENUNGGU_PERSETUJUAN' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'bg-blue-100 text-blue-800 border-blue-200',
                    'DIBAYAR', 'AKTIF' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'DIKEMBALIKAN' => 'bg-purple-100 text-purple-800 border-purple-200',
                    'SELESAI' => 'bg-teal-100 text-teal-800 border-teal-200',
                    'DITOLAK', 'DIBATALKAN' => 'bg-rose-100 text-rose-800 border-rose-200',
                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                };
            @endphp
            <span class="px-4 py-2 rounded-2xl text-xs font-bold border {{ $badgeClasses }} flex items-center gap-1.5 shadow-xs">
                <i data-lucide="info" class="w-4 h-4"></i>
                {{ $booking->status_label }}
            </span>
        </div>
    </div>

    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs mb-8">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-6">Status Perjalanan Transaksi</h3>
        
        @php
            $steps = [
                ['key' => 'booking', 'title' => 'Booking', 'desc' => 'Diajukan'],
                ['key' => 'approval', 'title' => 'Persetujuan', 'desc' => 'Maks. 24 Jam'],
                ['key' => 'payment', 'title' => 'Pembayaran', 'desc' => 'Maks. 24 Jam'],
                ['key' => 'active', 'title' => 'Chat & Sewa', 'desc' => 'Masa Sewa'],
                ['key' => 'return', 'title' => 'Pengembalian', 'desc' => 'Cek Kondisi'],
                ['key' => 'complete', 'title' => 'Selesai & Ulasan', 'desc' => 'Review'],
            ];

            $currentIndex = match($booking->status) {
                'MENUNGGU_PERSETUJUAN' => 1,
                'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 2,
                'DIBAYAR', 'AKTIF' => 3,
                'DIKEMBALIKAN' => 4,
                'SELESAI' => 5,
                'DITOLAK', 'DIBATALKAN' => -1,
                default => 0,
            };
        @endphp

        <div class="grid grid-cols-2 sm:grid-cols-6 gap-4">
            @foreach($steps as $idx => $step)
                @php
                    $isPassed = $currentIndex > $idx;
                    $isCurrent = $currentIndex === $idx;
                @endphp
                <div class="flex flex-col items-center text-center">
                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center font-bold text-sm mb-2 transition
                        {{ $isCurrent ? 'bg-emerald-600 text-white ring-4 ring-emerald-100 shadow-md' : ($isPassed ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400') }}">
                        @if($isPassed)
                            <i data-lucide="check" class="w-5 h-5"></i>
                        @else
                            {{ $idx + 1 }}
                        @endif
                    </div>
                    <span class="text-xs font-bold {{ $isCurrent ? 'text-emerald-700' : ($isPassed ? 'text-slate-800' : 'text-slate-400') }}">
                        {{ $step['title'] }}
                    </span>
                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $step['desc'] }}</span>
                </div>
            @endforeach
        </div>

        @if(in_array($booking->status, ['DITOLAK', 'DIBATALKAN']))
            <div class="mt-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-start gap-3">
                <i data-lucide="x-circle" class="w-5 h-5 text-rose-600 shrink-0 mt-0.5"></i>
                <div>
                    <div class="font-bold">Transaksi Dibatalkan / Ditolak</div>
                    <p class="mt-1 text-rose-700">
                        {{ $booking->alasan_penolakan ?? $booking->alasan_pembatalan ?? 'Transaksi ini telah dibatalkan.' }}
                    </p>
                </div>
            </div>
        @endif
    </div>

    @php
        $user = auth()->user();
        $isPenyewa = $user->id === $booking->penyewa_id;
        $isPemilik = $user->id === $booking->pemilik_id;
    @endphp

    @if($isPemilik && $booking->status === 'MENUNGGU_PERSETUJUAN')
        <div class="bg-gradient-to-r from-amber-500/10 via-amber-50 to-amber-100/50 p-6 rounded-3xl border border-amber-300 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-amber-800 bg-amber-200/80 px-2.5 py-1 rounded-lg mb-2">
                        <i data-lucide="clock" class="w-3.5 h-3.5"></i> Tindakan Pemilik Diperlukan (Batas 24 Jam)
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Penyewa Mengajukan Booking untuk Barang Anda</h3>
                    <p class="text-xs text-slate-600 mt-1">
                        Batas waktu respon otomatis: <strong>{{ $booking->expires_at_owner ? $booking->expires_at_owner->format('d M Y, H:i') : '-' }}</strong>. Jika tidak direspon, sistem akan membatalkan otomatis.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <form action="{{ route('pemilik.booking.approve', $booking->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Setujui Booking
                        </button>
                    </form>

                    <button onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-sm transition flex items-center gap-1.5">
                        <i data-lucide="x" class="w-4 h-4"></i>
                        Tolak Booking
                    </button>
                </div>
            </div>
        </div>

        <div id="rejectModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                <h4 class="text-base font-bold text-slate-900 mb-2">Tolak Permintaan Booking</h4>
                <p class="text-xs text-slate-500 mb-4">Berikan alasan penolakan agar penyewa dapat memahaminya.</p>
                <form action="{{ route('pemilik.booking.reject', $booking->id) }}" method="POST">
                    @csrf
                    <textarea name="alasan_penolakan" required rows="3" placeholder="Misal: Barang sedang diservis berkala..."
                              class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 mb-4"></textarea>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 rounded-xl hover:bg-slate-100">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs">
                            Kirim Penolakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($isPenyewa && in_array($booking->status, ['DISETUJUI', 'MENUNGGU_PEMBAYARAN']))
        <div class="bg-gradient-to-r from-blue-500/10 via-blue-50 to-blue-100/50 p-6 rounded-3xl border border-blue-300 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-blue-800 bg-blue-200/80 px-2.5 py-1 rounded-lg mb-2">
                        <i data-lucide="check-circle" class="w-3.5 h-3.5"></i> Booking Disetujui Pemilik!
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Selesaikan Pembayaran Simulasi dalam 24 Jam</h3>
                    <p class="text-xs text-slate-600 mt-1">
                        Batas waktu pembayaran: <strong>{{ $booking->expires_at_payment ? $booking->expires_at_payment->format('d M Y, H:i') : '-' }}</strong>.
                    </p>
                </div>

                <div>
                    <a href="{{ route('pembayaran.show', $booking->booking_code) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-md shadow-blue-500/20 transition">
                        <i data-lucide="credit-card" class="w-4 h-4"></i>
                        Lanjutkan ke Pembayaran (Rp{{ number_format($booking->total_biaya, 0, ',', '.') }})
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($booking->isChatActive())
        <div class="bg-gradient-to-r from-emerald-500/10 via-emerald-50 to-teal-50 p-6 rounded-3xl border border-emerald-300 shadow-xs mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1 text-xs font-bold uppercase tracking-wider text-emerald-800 bg-emerald-200/80 px-2.5 py-1 rounded-lg mb-2">
                        <i data-lucide="message-square" class="w-3.5 h-3.5"></i> Ruang Chat Transaksi Aktif
                    </span>
                    <h3 class="text-base font-bold text-slate-900">Koordinasi Pengambilan & Pengantaran</h3>
                    <p class="text-xs text-slate-600 mt-1">
                        Sesuai aturan Browly, chat telah aktif karena pembayaran telah lunas. Gunakan untuk komunikasi serah terima.
                    </p>
                </div>

                <div>
                    <a href="{{ route('chat.show', $booking->booking_code) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition">
                        <i data-lucide="message-circle" class="w-4 h-4"></i>
                        Buka Ruang Chat Transaksi
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if($isPenyewa && in_array($booking->status, ['DIBAYAR', 'AKTIF']))
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs mb-8">
            <h3 class="text-base font-bold text-slate-900 mb-2">Masa Penyewaan & Pengembalian Barang</h3>
            <p class="text-xs text-slate-500 mb-4">
                Batas tanggal selesai sewa: <strong>{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</strong>.
                Jika Anda telah selesai menggunakan barang, silakan klik tombol di bawah untuk mengajukan pengembalian.
            </p>

            <button onclick="document.getElementById('returnModal').classList.remove('hidden')"
                    class="px-5 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                <i data-lucide="arrow-left-right" class="w-4 h-4"></i>
                Ajukan Pengembalian Barang
            </button>
        </div>

        <div id="returnModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                <h4 class="text-base font-bold text-slate-900 mb-1">Pengembalian Barang</h4>
                <p class="text-xs text-slate-500 mb-4">Catat kondisi barang saat Anda mengembalikannya ke pemilik.</p>

                <form action="{{ route('pengembalian.store', $booking->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Tanggal & Waktu Kembali Aktual</label>
                        <input type="datetime-local" name="tanggal_pengembalian_aktual" required
                               value="{{ date('Y-m-d\TH:i') }}"
                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Kondisi Barang Saat Dikembalikan</label>
                        <select name="kondisi_barang" required class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                            <option value="sesuai_awal">Sesuai Kondisi Awal (Normal & Lengkap)</option>
                            <option value="rusak_ringan">Ada Sedikit Lecet / Kerusakan Ringan</option>
                            <option value="rusak_berat">Rusak Berat / Perlu Perbaikan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" placeholder="Catatan untuk pemilik saat pengembalian..."
                                  class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="document.getElementById('returnModal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 rounded-xl hover:bg-slate-100">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs">
                            Kirim Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($isPemilik && $booking->status === 'DIKEMBALIKAN')
        <div class="bg-purple-50 p-6 rounded-3xl border border-purple-200 shadow-xs mb-8">
            <h3 class="text-base font-bold text-slate-900 mb-1">Penyewa Telah Mengembalikan Barang</h3>
            <p class="text-xs text-slate-600 mb-4">
                Kondisi yang dilaporkan penyewa: <strong>{{ str_replace('_', ' ', strtoupper($booking->pengembalian->kondisi_barang ?? 'sesuai')) }}</strong>.
                Keterlambatan: <strong>{{ $booking->pengembalian->hari_keterlambatan ?? 0 }} hari</strong>.
            </p>

            <form action="{{ route('pengembalian.confirm', $booking->id) }}" method="POST" class="space-y-4 max-w-xl">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Verifikasi Kondisi Akhir Barang</label>
                    <select name="kondisi_barang_verifikasi" required class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                        <option value="sesuai_awal">Barang Diterima Baik (Sesuai Awal)</option>
                        <option value="rusak_ringan">Ada Lecet / Kerusakan Ringan</option>
                        <option value="rusak_berat">Rusak Berat</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Denda Keterlambatan (Rp)</label>
                        @php
                            $suggestedLateFee = ($booking->pengembalian->hari_keterlambatan ?? 0) * 20000;
                        @endphp
                        <input type="number" name="denda_keterlambatan" value="{{ $suggestedLateFee }}" min="0"
                               placeholder="Rp0"
                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <span class="text-[10px] text-slate-400">Contoh standar: Rp20.000 / hari</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Denda Kerusakan (Jika Ada)</label>
                        <input type="number" name="denda_kerusakan" value="0" min="0"
                               placeholder="Rp0"
                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Keterangan Denda Kerusakan (Opsional)</label>
                    <input type="text" name="deskripsi_kerusakan" placeholder="Misal: Biaya ganti tutup lensa yang hilang"
                           class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>

                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold text-xs shadow-xs transition">
                    Konfirmasi Pengembalian & Selesaikan Transaksi
                </button>
            </form>
        </div>
    @endif

    @if($isPenyewa && $booking->status === 'SELESAI' && !$booking->review)
        <div class="bg-amber-50 p-6 rounded-3xl border border-amber-200 shadow-xs mb-8">
            <h3 class="text-base font-bold text-slate-900 mb-1">Transaksi Selesai! Berikan Penilaian & Ulasan</h3>
            <p class="text-xs text-slate-600 mb-4">Bagikan pengalaman Anda menyewa barang dari {{ $booking->pemilik->name }}.</p>

            <form action="{{ route('review.store', $booking->id) }}" method="POST" class="space-y-4 max-w-xl">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Rating Bintang (1 - 5)</label>
                    <select name="rating" required class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="5">⭐⭐⭐⭐⭐ - 5 Bintang (Sangat Memuaskan)</option>
                        <option value="4">⭐⭐⭐⭐ - 4 Bintang (Bagus & Bersih)</option>
                        <option value="3">⭐⭐⭐ - 3 Bintang (Cukup Baik)</option>
                        <option value="2">⭐⭐ - 2 Bintang (Kurang Memuaskan)</option>
                        <option value="1">⭐ - 1 Bintang (Mengecewakan)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Komentar / Ulasan</label>
                    <textarea name="komentar" required rows="3" placeholder="Ceritakan kondisi barang dan keramahan pemilik..."
                              class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-amber-500"></textarea>
                </div>

                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-xs transition">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    @endif

    @if($booking->review)
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Ulasan Anda untuk Transaksi Ini</span>
                <span class="flex items-center gap-1 text-xs font-bold text-amber-500">
                    <i data-lucide="star" class="w-4 h-4 fill-amber-400"></i> {{ $booking->review->rating }}/5
                </span>
            </div>
            <p class="text-xs text-slate-700 italic">"{{ $booking->review->komentar }}"</p>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="package" class="w-4 h-4 text-emerald-600"></i> Informasi Barang & Durasi
            </h3>

            <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                <img src="{{ $booking->barang->foto }}" alt="" class="w-16 h-16 rounded-xl object-cover">
                <div>
                    <h4 class="text-sm font-bold text-slate-900">{{ $booking->barang->nama_barang }}</h4>
                    <span class="text-xs text-slate-500">{{ $booking->barang->kategori->nama }} • Kondisi: {{ $booking->barang->kondisi }}</span>
                    <div class="text-xs font-semibold text-emerald-600 mt-1">
                        Rp{{ number_format($booking->barang->harga_sewa_per_hari, 0, ',', '.') }}/hari
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Mulai</span>
                    <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Tanggal Selesai</span>
                    <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Total Hari Sewa</span>
                    <span class="font-bold text-slate-800">{{ $booking->jumlah_hari }} Hari</span>
                </div>
                <div>
                    <span class="text-slate-400 block font-medium">Metode Ambil</span>
                    <span class="font-bold text-slate-800">{{ $booking->metode_pengambilan === 'diantar' ? 'Diantar Pemilik' : 'Ambil Sendiri (Rp0)' }}</span>
                </div>
            </div>

            @if($booking->metode_pengambilan === 'diantar')
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <span class="text-slate-400 block font-medium">Alamat Pengantaran</span>
                    <span class="font-semibold text-slate-800">{{ $booking->alamat_pengantaran }} (±{{ $booking->jarak_km }} km)</span>
                </div>
            @endif

            @if($booking->catatan_penyewa)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <span class="text-slate-400 block font-medium">Catatan Penyewa</span>
                    <p class="text-slate-700 italic mt-0.5">{{ $booking->catatan_penyewa }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900 flex items-center gap-2">
                <i data-lucide="receipt" class="w-4 h-4 text-emerald-600"></i> Rincian Biaya & Pembayaran
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between text-slate-600">
                    <span>Biaya Sewa ({{ $booking->jumlah_hari }} hari × Rp{{ number_format($booking->barang->harga_sewa_per_hari, 0, ',', '.') }})</span>
                    <span class="font-semibold text-slate-900">Rp{{ number_format($booking->biaya_sewa, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Ongkos Kirim ({{ $booking->metode_pengambilan === 'diantar' ? $booking->jarak_km . ' km' : 'Ambil Sendiri' }})</span>
                    <span class="font-semibold text-slate-900">Rp{{ number_format($booking->ongkir, 0, ',', '.') }}</span>
                </div>
                <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-bold text-slate-900">
                    <span>Total Pembayaran</span>
                    <span class="text-emerald-600 font-extrabold text-base">Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block mb-2">Status Pembayaran</span>
                @if($booking->pembayaran)
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-1">
                        <div class="flex justify-between font-bold">
                            <span>Metode: {{ strtoupper($booking->pembayaran->metode) }}</span>
                            <span class="text-emerald-700">LUNAS</span>
                        </div>
                        <div class="text-[11px] text-emerald-700">No. Transaksi: {{ $booking->pembayaran->nomor_transaksi }}</div>
                        <div class="text-[11px] text-emerald-600">Waktu Bayar: {{ $booking->pembayaran->waktu_bayar->format('d M Y, H:i') }}</div>
                    </div>
                @else
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 text-xs text-slate-500">
                        Metode Pilihan: <strong class="text-slate-800">{{ strtoupper($booking->metode_pembayaran) }}</strong><br>
                        Status: <span class="text-amber-600 font-semibold">Belum Dibayar</span>
                    </div>
                @endif
            </div>

            @if($booking->dendas->count() > 0)
                <div class="pt-4 border-t border-slate-100">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-wider block mb-2">Denda Tercatat</span>
                    <div class="space-y-2">
                        @foreach($booking->dendas as $denda)
                            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-xs text-rose-800 flex justify-between items-center">
                                <div>
                                    <div class="font-bold">{{ $denda->jenis_label }}</div>
                                    <div class="text-[11px] text-rose-600">{{ $denda->deskripsi }}</div>
                                </div>
                                <div class="font-extrabold text-sm text-rose-700">
                                    Rp{{ number_format($denda->nominal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

