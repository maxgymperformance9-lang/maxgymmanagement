<?php

namespace App\Models;

use CodeIgniter\Model;

class RfidCardModel extends Model
{
    protected $table = 'tb_rfid_cards';
    protected $primaryKey = 'id_rfid';
    protected $allowedFields = [
        'card_uid',
        'id_member',
        'card_status',
        'issued_date',
        'expiry_date',
        'notes'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'card_uid' => 'required|is_unique[tb_rfid_cards.card_uid,id_rfid,{id_rfid}]',
        'id_member' => 'permit_empty|integer',
        'card_status' => 'required|in_list[active,inactive,blocked,lost]',
    ];

    protected $validationMessages = [
        'card_uid' => [
            'required' => 'Card UID is required',
            'is_unique' => 'This card UID already exists'
        ],
        'card_status' => [
            'required' => 'Card status is required',
            'in_list' => 'Invalid card status'
        ]
    ];

    // Relationships
    public function member()
    {
        return $this->belongsTo('App\Models\MemberModel', 'id_member', 'id_member');
    }

    public function balance()
    {
        return $this->hasOne('App\Models\RfidBalanceModel', 'id_rfid', 'id_rfid');
    }

    public function transactions()
    {
        return $this->hasMany('App\Models\RfidTransactionModel', 'id_rfid', 'id_rfid');
    }

    // Custom methods
    public function getActiveCards()
    {
        return $this->where('card_status', 'active')->findAll();
    }

    public function getCardByUid($uid)
    {
        return $this->where('card_uid', $uid)->first();
    }

    public function assignToMember($cardId, $memberId)
    {
        return $this->update($cardId, ['id_member' => $memberId]);
    }

    public function unassignFromMember($cardId)
    {
        return $this->update($cardId, ['id_member' => null]);
    }

    public function changeStatus($cardId, $status)
    {
        return $this->update($cardId, ['card_status' => $status]);
    }
}
