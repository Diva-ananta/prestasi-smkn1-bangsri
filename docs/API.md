# SiPres API v1
API resmi Sistem Informasi Prestasi Siswa
SMK Negeri 1 Bangsri.

Base URL: https://sipres.smkn1bangsri.sch.id/api

Versi API: v1

---

## Authentication
Endpoint API v1 membutuhkan API Key.
Kirim API Key melalui HTTP Header:
X-API-KEY: API_KEY_KAMU
Jangan menaruh API Key di frontend atau JavaScript browser.
API Key hanya boleh disimpan pada server website yang menggunakan API.

---

# 1. Health Check
Digunakan untuk mengecek apakah layanan API SiPres sedang aktif.

### Endpoint
GET /api/health

### Authentication
Tidak membutuhkan API Key.

### Response
```json
{
    "success": true,
    "status": "ok",
    "service": "SiPres API",
    "version": "v1"
}

---

#2. Daftar Prestasi
Mengambil daftar prestasi siswa yang berstatus Publish.

### Endpoint
GET /api/v1/prestasi

### Authentication
Membutuhkan API Key.

#####Parameter filter
| Parameter       | Tipe    | Wajib | Keterangan                         |Contoh
| --------------- | ------- | ----- | ---------------------------------- |---------------------------------------------------------------------|
| search          | string  | Tidak | Mencari prestasi atau peserta      |GET /api/v1/prestasi?search=Safety                                    |
| jurusan         | string  | Tidak | Filter berdasarkan jurusan siswa   |GET /api/v1/prestasi?jurusan=PPLG                                     |
| ekstrakurikuler | string  | Tidak | Filter berdasarkan ekstrakurikuler |GET /api/v1/prestasi?ekstrakurikuler=Passus%20Wira%20Adhi%20Dhaya     |
| tahun           | integer | Tidak | Filter berdasarkan tahun           |GET /api/v1/prestasi?tahun=2026                                       |
| page            | integer | Tidak | Nomor halaman                      |GET /api/v1/prestasi?page=2                                           |
kombinasi Filter |GET /api/v1/prestasi?jurusan=PPLG&tahun=2026&page=1

##### Response
{
    "success": true,
    "data": [
        {
            "nama_lomba": "Safety Ridding",
            "kategori": "Keterampilan",
            "ekstrakurikuler": null,
            "tingkat": "Nasional",
            "penyelenggara": "Astra Honda",
            "tanggal_mulai": "2026-09-15",
            "tanggal_selesai": null,
            "lokasi": null,
            "bidang_lomba": null,
            "hasil": "Juara 4",
            "kategori_juara": null,
            "foto_url": null,
            "detail_url": "https://sipres.smkn1bangsri.sch.id/prestasi/...",
            "peserta": [
                {
                    "nama": "Nama Siswa",
                    "jurusan": "PPLG",
                    "kelas": "12",
                    "peran": "Anggota"
                }
            ]
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 12,
        "total": 1,
        "last_page": 1,
        "from": 1,
        "to": 1
    }
}

#3. Daftar Artikel
Mengambil artikel yang sudah berstatus Publish dan sudah mencapai tanggal publikasi.

## Endpoint
GET /api/v1/artikel

### Authentication
Membutuhkan API Key.

#####Parameter filter
| Parameter | Tipe    | Wajib | Keterangan                         |Contoh
| --------- | ------- | ----- | ---------------------------------- |--------------------
| search    | string  | Tidak | Mencari judul atau penulis         |GET /api/v1/artikel?search=prestasi
| tahun     | integer | Tidak | Filter berdasarkan tahun publikasi |GET /api/v1/artikel?tahun=2026
| page      | integer | Tidak | Nomor halaman                      |GET /api/v1/artikel?page=2
Kombinasi :GET /api/v1/artikel?search=prestasi&tahun=2026


##### Response
{
    "success": true,
    "data": [
        {
            "judul": "Judul Artikel",
            "slug": "judul-artikel",
            "isi": "Isi artikel...",
            "gambar_url": "https://sipres.smkn1bangsri.sch.id/storage/...",
            "penulis": "Admin",
            "tanggal_publikasi": "2026-09-22",
            "status": "Publish",
            "prestasi_id": null,
            "detail_url": "https://sipres.smkn1bangsri.sch.id/artikel/judul-artikel"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 12,
        "total": 1,
        "last_page": 1,
        "from": 1,
        "to": 1
    }
}
#3. Detail Artikel
Mengambil satu artikel berdasarkan slug.

## Endpoint
GET /api/v1/artikel/{slug}

### Authentication
Membutuhkan API Key.

#####Contoh
GET /api/v1/artikel/judul-artikel

##### Response
{
    "success": true,
    "data": {
        "judul": "Judul Artikel",
        "slug": "judul-artikel",
        "isi": "Isi artikel...",
        "gambar_url": "https://sipres.smkn1bangsri.sch.id/storage/...",
        "penulis": "Admin",
        "tanggal_publikasi": "2026-09-22",
        "status": "Publish",
        "prestasi_id": null,
        "detail_url": "https://sipres.smkn1bangsri.sch.id/artikel/judul-artikel"
    }
}
