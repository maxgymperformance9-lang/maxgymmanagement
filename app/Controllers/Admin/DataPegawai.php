<?php

namespace App\Controllers\Admin;

use App\Models\PegawaiModel;

use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class dataPegawai extends BaseController
{
   protected PegawaiModel $pegawaiModel;
   protected $uploadModel;

   protected $PegawaiValidationRules = [
      'nip' => [
         'rules' => 'required|max_length[20]|min_length[10]',
         'errors' => [
            'required' => 'NIP harus diisi.',
            'is_unique' => 'NIP ini telah terdaftar.',
            'min_length[16]' => 'Panjang NIP minimal 16 karakter'
         ]
      ],
      'nama' => [
         'rules' => 'required|min_length[3]',
         'errors' => [
            'required' => 'Nama harus diisi'
         ]
      ],
      'jk' => ['rules' => 'required', 'errors' => ['required' => 'Jenis kelamin wajib diisi']],
      'no_hp' => 'required|numeric|max_length[20]|min_length[5]',
      'foto' => 'permit_empty|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]'
   ];

   public function __construct()
   {
      $this->pegawaiModel = new PegawaiModel();
      $this->uploadModel = new \App\Models\UploadModel();
   }

   public function index()
   {
      $data = [
         'title' => 'Data Pegawai',
         'ctx' => 'pegawai',
      ];

      return view('admin/data/data-pegawai', $data);
   }

   public function ambilDataPegawai()
   {
      $result = $this->pegawaiModel->getAllPegawai();

      $data = [
         'data' => $result,
         'empty' => empty($result)
      ];

      return view('admin/data/list-data-pegawai', $data);
   }

   public function formTambahPegawai()
   {
      $data = [
         'ctx' => 'pegawai',
         'title' => 'Tambah Data Pegawai'
      ];

      return view('admin/data/create/create-data-pegawai', $data);
   }

   public function savePegawai()
   {
      // validasi
      if (!$this->validate($this->PegawaiValidationRules)) {
         $data = [
            'ctx' => 'pegawai',
            'title' => 'Tambah Data Pegawai',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/create/create-data-pegawai', $data);
      }

      // upload foto
      $fotoPath = null;
      if ($this->request->getFile('foto')->isValid()) {
         $uploadResult = $this->uploadModel->uploadTempFile('foto', true);
         if ($uploadResult) {
            $fotoPath = $uploadResult['path'];
         }
      }

      // simpan
      $result = $this->pegawaiModel->createPegawai(
         nip: $this->request->getVar('nip'),
         nama: $this->request->getVar('nama'),
         jenisKelamin: $this->request->getVar('jk'),
         alamat: $this->request->getVar('alamat'),
         noHp: $this->request->getVar('no_hp'),
         foto: $fotoPath
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Tambah data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/pegawai');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menambah data',
         'error' => true
      ]);
      return redirect()->to('/admin/pegawai/create/');
   }

   public function formEditGuru($id)
   {
      $pegawai = $this->pegawaiModel->getPegawaiById($id);

      if (empty($pegawai)) {
         throw new PageNotFoundException('Data pegawai dengan id ' . $id . ' tidak ditemukan');
      }

      $data = [
         'data' => $pegawai,
         'ctx' => 'pegawai',
         'title' => 'Edit Data Pegawai',
      ];

      return view('admin/data/edit/edit-data-pegawai', $data);
   }

   public function updatePegawai()
   {
      $idPegawai = $this->request->getVar('id');

      // validasi
      if (!$this->validate($this->PegawaiValidationRules)) {
         $data = [
            'data' => $this->pegawaiModel->getPegawaiById($idPegawai),
            'ctx' => 'pegawai',
            'title' => 'Edit Data Pegawai',
            'validation' => $this->validator,
            'oldInput' => $this->request->getVar()
         ];
         return view('/admin/data/edit/edit-data-pegawai', $data);
      }

      // Get existing pegawai data to preserve current foto if no new photo uploaded
      $existingPegawai = $this->pegawaiModel->getPegawaiById($idPegawai);
      $fotoPath = $existingPegawai['foto']; // Keep existing photo by default

      // upload foto only if a new file is selected
      if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
         $uploadResult = $this->uploadModel->uploadTempFile('foto', true);
         if ($uploadResult) {
            $fotoPath = $uploadResult['path'];
         }
      }

      // update
      $result = $this->pegawaiModel->updatePegawai(
         id: $idPegawai,
         nip: $this->request->getVar('nip'),
         nama: $this->request->getVar('nama'),
         jenisKelamin: $this->request->getVar('jk'),
         alamat: $this->request->getVar('alamat'),
         noHp: $this->request->getVar('no_hp'),
         foto: $fotoPath
      );

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Edit data berhasil',
            'error' => false
         ]);
         return redirect()->to('/admin/pegawai');
      }

      session()->setFlashdata([
         'msg' => 'Gagal mengubah data',
         'error' => true
      ]);
      return redirect()->to('/admin/pegawai/edit/' . $idPegawai);
   }

   public function delete($id)
   {
      $result = $this->pegawaiModel->delete($id);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Data berhasil dihapus',
            'error' => false
         ]);
         return redirect()->to('/admin/pegawai');
      }

      session()->setFlashdata([
         'msg' => 'Gagal menghapus data',
         'error' => true
      ]);
      return redirect()->to('/admin/pegawai');
   }

   public function uploadFoto($id)
   {
      // Validasi file upload
      if (!$this->validate([
         'foto' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]'
      ])) {
         session()->setFlashdata([
            'msg' => 'File tidak valid. Pastikan file adalah gambar dengan ukuran maksimal 2MB.',
            'error' => true
         ]);
         return redirect()->to('/admin/pegawai');
      }

      // Upload foto
      $uploadResult = $this->uploadModel->uploadTempFile('foto', true);
      if (!$uploadResult) {
         session()->setFlashdata([
            'msg' => 'Gagal mengupload foto',
            'error' => true
         ]);
         return redirect()->to('/admin/pegawai');
      }

      // Update foto di database
      $result = $this->pegawaiModel->updateFoto($id, $uploadResult['path']);

      if ($result) {
         session()->setFlashdata([
            'msg' => 'Foto berhasil diupload',
            'error' => false
         ]);
      } else {
         session()->setFlashdata([
            'msg' => 'Gagal menyimpan foto ke database',
            'error' => true
         ]);
      }

      return redirect()->to('/admin/pegawai');
   }
}
