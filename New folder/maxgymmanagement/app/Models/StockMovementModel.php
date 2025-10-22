<?php namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table = 'tb_stock_movements';
    protected $primaryKey = 'id_movement';
    protected $allowedFields = [
        'id_warehouse',
        'id_product',
        'movement_type',
        'quantity',
        'from_warehouse',
        'to_warehouse',
        'reference',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';

    protected $validationRules = [
        'id_warehouse' => 'required|integer',
        'id_product' => 'required|integer',
        'movement_type' => 'required|in_list[in,out,transfer,adjustment]',
        'quantity' => 'required|integer|greater_than[0]',
        'from_warehouse' => 'permit_empty|integer',
        'to_warehouse' => 'permit_empty|integer',
        'reference' => 'permit_empty|max_length[255]',
        'notes' => 'permit_empty|max_length[1000]'
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
        'movement_type' => [
            'required' => 'Tipe pergerakan harus dipilih',
            'in_list' => 'Tipe pergerakan tidak valid'
        ],
        'quantity' => [
            'required' => 'Quantity harus diisi',
            'integer' => 'Quantity harus berupa bilangan bulat',
            'greater_than' => 'Quantity harus lebih besar dari 0'
        ],
        'from_warehouse' => [
            'integer' => 'ID gudang asal harus berupa angka'
        ],
        'to_warehouse' => [
            'integer' => 'ID gudang tujuan harus berupa angka'
        ],
        'reference' => [
            'max_length' => 'Referensi maksimal 255 karakter'
        ],
        'notes' => [
            'max_length' => 'Catatan maksimal 1000 karakter'
        ]
    ];

    public function getMovementsWithDetails($limit = null)
    {
        $builder = $this->select('tb_stock_movements.*, tb_products.nama_produk, tb_warehouses.nama_gudang, fw.nama_gudang as from_warehouse_name, tw.nama_gudang as to_warehouse_name')
                        ->join('tb_products', 'tb_products.id_product = tb_stock_movements.id_product')
                        ->join('tb_warehouses', 'tb_warehouses.id_warehouse = tb_stock_movements.id_warehouse')
                        ->join('tb_warehouses as fw', 'fw.id_warehouse = tb_stock_movements.from_warehouse', 'left')
                        ->join('tb_warehouses as tw', 'tw.id_warehouse = tb_stock_movements.to_warehouse', 'left')
                        ->orderBy('tb_stock_movements.created_at', 'DESC');

        if ($limit) {
            $builder->limit($limit);
        }

        return $builder->findAll();
    }

    public function getMovementsByWarehouse($warehouseId)
    {
        return $this->select('tb_stock_movements.*, tb_products.nama_produk')
                    ->join('tb_products', 'tb_products.id_product = tb_stock_movements.id_product')
                    ->where('tb_stock_movements.id_warehouse', $warehouseId)
                    ->orderBy('tb_stock_movements.created_at', 'DESC')
                    ->findAll();
    }

    public function getMovementsByProduct($productId)
    {
        return $this->select('tb_stock_movements.*, tb_warehouses.nama_gudang')
                    ->join('tb_warehouses', 'tb_warehouses.id_warehouse = tb_stock_movements.id_warehouse')
                    ->where('tb_stock_movements.id_product', $productId)
                    ->orderBy('tb_stock_movements.created_at', 'DESC')
                    ->findAll();
    }

    public function recordMovement($data)
    {
        // Validate data
        if (!$this->validate($data)) {
            return false;
        }

        // Start transaction
        $this->db->transStart();

        try {
            // Insert movement record
            $this->insert($data);

            // Update warehouse stock based on movement type
            $warehouseStockModel = new WarehouseStockModel();

            switch ($data['movement_type']) {
                case 'in':
                    $warehouseStockModel->updateStock($data['id_warehouse'], $data['id_product'], $data['quantity']);
                    break;
                case 'out':
                    $warehouseStockModel->updateStock($data['id_warehouse'], $data['id_product'], -$data['quantity']);
                    break;
                case 'transfer':
                    if (isset($data['from_warehouse']) && isset($data['to_warehouse'])) {
                        // Decrease from source warehouse
                        $warehouseStockModel->updateStock($data['from_warehouse'], $data['id_product'], -$data['quantity']);
                        // Increase to destination warehouse
                        $warehouseStockModel->updateStock($data['to_warehouse'], $data['id_product'], $data['quantity']);
                    }
                    break;
                case 'adjustment':
                    // For adjustment, quantity can be positive or negative
                    $warehouseStockModel->updateStock($data['id_warehouse'], $data['id_product'], $data['quantity']);
                    break;
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return false;
            }

            return true;

        } catch (\Exception $e) {
            $this->db->transRollback();
            return false;
        }
    }
}
