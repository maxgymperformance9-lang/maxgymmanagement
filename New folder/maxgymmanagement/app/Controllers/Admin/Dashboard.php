<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\PegawaiModel;
use App\Models\PenjagaModel;
use App\Models\DiModel;
use App\Models\PetugasModel;
use App\Models\PresensiPegawaiModel;
use App\Models\PresensiPenjagaModel;
use App\Models\PresensiMemberModel;
use App\Models\MemberModel;
use App\Models\MembershipPackageModel;
use CodeIgniter\I18n\Time;
use Config\AbsensiKantor as ConfigAbsensiKantor;

class Dashboard extends BaseController
{
   protected PenjagaModel $penjagaModel;
   protected PegawaiModel $pegawaiModel;

   protected DiModel $DiModel;

   protected PresensiPenjagaModel $presensiPenjagaModel;
   protected PresensiPegawaiModel $presensiPegawaiModel;

   protected PetugasModel $petugasModel;
   protected MemberModel $memberModel;
   protected MembershipPackageModel $membershipPackageModel;
   protected PresensiMemberModel $presensiMemberModel;

   /**
    * Get popular classes based on booking count
    */
   private function getPopularClasses()
   {
      $db = \Config\Database::connect();
      $query = $db->query("
         SELECT
            tb_fitness_classes.nama_class,
            COUNT(tb_class_bookings.id_booking) as booking_count
         FROM tb_fitness_classes
         LEFT JOIN tb_class_schedules ON tb_fitness_classes.id_class = tb_class_schedules.id_class
         LEFT JOIN tb_class_bookings ON tb_class_schedules.id_schedule = tb_class_bookings.id_schedule
         WHERE tb_class_schedules.tanggal >= CURDATE()
         GROUP BY tb_fitness_classes.id_class, tb_fitness_classes.nama_class
         ORDER BY booking_count DESC
         LIMIT 5
      ");

      return $query->getResultArray();
   }

   public function __construct()
   {
      $this->penjagaModel = new PenjagaModel();
      $this->pegawaiModel = new PegawaiModel();
      $this->DiModel = new DiModel();
      $this->presensiPenjagaModel = new PresensiPenjagaModel();
      $this->presensiPegawaiModel = new PresensiPegawaiModel();
      $this->petugasModel = new PetugasModel();
      $this->memberModel = new MemberModel();
      $this->membershipPackageModel = new MembershipPackageModel();
      $this->presensiMemberModel = new PresensiMemberModel();
   }

   public function index()
   {
      $now = Time::now();

      $dateRange = [];
      $penjagaKehadiranArray = [];
      $pegawaiKehadiranArray = [];
      $memberKehadiranArray = [];

      for ($i = 6; $i >= 0; $i--) {
         $date = $now->subDays($i)->toDateString();
         if ($i == 0) {
            $formattedDate = "Hari ini";
         } else {
            $t = $now->subDays($i);
            $formattedDate = "{$t->getDay()} " . substr($t->toFormattedDateString(), 0, 3);
         }
         array_push($dateRange, $formattedDate);
         array_push(
            $penjagaKehadiranArray,
            count($this->presensiPenjagaModel
               ->join('tb_penjaga', 'tb_presensi_penjaga.id_penjaga = tb_penjaga.id_penjaga', 'left')
               ->where(['tb_presensi_penjaga.tanggal' => "$date", 'tb_presensi_penjaga.id_kehadiran' => '1'])->findAll())
         );
         array_push(
            $pegawaiKehadiranArray,
            count($this->presensiPegawaiModel
               ->join('tb_pegawai', 'tb_presensi_pegawai.id_pegawai = tb_pegawai.id_pegawai', 'left')
               ->where(['tb_presensi_pegawai.tanggal' => "$date", 'tb_presensi_pegawai.id_kehadiran' => '1'])->findAll())
         );
         array_push(
            $memberKehadiranArray,
            count($this->presensiMemberModel
               ->join('tb_members', 'tb_presensi_member.id_member = tb_members.id_member', 'left')
               ->where(['tb_presensi_member.tanggal' => "$date", 'tb_presensi_member.id_kehadiran' => '1'])->findAll())
         );
      }

      $today = $now->toDateString();

      $data = [
         'title' => 'Dashboard',
         'ctx' => 'dashboard',

         'penjaga' => $this->penjagaModel->getAllPenjagaWithDi(),
         'pegawai' => $this->pegawaiModel->getAllPegawai(),

         'di' => $this->DiModel->getDataDi(),

         'dateRange' => $dateRange,
         'dateNow' => $now->toLocalizedString('d MMMM Y'),

         'grafikKehadiranPenjaga' => $penjagaKehadiranArray,
         'grafikkKehadiranPegawai' => $pegawaiKehadiranArray,
         'grafikKehadiranMember' => $memberKehadiranArray,

         'jumlahKehadiranPenjaga' => [
            'hadir' => count($this->presensiPenjagaModel->getPresensiByKehadiran('1', $today)),
            'sakit' => count($this->presensiPenjagaModel->getPresensiByKehadiran('2', $today)),
            'izin' => count($this->presensiPenjagaModel->getPresensiByKehadiran('3', $today)),
            'alfa' => count($this->presensiPenjagaModel->getPresensiByKehadiran('4', $today))
         ],

         'jumlahKehadiranPegawai' => [
            'hadir' => count($this->presensiPegawaiModel->getPresensiByKehadiran('1', $today)),
            'sakit' => count($this->presensiPegawaiModel->getPresensiByKehadiran('2', $today)),
            'izin' => count($this->presensiPegawaiModel->getPresensiByKehadiran('3', $today)),
            'alfa' => count($this->presensiPegawaiModel->getPresensiByKehadiran('4', $today))
         ],

         'jumlahKehadiranMember' => [
            'hadir' => count($this->presensiMemberModel->getPresensiByKehadiran('1', $today)),
            'sakit' => count($this->presensiMemberModel->getPresensiByKehadiran('2', $today)),
            'izin' => count($this->presensiMemberModel->getPresensiByKehadiran('3', $today)),
            'alfa' => count($this->presensiMemberModel->getPresensiByKehadiran('4', $today))
         ],

         'petugas' => $this->petugasModel->getAllPetugas(),
         'member' => $this->memberModel->getAllMembers(),

         'membershipStats' => [
            'totalPackages' => count($this->membershipPackageModel->getActivePackages()),
            'activeMemberships' => count($this->memberModel->where('status_membership', 'aktif')->findAll()),
            'expiringMemberships' => count($this->memberModel->getExpiringMemberships(30)),
            'expiredMemberships' => count($this->memberModel->getExpiredMemberships()),
         ],

         'bookingStats' => [
            'totalBookings' => count(model('ClassBookingModel')->findAll()),
            'todayBookings' => count(model('ClassBookingModel')->where('DATE(created_at)', date('Y-m-d'))->findAll()),
            'upcomingClasses' => count(model('ClassScheduleModel')->where('tanggal >=', date('Y-m-d'))->where('status', 'scheduled')->findAll()),
            'popularClasses' => $this->getPopularClasses(),
         ],
      ];

      return view('admin/dashboard', $data);
   }
}
