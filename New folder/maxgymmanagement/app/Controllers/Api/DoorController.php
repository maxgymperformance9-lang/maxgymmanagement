<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DoorAccessModel;
use App\Models\MemberModel;
use App\Models\PegawaiModel;
use App\Models\PenjagaModel;

class DoorController extends ResourceController
{
    protected $doorAccessModel;
    protected $memberModel;
    protected $pegawaiModel;
    protected $penjagaModel;

    protected $esp32Ip = '192.168.1.10'; // Ganti dengan IP ESP8266 Anda
    protected $esp32Port = '80';

    public function __construct()
    {
        $this->doorAccessModel = new DoorAccessModel();
        $this->memberModel = new MemberModel();
        $this->pegawaiModel = new PegawaiModel();
        $this->penjagaModel = new PenjagaModel();
    }

    /**
     * API untuk membuka pintu setelah absensi berhasil
     * POST /api/open-door
     */
    public function openDoor()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['user_id']) || !isset($data['user_type'])) {
            return $this->fail('user_id and user_type are required', 400);
        }

        $userId = $data['user_id'];
        $userType = $data['user_type'];

        // Validasi tipe user
        if (!in_array($userType, ['member', 'pegawai', 'penjaga'])) {
            return $this->fail('Invalid user_type. Must be member, pegawai, or penjaga', 400);
        }

        // Cek apakah user sudah absen hari ini
        if (!$this->doorAccessModel->hasAttendanceToday($userId, $userType)) {
            return $this->fail('User belum absen hari ini', 403);
        }

        // Kirim command ke ESP32 untuk buka pintu
        $result = $this->sendCommandToESP32('open');

        if ($result['success']) {
            // Log akses pintu berhasil
            $this->doorAccessModel->logDoorAccess($userId, $userType, 'success');

            return $this->respond([
                'status' => 'success',
                'message' => 'Pintu berhasil dibuka',
                'user_type' => $userType,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Log akses pintu gagal
            $this->doorAccessModel->logDoorAccess($userId, $userType, 'failed');

            return $this->fail('Gagal menghubungi ESP32: ' . $result['error'], 500);
        }
    }

    /**
     * API untuk akses pintu berulang (tanpa absensi)
     * POST /api/access-door
     */
    public function accessDoor()
    {
        $data = $this->request->getJSON(true);

        if (!isset($data['unique_code'])) {
            return $this->fail('unique_code is required', 400);
        }

        $uniqueCode = $data['unique_code'];

        // Cari user berdasarkan unique_code
        $user = null;
        $userType = null;

        // Cek member
        $user = $this->memberModel->cekMember($uniqueCode);
        if ($user) {
            $userType = 'member';
            $userId = $user['id_member'];
        } else {
            // Cek pegawai
            $user = $this->pegawaiModel->cekPegawai($uniqueCode);
            if ($user) {
                $userType = 'pegawai';
                $userId = $user['id_pegawai'];
            } else {
                // Cek penjaga
                $user = $this->penjagaModel->cekPenjaga($uniqueCode);
                if ($user) {
                    $userType = 'penjaga';
                    $userId = $user['id_penjaga'];
                }
            }
        }

        if (!$user) {
            return $this->fail('User tidak ditemukan', 404);
        }

        // Cek apakah user sudah absen hari ini
        if (!$this->doorAccessModel->hasAttendanceToday($userId, $userType)) {
            return $this->fail('User belum absen hari ini. Silakan absen terlebih dahulu.', 403);
        }

        // Kirim command ke ESP32 untuk buka pintu
        $result = $this->sendCommandToESP32('open');

        if ($result['success']) {
            // Log akses pintu berhasil
            $this->doorAccessModel->logDoorAccess($userId, $userType, 'success');

            return $this->respond([
                'status' => 'success',
                'message' => 'Pintu berhasil dibuka',
                'user_type' => $userType,
                'user_name' => $user['nama_' . ($userType === 'member' ? 'member' : ($userType === 'pegawai' ? 'pegawai' : 'penjaga'))],
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Log akses pintu gagal
            $this->doorAccessModel->logDoorAccess($userId, $userType, 'failed');

            return $this->fail('Gagal menghubungi ESP32: ' . $result['error'], 500);
        }
    }

    /**
     * API untuk test koneksi ESP32
     * GET /api/test-door
     */
    public function testConnection()
    {
        $result = $this->sendCommandToESP32('ping');

        if ($result['success']) {
            return $this->respond([
                'status' => 'success',
                'message' => 'Koneksi ESP32 OK',
                'response' => $result['response']
            ]);
        } else {
            return $this->fail('Gagal menghubungi ESP32: ' . $result['error'], 500);
        }
    }

    /**
     * Kirim command ke ESP32 via HTTP
     */
    private function sendCommandToESP32($command)
    {
        $url = "http://{$this->esp32Ip}:{$this->esp32Port}/{$command}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 detik timeout
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            return [
                'success' => false,
                'error' => $error
            ];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return [
                'success' => true,
                'response' => $response
            ];
        } else {
            return [
                'success' => false,
                'error' => "HTTP {$httpCode}: {$response}"
            ];
        }
    }
}
