<?php namespace App\Models;

use CodeIgniter\Model;
use Config\Office;

class BaseModel extends Model
{
    public $request;
    public $session;
    public $generalSettings;

    public function __construct()
    {
        parent::__construct();
        $this->request = \Config\Services::request();
        $this->session = \Config\Services::session();
        $this->generalSettings = Office::$generalSettings;
    }
}