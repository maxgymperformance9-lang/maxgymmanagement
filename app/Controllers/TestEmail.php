<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\PHPMailerService;
use CodeIgniter\HTTP\ResponseInterface;

class TestEmail extends BaseController
{
    public function index()
    {
        return view('test_email');
    }

    public function sendTest()
    {
        $emailService = new PHPMailerService();

        $testData = [
            'nama_member' => 'Test Member',
            'no_member' => 'TEST001',
            'tanggal_join' => date('Y-m-d'),
            'tanggal_expired' => date('Y-m-d', strtotime('+30 days')),
            'type_member' => 'umum',
            'email' => 'miminmintar009@gmail.com' // Change this to your actual test email
        ];

        $result = $emailService->sendWelcomeEmail($testData);

        if ($result) {
            echo "Email sent successfully!";
        } else {
            echo "Failed to send email. Check logs for details.";
        }
    }
}
