<?= $this->extend('templates/admin_page_layout') ?>
<?= $this->section('content') ?>
<div class="content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-lg-12 col-md-12">
            <div class="card">
               <div class="card-header card-header-primary">
                  <h4 class="card-title">Point of Sale - MAXGYM</h4>
                  <p class="card-category">Kasir & Transaksi</p>
               </div>
               <div class="card-body">
                  <div class="row">
                     <!-- Product Selection -->
                     <div class="col-md-8">
                        <div class="row">
                           <div class="col-12">
                              <h5>Produk Tersedia</h5>
                           </div>
                        </div>
                        <div class="row" id="productList">
                           <?php foreach ($products as $product): ?>
                              <div class="col-md-4 col-sm-6 mb-3">
                                 <div class="card product-card" data-product-id="<?= $product['id_product'] ?>" data-nama="<?= esc($product['nama_produk']) ?>" data-harga="<?= $product['harga'] ?>" data-type="product">
                                    <div class="card-body text-center">
                                       <h6 class="card-title"><?= esc($product['nama_produk']) ?></h6>
                                       <p class="card-text">Rp <?= number_format($product['harga'], 0, ',', '.') ?></p>
                                       <p class="text-muted">Stok: <?= $product['stok'] ?></p>
                                       <button class="btn btn-primary btn-sm add-to-cart" data-id="<?= $product['id_product'] ?>" data-type="product">
                                          <i class="material-icons">add_shopping_cart</i> Tambah
                                       </button>
                                    </div>
                                 </div>
                              </div>
                           <?php endforeach; ?>
                        </div>
                     </div>

                     <!-- Cart and Checkout -->
                     <div class="col-md-4">
                        <div class="card">
                           <div class="card-header">
                              <h5>Keranjang Belanja</h5>
                           </div>
                           <div class="card-body">
                              <!-- Member Selection -->
                              <div class="form-group">
                                 <label for="memberSelect">Member (Opsional)</label>
                                 <select class="form-control" id="memberSelect">
                                    <option value="">Non-Member</option>
                                    <?php foreach ($members as $member): ?>
                                       <option value="<?= $member['id_member'] ?>"<?= (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))) ? ' class="text-danger"' : ''; ?>>
                                          <?= esc($member['nama_member']) ?> (<?= esc($member['no_member']) ?>)
                                          <?php if (strtotime($member['tanggal_expired']) < strtotime(date('Y-m-d'))): ?>
                                             - EXPIRED
                                          <?php endif; ?>
                                       </option>
                                    <?php endforeach; ?>
                                 </select>
                              </div>

                              <!-- Cart Items -->
                              <div id="cartItems">
                                 <p class="text-center text-muted">Keranjang kosong</p>
                              </div>

                              <!-- Totals -->
                              <hr>
                              <div class="row">
                                 <div class="col-6">
                                    <strong>Subtotal:</strong>
                                 </div>
                                 <div class="col-6 text-right">
                                    <span id="subtotal">Rp 0</span>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-6">
                                    <label for="ppn">PPN (%):</label>
                                 </div>
                                 <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="ppn" value="0" min="0" max="100">
                                 </div>
                              </div>
                              <div class="row mt-2">
                                 <div class="col-6">
                                    <label for="discount">Diskon (%):</label>
                                 </div>
                                 <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" id="discount" value="0" min="0" max="100">
                                 </div>
                              </div>
                              <div class="row mt-2">
                                 <div class="col-6">
                                    <strong>Total:</strong>
                                 </div>
                                 <div class="col-6 text-right">
                                    <strong><span id="total">Rp 0</span></strong>
                                 </div>
                              </div>

                              <!-- Payment Amount -->
                              <div class="form-group mt-3">
                                 <label for="paymentAmount">Jumlah Dibayar</label>
                                 <input type="number" class="form-control" id="paymentAmount" placeholder="0" min="0" step="0.01">
                              </div>

                              <!-- Change Amount -->
                              <div class="row mt-2">
                                 <div class="col-6">
                                    <strong>Kembalian:</strong>
                                 </div>
                                 <div class="col-6 text-right">
                                    <strong><span id="changeAmount">Rp 0</span></strong>
                                 </div>
                              </div>

                              <!-- Payment Method -->
                              <div class="form-group mt-3">
                                 <label for="paymentMethod">Metode Pembayaran</label>
                                 <select class="form-control" id="paymentMethod">
                                    <option value="cash">Tunai</option>
                                    <option value="card">Kartu</option>
                                    <option value="transfer">Transfer</option>
                                 </select>
                              </div>

                              <!-- Checkout Button -->
                              <button class="btn btn-success btn-block" id="checkoutBtn" disabled>
                                 <i class="material-icons">payment</i> Bayar
                              </button>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<script>
let cart = [];

function updateCart() {
   const cartItems = document.getElementById('cartItems');
   const subtotalEl = document.getElementById('subtotal');
   const totalEl = document.getElementById('total');
   const checkoutBtn = document.getElementById('checkoutBtn');

   if (cart.length === 0) {
      cartItems.innerHTML = '<p class="text-center text-muted">Keranjang kosong</p>';
      subtotalEl.textContent = 'Rp 0';
      totalEl.textContent = 'Rp 0';
      checkoutBtn.disabled = true;
      return;
   }

   let html = '';
   let subtotal = 0;

   cart.forEach((item, index) => {
      const itemTotal = item.harga * item.quantity;
      subtotal += itemTotal;
      html += `
         <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
               <strong>${item.nama_produk}</strong><br>
               <small>Rp ${item.harga.toLocaleString()} x ${item.quantity}</small>
            </div>
            <div class="text-right">
               <div>Rp ${itemTotal.toLocaleString()}</div>
               <button class="btn btn-sm btn-danger" onclick="removeFromCart(${index})">
                  <i class="material-icons">remove</i>
               </button>
            </div>
         </div>
      `;
   });

   cartItems.innerHTML = html;

   const ppn = parseFloat(document.getElementById('ppn').value) || 0;
   const discount = parseFloat(document.getElementById('discount').value) || 0;

   const ppnAmount = (subtotal * ppn) / 100;
   const discountAmount = (subtotal * discount) / 100;
   const total = subtotal + ppnAmount - discountAmount;

   subtotalEl.textContent = `Rp ${subtotal.toLocaleString()}`;
   totalEl.textContent = `Rp ${total.toLocaleString()}`;

   checkoutBtn.disabled = false;
}

function addToCart(id, nama, harga, type = 'product') {
   const key = type === 'package' ? 'id_package' : 'id_product';
   const existingItem = cart.find(item => item[key] == id && item.type === type);
   if (existingItem) {
      existingItem.quantity += 1;
   } else {
      const item = {
         nama_produk: nama,
         harga: parseFloat(harga),
         quantity: 1,
         type: type
      };
      item[key] = id;
      cart.push(item);
   }
   updateCart();
}

function removeFromCart(index) {
   cart.splice(index, 1);
   updateCart();
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
   // Add to cart buttons
   document.querySelectorAll('.add-to-cart').forEach(btn => {
      btn.addEventListener('click', function() {
         const card = this.closest('.card');
         const id = this.dataset.id;
         const nama = card.dataset.nama;
         const harga = card.dataset.harga;
         const type = this.dataset.type;
         addToCart(id, nama, harga, type);
      });
   });

   // Update totals when PPN or discount changes
   document.getElementById('ppn').addEventListener('input', updateCart);
   document.getElementById('discount').addEventListener('input', updateCart);

   // Update change amount when payment amount changes
   document.getElementById('paymentAmount').addEventListener('input', function() {
      const paymentAmount = parseFloat(this.value) || 0;
      const totalText = document.getElementById('total').textContent.replace(/[^\d]/g, '');
      const total = parseFloat(totalText) || 0;
      const change = paymentAmount - total;
      const changeEl = document.getElementById('changeAmount');
      changeEl.textContent = `Rp ${change.toLocaleString()}`;
      if (change < 0) {
         changeEl.style.color = 'red';
      } else {
         changeEl.style.color = 'black';
      }
   });

   // Checkout
   document.getElementById('checkoutBtn').addEventListener('click', function() {
      const memberId = document.getElementById('memberSelect').value;
      const paymentMethod = document.getElementById('paymentMethod').value;
      const ppn = document.getElementById('ppn').value;
      const discount = document.getElementById('discount').value;

      if (cart.length === 0) {
         alert('Keranjang kosong!');
         return;
      }

      if (confirm('Apakah Anda yakin ingin memproses transaksi ini?')) {
         fetch('<?= base_url('admin/kasir/checkout') ?>', {
            method: 'POST',
            headers: {
               'Content-Type': 'application/x-www-form-urlencoded',
               'X-Requested-With': 'XMLHttpRequest',
               'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
            },
            body: new URLSearchParams({
               'cart': JSON.stringify(cart),
               'member_id': memberId,
               'payment_method': paymentMethod,
               'payment_amount': document.getElementById('paymentAmount').value,
               'ppn_percentage': ppn,
               'discount_percentage': discount
            })
         })
         .then(response => response.json())
         .then(data => {
            if (data.success) {
               alert('Transaksi berhasil! ID: ' + data.transaction_id);
               cart = [];
               updateCart();
               // Redirect to receipt
               window.open('<?= base_url('admin/kasir/receipt/') ?>' + data.transaction_id, '_blank');
            } else {
               alert('Error: ' + data.message);
            }
         })
         .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses transaksi');
         });
      }
   });
});
</script>
<?= $this->endSection() ?>
