<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use App\Models\PegawaiModel;
use App\Models\PenjagaModel;
use App\Models\PresensiPegawaiModel;
use App\Models\PresensiPenjagaModel;
use App\Models\MemberModel;
use App\Models\PresensiMemberModel;
use App\Models\DoorAccessModel;
use App\Libraries\enums\TipeUser;

class Scan extends BaseController
{
   private bool $WANotificationEnabled;

   protected PenjagaModel $penjagaModel;
   protected PegawaiModel $pegawaiModel;

   protected PresensiPenjagaModel $presensiPenjagaModel;
   protected PresensiPegawaiModel $presensiPegawaiModel;
   protected MemberModel $memberModel;
   protected PresensiMemberModel $presensiMemberModel;
   protected DoorAccessModel $doorAccessModel;

   public function __construct()
   {
      $this->WANotificationEnabled = getenv('WA_NOTIFICATION') === 'true' ? true : false;

      $this->penjagaModel = new PenjagaModel();
      $this->pegawaiModel = new PegawaiModel();
      $this->presensiPenjagaModel = new PresensiPenjagaModel();
      $this->presensiPegawaiModel = new PresensiPegawaiModel();
      $this->memberModel = new MemberModel();
      $this->presensiMemberModel = new PresensiMemberModel();
      $this->doorAccessModel = new DoorAccessModel();
   }

   /**
    * Send command to ESP8266 to open door
    */
   private function sendCommandToESP32($command)
   {
      $esp32Ip = '192.168.1.10';
      $esp32Port = '80';
      $url = "http://{$esp32Ip}:{$esp32Port}/{$command}";

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_TIMEOUT, 5);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

      $response = curl_exec($ch);
      $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $error = curl_error($ch);

      curl_close($ch);

      if ($error) {
         return ['success' => false, 'error' => $error];
      }

      if ($httpCode >= 200 && $httpCode < 300) {
         return ['success' => true, 'response' => $response];
      } else {
         return ['success' => false, 'error' => "HTTP {$httpCode}: {$response}"];
      }
   }

   public function index($t = 'Masuk')
   {
      $data = [
         'waktu' => $t,
         'title' => '<span style="color: white;">MAXGYM PERFORMANCE MANAGEMENT</span>',
         'mode' => 'admin' // Default admin mode
      ];
      return view('scan/scan', $data);
   }

   public function masuk()
   {
      return $this->index('Masuk');
   }

   public function pulang()
   {
      return $this->index('Pulang');
   }

   public function member()
   {
      $data = [
         'waktu' => 'Masuk', // Default to masuk for member display
         'title' => '<span style="color: white;">MAXGYM MEMBER SCAN</span>',
         'mode' => 'member' // Member-only mode
      ];
      return view('scan/scan', $data);
   }

   // ✅ tambahan: baca QR dari gambar upload
   public function scanFromFile()
   {
      helper(['form', 'url']);
      $msg = '';
      $uniqueCode = null;

      $file = $this->request->getFile('fileqr');
      if ($file && $file->isValid() && !$file->hasMoved()) {
         $path = $file->getTempName();

         // gunakan ZXing QR Reader
         $qrcode = new \Zxing\QrReader($path);
         $uniqueCode = $qrcode->text();

         if (!empty($uniqueCode)) {
            // lanjut ke proses absensi (masuk/pulang)
            $waktuAbsen = $this->request->getVar('waktu') ?? 'masuk';
            $this->request->setGlobal('post', ['unique_code' => $uniqueCode, 'waktu' => $waktuAbsen]);
            return $this->cekKode();
         } else {
            $msg = "❌ Gagal mendeteksi QR dari gambar. Pastikan gambar jelas dan fokus.";
         }
      } else {
         $msg = "❌ File tidak valid atau gagal diunggah.";
      }

      return $this->showErrorView($msg);
   }

   public function cekKode()
   {
      $uniqueCode = $this->request->getVar('unique_code');
      $waktuAbsen = strtolower($this->request->getVar('waktu'));

      // Debug log
      log_message('debug', 'CekKode called with unique_code: ' . $uniqueCode . ', waktu: ' . $waktuAbsen);

      $status = false;
      $type = TipeUser::penjaga;
      $result = null;

      // cek penjaga
      $result = $this->penjagaModel->cekPenjaga($uniqueCode);
      if (empty($result)) {
         $result = $this->pegawaiModel->cekPegawai($uniqueCode);
         if (!empty($result)) {
            $status = true;
            $type = TipeUser::pegawai;
         } else {
            $result = $this->memberModel->cekMember($uniqueCode);
            if (!empty($result)) {
               $status = true;
               $type = TipeUser::member;
            }
         }
      } else {
         $status = true;
      }

      // Jika tidak ditemukan, coba cari dengan partial match untuk scanner yang mungkin mengirim kode pendek
      if (!$status && strlen($uniqueCode) < 20) {
         log_message('debug', 'Trying partial match for short code: ' . $uniqueCode);

         // Cek penjaga dengan LIKE
         $result = $this->penjagaModel->cekPenjagaPartial($uniqueCode);
         if (!empty($result)) {
            $status = true;
            $type = TipeUser::penjaga;
         } else {
            // Cek pegawai dengan LIKE
            $result = $this->pegawaiModel->cekPegawaiPartial($uniqueCode);
            if (!empty($result)) {
               $status = true;
               $type = TipeUser::pegawai;
            } else {
               // Cek member dengan LIKE
               $result = $this->memberModel->cekMemberPartial($uniqueCode);
               if (!empty($result)) {
                  $status = true;
                  $type = TipeUser::member;
               }
            }
         }
      }

      // Jika masih tidak ditemukan, coba cari di tengah string (contains)
      if (!$status) {
         log_message('debug', 'Trying contains match for code: ' . $uniqueCode);

         // Cek penjaga dengan LIKE contains
         $result = $this->penjagaModel->cekPenjagaContains($uniqueCode);
         if (!empty($result)) {
            $status = true;
            $type = TipeUser::penjaga;
         } else {
            // Cek pegawai dengan LIKE contains
            $result = $this->pegawaiModel->cekPegawaiContains($uniqueCode);
            if (!empty($result)) {
               $status = true;
               $type = TipeUser::pegawai;
            } else {
               // Cek member dengan LIKE contains
               $result = $this->memberModel->cekMemberContains($uniqueCode);
               if (!empty($result)) {
                  $status = true;
                  $type = TipeUser::member;
               }
            }
         }
      }

      // Debug log hasil pencarian
      log_message('debug', 'Search result - Status: ' . ($status ? 'found' : 'not found') . ', Type: ' . $type->value . ', Result: ' . json_encode($result));

      if (!$status) {
         return $this->showErrorView('Data tidak ditemukan untuk kode: ' . $uniqueCode);
      }

      // Member tidak memiliki absensi pulang
      if ($type == TipeUser::member && $waktuAbsen == 'pulang') {
         return $this->showErrorView('Member tidak memiliki absensi pulang');
      }

      switch ($waktuAbsen) {
         case 'masuk':
            return $this->absenMasuk($type, $result);
         case 'pulang':
            return $this->absenPulang($type, $result);
         default:
            return $this->showErrorView('Data tidak valid');
      }
   }

   public function absenMasuk($type, $result)
   {
      $data['data'] = $result;
      $data['waktu'] = 'masuk';
      $date = Time::today()->toDateString();
      $time = Time::now()->toTimeString();
      $messageString = " sudah absen masuk pada tanggal $date jam $time";

      switch ($type) {
         case TipeUser::pegawai:
            $idPegawai = $result['id_pegawai'];
            $data['type'] = TipeUser::pegawai;
            $sudahAbsen = $this->presensiPegawaiModel->cekAbsen($idPegawai, $date);
            if ($sudahAbsen) {
               $data['presensi'] = $this->presensiPegawaiModel->getPresensiById($sudahAbsen);
               return $this->showErrorView('Anda sudah absen masuk hari ini', $data);
            }
            $this->presensiPegawaiModel->absenMasuk($idPegawai, $date, $time);
            $this->doorAccessModel->logDoorAccess($idPegawai, 'pegawai', 'success');
            $data['presensi'] = $this->presensiPegawaiModel->getPresensiByIdPegawaiTanggal($idPegawai, $date);

            // Open door immediately after successful attendance
            $this->sendCommandToESP32('open');
            break;

         case TipeUser::penjaga:
            $idPenjaga = $result['id_penjaga'];
            $data['type'] = TipeUser::penjaga;
            $sudahAbsen = $this->presensiPenjagaModel->cekAbsen($idPenjaga, $date);
            if ($sudahAbsen) {
               $data['presensi'] = $this->presensiPenjagaModel->getPresensiById($sudahAbsen);
               return $this->showErrorView('Anda sudah absen masuk hari ini', $data);
            }
            $this->presensiPenjagaModel->absenMasuk($idPenjaga, $date, $time, $result['id_di']);
            $this->doorAccessModel->logDoorAccess($idPenjaga, 'penjaga', 'success');
            $data['presensi'] = $this->presensiPenjagaModel->getPresensiByIdPenjagaTanggal($idPenjaga, $date);

            // Open door immediately after successful attendance
            $this->sendCommandToESP32('open');
            break;

         case TipeUser::member:
            $idMember = $result['id_member'];
            $data['type'] = TipeUser::member;
            // Cek apakah masa member telah habis
            if (strtotime($result['tanggal_expired']) < strtotime($date)) {
               return $this->showErrorView('<span style="color: red;">Masa member telah habis</span>', $data);
            }
            $sudahAbsen = $this->presensiMemberModel->cekAbsen($idMember, $date);
            if ($sudahAbsen) {
               $data['presensi'] = $this->presensiMemberModel->getPresensiById($sudahAbsen);
               return $this->showErrorView('Anda sudah absen masuk hari ini', $data);
            }
            $this->presensiMemberModel->absenMasuk($idMember, $date, $time);
            $this->doorAccessModel->logDoorAccess($idMember, 'member', 'success');
            $data['presensi'] = $this->presensiMemberModel->getPresensiByIdMemberTanggal($idMember, $date);

            // Open door immediately after successful attendance
            $this->sendCommandToESP32('open');
            break;
      }

      return view('scan/scan-result', $data);
   }

   public function absenPulang($type, $result)
   {
      $data['data'] = $result;
      $data['waktu'] = 'pulang';
      $date = Time::today()->toDateString();
      $time = Time::now()->toTimeString();
      $messageString = " sudah absen pulang pada tanggal $date jam $time";

      switch ($type) {
         case TipeUser::pegawai:
            $idPegawai = $result['id_pegawai'];
            $data['type'] = TipeUser::pegawai;
            $sudahAbsen = $this->presensiPegawaiModel->cekAbsen($idPegawai, $date);
            if (!$sudahAbsen) return $this->showErrorView('Anda belum absen hari ini', $data);
            $this->presensiPegawaiModel->absenKeluar($sudahAbsen, $time);
            $this->doorAccessModel->logDoorAccess($idPegawai, 'pegawai', 'success');
            $data['presensi'] = $this->presensiPegawaiModel->getPresensiById($sudahAbsen);
            break;

         case TipeUser::penjaga:
            $idPenjaga = $result['id_penjaga'];
            $data['type'] = TipeUser::penjaga;
            $sudahAbsen = $this->presensiPenjagaModel->cekAbsen($idPenjaga, $date);
            if (!$sudahAbsen) return $this->showErrorView('Anda belum absen hari ini', $data);
            $this->presensiPenjagaModel->absenKeluar($sudahAbsen, $time);
            $this->doorAccessModel->logDoorAccess($idPenjaga, 'penjaga', 'success');
            $data['presensi'] = $this->presensiPenjagaModel->getPresensiById($sudahAbsen);
            break;
      }

      return view('scan/scan-result', $data);
   }

   public function showErrorView(string $msg = 'no error message', $data = NULL)
   {
      $errdata = $data ?? [];
      $errdata['msg'] = $msg;
      return view('scan/error-scan-result', $errdata);
   }
}
