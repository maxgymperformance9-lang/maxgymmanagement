<?php

namespace App\Models;

class WilayahModel extends BaseModel
{
    protected $builder;

    public function __construct()
    {
        parent::__construct();
        $this->builder = $this->db->table('tb_wilayah');
    }

    //input values
   public function inputValues()
   {
      return [
         'wilayah' => inputPost('wilayah'),
      ];
   }

   public function addWilayah()
   {
      $data = $this->inputValues();
      return $this->builder->insert($data);
   }

   public function editWilayah($id)
   {
      $wilayah = $this->getWilayah($id);
      if (!empty($wilayah)) {
         $data = $this->inputValues();
         return $this->builder->where('id', $wilayah->id)->update($data);
      }
      return false;
   }

    public function getDataWilayah()
    {
        return $this->builder->orderBy('id')->get()->getResult('array');
    }

    public function getWilayah($id)
    {
        return $this->builder->where('id', cleanNumber($id))->get()->getRow();
    }


    public function deleteWilayah($id)
   {
       $wilayah = $this->getWilayah($id);
       if (!empty($wilayah)) {
           return $this->builder->where('id', $wilayah->id)->delete();
       }
       return false;
   }
}
