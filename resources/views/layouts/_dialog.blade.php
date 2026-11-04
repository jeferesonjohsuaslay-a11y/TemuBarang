{{-- Dialog konfirmasi bersama. Dibuka oleh JS untuk form ber-atribut data-konfirmasi. --}}
<dialog id="dialog-konfirmasi" class="dialog" aria-labelledby="dialog-judul">
    <form method="dialog">
        <h2 id="dialog-judul" class="dialog__judul">Konfirmasi</h2>
        <p id="dialog-pesan" class="dialog__pesan"></p>
        <div class="dialog__aksi">
            <button value="batal" class="tombol tombol--ghost">Batal</button>
            <button value="ya" class="tombol tombol--bahaya" id="dialog-ya">Ya, lanjutkan</button>
        </div>
    </form>
</dialog>
