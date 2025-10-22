<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClassScheduleModel;
use App\Models\ClassBookingModel;
use App\Models\MemberModel;

class MemberBookingController extends BaseController
{
    protected ClassScheduleModel $scheduleModel;
    protected ClassBookingModel $bookingModel;
    protected MemberModel $memberModel;

    public function __construct()
    {
        $this->scheduleModel = new ClassScheduleModel();
        $this->bookingModel = new ClassBookingModel();
        $this->memberModel = new MemberModel();
    }

    /**
     * Member booking dashboard
     */
    public function index()
    {
        // In a real app, you'd get member ID from session/auth
        // For now, we'll assume member ID is passed or from session
        $memberId = $this->request->getGet('member_id') ?? 1; // Default for testing

        $member = $this->memberModel->getMemberWithPackage($memberId);
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found');
        }

        $upcomingBookings = $this->bookingModel->getMemberUpcomingBookings($memberId);
        $bookingHistory = $this->bookingModel->getMemberBookingHistory($memberId);
        $bookingStats = $this->memberModel->getMemberBookingStats($memberId);

        $data = [
            'title' => 'Class Bookings',
            'member' => $member,
            'upcomingBookings' => $upcomingBookings,
            'bookingHistory' => $bookingHistory,
            'bookingStats' => $bookingStats
        ];

        return view('member/booking/dashboard', $data);
    }

    /**
     * Show available classes for booking
     */
    public function availableClasses()
    {
        $memberId = $this->request->getGet('member_id') ?? 1;
        $date = $this->request->getGet('date');

        $member = $this->memberModel->getMemberWithPackage($memberId);
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found');
        }

        if ($date) {
            $schedules = $this->scheduleModel->getSchedulesByDate($date);
        } else {
            $schedules = $this->scheduleModel->getUpcomingSchedulesForBooking(20);
        }

        // Check booking eligibility for each schedule
        foreach ($schedules as &$schedule) {
            $canBook = $this->bookingModel->canMemberBookClass($memberId, $schedule['id_schedule']);
            $schedule['can_book'] = $canBook['can_book'];
            $schedule['booking_reason'] = $canBook['reason'];
            $schedule['available_capacity'] = $this->scheduleModel->getAvailableCapacity($schedule['id_schedule']);
        }

        $data = [
            'title' => 'Available Classes',
            'member' => $member,
            'schedules' => $schedules,
            'selectedDate' => $date
        ];

        return view('member/booking/available-classes', $data);
    }

    /**
     * Book a class
     */
    public function bookClass()
    {
        $memberId = $this->request->getPost('member_id');
        $scheduleId = $this->request->getPost('schedule_id');

        if (!$memberId || !$scheduleId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member ID and Schedule ID are required'
            ]);
        }

        $result = $this->bookingModel->createBookingForMember($scheduleId, $memberId);

        return $this->response->setJSON($result);
    }

    /**
     * Cancel a booking
     */
    public function cancelBooking()
    {
        $bookingId = $this->request->getPost('booking_id');
        $memberId = $this->request->getPost('member_id');

        if (!$bookingId || !$memberId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking ID and Member ID are required'
            ]);
        }

        $result = $this->bookingModel->cancelBooking($bookingId, $memberId);

        return $this->response->setJSON($result);
    }

    /**
     * Get member's booking details
     */
    public function bookingDetails($bookingId)
    {
        $memberId = $this->request->getGet('member_id') ?? 1;

        $booking = $this->bookingModel->select('tb_class_bookings.*, tb_fitness_classes.nama_class, tb_class_schedules.tanggal, tb_class_schedules.waktu_mulai, tb_class_schedules.waktu_selesai, tb_class_schedules.instructor, tb_class_schedules.lokasi')
                    ->join('tb_class_schedules', 'tb_class_schedules.id_schedule = tb_class_bookings.id_schedule')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tb_class_bookings.id_booking', $bookingId)
                    ->where('tb_class_bookings.id_member', $memberId)
                    ->first();

        if (!$booking) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Booking not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'booking' => $booking
        ]);
    }

    /**
     * Get member's booking statistics
     */
    public function bookingStats()
    {
        $memberId = $this->request->getGet('member_id') ?? 1;

        $stats = $this->memberModel->getMemberBookingStats($memberId);

        if (!$stats) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member not found'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
