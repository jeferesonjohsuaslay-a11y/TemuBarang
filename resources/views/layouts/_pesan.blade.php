@if (session('sukses'))
    <div class="pesan pesan--sukses" role="status" data-hilang-otomatis>
        <x-icon name="check-circle" :size="20" />
        <span>{{ session('sukses') }}</span>
        <button type="button" class="pesan__tutup" aria-label="Tutup pesan"><x-icon name="x" :size="18" /></button>
    </div>
@endif
@if (session('galat'))
    <div class="pesan pesan--galat" role="alert">
        <x-icon name="alert" :size="20" />
        <span>{{ session('galat') }}</span>
        <button type="button" class="pesan__tutup" aria-label="Tutup pesan"><x-icon name="x" :size="18" /></button>
    </div>
@endif
