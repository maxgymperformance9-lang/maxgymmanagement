<?php

namespace App\Models;

use CodeIgniter\Model;

class FitnessClassModel extends Model
{
    protected $table            = 'tb_fitness_classes';
    protected $primaryKey       = 'id_class';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nama_class',
        'deskripsi',
        'durasi',
        'kapasitas',
        'harga',
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
        'nama_class' => 'required|min_length[3]|max_length[255]',
        'durasi' => 'required|integer|greater_than[0]',
        'kapasitas' => 'required|integer|greater_than[0]',
        'harga' => 'required|decimal',
        'status' => 'required|in_list[aktif,nonaktif]'
    ];
    protected $validationMessages   = [
        'nama_class' => [
            'required' => 'Nama kelas wajib diisi',
            'min_length' => 'Nama kelas minimal 3 karakter',
            'max_length' => 'Nama kelas maksimal 255 karakter'
        ],
        'durasi' => [
            'required' => 'Durasi wajib diisi',
            'integer' => 'Durasi harus berupa angka',
            'greater_than' => 'Durasi harus lebih dari 0'
        ],
        'kapasitas' => [
            'required' => 'Kapasitas wajib diisi',
            'integer' => 'Kapasitas harus berupa angka',
            'greater_than' => 'Kapasitas harus lebih dari 0'
        ],
        'harga' => [
            'required' => 'Harga wajib diisi',
            'decimal' => 'Harga harus berupa angka desimal'
        ],
        'status' => [
            'required' => 'Status wajib diisi',
            'in_list' => 'Status harus aktif atau nonaktif'
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

    public function getAllClasses()
    {
        return $this->orderBy('nama_class')->findAll();
    }

    public function getActiveClasses()
    {
        return $this->where('status', 'aktif')->orderBy('nama_class')->findAll();
    }

    public function getClassById($id)
    {
        return $this->where([$this->primaryKey => $id])->first();
    }

    public function createClass($nama, $deskripsi, $durasi, $kapasitas, $harga, $status = 'aktif')
    {
        return $this->save([
            'nama_class' => $nama,
            'deskripsi' => $deskripsi,
            'durasi' => $durasi,
            'kapasitas' => $kapasitas,
            'harga' => $harga,
            'status' => $status
        ]);
    }

    public function updateClass($id, $nama, $deskripsi, $durasi, $kapasitas, $harga, $status)
    {
        return $this->save([
            $this->primaryKey => $id,
            'nama_class' => $nama,
            'deskripsi' => $deskripsi,
            'durasi' => $durasi,
            'kapasitas' => $kapasitas,
            'harga' => $harga,
            'status' => $status
        ]);
    }
}
