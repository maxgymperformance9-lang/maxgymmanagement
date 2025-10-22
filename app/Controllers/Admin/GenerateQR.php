<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

use App\Models\PegawaiModel;
use App\Models\DiModel;
use App\Models\PenjagaModel;
use App\Models\MemberModel;

class GenerateQR extends BaseController
{
   protected PenjagaModel $PenjagaModel;
   protected DiModel $DiModel;

   protected PegawaiModel $pegawaiModel;
   protected MemberModel $memberModel;

   public function __construct()
   {
      $this->PenjagaModel = new PenjagaModel();
      $this->DiModel = new DiModel();

      $this->pegawaiModel = new PegawaiModel();
      $this->memberModel = new MemberModel();
   }

   public function index()
   {
      $penjaga = $this->PenjagaModel->getAllPenjagaWithDi();
      $di = $this->DiModel->getDataDi();
      $pegawai = $this->pegawaiModel->getAllPegawai();
      $member = $this->memberModel->getAllMembers();

      $data = [
         'title' => 'Generate QR Code',
         'ctx' => 'qr',
         'penjaga' => $penjaga,
         'di' => $di,
         'pegawai' => $pegawai,
         'member' => $member
      ];

      return view('admin/generate-qr/generate-qr', $data);
   }

   public function getPenjagaByDi()
   {
      $idDi = $this->request->getVar('idDi');

      $penjaga = $this->PenjagaModel->getPenjagaByDi($idDi);

      return $this->response->setJSON($penjaga);
   }
}
