<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MembershipPackageModel;

class MembershipPackageController extends BaseController
{
    protected $membershipPackageModel;

    public function __construct()
    {
        $this->membershipPackageModel = new MembershipPackageModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Membership Packages',
            'ctx' => 'membership-packages',
        ];

        return view('admin/membership_packages/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Create Membership Package',
            'ctx' => 'membership-packages',
        ];

        return view('admin/membership_packages/create', $data);
    }

    public function store()
    {
        $rules = [
            'nama_package' => 'required|min_length[3]|max_length[100]',
            'harga' => 'required|numeric|greater_than[0]',
            'durasi_hari' => 'required|integer|greater_than[0]',
            'pt_sessions' => 'permit_empty|integer|greater_than_equal_to[0]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'benefits' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $benefits = $this->request->getPost('benefits');
        if (empty($benefits)) {
            $benefits = '[]';
        } else {
            // Split by newlines and filter out empty lines
            $benefitsArray = array_filter(array_map('trim', explode("\n", $benefits)));
            $benefits = json_encode($benefitsArray);
        }

        $data = [
            'nama_package' => $this->request->getPost('nama_package'),
            'harga' => $this->request->getPost('harga'),
            'durasi_hari' => $this->request->getPost('durasi_hari'),
            'pt_sessions' => $this->request->getPost('pt_sessions') ?: 0,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'benefits' => $benefits,
            'unlimited_classes' => $this->request->getPost('unlimited_classes') ? 1 : 0,
            'locker_access' => $this->request->getPost('locker_access') ? 1 : 0,
            'status' => 'aktif',
        ];

        $this->membershipPackageModel->createPackage($data);

        return redirect()->to('/admin/membership-packages')->with('success', 'Membership package created successfully');
    }

    public function edit($id)
    {
        $package = $this->membershipPackageModel->getPackageById($id);

        if (!$package) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Edit Membership Package',
            'ctx' => 'membership-packages',
            'package' => $package,
        ];

        return view('admin/membership_packages/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_package' => 'required|min_length[3]|max_length[100]',
            'harga' => 'required|numeric|greater_than[0]',
            'durasi_hari' => 'required|integer|greater_than[0]',
            'pt_sessions' => 'permit_empty|integer|greater_than_equal_to[0]',
            'deskripsi' => 'permit_empty|max_length[500]',
            'benefits' => 'permit_empty|max_length[1000]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $benefits = $this->request->getPost('benefits');
        if (empty($benefits)) {
            $benefits = '[]';
        } else {
            // Split by newlines and filter out empty lines
            $benefitsArray = array_filter(array_map('trim', explode("\n", $benefits)));
            $benefits = json_encode($benefitsArray);
        }

        $data = [
            'nama_package' => $this->request->getPost('nama_package'),
            'harga' => $this->request->getPost('harga'),
            'durasi_hari' => $this->request->getPost('durasi_hari'),
            'pt_sessions' => $this->request->getPost('pt_sessions') ?: 0,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'benefits' => $benefits,
            'unlimited_classes' => $this->request->getPost('unlimited_classes') ? 1 : 0,
            'locker_access' => $this->request->getPost('locker_access') ? 1 : 0,
        ];

        $this->membershipPackageModel->updatePackage($id, $data);

        return redirect()->to('/admin/membership-packages')->with('success', 'Membership package updated successfully');
    }

    public function delete($id)
    {
        $this->membershipPackageModel->deletePackage($id);

        return redirect()->to('/admin/membership-packages')->with('success', 'Membership package deleted successfully');
    }

    public function ajaxList()
    {
        $packages = $this->membershipPackageModel->findAll();

        return $this->response->setJSON($packages);
    }

    public function toggleStatus($id)
    {
        $package = $this->membershipPackageModel->getPackageById($id);

        if (!$package) {
            return $this->response->setJSON(['success' => false, 'message' => 'Package not found']);
        }

        $newStatus = $package['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        $this->membershipPackageModel->updatePackage($id, ['status' => $newStatus]);

        return $this->response->setJSON(['success' => true, 'status' => $newStatus]);
    }
}
