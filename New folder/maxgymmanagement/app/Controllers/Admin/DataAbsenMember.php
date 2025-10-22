<?php

namespace App\Controllers\Admin;

use App\Models\MemberModel;

use App\Controllers\BaseController;
use App\Models\KehadiranModel;
use App\Models\PresensiMemberModel;
use CodeIgniter\I18n\Time;

class DataAbsenMember extends BaseController
{
   protected MemberModel $memberModel;

   protected PresensiMemberModel $presensiMember;

   protected KehadiranModel $kehadiranModel;

   public function __construct()
   {
      $this->memberModel = new MemberModel();

      $this->presensiMember = new PresensiMemberModel();

      $this->kehadiranModel = new KehadiranModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Data Absen Member',
         'ctx' => 'absen-member',
      ];

      return view('admin/absen/absen-member', $data);
   }

   public function ambilDataMember()
   {
      // ambil variabel POST
      $tanggal = $this->request->getVar('tanggal');

      $lewat = Time::parse($tanggal)->isAfter(Time::today());

      $result = $this->presensiMember->getPresensiByTanggal($tanggal);

      $data = [
         'data' => $result,
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'lewat' => $lewat
      ];

      return view('admin/absen/list-absen-member', $data);
   }

   public function ambilKehadiran()
   {
      $idPresensi = $this->request->getVar('id_presensi');
      $idMember = $this->request->getVar('id_member');

      $data = [
         'presensi' => $this->presensiMember->getPresensiById($idPresensi),
         'listKehadiran' => $this->kehadiranModel->getAllKehadiran(),
         'data' => $this->memberModel->getMemberById($idMember)
      ];

      return view('admin/absen/ubah-kehadiran-member-modal', $data);
   }

   public function ubahKehadiran()
   {
      // ambil variabel POST
      $idKehadiran = $this->request->getVar('id_kehadiran');
      $idMember = $this->request->getVar('id_member');
      $tanggal = $this->request->getVar('tanggal');
      $jamMasuk = $this->request->getVar('jam_masuk');
      $jamKeluar = $this->request->getVar('jam_keluar');
      $keterangan = $this->request->getVar('keterangan');

      $cek = $this->presensiMember->cekAbsen($idMember, $tanggal);

      $result = $this->presensiMember->updatePresensi(
         $cek == false ? NULL : $cek,
         $idMember,
         $tanggal,
         $idKehadiran,
         $jamMasuk ?? NULL,
         $jamKeluar ?? NULL,
         $keterangan
      );

      $response['nama_member'] = $this->memberModel->getMemberById($idMember)['nama_member'];

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

      $result = $this->presensiMember->deletePresensi($idPresensi);

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
