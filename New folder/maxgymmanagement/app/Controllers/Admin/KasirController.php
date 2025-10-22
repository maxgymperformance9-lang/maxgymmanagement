<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionItemModel;
use App\Models\MemberModel;

class KasirController extends BaseController
{
    protected $productModel;
    protected $transactionModel;
    protected $transactionItemModel;
    protected $memberModel;
    protected $db;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->productModel = new ProductModel();
        $this->transactionModel = new TransactionModel();
        $this->transactionItemModel = new TransactionItemModel();
        $this->memberModel = new MemberModel();
        $this->db = \Config\Database::connect();
    }

    // Product CRUD Methods
    public function index()
    {
        $data = [
            'title' => 'Data Produk',
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/data-produk', $data);
    }

    public function getProducts()
    {
        $products = $this->productModel->findAll();
        $data = [
            'data' => $products,
            'empty' => empty($products)
        ];
        return view('admin/kasir/list-data-produk', $data);
    }

    public function createProduct()
    {
        $data = [
            'title' => 'Tambah Produk',
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/create/create-data-produk', $data);
    }

    public function storeProduct()
    {
        $rules = $this->productModel->validationRules;
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->productModel->insert($data)) {
            return redirect()->to('/admin/produk')->with('msg', 'Produk berhasil ditambahkan')->with('error', false);
        }

        return redirect()->back()->withInput()->with('msg', 'Gagal menambahkan produk')->with('error', true);
    }

    public function editProduct($id)
    {
        $product = $this->productModel->find($id);
        if (!$product) {
            return redirect()->to('/admin/produk')->with('msg', 'Produk tidak ditemukan')->with('error', true);
        }

        $data = [
            'title' => 'Edit Produk',
            'product' => $product,
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/edit/edit-data-produk', $data);
    }

    public function updateProduct($id)
    {
        $rules = $this->productModel->validationRules;
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_produk' => $this->request->getPost('nama_produk'),
            'harga' => $this->request->getPost('harga'),
            'stok' => $this->request->getPost('stok'),
            'kategori' => $this->request->getPost('kategori'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->productModel->update($id, $data)) {
            return redirect()->to('/admin/produk')->with('msg', 'Produk berhasil diupdate')->with('error', false);
        }

        return redirect()->back()->withInput()->with('msg', 'Gagal mengupdate produk')->with('error', true);
    }

    public function deleteProduct($id)
    {
        if ($this->productModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Produk berhasil dihapus']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus produk']);
    }

    // Transaction Methods
    public function transaksi()
    {
        $data = [
            'title' => 'Data Transaksi',
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/data-transaksi', $data);
    }

    public function getTransactions()
    {
        $transactions = $this->transactionModel->getTransactionsWithMember();
        $data = [
            'data' => $transactions,
            'empty' => empty($transactions)
        ];
        return view('admin/kasir/list-data-transaksi', $data);
    }

    public function kasir()
    {
        $products = $this->productModel->getActiveProducts();
        $members = $this->memberModel->findAll();

        $data = [
            'title' => 'Point of Sale',
            'products' => $products,
            'members' => $members,
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/kasir', $data);
    }

    public function checkout()
    {
        $cart = json_decode($this->request->getPost('cart'), true);
        $memberId = $this->request->getPost('member_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $paymentAmount = $this->request->getPost('payment_amount');
        $ppnPercentage = $this->request->getPost('ppn_percentage') ?? 0;
        $discountPercentage = $this->request->getPost('discount_percentage') ?? 0;

        if (empty($cart)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Keranjang kosong']);
        }

        // Calculate totals
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['harga'] * $item['quantity'];
        }

        $ppnAmount = ($total * $ppnPercentage) / 100;
        $discountAmount = ($total * $discountPercentage) / 100;
        $grandTotal = $total + $ppnAmount - $discountAmount;

        // Validate payment amount
        if ($paymentAmount < $grandTotal) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jumlah pembayaran kurang dari total yang harus dibayar']);
        }

        $changeAmount = $paymentAmount - $grandTotal;

        // Generate transaction ID
        $transactionId = $this->transactionModel->generateTransactionId();

        // Start transaction
        $this->db->transStart();

        try {
            // Insert transaction
            $transactionData = [
                'id_transaction' => $transactionId,
                'total' => $total,
                'ppn_percentage' => $ppnPercentage,
                'discount_percentage' => $discountPercentage,
                'ppn_amount' => $ppnAmount,
                'discount_amount' => $discountAmount,
                'grand_total' => $grandTotal,
                'payment_amount' => $paymentAmount,
                'change_amount' => $changeAmount,
                'payment_method' => $paymentMethod,
                'tanggal' => date('Y-m-d H:i:s'),
                'id_member' => $memberId ?: null,
                'status' => 'completed'
            ];

            $this->transactionModel->insert($transactionData);

            // Insert transaction items and update stock
            foreach ($cart as $item) {
                $subtotal = $item['harga'] * $item['quantity'];

                $itemData = [
                    'id_transaction' => $transactionId,
                    'id_product' => isset($item['id_product']) ? $item['id_product'] : null,
                    'id_package' => isset($item['id_package']) ? $item['id_package'] : null,
                    'nama_produk' => $item['nama_produk'],
                    'harga' => $item['harga'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $subtotal
                ];

                $this->transactionItemModel->insert($itemData);

                // Update product stock only for products, not packages
                if (isset($item['id_product']) && !empty($item['id_product'])) {
                    $this->productModel->updateStock($item['id_product'], $item['quantity']);
                }

                // Assign membership package to member if package is purchased
                if (isset($item['id_package']) && !empty($item['id_package']) && !empty($memberId)) {
                    log_message('debug', 'Assigning package ' . $item['id_package'] . ' to member ' . $memberId);
                    $assignResult = $this->memberModel->assignPackage($memberId, $item['id_package']);
                    log_message('debug', 'Package assignment result: ' . ($assignResult ? 'success' : 'failed'));
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Transaksi gagal']);
            }

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Transaksi berhasil',
                'transaction_id' => $transactionId
            ]);

        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function viewTransaction($id)
    {
        $transaction = $this->transactionModel->getTransactionById($id);
        if (!$transaction) {
            return redirect()->to('/admin/kasir/transaksi')->with('msg', 'Transaksi tidak ditemukan')->with('error', true);
        }

        $items = $this->transactionModel->getTransactionItems($id);

        $data = [
            'title' => 'Detail Transaksi',
            'transaction' => $transaction,
            'items' => $items,
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/view-transaksi', $data);
    }

    public function printReceipt($id)
    {
        $transaction = $this->transactionModel->getTransactionById($id);
        if (!$transaction) {
            return redirect()->to('/admin/kasir/transaksi')->with('msg', 'Transaksi tidak ditemukan')->with('error', true);
        }

        $items = $this->transactionModel->getTransactionItems($id);
        $member = null;
        if ($transaction['id_member']) {
            $member = $this->memberModel->find($transaction['id_member']);
        }

        $data = [
            'transaction' => $transaction,
            'items' => $items,
            'member' => $member,
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/kasir/receipt', $data);
    }
}
