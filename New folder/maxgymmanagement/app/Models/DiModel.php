<?php

namespace App\Models;

use CodeIgniter\Model;

class DiModel extends BaseModel
{
   protected $builder;

   public function __construct()
   {
      parent::__construct();
      $this->builder = $this->db->table('tb_di');
   }

   //input values
   public function inputValues()
   {
      return [
         'di' => inputPost('di'),
         'id_wilayah' => inputPost('id_wilayah'),
      ];
   }

   public function addDi()
   {
      $data = $this->inputValues();
      return $this->builder->insert($data);
   }

   public function editDi($id)
   {
      $di = $this->getDi($id);
      if (!empty($di)) {
         $data = $this->inputValues();
         return $this->builder->where('id_di', $di->id_di)->update($data);
      }
      return false;
   }

   public function getDataDi()
   {
      return $this->builder->join('tb_wilayah', 'tb_di.id_wilayah = tb_wilayah.id')->orderBy('tb_di.id_di')->get()->getResult('array');
   }

   public function getDi($id)
   {
      return $this->builder->join('tb_wilayah', 'tb_di.id_wilayah = tb_wilayah.id')->where('id_di', cleanNumber($id))->get()->getRow();
   }

   public  function getCategoryTree($categoryId, $categories)
   {
      $tree = array();
      $categoryId = cleanNumber($categoryId);
      if (!empty($categoryId)) {
         array_push($tree, $categoryId);
      }
      return $tree;
   }

   public function getDiCountByWilayah($wilayahId)
   {
      $tree = array();
      $wilayahId = cleanNumber($wilayahId);
      if (!empty($wilayahId)) {
         array_push($tree, $wilayahId);
      }

      $wilayahIds = $tree;
      if (countItems($wilayahIds) < 1) {
         return array();
      }

      return $this->builder->whereIn('tb_di.id_wilayah', $wilayahIds, false)->countAllResults();
   }

   public function deleteDi($id)
   {
      $di = $this->getDi($id);
      if (!empty($di)) {
         return $this->builder->where('id_di', $di->id_di)->delete();
      }
      return false;
   }

   public function getAllDi()
   {
      return $this->join('tb_wilayah', 'tb_di.id_wilayah = tb_wilayah.id', 'left')->findAll();
   }
}
