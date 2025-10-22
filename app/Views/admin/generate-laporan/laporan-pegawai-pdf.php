<table style="width: 100%; margin-bottom: 20px;">
   <tr>
      <td style="width: 100px;">
         <img src="<?= getLogo(); ?>" width="80px" height="80px" style="display: block;">
      </td>
      <td style="text-align: center; vertical-align: middle;">
         <h2 style="margin: 0; font-size: 16px;">DAFTAR HADIR PEGAWAI</h2>
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
               <?= $value->toLocalizedString('E'); ?><br><?= $value->format('d'); ?>
            </th>
         <?php endforeach; ?>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #d4edda;">H</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #fff3cd;">S</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #fff3cd;">I</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%; background-color: #f8d7da;">A</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 0; ?>
      <?php foreach ($listPegawai as $pegawai) : ?>
         <?php
         $jumlahHadir = count(array_filter($listAbsen, function ($a) use ($i) {
            if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
            return $a[$i]['id_kehadiran'] == 1;
         }));
         $jumlahSakit = count(array_filter($listAbsen, function ($a) use ($i) {
            if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
            return $a[$i]['id_kehadiran'] == 2;
         }));
         $jumlahIzin = count(array_filter($listAbsen, function ($a) use ($i) {
            if ($a['lewat'] || is_null($a[$i]['id_kehadiran'])) return false;
            return $a[$i]['id_kehadiran'] == 3;
         }));
         $jumlahTidakHadir = count(array_filter($listAbsen, function ($a) use ($i) {
            if ($a['lewat']) return false;
            if (is_null($a[$i]['id_kehadiran']) || $a[$i]['id_kehadiran'] == 4) return true;
            return false;
         }));
         ?>
         <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center;"><?= $i + 1; ?></td>
            <td style="border: 1px solid #000; padding: 5px;"><?= $pegawai['nama_pegawai']; ?></td>
            <?php foreach ($listAbsen as $absen) : ?>
               <?php
               $kehadiran = $absen[$i]['id_kehadiran'] ?? ($absen['lewat'] ? 5 : 4);
               $cellStyle = 'border: 1px solid #000; padding: 5px; text-align: center;';
               switch ($kehadiran) {
                  case 1:
                     $cellStyle .= ' background-color: #d4edda;';
                     $text = 'H';
                     break;
                  case 2:
                     $cellStyle .= ' background-color: #fff3cd;';
                     $text = 'S';
                     break;
                  case 3:
                     $cellStyle .= ' background-color: #fff3cd;';
                     $text = 'I';
                     break;
                  case 4:
                     $cellStyle .= ' background-color: #f8d7da;';
                     $text = 'A';
                     break;
                  case 5:
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
            <td style="border: 1px solid #000; padding: 5px; text-align: center; background-color: #fff3cd;">
               <?= $jumlahSakit != 0 ? $jumlahSakit : '-'; ?>
            </td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; background-color: #fff3cd;">
               <?= $jumlahIzin != 0 ? $jumlahIzin : '-'; ?>
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

<table style="width: 50%; margin-bottom: 20px;">
   <tr>
      <td style="padding: 5px; font-weight: bold; width: 40%;">Jumlah Pegawai</td>
      <td style="padding: 5px;">: <?= count($listPegawai); ?></td>
   </tr>
   <tr>
      <td style="padding: 5px; font-weight: bold;">Laki-laki</td>
      <td style="padding: 5px;">: <?= $jumlahPegawai['laki']; ?></td>
   </tr>
   <tr>
      <td style="padding: 5px; font-weight: bold;">Perempuan</td>
      <td style="padding: 5px;">: <?= $jumlahPegawai['perempuan']; ?></td>
   </tr>
</table>
