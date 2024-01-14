<?php

//memanggil file interkoneksi database
require '../config/connect.php';

//memanggil file method API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $response = array();

  $id_pelanggan = $_POST['id_pelanggan'];

  $update = "UPDATE pelanggan
               SET is_deleted = 'Y'
               WHERE id_pelanggan = '$id_pelanggan'";

  if (mysqli_query($con, $update)) {
    $response['status_code'] = 200;
    $response['message'] = "BERHASIL DIHAPUS";
    echo json_encode($response);
  } else {
    $response['status_code'] = 201;
    $response['message'] = "GAGAL DIHAPUS: " . mysqli_error($con);
    echo json_encode($response);
  }
} else {
  $response = array();
  $response['status_code'] = 401;
  $response['message'] = "METHOD NOT ALLOWED";
  echo json_encode($response);
}
