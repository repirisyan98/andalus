<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Sistem Pembayaran Air Cluster Andalus

### System requirement 
- Php 8.2
- Mysql 8.0
- Internet Connection (ada beberapa asset yang diakses secara online)

### Instalasi
- git clone (link repository)
- Lakukan perintah di dalam folder 
    - composer install 
    - .env.example -> .env
    - php artisan key:generate
    - php artisan migrate
    - php artisan db:seed
    - php artisan storage:link

### Account
- username : admin
- password : 12345678

### Setup Aplikasi
- Storage Folder
    - bukti_transfer
- vendor/laravel/ui/auth-backend/AuthenticatesUsers.php
    - function username() => return email to return username
- .env
    - APP_URL=http://url/public
    - jika tidak di hosting langsung ke web server maka hapus public nya

### Note
- Jika melakukan instalasi di windows dan di hosting ke web server xampp, instalasi xampp jangan di simpan di local disk C
- add php gd extension di php.ini

