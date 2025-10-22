<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FitnessClassModel;
use CodeIgniter\HTTP\ResponseInterface;

class FitnessClassController extends BaseController
{
    protected $fitnessClassModel;

    public function __construct()
    {
        $this->fitnessClassModel = new FitnessClassModel();
    }

    public function index()
    {
        $generalSettings = model('GeneralSettingsModel')->first();
        $data = [
            'title' => 'Data Kelas Fitness',
            'classes' => $this->fitnessClassModel->getAllClasses(),
            'generalSettings' => $generalSettings,
            'ctx' => 'fitness-classes'
        ];
        return view('admin/fitness/classes', $data);
    }

    public function create()
    {
        $generalSettings = model('GeneralSettingsModel')->first();
        $data = [
            'title' => 'Tambah Kelas Fitness',
            'generalSettings' => $generalSettings
        ];
        return view('admin/fitness/create-class', $data);
    }

    public function store()
    {
        $rules = [
            'nama_class' => 'required|min_length[3]|max_length[255]',
            'durasi' => 'required|integer|greater_than[0]',
            'kapasitas' => 'required|integer|greater_than[0]',
            'harga' => 'required|decimal',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_class' => $this->request->getPost('nama_class'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'durasi' => $this->request->getPost('durasi'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'harga' => $this->request->getPost('harga'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->fitnessClassModel->save($data)) {
            return redirect()->to('/admin/fitness-classes')->with('success', 'Kelas fitness berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan kelas fitness');
        }
    }

    public function edit($id)
    {
        $class = $this->fitnessClassModel->getClassById($id);

        if (!$class) {
            return redirect()->to('/admin/fitness-classes')->with('error', 'Kelas fitness tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Kelas Fitness',
            'class' => $class,
            'content' => 'admin/fitness/edit-class'
        ];

        return view('templates/admin_page_layout', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_class' => 'required|min_length[3]|max_length[255]',
            'durasi' => 'required|integer|greater_than[0]',
            'kapasitas' => 'required|integer|greater_than[0]',
            'harga' => 'required|decimal',
            'status' => 'required|in_list[aktif,nonaktif]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama_class' => $this->request->getPost('nama_class'),
            'deskripsi' => $this->request->getPost('deskripsi'),
            'durasi' => $this->request->getPost('durasi'),
            'kapasitas' => $this->request->getPost('kapasitas'),
            'harga' => $this->request->getPost('harga'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->fitnessClassModel->update($id, $data)) {
            return redirect()->to('/admin/fitness-classes')->with('success', 'Kelas fitness berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui kelas fitness');
        }
    }

    public function delete($id)
    {
        if ($this->fitnessClassModel->delete($id)) {
            return redirect()->to('/admin/fitness-classes')->with('success', 'Kelas fitness berhasil dihapus');
        } else {
            return redirect()->to('/admin/fitness-classes')->with('error', 'Gagal menghapus kelas fitness');
        }
    }

    
    public function ajaxList()
    {
        // Ambil semua data kelas fitness
        $classes = method_exists($this->fitnessClassModel, 'getAllClasses')
            ? $this->fitnessClassModel->getAllClasses()
            : $this->fitnessClassModel->findAll();

        // Kembalikan partial view untuk AJAX
        return view('admin/fitness/list-classes', ['classes' => $classes]);
    }

    public function toggleStatus($id)
    {
        $class = $this->fitnessClassModel->getClassById($id);

        if (!$class) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kelas fitness tidak ditemukan']);
        }

        $newStatus = $class['status'] === 'aktif' ? 'nonaktif' : 'aktif';

        if ($this->fitnessClassModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status kelas fitness berhasil diubah',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status kelas fitness']);
        }
    }
}
