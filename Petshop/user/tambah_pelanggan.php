<?php
// Memanggil file koneksi database
require '../config/connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Ambil data dari permintaan
  $nama = $_POST['nama'];
  $nik = $_POST['nik'];
  $alamat = $_POST['alamat'];
  $kontak = $_POST['kontak'];
  $gender = $_POST['gender'];


  // Cek apakah status pegawai tidak aktif
  $cekIsdeleted = mysqli_query($con, "SELECT * FROM `pelanggan` WHERE nik='$nik' AND is_deleted='y'");
  if (mysqli_num_rows($cekIsdeleted) > 0) {
    // Update is_deleted menjadi kosong
    $updateIsDeleted = mysqli_query($con, "UPDATE `pelanggan` SET is_deleted='' WHERE nik='$nik'");
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
    $cekNik = mysqli_query($con, "SELECT * FROM `pelanggan` WHERE nik='$nik'");
    if (mysqli_num_rows($cekNik) > 0) {
      $response['status_code'] = 201;
      $response['message'] = "NIK SUDAH TERDAFTAR";
      echo json_encode($response);
    } else {
      // Insert data pegawai baru
      $id_pelanggan_query = mysqli_query($con, "SELECT * FROM `pelanggan` ORDER BY id_pelanggan DESC LIMIT 1");

      if ($id_pelanggan_query) {
        $da = mysqli_fetch_array($id_pelanggan_query);
        if ($da) {
          $id_pelanggan = $da['id_pelanggan'] + 1;
        } else {
          $id_pelanggan = 1;
        }
      } else {
        $response['status_code'] = 201;
        $response['message'] = "Error fetching id_pelanggan";
        echo json_encode($response);
        exit();
      }

      $insert = "INSERT INTO pelanggan (id_pelanggan, nama, nik, alamat, kontak, gender)
                       VALUES ('$id_pelanggan', '$nama', '$nik', '$alamat', '$kontak', '$gender')";

      if (mysqli_query($con, $insert)) {
        $response['id_pelanggan'] = (string)$id_pelanggan;
        $response['nama'] = $nama;
        $response['nik'] = $nik;
        $response['alamat'] = $alamat;
        $response['kontak'] = $kontak;
        $response['gender'] = $gender;
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
