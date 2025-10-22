<?php

namespace App\Models;

use CodeIgniter\Model;

class RfidBalanceModel extends Model
{
    protected $table = 'tb_rfid_balances';
    protected $primaryKey = 'id_balance';
    protected $allowedFields = [
        'id_rfid',
        'balance',
        'last_transaction_date'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_rfid' => 'required|integer|is_unique[tb_rfid_balances.id_rfid,id_balance,{id_balance}]',
        'balance' => 'required|decimal',
    ];

    protected $validationMessages = [
        'id_rfid' => [
            'required' => 'RFID card ID is required',
            'integer' => 'RFID card ID must be an integer',
            'is_unique' => 'Balance already exists for this RFID card'
        ],
        'balance' => [
            'required' => 'Balance is required',
            'decimal' => 'Balance must be a valid decimal number'
        ]
    ];

    // Relationships
    public function rfidCard()
    {
        return $this->belongsTo('App\Models\RfidCardModel', 'id_rfid', 'id_rfid');
    }

    // Custom methods
    public function getBalanceByCardId($cardId)
    {
        return $this->where('id_rfid', $cardId)->first();
    }

    public function updateBalance($cardId, $amount, $operation = 'add')
    {
        $balance = $this->where('id_rfid', $cardId)->first();

        if (!$balance) {
            return false;
        }

        $newBalance = ($operation === 'add') ?
            $balance['balance'] + $amount :
            $balance['balance'] - $amount;

        // Prevent negative balance
        if ($newBalance < 0) {
            return false;
        }

        return $this->update($balance['id_balance'], [
            'balance' => $newBalance,
            'last_transaction_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function addBalance($cardId, $amount)
    {
        return $this->updateBalance($cardId, $amount, 'add');
    }

    public function deductBalance($cardId, $amount)
    {
        return $this->updateBalance($cardId, $amount, 'deduct');
    }

    public function checkSufficientBalance($cardId, $amount)
    {
        $balance = $this->where('id_rfid', $cardId)->first();
        return $balance && $balance['balance'] >= $amount;
    }
}
