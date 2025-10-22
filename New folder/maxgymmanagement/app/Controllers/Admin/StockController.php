<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\StockModel;

class StockController extends BaseController
{
    protected $stockModel;

    public function __construct()
    {
        $this->stockModel = new StockModel();
    }

    public function index()
    {
        $data['title'] = 'Stock Overview';
        $data['stocks'] = $this->stockModel->getAllStock();
        return view('admin/stock/index', $data);
    }

    public function movements()
    {
        $stockMovementModel = new \App\Models\StockMovementModel();
        $data['title'] = 'Stock Movements';
        $data['movements'] = $stockMovementModel->getMovementsWithDetails();
        return view('admin/stock/movements', $data);
    }

    public function manage()
    {
        $data['title'] = 'Manage Stock';
        $data['stocks'] = $this->stockModel->getAllStock();
        return view('admin/stock/manage', $data);
    }

    public function stockIn()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'warehouse_id' => 'required|integer',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validasi gagal']);
            }

            $warehouseId = $this->request->getPost('warehouse_id');
            $productId = $this->request->getPost('product_id');
            $quantity = $this->request->getPost('quantity');

            if ($this->stockModel->updateStock($warehouseId, $productId, $quantity)) {
                // Log movement
                $stockMovementModel = new \App\Models\StockMovementModel();
                $stockMovementModel->insert([
                    'id_warehouse' => $warehouseId,
                    'id_product' => $productId,
                    'movement_type' => 'in',
                    'quantity' => $quantity,
                    'notes' => $this->request->getPost('notes') ?? ''
                ]);
                return $this->response->setJSON(['success' => true, 'message' => 'Stok berhasil ditambahkan']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Gagal menambahkan stok']);
        }

        $data['title'] = 'Stock In';
        return view('admin/stock/stock-in', $data);
    }

    public function stockOut()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'warehouse_id' => 'required|integer',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validasi gagal']);
            }

            $warehouseId = $this->request->getPost('warehouse_id');
            $productId = $this->request->getPost('product_id');
            $quantity = -$this->request->getPost('quantity'); // Negative for out

            if ($this->stockModel->updateStock($warehouseId, $productId, $quantity)) {
                // Log movement
                $stockMovementModel = new \App\Models\StockMovementModel();
                $stockMovementModel->insert([
                    'id_warehouse' => $warehouseId,
                    'id_product' => $productId,
                    'movement_type' => 'out',
                    'quantity' => abs($quantity),
                    'notes' => $this->request->getPost('notes') ?? ''
                ]);
                return $this->response->setJSON(['success' => true, 'message' => 'Stok berhasil dikurangi']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengurangi stok']);
        }

        $data['title'] = 'Stock Out';
        return view('admin/stock/stock-out', $data);
    }

    public function transfer()
    {
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'from_warehouse_id' => 'required|integer',
                'to_warehouse_id' => 'required|integer',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer|greater_than[0]'
            ];

            if (!$this->validate($rules)) {
                return $this->response->setJSON(['success' => false, 'message' => 'Validasi gagal']);
            }

            $fromWarehouseId = $this->request->getPost('from_warehouse_id');
            $toWarehouseId = $this->request->getPost('to_warehouse_id');
            $productId = $this->request->getPost('product_id');
            $quantity = $this->request->getPost('quantity');

            // Check if source warehouse has enough stock
            $currentStock = $this->stockModel->getStockByWarehouseAndProduct($fromWarehouseId, $productId);
            if (!$currentStock || $currentStock['quantity'] < $quantity) {
                return $this->response->setJSON(['success' => false, 'message' => 'Stok tidak mencukupi di gudang asal']);
            }

            // Record the transfer movement using the StockMovementModel's recordMovement method
            $stockMovementModel = new \App\Models\StockMovementModel();
            $movementData = [
                'id_warehouse' => $fromWarehouseId, // Primary warehouse for the movement
                'id_product' => $productId,
                'movement_type' => 'transfer',
                'quantity' => $quantity,
                'from_warehouse' => $fromWarehouseId,
                'to_warehouse' => $toWarehouseId,
                'notes' => $this->request->getPost('notes') ?? ''
            ];

            if ($stockMovementModel->recordMovement($movementData)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Transfer stok berhasil']);
            }

            return $this->response->setJSON(['success' => false, 'message' => 'Gagal melakukan transfer stok']);
        }

        $data['title'] = 'Transfer Stock';
        return view('admin/stock/transfer', $data);
    }

    public function getLowStockAlerts()
    {
        $alerts = $this->stockModel->getLowStockAlerts();
        return $this->response->setJSON(['success' => true, 'data' => $alerts]);
    }

    public function updateStockLevels()
    {
        $rules = [
            'warehouse_id' => 'required|integer',
            'product_id' => 'required|integer',
            'min_stock' => 'required|integer|greater_than_equal_to[0]',
            'max_stock' => 'permit_empty|integer|greater_than_equal_to[0]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Validasi gagal']);
        }

        $warehouseId = $this->request->getPost('warehouse_id');
        $productId = $this->request->getPost('product_id');
        $minStock = $this->request->getPost('min_stock');
        $maxStock = $this->request->getPost('max_stock');

        if ($this->stockModel->updateStockLevels($warehouseId, $productId, $minStock, $maxStock)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Level stok berhasil diupdate']);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengupdate level stok']);
    }

    public function getStocks()
    {
        $stocks = $this->stockModel->getAllStock();
        return $this->response->setJSON(['success' => true, 'data' => $stocks]);
    }

    public function getMovements()
    {
        $stockMovementModel = new \App\Models\StockMovementModel();
        $movements = $stockMovementModel->getMovementsWithDetails();
        return $this->response->setJSON(['success' => true, 'data' => $movements]);
    }

    public function getStockByWarehouse()
    {
        $warehouseId = $this->request->getPost('warehouse_id');
        $productId = $this->request->getPost('product_id');

        if (!$warehouseId) {
            return $this->response->setJSON(['success' => false, 'message' => 'Warehouse ID required']);
        }

        $stocks = $this->stockModel->getStockByWarehouse($warehouseId);
        return $this->response->setJSON(['success' => true, 'data' => $stocks]);
    }

    public function getWarehouses()
    {
        $warehouseModel = new \App\Models\WarehouseModel();
        $warehouses = $warehouseModel->where('status', 'active')->findAll();
        return $this->response->setJSON(['success' => true, 'data' => $warehouses]);
    }

    public function getProducts()
    {
        $productModel = new \App\Models\ProductModel();
        $products = $productModel->findAll();
        return $this->response->setJSON(['success' => true, 'data' => $products]);
    }
}
