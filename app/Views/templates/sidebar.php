<?php
$context = $ctx ?? 'dashboard';
switch ($context) {
   case 'absen-penjaga':
   case 'penjaga':
   case 'di':
      $sidebarColor = 'azure';
      break;
   case 'absen-pegawai':
   case 'pegawai':
      $sidebarColor = 'azure';
      break;

   case 'qr':
      $sidebarColor = 'azure';
      break;

   default:
      $sidebarColor = 'azure';
      break;
}
?>
<div class="sidebar" data-color="<?= $sidebarColor; ?>" data-background-color="black" data-image="<?= base_url('assets/img/sidebar/apakah1.jpg'); ?>">
   <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
   <div class="logo">
      <a class="simple-text logo-normal">
         <b>Management<br>Maxgym Peformance</b>
      </a>
   </div>
   <div class="sidebar-wrapper">
      <ul class="nav">
         <li class="nav-item <?= $context == 'dashboard' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/dashboard'); ?>">
               <i class="material-icons">dashboard</i>
               <p>Dashboard</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'absen-penjaga' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/absen-penjaga'); ?>">
               <i class="material-icons">checklist</i>
               <p>Absensi Penjaga</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'absen-pegawai' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/absen-pegawai'); ?>">
               <i class="material-icons">checklist</i>
               <p>Absensi Pegawai</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'absen-member' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/absen-member'); ?>">
               <i class="material-icons">checklist</i>
               <p>Absensi Member</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'pegawai' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/pegawai'); ?>">
               <i class="material-icons">person_4</i>
               <p>Data Pegawai</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'member' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/member'); ?>">
               <i class="material-icons">person_4</i>
               <p>Data Member</p>
            </a>
         </li>

         <li class="nav-item <?= $context == 'produk' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/produk'); ?>">
               <i class="material-icons">shopping_cart</i>
               <p>Data Produk</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'warehouse' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/warehouse'); ?>">
               <i class="material-icons">warehouse</i>
               <p>Data Gudang</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'stock' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/stock'); ?>">
               <i class="material-icons">inventory</i>
               <p>Stok Gudang</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'kasir' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/kasir'); ?>">
               <i class="material-icons">point_of_sale</i>
               <p>Kasir</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'transaksi' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/kasir/transaksi'); ?>">
               <i class="material-icons">receipt</i>
               <p>Data Transaksi</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'pengeluaran' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/pengeluaran'); ?>">
               <i class="material-icons">account_balance_wallet</i>
               <p>Data Pengeluaran</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'fitness-classes' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/fitness-classes'); ?>">
               <i class="material-icons">fitness_center</i>
               <p>Data Kelas Fitness</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'class-schedules' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/class-schedules'); ?>">
               <i class="material-icons">schedule</i>
               <p>Jadwal Kelas</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'qr' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/generate'); ?>">
               <i class="material-icons">qr_code</i>
               <p>Generate QR Code</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'laporan' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/laporan'); ?>">
               <i class="material-icons">print</i>
               <p>Generate Laporan</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'rfid' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/rfid'); ?>">
               <i class="material-icons">credit_card</i>
               <p>RFID Management</p>
            </a>
         </li>
         <?php if (user()->toArray()['is_superadmin'] ?? '0' == '1') : ?>
            <li class="nav-item <?= $context == 'petugas' ? 'active' : ''; ?>">
               <a class="nav-link" href="<?= base_url('admin/petugas'); ?>">
                  <i class="material-icons">computer</i>
                  <p>Data Petugas</p>
               </a>
            </li>
         <li class="nav-item <?= $context == 'email-templates' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/email-templates'); ?>">
               <i class="material-icons">email</i>
               <p>Email Templates</p>
            </a>
         </li>
         <li class="nav-item <?= $context == 'general_settings' ? 'active' : ''; ?>">
            <a class="nav-link" href="<?= base_url('admin/general-settings'); ?>">
               <i class="material-icons">settings</i>
               <p>Pengaturan</p>
            </a>
         </li>
         <?php endif; ?>
         <!-- <li class="nav-item active-pro mb-3">
            <a class="nav-link" href="./upgrade.html">
               <i class="material-icons">unarchive</i>
               <p>Bottom sidebar</p>
            </a>
         </li> -->
      </ul>
   </div>
</div>
