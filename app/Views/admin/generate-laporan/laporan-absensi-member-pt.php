<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">DAFTAR HADIR MEMBER PERSONAL TRAINER</h2>
         <h4 align="center"><?= $generalSettings->office_name; ?></h4>
         <h4 align="center">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Bulan : <?= $bulan; ?></span>
<table align="center" border="1">
   <thead>
      <td></td>
      <td></td>
      <th colspan="<?= count($tanggal); ?>">Hari/Tanggal</th>
   </thead>
   <thead>
      <td></td>
      <td></td>
      <?php foreach ($tanggal as $value) : ?>
         <th align="center"><?= $value->format('E'); ?></th>
      <?php endforeach; ?>
      <td colspan="2" align="center">Total</td>
   </thead>
   <tr>
      <th align="center">No</th>
      <th width="1000px">Nama</th>
      <?php foreach ($tanggal as $value) : ?>
         <th align="center"><?= $value->format('d'); ?></th>
      <?php endforeach; ?>
      <th align="center" style="background-color:lightgreen;">H</th>
      <th align="center" style="background-color:red;">A</th>
   </tr>

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
         <td align="center"><?= $i + 1; ?></td>
         <td><?= $member['nama_member']; ?></td>
         <?php foreach ($listAbsen as $absen) : ?>
            <?php
            $kehadiran = null;
            foreach ($absen as $presensi) {
               if (isset($presensi['id_member']) && $presensi['id_member'] == $member['id_member']) {
                  $kehadiran = $presensi['id_kehadiran'];
                  break;
               }
            }
            echo kehadiran($kehadiran ?? ($absen['lewat'] ? null : 4));
            ?>
         <?php endforeach; ?>
         <td align="center">
            <?= $jumlahHadir != 0 ? $jumlahHadir : '-'; ?>
         </td>
         <td align="center">
            <?= $jumlahTidakHadir != 0 ? $jumlahTidakHadir : '-'; ?>
         </td>
      </tr>
   <?php
      $i++;
   endforeach; ?>

</table>
<br></br>
<table>
   <tr>
      <td>Jumlah Member PT</td>
      <td>: <?= count($listMember); ?></td>
   </tr>
   <tr>
      <td>Laki-laki</td>
      <td>: <?= $jumlahMember['laki']; ?></td>
   </tr>
   <tr>
      <td>Perempuan</td>
      <td>: <?= $jumlahMember['perempuan']; ?></td>
   </tr>
</table>

<?php if (!empty($ptMembersExceeding)): ?>
<br>
<h4>Member PT yang Melebihi Batas 12 Sesi Per Bulan:</h4>
<table border="1" style="width: 100%; border-collapse: collapse;">
   <thead>
      <tr>
         <th>Nama Member</th>
         <th>Jumlah Kehadiran Bulan Ini</th>
         <th>Tanggal Absen Terakhir</th>
         <th>Sisa Sesi PT</th>
      </tr>
   </thead>
   <tbody>
      <?php foreach ($ptMembersExceeding as $ptMember): ?>
         <tr>
            <td><?= esc($ptMember['nama_member']); ?></td>
            <td><?= $ptMember['attendance_count']; ?></td>
            <td><?= date('d-m-Y', strtotime($ptMember['last_attendance_date'])); ?></td>
            <td><?= $ptMember['remaining_sessions']; ?> (sudah habis)</td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<?php endif; ?>

<?php
function kehadiran($kehadiran)
{
   $text = '';
   switch ($kehadiran) {
      case 1:
         $text = "<td align='center' style='background-color:lightgreen;'>H</td>";
         break;
      case 4:
         $text = "<td align='center' style='background-color:red;'>A</td>";
         break;
      default:
         $text = "<td></td>";
         break;
   }

   return $text;
}
?>
<?= $this->endSection() ?>
