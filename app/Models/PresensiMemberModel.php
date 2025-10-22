<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiMemberModel extends Model
{
    protected $table = 'tb_presensi_member';

    protected $primaryKey = 'id_presensi_member';

    protected $allowedFields = [
        'id_member',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'id_kehadiran'
    ];

    protected $useTimestamps = true;

    public function cekAbsen($idMember, $tanggal)
    {
        return $this->where(['id_member' => $idMember, 'tanggal' => $tanggal])->first();
    }

    public function absenMasuk($idMember, $tanggal, $jamMasuk)
    {
        return $this->insert([
            'id_member' => $idMember,
            'tanggal' => $tanggal,
            'jam_masuk' => $jamMasuk,
            'id_kehadiran' => 1 // hadir
        ]);
    }

    public function absenKeluar($idPresensi, $jamKeluar)
    {
        return $this->update($idPresensi, ['jam_keluar' => $jamKeluar]);
    }

    public function getPresensiById($id)
    {
        return $this->find($id);
    }

    public function getPresensiByIdMemberTanggal($idMember, $tanggal)
    {
        return $this->where(['id_member' => $idMember, 'tanggal' => $tanggal])->first();
    }

    public function getPresensiByKehadiran($idKehadiran, $tanggal)
    {
        return $this->where(['id_kehadiran' => $idKehadiran, 'tanggal' => $tanggal])->findAll();
    }

    public function getPresensiByTanggal($tanggal)
    {
        return $this->setTable('tb_members')
            ->select('*')
            ->join(
                "(SELECT id_presensi_member, id_member AS id_member_presensi, tanggal, jam_masuk, jam_keluar, id_kehadiran FROM tb_presensi_member) tb_presensi_member",
                "tb_members.id_member = tb_presensi_member.id_member_presensi AND tb_presensi_member.tanggal = '$tanggal'",
                'inner'
            )
            ->join(
                'tb_kehadiran',
                'tb_presensi_member.id_kehadiran = tb_kehadiran.id_kehadiran',
                'left'
            )
            ->orderBy("nama_member")
            ->findAll();
    }

    public function getMonthlyAttendanceCount($idMember, $yearMonth)
    {
        return $this->where('id_member', $idMember)
            ->where('tanggal >=', $yearMonth . '-01')
            ->where('tanggal <=', $yearMonth . '-31')
            ->countAllResults();
    }

    public function getPTMembersExceedingLimit($yearMonth)
    {
        $db = \Config\Database::connect();
        return $db->query("
            SELECT m.nama_member, m.id_member, COUNT(p.id_presensi_member) as attendance_count,
                   MAX(p.tanggal) as last_attendance_date,
                   12 - COUNT(p.id_presensi_member) as remaining_sessions
            FROM tb_members m
            LEFT JOIN tb_presensi_member p ON m.id_member = p.id_member
                AND p.tanggal >= '{$yearMonth}-01'
                AND p.tanggal <= '{$yearMonth}-31'
            WHERE m.type_member = 'member_pt'
            GROUP BY m.id_member, m.nama_member
            HAVING COUNT(p.id_presensi_member) >= 12
        ")->getResultArray();
    }

    public function deletePresensi($idPresensi)
    {
        return $this->delete($idPresensi);
    }
}
