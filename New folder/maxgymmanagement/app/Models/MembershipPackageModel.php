<?php

namespace App\Models;

use CodeIgniter\Model;

class MembershipPackageModel extends Model
{
    protected $table = 'tb_membership_packages';
    protected $primaryKey = 'id_package';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nama_package',
        'harga',
        'durasi_hari',
        'pt_sessions',
        'deskripsi',
        'status',
        'benefits',
        'unlimited_classes',
        'locker_access'
    ];

    public function getActivePackages()
    {
        return $this->where('status', 'aktif')->findAll();
    }

    public function getPackageById($id)
    {
        return $this->where($this->primaryKey, $id)->first();
    }

    public function createPackage($data)
    {
        $this->insert($data);
        return $this->insertID();
    }

    public function updatePackage($id, $data)
    {
        return $this->update($id, $data);
    }

    public function deletePackage($id)
    {
        return $this->delete($id);
    }
}
