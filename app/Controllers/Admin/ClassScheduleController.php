<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ClassScheduleModel;
use App\Models\FitnessClassModel;
use App\Models\ClassBookingModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClassScheduleController extends BaseController
{
    protected $scheduleModel;
    protected $classModel;
    protected $bookingModel;

    public function __construct()
    {
        $this->scheduleModel = new ClassScheduleModel();
        $this->classModel = new FitnessClassModel();
        $this->bookingModel = new ClassBookingModel();
    }

    public function index()
    {
        $generalSettings = model('GeneralSettingsModel')->first();
        $data = [
            'title' => 'Jadwal Kelas Fitness',
            'schedules' => $this->scheduleModel->getAllSchedules(),
            'generalSettings' => $generalSettings,
            'ctx' => 'class-schedules'
        ];
        return view('admin/schedule/list-schedules', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Jadwal Kelas',
            'classes' => $this->classModel->getActiveClasses(),
            'content' => 'admin/schedule/create-schedule'
        ];

        return view('templates/admin_page_layout', $data);
    }

    public function store()
    {
        $rules = [
            'id_class' => 'required|integer',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'waktu_mulai' => 'required|valid_date[H:i:s]',
            'waktu_selesai' => 'required|valid_date[H:i:s]',
            'status' => 'required|in_list[scheduled,ongoing,completed,cancelled]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_class' => $this->request->getPost('id_class'),
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'instructor' => $this->request->getPost('instructor'),
            'lokasi' => $this->request->getPost('lokasi'),
            'kapasitas_terisi' => 0,
            'status' => $this->request->getPost('status')
        ];

        if ($this->scheduleModel->save($data)) {
            return redirect()->to('/admin/class-schedules')->with('success', 'Jadwal kelas berhasil ditambahkan');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan jadwal kelas');
        }
    }

    public function edit($id)
    {
        $schedule = $this->scheduleModel->getScheduleById($id);

        if (!$schedule) {
            return redirect()->to('/admin/class-schedules')->with('error', 'Jadwal kelas tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Jadwal Kelas',
            'schedule' => $schedule,
            'classes' => $this->classModel->getActiveClasses(),
            'content' => 'admin/schedule/edit-schedule'
        ];

        return view('templates/admin_page_layout', $data);
    }

    public function update($id)
    {
        $rules = [
            'id_class' => 'required|integer',
            'tanggal' => 'required|valid_date[Y-m-d]',
            'waktu_mulai' => 'required|valid_date[H:i:s]',
            'waktu_selesai' => 'required|valid_date[H:i:s]',
            'status' => 'required|in_list[scheduled,ongoing,completed,cancelled]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'id_class' => $this->request->getPost('id_class'),
            'tanggal' => $this->request->getPost('tanggal'),
            'waktu_mulai' => $this->request->getPost('waktu_mulai'),
            'waktu_selesai' => $this->request->getPost('waktu_selesai'),
            'instructor' => $this->request->getPost('instructor'),
            'lokasi' => $this->request->getPost('lokasi'),
            'status' => $this->request->getPost('status')
        ];

        if ($this->scheduleModel->update($id, $data)) {
            return redirect()->to('/admin/class-schedules')->with('success', 'Jadwal kelas berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui jadwal kelas');
        }
    }

    public function delete($id)
    {
        if ($this->scheduleModel->delete($id)) {
            return redirect()->to('/admin/class-schedules')->with('success', 'Jadwal kelas berhasil dihapus');
        } else {
            return redirect()->to('/admin/class-schedules')->with('error', 'Gagal menghapus jadwal kelas');
        }
    }

    public function viewBookings($id)
    {
        $schedule = $this->scheduleModel->getScheduleById($id);

        if (!$schedule) {
            return redirect()->to('/admin/class-schedules')->with('error', 'Jadwal kelas tidak ditemukan');
        }

        $bookings = $this->bookingModel->getBookingsBySchedule($id);
        $attendanceStats = $this->bookingModel->getAttendanceStats($id);

        $data = [
            'title' => 'Booking Kelas: ' . $schedule['nama_class'],
            'schedule' => $schedule,
            'bookings' => $bookings,
            'attendanceStats' => $attendanceStats,
            'content' => 'admin/schedule/view-bookings'
        ];

        return view('templates/admin_page_layout', $data);
    }

    public function updateStatus($id)
    {
        $schedule = $this->scheduleModel->getScheduleById($id);

        if (!$schedule) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal kelas tidak ditemukan']);
        }

        $newStatus = $this->request->getPost('status');

        if (!in_array($newStatus, ['scheduled', 'ongoing', 'completed', 'cancelled'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Status tidak valid']);
        }

        if ($this->scheduleModel->update($id, ['status' => $newStatus])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status jadwal kelas berhasil diubah',
                'new_status' => $newStatus
            ]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Gagal mengubah status jadwal kelas']);
        }
    }

    public function calendar()
    {
        $data = [
            'title' => 'Kalender Jadwal Kelas',
            'content' => 'admin/schedule/calendar'
        ];

        return view('templates/admin_page_layout', $data);
    }

    public function getCalendarData()
    {
        $start = $this->request->getGet('start');
        $end = $this->request->getGet('end');

        $schedules = $this->scheduleModel->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.deskripsi')
                                        ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                                        ->where('tanggal >=', $start)
                                        ->where('tanggal <=', $end)
                                        ->findAll();

        $events = [];
        foreach ($schedules as $schedule) {
            $events[] = [
                'id' => $schedule['id_schedule'],
                'title' => $schedule['nama_class'],
                'start' => $schedule['tanggal'] . 'T' . $schedule['waktu_mulai'],
                'end' => $schedule['tanggal'] . 'T' . $schedule['waktu_selesai'],
                'description' => $schedule['deskripsi'],
                'instructor' => $schedule['instructor'],
                'location' => $schedule['lokasi'],
                'status' => $schedule['status'],
                'capacity' => $schedule['kapasitas'],
                'filled' => $schedule['kapasitas_terisi']
            ];
        }

        return $this->response->setJSON($events);
    }
}
