<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\RfidCardModel;
use App\Models\RfidBalanceModel;
use App\Models\RfidTransactionModel;

class RfidApiController extends BaseController
{
    protected $rfidCardModel;
    protected $rfidBalanceModel;
    protected $rfidTransactionModel;

    public function __construct()
    {
        $this->rfidCardModel = new RfidCardModel();
        $this->rfidBalanceModel = new RfidBalanceModel();
        $this->rfidTransactionModel = new RfidTransactionModel();
    }

    // API endpoint for ESP8266 RFID payment terminal
    public function processPayment()
    {
        $cardUid = $this->request->getPost('card_uid');
        $amount = $this->request->getPost('amount');

        if (!$cardUid || !$amount) {
            return $this->response->setJSON(['success' => false, 'message' => 'Missing parameters']);
        }

        $card = $this->rfidCardModel->getCardByUid($cardUid);

        if (!$card) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card not found']);
        }

        if ($card['card_status'] !== 'active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Card is not active']);
        }

        // Check expiry date
        if ($card['expiry_date'] && strtotime($card['expiry_date']) < time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card has expired']);
        }

        if (!$this->rfidBalanceModel->checkSufficientBalance($card['id_rfid'], $amount)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Insufficient balance']);
        }

        // Deduct balance
        if ($this->rfidBalanceModel->deductBalance($card['id_rfid'], $amount)) {
            // Record transaction
            $this->rfidTransactionModel->recordTransaction(
                $card['id_rfid'],
                'payment',
                $amount,
                'RFID Payment Terminal',
                null // No staff ID for automated payment
            );

            $newBalance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment successful',
                'new_balance' => $newBalance['balance'],
                'card_uid' => $cardUid,
                'amount' => $amount
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Payment failed']);
    }

    // Validate RFID card for door access
    public function validateCard()
    {
        $cardUid = $this->request->getGet('card_uid');

        if (!$cardUid) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card UID required']);
        }

        $card = $this->rfidCardModel->getCardByUid($cardUid);

        if (!$card) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card not found']);
        }

        if ($card['card_status'] !== 'active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Card is not active']);
        }

        // Check expiry date
        if ($card['expiry_date'] && strtotime($card['expiry_date']) < time()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card has expired']);
        }

        // Check balance for access (minimum balance required)
        $balance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
        $currentBalance = $balance ? $balance['balance'] : 0;

        // Minimum balance for door access (e.g., 5000)
        if ($currentBalance < 5000) {
            return $this->response->setJSON(['success' => false, 'message' => 'Insufficient balance for access']);
        }

        // Log door access
        $doorAccessModel = new \App\Models\DoorAccessModel();
        $doorAccessModel->logAccess($card['id_rfid'], 'granted', 'RFID scan');

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Access granted',
            'card_uid' => $cardUid,
            'balance' => $currentBalance
        ]);
    }

    // Get card balance
    public function getBalance()
    {
        $cardUid = $this->request->getGet('card_uid');

        if (!$cardUid) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card UID required']);
        }

        $card = $this->rfidCardModel->getCardByUid($cardUid);

        if (!$card) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card not found']);
        }

        $balance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
        $currentBalance = $balance ? $balance['balance'] : 0;

        return $this->response->setJSON([
            'success' => true,
            'card_uid' => $cardUid,
            'balance' => $currentBalance,
            'card_status' => $card['card_status']
        ]);
    }
}
