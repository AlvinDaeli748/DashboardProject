## Cara Instalasi

1. Clone Repositori : `git clone https://github.com/AlvinDaeli748/DashboardProject.git`
2. Akses Folder Repositori : `cd DashboardProject`
3. Jalankan Composer : `composer install`
4. Setup env : `cd env .env`
    * Uncomment `CI_ENVIRONMENT`
5. Koneksi Database dengan MySQL dengan nama database  `dbproject`
6. Pastikan ekstensi berikut aktif pada `php.ini` dengan cara uncomment ekstensi berikut.
    * intl
    * mbstring
    * mysqli
    * fileinfo
    * gd
    * zip
    * openssl
    * json
    * mysqlnd
    * extension_dir="ext"
        * Jalankan `php -m` untuk memastikan ekstensi aktif
7. Export database dan seed data : `php spark migrate -all`
    * Jalankan `php spark db:seed PenjualanSeeder` untuk menambah dummy data sebanyak 500 data.
8. Start Website : `php spark serve`
    * Website dapat diakses dengan link `http://localhost:8080`