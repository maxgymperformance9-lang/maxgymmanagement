<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Detail Transaksi</h4>
                  <p class="card-category">ID Transaksi: <?= esc($transaction['id_transaction']) ?></p>
               </div>
               <div class="card-body">
                  <div class="row">
                     <div class="col-md-6">
                        <h5>Informasi Transaksi</h5>
                        <table class="table table-borderless">
                           <tr>
                              <td><strong>ID Transaksi:</strong></td>
                              <td><?= esc($transaction['id_transaction']) ?></td>
                           </tr>
                           <tr>
                              <td><strong>Tanggal:</strong></td>
                              <td><?= date('d/m/Y H:i', strtotime($transaction['tanggal'])) ?></td>
                           </tr>
                           <tr>
                              <td><strong>Status:</strong></td>
                              <td><span class="badge badge-success"><?= esc(ucfirst($transaction['status'])) ?></span></td>
                           </tr>
                           <tr>
                              <td><strong>Metode Pembayaran:</strong></td>
                              <td><?= esc(ucfirst($transaction['payment_method'])) ?></td>
                           </tr>
                           <tr>
                              <td><strong>Member:</strong></td>
                              <td><?= esc($transaction['nama_member'] ?? 'Non-Member') ?></td>
                           </tr>
                        </table>
                     </div>
                     <div class="col-md-6">
                        <h5>Ringkasan Pembayaran</h5>
                        <table class="table table-borderless">
                           <tr>
                              <td><strong>Subtotal:</strong></td>
                              <td class="text-right"><?= number_format($transaction['total'], 0, ',', '.') ?> IDR</td>
                           </tr>
                           <tr>
                              <td><strong>PPN (<?= $transaction['ppn_percentage'] ?>%):</strong></td>
                              <td class="text-right"><?= number_format($transaction['ppn_amount'], 0, ',', '.') ?> IDR</td>
                           </tr>
                           <tr>
                              <td><strong>Diskon (<?= $transaction['discount_percentage'] ?>%):</strong></td>
                              <td class="text-right">-<?= number_format($transaction['discount_amount'], 0, ',', '.') ?> IDR</td>
                           </tr>
                           <tr>
                              <td><strong><h5>Total:</h5></strong></td>
                              <td class="text-right"><h5><?= number_format($transaction['grand_total'], 0, ',', '.') ?> IDR</h5></td>
                           </tr>
                        </table>
                     </div>
                  </div>
                  <hr>
                  <h5>Detail Produk</h5>
                  <div class="table-responsive">
                     <table class="table table-hover">
                        <thead class="text-primary">
                           <th>No</th>
                           <th>Nama Produk</th>
                           <th>Qty</th>
                           <th>Harga</th>
                           <th>Subtotal</th>
                        </thead>
                        <tbody>
                           <?php $no = 1; foreach ($items as $item): ?>
                              <tr>
                                 <td><?= $no++ ?></td>
                                 <td><?= esc($item['nama_produk']) ?></td>
                                 <td><?= $item['quantity'] ?></td>
                                 <td><?= number_format($item['harga'], 0, ',', '.') ?> IDR</td>
                                 <td><?= number_format($item['subtotal'], 0, ',', '.') ?> IDR</td>
                              </tr>
                           <?php endforeach; ?>
                        </tbody>
                     </table>
                  </div>
                  <div class="row">
                     <div class="col-12 text-right">
                        <a href="<?= base_url('admin/kasir/receipt/' . $transaction['id_transaction']) ?>" target="_blank" class="btn btn-success">
                           <i class="material-icons">print</i> Print Struk
                        </a>
                        <a href="<?= base_url('admin/kasir/transaksi') ?>" class="btn btn-secondary">
                           <i class="material-icons">arrow_back</i> Kembali
                        </a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?= $this->endSection() ?>
