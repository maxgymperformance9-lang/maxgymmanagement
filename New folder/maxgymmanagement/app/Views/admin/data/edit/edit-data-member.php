<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-success">
                  <h4 class="card-title"><b>Form Edit Member</b></h4>

               </div>
               <div class="card-body mx-5 my-3">

                  <?php if (session()->getFlashdata('msg')) : ?>
                     <div class="pb-2">
                        <div class="alert alert-<?= session()->getFlashdata('error') == true ? 'danger' : 'success'  ?> ">
                           <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                              <i class="material-icons">close</i>
                           </button>
                           <?= session()->getFlashdata('msg') ?>
                        </div>
                     </div>
                  <?php endif; ?>

                  <form action="<?= base_url('admin/member/edit'); ?>" method="post" enctype="multipart/form-data">
                     <?= csrf_field() ?>
                     <?php $validation = \Config\Services::validation(); ?>

                     <input type="hidden" name="id" value="<?= $data['id_member'] ?>">

                     <div class="form-group mt-4">
                        <label for="nama">Nama Member</label>
                        <input type="text" id="nama" class="form-control <?= $validation->getError('nama') ? 'is-invalid' : ''; ?>" name="nama" placeholder="Nama Member" value="<?= old('nama') ?? $oldInput['nama'] ?? $data['nama_member'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('nama'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-2">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <?php
                        $jenisKelamin = (old('jenis_kelamin') ?? $oldInput['jenis_kelamin'] ?? $data['jenis_kelamin']);
                        $l = $jenisKelamin == 'Laki-laki' ? 'checked' : '';
                        $pe = $jenisKelamin == 'Perempuan' ? 'checked' : '';
                        ?>
                        <div class="form-check form-control pt-0 mb-1 <?= $validation->getError('jenis_kelamin') ? 'is-invalid' : ''; ?>">
                           <div class="row">
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="jenis_kelamin" id="laki" value="Laki-laki" <?= $l; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="laki">
                                          <h6 class="text-dark">Laki-laki</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan" <?= $pe; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="perempuan">
                                          <h6 class="text-dark">Perempuan</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="invalid-feedback">
                           <?= $validation->getError('jenis_kelamin'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="no_member">No Member</label>
                        <input type="text" id="no_member" class="form-control <?= $validation->getError('no_member') ? 'is-invalid' : ''; ?>" name="no_member" placeholder="No Member" value="<?= old('no_member') ?? $oldInput['no_member'] ?? $data['no_member'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('no_member'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-2">
                        <label for="type">Type Member</label>
                        <?php
                        $typeMember = (old('type') ?? $oldInput['type'] ?? $data['type_member']);
                        $u = $typeMember == 'umum' ? 'checked' : '';
                        $p = $typeMember == 'pelajar' ? 'checked' : '';
                        $m = $typeMember == 'mahasiswa' ? 'checked' : '';
                        $pt = $typeMember == 'personal_trainer' ? 'checked' : '';
                        $mpt = $typeMember == 'member_pt' ? 'checked' : '';
                        ?>
                        <div class="form-check form-control pt-0 mb-1 <?= $validation->getError('type') ? 'is-invalid' : ''; ?>">
                           <div class="row">
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="type" id="umum" value="umum" <?= $u; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="umum">
                                          <h6 class="text-dark">Umum</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="type" id="pelajar" value="pelajar" <?= $p; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="pelajar">
                                          <h6 class="text-dark">Pelajar</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="type" id="mahasiswa" value="mahasiswa" <?= $m; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="mahasiswa">
                                          <h6 class="text-dark">Mahasiswa</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="type" id="personal_trainer" value="personal_trainer" <?= $pt; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="personal_trainer">
                                          <h6 class="text-dark">Personal Trainer</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                              <div class="col-auto">
                                 <div class="row">
                                    <div class="col-auto pr-1">
                                       <input class="form-check" type="radio" name="type" id="member_pt" value="member_pt" <?= $mpt; ?>>
                                    </div>
                                    <div class="col">
                                       <label class="form-check-label pl-0 pt-1" for="member_pt">
                                          <h6 class="text-dark">Member + PT</h6>
                                       </label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="invalid-feedback">
                           <?= $validation->getError('type'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="hp">No HP</label>
                        <input type="number" id="hp" name="no_hp" class="form-control <?= $validation->getError('no_hp') ? 'is-invalid' : ''; ?>" placeholder="08969xxx" value="<?= old('no_hp') ?? $oldInput['no_hp'] ?? $data['no_hp'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('no_hp'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control <?= $validation->getError('email') ? 'is-invalid' : ''; ?>" placeholder="email@example.com" value="<?= old('email') ?? $oldInput['email'] ?? $data['email'] ?>">
                        <div class="invalid-feedback">
                           <?= $validation->getError('email'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" class="form-control" rows="3"><?= old('alamat') ?? $oldInput['alamat'] ?? $data['alamat'] ?></textarea>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tanggal_join">Tanggal Join</label>
                        <input type="date" id="tanggal_join" name="tanggal_join" class="form-control <?= $validation->getError('tanggal_join') ? 'is-invalid' : ''; ?>" value="<?= old('tanggal_join') ?? $oldInput['tanggal_join'] ?? $data['tanggal_join'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tanggal_join'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="tanggal_expired">Tanggal Expired</label>
                        <input type="date" id="tanggal_expired" name="tanggal_expired" class="form-control <?= $validation->getError('tanggal_expired') ? 'is-invalid' : ''; ?>" value="<?= old('tanggal_expired') ?? $oldInput['tanggal_expired'] ?? $data['tanggal_expired'] ?>" required>
                        <div class="invalid-feedback">
                           <?= $validation->getError('tanggal_expired'); ?>
                        </div>
                     </div>

                     <div class="form-group mt-4">
                        <label for="keterangan">Keterangan</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" rows="3"><?= old('keterangan') ?? $oldInput['keterangan'] ?? $data['keterangan'] ?></textarea>
                     </div>

                     <div class="form-group mt-4">
                        <label for="foto">Foto</label>
                        <div class="row">
                           <div class="col-md-6">
                              <?php if (!empty($data['foto'])): ?>
                                 <div class="mb-3">
                                    <label>Foto Saat Ini:</label><br>
                                    <img src="<?= base_url($data['foto']); ?>" alt="Foto Member" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                 </div>
                              <?php endif; ?>
                           </div>
                           <div class="col-md-6">
                              <label>Ganti Foto:</label>
                              <div class="input-group">
                                 <input type="text" class="form-control" id="foto-display" placeholder="Pilih Foto" readonly>
                                 <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" onclick="document.getElementById('foto').click()">Pilih Foto</button>
                                 </div>
                              </div>
                              <input type="file" id="foto" name="foto" class="d-none" accept="image/*">
                              <div class="invalid-feedback">
                                 <?= $validation->getError('foto'); ?>
                              </div>
                              <small class="form-text text-muted">Format: JPG, JPEG, PNG, GIF. Maksimal 2MB.</small>
                           </div>
                        </div>
                     </div>

                     <button type="submit" class="btn btn-success btn-block">Simpan</button>
                  </form>

                  <hr>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
// Update file input display when file is selected
$('#foto').on('change', function() {
   var fileName = $(this).val().split('\\').pop();
   $('#foto-display').val(fileName || 'Pilih Foto');
});
</script>
<?= $this->endSection() ?>
