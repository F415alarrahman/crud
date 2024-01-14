<?php

// Memanggil file interkoneksi database
require '../config/connect.php';

// Memanggil file method API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  $response = array();

  $nama = $_POST['nama'];
  $jenis = $_POST['jenis'];
  $ras = $_POST['ras'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $id_pet = $_POST['id_pet'];

  $update = "UPDATE pet
             SET nama = '$nama',
                 jenis = '$jenis',
                 ras = '$ras',
                 tgl_lahir = '$tgl_lahir'
             WHERE id_pet = '$id_pet'";

  if (mysqli_query($con, $update)) {
    $response['status_code'] = 200;
    $response['message'] = "SUCCESS";
    echo json_encode($response);
  } else {
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
