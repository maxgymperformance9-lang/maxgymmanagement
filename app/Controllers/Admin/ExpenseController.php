<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ExpenseModel;
use CodeIgniter\HTTP\ResponseInterface;

class ExpenseController extends BaseController
{
    protected ExpenseModel $expenseModel;

    public function __construct()
    {
        $this->expenseModel = new ExpenseModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pengeluaran',
            'ctx' => 'pengeluaran',
        ];

        return view('admin/pengeluaran/pengeluaran', $data);
    }

    public function ambilDataPengeluaran()
    {
        $bulan = $this->request->getVar('bulan') ?? date('Y-m');

        $pengeluaran = $this->expenseModel->getAllExpenses($bulan);

        $data = [
            'pengeluaran' => $pengeluaran,
            'bulan' => $bulan
        ];

        return view('admin/pengeluaran/list-pengeluaran', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Pengeluaran',
            'ctx' => 'pengeluaran',
        ];

        return view('admin/pengeluaran/create-pengeluaran', $data);
    }

    public function store()
    {
        $data = [
            'id_expense' => $this->expenseModel->generateExpenseId(),
            'description' => $this->request->getVar('description'),
            'amount' => $this->request->getVar('amount'),
            'category' => $this->request->getVar('category'),
            'expense_date' => $this->request->getVar('expense_date'),
        ];

        if ($this->expenseModel->save($data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pengeluaran berhasil ditambahkan'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menambahkan pengeluaran',
                'errors' => $this->expenseModel->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $pengeluaran = $this->expenseModel->find($id);

        if (!$pengeluaran) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengeluaran tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Pengeluaran',
            'ctx' => 'pengeluaran',
            'pengeluaran' => $pengeluaran
        ];

        return view('admin/pengeluaran/edit-pengeluaran', $data);
    }

    public function update($id)
    {
        $data = [
            'description' => $this->request->getVar('description'),
            'amount' => $this->request->getVar('amount'),
            'category' => $this->request->getVar('category'),
            'expense_date' => $this->request->getVar('expense_date'),
        ];

        if ($this->expenseModel->update($id, $data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pengeluaran berhasil diupdate'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal mengupdate pengeluaran',
                'errors' => $this->expenseModel->errors()
            ]);
        }
    }

    public function delete($id)
    {
        if ($this->expenseModel->delete($id)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pengeluaran berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Gagal menghapus pengeluaran'
            ]);
        }
    }
}
