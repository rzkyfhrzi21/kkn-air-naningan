# Pekon Air Naningan — Dokumentasi Arsitektur Data

> Proyek: Portal Desa KKN Air Naningan  
> Stack: PHP 8.3 MVC Native · Tailwind CSS Browser CDN v4 · Bootstrap 5 JS · Data JSON flat

---

## Arsitektur Data (JSON Flat-File)

Semua data disimpan di `public/data/*.json`.  
Model PHP membaca/menulis via `flock` + backup otomatis sebelum overwrite.  
**Tidak ada database** — migrasi ke DB di masa depan cukup ubah Model tanpa sentuh Controller/View.

---

## Skema Tabel JSON

### 1. `umkm.json` — Kelola UMKM

Digunakan di: **Halaman Publik `/umkm`** + **Admin `/admin/kelola-umkm`**

```json
[
  {
    "id": "umkm-1728387600",
    "nama": "Kopi Robusta Pak Slamet",
    "slug": "kopi-robusta-pak-slamet",
    "kategori": "Makanan & Minuman",
    "deskripsi": "Kopi robusta grade A hasil panen sendiri, diproses secara wet-hulling tradisional.",
    "harga": 85000,
    "satuan": "250g",
    "pemilik": "Slamet Riyadi",
    "no_wa": "6281234567890",
    "foto": "uploads/umkm/umkm-1728387600.jpg",
    "is_featured": true,
    "created_at": "2024-10-08T10:00:00+07:00",
    "updated_at": "2024-10-08T10:00:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `id` | `string` | Format: `umkm-{timestamp}` |
| `nama` | `string` | Nama produk/usaha |
| `slug` | `string` | URL-friendly, dari nama |
| `kategori` | `string` | Makanan & Minuman / Kerajinan / Pertanian / Jasa |
| `deskripsi` | `string` | Deskripsi detail |
| `harga` | `integer` | Harga dalam Rupiah |
| `satuan` | `string` | Per buah / Per kg / Per paket |
| `pemilik` | `string` | Nama pemilik |
| `no_wa` | `string` | Format: `62xxx` (tanpa + atau tanda baca) |
| `foto` | `string` | Path relatif dari webroot |
| `is_featured` | `boolean` | Ditampilkan di hero beranda |
| `created_at` | `string` | ISO 8601 |
| `updated_at` | `string` | ISO 8601 |

---

### 2. `wisata.json` — Kelola Wisata

Digunakan di: **Halaman Publik `/wisata`** + **Admin `/admin/kelola-wisata`**

```json
[
  {
    "id": "wisata-1728387600",
    "nama": "Air Terjun Way Lalaan",
    "slug": "air-terjun-way-lalaan",
    "kategori": "Air Terjun",
    "deskripsi": "Air terjun bertingkat dengan kolam alami di kaki Bukit Pesagi.",
    "lokasi": "Dusun Talang Baru",
    "koordinat": { "lat": -5.2341, "lng": 104.4562 },
    "tiket": 10000,
    "jam_buka": "07:00",
    "jam_tutup": "17:00",
    "hari_operasi": ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"],
    "status": "buka",
    "foto_utama": "uploads/wisata/wisata-1728387600.jpg",
    "galeri": ["uploads/wisata/wisata-1728387600-2.jpg"],
    "fasilitas": ["Parkir", "Warung Makan", "Toilet"],
    "created_at": "2024-10-08T10:00:00+07:00",
    "updated_at": "2024-10-08T10:00:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `kategori` | `string` | Air Terjun / Titik Pandang / Wisata Alam / Agrowisata |
| `koordinat` | `object` | `lat` + `lng` float untuk embed peta |
| `tiket` | `integer` | Rupiah (0 = gratis) |
| `status` | `string` | `buka` atau `tutup` |
| `galeri` | `array` | Path foto tambahan |
| `fasilitas` | `array` | Daftar fasilitas tersedia |

---

### 3. `potensi.json` — Kelola Potensi Desa

Digunakan di: **Halaman Publik `/potensi`** + **Admin `/admin/kelola-potensi`**

```json
[
  {
    "id": "potensi-1728387600",
    "nama": "Kopi Robusta",
    "slug": "kopi-robusta",
    "kategori": "Perkebunan",
    "deskripsi_singkat": "Produksi tahunan mencapai 50 ton dengan kualitas ekspor.",
    "deskripsi_panjang": "Kopi Air Naningan memiliki Indikasi Geografis resmi dari Kemenkumham. Dibudidayakan pada ketinggian 800-1200 mdpl dengan proses wet-hulling tradisional.",
    "produksi_per_tahun": "50 ton",
    "foto": "uploads/potensi/potensi-1728387600.jpg",
    "is_unggulan": true,
    "urutan": 1,
    "created_at": "2024-10-08T10:00:00+07:00",
    "updated_at": "2024-10-08T10:00:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `kategori` | `string` | Perkebunan / Pertanian / Industri Rumahan / Kerajinan / Peternakan |
| `is_unggulan` | `boolean` | Tampil di hero section halaman potensi |
| `urutan` | `integer` | Urutan tampil di halaman publik |
| `produksi_per_tahun` | `string\|null` | Opsional |

---

### 4. `berita.json` — Kelola Berita

Digunakan di: **Halaman Publik `/berita`** + **Admin `/admin/kelola-berita`**

```json
[
  {
    "id": "berita-1728387600",
    "judul": "Panen Raya Kopi Robusta Air Naningan Sukses Digelar",
    "slug": "panen-raya-kopi-robusta-2024",
    "kategori": "Kegiatan",
    "ringkasan": "Petani kopi merayakan panen raya dengan hasil yang melimpah tahun ini.",
    "konten": "<p>Isi artikel lengkap, sudah di-sanitize sebelum disimpan...</p>",
    "foto_sampul": "uploads/berita/berita-1728387600.jpg",
    "penulis": "Admin Pekon",
    "status": "terbit",
    "tanggal_terbit": "2024-10-08",
    "created_at": "2024-10-07T20:00:00+07:00",
    "updated_at": "2024-10-08T06:00:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `kategori` | `string` | Pengumuman / Kegiatan / Bansos / Infrastruktur |
| `konten` | `string` | HTML dari editor — **wajib di-sanitize (strip_tags whitelist) saat simpan** |
| `status` | `string` | `draft` atau `terbit` |
| `tanggal_terbit` | `string` | Format `YYYY-MM-DD` |

---

### 5. `galeri.json` — Kelola Galeri

Digunakan di: **Halaman Publik `/galeri`** + **Admin `/admin/kelola-galeri`**

```json
[
  {
    "id": "galeri-1728387600",
    "judul": "Pemandangan Pagi Desa",
    "kategori": "Landscape",
    "tipe": "foto",
    "file": "uploads/galeri/galeri-1728387600.jpg",
    "thumbnail": "uploads/galeri/thumb/galeri-1728387600.jpg",
    "ukuran_bytes": 2400000,
    "is_featured": true,
    "urutan": 1,
    "created_at": "2024-10-08T07:00:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `tipe` | `string` | `foto` atau `video` |
| `thumbnail` | `string` | Auto-generate dari foto (GD), atau frame video |
| `ukuran_bytes` | `integer` | Validasi: foto max 2MB, video max 15MB |
| `is_featured` | `boolean` | Tampil di grid beranda |
| `urutan` | `integer` | Urutan tampil |

---

### 6. `profil.json` — Profil Desa

Digunakan di: **Halaman Publik `/profil`** + **Admin `/admin/kelola-profil`**  
*Objek tunggal (bukan array) — satu profil desa.*

```json
{
  "nama_desa": "Pekon Air Naningan",
  "nama_kepala": "Supriyanto, S.H.",
  "berdiri_tahun": 1957,
  "luas_wilayah_ha": 1240,
  "visi": "Terwujudnya Pekon Air Naningan yang mandiri, sejahtera, dan berbudaya berbasis potensi alam.",
  "misi": [
    "Meningkatkan kualitas layanan publik berbasis teknologi.",
    "Mengembangkan potensi pertanian dan perkebunan unggulan.",
    "Memperkuat ekonomi kreatif dan UMKM warga."
  ],
  "sejarah": "<p>Pekon Air Naningan berdiri pada tahun 1957...</p>",
  "logo": "uploads/profil/logo-desa.png",
  "foto_kepala": "uploads/profil/foto-kepala.jpg",
  "kependudukan": {
    "total": 3842,
    "laki_laki": 1921,
    "perempuan": 1921,
    "kepala_keluarga": 980,
    "per_dusun": [
      { "nama": "Dusun I",   "jumlah": 960  },
      { "nama": "Dusun II",  "jumlah": 1050 },
      { "nama": "Dusun III", "jumlah": 890  },
      { "nama": "Dusun IV",  "jumlah": 942  }
    ]
  },
  "mata_pencaharian": [
    { "jenis": "Petani Kopi",    "persen": 62 },
    { "jenis": "Pedagang",       "persen": 18 },
    { "jenis": "Pekerja Lepas",  "persen": 12 },
    { "jenis": "Lainnya",        "persen": 8  }
  ],
  "kontak": {
    "alamat": "Jl. Raya Air Naningan No.1, Kec. Air Naningan, Kab. Tanggamus 35379",
    "email": "info@airnaningan.desa.id",
    "no_wa": "6281234567890",
    "jam_layanan": "Senin - Jumat, 08:00 - 15:00 WIB"
  },
  "updated_at": "2024-10-01T08:00:00+07:00"
}
```

---

### 7. `pesan.json` — Pesan Masuk

Digunakan di: **Admin `/admin/pesan-masuk`**  
*(Write-only dari form publik `/kontak` — publik tidak membaca file ini)*

```json
[
  {
    "id": "pesan-1728387600",
    "nama": "Budi Santoso",
    "email": "budi.s@example.com",
    "no_wa": "6281234567890",
    "subjek": "Tanya Jadwal Wisata Air Terjun",
    "kategori": "Pertanyaan Umum",
    "pesan": "Selamat pagi admin, saya ingin menanyakan...",
    "status": "belum_dibaca",
    "balasan": "",
    "dibalas_at": null,
    "created_at": "2024-10-14T09:42:00+07:00"
  }
]
```

| Field | Tipe | Keterangan |
|---|---|---|
| `kategori` | `string` | Pertanyaan Umum / Aduan / Kolaborasi / Lainnya |
| `status` | `string` | `belum_dibaca` / `sudah_dibaca` / `dibalas` / `diarsipkan` |
| `balasan` | `string` | Teks balasan admin |
| `dibalas_at` | `string\|null` | ISO 8601 atau `null` |

---

### 8. `pengaturan.json` — Pengaturan Situs

Digunakan di: **Admin `/admin/pengaturan`**  
*Objek tunggal — konfigurasi global situs.*

```json
{
  "nama_desa": "Pekon Air Naningan",
  "logo": "uploads/profil/logo-desa.png",
  "no_wa": "6281234567890",
  "alamat": "Jl. Raya Air Naningan No.1, Tanggamus",
  "jam_layanan": "Senin - Jumat, 08:00 - 15:00 WIB",
  "seo": {
    "meta_title": "Pekon Air Naningan - Potensi Agraria & Wisata Tanggamus",
    "meta_description": "Website resmi Pekon Air Naningan. Temukan informasi UMKM, wisata alam, dan potensi desa.",
    "og_image": "uploads/profil/og-image.jpg"
  },
  "updated_at": "2024-10-01T08:00:00+07:00"
}
```

---

## Peta Relasi: Halaman → File JSON

| Halaman Publik | File JSON Dibaca | Metode Render |
|---|---|---|
| `/` (Beranda) | `umkm.json` · `wisata.json` · `potensi.json` · `berita.json` | PHP penuh (SEO-friendly) |
| `/profil` | `profil.json` | PHP penuh |
| `/umkm` | `umkm.json` | PHP penuh + AJAX filter/search |
| `/wisata` | `wisata.json` | PHP penuh + AJAX filter/search |
| `/potensi` | `potensi.json` | PHP penuh |
| `/berita` | `berita.json` | PHP penuh + AJAX filter/search |
| `/galeri` | `galeri.json` | PHP penuh + AJAX filter |
| `/kontak` | *(write → `pesan.json`)* | PHP penuh |

| Halaman Admin | File JSON Dibaca/Ditulis |
|---|---|
| `/admin` (Dashboard) | Semua (hitung total tiap entitas) |
| `/admin/kelola-umkm` | `umkm.json` |
| `/admin/kelola-wisata` | `wisata.json` |
| `/admin/kelola-potensi` | `potensi.json` |
| `/admin/kelola-berita` | `berita.json` |
| `/admin/kelola-galeri` | `galeri.json` |
| `/admin/kelola-profil` | `profil.json` |
| `/admin/pesan-masuk` | `pesan.json` |
| `/admin/pengaturan` | `pengaturan.json` |

---

## Konvensi Teknis

| Aspek | Aturan |
|---|---|
| **ID** | Format `{entitas}-{timestamp}` misal `umkm-1728387600` |
| **Foto** | Rename ke `{id}.{ext}` saat upload, simpan di `public/uploads/{entitas}/` |
| **Backup** | Setiap write backup ke `secure/backup/{entitas}_{YYYY-MM-DD_His}.json` |
| **flock** | Write pakai `LOCK_EX`, baca pakai `LOCK_SH` |
| **Sanitasi HTML** | Field `konten`/`sejarah` wajib `strip_tags` dengan whitelist sebelum disimpan |
| **no_wa** | Simpan format `62xxx` tanpa spasi/tanda baca; validasi: harus diawali `62` |
| **Upload foto** | Validasi MIME asli via `finfo_file`, bukan ekstensi — maks 2MB |
| **Upload video** | Validasi MIME asli, maks 15MB — HEIC/MKV harus diputuskan: convert atau tolak |

---

## Security Warning Console

File `security-warning.js` di-include di:

- **Landing page** — via `app/Views/public/partials/footer.php`  
- **Halaman login admin** — via `app/Views/admin/login/index.php`  
- **Seluruh dashboard admin** — via `app/Views/admin/partials/footer.php`  

Menampilkan ASCII art peringatan di DevTools console untuk mencegah social engineering terhadap pengunjung awam yang terbuka console-nya.
