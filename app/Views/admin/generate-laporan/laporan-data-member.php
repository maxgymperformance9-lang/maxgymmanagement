<?= $this->extend('templates/laporan') ?>

<?= $this->section('content') ?>
<table>
   <tr>
      <td><img src="<?= getLogo(); ?>" width="100px" height="100px"></img></td>
      <td width="100%">
         <h2 align="center">DAFTAR DATA MEMBER</h2>
         <h4 align="center"><?= $generalSettings->office_name; ?></h4>
         <h4 align="center">TAHUN BERDIRI <?= $generalSettings->office_year; ?></h4>
      </td>
      <td>
         <div style="width:100px"></div>
      </td>
   </tr>
</table>
<table align="center" border="1">
   <thead>
      <tr>
         <th align="center">No</th>
         <th align="center">Kode Member</th>
         <th align="center">Nama</th>
         <th align="center">Jenis Kelamin</th>
         <th align="center">Tipe Member</th>
         <th align="center">Tanggal Bergabung</th>
         <th align="center">Status</th>
      </tr>
   </thead>
   <tbody>
      <?php $i = 1; ?>
      <?php foreach ($listMember as $member) : ?>
         <tr>
            <td align="center"><?= $i++; ?></td>
            <td align="center"><?= $member['unique_code']; ?></td>
            <td<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' style="color: red;"' : ''; ?>><?= $member['nama_member']; ?></td>
            <td align="center"<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' style="color: red;"' : ''; ?>><?= $member['jenis_kelamin']; ?></td>
            <td align="center"<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' style="color: red;"' : ''; ?>><?= $member['type_member']; ?></td>
            <td align="center"<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' style="color: red;"' : ''; ?>><?= date('d-m-Y', strtotime($member['tanggal_join'])); ?></td>
            <td align="center"<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' style="color: red;"' : ''; ?>>
               <?php if (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))): ?>
                  <strong style="color: red;">EXPIRED</strong>
               <?php else: ?>
                  Aktif
               <?php endif; ?>
            </td>
         </tr>
      <?php endforeach; ?>
   </tbody>
</table>
<br></br>
<table>
   <tr>
      <td>Total Member</td>
      <td>: <?= count($listMember); ?></td>
   </tr>
</table>
<?= $this->endSection() ?>
