<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class EmailTemplateController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Email Templates',
            'ctx' => 'email-templates',
        ];

        return view('admin/email_templates/index', $data);
    }

    public function preview($template)
    {
        $templates = [
            'welcome' => 'welcome.php',
            'expiration_reminder' => 'expiration_reminder.php',
            'membership_expired' => 'membership_expired.php',
            'renewal_confirmation' => 'renewal_confirmation.php',
            'password_reset' => 'password_reset.php'
        ];

        if (!isset($templates[$template])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Template not found');
        }

        // Sample data for preview
        $sampleData = [
            'nama_member' => 'John Doe',
            'no_member' => 'MBR001',
            'tanggal_join' => '2024-01-15',
            'tanggal_expired' => '2024-12-15',
            'type_member' => 'Premium',
            'email' => 'john.doe@example.com',
            'reset_link' => base_url('reset-password/token123')
        ];

        $data = [
            'title' => 'Preview Email Template',
            'template' => $template,
            'template_file' => $templates[$template],
            'member' => $sampleData,
            'days_left' => 7,
            'reset_link' => $sampleData['reset_link']
        ];

        return view('admin/email_templates/preview', $data);
    }
}
