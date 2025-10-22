<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjagaModel extends Model
{
   protected function initialize()
   {
      $this->allowedFields = [
         'nip',
         'nama_penjaga',
         'id_di',
         'jenis_kelamin',
         'no_hp',
         'unique_code'
      ];
   }

   protected $table = 'tb_penjaga';

   protected $primaryKey = 'id_penjaga';

   public function cekPenjaga(string $unique_code)
   {
      $this->join(
         'tb_di',
         'tb_di.id_di = tb_penjaga.id_di',
         'LEFT'
      )->join(
         'tb_wilayah',
         'tb_wilayah.id = tb_di.id_wilayah',
         'LEFT'
      );
      return $this->where(['unique_code' => $unique_code])->first();
   }

   public function cekPenjagaPartial(string $partial_code)
   {
      $this->join(
         'tb_di',
         'tb_di.id_di = tb_penjaga.id_di',
         'LEFT'
      )->join(
         'tb_wilayah',
         'tb_wilayah.id = tb_di.id_wilayah',
         'LEFT'
      );
      return $this->like('unique_code', $partial_code, 'after')->first();
   }

   public function cekPenjagaContains(string $partial_code)
   {
      $this->join(
         'tb_di',
         'tb_di.id_di = tb_penjaga.id_di',
         'LEFT'
      )->join(
         'tb_wilayah',
         'tb_wilayah.id = tb_di.id_wilayah',
         'LEFT'
      );
      return $this->like('unique_code', $partial_code)->first();
   }

   public function getPenjagaById($id)
   {
      return $this->where([$this->primaryKey => $id])->first();
   }

   public function getAllPenjagaWithDi($di = null, $wilayah = null)
   {
      $query = $this->join(
         'tb_di',
         'tb_di.id_di = tb_penjaga.id_di',
         'LEFT'
      )->join(
         'tb_wilayah',
         'tb_di.id_wilayah = tb_wilayah.id',
         'LEFT'
      );

      if (!empty($di) && !empty($wilayah)) {
         $query = $this->where(['di' => $di, 'wilayah' => $wilayah]);
      } else if (empty($di) && !empty($wilayah)) {
         $query = $this->where(['wilayah' => $wilayah]);
      } else if (!empty($di) && empty($wilayah)) {
         $query = $this->where(['di' => $di]);
      } else {
         $query = $this;
      }

      return $query->orderBy('nama_penjaga')->findAll();
   }

   public function getPenjagaByDi($id_di)
   {
      return $this->join(
         'tb_di',
         'tb_di.id_di = tb_penjaga.id_di',
         'LEFT'
      )
         ->join('tb_wilayah', 'tb_di.id_wilayah = tb_wilayah.id', 'left')
         ->where(['tb_penjaga.id_di' => $id_di])
         ->orderBy('nama_penjaga')
         ->findAll();
   }

   public function createSiswa($nip, $nama, $idDi, $jenisKelamin, $noHp)
   {
      return $this->save([
         'nip' => $nip,
         'nama_penjaga' => $nama,
         'id_di' => $idDi,
         'jenis_kelamin' => $jenisKelamin,
         'no_hp' => $noHp,
         'unique_code' => generateToken()
      ]);
   }

   public function updatePenjaga($id, $nip, $nama, $idDi, $jenisKelamin, $noHp)
   {
      return $this->save([
         $this->primaryKey => $id,
         'nip' => $nip,
         'nama_penjaga' => $nama,
         'id_di' => $idDi,
         'jenis_kelamin' => $jenisKelamin,
         'no_hp' => $noHp,
      ]);
   }

   public function getPenjagaCountByDi($diId)
   {
      $tree = array();
      $diId = cleanNumber($diId);
      if (!empty($diId)) {
         array_push($tree, $diId);
      }

      $kelasIds = $tree;
      if (countItems($kelasIds) < 1) {
         return array();
      }

      return $this->whereIn('tb_penjaga.id_di', $kelasIds, false)->countAllResults();
   }

   //generate CSV object
   public function generateCSVObject($filePath)
   {
      $array = array();
      $fields = array();
      $txtName = uniqid() . '.txt';
      $i = 0;
      $handle = fopen($filePath, 'r');
      if ($handle) {
         while (($row = fgetcsv($handle)) !== false) {
            if (empty($fields)) {
               $fields = $row;
               continue;
            }
            foreach ($row as $k => $value) {
               $array[$i][$fields[$k]] = $value;
            }
            $i++;
         }
         if (!feof($handle)) {
            return false;
         }
         fclose($handle);
         if (!empty($array)) {
            $txtFile = fopen(FCPATH . 'uploads/tmp/' . $txtName, 'w');
            fwrite($txtFile, serialize($array));
            fclose($txtFile);
            $obj = new \stdClass();
            $obj->numberOfItems = countItems($array);
            $obj->txtFileName = $txtName;
            @unlink($filePath);
            return $obj;
         }
      }
      return false;
   }

   //import csv item
   public function importCSVItem($txtFileName, $index)
   {
      $filePath = FCPATH . 'uploads/tmp/' . $txtFileName;
      $file = fopen($filePath, 'r');
      $content = fread($file, filesize($filePath));
      $array = @unserialize($content);
      if (!empty($array)) {
         $i = 1;
         foreach ($array as $item) {
            if ($i == $index) {
               $data = array();
               $data['nip'] = getCSVInputValue($item, 'nip', 'int');
               $data['nama_penjaga'] = getCSVInputValue($item, 'nama_penjaga');
               $data['id_di'] = getCSVInputValue($item, 'id_di', 'int');
               $data['jenis_kelamin'] = getCSVInputValue($item, 'jenis_kelamin');
               $data['no_hp'] = getCSVInputValue($item, 'no_hp');
               $data['unique_code'] = generateToken();

               $this->insert($data);
               return $data;
            }
            $i++;
         }
      }
   }

   public function getPenjaga($id)
   {
      return $this->where('id_penjaga', cleanNumber($id))->get()->getRow();
   }

   //delete post
   public function deletePenjaga($id)
   {
      $penjaga = $this->getPenjaga($id);
      if (!empty($penjaga)) {
         //delete penjaga
         return $this->where('id_penjaga', $penjaga->id_penjaga)->delete();
      }
      return false;
   }

   //delete multi post
   public function deleteMultiSelected($penjagaIds)
   {
      if (!empty($penjagaIds)) {
         foreach ($penjagaIds as $id) {
            $this->deletePenjaga($id);
         }
      }
   }
}
