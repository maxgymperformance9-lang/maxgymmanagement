<?php

namespace App\Models;

use CodeIgniter\Model;

class RfidTransactionModel extends Model
{
    protected $table = 'tb_rfid_transactions';
    protected $primaryKey = 'id_transaction';
    protected $allowedFields = [
        'id_rfid',
        'transaction_type',
        'amount',
        'description',
        'transaction_date',
        'processed_by'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_rfid' => 'required|integer',
        'transaction_type' => 'required|in_list[topup,payment,refund]',
        'amount' => 'required|decimal',
        'description' => 'permit_empty|string|max_length[255]',
        'processed_by' => 'permit_empty|integer',
    ];

    protected $validationMessages = [
        'id_rfid' => [
            'required' => 'RFID card ID is required',
            'integer' => 'RFID card ID must be an integer'
        ],
        'transaction_type' => [
            'required' => 'Transaction type is required',
            'in_list' => 'Invalid transaction type'
        ],
        'amount' => [
            'required' => 'Amount is required',
            'decimal' => 'Amount must be a valid decimal number'
        ],
        'description' => [
            'string' => 'Description must be a string',
            'max_length' => 'Description cannot exceed 255 characters'
        ],
        'processed_by' => [
            'integer' => 'Processed by must be an integer'
        ]
    ];

    // Relationships
    public function rfidCard()
    {
        return $this->belongsTo('App\Models\RfidCardModel', 'id_rfid', 'id_rfid');
    }

    public function processedBy()
    {
        return $this->belongsTo('App\Models\PegawaiModel', 'processed_by', 'id_pegawai');
    }

    // Custom methods
    public function getTransactionsByCard($cardId, $limit = null)
    {
        $query = $this->where('id_rfid', $cardId)->orderBy('transaction_date', 'DESC');

        if ($limit) {
            $query->limit($limit);
        }

        return $query->findAll();
    }

    public function getTransactionsByType($type, $dateFrom = null, $dateTo = null)
    {
        $query = $this->where('transaction_type', $type);

        if ($dateFrom) {
            $query->where('transaction_date >=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('transaction_date <=', $dateTo);
        }

        return $query->findAll();
    }

    public function getTotalAmountByType($type, $dateFrom = null, $dateTo = null)
    {
        $query = $this->selectSum('amount')->where('transaction_type', $type);

        if ($dateFrom) {
            $query->where('transaction_date >=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('transaction_date <=', $dateTo);
        }

        $result = $query->first();
        return $result ? $result['amount'] : 0;
    }

    public function recordTransaction($cardId, $type, $amount, $description = null, $processedBy = null)
    {
        $data = [
            'id_rfid' => $cardId,
            'transaction_type' => $type,
            'amount' => $amount,
            'description' => $description,
            'transaction_date' => date('Y-m-d H:i:s'),
            'processed_by' => $processedBy
        ];

        return $this->insert($data);
    }

    public function getRecentTransactions($limit = 10)
    {
        return $this->orderBy('transaction_date', 'DESC')->limit($limit)->findAll();
    }

    public function getTransactionSummary($dateFrom = null, $dateTo = null)
    {
        $query = $this->select('transaction_type, COUNT(*) as count, SUM(amount) as total')
                     ->groupBy('transaction_type');

        if ($dateFrom) {
            $query->where('transaction_date >=', $dateFrom);
        }

        if ($dateTo) {
            $query->where('transaction_date <=', $dateTo);
        }

        return $query->findAll();
    }
}
