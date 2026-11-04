/* ==========================================================================
   TemuBarang - interaksi halaman
   Setiap fitur dibuat sebagai fungsi kecil agar mudah dijelaskan.
   Semua fungsi dipanggil sekali di bagian paling bawah.
   ========================================================================== */

/** 1. Menu navigasi di layar kecil (tombol hamburger). */
function aktifkanMenuMobile() {
    const tombol = document.getElementById('tombol-menu');
    const menu = document.getElementById('menu-utama');
    if (!tombol || !menu) return;

    tombol.addEventListener('click', () => {
        const terbuka = menu.classList.toggle('terbuka');
        tombol.setAttribute('aria-expanded', String(terbuka));
        tombol.setAttribute('aria-label', terbuka ? 'Tutup menu' : 'Buka menu');
    });
}

/** 2. Tombol "Tampilkan / Sembunyikan" pada kolom kata sandi. */
function aktifkanTampilSandi() {
    document.querySelectorAll('[data-tampil-sandi]').forEach((tombol) => {
        const kolom = document.getElementById(tombol.dataset.tampilSandi);
        if (!kolom) return;

        tombol.addEventListener('click', () => {
            const tersembunyi = kolom.type === 'password';
            kolom.type = tersembunyi ? 'text' : 'password';
            tombol.textContent = tersembunyi ? 'Sembunyikan' : 'Tampilkan';
        });
    });
}

/** 3. Dialog konfirmasi untuk aksi penting (hapus, tandai selesai). */
function aktifkanDialogKonfirmasi() {
    const dialog = document.getElementById('dialog-konfirmasi');
    if (!dialog || typeof dialog.showModal !== 'function') return;

    const teksPesan = document.getElementById('dialog-pesan');
    const tombolYa = document.getElementById('dialog-ya');
    let formTertunda = null;

    document.querySelectorAll('form[data-konfirmasi]').forEach((form) => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            formTertunda = form;

            teksPesan.textContent = form.dataset.konfirmasi;
            tombolYa.textContent = form.dataset.tombol || 'Ya, lanjutkan';
            tombolYa.className = 'tombol ' + (form.dataset.gaya === 'utama' ? 'tombol--utama' : 'tombol--bahaya');

            dialog.showModal();
        });
    });

    dialog.addEventListener('close', () => {
        if (dialog.returnValue === 'ya' && formTertunda) formTertunda.submit();
        formTertunda = null;
        dialog.returnValue = '';
    });
}

/** 4. Pesan sukses hilang sendiri; semua pesan bisa ditutup manual. */
function aktifkanPesan() {
    document.querySelectorAll('.pesan').forEach((pesan) => {
        const tutup = () => pesan.remove();
        pesan.querySelector('.pesan__tutup')?.addEventListener('click', tutup);
        if (pesan.hasAttribute('data-hilang-otomatis')) setTimeout(tutup, 5000);
    });
}

/** 5. Pratinjau foto sebelum diunggah. */
function aktifkanPratinjauFoto() {
    const input = document.getElementById('foto');
    const kotak = document.getElementById('pratinjau');
    if (!input || !kotak) return;

    input.addEventListener('change', () => {
        const berkas = input.files[0];
        if (!berkas) return;

        if (berkas.size > 3 * 1024 * 1024) {
            alert('Ukuran foto maksimal 3 MB. Pilih foto yang lebih kecil.');
            input.value = '';
            return;
        }

        kotak.innerHTML = '';
        const gambar = document.createElement('img');
        gambar.alt = 'Pratinjau foto';
        gambar.src = URL.createObjectURL(berkas);
        kotak.appendChild(gambar);
        kotak.hidden = false;
    });
}

/** 6. Ubah status langsung dari dropdown (Dashboard Admin). */
function aktifkanKirimOtomatis() {
    document.querySelectorAll('select[data-kirim-otomatis]').forEach((pilihan) => {
        pilihan.addEventListener('change', () => pilihan.form.submit());
    });
}

/**
 * 7. Form buat laporan: label ikut berubah mengikuti jenis laporan
 *    (misalnya "Lokasi terakhir terlihat" vs "Lokasi ditemukan").
 *    Teksnya berasal dari method polimorfik di model (petaJenis).
 */
function aktifkanLabelJenis() {
    const form = document.getElementById('form-laporan');
    if (!form || !form.dataset.peta) return;

    const peta = JSON.parse(form.dataset.peta);

    const terapkan = () => {
        const dipilih = form.querySelector('input[name="jenis"]:checked');
        const info = dipilih ? peta[dipilih.value] : null;
        if (!info) return;

        form.querySelectorAll('[data-label]').forEach((el) => {
            el.textContent = info[el.dataset.label];
        });
    };

    form.querySelectorAll('input[name="jenis"]').forEach((radio) => radio.addEventListener('change', terapkan));
    terapkan();
}

document.addEventListener('DOMContentLoaded', () => {
    aktifkanMenuMobile();
    aktifkanTampilSandi();
    aktifkanDialogKonfirmasi();
    aktifkanPesan();
    aktifkanPratinjauFoto();
    aktifkanKirimOtomatis();
    aktifkanLabelJenis();
});
