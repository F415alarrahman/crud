<?php

//memanggil file interkoneksi database
require '../config/connect.php';

//memanggil filemethod API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  #code...

  $response = array();

  $id_pelanggan = $_POST['id_pelanggan'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $kontak = $_POST['kontak'];
  $gender = $_POST['gender'];


  $update = "UPDATE pelanggan
  SET nama = '$nama',
      alamat = '$alamat',
      kontak = '$kontak',
      gender = '$gender'
      WHERE id_pelanggan = '$id_pelanggan'";

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
