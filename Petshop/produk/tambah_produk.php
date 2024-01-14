<?php
// Memanggil file koneksi database
require '../config/connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Ambil data dari permintaan
  $nama_produk = $_POST['nama_produk'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];

  $cekIsdeleted = mysqli_query($con, "SELECT * FROM `produk` WHERE nama_produk='$nama_produk' AND is_deleted='y'");
  if (mysqli_num_rows($cekIsdeleted) > 0) {
    // Update is_deleted menjadi kosong
    $updateIsDeleted = mysqli_query($con, "UPDATE `produk` SET is_deleted='' WHERE nama_produk='$nama_produk'");
    if ($updateIsDeleted) {
      $response['status_code'] = 200;
      $response['message'] = "Status Diubah Menjadi Aktif";
    } else {
      $response['status_code'] = 201;
      $response['message'] = "Gagal Mengubah Status";
    }
    echo json_encode($response);
  } else {
    $cekNamaProduk = mysqli_query($con, "SELECT * FROM `produk` WHERE nama_produk='$nama_produk'");
    if (mysqli_num_rows($cekNamaProduk) > 0) {
      $response['status_code'] = 201;
      $response['message'] = "NAMA PRODUK SUDAH TERDAFTAR";
      echo json_encode($response);
    } else {

      $cekId = mysqli_query($con, "SELECT * FROM `produk` ORDER by id_produk desc limit 1");
      $da = mysqli_fetch_array($cekId);

      // Periksa apakah ada hasil dari query sebelum mengakses indeks
      if ($da) {
        $id_produk = $da['id_produk'] + 1;
      } else {
        // Set id_produk ke 1 jika tidak ada hasil dari query
        $id_produk = 1;
      }

      $insert = "INSERT INTO produk (id_produk, nama_produk, harga, stok)
                       VALUES ('$id_produk', '$nama_produk', '$harga', '$stok')";

      if (mysqli_query($con, $insert)) {
        $response['id_produk'] = (string)$id_produk;
        $response['nama_produk'] = $nama_produk;
        $response['harga'] = $harga;
        $response['stok'] = $stok;
        $response['status_code'] = 200;
        $response['message'] = "SUCCESS";
        echo json_encode($response);
      } else {
        $response['status_code'] = 201;
        $response['message'] = "FAILED!";
        echo json_encode($response);
      }
    }
  }
} else {
  $response = array();
  $response['status_code'] = 401;
  $response['message'] = "METHOD NOT ALLOWED";
  echo json_encode($response);
}
