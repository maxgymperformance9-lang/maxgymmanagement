<div class="card-body table-responsive">
   <?php if (!$empty) : ?>
      <table class="table table-hover">
         <thead class="text-success">
            <th><b>No</b></th>
            <th><b>Foto</b></th>
            <th><b>Nama Member</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>No Member</b></th>
            <th><b>Type Member</b></th>
            <th><b>No HP</b></th>
            <th><b>Email</b></th>
            <th><b>Alamat</b></th>
            <th><b>Tanggal Join</b></th>
            <th><b>Tanggal Expired</b></th>
            <th width="1%"><b>Aksi</b></th>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><?= $i; ?></td>
                  <td>
                     <?php if (!empty($value['foto'])): ?>
                        <img src="<?= base_url($value['foto']); ?>" alt="Foto <?= $value['nama_member']; ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                     <?php else: ?>
                        <div class="text-center text-muted">
                           <i class="material-icons" style="font-size: 40px;">account_circle</i>
                        </div>
                     <?php endif; ?>
                  </td>
                  <td><b<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['nama_member']; ?></b></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['jenis_kelamin']; ?></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['no_member']; ?></td>
                  <td>
                     <?php
                     $type = $value['type_member'];
                     if ($type == 'personal_trainer') {
                        echo 'Personal Trainer';
                     } elseif ($type == 'member_pt') {
                        echo 'Member + PT';
                     } else {
                        echo ucfirst($type);
                     }
                     ?>
                     <?php if (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))): ?>
                        <span class="badge badge-danger ml-1">EXPIRED</span>
                     <?php endif; ?>
                  </td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['no_hp']; ?></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['email']; ?></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['alamat']; ?></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>><?= $value['tanggal_join']; ?></td>
                  <td<?= (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>>
                     <?= $value['tanggal_expired']; ?>
                     <?php if (strtotime($value['tanggal_expired']) < strtotime(date('Y-m-d'))): ?>
                        <span class="badge badge-danger">EXPIRED</span>
                     <?php endif; ?>
                  </td>
                  <td>
                     <div class="d-flex justify-content-center">
                        <a title="Download QR Code" href="<?= base_url('admin/qr/member/' . $value['id_member'] . '/download'); ?>" class="btn btn-info p-2">
                           <i class="material-icons">qr_code</i>
                        </a>

                        <button title="Upload Foto" onclick="uploadFoto(<?= $value['id_member']; ?>, '<?= $value['nama_member']; ?>')" class="btn btn-warning p-2">
                           <i class="material-icons">photo_camera</i>
                        </button>

                        <?php if (!empty($value['email'])): ?>
                        <button title="Kirim Email Welcome" onclick="sendWelcomeEmail(<?= $value['id_member']; ?>, '<?= $value['nama_member']; ?>')" class="btn btn-primary p-2">
                           <i class="material-icons">email</i>
                        </button>
                        <?php endif; ?>

                        <?php if (!empty($value['no_hp'])): ?>
                        <button title="Kirim WhatsApp Welcome" onclick="sendWelcomeWhatsApp(<?= $value['id_member']; ?>, '<?= $value['nama_member']; ?>')" class="btn btn-success p-2">
                           <i class="material-icons">message</i>
                        </button>
                        <?php endif; ?>

                        <a title="Edit" href="<?= base_url('admin/member/edit/' . $value['id_member']); ?>" class="btn btn-success p-2" id="<?= $value['nama_member']; ?>">
                           <i class="material-icons">edit</i>
                        </a>
                        <form action="<?= base_url('admin/member/delete/' . $value['id_member']); ?>" method="post" class="d-inline">
                           <?= csrf_field(); ?>
                           <input type="hidden" name="_method" value="DELETE">
                           <button title="Delete" onclick="return confirm('Konfirmasi untuk menghapus data');" type="submit" class="btn btn-danger p-2" id="<?= $value['nama_member']; ?>">
                              <i class="material-icons">delete_forever</i>
                           </button>
                        </form>
                     </div>
                  </td>
               </tr>
            <?php $i++;
            endforeach; ?>
         </tbody>
      </table>
   <?php else : ?>
      <div class="row">
         <div class="col">
            <h4 class="text-center text-danger">Data tidak ditemukan</h4>
         </div>
      </div>
   <?php endif; ?>
</div>
