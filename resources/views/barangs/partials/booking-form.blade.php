<form action="{{ route('booking.store', $barang->id) }}" method="POST" id="bookingForm" class="space-y-4">
    @csrf

    <!-- Date Inputs -->
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                Tanggal Mulai
            </label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                   min="{{ date('Y-m-d') }}"
                   value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
        </div>
        <div>
            <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                Tanggal Selesai
            </label>
            <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                   min="{{ date('Y-m-d') }}"
                   value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+1 day'))) }}"
                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
        </div>
    </div>

    <!-- Live Availability Notice -->
    <div id="availabilityNotice" class="hidden p-3.5 rounded-2xl text-xs flex items-center gap-2 font-medium"></div>

    <!-- Delivery Method -->
    <div>
        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
            Metode Pengambilan
        </label>
        <div class="grid grid-cols-2 gap-2">
            @if(in_array($barang->metode_pengambilan, ['ambil_sendiri', 'both']))
                <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                    <input type="radio" name="metode_pengambilan" value="ambil_sendiri" class="sr-only" checked onchange="calculateCost()">
                    <div class="flex items-center gap-2">
                        <i data-lucide="store" class="w-4 h-4 text-emerald-600"></i>
                        <span class="font-bold text-slate-800">Ambil Sendiri (Rp0)</span>
                    </div>
                </label>
            @endif

            @if(in_array($barang->metode_pengambilan, ['diantar', 'both']))
                <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                    <input type="radio" name="metode_pengambilan" value="diantar" class="sr-only" {{ $barang->metode_pengambilan === 'diantar' ? 'checked' : '' }} onchange="calculateCost()">
                    <div class="flex items-center gap-2">
                        <i data-lucide="truck" class="w-4 h-4 text-emerald-600"></i>
                        <span class="font-bold text-slate-800">Diantar Pemilik</span>
                    </div>
                </label>
            @endif
        </div>
    </div>

    <!-- Delivery Fields Container -->
    <div id="deliveryFields" class="hidden space-y-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
        <div>
            <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                Perkiraan Jarak Pengantaran (KM)
            </label>
            <input type="number" step="0.5" min="0.5" max="50" name="jarak_km" id="jarak_km" value="5"
                   onchange="calculateCost()" oninput="calculateCost()"
                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
            <span class="text-[10px] text-slate-400 mt-1 block">Tarif: Rp{{ number_format($barang->tarif_pengantaran_per_km, 0, ',', '.') }}/km</span>
        </div>
        <div>
            <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                Alamat Lengkap Pengantaran
            </label>
            <input type="text" name="alamat_pengantaran" placeholder="Jalan, No. Rumah, Patokan lokasi"
                   class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-medium">
        </div>
    </div>

    <!-- Payment Options -->
    <div>
        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
            Pilihan Metode Pembayaran
        </label>
        <div class="grid grid-cols-2 gap-2">
            @if(in_array($barang->metode_pembayaran, ['qris', 'both']))
                <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                    <input type="radio" name="metode_pembayaran" value="qris" class="sr-only" checked>
                    <div class="flex items-center gap-2">
                        <i data-lucide="qr-code" class="w-4 h-4 text-emerald-600"></i>
                        <span class="font-bold text-slate-800">Simulasi QRIS</span>
                    </div>
                </label>
            @endif

            @if(in_array($barang->metode_pembayaran, ['cash', 'both']))
                <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                    <input type="radio" name="metode_pembayaran" value="cash" class="sr-only" {{ $barang->metode_pembayaran === 'cash' ? 'checked' : '' }}>
                    <div class="flex items-center gap-2">
                        <i data-lucide="banknote" class="w-4 h-4 text-emerald-600"></i>
                        <span class="font-bold text-slate-800">Tunai (Cash / COD)</span>
                    </div>
                </label>
            @endif
        </div>
    </div>

    <!-- Notes -->
    <div>
        <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
            Catatan / Kebutuhan Tambahan
        </label>
        <textarea name="catatan_penyewa" rows="2" placeholder="Misal: untuk acara akhir pekan, mohon disiapkan tas cadangan"
                  class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium"></textarea>
    </div>

    <!-- Real-time Cost Calculation Card -->
    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
        <div class="flex justify-between text-slate-600 font-medium">
            <span>Biaya Sewa (<span id="calcDays" class="font-bold text-slate-800">1</span> hari)</span>
            <span class="font-bold text-slate-800" id="calcRental">Rp0</span>
        </div>
        <div class="flex justify-between text-slate-600 font-medium">
            <span>Ongkos Kirim</span>
            <span class="font-bold text-slate-800" id="calcOngkir">Rp0</span>
        </div>
        <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-extrabold text-slate-900">
            <span>Total Biaya</span>
            <span class="text-emerald-600 font-black text-base" id="calcTotal">Rp0</span>
        </div>
    </div>

    <!-- Rules Micro-Notice -->
    <div class="p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 text-[11px] text-blue-900 space-y-1">
        <div class="font-bold flex items-center gap-1.5 text-blue-800">
            <i data-lucide="info" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i>
            <span>Ketentuan Transaksi:</span>
        </div>
        <p>• Pemilik memiliki waktu <strong>24 jam</strong> untuk menyetujui booking Anda.</p>
        <p>• Ruang chat resmi aktif setelah pembayaran berhasil diselesaikan.</p>
    </div>

    <!-- Submit Button -->
    @if(!empty($isOwnerPreview))
        <div class="p-3 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs text-center font-bold">
            <i data-lucide="eye" class="w-4 h-4 inline-block mr-1"></i> Mode Pratinjau: Tombol ajukan sewa dinonaktifkan untuk pemilik
        </div>
    @else
        <button type="submit" id="btnSubmitBooking"
                class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-sm transition-all duration-200 shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2 hover:-translate-y-0.5 cursor-pointer">
            <i data-lucide="send" class="w-4 h-4"></i>
            <span>Ajukan Booking Sekarang</span>
        </button>
    @endif
</form>
