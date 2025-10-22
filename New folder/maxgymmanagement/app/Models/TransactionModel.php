<?php namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'tb_transactions';
    protected $primaryKey = 'id_transaction';
    protected $allowedFields = [
        'id_transaction',
        'total',
        'ppn_percentage',
        'discount_percentage',
        'ppn_amount',
        'discount_amount',
        'grand_total',
        'payment_amount',
        'change_amount',
        'payment_method',
        'tanggal',
        'id_member',
        'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'total' => 'required|numeric|greater_than[0]',
        'ppn_percentage' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'discount_percentage' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        'ppn_amount' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'discount_amount' => 'permit_empty|numeric|greater_than_equal_to[0]',
        'grand_total' => 'required|numeric|greater_than[0]',
        'payment_method' => 'required|in_list[cash,card,transfer]',
        'tanggal' => 'required|valid_date',
        'id_member' => 'permit_empty|integer',
        'status' => 'required|in_list[pending,completed,cancelled]'
    ];

    protected $validationMessages = [
        'total' => [
            'required' => 'Total harus diisi',
            'numeric' => 'Total harus berupa angka',
            'greater_than' => 'Total harus lebih besar dari 0'
        ],
        'ppn_percentage' => [
            'numeric' => 'PPN persentase harus berupa angka',
            'greater_than_equal_to' => 'PPN persentase minimal 0',
            'less_than_equal_to' => 'PPN persentase maksimal 100'
        ],
        'discount_percentage' => [
            'numeric' => 'Diskon persentase harus berupa angka',
            'greater_than_equal_to' => 'Diskon persentase minimal 0',
            'less_than_equal_to' => 'Diskon persentase maksimal 100'
        ],
        'ppn_amount' => [
            'numeric' => 'Jumlah PPN harus berupa angka',
            'greater_than_equal_to' => 'Jumlah PPN minimal 0'
        ],
        'discount_amount' => [
            'numeric' => 'Jumlah diskon harus berupa angka',
            'greater_than_equal_to' => 'Jumlah diskon minimal 0'
        ],
        'grand_total' => [
            'required' => 'Grand total harus diisi',
            'numeric' => 'Grand total harus berupa angka',
            'greater_than' => 'Grand total harus lebih besar dari 0'
        ],
        'payment_method' => [
            'required' => 'Metode pembayaran harus diisi',
            'in_list' => 'Metode pembayaran harus cash, card, atau transfer'
        ],
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'id_member' => [
            'integer' => 'ID member harus berupa bilangan bulat'
        ],
        'status' => [
            'required' => 'Status harus diisi',
            'in_list' => 'Status harus pending, completed, atau cancelled'
        ]
    ];

    public function getTransactionsWithMember()
    {
        return $this->select('tb_transactions.*, tb_members.nama_member as nama_member')
                    ->join('tb_members', 'tb_members.id_member = tb_transactions.id_member', 'left')
                    ->findAll();
    }

    public function getTransactionById($id)
    {
        return $this->select('tb_transactions.*, tb_members.nama_member as nama_member')
                    ->join('tb_members', 'tb_members.id_member = tb_transactions.id_member', 'left')
                    ->find($id);
    }

    public function generateTransactionId()
    {
        $date = date('Ymd');
        $lastTransaction = $this->like('id_transaction', $date, 'after')
                                ->orderBy('id_transaction', 'DESC')
                                ->first();

        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction['id_transaction'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function getTransactionItems($transactionId)
    {
        $transactionItemModel = new TransactionItemModel();
        return $transactionItemModel->where('id_transaction', $transactionId)->findAll();
    }

    public function getTransactionsWithItems($startDate, $endDate)
    {
        $transactions = $this->select('tb_transactions.*, tb_members.nama_member as nama_member')
                            ->join('tb_members', 'tb_members.id_member = tb_transactions.id_member', 'left')
                            ->where('DATE(tb_transactions.tanggal) >=', $startDate)
                            ->where('DATE(tb_transactions.tanggal) <=', $endDate)
                            ->findAll();

        foreach ($transactions as &$transaction) {
            $transaction['items'] = $this->getTransactionItems($transaction['id_transaction']);
        }

        return $transactions;
    }

    public function getFinancialSummary($month)
    {
        $startDate = $month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate));

        $summary = $this->select('
                COUNT(*) as total_transactions,
                SUM(total) as total_revenue,
                SUM(ppn_amount) as total_ppn,
                SUM(discount_amount) as total_discount,
                SUM(grand_total) as total_grand_total,
                AVG(grand_total) as average_transaction
            ')
            ->where('DATE(tanggal) >=', $startDate)
            ->where('DATE(tanggal) <=', $endDate)
            ->where('status', 'completed')
            ->first();

        // Get payment method breakdown
        $paymentBreakdown = $this->select('
                payment_method,
                COUNT(*) as count,
                SUM(grand_total) as total_amount
            ')
            ->where('DATE(tanggal) >=', $startDate)
            ->where('DATE(tanggal) <=', $endDate)
            ->where('status', 'completed')
            ->groupBy('payment_method')
            ->findAll();

        $summary['payment_methods'] = [];
        foreach ($paymentBreakdown as $method) {
            $summary['payment_methods'][$method['payment_method']] = [
                'count' => $method['count'],
                'total_amount' => $method['total_amount']
            ];
        }

        return $summary;
    }
}
