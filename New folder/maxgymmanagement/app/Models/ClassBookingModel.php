<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassBookingModel extends Model
{
    protected $table            = 'tb_class_bookings';
    protected $primaryKey       = 'id_booking';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_schedule',
        'id_member',
        'tanggal_booking',
        'status'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'id_schedule' => 'required|integer',
        'id_member' => 'required|integer',
        'tanggal_booking' => 'required|valid_date[Y-m-d H:i:s]',
        'status' => 'required|in_list[booked,attended,cancelled,no_show]'
    ];
    protected $validationMessages   = [
        'id_schedule' => [
            'required' => 'ID Jadwal wajib diisi',
            'integer' => 'ID Jadwal harus berupa angka'
        ],
        'id_member' => [
            'required' => 'ID Member wajib diisi',
            'integer' => 'ID Member harus berupa angka'
        ],
        'tanggal_booking' => [
            'required' => 'Tanggal booking wajib diisi',
            'valid_date' => 'Format tanggal booking tidak valid'
        ],
        'status' => [
            'required' => 'Status wajib diisi',
            'in_list' => 'Status harus booked, attended, cancelled, atau no_show'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAllBookings()
    {
        return $this->select('tb_class_bookings.*, tb_members.nama_member, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai')
                    ->join('tb_members', 'tb_members.id_member = tb_class_bookings.id_member')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->orderBy('tanggal_booking', 'DESC')
                    ->findAll();
    }

    public function getBookingsByMember($idMember)
    {
        return $this->select('tb_class_bookings.*, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai, tb_class_schedules.instructor, tb_class_schedules.lokasi')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tb_class_bookings.id_member', $idMember)
                    ->orderBy('tb_class_schedules.tanggal', 'DESC')
                    ->orderBy('tb_class_schedules.waktu_mulai', 'ASC')
                    ->findAll();
    }

    public function getBookingsBySchedule($idSchedule)
    {
        return $this->select('tb_class_bookings.*, tb_members.nama_member, tb_members.no_member')
                    ->join('tb_members', 'tb_members.id_member = tb_class_bookings.id_member')
                    ->where('id_schedule', $idSchedule)
                    ->orderBy('nama_member', 'ASC')
                    ->findAll();
    }

    public function getBookingById($id)
    {
        return $this->select('tb_class_bookings.*, tb_members.nama_member, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai')
                    ->join('tb_members', 'tb_members.id_member = tb_class_bookings.id_member')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where([$this->primaryKey => $id])
                    ->first();
    }

    public function createBooking($idSchedule, $idMember, $tanggalBooking = null, $status = 'booked')
    {
        if (!$tanggalBooking) {
            $tanggalBooking = date('Y-m-d H:i:s');
        }

        return $this->save([
            'id_schedule' => $idSchedule,
            'id_member' => $idMember,
            'tanggal_booking' => $tanggalBooking,
            'status' => $status
        ]);
    }

    public function updateBookingStatus($id, $status)
    {
        return $this->update($id, ['status' => $status]);
    }

    public function checkBookingExists($idSchedule, $idMember)
    {
        return $this->where('id_schedule', $idSchedule)
                    ->where('id_member', $idMember)
                    ->where('status !=', 'cancelled')
                    ->first();
    }

    public function getAttendanceStats($idSchedule)
    {
        $bookings = $this->where('id_schedule', $idSchedule)->findAll();

        $stats = [
            'total' => count($bookings),
            'attended' => 0,
            'cancelled' => 0,
            'no_show' => 0,
            'booked' => 0
        ];

        foreach ($bookings as $booking) {
            $stats[$booking['status']]++;
        }

        return $stats;
    }

    /**
     * Check if member can book a class based on their membership
     */
    public function canMemberBookClass($memberId, $scheduleId)
    {
        $member = model('MemberModel')->find($memberId);
        $schedule = model('ClassScheduleModel')->getScheduleById($scheduleId);

        if (!$member || !$schedule) {
            return ['can_book' => false, 'reason' => 'Member or schedule not found'];
        }

        // Check if member has active membership
        if ($member['status_membership'] !== 'aktif') {
            return ['can_book' => false, 'reason' => 'Membership is not active'];
        }

        // Check if member already booked this schedule
        $existingBooking = $this->checkBookingExists($scheduleId, $memberId);
        if ($existingBooking) {
            return ['can_book' => false, 'reason' => 'Already booked for this class'];
        }

        // Check capacity
        $availableCapacity = model('ClassScheduleModel')->getAvailableCapacity($scheduleId);
        if ($availableCapacity <= 0) {
            return ['can_book' => false, 'reason' => 'Class is full'];
        }

        // Check membership package features
        if ($member['id_package']) {
            $package = model('MembershipPackageModel')->find($member['id_package']);

            // If unlimited classes, allow booking
            if ($package && $package['unlimited_classes']) {
                return ['can_book' => true, 'reason' => 'Unlimited classes package'];
            }

            // For session-based packages, check if member has sessions left
            if ($package && $package['pt_sessions'] > 0) {
                $usedSessions = $this->getUsedSessionsThisMonth($memberId);
                $remainingSessions = ($member['sisa_pt_sessions'] ?? 0) - $usedSessions;

                if ($remainingSessions > 0) {
                    return ['can_book' => true, 'reason' => 'Has remaining sessions'];
                } else {
                    return ['can_book' => false, 'reason' => 'No remaining sessions'];
                }
            }
        }

        return ['can_book' => false, 'reason' => 'No valid membership package'];
    }

    /**
     * Get number of sessions used by member this month
     */
    public function getUsedSessionsThisMonth($memberId)
    {
        $startOfMonth = date('Y-m-01');
        $endOfMonth = date('Y-m-t');

        return $this->select('COUNT(*) as used_sessions')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->where('tb_class_bookings.id_member', $memberId)
                    ->where('tb_class_bookings.status !=', 'cancelled')
                    ->where('DATE(tb_class_schedules.tanggal) >=', $startOfMonth)
                    ->where('DATE(tb_class_schedules.tanggal) <=', $endOfMonth)
                    ->first()['used_sessions'] ?? 0;
    }

    /**
     * Create a booking for a member
     */
    public function createBookingForMember($scheduleId, $memberId)
    {
        $canBook = $this->canMemberBookClass($memberId, $scheduleId);

        if (!$canBook['can_book']) {
            return ['success' => false, 'message' => $canBook['reason']];
        }

        $bookingData = [
            'id_schedule' => $scheduleId,
            'id_member' => $memberId,
            'tanggal_booking' => date('Y-m-d H:i:s'),
            'status' => 'booked'
        ];

        if ($this->insert($bookingData)) {
            // Update schedule capacity
            model('ClassScheduleModel')->incrementCapacity($scheduleId);

            return ['success' => true, 'message' => 'Booking created successfully'];
        }

        return ['success' => false, 'message' => 'Failed to create booking'];
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking($bookingId, $memberId)
    {
        $booking = $this->find($bookingId);

        if (!$booking || $booking['id_member'] != $memberId) {
            return ['success' => false, 'message' => 'Booking not found or access denied'];
        }

        if ($booking['status'] === 'cancelled') {
            return ['success' => false, 'message' => 'Booking already cancelled'];
        }

        // Check if cancellation is allowed (e.g., not too close to class time)
        $schedule = model('ClassScheduleModel')->getScheduleById($booking['id_schedule']);
        $classDateTime = $schedule['tanggal'] . ' ' . $schedule['waktu_mulai'];
        $hoursUntilClass = (strtotime($classDateTime) - time()) / 3600;

        if ($hoursUntilClass < 2) { // Can't cancel within 2 hours of class
            return ['success' => false, 'message' => 'Cannot cancel booking within 2 hours of class time'];
        }

        if ($this->update($bookingId, ['status' => 'cancelled'])) {
            // Update schedule capacity
            model('ClassScheduleModel')->decrementCapacity($booking['id_schedule']);

            return ['success' => true, 'message' => 'Booking cancelled successfully'];
        }

        return ['success' => false, 'message' => 'Failed to cancel booking'];
    }

    /**
     * Get member's upcoming bookings
     */
    public function getMemberUpcomingBookings($memberId)
    {
        return $this->select('tb_class_bookings.*, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai, tb_class_schedules.instructor, tb_class_schedules.lokasi')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tb_class_bookings.id_member', $memberId)
                    ->where('tb_class_bookings.status', 'booked')
                    ->where('CONCAT(tb_class_schedules.tanggal, " ", tb_class_schedules.waktu_mulai) >=', date('Y-m-d H:i:s'))
                    ->orderBy('tb_class_schedules.tanggal', 'ASC')
                    ->orderBy('tb_class_schedules.waktu_mulai', 'ASC')
                    ->findAll();
    }

    /**
     * Get member's booking history
     */
    public function getMemberBookingHistory($memberId)
    {
        return $this->select('tb_class_bookings.*, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai, tb_class_schedules.instructor, tb_class_schedules.lokasi')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tb_class_bookings.id_member', $memberId)
                    ->where('CONCAT(tb_class_schedules.tanggal, " ", tb_class_schedules.waktu_mulai) <', date('Y-m-d H:i:s'))
                    ->orderBy('tb_class_schedules.tanggal', 'DESC')
                    ->orderBy('tb_class_schedules.waktu_mulai', 'DESC')
                    ->findAll();
    }
}
