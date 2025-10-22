<?php

namespace App\Controllers\Admin;

use App\Models\PenjagaModel;
use App\Models\DiModel;

use App\Controllers\BaseController;
use App\Models\WilayahModel;
use App\Models\UploadModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class dataPenjaga extends BaseController
{
   protected PenjagaModel $PenjagaModel;
   protected DiModel $DiModel;
   protected WilayahModel $WilayahModel;

   protected $penjagaValidationRules = [
      'nip' => [
         'rules' => 'required|max_length[20]|min_length[4]',
         'errors' => [
            'required' => 'NIP harus diisi.',
            'is_unique' => 'NIP ini telah terdaftar.',
            'min_length[4]' => 'Panjang NIP minimal 4 karakter'
         ]
      ],
      'nama' => [
         'rules' => 'required|min_length[3]',
         'errors' => [
            'required' => 'Nama harus diisi'
         ]
      ],
      'id_di' => [
         'rules' => 'required',
         'errors' => [
            'required' => 'D.I/WIL harus diisi'
         ]
      ],
      'jk' => ['rules' => 'required', 'errors' => ['required' => 'Jenis kelamin wajib diisi']],
      'no_hp' => 'required|numeric|max_length[20]|min_length[5]'
   ];

   public function __construct()
   {
      $this->PenjagaModel = new PenjagaModel();
      $this->DiModel = new DiModel();
      $this->WilayahModel = new WilayahModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Data Petugas',
         'ctx' => 'penjaga',
         'di' => $this->DiModel->getDataDi(),
         'wilayah' => $this->WilayahModel->getDataWilayah()
      ];

      return view('admin/data/data-penjaga', $data);
   }

   public function ambilDataPenjaga()
   {
      $di = $this->request->getVar('di') ?? null;
      $wilayah = $this->request->getVar('wilayah') ?? null;

      $result = $this->PenjagaModel->getAllPenjagaWithDi($di, $wilayah);

      $data = [
         'data' => $result,
         'empty' => empty($result)
      ];

      return view('admin/data/list-data-penjaga', $data);
   }

   public function formTambahSiswa()
   {
      $di = $this->DiModel->getDataDi();

      $data = [
         'ctx' => 'penjaga',
         'di' => $di,
         'title' => 'Tambah Data Petugas'
      ];

      return view('admin/data/create/create-data-penjaga', $data);
   }

   public function saveSiswa()
   {
      // validasi
      if (!$this->validate($this->penjagaValidationRules)) {
         $di = $this->DiModel->getDataDi();

         $data = [
            'ctx' => 'penjaga',
            'di' => $di,
            'title' => 'Tambah Data Petugas',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/create/create-data-penjaga', $data);
      }

      // simpan
      $result = $this->PenjagaModel->createSiswa(
         nip: $this->request->getVar('nip'),
         nama: $this->request->getVar('nama'),
         idDi: intval($this->request->getVar('id_di')),
         jenisKelamin: $this->request->getVar('jk'),
         noHp: $this->request->getVar('no_hp'),
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Tambah data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/penjaga');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menambah data',
         'error' => true
      ]);
      return redirect()->to('/admin/petugas/create');
   }

   public function formEditSiswa($id)
   {
      $penjaga = $this->PenjagaModel->getPenjagaById($id);
      $di = $this->DiModel->getDataDi();

      if (empty($penjaga) || empty($di)) {
         throw new PageNotFoundException('Data petugas dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $penjaga,
         'di' => $di,
         'ctx' => 'penjaga',
         'title' => 'Edit Petugas',
      ];

      return view('admin/data/edit/edit-data-penjaga', $data);
   }

   public function updatePenjaga()
   {
      $idPenjaga = $this->request->getVar('id');

      $penjagaLama = $this->PenjagaModel->getPenjagaById($idPenjaga);

      if ($penjagaLama['nip'] != $this->request->getVar('nip')) {
         $this->penjagaValidationRules['nip']['rules'] = 'required|max_length[20]|min_length[4]|is_unique[tb_penjaga.nip]';
      }

      // validasi
      if (!$this->validate($this->penjagaValidationRules)) {
         $penjaga = $this->PenjagaModel->getPenjagaById($idPenjaga);
         $di = $this->DiModel->getDataDi();

         $data = [
            'data' => $penjaga,
            'di' => $di,
            'ctx' => 'penjaga',
            'title' => 'Edit Petugas',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/edit/edit-data-penjaga', $data);
      }

      // update
      $result = $this->PenjagaModel->updatePenjaga(
         id: $idPenjaga,
         nip: $this->request->getVar('nip'),
         nama: $this->request->getVar('nama'),
         idDi: intval($this->request->getVar('id_di')),
         jenisKelamin: $this->request->getVar('jk'),
         noHp: $this->request->getVar('no_hp'),
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Edit data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/penjaga');
      }

      session()->setFlashdata([
         'msg' => 'Gagal mengubah data',
         'error' => true
      ]);
      return redirect()->to('/admin/penjaga/edit/' . $idPenjaga);
   }

   public function delete($id)
   {
      $result = $this->PenjagaModel->delete($id);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Data berhasil dihapus',
            'error' => false
         ]);
         return redirect()->to('/admin/penjaga');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menghapus data',
         'error' => true
      ]);
      return redirect()->to('/admin/penjaga');
   }

   /**
    * Delete Selected Posts
    */
   public function deleteSelectedPenjaga()
   {
      $penjagaIds = inputPost('penjaga_ids');
      $this->PenjagaModel->deleteMultiSelected($penjagaIds);
   }

   /*
    *-------------------------------------------------------------------------------------------------
    * IMPORT penjaga
    *-------------------------------------------------------------------------------------------------
    */

   /**
    * Bulk Post Upload
    */
   public function bulkPostSiswa()
   {
      $data['title'] = 'Import Petugas';
      $data['ctx'] = 'penjaga';
      $data['di'] = $this->DiModel->getDataDi();

      return view('/admin/data/import-penjaga', $data);
   }

   /**
    * Generate CSV Object Post
    */
   public function generateCSVObjectPost()
   {
      $uploadModel = new UploadModel();
      //delete old txt files
      $files = glob(FCPATH . 'uploads/tmp/*.txt');
      if (!empty($files)) {
         foreach ($files as $item) {
            @unlink($item);
         }
      }
      $file = $uploadModel->uploadCSVFile('file');
      if (!empty($file) && !empty($file['path'])) {
         $obj = $this->PenjagaModel->generateCSVObject($file['path']);
         if (!empty($obj)) {
            $data = [
               'result' => 1,
               'numberOfItems' => $obj->numberOfItems,
               'txtFileName' => $obj->txtFileName,
            ];
            echo json_encode($data);
            exit();
         }
      }
      echo json_encode(['result' => 0]);
   }

   /**
    * Import CSV Item Post
    */
   public function importCSVItemPost()
   {
      $txtFileName = inputPost('txtFileName');
      $index = inputPost('index');
      $penjaga = $this->PenjagaModel->importCSVItem($txtFileName, $index);
      if (!empty($penjaga)) {
         $data = [
            'result' => 1,
            'penjaga' => $penjaga,
            'index' => $index
         ];
         echo json_encode($data);
      } else {
         $data = [
            'result' => 0,
            'index' => $index
         ];
         echo json_encode($data);
      }
   }

   /**
    * Download CSV File Post
    */
   public function downloadCSVFilePost()
   {
      $submit = inputPost('submit');
      $response = \Config\Services::response();
      if ($submit == 'csv_siswa_template') {
         return $response->download(FCPATH . 'assets/file/csv_siswa_template.csv', null);
      } elseif ($submit == 'csv_guru_template') {
         return $response->download(FCPATH . 'assets/file/csv_guru_template.csv', null);
      }
   }
}
