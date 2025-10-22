<?php namespace App\Models;

use CodeIgniter\Model;

class TransactionItemModel extends Model
{
    protected $table = 'tb_transaction_items';
    protected $primaryKey = 'id_transaction_item';
    protected $allowedFields = [
        'id_transaction',
        'id_product',
        'nama_produk',
        'harga',
        'quantity',
        'subtotal',
        'id_package'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_transaction' => 'required|max_length[50]',
        'id_product' => 'permit_empty|integer',
        'nama_produk' => 'required|max_length[255]',
        'harga' => 'required|numeric|greater_than[0]',
        'quantity' => 'required|integer|greater_than[0]',
        'subtotal' => 'required|numeric|greater_than[0]',
        'id_package' => 'permit_empty|integer'
    ];

    protected $validationMessages = [
        'id_transaction' => [
            'required' => 'ID transaksi harus diisi',
            'max_length' => 'ID transaksi maksimal 50 karakter'
        ],
        'id_product' => [
            'required' => 'ID produk harus diisi',
            'integer' => 'ID produk harus berupa bilangan bulat'
        ],
        'nama_produk' => [
            'required' => 'Nama produk harus diisi',
            'max_length' => 'Nama produk maksimal 255 karakter'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih besar dari 0'
        ],
        'quantity' => [
            'required' => 'Quantity harus diisi',
            'integer' => 'Quantity harus berupa bilangan bulat',
            'greater_than' => 'Quantity harus lebih besar dari 0'
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'numeric' => 'Subtotal harus berupa angka',
            'greater_than' => 'Subtotal harus lebih besar dari 0'
        ]
    ];

    public function getItemsByTransaction($transactionId)
    {
        return $this->where('id_transaction', $transactionId)->findAll();
    }

    public function getItemsWithProduct($transactionId)
    {
        return $this->select('tb_transaction_items.*, tb_products.stok')
                    ->join('tb_products', 'tb_products.id_product = tb_transaction_items.id_product')
                    ->where('id_transaction', $transactionId)
                    ->findAll();
    }
}
