<?php

//memanggil file interkoneksi database
require '../config/connect.php';

//memanggil filemethod API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  #code...

  $response = array();

  $id_produk = $_POST['id_produk'];
  $nama_produk = $_POST['nama_produk'];
  $harga = $_POST['harga'];
  $stok = $_POST['stok'];


  $update = "UPDATE produk
           SET nama_produk = '$nama_produk',
               harga = '$harga',
               stok = '$stok'
           WHERE id_produk = '$id_produk'";

  if (mysqli_query($con, $update)) {
    #code...
    $response['status_code'] = 200;
    $response['message'] = "SUCCES";
    echo json_encode($response);
  } else {
    #code...
    $response['status_code'] = 201;
    $response['message'] = "FAILED";
    echo json_encode($response);
  }
} else {
  $response = array();
  $response['status_code'] = 401;
  $response['message'] = "METHOD NOT ALLOWED";
  echo json_encode($response);
}
