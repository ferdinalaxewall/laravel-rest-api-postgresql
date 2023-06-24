<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Tentang Project

Laravel Restful API dengan Database PostgreSQL, dan Menggunakan **Service Repository Pattern**.

## Instalasi

Silahkan Clone Proyek ini dengan Mengikuti Command Berikut:

```bash
git clone https://github.com/ferdinalaxewall/laravel-rest-postgresql.git
```

Masih dalam Bash/CommandPrompt/Shell yang sama, Kalian Ketikan Command Berikut: 

```bash
composer install
```

atau

```bash
composer update
```

Selanjutnya, Duplikat File .env.example dan rename salah satunya menjadi .env, Lalu Buka Bash/CommandPrompt/Shell kalian, Dan Ketikan Command Berikut: 

```bash
cp .env.example .env
```

Selanjutnya Generate key untuk local .env kalian

```bash
php artisan key:generate
```

## Cara Penggunaan

Silahkan buka file .env kalian, lalu ubah bagian berikut dan sesuaikan dengan environment yang kalian siapkan

```php
DB_HOST=xxx
DB_PORT=xxx
DB_DATABASE=xxx
DB_USERNAME=xxx
DB_PASSWORD=xxx
```

## Konfigurasi
Untuk melihat Dokumentasi API, Silahkan ketik perintah dibawah untuk konfigurasi Package Swagger:

```bash
$ php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
$ php artisan l5-swagger:generate
```

Jika belum ada tabel dalam database, Migrasi kan Table Untuk Database yang telah di buat pada .env file sebelumnya

```bash
php artisan migrate
```

## Dokumentasi API
Untuk Melihat Dokumentasi API, anda hanya perlu mengunjungi menu "Dokumentasi API" yang ada pada Navigation Bar (Navbar) atau **http://(app_url)/api/documentation**
