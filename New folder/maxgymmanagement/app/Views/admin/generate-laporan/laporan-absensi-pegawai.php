<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">DAFTAR HADIR PEGAWAI</h2>
         <h4 align="center"><?= $generalSettings->office_name; ?></h4>
         <h4 align="center">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<span>Bulan : <?= date('F Y'); ?></span>
<table align="center" border="1">
   <thead>
      <td></td>
      <td></td>
      <th colspan="<?= count($listAbsen); ?>">Hari/Tanggal</th>
   </thead>
   <thead>
      <td></td>
      <td></td>
      <?php foreach ($listAbsen as $absen) : ?>
         <th align="center">Tgl</th>
      <?php endforeach; ?>
      <td colspan="4" align="center">Total</td>
   </thead>
   <tr>
      <th align="center">No</th>
      <th width="1000px">Nama</th>
      <?php foreach ($listAbsen as $absen) : ?>
         <th align="center">H</th>
      <?php endforeach; ?>
      <th align="center" style="background-color:lightgreen;">H</th>
      <th align="center" style="background-color:yellow;">S</th>
      <th align="center" style="background-color:yellow;">I</th>
      <th align="center" style="background-color:red;">A</th>
   </tr>

   <?php $i = 0; ?>

   <?php foreach ($listPegawai as $pegawai) : ?>
      <?php
      $jumlahHadir = 0;
      $jumlahSakit = 0;
      $jumlahIzin = 0;
      $jumlahTidakHadir = 0;
      ?>
      <tr>
         <td align="center"><?= $i + 1; ?></td>
         <td><?= $pegawai['nama_pegawai']; ?></td>
         <?php foreach ($listAbsen as $absen) : ?>
            <?php
            $kehadiran = null;
            foreach ($absen as $presensi) {
               if (isset($presensi['id_pegawai']) && $presensi['id_pegawai'] == $pegawai['id_pegawai']) {
                  $kehadiran = $presensi['id_kehadiran'];
                  break;
               }
            }
            if ($kehadiran == 1) $jumlahHadir++;
            elseif ($kehadiran == 2) $jumlahSakit++;
            elseif ($kehadiran == 3) $jumlahIzin++;
            elseif ($kehadiran == 4 || is_null($kehadiran)) $jumlahTidakHadir++;
            echo kehadiranPegawai($kehadiran);
            ?>
         <?php endforeach; ?>
         <td align="center">
            <?= $jumlahHadir != 0 ? $jumlahHadir : '-'; ?>
         </td>
         <td align="center">
            <?= $jumlahSakit != 0 ? $jumlahSakit : '-'; ?>
         </td>
         <td align="center">
            <?= $jumlahIzin != 0 ? $jumlahIzin : '-'; ?>
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
      <td>Jumlah Pegawai</td>
      <td>: <?= count($listPegawai); ?></td>
   </tr>
</table>
<?php
function kehadiranPegawai($kehadiran)
{
   $text = '';
   switch ($kehadiran) {
      case 1:
         $text = "<td align='center' style='background-color:lightgreen;'>H</td>";
         break;
      case 2:
         $text = "<td align='center' style='background-color:yellow;'>S</td>";
         break;
      case 3:
         $text = "<td align='center' style='background-color:yellow;'>I</td>";
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
