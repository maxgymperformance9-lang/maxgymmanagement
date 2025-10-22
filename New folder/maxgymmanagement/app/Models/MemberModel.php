<?php

namespace App\Models;

use CodeIgniter\Model;

class MemberModel extends Model
{
   protected $allowedFields = [
      'nama_member',
      'jenis_kelamin',
      'no_member',
      'type_member',
      'no_hp',
      'email',
      'alamat',
      'tanggal_join',
      'tanggal_expired',
      'keterangan',
      'unique_code',
      'foto',
      'id_package',
      'tanggal_bergabung',
      'tanggal_kadaluarsa',
      'status_membership',
      'sisa_pt_sessions',
      'locker_number'
   ];

    protected $table = 'tb_members';

    protected $primaryKey = 'id_member';

    protected $useTimestamps = true;

    public function getAllMembers()
    {
        return $this->orderBy('nama_member')->findAll();
    }

    public function getMemberById($id)
    {
        return $this->where([$this->primaryKey => $id])->first();
    }

    public function createMember($nama, $jenisKelamin, $noMember, $type, $noHp, $email, $alamat, $tanggalJoin, $tanggalExpired, $keterangan, $foto = null)
    {
        $data = [
            'nama_member' => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'no_member' => $noMember,
            'type_member' => $type,
            'no_hp' => $noHp,
            'email' => $email,
            'alamat' => $alamat,
            'tanggal_join' => $tanggalJoin,
            'tanggal_expired' => $tanggalExpired,
            'keterangan' => $keterangan,
            'foto' => $foto,
            'unique_code' => bin2hex(random_bytes(16))
        ];
        $this->insert($data);
        return $this->insertID();
    }

    public function updateMember($id, $nama, $jenisKelamin, $noMember, $type, $noHp, $email, $alamat, $tanggalJoin, $tanggalExpired, $keterangan, $foto = null)
    {
        return $this->save([
            $this->primaryKey => $id,
            'nama_member' => $nama,
            'jenis_kelamin' => $jenisKelamin,
            'no_member' => $noMember,
            'type_member' => $type,
            'no_hp' => $noHp,
            'email' => $email,
            'alamat' => $alamat,
            'tanggal_join' => $tanggalJoin,
            'tanggal_expired' => $tanggalExpired,
            'keterangan' => $keterangan,
            'foto' => $foto
        ]);
    }

   public function cekMember(string $unique_code)
   {
      return $this->where(['unique_code' => $unique_code])->first();
   }

   public function cekMemberPartial(string $partial_code)
   {
      return $this->like('unique_code', $partial_code, 'after')->first();
   }

   public function cekMemberContains(string $partial_code)
   {
      return $this->like('unique_code', $partial_code)->first();
   }

   public function updateFoto($id, $fotoPath)
   {
      return $this->update($id, ['foto' => $fotoPath]);
   }

   /**
    * Get members with package information
    */
   public function getMembersWithPackages()
   {
      return $this->select('tb_members.*, tb_membership_packages.nama_package, tb_membership_packages.harga')
                  ->join('tb_membership_packages', 'tb_membership_packages.id_package = tb_members.id_package', 'left')
                  ->orderBy('tb_members.nama_member')
                  ->findAll();
   }

   /**
    * Get member with package details
    */
   public function getMemberWithPackage($id)
   {
      return $this->select('tb_members.*, tb_membership_packages.nama_package, tb_membership_packages.harga, tb_membership_packages.durasi_hari, tb_membership_packages.benefits, tb_membership_packages.unlimited_classes, tb_membership_packages.pt_sessions, tb_membership_packages.locker_access')
                  ->join('tb_membership_packages', 'tb_membership_packages.id_package = tb_members.id_package', 'left')
                  ->where('tb_members.id_member', $id)
                  ->first();
   }

   /**
    * Get members by package
    */
   public function getMembersByPackage($packageId)
   {
      return $this->where('id_package', $packageId)->findAll();
   }

   /**
    * Get expiring memberships (next 30 days)
    */
   public function getExpiringMemberships($days = 30)
   {
      $futureDate = date('Y-m-d', strtotime("+{$days} days"));
      return $this->where('tanggal_kadaluarsa <=', $futureDate)
                  ->where('tanggal_kadaluarsa >=', date('Y-m-d'))
                  ->where('status_membership', 'aktif')
                  ->findAll();
   }

   /**
    * Get expired memberships
    */
   public function getExpiredMemberships()
   {
      return $this->where('tanggal_kadaluarsa <', date('Y-m-d'))
                  ->where('status_membership', 'aktif')
                  ->findAll();
   }

   /**
    * Update membership status
    */
   public function updateMembershipStatus($id, $status)
   {
      return $this->update($id, ['status_membership' => $status]);
   }

   /**
    * Assign package to member
    */
   public function assignPackage($memberId, $packageId, $startDate = null)
   {
      $package = model('MembershipPackageModel')->find($packageId);

      if (!$package) {
         return false;
      }

      $startDate = $startDate ?: date('Y-m-d');
      $endDate = date('Y-m-d', strtotime($startDate . ' + ' . $package['durasi_hari'] . ' days'));

      $data = [
         'id_package' => $packageId,
         'tanggal_bergabung' => $startDate,
         'tanggal_kadaluarsa' => $endDate,
         'status_membership' => 'aktif',
         'sisa_pt_sessions' => $package['pt_sessions'] ?? 0
      ];

      return $this->update($memberId, $data);
   }

   /**
    * Renew membership
    */
   public function renewMembership($memberId)
   {
      $member = $this->find($memberId);

      if (!$member || !$member['id_package']) {
         return false;
      }

      $package = model('MembershipPackageModel')->find($member['id_package']);

      if (!$package) {
         return false;
      }

      $newEndDate = date('Y-m-d', strtotime($member['tanggal_kadaluarsa'] . ' + ' . $package['durasi_hari'] . ' days'));

      $data = [
         'tanggal_kadaluarsa' => $newEndDate,
         'status_membership' => 'aktif',
         'sisa_pt_sessions' => ($member['sisa_pt_sessions'] ?? 0) + ($package['pt_sessions'] ?? 0)
      ];

      return $this->update($memberId, $data);
   }

   /**
    * Use PT session
    */
   public function usePTSession($memberId)
   {
      $member = $this->find($memberId);

      if (!$member || ($member['sisa_pt_sessions'] ?? 0) <= 0) {
         return false;
      }

      return $this->update($memberId, [
         'sisa_pt_sessions' => $member['sisa_pt_sessions'] - 1
      ]);
   }

   /**
    * Get member's class booking statistics
    */
   public function getMemberBookingStats($memberId)
   {
      $member = $this->find($memberId);
      if (!$member) return null;

      $bookingModel = model('ClassBookingModel');

      $stats = [
         'total_bookings' => 0,
         'upcoming_bookings' => 0,
         'completed_sessions' => 0,
         'cancelled_bookings' => 0,
         'remaining_sessions' => $member['sisa_pt_sessions'] ?? 0,
         'used_sessions_this_month' => $bookingModel->getUsedSessionsThisMonth($memberId)
      ];

      // Get booking counts
      $upcoming = $bookingModel->getMemberUpcomingBookings($memberId);
      $history = $bookingModel->getMemberBookingHistory($memberId);

      $stats['upcoming_bookings'] = count($upcoming);
      $stats['total_bookings'] = count($upcoming) + count($history);

      foreach ($history as $booking) {
         if ($booking['status'] === 'attended') {
            $stats['completed_sessions']++;
         } elseif ($booking['status'] === 'cancelled') {
            $stats['cancelled_bookings']++;
         }
      }

      return $stats;
   }

   /**
    * Check if member can access locker
    */
   public function canAccessLocker($memberId)
   {
      $member = $this->find($memberId);
      if (!$member || $member['status_membership'] !== 'aktif') {
         return false;
      }

      if ($member['id_package']) {
         $package = model('MembershipPackageModel')->find($member['id_package']);
         return $package && $package['locker_access'];
      }

      return false;
   }

   /**
    * Assign locker to member
    */
   public function assignLocker($memberId, $lockerNumber)
   {
      if (!$this->canAccessLocker($memberId)) {
         return ['success' => false, 'message' => 'Member does not have locker access'];
      }

      // Check if locker is already assigned
      $existing = $this->where('locker_number', $lockerNumber)
                      ->where('id_member !=', $memberId)
                      ->first();

      if ($existing) {
         return ['success' => false, 'message' => 'Locker already assigned to another member'];
      }

      if ($this->update($memberId, ['locker_number' => $lockerNumber])) {
         return ['success' => true, 'message' => 'Locker assigned successfully'];
      }

      return ['success' => false, 'message' => 'Failed to assign locker'];
   }
}

