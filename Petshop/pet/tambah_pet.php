<?php
// Memanggil file koneksi database
require '../config/connect.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  // Ambil data dari permintaan
  $nama = $_POST['nama'];
  $jenis = $_POST['jenis'];
  $ras = $_POST['ras'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $id_pet = $_POST['id_pet'];
  $id_pelanggan = $_POST['id_pelanggan'];

  // Validasi format tanggal
  if (DateTime::createFromFormat('Y-m-d', $tgl_lahir) !== false) {
    // Format tanggal benar, lanjutkan dengan menyimpan ke database

    // Pengecekan keberadaan pelanggan
    $cekPelanggan = mysqli_query($con, "SELECT * FROM `pelanggan` WHERE id_pelanggan='$id_pelanggan'");

    if (mysqli_num_rows($cekPelanggan) > 0) {
      // Pelanggan sudah terdaftar, lanjutkan dengan menyimpan ke database

      // Cek apakah status pet tidak aktif
      $cekIsdeleted = mysqli_query($con, "SELECT * FROM `pet` WHERE id_pet='$id_pet' AND is_deleted='y'");
      if (mysqli_num_rows($cekIsdeleted) > 0) {
        // Update is_deleted menjadi kosong
        $updateIsDeleted = mysqli_query($con, "UPDATE `pet` SET is_deleted='' WHERE id_pet='$id_pet'");
        if ($updateIsDeleted) {
          $response['status_code'] = 200;
          $response['message'] = "Status Pet Diubah Menjadi Aktif";
        } else {
          $response['status_code'] = 201;
          $response['message'] = "Gagal Mengubah Status Pet";
        }
        echo json_encode($response);
      } else {
        // Cek apakah ID pet sudah terdaftar
        $cekPetTerdaftar = mysqli_query($con, "SELECT * FROM `pet` WHERE id_pet='$id_pet'");
        if (mysqli_num_rows($cekPetTerdaftar) > 0) {
          // ID pet sudah terdaftar, berikan respons atau lakukan tindakan lain sesuai kebutuhan
          $response['status_code'] = 201;
          $response['message'] = "ID Pet Sudah Terdaftar";
          echo json_encode($response);
        } else {
          // Insert data pet baru
          $insert = "INSERT INTO pet (id_pet, nama, jenis, ras, tgl_lahir, pelanggan_id_pelanggan, is_deleted)
                     VALUES ('$id_pet', '$nama', '$jenis', '$ras', '$tgl_lahir', '$id_pelanggan', '')";

          if (mysqli_query($con, $insert)) {
            $response['id_pet'] = (string)$id_pet;
            $response['nama'] = $nama;
            $response['jenis'] = $jenis;
            $response['ras'] = $ras;
            $response['tgl_lahir'] = $tgl_lahir;
            $response['pelanggan_id_pelanggan'] = $id_pelanggan;
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
      // Pelanggan tidak terdaftar, berikan respons atau lakukan tindakan lain sesuai kebutuhan
      $response['status_code'] = 201;
      $response['message'] = "Pelanggan tidak terdaftar";
      echo json_encode($response);
    }
  } else {
    // Format tanggal tidak benar, tangani kesalahan atau berikan pesan kesalahan kepada pengguna
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
