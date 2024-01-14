<?php

//memanggil file interkoneksi database
require '../config/connect.php';

//memanggil filemethod API yang digunakan
if ($_SERVER['REQUEST_METHOD'] == "POST") {
  #code...

  $response = array();

  $id_pegawai = $_POST['id_pegawai'];
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $jabatan = $_POST['jabatan'];
  $gender = $_POST['gender'];
  $kontak = $_POST['kontak'];
  $gaji = $_POST['gaji'];


  $update = "UPDATE pegawai
  SET nama = '$nama',
      alamat = '$alamat',
      jabatan = '$jabatan',
      gender = '$gender',
      kontak = '$kontak',
      gaji = '$gaji'
      WHERE id_pegawai = '$id_pegawai'";

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
