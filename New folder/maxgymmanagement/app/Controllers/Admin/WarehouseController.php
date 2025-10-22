<?php namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WarehouseModel;

class WarehouseController extends BaseController
{
    protected $warehouseModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->warehouseModel = new WarehouseModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Gudang',
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/warehouse/index', $data);
    }

    public function getWarehouses()
    {
        $warehouses = $this->warehouseModel->getWarehouseWithStock();
        return $this->response->setJSON([
            'data' => $warehouses,
            'empty' => empty($warehouses)
        ]);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Gudang',
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/warehouse/create', $data);
    }

    public function store()
    {
        $rules = $this->warehouseModel->validationRules;
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_gudang' => $this->request->getPost('nama_gudang'),
            'lokasi' => $this->request->getPost('lokasi'),
            'status' => $this->request->getPost('status') ?? 'active'
        ];

        if ($this->warehouseModel->insert($data)) {
            return redirect()->to('/admin/warehouse')->with('msg', 'Gudang berhasil ditambahkan')->with('error', false);
        }

        return redirect()->back()->withInput()->with('msg', 'Gagal menambahkan gudang')->with('error', true);
    }

    public function edit($id)
    {
        $warehouse = $this->warehouseModel->find($id);
        if (!$warehouse) {
            return redirect()->to('/admin/warehouse')->with('msg', 'Gudang tidak ditemukan')->with('error', true);
        }

        $data = [
            'title' => 'Edit Gudang',
            'warehouse' => $warehouse,
            'generalSettings' => $this->generalSettings
        ];
        return view('admin/warehouse/edit', $data);
    }

    public function update($id)
    {
        $rules = $this->warehouseModel->validationRules;
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_gudang' => $this->request->getPost('nama_gudang'),
            'lokasi' => $this->request->getPost('lokasi'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->warehouseModel->update($id, $data)) {
            return redirect()->to('/admin/warehouse')->with('msg', 'Gudang berhasil diupdate')->with('error', false);
        }

        return redirect()->back()->withInput()->with('msg', 'Gagal mengupdate gudang')->with('error', true);
    }

    public function delete($id)
    {
        if ($this->warehouseModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Gudang berhasil dihapus']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'Gagal menghapus gudang']);
    }

    public function toggleStatus($id)
    {
        $warehouse = $this->warehouseModel->find($id);
        if (!$warehouse) {
            return $this->response->setJSON(['success' => false, 'message' => 'Gudang tidak ditemukan']);
        }

        $newStatus = $warehouse['status'] === 'active' ? 'inactive' : 'active';
        if ($this->warehouseModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status gudang berhasil diubah',
                'new_status' => $newStatus
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status gudang']);
    }
}
