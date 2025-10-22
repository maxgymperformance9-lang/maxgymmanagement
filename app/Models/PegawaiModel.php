<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{
   protected $allowedFields = [
      'nip',
      'nama_pegawai',
      'jenis_kelamin',
      'alamat',
      'no_hp',
      'unique_code',
      'foto'
   ];

   protected $table = 'tb_pegawai';

   protected $primaryKey = 'id_pegawai';

   public function cekPegawai(string $unique_code)
   {
      return $this->where(['unique_code' => $unique_code])->first();
   }

   public function cekPegawaiPartial(string $partial_code)
   {
      return $this->like('unique_code', $partial_code, 'after')->first();
   }

   public function cekPegawaiContains(string $partial_code)
   {
      return $this->like('unique_code', $partial_code)->first();
   }

   public function getAllPegawai()
   {
      return $this->orderBy('nama_pegawai')->findAll();
   }

   public function getPegawaiById($id)
   {
      return $this->where([$this->primaryKey => $id])->first();
   }

   public function createPegawai($nip, $nama, $jenisKelamin, $alamat, $noHp, $foto = null)
   {
      return $this->save([
         'nip' => $nip,
         'nama_pegawai' => $nama,
         'jenis_kelamin' => $jenisKelamin,
         'alamat' => $alamat,
         'no_hp' => $noHp,
         'foto' => $foto,
         'unique_code' => sha1($nama . md5($nip . $nama . $noHp)) . substr(sha1($nip . rand(0, 100)), 0, 24)
      ]);
   }

   public function updatePegawai($id, $nip, $nama, $jenisKelamin, $alamat, $noHp, $foto = null)
   {
      return $this->save([
         $this->primaryKey => $id,
         'nip' => $nip,
         'nama_pegawai' => $nama,
         'jenis_kelamin' => $jenisKelamin,
         'alamat' => $alamat,
         'no_hp' => $noHp,
         'foto' => $foto,
      ]);
   }

   public function updateFoto($id, $fotoPath)
   {
      return $this->update($id, ['foto' => $fotoPath]);
   }
}
