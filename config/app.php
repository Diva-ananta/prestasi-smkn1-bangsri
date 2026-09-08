<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of your application, which will be used when the
    | framework needs to place the application's name in a notification or
    | other UI elements where an application name needs to be displayed.
    |
    */

    'name' => env('APP_NAME', 'Laravel'),

    // Isi URL website setiap ekstrakurikuler. Nama di sebelah kiri harus sama
    // dengan nama ekstrakurikuler yang dipilih pada form prestasi.
    'ekstrakurikuler' => [
        'Tidak terkait ekstrakurikuler' => null,
        'Organisari Siswa Intra Sekolah (OSIS)' => 'https://smkn1bangsri.sch.id/extracurriculars/organisasi-siswa-intra-sekolah',
        'Passus Wira Adhi Dhaya' => 'https://smkn1bangsri.sch.id/extracurriculars/passus-wira-adhi-dhaya',
        'Pramuka Putra KH. Achmad Fauzan' => 'https://smkn1bangsri.sch.id/extracurriculars/pramuka-putra-kh-achmad-fauzan',
        'Pramuka Putri KH. Achmad Fauzan' => 'https://smkn1bangsri.sch.id/extracurriculars/pramuka-putri-kh-achmad-fauzan',
        'PMR Wira Sandya Adhimukti' => 'https://smkn1bangsri.sch.id/extracurriculars/pmr-wira-sandya-adhimukti',
        'Pencak Silat Cempaka Putih' => 'https://smkn1bangsri.sch.id/extracurriculars/pencak-silat-cempaka-putih',
        'Palawa Futsal Skansaba' => 'https://smkn1bangsri.sch.id/extracurriculars/palawa-futsal-skansaba',
        'Voli Eskasaba' => 'https://smkn1bangsri.sch.id/extracurriculars/bola-voli-smk-negeri-1-bangsri',
        'Basket Skansaba' => 'https://smkn1bangsri.sch.id/extracurriculars/basket-skansaba',
        'Handball Skansaba' => 'https://smkn1bangsri.sch.id/extracurriculars/hand-ball',
        'Natha Mandhala Pecinta Alam' => 'https://smkn1bangsri.sch.id/extracurriculars/natha-mandhala-pecinta-alam',
        'Anwa Sanskara Jurnalistik' => 'https://smkn1bangsri.sch.id/extracurriculars/anwa-sanskara-jurnalistik',
        'Webdev Taksan Nawasena' => 'https://smkn1bangsri.sch.id/extracurriculars/webdev-taksan-nawasena',
        'Badminton Eskasaba' => 'https://smkn1bangsri.sch.id/extracurriculars/badminton-skansaba',
        'Tari Arum Sekar Saba'=> 'https://smkn1bangsri.sch.id/extracurriculars/tari-arum-sekarsaba'
    ],

    /*
    |--------------------------------------------------------------------------
    | Application Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" your application is currently
    | running in. This may determine how you prefer to configure various
    | services the application utilizes. Set this in your ".env" file.
    |
    */

    'env' => env('APP_ENV', 'production'),

    /*
    |--------------------------------------------------------------------------
    | Application Debug Mode
    |--------------------------------------------------------------------------
    |
    | When your application is in debug mode, detailed error messages with
    | stack traces will be shown on every error that occurs within your
    | application. If disabled, a simple generic error page is shown.
    |
    */

    'debug' => (bool) env('APP_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | Application URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by the console to properly generate URLs when using
    | the Artisan command line tool. You should set this to the root of
    | the application so that it's available within Artisan commands.
    |
    */

    'url' => env('APP_URL', 'http://localhost'),

    /*
    |--------------------------------------------------------------------------
    | Application Timezone
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default timezone for your application, which
    | will be used by the PHP date and date-time functions. The timezone
    | is set to "UTC" by default as it is suitable for most use cases.
    |
    */

    'timezone' => 'UTC',

    /*
    |--------------------------------------------------------------------------
    | Application Locale Configuration
    |--------------------------------------------------------------------------
    |
    | The application locale determines the default locale that will be used
    | by Laravel's translation / localization methods. This option can be
    | set to any locale for which you plan to have translation strings.
    |
    */

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'faker_locale' => env('APP_FAKER_LOCALE', 'en_US'),

    /*
    |--------------------------------------------------------------------------
    | Encryption Key
    |--------------------------------------------------------------------------
    |
    | This key is utilized by Laravel's encryption services and should be set
    | to a random, 32 character string to ensure that all encrypted values
    | are secure. You should do this prior to deploying the application.
    |
    */

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | Maintenance Mode Driver
    |--------------------------------------------------------------------------
    |
    | These configuration options determine the driver used to determine and
    | manage Laravel's "maintenance mode" status. The "cache" driver will
    | allow maintenance mode to be controlled across multiple machines.
    |
    | Supported drivers: "file", "cache"
    |
    */

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
