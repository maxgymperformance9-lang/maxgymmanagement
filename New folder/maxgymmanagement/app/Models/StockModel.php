<?php namespace App\Models;

use CodeIgniter\Model;

class StockModel extends Model
{
    protected $table = 'tb_warehouse_stock';
    protected $primaryKey = 'id_stock';
    protected $allowedFields = [
        'id_warehouse',
        'id_product',
        'quantity',
        'min_stock',
        'max_stock'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_warehouse' => 'required|integer',
        'id_product' => 'required|integer',
        'quantity' => 'required|integer|greater_than_equal_to[0]',
        'min_stock' => 'required|integer|greater_than_equal_to[0]',
        'max_stock' => 'permit_empty|integer|greater_than_equal_to[0]'
    ];

    protected $validationMessages = [
        'id_warehouse' => [
            'required' => 'Gudang harus dipilih',
            'integer' => 'ID gudang harus berupa angka'
        ],
        'id_product' => [
            'required' => 'Produk harus dipilih',
            'integer' => 'ID produk harus berupa angka'
        ],
        'quantity' => [
            'required' => 'Quantity harus diisi',
            'integer' => 'Quantity harus berupa bilangan bulat',
            'greater_than_equal_to' => 'Quantity tidak boleh negatif'
        ],
        'min_stock' => [
            'required' => 'Minimum stock harus diisi',
            'integer' => 'Minimum stock harus berupa bilangan bulat',
            'greater_than_equal_to' => 'Minimum stock tidak boleh negatif'
        ],
        'max_stock' => [
            'integer' => 'Maximum stock harus berupa bilangan bulat',
            'greater_than_equal_to' => 'Maximum stock tidak boleh negatif'
        ]
    ];

    public function getStockByWarehouse($warehouseId)
    {
        return $this->select('tb_warehouse_stock.*, tb_products.nama_produk, tb_products.harga')
                    ->join('tb_products', 'tb_products.id_product = tb_warehouse_stock.id_product')
                    ->where('tb_warehouse_stock.id_warehouse', $warehouseId)
                    ->findAll();
    }

    public function getStockByProduct($productId)
    {
        return $this->select('tb_warehouse_stock.*, tb_warehouses.nama_gudang')
                    ->join('tb_warehouses', 'tb_warehouses.id_warehouse = tb_warehouse_stock.id_warehouse')
                    ->where('tb_warehouse_stock.id_product', $productId)
                    ->findAll();
    }

    public function getTotalStockByProduct($productId)
    {
        return $this->selectSum('quantity')
                    ->where('id_product', $productId)
                    ->first()['quantity'] ?? 0;
    }

    public function updateStock($warehouseId, $productId, $quantity)
    {
        $existing = $this->where('id_warehouse', $warehouseId)
                        ->where('id_product', $productId)
                        ->first();

        if ($existing) {
            $newQuantity = $existing['quantity'] + $quantity;
            if ($newQuantity >= 0) {
                return $this->update($existing['id_stock'], ['quantity' => $newQuantity]);
            }
        } else {
            // Create new stock entry if doesn't exist
            if ($quantity > 0) {
                return $this->insert([
                    'id_warehouse' => $warehouseId,
                    'id_product' => $productId,
                    'quantity' => $quantity,
                    'min_stock' => 0
                ]);
            }
        }
        return false;
    }

    public function getLowStockAlerts()
    {
        return $this->select('tb_warehouse_stock.*, tb_products.nama_produk, tb_warehouses.nama_gudang')
                    ->join('tb_products', 'tb_products.id_product = tb_warehouse_stock.id_product')
                    ->join('tb_warehouses', 'tb_warehouses.id_warehouse = tb_warehouse_stock.id_warehouse')
                    ->where('tb_warehouse_stock.quantity <= tb_warehouse_stock.min_stock')
                    ->where('tb_warehouses.status', 'active')
                    ->findAll();
    }

    public function updateStockLevels($warehouseId, $productId, $minStock, $maxStock = null)
    {
        $existing = $this->where('id_warehouse', $warehouseId)
                        ->where('id_product', $productId)
                        ->first();

        if ($existing) {
            $data = ['min_stock' => $minStock];
            if ($maxStock !== null) {
                $data['max_stock'] = $maxStock;
            }
            return $this->update($existing['id_stock'], $data);
        }

        return false;
    }

    public function getAllStock()
    {
        return $this->select('tb_warehouse_stock.*, tb_products.nama_produk, tb_warehouses.nama_gudang')
                    ->join('tb_products', 'tb_products.id_product = tb_warehouse_stock.id_product')
                    ->join('tb_warehouses', 'tb_warehouses.id_warehouse = tb_warehouse_stock.id_warehouse')
                    ->where('tb_warehouses.status', 'active')
                    ->findAll();
    }

    public function getStockByWarehouseAndProduct($warehouseId, $productId)
    {
        return $this->where('id_warehouse', $warehouseId)
                    ->where('id_product', $productId)
                    ->first();
    }
}
