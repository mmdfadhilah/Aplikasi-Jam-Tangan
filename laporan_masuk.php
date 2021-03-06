<?php  
session_start();

if ( !isset( $_SESSION["login"]) ) {
    header("Location: login.php");
    exit;
}

// memanggil function.php
require 'functions.php';

// pagination

// konfigurasi
$jumlahDataPerHalaman = 4;

// untuk menghitung banyaknya data di dalam array assosiatif (count)
$jumlahData = count(query("SELECT * FROM barang_masuk") );

// untuk membulatkan hasil bagi ke atas (ceil)
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);

// operator ternary untuk cek halaman
$halamanAktif = ( isset($_GET["halaman"]) ) ? $_GET["halaman"] : 1;

// menghitung index buat menampilkan di tabel
$awalData = ( $jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;

// // untuk mengetahui halaman keberapa
// if ( isset($_GET["halaman"]) ) {
//     $halamanAktif = $_GET["halaman"];
// } else {
//     $halamanAktif = 1;
// }

// variabel untuk meyimpan data tabel (lemari)
$barang_masuk = query("SELECT * FROM barang_masuk ORDER BY id_bm DESC LIMIT $awalData, $jumlahDataPerHalaman");

// tombol cari ditekan
if ( isset($_POST["cari"]) ) {
    $barang_masuk = cari_masuk($_POST["keyword"]);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Laporan Barang Masuk</title>
    <link rel="stylesheet" type="text/css" href="css/style-laporan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>

<div class="container">
    <div class="header">
        <p>Jam Tangan</p>
    </div>

    <div class="page">
        <i class="fa fa-arrow-right"></i>
        <p> Laporan Barang Masuk</p>
    </div>

    <!-- Navigasi -->

    <div class="sidebar">
        <a href="index.php">Home</a>
        <a href="tambah.php">Tambah Data</a>
        <a href="penjualan.php">Penjualan</a>
        <a href="barang_masuk.php">Barang Masuk</a>
        <a href="laporan_jual.php">Laporan Penjualan</a>
        <a href="laporan_masuk.php">Laporan Barang Masuk</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Pencarian dan cetak -->

    <div class="content">
        <div class="wrapper">
            <div id="tombol">
                    <ul class="tombol-cari-cetak">
                        <li>
                            <form action="" method="post" class="cari">
                                <ul>
                                    <li>
                                        <input type="text" name="keyword" size="40" autofocus placeholder="Masukkan keyword pencarian!" autocomplete="off" class="input_cari">
                                        </li>
                                    <li>
                                        <div class="tmbl_cari">
                                            <button type="submit" name="cari"><i class="fa fa-search"></i></button>
                                        </div>
                                    </li>
                                </ul>
                            </form>
                        </li>

                        <li>
                            <form action="cetak_masuk.php" method="post" target="_blank">
                                <div class="tmbl_cari">
                                    <button type="submit" name="cetak">Cetak</button>
                                </div>
                            </form>
                        </li>
                    </ul>
            </div>

    <!-- Tabel -->

            <table class="table1">
                <tr>
                    <th>No.</th>
                    <th>Aksi</th>
                    <th>Kode Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Masuk</th>
                </tr>

                <?php $i = 1; ?>
                <?php foreach( $barang_masuk as $row ) :  ?>
                <tr>
                    <td><?= $i; ?></td>
                    <td>

                        <!-- Button Hapus -->
                        <span id="hapus"><a href="hapus_laporan_masuk.php?id_bm=<?= $row["id_bm"]; ?>" onclick="return confirm('Yakin?');">Hapus</a></span>
                        <!-- Button Ubah -->
                        <a href="ubah_masuk.php?id_bm=<?= $row["id_bm"]; ?>">Ubah</a>

                    </td>
                    <td><?= $row["kode_barang"]; ?></td>
                    <td><?= $row["jumlah"]; ?></td>
                    <td><?= $row["tgl_masuk"]; ?></td>
                </tr>
                <?php $i++; ?>
                <?php endforeach; ?>
            </table>

        <!-- navigasi halaman -->

            <div class="navigasi">
                <?php if ( $halamanAktif > 1 ) : ?>
                    <a href="?halaman=<?= $halamanAktif - 1; ?>">&laquo;</a>
                <?php endif; ?>

                <?php for( $i = 1; $i <= $jumlahHalaman; $i++) : ?>
                    <?php if ( $i == $halamanAktif ) : ?>
                        <a href="?halaman=<?= $i; ?>" style="font-weight: bold; color: white;"><?= $i; ?></a>
                    <?php else : ?>
                        <a href="?halaman=<?= $i; ?>"><?= $i; ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if ( $halamanAktif < $jumlahHalaman ) : ?>
                    <a href="?halaman=<?= $halamanAktif + 1; ?>">&raquo;</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="clear"></div>

    <div class="footer">
        <p>Copyright &copy; 2020 by Muhammad Fadhilah</p>
    </div>
</div>
</body>
</html>