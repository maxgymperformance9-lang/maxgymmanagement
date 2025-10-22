<?php

namespace App\Controllers\Admin;

use App\Models\DiModel;

use App\Models\PenjagaModel;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use App\Models\PresensiPenjagaModel;
use CodeIgniter\I18n\Time;

class DataAbsenPenjaga extends BaseController
{
   protected DiModel $DiModel;

   protected PenjagaModel $penjagaModel;

   protected KehadiranModel $kehadiranModel;

   protected PresensiPenjagaModel $presensiPenjaga;

   protected string $currentDate;

   public function __construct()
   {
      $this->currentDate = Time::today()->toDateString();

      $this->PenjagaModel = new PenjagaModel();

      $this->kehadiranModel = new KehadiranModel();

      $this->DiModel = new DiModel();

      $this->presensiPenjaga = new PresensiPenjagaModel();
   }

   public function index()
   {
      $di = $this->DiModel->getDataDi();

      $data = [
         'title' => 'Data Absen Penjaga',
         'ctx' => 'absen-penjaga',
         'di' => $di
      ];

      return view('admin/absen/absen-penjaga', $data);
   }

   public function ambilDataPenjaga()
   {
      // ambil variabel POST
      $di = $this->request->getVar('di');
      $idDi = $this->request->getVar('id_di');
      $tanggal = $this->request->getVar('tanggal');

      $lewat = Time::parse($tanggal)->isAfter(Time::today());

      $result = $this->presensiPenjaga->getPresensiByDiTanggal($idDi, $tanggal);

      $data = [
         'di' => $di,
         'data' => $result,
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'lewat' => $lewat
      ];

      return view('admin/absen/list-absen-penjaga', $data);
   }

   public function ambilKehadiran()
   {
      $idPresensi = $this->request->getVar('id_presensi');
      $idPenjaga = $this->request->getVar('id_penjaga');

      $data = [
         'presensi' => $this->presensiPenjaga->getPresensiById($idPresensi),
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'data' => $this->PenjagaModel->getPenjagaById($idPenjaga)
      ];

      return view('admin/absen/ubah-kehadiran-modal', $data);
   }

   public function ubahKehadiran()
   {
      // ambil variabel POST
      $idKehadiran = $this->request->getVar('id_kehadiran');
      $idPenjaga = $this->request->getVar('id_penjaga');
      $idDi = $this->request->getVar('id_di');
      $tanggal = $this->request->getVar('tanggal');
      $jamMasuk = $this->request->getVar('jam_masuk');
      $jamKeluar = $this->request->getVar('jam_keluar');
      $keterangan = $this->request->getVar('keterangan');

      $cek = $this->presensiPenjaga->cekAbsen($idPenjaga, $tanggal);

      $result = $this->presensiPenjaga->updatePresensi(
         $cek == false ? NULL : $cek,
         $idPenjaga,
         $idDi,
         $tanggal,
         $idKehadiran,
         $jamMasuk ?? NULL,
         $jamKeluar ?? NULL,
         $keterangan
      );

      $response['nama_penjaga'] = $this->PenjagaModel->getPenjagaById($idPenjaga)['nama_penjaga'];

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

      $result = $this->presensiPenjaga->deletePresensi($idPresensi);

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
