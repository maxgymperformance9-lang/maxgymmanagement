<?php

namespace App\Models;

use App\Models\PresensiInterface;
use CodeIgniter\I18n\Time;
use CodeIgniter\Model;
use App\Libraries\enums\kehadiran;

class PresensiPenjagaModel extends Model implements PresensiInterface
{
   protected $primaryKey = 'id_presensi';

   protected $allowedFields = [
      'id_penjaga',
      'id_di',
      'tanggal',
      'jam_masuk',
      'jam_keluar',
      'id_kehadiran',
      'keterangan'
   ];

   protected $table = 'tb_presensi_penjaga';

   public function cekAbsen(string|int $id, string|Time $date)
   {
      $result = $this->where(['id_penjaga' => $id, 'tanggal' => $date])->first();

      if (empty($result)) return false;

      return $result[$this->primaryKey];
   }

   public function absenMasuk(string $id,  $date, $time, $idDi = '')
   {
      $this->save([
         'id_penjaga' => $id,
         'id_di' => $idDi,
         'tanggal' => $date,
         'jam_masuk' => $time,
         // 'jam_keluar' => '',
         'id_kehadiran' => kehadiran::Hadir->value,
         'keterangan' => ''
      ]);
   }

   public function absenKeluar(string $id, $time)
   {
      $this->update($id, [
         'jam_keluar' => $time,
         'keterangan' => ''
      ]);
   }

   public function getPresensiByIdPenjagaTanggal($idPenjaga, $date)
   {
      return $this->where(['id_penjaga' => $idPenjaga, 'tanggal' => $date])->first();
   }

   public function getPresensiById(string $idPresensi)
   {
      return $this->where([$this->primaryKey => $idPresensi])->first();
   }

   public function getPresensiByDiTanggal($idDi, $tanggal)
   {
      return $this->setTable('tb_penjaga')
         ->select('*')
         ->join(
            "(SELECT id_presensi, id_penjaga AS id_penjaga_presensi, tanggal, jam_masuk, jam_keluar, id_kehadiran, keterangan FROM tb_presensi_penjaga)tb_presensi_penjaga",
            "{$this->table}.id_penjaga = tb_presensi_penjaga.id_penjaga_presensi AND tb_presensi_penjaga.tanggal = '$tanggal'",
            'left'
         )
         ->join(
            'tb_kehadiran',
            'tb_presensi_penjaga.id_kehadiran = tb_kehadiran.id_kehadiran',
            'left'
         )
         ->where("{$this->table}.id_di = $idDi")
         ->orderBy("nama_penjaga")
         ->findAll();
   }

   public function getPresensiByKehadiran(string $idKehadiran, $tanggal)
   {
      $this->join(
         'tb_penjaga',
         "tb_presensi_penjaga.id_penjaga = tb_penjaga.id_penjaga AND tb_presensi_penjaga.tanggal = '$tanggal'",
         'right'
      );

      if ($idKehadiran == '4') {
         $result = $this->findAll();

         $filteredResult = [];

         foreach ($result as $value) {
            if ($value['id_kehadiran'] != ('1' || '2' || '3')) {
               array_push($filteredResult, $value);
            }
         }

         return $filteredResult;
      } else {
         $this->where(['tb_presensi_penjaga.id_kehadiran' => $idKehadiran]);
         return $this->findAll();
      }
   }

   public function updatePresensi(
      $idPresensi,
      $idPenjaga,
      $idDi,
      $tanggal,
      $idKehadiran,
      $jamMasuk,
      $jamKeluar,
      $keterangan
   ) {
      $presensi = $this->getPresensiByIdPenjagaTanggal($idPenjaga, $tanggal);

      $data = [
         'id_penjaga' => $idPenjaga,
         'id_di' => $idDi,
         'tanggal' => $tanggal,
         'id_kehadiran' => $idKehadiran,
         'keterangan' => $keterangan ?? $presensi['keterangan'] ?? ''
      ];

      if ($idPresensi != null) {
         $data[$this->primaryKey] = $idPresensi;
      }

      if ($jamMasuk != null) {
         $data['jam_masuk'] = $jamMasuk;
      }

      if ($jamKeluar != null) {
         $data['jam_keluar'] = $jamKeluar;
      }

      return $this->save($data);
   }

   public function deletePresensi($idPresensi)
   {
      return $this->delete($idPresensi);
   }
}
