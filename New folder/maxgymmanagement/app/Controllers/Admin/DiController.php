<?php

namespace App\Controllers\Admin;

use App\Models\WilayahModel;
use App\Models\DiModel;
use App\Controllers\BaseController;

class DiController extends BaseController
{
    protected DiModel $DiModel;

    protected WilayahModel $WilayahModel;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->DiModel = new DiModel();
        $this->WilayahModel = new WilayahModel();
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $data = [
            'title' => 'D.I & Wilayah',
            'ctx' => 'di',
        ];

        return view('admin/di/index', $data);
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function listData()
    {
        $vars['data'] = $this->DiModel->getDataDi();
        $htmlContent = '';
        if (!empty($vars['data'])) {
            $htmlContent = view('admin/di/list-di', $vars);
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
    public function tambahDi()
    {
        $data['ctx'] = 'di';
        $data['title'] = 'Tambah Data D.I';
        $data['wilayah'] = $this->WilayahModel->findAll();

        return view('/admin/di/create', $data);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function tambahDiPost()
    {
        $val = \Config\Services::validation();
        $val->setRule('di', 'di', 'required|max_length[32]');
        $val->setRule('id_wilayah', 'wilayah', 'required|numeric');

        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->to('admin/di/tambah')->withInput();
        } else {
            if ($this->DiModel->addDi()) {
                $this->session->setFlashdata('success', 'Tambah data berhasil');
                return redirect()->to('admin/di');
            } else {
                $this->session->setFlashdata('error', 'Gagal menambah data');
                return redirect()->to('admin/di/tambah')->withInput();
            }
        }

        return redirect()->to('admin/di/tambah');
    }

    /**
     * Return a resource object, with default properties
     *
     * @return mixed
     */
    public function editDi($id)
    {
        $data['title'] = 'Edit D.I';
        $data['ctx'] = 'di';
        $data['wilayah'] = $this->WilayahModel->findAll();
        $data['di'] = $this->DiModel->getDi($id);
        if (empty($data['di'])) {
            return redirect()->to('admin/di');
        }

        return view('/admin/di/edit', $data);
    }

    /**
     * Edit a resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function editDiPost()
    {
        $val = \Config\Services::validation();
        $val->setRule('di', 'di', 'required|max_length[32]');
        $val->setRule('id_wilayah', 'wilayah', 'required|numeric');
        if (!$this->validate(getValRules($val))) {
            $this->session->setFlashdata('errors', $val->getErrors());
            return redirect()->back();
        } else {
            $id = inputPost('id');
            if ($this->DiModel->editDi($id)) {
                $this->session->setFlashdata('success', 'Edit data berhasil');
                return redirect()->to('admin/di');
            } else {
                $this->session->setFlashdata('error', 'Gagal Mengubah data');
            }
        }
        return redirect()->to('admin/di/edit/' . cleanNumber($id));
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function deleteDiPost($id = null)
    {
        $id = inputPost('id');
        $di = $this->DiModel->getDi($id);
        if (!empty($di)) {
            $PenjagaModel = new \App\Models\PenjagaModel();
            if (!empty($PenjagaModel->getPenjagaCountByDi($id))) {
                $this->session->setFlashdata('error', 'D.I Masih Memiliki Petugas Aktif');
                exit();
            }
            if ($this->DiModel->deleteDi($id)) {
                $this->session->setFlashdata('success', 'Data berhasil dihapus');
            } else {
                $this->session->setFlashdata('error', 'Gagal menghapus data');
            }
        }
    }
}
