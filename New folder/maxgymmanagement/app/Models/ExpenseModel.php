<?php

namespace App\Models;

use CodeIgniter\Model;

class ExpenseModel extends Model
{
    protected $table            = 'tb_expenses';
    protected $primaryKey       = 'id_expense';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id_expense', 'description', 'amount', 'category', 'expense_date'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_expense' => 'required|is_unique[tb_expenses.id_expense]',
        'description' => 'required|max_length[255]',
        'amount' => 'required|numeric|greater_than[0]',
        'category' => 'required|max_length[100]',
        'expense_date' => 'required|valid_date'
    ];
    protected $validationMessages   = [
        'id_expense' => [
            'required' => 'ID pengeluaran harus diisi',
            'is_unique' => 'ID pengeluaran sudah ada'
        ],
        'description' => [
            'required' => 'Deskripsi harus diisi',
            'max_length' => 'Deskripsi maksimal 255 karakter'
        ],
        'amount' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih besar dari 0'
        ],
        'category' => [
            'required' => 'Kategori harus diisi',
            'max_length' => 'Kategori maksimal 100 karakter'
        ],
        'expense_date' => [
            'required' => 'Tanggal pengeluaran harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function generateExpenseId()
    {
        $date = date('Ymd');
        $lastExpense = $this->like('id_expense', $date, 'after')
                            ->orderBy('id_expense', 'DESC')
                            ->first();

        if ($lastExpense) {
            $lastNumber = (int) substr($lastExpense['id_expense'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getMonthlyExpenses($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->select('
                COUNT(*) as total_expenses,
                SUM(amount) as total_expense_amount,
                AVG(amount) as average_expense
            ')
            ->where('DATE(expense_date) >=', $startDate)
            ->where('DATE(expense_date) <=', $endDate)
            ->first();
    }

    public function getExpensesByCategory($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->select('
                category,
                COUNT(*) as count,
                SUM(amount) as total_amount
            ')
            ->where('DATE(expense_date) >=', $startDate)
            ->where('DATE(expense_date) <=', $endDate)
            ->groupBy('category')
            ->findAll();
    }

    public function getAllExpenses($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        return $this->where('DATE(expense_date) >=', $startDate)
                    ->where('DATE(expense_date) <=', $endDate)
                    ->orderBy('expense_date', 'DESC')
                    ->findAll();
    }
}
