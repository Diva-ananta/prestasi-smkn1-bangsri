@extends('layouts.admin')

@section('title', 'Dokumentasi API')

@section('content')
    <div class="page-shell">
        <div class="page-header animate-fade-in">
            <div>
                <p class="mb-2 text-[11px] font-semibold uppercase tracking-[0.24em] text-emerald-200">Developer</p>
                <h1 class="text-2xl font-bold md:text-3xl">Dokumentasi API</h1>
                <p class="mt-2 text-sm">Panduan penggunaan API SiPres untuk website yang terdaftar.</p>
            </div>
        </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Intro --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h1 class="text-2xl font-bold text-gray-900">
                        SiPres API
                    </h1>

                    <p class="mt-2 text-gray-600">
                        API resmi Sistem Informasi Prestasi Siswa SMKN 1 Bangsri.
                        API ini digunakan oleh website sekolah dan jurusan
                        untuk mengambil data prestasi dan artikel yang telah
                        dipublikasikan.
                    </p>

                    <div class="mt-5 rounded-lg border border-blue-200 bg-blue-50 p-4">
                        <div class="text-sm font-semibold text-blue-800">
                            Base URL
                        </div>

                        <code class="mt-1 block text-sm text-blue-900 break-all">
                            {{ url('/api/v1') }}
                        </code>
                    </div>

                </div>
            </div>


            {{-- Authentication --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        1. Authentication
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Semua endpoint API versi 1 membutuhkan API key dari
                        SiPres.
                    </p>

                    <div class="mt-4 rounded-lg bg-gray-900 p-4 overflow-x-auto">
                        <pre class="text-sm text-gray-100"><code>X-API-KEY: API_KEY_WEBSITE</code></pre>
                    </div>

                    <div class="mt-4 rounded-lg border border-yellow-200 bg-yellow-50 p-4">
                        <p class="text-sm text-yellow-800">
                            Jangan letakkan API key di JavaScript frontend,
                            HTML, atau repository publik. API key sebaiknya
                            disimpan di environment variable pada server
                            website pemakai API.
                        </p>
                    </div>

                </div>
            </div>


            {{-- Health --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        2. Health Check
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Digunakan untuk memeriksa apakah layanan API SiPres
                        sedang tersedia.
                    </p>

                    <div class="mt-4">
                        <span class="inline-flex rounded-md bg-green-100 px-2.5 py-1 text-xs font-semibold text-green-700">
                            GET
                        </span>

                        <code class="ml-2 text-sm text-gray-800">
                            /api/health
                        </code>
                    </div>

                    <div class="mt-4 rounded-lg bg-gray-900 p-4 overflow-x-auto">
<pre class="text-sm text-gray-100"><code>{
    "success": true,
    "status": "ok",
    "service": "SiPres API",
    "version": "v1"
}</code></pre>
                    </div>

                </div>
            </div>


            {{-- Prestasi --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        3. Prestasi
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Mengambil daftar prestasi siswa yang berstatus
                        <strong>Publish</strong>.
                    </p>

                    <div class="mt-4">
                        <span class="inline-flex rounded-md bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                            GET
                        </span>

                        <code class="ml-2 text-sm text-gray-800">
                            /api/v1/prestasi
                        </code>
                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Parameter
                    </h3>

                    <div class="mt-3 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                    <th class="px-4 py-3">Parameter</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 text-sm">

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>search</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        string
                                    </td>
                                    <td class="px-4 py-3">
                                        Mencari berdasarkan data prestasi atau peserta.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>jurusan</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        string
                                    </td>
                                    <td class="px-4 py-3">
                                        Filter berdasarkan jurusan peserta.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>ekstrakurikuler</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        string
                                    </td>
                                    <td class="px-4 py-3">
                                        Filter berdasarkan ekstrakurikuler.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>tahun</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        integer
                                    </td>
                                    <td class="px-4 py-3">
                                        Filter berdasarkan tahun prestasi.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>page</code>
                                    </td>
                                    <td class="px-4 py-3">
                                        integer
                                    </td>
                                    <td class="px-4 py-3">
                                        Nomor halaman. Pagination menggunakan
                                        12 data per halaman.
                                    </td>
                                </tr>

                            </tbody>

                        </table>
                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Contoh Request
                    </h3>

                    <div class="mt-3 rounded-lg bg-gray-900 p-4 overflow-x-auto">
<pre class="text-sm text-gray-100"><code>GET {{ url('/api/v1/prestasi') }}?jurusan=PPLG&tahun=2026&page=1

X-API-KEY: API_KEY_WEBSITE</code></pre>
                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Contoh Response
                    </h3>

                    <div class="mt-3 rounded-lg bg-gray-900 p-4 overflow-x-auto">
<pre class="text-sm text-gray-100"><code>{
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
}</code></pre>
                    </div>

                </div>
            </div>


            {{-- Artikel --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        4. Artikel
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        Mengambil artikel SiPres yang sudah dipublikasikan.
                    </p>

                    <div class="mt-4 space-y-3">

                        <div>
                            <span class="inline-flex rounded-md bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                GET
                            </span>

                            <code class="ml-2 text-sm text-gray-800">
                                /api/v1/artikel
                            </code>
                        </div>

                        <div>
                            <span class="inline-flex rounded-md bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-700">
                                GET
                            </span>

                            <code class="ml-2 text-sm text-gray-800">
                                /api/v1/artikel/{slug}
                            </code>
                        </div>

                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Parameter List Artikel
                    </h3>

                    <div class="mt-3 overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                    <th class="px-4 py-3">Parameter</th>
                                    <th class="px-4 py-3">Tipe</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 text-sm">

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>search</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        string
                                    </td>

                                    <td class="px-4 py-3">
                                        Mencari berdasarkan judul atau penulis.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>tahun</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        integer
                                    </td>

                                    <td class="px-4 py-3">
                                        Filter artikel berdasarkan tahun publikasi.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>page</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        integer
                                    </td>

                                    <td class="px-4 py-3">
                                        Nomor halaman.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Contoh Request
                    </h3>

                    <div class="mt-3 rounded-lg bg-gray-900 p-4 overflow-x-auto">
<pre class="text-sm text-gray-100"><code>GET {{ url('/api/v1/artikel') }}?tahun=2026&page=1

X-API-KEY: API_KEY_WEBSITE</code></pre>
                    </div>

                    <h3 class="mt-6 text-sm font-semibold text-gray-900">
                        Detail Artikel
                    </h3>

                    <div class="mt-3 rounded-lg bg-gray-900 p-4 overflow-x-auto">
<pre class="text-sm text-gray-100"><code>GET {{ url('/api/v1/artikel/{slug}') }}

X-API-KEY: API_KEY_WEBSITE</code></pre>
                    </div>

                </div>
            </div>


            {{-- Error --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        5. Error Response
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        API menggunakan HTTP status code untuk menunjukkan
                        hasil request.
                    </p>

                    <div class="mt-4 overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead>
                                <tr class="text-left text-xs font-medium text-gray-500 uppercase">
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Kode</th>
                                    <th class="px-4 py-3">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200 text-sm">

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>401</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        API_KEY_REQUIRED
                                    </td>

                                    <td class="px-4 py-3">
                                        API key tidak dikirim.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>401</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        INVALID_API_KEY
                                    </td>

                                    <td class="px-4 py-3">
                                        API key tidak valid atau client tidak aktif.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>404</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        ARTICLE_NOT_FOUND
                                    </td>

                                    <td class="px-4 py-3">
                                        Artikel tidak ditemukan atau belum dipublikasikan.
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-4 py-3">
                                        <code>500</code>
                                    </td>

                                    <td class="px-4 py-3">
                                        API_KEY_NOT_CONFIGURED
                                    </td>

                                    <td class="px-4 py-3">
                                        Konfigurasi API key SiPres belum tersedia.
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>

                </div>
            </div>


            {{-- Rate Limit --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h2 class="text-lg font-semibold text-gray-900">
                        6. Rate Limit
                    </h2>

                    <p class="mt-2 text-sm text-gray-600">
                        API SiPres menggunakan batas maksimal
                        <strong>60 request per menit</strong> untuk endpoint
                        API versi 1.
                    </p>

                    <div class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">

                        <div class="text-sm font-semibold text-gray-800">
                            60 request / menit
                        </div>

                        <div class="mt-1 text-sm text-gray-600">
                            Jika batas terlampaui, client perlu menunggu
                            sebelum melakukan request kembali.
                        </div>

                    </div>

                </div>
            </div>


            {{-- Footer --}}
            <div class="text-center text-sm text-gray-500 pb-4">
                SiPres API — SMKN 1 Bangsri
            </div>

        </div>
    </div>

    </div>
@endsection