<table style="width: 100%; margin-bottom: 20px;">
   <tr>
      <td style="width: 100px;">
         <img src="<?= getLogo(); ?>" width="80px" height="80px" style="display: block;">
      </td>
      <td style="text-align: center; vertical-align: middle;">
         <h2 style="margin: 0; font-size: 16px;">DAFTAR DATA MEMBER</h2>
         <h4 style="margin: 5px 0; font-size: 14px;"><?= $generalSettings->office_name; ?></h4>
         <h4 style="margin: 5px 0; font-size: 14px;">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td style="width: 100px;"></td>
   </tr>
</table>

<table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
   <thead>
      <tr>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 5%;">No</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 15%;">Kode Member</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: left; width: 25%;">Nama</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 12%;">Jenis Kelamin</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 15%;">Tipe Member</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 13%;">Tanggal Bergabung</th>
         <th style="border: 1px solid #000; padding: 5px; text-align: center; width: 10%;">Status</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 1; ?>
      <?php foreach ($listMember as $member) : ?>
         <?php
         $isExpired = strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'));
         $textColor = $isExpired ? 'color: red;' : '';
         ?>
         <tr>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>"><?= $i++; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>"><?= $member['unique_code']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; <?= $textColor ?>"><?= $member['nama_member']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>"><?= $member['jenis_kelamin']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>"><?= $member['type_member']; ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>"><?= date('d-m-Y', strtotime($member['tanggal_join'])); ?></td>
            <td style="border: 1px solid #000; padding: 5px; text-align: center; <?= $textColor ?>">
               <?php if ($isExpired): ?>
                  <strong>EXPIRED</strong>
               <?php else: ?>
                  Aktif
               <?php endif; ?>
            </td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>

<table style="width: 30%; margin-bottom: 20px;">
   <tr>
      <td style="padding: 5px; font-weight: bold; width: 50%;">Total Member</td>
      <td style="padding: 5px;">: <?= count($listMember); ?></td>
   </tr>
</table>
