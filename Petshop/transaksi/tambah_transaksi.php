<?php
// Memanggil file koneksi database
require '../config/connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {

  $tgl_transaksi = $_POST['tgl_transaksi'];
  $id_produk = $_POST['id_produk'];
  $jumlah_barang = $_POST['jumlah_barang'];
  $total_harga = $_POST['total_harga'];
  $id_pegawai = $_POST['id_pegawai'];
  $id_pelanggan = $_POST['id_pelanggan'];

  if (DateTime::createFromFormat('Y-m-d', $tgl_transaksi) !== false) {
    $cekIdPegawai = mysqli_query($con, "SELECT * FROM `pegawai` WHERE id_pegawai='$id_pegawai'");
    if (mysqli_num_rows($cekIdPegawai) > 0) {
      $cekIdPelanggan = mysqli_query($con, "SELECT * FROM `pelanggan` WHERE id_pelanggan='$id_pelanggan'");
      if (mysqli_num_rows($cekIdPelanggan) > 0) {
        $cekIdProduk = mysqli_query($con, "SELECT * FROM `produk` WHERE id_produk='$id_produk'");
        if (mysqli_num_rows($cekIdProduk) > 0) {
          $insert = "INSERT INTO transaksi (tgl_transaksi, id_produk, jumlah_barang, total_harga, pegawai_id_pegawai, pelanggan_id_pelanggan)
                     VALUES ('$tgl_transaksi', '$id_produk', '$jumlah_barang', '$total_harga', '$id_pegawai', '$id_pelanggan')";

          if (mysqli_query($con, $insert)) {
            $response['tgl_transaksi'] = $tgl_transaksi;
            $response['id_produk'] = $id_produk;
            $response['jumlah_barang'] = $jumlah_barang;
            $response['total_harga'] = $total_harga;
            $response['pegawai_id_pegawai'] = $id_pegawai;
            $response['pelanggan_id_pelanggan'] = $id_pelanggan;
            $response['status_code'] = 200;
            $response['message'] = "SUCCESS";
            echo json_encode($response);
          } else {
            $response['status_code'] = 201;
            $response['message'] = "FAILED!";
            echo json_encode($response);
          }
        } else {
          $response['status_code'] = 201;
          $response['message'] = "Produk tidak terdaftar";
          echo json_encode($response);
        }
      } else {
        $response['status_code'] = 201;
        $response['message'] = "Pelanggan tidak terdaftar";
        echo json_encode($response);
      }
    } else {
      $response['status_code'] = 201;
      $response['message'] = "Pegawai tidak terdaftar";
      echo json_encode($response);
    }
  } else {
    $response['status_code'] = 400;
    $response['message'] = "Invalid date format for tgl_lahir";
    echo json_encode($response);
  }
} else {
  $response = array();
  $response['status_code'] = 401;
  $response['message'] = "METHOD NOT ALLOWED";
  echo json_encode($response);
}
