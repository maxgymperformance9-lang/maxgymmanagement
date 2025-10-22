<div class="card-body table-responsive">
   <?php if (!$empty) : ?>
      <table class="table table-hover">
         <thead class="text-primary">
            <th width="20"><input type="checkbox" class="checkbox-table" id="checkAll"></th>
            <th><b>No</b></th>
            <th><b>Jabatan</b></th>
            <th><b>Nama Penjaga</b></th>
            <th><b>Jenis Kelamin</b></th>
            <th><b>D.I/WIL</b></th>
            <th><b>Wilayah</b></th>
            <th><b>No HP</b></th>
            <th width="1%"><b>Aksi</b></th>
         </thead>
         <tbody>
            <?php $i = 1;
            foreach ($data as $value) : ?>
               <tr>
                  <td><input type="checkbox" name="checkbox-table" class="checkbox-table" value="<?= $value['id_penjaga']; ?>"></td>
                  <td><?= $i; ?></td>
                  <td><?= $value['nip']; ?></td>
                  <td><b><?= $value['nama_penjaga']; ?></b></td>
                  <td><?= $value['jenis_kelamin']; ?></td>
                  <td><?= $value['di']; ?></td>
                  <td><?= $value['wilayah']; ?></td>
                  <td><?= $value['no_hp']; ?></td>
                  <td>
                     <div class="d-flex justify-content-center">
                        <a title="Edit" href="<?= base_url('admin/petugas/edit/' . $value['id_penjaga']); ?>" class="btn btn-primary p-2" id="<?= $value['nip']; ?>">
                           <i class="material-icons">edit</i>
                        </a>
                        <form action="<?= base_url('admin/petugas/delete/' . $value['id_penjaga']); ?>" method="post" class="d-inline">
                           <?= csrf_field(); ?>
                           <input type="hidden" name="_method" value="DELETE">
                           <button title="Delete" onclick="return confirm('Konfirmasi untuk menghapus data');" type="submit" class="btn btn-danger p-2" id="<?= $value['nip']; ?>">
                              <i class="material-icons">delete_forever</i>
                           </button>
                        </form>
                        <a title="Download QR Code" href="<?= base_url('admin/qr/penjaga/' . $value['id_penjaga'] . '/download'); ?>" class="btn btn-success p-2">
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