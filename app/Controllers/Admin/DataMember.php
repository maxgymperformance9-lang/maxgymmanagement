<?php

namespace App\Controllers\Admin;

use App\Models\MemberModel;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;

class DataMember extends BaseController
{
    protected MemberModel $memberModel;
    protected $uploadModel;

    protected $MemberValidationRules = [
        'nama' => [
            'rules' => 'required|min_length[3]',
            'errors' => [
                'required' => 'Nama harus diisi'
            ]
        ],
        'jenis_kelamin' => [
            'rules' => 'required|in_list[Laki-laki,Perempuan]',
            'errors' => [
                'required' => 'Jenis kelamin harus dipilih',
                'in_list' => 'Jenis kelamin tidak valid'
            ]
        ],
        'no_member' => 'required|max_length[20]',
        'type' => [
            'rules' => 'required|in_list[umum,pelajar,mahasiswa,personal_trainer,member_pt]',
            'errors' => [
                'required' => 'Type member harus dipilih',
                'in_list' => 'Type member tidak valid'
            ]
        ],
        'no_hp' => 'required|numeric|max_length[20]|min_length[5]',
        'email' => 'permit_empty|valid_email',
        'alamat' => 'permit_empty',
        'tanggal_join' => 'required|valid_date',
        'tanggal_expired' => 'required|valid_date',
        'keterangan' => 'permit_empty',
        'foto' => 'permit_empty|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]'
    ];

    public function __construct()
    {
        $this->memberModel = new MemberModel();
        $this->uploadModel = new \App\Models\UploadModel();
        $this->emailService = new \App\Libraries\PHPMailerService();
    }

    // ==========================
    // == HALAMAN UTAMA MEMBER ==
    // ==========================
    public function index()
    {
        $data = [
            'title' => 'Data Member',
            'ctx' => 'member',
        ];

        return view('admin/data/data-member', $data);
    }

    public function ambilDataMember()
    {
        $result = $this->memberModel->getAllMembers();

        $data = [
            'data' => $result,
            'empty' => empty($result)
        ];

        return view('admin/data/list-data-member', $data);
    }

    // ==========================
    // == TAMBAH MEMBER ==
    // ==========================
    public function formTambahMember()
    {
        $data = [
            'ctx' => 'member',
            'title' => 'Tambah Data Member'
        ];

        return view('admin/data/create/create-data-member', $data);
    }

    public function saveMember()
    {
        if (!$this->validate($this->MemberValidationRules)) {
            $data = [
                'ctx' => 'member',
                'title' => 'Tambah Data Member',
                'validation' => $this->validator,
                'oldInput' => $this->request->getVar()
            ];
            return view('/admin/data/create/create-data-member', $data);
        }

        $fotoPath = null;
        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid()) {
            $uploadResult = $this->uploadModel->uploadTempFile('foto', true);
            if ($uploadResult) {
                $fotoPath = $uploadResult['path'];
            }
        }

        $result = $this->memberModel->createMember(
            nama: $this->request->getVar('nama'),
            jenisKelamin: $this->request->getVar('jenis_kelamin'),
            noMember: $this->request->getVar('no_member'),
            type: $this->request->getVar('type'),
            noHp: $this->request->getVar('no_hp'),
            email: $this->request->getVar('email'),
            alamat: $this->request->getVar('alamat'),
            tanggalJoin: $this->request->getVar('tanggal_join'),
            tanggalExpired: $this->request->getVar('tanggal_expired'),
            keterangan: $this->request->getVar('keterangan'),
            foto: $fotoPath
        );

        if ($result) {
            $member = $this->memberModel->getMemberById($result);
            if ($member) {
                $qrGenerator = new \App\Controllers\Admin\QRGenerator();
                $qrGenerator->generate(
                    $nama = $member['nama_member'],
                    $nomor = $member['no_hp'],
                    $unique_code = $member['unique_code']
                );

                // Send welcome email if email is provided
                if (!empty($member['email'])) {
                    $this->emailService->sendWelcomeEmail($member);
                }

                // Send welcome WhatsApp message if phone number is provided
                if (!empty($member['no_hp'])) {
                    $this->emailService->sendWelcomeWhatsApp($member);
                }
            }

            session()->setFlashdata([
                'msg' => 'Tambah data berhasil dan QR Code telah digenerate',
                'error' => false
            ]);
            return redirect()->to('/admin/member');
        }

        session()->setFlashdata([
            'msg' => 'Gagal menambah data',
            'error' => true
        ]);
        return redirect()->to('/admin/member/create/');
    }

    // ==========================
    // == EDIT MEMBER ==
    // ==========================
    public function formEditMember($id)
    {
        $member = $this->memberModel->getMemberById($id);

        if (empty($member)) {
            throw new PageNotFoundException('Data member dengan id ' . $id . ' tidak ditemukan');
        }

        $data = [
            'data' => $member,
            'ctx' => 'member',
            'title' => 'Edit Data Member',
        ];

        return view('admin/data/edit/edit-data-member', $data);
    }

    public function updateMember()
    {
        $idMember = $this->request->getVar('id');

        if (!$this->validate($this->MemberValidationRules)) {
            $data = [
                'data' => $this->memberModel->getMemberById($idMember),
                'ctx' => 'member',
                'title' => 'Edit Data Member',
                'validation' => $this->validator,
                'oldInput' => $this->request->getVar()
            ];
            return view('/admin/data/edit/edit-data-member', $data);
        }

        $existingMember = $this->memberModel->getMemberById($idMember);
        $fotoPath = $existingMember['foto'] ?? null;

        $fotoFile = $this->request->getFile('foto');
        if ($fotoFile && $fotoFile->isValid()) {
            $uploadResult = $this->uploadModel->uploadTempFile('foto', true);
            if ($uploadResult) {
                $fotoPath = $uploadResult['path'];
            }
        }

        $result = $this->memberModel->updateMember(
            id: $idMember,
            nama: $this->request->getVar('nama'),
            jenisKelamin: $this->request->getVar('jenis_kelamin'),
            noMember: $this->request->getVar('no_member'),
            type: $this->request->getVar('type'),
            noHp: $this->request->getVar('no_hp'),
            email: $this->request->getVar('email'),
            alamat: $this->request->getVar('alamat'),
            tanggalJoin: $this->request->getVar('tanggal_join'),
            tanggalExpired: $this->request->getVar('tanggal_expired'),
            keterangan: $this->request->getVar('keterangan'),
            foto: $fotoPath
        );

        if ($result) {
            // Send renewal confirmation email if email is provided and membership was extended
            $updatedMember = $this->memberModel->getMemberById($idMember);
            if ($updatedMember && !empty($updatedMember['email'])) {
                $this->emailService->sendRenewalConfirmation($updatedMember);
            }

            session()->setFlashdata([
                'msg' => 'Edit data berhasil',
                'error' => false
            ]);
            return redirect()->to('/admin/member');
        }

        session()->setFlashdata([
            'msg' => 'Gagal mengubah data',
            'error' => true
        ]);
        return redirect()->to('/admin/member/edit/' . $idMember);
    }

    // ==========================
    // == HAPUS MEMBER ==
    // ==========================
    public function delete($id)
    {
        $result = $this->memberModel->delete($id);

        if ($result) {
            session()->setFlashdata([
                'msg' => 'Data berhasil dihapus',
                'error' => false
            ]);
            return redirect()->to('/admin/member');
        }

        session()->setFlashdata([
            'msg' => 'Gagal menghapus data',
            'error' => true
        ]);
        return redirect()->to('/admin/member');
    }

    // ==========================
    // == UPLOAD FOTO MEMBER ==
    // ==========================
    public function uploadFoto($id)
    {
        $file = $this->request->getFile('foto');

        if (!$file || !$file->isValid()) {
            session()->setFlashdata([
                'msg' => 'File foto tidak valid atau tidak ditemukan.',
                'error' => true
            ]);
            return redirect()->back();
        }

        // Validasi file
        if (!$this->validate([
            'foto' => [
                'rules' => 'uploaded[foto]|max_size[foto,2048]|is_image[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]',
                'errors' => [
                    'uploaded' => 'Harap pilih file foto.',
                    'max_size' => 'Ukuran foto maksimal 2MB.',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Tipe file tidak diizinkan.'
                ]
            ]
        ])) {
            session()->setFlashdata([
                'msg' => 'Validasi gagal: ' . implode(', ', $this->validator->getErrors()),
                'error' => true
            ]);
            return redirect()->back();
        }

        // Buat folder upload jika belum ada
        $uploadPath = FCPATH . 'uploads/member/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        if (!$file->move($uploadPath, $newName)) {
            session()->setFlashdata([
                'msg' => 'Gagal memindahkan file ke folder upload.',
                'error' => true
            ]);
            return redirect()->back();
        }

        // Simpan ke database
        $update = $this->memberModel->update($id, ['foto' => $newName]);

        if ($update) {
            session()->setFlashdata([
                'msg' => 'Foto member berhasil diupload.',
                'error' => false
            ]);
        } else {
            session()->setFlashdata([
                'msg' => 'Gagal menyimpan foto ke database.',
                'error' => true
            ]);
        }

        return redirect()->to('/admin/member');
    }

    // ==========================
    // == KIRIM EMAIL WELCOME ==
    // ==========================
    public function sendWelcomeEmail($id)
    {
        $member = $this->memberModel->getMemberById($id);

        if (empty($member)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }

        if (empty($member['email'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member tidak memiliki email'
            ]);
        }

        $result = $this->emailService->sendWelcomeEmail($member);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Email welcome berhasil dikirim ke ' . $member['email']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengirim email welcome'
            ]);
        }
    }

    // ==========================
    // == KIRIM WHATSAPP WELCOME ==
    // ==========================
    public function sendWelcomeWhatsApp($id)
    {
        $member = $this->memberModel->getMemberById($id);

        if (empty($member)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member tidak ditemukan'
            ]);
        }

        if (empty($member['no_hp'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Member tidak memiliki nomor HP'
            ]);
        }

        $result = $this->emailService->sendWelcomeWhatsApp($member);

        if ($result) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'WhatsApp welcome berhasil dikirim ke ' . $member['no_hp']
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal mengirim WhatsApp welcome'
            ]);
        }
    }
}
