## Cara Instalasi

1. Clone Repositori : `git clone https://github.com/AlvinDaeli748/DashboardProject.git`
2. Akses Folder Repositori : `cd DashboardProject`
3. Jalankan Composer : `composer install`
4. Setup env : `cd env .env`
    * Uncomment `CI_ENVIRONMENT`
    * Isi informasi database anda
        * CI_ENVIRONMENT = production
        * database.default.database = dbproject
        * database.default.username = root    
        * database.default.password =         
        * database.default.DBDriver = MySQLi
5. Pastikan ekstensi berikut aktif pada `php.ini` dengan cara uncomment ekstensi berikut. Jalankan `php --ini` untuk mengecek lokasi file `php.ini` yang digunakan.
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
6. Tambah Database di Phpmyadmin dengan nama database `dbproject`
    * Database berupa MySQL, dapat menggunakan XAMPP untuk akses ke `localhost/phpmyadmin`
7. Export database dan seed data : `php spark migrate -all`
    * Apabila ingin menambah data, jalankan `php spark db:seed PenjualanSeeder` untuk menambah dummy data sebanyak 500 data.
8. Start Website : `php spark serve`
    * Website dapat diakses dengan link `http://localhost:8080`
9. Data untuk Login terdapat pada `Akun_Admin.txt`