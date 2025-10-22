<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;
use DateTime;
use DateInterval;
use DatePeriod;

use App\Models\PegawaiModel;
use App\Models\DiModel;
use App\Models\PresensiPegawaiModel;
use App\Models\PenjagaModel;
use App\Models\PresensiPenjagaModel;
use App\Models\MemberModel;
use App\Models\PresensiMemberModel;
use App\Models\TransactionModel;
use App\Models\TransactionItemModel;
use App\Models\ExpenseModel;
use App\Models\StockMovementModel;

use Dompdf\Dompdf;
use Dompdf\Options;

class GenerateLaporan extends BaseController
{
   protected PenjagaModel $penjagaModel;
   protected DiModel $DiModel;

   protected PegawaiModel $pegawaiModel;

   protected PresensiPenjagaModel $presensiPenjagaModel;
   protected PresensiPegawaiModel $presensiPegawaiModel;

   protected MemberModel $memberModel;
   protected PresensiMemberModel $presensiMemberModel;
   protected TransactionModel $transactionModel;
   protected TransactionItemModel $transactionItemModel;
   protected ExpenseModel $expenseModel;
   protected StockMovementModel $stockMovementModel;

   public function __construct()
   {
      $this->penjagaModel = new PenjagaModel();
      $this->DiModel = new DiModel();

      $this->pegawaiModel = new PegawaiModel();

      $this->presensiPenjagaModel = new PresensiPenjagaModel();
      $this->presensiPegawaiModel = new PresensiPegawaiModel();

      $this->memberModel = new MemberModel();
      $this->presensiMemberModel = new PresensiMemberModel();
      $this->transactionModel = new TransactionModel();
      $this->transactionItemModel = new TransactionItemModel();
      $this->expenseModel = new ExpenseModel();
      $this->stockMovementModel = new StockMovementModel();
   }

   public function index()
   {
      $di = $this->DiModel->getDataDi();
      $pegawai = $this->pegawaiModel->getAllPegawai();

      $penjagaPerDi = [];

      foreach ($di as $value) {
         array_push($penjagaPerDi, $this->penjagaModel->getPenjagaByDi($value['id_di']));
      }

      $data = [
         'title' => 'Generate Laporan',
         'ctx' => 'laporan',
         'PenjagaPerDi' => $penjagaPerDi,
         'di' => $di,
         'pegawai' => $pegawai
      ];



      return view('admin/generate-laporan/generate-laporan', $data);
   }

   public function generateLaporanPenjaga()
   {
      $idDi = $this->request->getVar('di');
      $penjaga = $this->penjagaModel->getPenjagaByDi($idDi);
      $type = $this->request->getVar('type');

      if (empty($penjaga)) {
         session()->setFlashdata([
            'msg' => 'Data Penjaga kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $di = $this->DiModel->where(['id_di' => $idDi])
         ->join('tb_wilayah', 'tb_di.id_wilayah = tb_wilayah.id', 'left')
         ->first();

      $bulan = $this->request->getVar('tanggalPenjaga');

      // hari pertama dalam 1 bulan
      $begin = new Time($bulan, locale: 'id');
      // tanggal terakhir dalam 1 bulan
      $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
      // interval 1 hari
      $interval = DateInterval::createFromDateString('1 day');
      // buat array dari semua hari di bulan
      $period = new DatePeriod($begin, $interval, $end);

      $arrayTanggal = [];
      $dataAbsen = [];

      foreach ($period as $value) {
         // kecualikan hari sabtu dan minggu
         if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
            $lewat = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());

            $absenByTanggal = $this->presensiPenjagaModel
               ->getPresensiByDiTanggal($idDi, $value->format('Y-m-d'));

            $absenByTanggal['lewat'] = $lewat;

            array_push($dataAbsen, $absenByTanggal);
            array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
         }
      }

      $laki = 0;

      foreach ($penjaga as $value) {
         if ($value['jenis_kelamin'] != 'Perempuan') {
            $laki++;
         }
      }

      $data = [
         'tanggal' => $arrayTanggal,
         'bulan' => $begin->toLocalizedString('MMMM'),
         'listAbsen' => $dataAbsen,
         'listPenjaga' => $penjaga,
         'jumlahPenjaga' => [
            'laki' => $laki,
            'perempuan' => count($penjaga) - $laki
         ],
         'di' => $di,
         'grup' => "" . $di ['di'] . " " . $di['wilayah'],
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_absen_penjaga_' . $di['di'] . " " . $di['wilayah'] . '_' . $begin->toLocalizedString('MMMM-Y') . '.doc'
         );

         return view('admin/generate-laporan/laporan-penjaga', $data);
      }

      return view('admin/generate-laporan/laporan-penjaga', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanPegawai()
   {
      $pegawai = $this->pegawaiModel->getAllPegawai();
      $type = $this->request->getVar('type');

      if (empty($pegawai)) {
         session()->setFlashdata([
            'msg' => 'Data pegawai kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $bulan = $this->request->getVar('tanggalPegawai');

      // hari pertama dalam 1 bulan
      $begin = new Time($bulan, locale: 'id');
      // tanggal terakhir dalam 1 bulan
      $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
      // interval 1 hari
      $interval = DateInterval::createFromDateString('1 day');
      // buat array dari semua hari di bulan
      $period = new DatePeriod($begin, $interval, $end);

      $arrayTanggal = [];
      $dataAbsen = [];

      foreach ($period as $value) {
         // kecualikan hari sabtu dan minggu
         if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
            $lewat = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());

            $absenByTanggal = $this->presensiPegawaiModel
               ->getPresensiByTanggal($value->format('Y-m-d'));

            $absenByTanggal['lewat'] = $lewat;

            array_push($dataAbsen, $absenByTanggal);
            array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
         }
      }

      $laki = 0;

      foreach ($pegawai as $value) {
         if ($value['jenis_kelamin'] != 'Perempuan') {
            $laki++;
         }
      }

      $data = [
         'tanggal' => $arrayTanggal,
         'bulan' => $begin->toLocalizedString('MMMM'),
         'listAbsen' => $dataAbsen,
         'listPegawai' => $pegawai,
         'jumlahPegawai' => [
            'laki' => $laki,
            'perempuan' => count($pegawai) - $laki
         ],
         'grup' => 'pegawai',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_absen_pegawai_' . $begin->toLocalizedString('MMMM-Y') . '.doc'
         );

         return view('admin/generate-laporan/laporan-pegawai', $data);
      }

      return view('admin/generate-laporan/laporan-pegawai', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanAbsensiMember()
   {
      $members = $this->memberModel->findAll();
      $type = $this->request->getVar('type');

      if (empty($members)) {
         session()->setFlashdata([
            'msg' => 'Data member kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $bulan = $this->request->getVar('tanggalMember');

      // hari pertama dalam 1 bulan
      $begin = new Time($bulan, locale: 'id');
      // tanggal terakhir dalam 1 bulan
      $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
      // interval 1 hari
      $interval = DateInterval::createFromDateString('1 day');
      // buat array dari semua hari di bulan
      $period = new DatePeriod($begin, $interval, $end);

      $arrayTanggal = [];
      $dataAbsen = [];

      foreach ($period as $value) {
         // kecualikan hari sabtu dan minggu
         if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
            $lewat = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());

            $absenByTanggal = $this->presensiMemberModel
               ->getPresensiByTanggal($value->format('Y-m-d'));

            $absenByTanggal['lewat'] = $lewat;

            array_push($dataAbsen, $absenByTanggal);
            array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
         }
      }

      $laki = 0;

      foreach ($members as $value) {
         if ($value['jenis_kelamin'] != 'Perempuan') {
            $laki++;
         }
      }

      // Calculate attendance by member type
      $attendanceByType = [
         'umum' => ['total' => 0, 'hadir' => 0],
         'pelajar' => ['total' => 0, 'hadir' => 0],
         'mahasiswa' => ['total' => 0, 'hadir' => 0],
         'personal_trainer' => ['total' => 0, 'hadir' => 0],
         'member_pt' => ['total' => 0, 'hadir' => 0]
      ];

      foreach ($members as $member) {
         $type = strtolower($member['type_member']);
         if (isset($attendanceByType[$type])) {
            $attendanceByType[$type]['total']++;
            // Count how many days this member was present in the month
            $memberHadir = 0;
            foreach ($dataAbsen as $absen) {
               $kehadiran = null;
               foreach ($absen as $presensi) {
                  if (isset($presensi['id_member']) && $presensi['id_member'] == $member['id_member']) {
                     $kehadiran = $presensi['id_kehadiran'];
                     break;
                  }
               }
               if ($kehadiran == 1) {
                  $memberHadir++;
               }
            }
            if ($memberHadir > 0) {
               $attendanceByType[$type]['hadir']++;
            }
         }
      }

      $data = [
         'tanggal' => $arrayTanggal,
         'bulan' => $begin->toLocalizedString('MMMM'),
         'listAbsen' => $dataAbsen,
         'listMember' => $members,
         'jumlahMember' => [
            'laki' => $laki,
            'perempuan' => count($members) - $laki
         ],
         'attendanceByType' => $attendanceByType,
         'grup' => 'member',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_absen_member_' . $begin->toLocalizedString('MMMM-Y') . '.doc'
         );

         return view('admin/generate-laporan/laporan-absensi-member', $data);
      }

      return view('admin/generate-laporan/laporan-absensi-member', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanAbsensiMemberPT()
   {
      $members = $this->memberModel->where('type_member', 'member_pt')->findAll();
      $type = $this->request->getVar('type');

      if (empty($members)) {
         session()->setFlashdata([
            'msg' => 'Data member PT kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $bulan = $this->request->getVar('tanggalMemberPT');

      // hari pertama dalam 1 bulan
      $begin = new Time($bulan, locale: 'id');
      // tanggal terakhir dalam 1 bulan
      $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
      // interval 1 hari
      $interval = DateInterval::createFromDateString('1 day');
      // buat array dari semua hari di bulan
      $period = new DatePeriod($begin, $interval, $end);

      $arrayTanggal = [];
      $dataAbsen = [];

      foreach ($period as $value) {
         // kecualikan hari sabtu dan minggu
         if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
            $lewat = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());

            $absenByTanggal = $this->presensiMemberModel
               ->getPresensiByTanggal($value->format('Y-m-d'));

            $absenByTanggal['lewat'] = $lewat;

            array_push($dataAbsen, $absenByTanggal);
            array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
         }
      }

      $laki = 0;

      foreach ($members as $value) {
         if ($value['jenis_kelamin'] != 'Perempuan') {
            $laki++;
         }
      }

      // Get PT members exceeding limit
      $ptMembersExceeding = $this->presensiMemberModel->getPTMembersExceedingLimit($begin->format('Y-m'));

      $data = [
         'tanggal' => $arrayTanggal,
         'bulan' => $begin->toLocalizedString('MMMM'),
         'listAbsen' => $dataAbsen,
         'listMember' => $members,
         'jumlahMember' => [
            'laki' => $laki,
            'perempuan' => count($members) - $laki
         ],
         'ptMembersExceeding' => $ptMembersExceeding,
         'grup' => 'member_pt',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_absen_member_pt_' . $begin->toLocalizedString('MMMM-Y') . '.doc'
         );

         return view('admin/generate-laporan/laporan-absensi-member-pt', $data);
      }

      return view('admin/generate-laporan/laporan-absensi-member-pt', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanDataMember()
   {
      $members = $this->memberModel->findAll();
      $type = $this->request->getVar('type');

      if (empty($members)) {
         session()->setFlashdata([
            'msg' => 'Data member kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $data = [
         'listMember' => $members,
         'grup' => 'member',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_data_member.doc'
         );

         return view('admin/generate-laporan/laporan-data-member', $data);
      }

      return view('admin/generate-laporan/laporan-data-member', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanTransaksi()
   {
      $startDate = $this->request->getVar('startDate');
      $endDate = $this->request->getVar('endDate');
      $type = $this->request->getVar('type');

      $transactions = $this->transactionModel->getTransactionsWithItems($startDate, $endDate);

      if (empty($transactions)) {
         session()->setFlashdata([
            'msg' => 'Data transaksi kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $data = [
         'transactions' => $transactions,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'grup' => 'transaksi',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_transaksi_' . $startDate . '_to_' . $endDate . '.doc'
         );

         return view('admin/generate-laporan/laporan-transaksi', $data);
      }

      return view('admin/generate-laporan/laporan-transaksi', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanKeuangan()
   {
      $bulan = $this->request->getVar('bulanKeuangan');
      $type = $this->request->getVar('type');

      // Get financial summary for the month
      $summary = $this->transactionModel->getFinancialSummary($bulan);

      // Get expense summary for the month
      $expenseSummary = $this->expenseModel->getMonthlyExpenses($bulan);

      // Calculate net profit: revenue - expenses
      $netProfit = ($summary['total_revenue'] ?? 0) - ($expenseSummary['total_expense_amount'] ?? 0);

      if (empty($summary) && empty($expenseSummary)) {
         session()->setFlashdata([
            'msg' => 'Data keuangan kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $data = [
         'summary' => $summary,
         'expenseSummary' => $expenseSummary,
         'netProfit' => $netProfit,
         'bulan' => $bulan,
         'grup' => 'keuangan',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_keuangan_' . $bulan . '.doc'
         );

         return view('admin/generate-laporan/laporan-keuangan', $data);
      }

      return view('admin/generate-laporan/laporan-keuangan', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanStock()
   {
      $startDate = $this->request->getVar('startDateStock');
      $endDate = $this->request->getVar('endDateStock');
      $type = $this->request->getVar('type');

      $stockMovements = $this->stockMovementModel->getMovementsWithDetails();

      // Filter by date range if provided
      if ($startDate && $endDate) {
         $stockMovements = array_filter($stockMovements, function($movement) use ($startDate, $endDate) {
            $movementDate = date('Y-m-d', strtotime($movement['created_at']));
            return $movementDate >= $startDate && $movementDate <= $endDate;
         });
      }

      if (empty($stockMovements)) {
         session()->setFlashdata([
            'msg' => 'Data pergerakan stok kosong!',
            'error' => true
         ]);
         return redirect()->to('/admin/laporan');
      }

      $data = [
         'stockMovements' => $stockMovements,
         'startDate' => $startDate,
         'endDate' => $endDate,
         'grup' => 'stock',
      ];

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_stock_' . $startDate . '_to_' . $endDate . '.doc'
         );

         return view('admin/generate-laporan/laporan-stock', $data);
      }

      return view('admin/generate-laporan/laporan-stock', $data) . view('admin/generate-laporan/topdf');
   }

   public function generateLaporanKeseluruhan()
   {
      $jenisLaporan = $this->request->getVar('jenisLaporan');
      $type = $this->request->getVar('type');

      $data = [];
      $periode = '';

      if ($jenisLaporan == 'harian') {
         $tanggal = $this->request->getVar('tanggal');
         $periode = 'Harian - ' . date('d F Y', strtotime($tanggal));

         // Laporan Absensi Pegawai (harian)
         $pegawai = $this->pegawaiModel->getAllPegawai();
         if (!empty($pegawai)) {
            $dataAbsenPegawai = $this->presensiPegawaiModel->getPresensiByTanggal($tanggal);
            $data['laporanAbsenPegawai'] = [
               'tanggal' => [Time::createFromFormat('Y-m-d', $tanggal)],
               'bulan' => date('F', strtotime($tanggal)),
               'listAbsen' => [$dataAbsenPegawai],
               'listPegawai' => $pegawai,
               'jumlahPegawai' => [
                  'laki' => count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] != 'Perempuan')),
                  'perempuan' => count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] == 'Perempuan'))
               ],
               'grup' => 'pegawai',
            ];
         }

         // Laporan Absensi Member (harian)
         $members = $this->memberModel->findAll();
         if (!empty($members)) {
            $dataAbsenMember = $this->presensiMemberModel->getPresensiByTanggal($tanggal);
            $data['laporanAbsenMember'] = [
               'tanggal' => [Time::createFromFormat('Y-m-d', $tanggal)],
               'bulan' => date('F', strtotime($tanggal)),
               'listAbsen' => [$dataAbsenMember],
               'listMember' => $members,
               'jumlahMember' => [
                  'laki' => count(array_filter($members, fn($m) => $m['jenis_kelamin'] != 'Perempuan')),
                  'perempuan' => count(array_filter($members, fn($m) => $m['jenis_kelamin'] == 'Perempuan'))
               ],
               'attendanceByType' => [], // Simplified for daily
               'grup' => 'member',
            ];
         }

         // Laporan Transaksi (harian)
         $transactions = $this->transactionModel->getTransactionsWithItems($tanggal, $tanggal);
         if (!empty($transactions)) {
            $data['laporanTransaksi'] = [
               'transactions' => $transactions,
               'startDate' => $tanggal,
               'endDate' => $tanggal,
               'grup' => 'transaksi',
            ];
         }

         // Laporan Keuangan (harian - menggunakan bulan dari tanggal)
         $bulanKeuangan = date('Y-m', strtotime($tanggal));
         $summary = $this->transactionModel->getFinancialSummary($bulanKeuangan);
         $expenseSummary = $this->expenseModel->getMonthlyExpenses($bulanKeuangan);
         $netProfit = ($summary['total_revenue'] ?? 0) - ($expenseSummary['total_expense_amount'] ?? 0);
         if (!empty($summary) || !empty($expenseSummary)) {
            $data['laporanKeuangan'] = [
               'summary' => $summary,
               'expenseSummary' => $expenseSummary,
               'netProfit' => $netProfit,
               'bulan' => $bulanKeuangan,
               'grup' => 'keuangan',
            ];
         }

      } elseif ($jenisLaporan == 'bulanan') {
         $bulan = $this->request->getVar('bulan');
         $periode = 'Bulanan - ' . date('F Y', strtotime($bulan . '-01'));

         // Laporan Absensi Pegawai (bulanan)
         $pegawai = $this->pegawaiModel->getAllPegawai();
         if (!empty($pegawai)) {
            $begin = new Time($bulan, locale: 'id');
            $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            $arrayTanggal = [];
            $dataAbsen = [];
            foreach ($period as $value) {
               if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
                  $absenByTanggal = $this->presensiPegawaiModel->getPresensiByTanggal($value->format('Y-m-d'));
                  $absenByTanggal['lewat'] = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());
                  array_push($dataAbsen, $absenByTanggal);
                  array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
               }
            }

            $data['laporanAbsenPegawai'] = [
               'tanggal' => $arrayTanggal,
               'bulan' => $begin->toLocalizedString('MMMM'),
               'listAbsen' => $dataAbsen,
               'listPegawai' => $pegawai,
               'jumlahPegawai' => [
                  'laki' => count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] != 'Perempuan')),
                  'perempuan' => count(array_filter($pegawai, fn($p) => $p['jenis_kelamin'] == 'Perempuan'))
               ],
               'grup' => 'pegawai',
            ];
         }

         // Laporan Absensi Member (bulanan)
         $members = $this->memberModel->findAll();
         if (!empty($members)) {
            $begin = new Time($bulan, locale: 'id');
            $end = (new DateTime($begin->format('Y-m-t')))->modify('+1 day');
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);

            $arrayTanggal = [];
            $dataAbsen = [];
            foreach ($period as $value) {
               if (!($value->format('D') == 'Sat' || $value->format('D') == 'Sun')) {
                  $absenByTanggal = $this->presensiMemberModel->getPresensiByTanggal($value->format('Y-m-d'));
                  $absenByTanggal['lewat'] = Time::parse($value->format('Y-m-d'))->isAfter(Time::today());
                  array_push($dataAbsen, $absenByTanggal);
                  array_push($arrayTanggal, Time::createFromInstance($value, locale: 'id'));
               }
            }

            $data['laporanAbsenMember'] = [
               'tanggal' => $arrayTanggal,
               'bulan' => $begin->toLocalizedString('MMMM'),
               'listAbsen' => $dataAbsen,
               'listMember' => $members,
               'jumlahMember' => [
                  'laki' => count(array_filter($members, fn($m) => $m['jenis_kelamin'] != 'Perempuan')),
                  'perempuan' => count(array_filter($members, fn($m) => $m['jenis_kelamin'] == 'Perempuan'))
               ],
               'attendanceByType' => [], // Simplified
               'grup' => 'member',
            ];
         }

         // Laporan Data Member
         $data['laporanDataMember'] = [
            'listMember' => $members,
            'grup' => 'member',
         ];

         // Laporan Transaksi (bulanan)
         $startDate = $bulan . '-01';
         $endDate = date('Y-m-t', strtotime($startDate));
         $transactions = $this->transactionModel->getTransactionsWithItems($startDate, $endDate);
         if (!empty($transactions)) {
            $data['laporanTransaksi'] = [
               'transactions' => $transactions,
               'startDate' => $startDate,
               'endDate' => $endDate,
               'grup' => 'transaksi',
            ];
         }

         // Laporan Keuangan (bulanan)
         $summary = $this->transactionModel->getFinancialSummary($bulan);
         $expenseSummary = $this->expenseModel->getMonthlyExpenses($bulan);
         $netProfit = ($summary['total_revenue'] ?? 0) - ($expenseSummary['total_expense_amount'] ?? 0);
         if (!empty($summary) || !empty($expenseSummary)) {
            $data['laporanKeuangan'] = [
               'summary' => $summary,
               'expenseSummary' => $expenseSummary,
               'netProfit' => $netProfit,
               'bulan' => $bulan,
               'grup' => 'keuangan',
            ];
         }

      } elseif ($jenisLaporan == 'tahunan') {
         $tahun = $this->request->getVar('tahun');
         $periode = 'Tahunan - ' . $tahun;

         // Laporan Keuangan (tahunan - aggregate per bulan)
         $summaryTahunan = [];
         $expenseTahunan = [];
         $netProfitTahunan = 0;

         for ($month = 1; $month <= 12; $month++) {
            $bulan = sprintf('%04d-%02d', $tahun, $month);
            $summary = $this->transactionModel->getFinancialSummary($bulan);
            $expenseSummary = $this->expenseModel->getMonthlyExpenses($bulan);
            $netProfit = ($summary['total_revenue'] ?? 0) - ($expenseSummary['total_expense_amount'] ?? 0);

            $summaryTahunan[] = [
               'bulan' => $bulan,
               'revenue' => $summary['total_revenue'] ?? 0,
               'expenses' => $expenseSummary['total_expense_amount'] ?? 0,
               'net_profit' => $netProfit
            ];
            $netProfitTahunan += $netProfit;
         }

         $data['laporanKeuangan'] = [
            'summaryTahunan' => $summaryTahunan,
            'netProfitTahunan' => $netProfitTahunan,
            'tahun' => $tahun,
            'grup' => 'keuangan',
         ];

         // Laporan Data Member (tahunan - semua member)
         $members = $this->memberModel->findAll();
         $data['laporanDataMember'] = [
            'listMember' => $members,
            'grup' => 'member',
         ];
      }

      $data['periode'] = $periode;
      $data['jenisLaporan'] = $jenisLaporan;

      if ($type == 'doc') {
         $this->response->setHeader('Content-type', 'application/vnd.ms-word');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment;Filename=laporan_keseluruhan_' . strtolower($jenisLaporan) . '_' . date('Ymd') . '.doc'
         );

         return view('admin/generate-laporan/generate-all-report', $data);
      }

      if ($type == 'pdf') {
         // Generate PDF using DomPDF
         $options = new Options();
         $options->set('isHtml5ParserEnabled', true);
         $options->set('isRemoteEnabled', true);
         $options->set('defaultFont', 'Arial');

         $dompdf = new Dompdf($options);
         $html = view('admin/generate-laporan/generate-all-report-pdf', $data);
         $dompdf->loadHtml($html);
         $dompdf->setPaper('A4', 'portrait');
         $dompdf->render();

         $this->response->setHeader('Content-Type', 'application/pdf');
         $this->response->setHeader(
            'Content-Disposition',
            'attachment; filename=laporan_keseluruhan_' . strtolower($jenisLaporan) . '_' . date('Ymd') . '.pdf'
         );

         return $this->response->setBody($dompdf->output());
      }

      return view('admin/generate-laporan/generate-all-report', $data) . view('admin/generate-laporan/topdf');
   }
}
