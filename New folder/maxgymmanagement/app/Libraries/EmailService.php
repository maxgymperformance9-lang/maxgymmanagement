<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class EmailService
{
    protected $email;

    public function __construct()
    {
        $this->email = new Email();
    }

    /**
     * Send welcome email to new member
     */
    public function sendWelcomeEmail($memberData)
    {
        $subject = 'Selamat Datang di MaxGym Management!';
        $message = view('email/welcome', ['member' => $memberData]);

        return $this->sendEmail($memberData['email'], $subject, $message);
    }

    /**
     * Send membership expiration reminder
     */
    public function sendExpirationReminder($memberData, $daysLeft)
    {
        $subject = 'Pengingat: Membership Anda Akan Berakhir';
        $message = view('email/expiration_reminder', [
            'member' => $memberData,
            'days_left' => $daysLeft
        ]);

        return $this->sendEmail($memberData['email'], $subject, $message);
    }

    /**
     * Send membership expired notification
     */
    public function sendMembershipExpired($memberData)
    {
        $subject = 'Membership Anda Telah Berakhir';
        $message = view('email/membership_expired', ['member' => $memberData]);

        return $this->sendEmail($memberData['email'], $subject, $message);
    }

    /**
     * Send membership renewal confirmation
     */
    public function sendRenewalConfirmation($memberData)
    {
        $subject = 'Konfirmasi Perpanjangan Membership';
        $message = view('email/renewal_confirmation', ['member' => $memberData]);

        return $this->sendEmail($memberData['email'], $subject, $message);
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset($email, $resetLink)
    {
        $subject = 'Reset Password - MaxGym Management';
        $message = view('email/password_reset', ['reset_link' => $resetLink]);

        return $this->sendEmail($email, $subject, $message);
    }

    /**
     * Generic email sending method
     */
    private function sendEmail($to, $subject, $message)
    {
        $this->email->setTo($to);
        $this->email->setFrom(config('Email')->fromEmail, config('Email')->fromName);
        $this->email->setSubject($subject);
        $this->email->setMessage($message);
        $this->email->setMailType('html');

        if ($this->email->send()) {
            log_message('info', "Email sent successfully to: {$to} - Subject: {$subject}");
            return true;
        } else {
            log_message('error', "Failed to send email to: {$to} - Error: " . $this->email->printDebugger());
            return false;
        }
    }

    /**
     * Send bulk emails (for reminders, etc.)
     */
    public function sendBulkEmails($recipients, $subject, $message)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($recipients as $recipient) {
            if ($this->sendEmail($recipient['email'], $subject, $message)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        log_message('info', "Bulk email completed. Success: {$successCount}, Failed: {$failCount}");
        return ['success' => $successCount, 'failed' => $failCount];
    }

    /**
     * Send WhatsApp message to member
     */
    public function sendWelcomeWhatsApp($memberData)
    {
        if (empty($memberData['no_hp'])) {
            return false;
        }

        // Format phone number (remove leading 0 and add country code if needed)
        $phone = $this->formatPhoneNumber($memberData['no_hp']);

        $message = "Selamat datang di MaxGym Management!\n\n" .
                  "Halo {$memberData['nama_member']},\n\n" .
                  "Terima kasih telah bergabung dengan kami. Berikut adalah detail membership Anda:\n\n" .
                  "📋 Nama: {$memberData['nama_member']}\n" .
                  "🆔 No Member: {$memberData['no_member']}\n" .
                  "📅 Tanggal Join: " . date('d/m/Y', strtotime($memberData['tanggal_join'])) . "\n" .
                  "⏰ Tanggal Expired: " . date('d/m/Y', strtotime($memberData['tanggal_expired'])) . "\n\n" .
                  "Silakan gunakan QR Code Anda untuk check-in di gym.\n\n" .
                  "Salam,\nMaxGym Management";

        return $this->sendWhatsAppMessage($phone, $message);
    }

    /**
     * Send WhatsApp message using external API
     */
    private function sendWhatsAppMessage($phone, $message)
    {
        try {
            // Using WhatsApp Business API or third-party service
            // For this example, we'll use a placeholder API call
            // You would replace this with actual WhatsApp API integration

            $apiUrl = 'https://api.whatsapp.com/send'; // Replace with actual API endpoint
            $apiKey = 'your_api_key_here'; // Replace with actual API key

            $data = [
                'phone' => $phone,
                'message' => $message,
                'api_key' => $apiKey
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpCode == 200) {
                log_message('info', "WhatsApp message sent successfully to: {$phone}");
                return true;
            } else {
                log_message('error', "Failed to send WhatsApp message to: {$phone} - HTTP Code: {$httpCode} - Response: {$response}");
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', "Exception sending WhatsApp message to {$phone}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/\D/', '', $phone);

        // If starts with 0, replace with country code (assuming Indonesia +62)
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        }

        // Ensure it starts with country code
        if (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return $phone;
    }
}
