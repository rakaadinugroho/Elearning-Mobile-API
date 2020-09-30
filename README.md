# Elearning Mobile API

Hallo! ini adalah kode terbuka untuk mengintegrasikan dengan [Elearning Mobile](http://github.com/rakaadinugroho/Elarning-Mobile) .

#Teknologi Pengembangan
1. CodeIgniter
2. JWT Authentication , contoh sederhananya [Disini](https://github.com/rakaadinugroho/Codeigniter-JWT-User-Authentication)
3. RESTFul APi ( dengan format JSON)

Penggunaan
=====
1. Untuk management Soal/Guru/Dll bisa menggunakan dasar aplikasi dari [Bang Akhwan90](https://github.com/akhwan90/cat).
2. Setup Table dari Sistem [Bang Akhwan90](https://github.com/akhwan90/cat)
3. Modifikasi Tabelnya dengan menambahkan seperti dibawah ini.
4. Tambahkan Kolom `Thumbnail` pada table `m_mapel` dan `tr_guru_tes` .
5. Tambahkan Trigger Untuk Menghapus `m_admin` jika `m_siswa` atau `m_guru` dihapus.
6. Tahbahkan SQL Berikut, Buat Table on MySQL
    
        CREATE TABLE keys (
       `id` int(11) NOT NULL,
       `user_id` int(11) NOT NULL,
       `key` varchar(40) NOT NULL,
       `level` int(2) NOT NULL,
       `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
       `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
       `ip_addresses` text,
       `date_created` int(11) NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
7. Sesuaikan `config.php` dan `database.php` di `Application/config` pada sistem API
8. Tara!!!

Demikian :)

## Dibangun Oleh
1. [Raka Adi Nugroho](http://github.com/rakaadinugroho)
2. Yourname?
