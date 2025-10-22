<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerService
{
    protected $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->configureSMTP();
    }

    /**
     * Configure SMTP settings
     */
    private function configureSMTP()
    {
        try {
            // Server settings
            $this->mail->isSMTP();
            $this->mail->Host       = 'smtp.gmail.com';
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = 'maxgymperformance9@gmail.com';
            $this->mail->Password   = 'czhzewvmdvevkqdu';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mail->Port       = 465;

            // Default sender
            $this->mail->setFrom('maxgymperformance9@gmail.com', 'MaxGym Management');

        } catch (Exception $e) {
            log_message('error', 'PHPMailer configuration error: ' . $e->getMessage());
        }
    }

    /**
     * Send welcome email to new member with photo attachment
     */
    public function sendWelcomeEmail($memberData)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($memberData['email'], $memberData['nama_member']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Selamat Datang di MaxGym Management!';

            // Enhanced email body with photo instruction
            $emailBody = view('email/welcome', ['member' => $memberData]);
            $emailBody .= '<br><br><p><strong>Foto member Anda telah dilampirkan dalam email ini untuk keperluan absensi.</strong></p>';

            $this->mail->Body = $emailBody;

            // Attach member photo if available
            if (!empty($memberData['foto'])) {
                $photoPath = FCPATH . 'uploads/member/' . $memberData['foto'];
                if (file_exists($photoPath)) {
                    $this->mail->addAttachment($photoPath, 'Foto_Member_' . $memberData['nama_member'] . '.png');
                    log_message('info', "Member photo attached successfully for: {$memberData['nama_member']}");
                } else {
                    log_message('warning', "Member photo not found for: {$memberData['nama_member']} - Path: {$photoPath}");
                }
            } else {
                log_message('warning', "No photo data available for member: {$memberData['nama_member']}");
            }

            $this->mail->send();
            log_message('info', "Welcome email with photo attachment sent successfully to: {$memberData['email']}");
            return true;
        } catch (Exception $e) {
            log_message('error', "Failed to send welcome email to {$memberData['email']}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send membership expiration reminder
     */
    public function sendExpirationReminder($memberData, $daysLeft)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($memberData['email'], $memberData['nama_member']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Pengingat: Membership Anda Akan Berakhir';
            $this->mail->Body    = view('email/expiration_reminder', [
                'member' => $memberData,
                'days_left' => $daysLeft
            ]);

            $this->mail->send();
            log_message('info', "Expiration reminder sent successfully to: {$memberData['email']}");
            return true;
        } catch (Exception $e) {
            log_message('error', "Failed to send expiration reminder to {$memberData['email']}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send membership expired notification
     */
    public function sendMembershipExpired($memberData)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($memberData['email'], $memberData['nama_member']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Membership Anda Telah Berakhir';
            $this->mail->Body    = view('email/membership_expired', ['member' => $memberData]);

            $this->mail->send();
            log_message('info', "Membership expired notification sent successfully to: {$memberData['email']}");
            return true;
        } catch (Exception $e) {
            log_message('error', "Failed to send membership expired notification to {$memberData['email']}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send membership renewal confirmation
     */
    public function sendRenewalConfirmation($memberData)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($memberData['email'], $memberData['nama_member']);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Konfirmasi Perpanjangan Membership';
            $this->mail->Body    = view('email/renewal_confirmation', ['member' => $memberData]);

            $this->mail->send();
            log_message('info', "Renewal confirmation sent successfully to: {$memberData['email']}");
            return true;
        } catch (Exception $e) {
            log_message('error', "Failed to send renewal confirmation to {$memberData['email']}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send password reset email
     */
    public function sendPasswordReset($email, $resetLink)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();

            $this->mail->addAddress($email);
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Reset Password - MaxGym Management';
            $this->mail->Body    = view('email/password_reset', ['reset_link' => $resetLink]);

            $this->mail->send();
            log_message('info', "Password reset email sent successfully to: {$email}");
            return true;
        } catch (Exception $e) {
            log_message('error', "Failed to send password reset email to {$email}: " . $this->mail->ErrorInfo);
            return false;
        }
    }

    /**
     * Send WhatsApp message using external API
     */
    public function sendWelcomeWhatsApp($memberData)
    {
        try {
            $phone = $this->formatPhoneNumber($memberData['no_hp']);

            $message = "Selamat datang di MaxGym Management!\n\n" .
                      "Halo {$memberData['nama_member']},\n\n" .
                      "Terima kasih telah bergabung dengan MaxGym Management.\n" .
                      "No. Member: {$memberData['no_member']}\n" .
                      "Tanggal Join: " . date('d/m/Y', strtotime($memberData['tanggal_join'])) . "\n" .
                      "Tanggal Expired: " . date('d/m/Y', strtotime($memberData['tanggal_expired'])) . "\n\n" .
                      "Silakan gunakan QR Code Anda untuk check-in di gym.\n\n" .
                      "Salam,\nMaxGym Management";

            return $this->sendWhatsAppMessage($phone, $message);
        } catch (\Exception $e) {
            log_message('error', "Exception sending WhatsApp welcome to {$memberData['no_hp']}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp message for attendance confirmation
     */
    public function sendAttendanceWhatsApp($memberData, $attendanceData)
    {
        try {
            $phone = $this->formatPhoneNumber($memberData['no_hp']);

            $message = "✅ Absensi Berhasil!\n\n" .
                      "Halo {$memberData['nama_member']},\n\n" .
                      "Absensi Anda telah tercatat:\n" .
                      "📅 Tanggal: " . date('d/m/Y') . "\n" .
                      "🕐 Jam Masuk: {$attendanceData['jam_masuk']}\n" .
                      "🏠 Lokasi: MaxGym Management\n\n" .
                      "Terima kasih atas kunjungan Anda!\n\n" .
                      "Salam,\nMaxGym Management";

            return $this->sendWhatsAppMessage($phone, $message);
        } catch (\Exception $e) {
            log_message('error', "Exception sending attendance WhatsApp to {$memberData['no_hp']}: " . $e->getMessage());
            return false;
        }
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
