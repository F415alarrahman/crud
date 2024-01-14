<?php

//memanggil file interkoneksi database
require '../config/connect.php';

//memanggil file method API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $response = array();

  $id_pegawai = $_POST['id_pegawai'];

  $update = "UPDATE pegawai
               SET is_deleted = 'Y'
               WHERE id_pegawai = '$id_pegawai'";

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
