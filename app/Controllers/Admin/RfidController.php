<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RfidCardModel;
use App\Models\RfidBalanceModel;
use App\Models\RfidTransactionModel;
use App\Models\MemberModel;

class RfidController extends BaseController
{
    protected $rfidCardModel;
    protected $rfidBalanceModel;
    protected $rfidTransactionModel;
    protected $memberModel;

    public function __construct()
    {
        $this->rfidCardModel = new RfidCardModel();
        $this->rfidBalanceModel = new RfidBalanceModel();
        $this->rfidTransactionModel = new RfidTransactionModel();
        $this->memberModel = new MemberModel();
    }

    public function index()
    {
        $rfidCards = $this->rfidCardModel->findAll();

        // Add balance to each card
        foreach ($rfidCards as &$card) {
            $balance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
            $card['balance'] = $balance ? $balance['balance'] : 0;
        }

        $data = [
            'title' => 'RFID Cards Management',
            'rfidCards' => $rfidCards,
            'members' => $this->memberModel->findAll()
        ];

        return view('admin/rfid/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Add New RFID Card',
            'members' => $this->memberModel->findAll()
        ];

        return view('admin/rfid/create', $data);
    }

    public function store()
    {
        $rules = [
            'card_uid' => 'required|is_unique[tb_rfid_cards.card_uid]',
            'id_member' => 'permit_empty|integer',
            'card_status' => 'required|in_list[active,inactive,blocked,lost]',
            'issued_date' => 'permit_empty|valid_date',
            'expiry_date' => 'permit_empty|valid_date',
            'initial_balance' => 'permit_empty|decimal'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cardData = [
            'card_uid' => $this->request->getPost('card_uid'),
            'id_member' => $this->request->getPost('id_member') ?: null,
            'card_status' => $this->request->getPost('card_status'),
            'issued_date' => $this->request->getPost('issued_date') ?: date('Y-m-d'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'notes' => $this->request->getPost('notes')
        ];

        $cardId = $this->rfidCardModel->insert($cardData);

        if ($cardId) {
            // Create initial balance if provided
            $initialBalance = $this->request->getPost('initial_balance');
            if ($initialBalance && $initialBalance > 0) {
                $this->rfidBalanceModel->insert([
                    'id_rfid' => $cardId,
                    'balance' => $initialBalance,
                    'last_transaction_date' => date('Y-m-d H:i:s')
                ]);

                // Record initial topup transaction
                $this->rfidTransactionModel->recordTransaction(
                    $cardId,
                    'topup',
                    $initialBalance,
                    'Initial balance',
                    session()->get('id_pegawai')
                );
            }

            return redirect()->to('/admin/rfid')->with('success', 'RFID card created successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to create RFID card');
    }

    public function edit($id)
    {
        $rfidCard = $this->rfidCardModel->find($id);
        if (!$rfidCard) {
            return redirect()->to('/admin/rfid')->with('error', 'RFID card not found');
        }

        $data = [
            'title' => 'Edit RFID Card',
            'rfidCard' => $rfidCard,
            'members' => $this->memberModel->findAll(),
            'balance' => $this->rfidBalanceModel->getBalanceByCardId($id)
        ];

        return view('admin/rfid/edit', $data);
    }

    public function update($id)
    {
        $rules = [
            'card_uid' => "required|is_unique[tb_rfid_cards.card_uid,id_rfid,{$id}]",
            'id_member' => 'permit_empty|integer',
            'card_status' => 'required|in_list[active,inactive,blocked,lost]',
            'issued_date' => 'permit_empty|valid_date',
            'expiry_date' => 'permit_empty|valid_date'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $cardData = [
            'card_uid' => $this->request->getPost('card_uid'),
            'id_member' => $this->request->getPost('id_member') ?: null,
            'card_status' => $this->request->getPost('card_status'),
            'issued_date' => $this->request->getPost('issued_date'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'notes' => $this->request->getPost('notes')
        ];

        if ($this->rfidCardModel->update($id, $cardData)) {
            return redirect()->to('/admin/rfid')->with('success', 'RFID card updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update RFID card');
    }

    public function delete($id)
    {
        if ($this->rfidCardModel->delete($id)) {
            // Also delete related balance and transactions
            $this->rfidBalanceModel->where('id_rfid', $id)->delete();
            $this->rfidTransactionModel->where('id_rfid', $id)->delete();

            return redirect()->to('/admin/rfid')->with('success', 'RFID card deleted successfully');
        }

        return redirect()->to('/admin/rfid')->with('error', 'Failed to delete RFID card');
    }

    public function assignMember($id)
    {
        $memberId = $this->request->getPost('id_member');

        if ($this->rfidCardModel->assignToMember($id, $memberId)) {
            return redirect()->to('/admin/rfid')->with('success', 'Member assigned to RFID card successfully');
        }

        return redirect()->to('/admin/rfid')->with('error', 'Failed to assign member');
    }

    public function unassignMember($id)
    {
        if ($this->rfidCardModel->unassignFromMember($id)) {
            return redirect()->to('/admin/rfid')->with('success', 'Member unassigned from RFID card successfully');
        }

        return redirect()->to('/admin/rfid')->with('error', 'Failed to unassign member');
    }

    public function topup($id)
    {
        $rules = [
            'amount' => 'required|decimal|greater_than[0]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $amount = $this->request->getPost('amount');
        $description = $this->request->getPost('description') ?: 'Topup';

        // Add balance
        if ($this->rfidBalanceModel->addBalance($id, $amount)) {
            // Record transaction
            $this->rfidTransactionModel->recordTransaction(
                $id,
                'topup',
                $amount,
                $description,
                session()->get('id_pegawai')
            );

            return redirect()->to('/admin/rfid')->with('success', 'Topup successful');
        }

        return redirect()->back()->with('error', 'Failed to topup balance');
    }

    public function transactions($id = null)
    {
        if ($id) {
            $data = [
                'title' => 'RFID Card Transactions',
                'transactions' => $this->rfidTransactionModel->getTransactionsByCard($id),
                'rfidCard' => $this->rfidCardModel->find($id)
            ];
        } else {
            $data = [
                'title' => 'All RFID Transactions',
                'transactions' => $this->rfidTransactionModel->getRecentTransactions(50)
            ];
        }

        return view('admin/rfid/transactions', $data);
    }

    public function changeStatus($id)
    {
        $status = $this->request->getPost('card_status');

        if ($this->rfidCardModel->changeStatus($id, $status)) {
            return redirect()->to('/admin/rfid')->with('success', 'Card status updated successfully');
        }

        return redirect()->to('/admin/rfid')->with('error', 'Failed to update card status');
    }

    // API endpoints for AJAX requests
    public function getCardInfo($uid)
    {
        $card = $this->rfidCardModel->getCardByUid($uid);

        if ($card) {
            $balance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
            $card['balance'] = $balance ? $balance['balance'] : 0;
            return $this->response->setJSON(['success' => true, 'data' => $card]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Card not found']);
    }

    public function processPayment()
    {
        $cardUid = $this->request->getPost('card_uid');
        $amount = $this->request->getPost('amount');

        $card = $this->rfidCardModel->getCardByUid($cardUid);

        if (!$card) {
            return $this->response->setJSON(['success' => false, 'message' => 'Card not found']);
        }

        if ($card['card_status'] !== 'active') {
            return $this->response->setJSON(['success' => false, 'message' => 'Card is not active']);
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
                'Payment',
                session()->get('id_pegawai')
            );

            $newBalance = $this->rfidBalanceModel->getBalanceByCardId($card['id_rfid']);
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment successful',
                'new_balance' => $newBalance['balance']
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Payment failed']);
    }
}
