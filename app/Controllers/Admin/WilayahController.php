<?php

namespace App\Controllers\Admin;

use App\Models\WilayahModel;
use App\Models\DiModel;
use App\Controllers\BaseController;

class WilayahController extends BaseController
{
    protected WilayahModel $WilayahModel;
    protected DiModel $DiModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->DiModel = new DiModel();
        $this->WilayahModel = new WilayahModel();
    }

    /**
     * Return redirect to di controller
     *
     * @return mixed
     */
    public function index()
    {
        return redirect()->to('admin/di');
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */

    public function listData()
    {
        $vars['data'] = $this->WilayahModel->getDataWilayah();
        $htmlContent = '';
        if (!empty($vars['data'])) {
            $htmlContent = view('admin/wilayah/list-wilayah', $vars);
            $data = [
                'result' => 1,
                'htmlContent' => $htmlContent,
            ];
            echo json_encode($data);
        } else {
            echo json_encode(['result' => 0]);
        }
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function tambahWilayah()
    {
        $data = [
            'ctx' => 'wilayah',
            'title' => 'Tambah Data D.I',
        ];
        return view('/admin/wilayah/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function tambahJurusanPost()
    {
        $val = \Config\Services::validation();
        $val->setRule('wilayah', 'wilayah', 'required|max_length[32]|is_unique[tb_wilayah.wilayah]');

        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to('admin/wilayah/tambah')->withInput();
        } else {
            if ($this->WilayahModel->addWilayah()) {
                $this->session->setFlashdata('success', 'Tambah data berhasil');
                return redirect()->to('admin/wilayah');
            } else {
                $this->session->setFlashdata('error', 'Gagal menambah data');
                return redirect()->to('admin/wilayah/tambah')->withInput();
            }
        }

        return redirect()->to('admin/wilayah/tambah');
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function editWilayah($id)
    {
        $data['title'] = 'Edit Wilayah';
        $data['ctx'] = 'di';
        $data['wilayah'] = $this->WilayahModel->getWilayah($id);
        if (empty($data['wilayah'])) {
            return redirect()->to('admin/di');
        }

        return view('/admin/wilayah/edit', $data);
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function editWilayahPost()
    {
        $val = \Config\Services::validation();
        $val->setRule('wilayah', 'wilayah', 'required|max_length[32]|is_unique[tb_wilayah.wilayah]');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back();
        } else {
            $id = inputPost('id');
            if ($this->WilayahModel->editWilayah($id)) {
                $this->session->setFlashdata('success', 'Edit data berhasil');
                return redirect()->to('admin/wilayah');
            } else {
                $this->session->setFlashdata('error', 'Gagal Mengubah data');
            }
        }
        return redirect()->to('admin/wilayah/edit/' . cleanNumber($id));
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function deleteWilayahPost($id = null)
    {
        $id = inputPost('id');
        $wilayah = $this->WilayahModel->getWilayah($id);
        if (!empty($wilayah)) {
            if (!empty($this->DiModel->getDiCountByWilayah($id))) {
                $this->session->setFlashdata('error', 'Hapus Relasi Data Dulu');
                exit();
            }
            if ($this->WilayahModel->deleteWilayah($id)) {
                $this->session->setFlashdata('success', 'Data berhasil dihapus');
            } else {
                $this->session->setFlashdata('error', 'Gagal menghapus data');
            }
        }
    }
}
