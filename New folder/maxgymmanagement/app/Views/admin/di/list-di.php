<div class="card-body table-responsive">
  <table class="table table-hover">
    <thead class="text-primary">
      <th><b>No</b></th>
      <th><b>D.I/WIL</b></th>
      <th><b>Wilayah</b></th>
      <th><b>Aksi</b></th>
    </thead>
    <tbody>
      <?php $i = 1;
      foreach ($data as $value) : ?>
        <tr>
          <td><?= $i; ?></td>
          <td><b><?= $value['di']; ?></b></td>
          <td><?= $value['wilayah']; ?></td>
          <td>
            <a href="<?= base_url('admin/di/edit/' .  $value['id_di']); ?>" type="button" class="btn btn-primary p-2" id="<?= $value['id_di']; ?>">
              <i class="material-icons">edit</i>
              Edit
            </a>
            <button onclick='deleteItem("admin/di/deleteDiPost","<?= $value["id_di"]; ?>","Konfirmasi untuk menghapus data");' class="btn btn-danger p-2" id="<?= $value['id_di']; ?>">
              <i class="material-icons">delete_forever</i>
              Delete
            </button>
          </td>
        </tr>
      <?php $i++;
      endforeach; ?>
    </tbody>
  </table>
</div>