<?php

namespace App\Models;

use CodeIgniter\Model;

class DoorAccessModel extends Model
{
    protected $table = 'tb_door_access_log';

    protected $primaryKey = 'id_door_access';

    protected $allowedFields = [
        'id_member',
        'id_pegawai',
        'id_penjaga',
        'tanggal',
        'jam',
        'tipe_user',
        'status'
    ];

    protected $useTimestamps = true;

    /**
     * Cek apakah user sudah absen hari ini (untuk akses pintu)
     */
    public function hasAttendanceToday($userId, $userType)
    {
        $date = date('Y-m-d');

        switch ($userType) {
            case 'member':
                return $this->db->table('tb_presensi_member')
                    ->where('id_member', $userId)
                    ->where('tanggal', $date)
                    ->countAllResults() > 0;
            case 'pegawai':
                return $this->db->table('tb_presensi_pegawai')
                    ->where('id_pegawai', $userId)
                    ->where('tanggal', $date)
                    ->countAllResults() > 0;
            case 'penjaga':
                return $this->db->table('tb_presensi_penjaga')
                    ->where('id_penjaga', $userId)
                    ->where('tanggal', $date)
                    ->countAllResults() > 0;
            default:
                return false;
        }
    }

    /**
     * Log akses pintu
     */
    public function logDoorAccess($userId, $userType, $status = 'success')
    {
        $data = [
            'tanggal' => date('Y-m-d'),
            'jam' => date('H:i:s'),
            'tipe_user' => $userType,
            'status' => $status
        ];

        switch ($userType) {
            case 'member':
                $data['id_member'] = $userId;
                break;
            case 'pegawai':
                $data['id_pegawai'] = $userId;
                break;
            case 'penjaga':
                $data['id_penjaga'] = $userId;
                break;
        }

        return $this->insert($data);
    }

    /**
     * Get log akses pintu berdasarkan tanggal
     */
    public function getAccessLogByDate($date)
    {
        return $this->where('tanggal', $date)->findAll();
    }

    /**
     * Get log akses pintu berdasarkan user dan tanggal
     */
    public function getAccessLogByUserAndDate($userId, $userType, $date)
    {
        $field = 'id_' . $userType;
        return $this->where($field, $userId)
                    ->where('tanggal', $date)
                    ->findAll();
    }

    /**
     * Hitung total akses pintu hari ini
     */
    public function getTodayAccessCount()
    {
        $date = date('Y-m-d');
        return $this->where('tanggal', $date)->countAllResults();
    }

    /**
     * Hitung akses pintu per user hari ini
     */
    public function getTodayAccessCountByUser($userId, $userType)
    {
        $date = date('Y-m-d');
        $field = 'id_' . $userType;
        return $this->where($field, $userId)
                    ->where('tanggal', $date)
                    ->countAllResults();
    }
}
