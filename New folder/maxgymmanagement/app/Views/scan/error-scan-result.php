



<!-- Hidden data for JavaScript to extract -->
<div style="display: none;">
   <?php
   use App\Libraries\enums\TipeUser;

   // Function to display attendance times
   function displayAttendanceTimes($presensi = []) {
      ?>
      <p>Jam masuk : <b class="text-info"><?= $presensi['jam_masuk'] ?? '-'; ?></b></p>
      <p>Jam pulang : <b class="text-info"><?= $presensi['jam_keluar'] ?? '-'; ?></b></p>
      <?php
   }

   switch ($type):
      case TipeUser::penjaga: ?>
         <div class="row w-100">
            <div class="col">
               <p>Nama : <b><?= $data['nama_penjaga'] ?? '-'; ?></b></p>
               <p>NIP : <b><?= $data['nip'] ?? '-'; ?></b></p>
               <p>D.I/Wilayah : <b><?= ($data['di'] ?? '-') . ' ' . ($data['wilayah'] ?? '-'); ?></b></p>
            </div>
            <div class="col">
               <?php displayAttendanceTimes($presensi ?? []); ?>
            </div>
         </div>
         <?php break; ?>

      <?php case TipeUser::pegawai: ?>
         <div class="row w-100">
            <div class="col">
               <p>Nama : <b><?= $data['nama_pegawai'] ?? '-'; ?></b></p>
               <p>NIP : <b><?= $data['nip'] ?? '-'; ?></b></p>
               <p>No HP : <b><?= $data['no_hp'] ?? '-'; ?></b></p>
            </div>
            <div class="col">
               <?php displayAttendanceTimes($presensi ?? []); ?>
            </div>
         </div>
         <?php break; ?>

      <?php case TipeUser::member: ?>
         <div class="row w-100">
            <div class="col">
               <p>Nama : <b><?= $data['nama_member'] ?? '-'; ?></b></p>
               <p>No Member : <b><?= $data['no_member'] ?? '-'; ?></b></p>
               <p>No HP : <b><?= $data['no_hp'] ?? '-'; ?></b></p>
               <p>Tipe Member : <b><?= $data['type_member'] ?? '-'; ?></b></p>
            </div>
            <div class="col">
               <?php displayAttendanceTimes($presensi ?? []); ?>
            </div>
         </div>
         <?php break; ?>

      <?php default: ?>
         <p class="text-danger">Tipe user tidak valid.</p>
   <?php endswitch; ?>

   <!-- FOTO MEMBER (hidden for JS extraction) -->
   <?php
   // Tentukan path foto
   $photoPath = '';
   if (!empty($type)) {
      switch ($type) {
         case TipeUser::penjaga:
         case TipeUser::pegawai:
         case TipeUser::member:
            $photoPath = $data['foto'] ?? '';
            break;
      }
   }

   // Gunakan foto default jika tidak ada
   if (empty($photoPath) || !file_exists(FCPATH . $photoPath)) {
      $photoPath = 'assets/img/default-avatar.png';
   }
   ?>

   <img id="userPhotoData"
        src="<?= base_url($photoPath); ?>"
        alt="Foto User"
        style="display: none;">
</div>

         <p class="text-muted mt-3">Halaman akan kembali ke scan dalam 5 detik...</p>

      </div>
   </div>
</div>

<script>
// Auto redirect back to scan page after 5 seconds
setTimeout(function() {
   window.location.href = '<?= base_url("scan/" . strtolower($waktu)); ?>';
}, 5000);
</script>
