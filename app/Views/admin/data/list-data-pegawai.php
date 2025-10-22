<div class="card-body table-responsive">
   <?php if (!$empty) : ?>
      <table class="table table-hover">
         <thead class="text-success">
            <th><b>No</b></th>
            <th><b>Foto</b></th>
            <th><b>NIP</b></th>
            <th><b>Nama Pegawai</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>No HP</b></th>
            <th><b>Jabatan</b></th>
            <th width="1%"><b>Aksi</b></th>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><?= $i; ?></td>
                  <td>
                     <?php if (!empty($value['foto'])): ?>
                        <img src="<?= base_url($value['foto']); ?>" alt="Foto <?= $value['nama_pegawai']; ?>" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                     <?php else: ?>
                        <div class="text-center text-muted">
                           <i class="material-icons" style="font-size: 40px;">account_circle</i>
                        </div>
                     <?php endif; ?>
                  </td>
                  <td><?= $value['nip']; ?></td>
                  <td><b><?= $value['nama_pegawai']; ?></b></td>
                  <td><?= $value['jenis_kelamin']; ?></td>
                  <td><?= $value['no_hp']; ?></td>
                  <td><?= $value['alamat']; ?></td>
                  <td>
                     <div class="d-flex justify-content-center">
                        <button title="Upload Foto" onclick="uploadFoto(<?= $value['id_pegawai']; ?>, '<?= $value['nama_pegawai']; ?>')" class="btn btn-warning p-2">
                           <i class="material-icons">photo_camera</i>
                        </button>

                        <a title="Edit" href="<?= base_url('admin/pegawai/edit/' . $value['id_pegawai']); ?>" class="btn btn-success p-2" id="<?= $value['nip']; ?>">
                           <i class="material-icons">edit</i>
                        </a>
                        <form action="<?= base_url('admin/pegawai/delete/' . $value['id_pegawai']); ?>" method="post" class="d-inline">
                           <?= csrf_field(); ?>
                           <input type="hidden" name="_method" value="DELETE">
                           <button title="Delete" onclick="return confirm('Konfirmasi untuk menghapus data');" type="submit" class="btn btn-danger p-2" id="<?= $value['nip']; ?>">
                              <i class="material-icons">delete_forever</i>
                           </button>
                        </form>
                        <a title="Download QR Code" href="<?= base_url('admin/qr/pegawai/' . $value['id_pegawai'] . '/download'); ?>" class="btn btn-info p-2">
                           <i class="material-icons">qr_code</i>
                        </a>
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