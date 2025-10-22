<?php namespace App\Models;

use CodeIgniter\Model;

class WarehouseModel extends Model
{
    protected $table = 'tb_warehouses';
    protected $primaryKey = 'id_warehouse';
    protected $allowedFields = [
        'nama_gudang',
        'lokasi',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'nama_gudang' => 'required|min_length[2]|max_length[255]',
        'lokasi' => 'permit_empty|max_length[1000]',
        'status' => 'required|in_list[active,inactive]'
    ];

    protected $validationMessages = [
        'nama_gudang' => [
            'required' => 'Nama gudang harus diisi',
            'min_length' => 'Nama gudang minimal 2 karakter',
            'max_length' => 'Nama gudang maksimal 255 karakter'
        ],
        'lokasi' => [
            'max_length' => 'Lokasi maksimal 1000 karakter'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus active atau inactive'
        ]
    ];

    public function getActiveWarehouses()
    {
        return $this->where('status', 'active')->findAll();
    }

    public function getWarehouseById($id)
    {
        return $this->find($id);
    }

    public function getWarehouseWithStock()
    {
        return $this->select('tb_warehouses.*, COUNT(tb_warehouse_stock.id_stock) as total_products')
                    ->join('tb_warehouse_stock', 'tb_warehouse_stock.id_warehouse = tb_warehouses.id_warehouse', 'left')
                    ->groupBy('tb_warehouses.id_warehouse')
                    ->findAll();
    }
}
