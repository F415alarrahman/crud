<?php
// Memanggil file koneksi database
require '../config/connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Ambil data dari permintaan
  $nama = $_POST['nama'];
  $nik = $_POST['nik'];
  $alamat = $_POST['alamat'];
  $jabatan = $_POST['jabatan'];
  $gender = $_POST['gender'];
  $kontak = $_POST['kontak'];
  $gaji = $_POST['gaji'];


  // Cek apakah status pegawai tidak aktif
  $cekIsdeleted = mysqli_query($con, "SELECT * FROM `pegawai` WHERE nik='$nik' AND is_deleted='y'");
  if (mysqli_num_rows($cekIsdeleted) > 0) {
    // Update is_deleted menjadi kosong
    $updateIsDeleted = mysqli_query($con, "UPDATE `pegawai` SET is_deleted='' WHERE nik='$nik'");
    if ($updateIsDeleted) {
      $response['status_code'] = 200;
      $response['message'] = "Status Diubah Menjadi Aktif";
    } else {
      $response['status_code'] = 201;
      $response['message'] = "Gagal Mengubah Status";
    }
    echo json_encode($response);
  } else {
    // Cek apakah NIK sudah terdaftar
    $cekNik = mysqli_query($con, "SELECT * FROM `pegawai` WHERE nik='$nik'");
    if (mysqli_num_rows($cekNik) > 0) {
      $response['status_code'] = 201;
      $response['message'] = "NIK SUDAH TERDAFTAR";
      echo json_encode($response);
    } else {
      // Insert data pegawai baru
      $id_pegawai_query = mysqli_query($con, "SELECT * FROM `pegawai` ORDER BY id_pegawai DESC LIMIT 1");

      if ($id_pegawai_query) {
        $da = mysqli_fetch_array($id_pegawai_query);
        if ($da) {
          $id_pegawai = $da['id_pegawai'] + 1;
        } else {
          $id_pegawai = 1;
        }
      } else {
        $response['status_code'] = 201;
        $response['message'] = "Error fetching id_pegawai";
        echo json_encode($response);
        exit();
      }

      $insert = "INSERT INTO pegawai (id_pegawai, nama, alamat, jabatan, gender, kontak, gaji, nik)
                       VALUES ('$id_pegawai', '$nama', '$alamat', '$jabatan', '$gender', '$kontak', '$gaji', '$nik')";

      if (mysqli_query($con, $insert)) {
        $response['id_pegawai'] = (string)$id_pegawai;
        $response['nik'] = $nik;
        $response['nama'] = $nama;
        $response['alamat'] = $alamat;
        $response['jabatan'] = $jabatan;
        $response['gender'] = $gender;
        $response['kontak'] = $kontak;
        $response['gaji'] = $gaji;
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
