<?php namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table = 'tb_products';
    protected $primaryKey = 'id_product';
    protected $allowedFields = [
        'nama_produk',
        'harga',
        'stok',
        'kategori',
        'deskripsi',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama_produk' => 'required|min_length[2]|max_length[255]',
        'harga' => 'required|numeric|greater_than[0]',
        'stok' => 'required|integer|greater_than_equal_to[0]',
        'kategori' => 'permit_empty|max_length[100]',
        'deskripsi' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'nama_produk' => [
            'required' => 'Nama produk harus diisi',
            'min_length' => 'Nama produk minimal 2 karakter',
            'max_length' => 'Nama produk maksimal 255 karakter'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih besar dari 0'
        ],
        'stok' => [
            'required' => 'Stok harus diisi',
            'integer' => 'Stok harus berupa bilangan bulat',
            'greater_than_equal_to' => 'Stok tidak boleh negatif'
        ],
        'kategori' => [
            'max_length' => 'Kategori maksimal 100 karakter'
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 1000 karakter'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus active atau inactive'
        ]
    ];

    public function getActiveProducts()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getProductById($id)
    {
        return $this->find($id);
    }

    public function updateStock($id, $quantity)
    {
        $product = $this->find($id);
        if ($product) {
            $newStock = $product['stok'] - $quantity;
            if ($newStock >= 0) {
                return $this->update($id, ['stok' => $newStock]);
            }
        }
        return false;
    }
}
