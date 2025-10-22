<table style="width: 100%; margin-bottom: 20px;">
   <tr>
      <td style="width: 100px;">
         <img src="<?= getLogo(); ?>" width="80px" height="80px" style="display: block;">
      </td>
      <td style="text-align: center; vertical-align: middle;">
         <h2 style="margin: 0; font-size: 16px;">DAFTAR HADIR MEMBER</h2>
         <h4 style="margin: 5px 0; font-size: 14px;"><?= $generalSettings->office_name; ?></h4>
         <h4 style="margin: 5px 0; font-size: 14px;">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td style="width: 100px;"></td>
   </tr>
</table>

<p style="margin-bottom: 10px;"><strong>Bulan: <?= $bulan; ?></strong></p>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%;">No</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: left; width: 25%;">Nama</th>
         <?php foreach ($tanggal as $value) : ?>
            <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 3%; font-size: 10px;">
               <?= $value->format('D'); ?><br><?= $value->format('d'); ?>
            </th>
         <?php endforeach; ?>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #d4edda;">H</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #f8d7da;">A</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 0; ?>
      <?php foreach ($listMember as $member) : ?>
         <?php
         $jumlahHadir = count(array_filter($listAbsen, function ($a) use ($i, $member) {
            if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
            return $a[$i]['id_kehadiran'] == 1 && $a[$i]['id_member'] == $member['id_member'];
         }));
         $jumlahTidakHadir = count(array_filter($listAbsen, function ($a) use ($i, $member) {
            if ($a['lewat']) return false;
            if (is_null($a[$i]['id_kehadiran']) || $a[$i]['id_kehadiran'] == 4) return true;
            return false;
         }));
         ?>
         <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $i + 1; ?></td>
            <td style="border: 1px solid #000; padding: 5px;"><?= $member['nama_member']; ?></td>
            <?php foreach ($listAbsen as $absen) : ?>
               <?php
               $kehadiran = null;
               foreach ($absen as $presensi) {
                  if (isset($presensi['id_member']) && $presensi['id_member'] == $member['id_member']) {
                     $kehadiran = $presensi['id_kehadiran'];
                     break;
                  }
               }
               $status = $kehadiran ?? ($absen['lewat'] ? null : 4);
               $cellStyle = 'border: 1px solid #000; padding: 5px; text-align: center;';
               switch ($status) {
                  case 1:
                     $cellStyle .= ' background-color: #d4edda;';
                     $text = 'H';
                     break;
                  case 4:
                     $cellStyle .= ' background-color: #f8d7da;';
                     $text = 'A';
                     break;
                  default:
                     $text = '';
                     break;
               }
               ?>
               <td style="<?= $cellStyle ?>"><?= $text ?></td>
            <?php endforeach; ?>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; background-color: #d4edda;">
               <?= $jumlahHadir != 0 ? $jumlahHadir : '-'; ?>
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; background-color: #f8d7da;">
               <?= $jumlahTidakHadir != 0 ? $jumlahTidakHadir : '-'; ?>
            </td>
         </tr>
      <?php
         $i++;
      endforeach; ?>
   </tbody>
</table>

<table style="width: 70%; margin-bottom: 20px;">
   <tr>
      <td style="padding: 5px; font-weight: bold; width: 40%;">Jumlah Member</td>
      <td style="padding: 5px;">: <?= count($listMember); ?></td>
   </tr>
   <tr>
      <td style="padding: 5px; font-weight: bold;">Laki-laki</td>
      <td style="padding: 5px;">: <?= $jumlahMember['laki']; ?></td>
   </tr>
   <tr>
      <td style="padding: 5px; font-weight: bold;">Perempuan</td>
      <td style="padding: 5px;">: <?= $jumlahMember['perempuan']; ?></td>
   </tr>
   <tr>
      <td colspan="2" style="padding: 5px; font-weight: bold;">Statistik Kehadiran Berdasarkan Tipe Member:</td>
   </tr>
   <tr>
      <td style="padding: 5px;">Member Umum</td>
      <td style="padding: 5px;">: <?= $attendanceByType['umum']['hadir']; ?> dari <?= $attendanceByType['umum']['total']; ?> member hadir</td>
   </tr>
   <tr>
      <td style="padding: 5px;">Member Pelajar</td>
      <td style="padding: 5px;">: <?= $attendanceByType['pelajar']['hadir']; ?> dari <?= $attendanceByType['pelajar']['total']; ?> member hadir</td>
   </tr>
   <tr>
      <td style="padding: 5px;">Member Mahasiswa</td>
      <td style="padding: 5px;">: <?= $attendanceByType['mahasiswa']['hadir']; ?> dari <?= $attendanceByType['mahasiswa']['total']; ?> member hadir</td>
   </tr>
   <tr>
      <td style="padding: 5px;">Personal Trainer</td>
      <td style="padding: 5px;">: <?= $attendanceByType['personal_trainer']['hadir']; ?> dari <?= $attendanceByType['personal_trainer']['total']; ?> member hadir</td>
   </tr>
   <tr>
      <td style="padding: 5px;">Member + PT</td>
      <td style="padding: 5px;">: <?= $attendanceByType['member_pt']['hadir']; ?> dari <?= $attendanceByType['member_pt']['total']; ?> member hadir</td>
   </tr>
</table>

<?php if (!empty($ptMembersExceeding)): ?>
<h4 style="margin: 20px 0 10px 0;">Member PT yang Melebihi Batas 12 Sesi Per Bulan:</h4>
<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 5px; text-align: left;">Nama Member</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center;">Jumlah Kehadiran Bulan Ini</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center;">Tanggal Absen Terakhir</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center;">Sisa Sesi PT</th>
      </tr>
   </thead>
   <tbody>
      <?php foreach ($ptMembersExceeding as $ptMember): ?>
         <tr>
            <td style="border: 1px solid #000; padding: 5px;"><?= esc($ptMember['nama_member']); ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $ptMember['attendance_count']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= date('d-m-Y', strtotime($ptMember['last_attendance_date'])); ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $ptMember['remaining_sessions']; ?> (sudah habis)</td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<?php endif; ?>
