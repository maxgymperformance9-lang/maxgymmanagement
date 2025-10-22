<?php

namespace App\Models;

use CodeIgniter\Model;

class ClassScheduleModel extends Model
{
    protected $table            = 'tb_class_schedules';
    protected $primaryKey       = 'id_schedule';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_class',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'instructor',
        'lokasi',
        'kapasitas_terisi',
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
        'id_class' => 'required|integer',
        'tanggal' => 'required|valid_date[Y-m-d]',
        'waktu_mulai' => 'required|valid_date[H:i:s]',
        'waktu_selesai' => 'required|valid_date[H:i:s]',
        'status' => 'required|in_list[scheduled,ongoing,completed,cancelled]'
    ];
    protected $validationMessages   = [
        'id_class' => [
            'required' => 'ID Kelas wajib diisi',
            'integer' => 'ID Kelas harus berupa angka'
        ],
        'tanggal' => [
            'required' => 'Tanggal wajib diisi',
            'valid_date' => 'Format tanggal tidak valid'
        ],
        'waktu_mulai' => [
            'required' => 'Waktu mulai wajib diisi',
            'valid_date' => 'Format waktu mulai tidak valid'
        ],
        'waktu_selesai' => [
            'required' => 'Waktu selesai wajib diisi',
            'valid_date' => 'Format waktu selesai tidak valid'
        ],
        'status' => [
            'required' => 'Status wajib diisi',
            'in_list' => 'Status harus scheduled, ongoing, completed, atau cancelled'
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

    public function getAllSchedules()
    {
        return $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->orderBy('tanggal', 'DESC')
                    ->orderBy('waktu_mulai', 'ASC')
                    ->findAll();
    }

    public function getSchedulesByDate($date)
    {
        return $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tanggal', $date)
                    ->orderBy('waktu_mulai', 'ASC')
                    ->findAll();
    }

    public function getScheduleById($id)
    {
        return $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas, tb_fitness_classes.deskripsi')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where([$this->primaryKey => $id])
                    ->first();
    }

    public function createSchedule($idClass, $tanggal, $waktuMulai, $waktuSelesai, $instructor = null, $lokasi = null, $status = 'scheduled')
    {
        return $this->save([
            'id_class' => $idClass,
            'tanggal' => $tanggal,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'instructor' => $instructor,
            'lokasi' => $lokasi,
            'kapasitas_terisi' => 0,
            'status' => $status
        ]);
    }

    public function updateSchedule($id, $idClass, $tanggal, $waktuMulai, $waktuSelesai, $instructor, $lokasi, $status)
    {
        return $this->save([
            $this->primaryKey => $id,
            'id_class' => $idClass,
            'tanggal' => $tanggal,
            'waktu_mulai' => $waktuMulai,
            'waktu_selesai' => $waktuSelesai,
            'instructor' => $instructor,
            'lokasi' => $lokasi,
            'status' => $status
        ]);
    }

    public function getAvailableCapacity($idSchedule)
    {
        $schedule = $this->getScheduleById($idSchedule);
        if (!$schedule) return 0;

        return $schedule['kapasitas'] - $schedule['kapasitas_terisi'];
    }

    public function incrementCapacity($idSchedule)
    {
        $schedule = $this->where($this->primaryKey, $idSchedule)->first();
        if ($schedule) {
            $newCapacity = $schedule['kapasitas_terisi'] + 1;
            return $this->update($idSchedule, ['kapasitas_terisi' => $newCapacity]);
        }
        return false;
    }

    public function decrementCapacity($idSchedule)
    {
        $schedule = $this->where($this->primaryKey, $idSchedule)->first();
        if ($schedule && $schedule['kapasitas_terisi'] > 0) {
            $newCapacity = $schedule['kapasitas_terisi'] - 1;
            return $this->update($idSchedule, ['kapasitas_terisi' => $newCapacity]);
        }
        return false;
    }

    /**
     * Get upcoming schedules for member booking
     */
    public function getUpcomingSchedulesForBooking($limit = 50)
    {
        $currentDateTime = date('Y-m-d H:i:s');

        return $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas, tb_fitness_classes.deskripsi, tb_fitness_classes.kategori')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('CONCAT(tanggal, " ", waktu_mulai) >', $currentDateTime)
                    ->where('status', 'scheduled')
                    ->orderBy('tanggal', 'ASC')
                    ->orderBy('waktu_mulai', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Get schedules by date range for member view
     */
    public function getSchedulesByDateRange($startDate, $endDate)
    {
        return $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas, tb_fitness_classes.deskripsi, tb_fitness_classes.kategori')
                    ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class')
                    ->where('tanggal >=', $startDate)
                    ->where('tanggal <=', $endDate)
                    ->where('status', 'scheduled')
                    ->orderBy('tanggal', 'ASC')
                    ->orderBy('waktu_mulai', 'ASC')
                    ->findAll();
    }

    /**
     * Check if schedule has available capacity
     */
    public function hasAvailableCapacity($idSchedule)
    {
        $available = $this->getAvailableCapacity($idSchedule);
        return $available > 0;
    }

    /**
     * Get schedule with booking count
     */
    public function getScheduleWithBookingCount($idSchedule)
    {
        $schedule = $this->getScheduleById($idSchedule);
        if (!$schedule) return null;

        $bookingModel = model('ClassBookingModel');
        $bookings = $bookingModel->where('id_schedule', $idSchedule)->findAll();

        $schedule['booking_count'] = count($bookings);
        $schedule['available_capacity'] = $schedule['kapasitas'] - $schedule['kapasitas_terisi'];

        return $schedule;
    }

    /**
     * Get schedules with their booking statistics
     */
    public function getSchedulesWithStats($date = null)
    {
        $query = $this->select('tb_class_schedules.*, tb_fitness_classes.nama_class, tb_fitness_classes.kapasitas, tb_fitness_classes.deskripsi')
                     ->join('tb_fitness_classes', 'tb_fitness_classes.id_class = tb_class_schedules.id_class');

        if ($date) {
            $query->where('tanggal', $date);
        }

        $schedules = $query->orderBy('tanggal', 'DESC')
                          ->orderBy('waktu_mulai', 'ASC')
                          ->findAll();

        $bookingModel = model('ClassBookingModel');

        foreach ($schedules as &$schedule) {
            $stats = $bookingModel->getAttendanceStats($schedule['id_schedule']);
            $schedule['stats'] = $stats;
            $schedule['available_capacity'] = $schedule['kapasitas'] - $schedule['kapasitas_terisi'];
        }

        return $schedules;
    }
}
