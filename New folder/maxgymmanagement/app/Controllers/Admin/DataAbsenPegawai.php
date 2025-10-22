<?php

namespace App\Controllers\Admin;

use App\Models\PegawaiModel;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use App\Models\PresensiPegawaiModel;
use CodeIgniter\I18n\Time;

class DataAbsenPegawai extends BaseController
{
   protected PegawaiModel $pegawaiModel;

   protected PresensiPegawaiModel $presensiPegawai;

   protected KehadiranModel $kehadiranModel;

   public function __construct()
   {
      $this->pegawaiModel = new PegawaiModel();

      $this->presensiPegawai = new PresensiPegawaiModel();

      $this->kehadiranModel = new KehadiranModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Data Absen Pegawai',
         'ctx' => 'absen-pegawai',
      ];

      return view('admin/absen/absen-pegawai', $data);
   }

   public function ambilDataPegawai()
   {
      // ambil variabel POST
      $tanggal = $this->request->getVar('tanggal');

      $lewat = Time::parse($tanggal)->isAfter(Time::today());

      $result = $this->presensiPegawai->getPresensiByTanggal($tanggal);

      $data = [
         'data' => $result,
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'lewat' => $lewat
      ];

      return view('admin/absen/list-absen-pegawai', $data);
   }

   public function ambilKehadiran()
   {
      $idPresensi = $this->request->getVar('id_presensi');
      $idPegawai = $this->request->getVar('id_pegawai');

      $data = [
         'presensi' => $this->presensiPegawai->getPresensiById($idPresensi),
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'data' => $this->pegawaiModel->getPegawaiById($idPegawai)
      ];

      return view('admin/absen/ubah-kehadiran-modal', $data);
   }

   public function ubahKehadiran()
   {
      // ambil variabel POST
      $idKehadiran = $this->request->getVar('id_kehadiran');
      $idPegawai = $this->request->getVar('id_pegawai');
      $tanggal = $this->request->getVar('tanggal');
      $jamMasuk = $this->request->getVar('jam_masuk');
      $jamKeluar = $this->request->getVar('jam_keluar');
      $keterangan = $this->request->getVar('keterangan');

      $cek = $this->presensiPegawai->cekAbsen($idPegawai, $tanggal);

      $result = $this->presensiPegawai->updatePresensi(
         $cek == false ? NULL : $cek,
         $idPegawai,
         $tanggal,
         $idKehadiran,
         $jamMasuk ?? NULL,
         $jamKeluar ?? NULL,
         $keterangan
      );

      $response['nama_pegawai'] = $this->pegawaiModel->getPegawaiById($idPegawai)['nama_pegawai'];

      if ($result) {
         $response['status'] = TRUE;
      } else {
         $response['status'] = FALSE;
      }

      return $this->response->setJSON($response);
   }

   public function deletePresensi()
   {
      $idPresensi = $this->request->getVar('id_presensi');

      $result = $this->presensiPegawai->deletePresensi($idPresensi);

      if ($result) {
         $response['status'] = TRUE;
         $response['message'] = 'Presensi berhasil dihapus';
      } else {
         $response['status'] = FALSE;
         $response['message'] = 'Gagal menghapus presensi';
      }

      return $this->response->setJSON($response);
   }
}
