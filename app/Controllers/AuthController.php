<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\EmailService;
use App\Models\MemberModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class AuthController extends BaseController
{
    protected $emailService;
    protected $memberModel;

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->emailService = new EmailService();
        $this->memberModel = new MemberModel();
    }

    /**
     * Display the forgot password form
     */
    public function forgotPassword()
    {
        $data = [
            'title' => 'Forgot Password'
        ];

        return view('auth/forgot_password', $data);
    }

    /**
     * Process forgot password request
     */
    public function attemptForgot()
    {
        $rules = [
            'email' => 'required|valid_email',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = $this->request->getPost('email');

        // Check if member exists
        $member = $this->memberModel->where('email', $email)->first();

        if (!$member) {
            return redirect()->back()->withInput()->with('error', 'Email tidak ditemukan dalam sistem.');
        }

        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $resetLink = base_url('reset-password/' . $token);

        // Store token in database (you might want to create a reset_tokens table)
        // For now, we'll store it in session or use a simple approach
        session()->set('reset_token_' . $token, [
            'email' => $email,
            'expires' => time() + 3600 // 1 hour
        ]);

        // Send reset email
        if ($this->emailService->sendPasswordReset($email, $resetLink)) {
            return redirect()->back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Gagal mengirim email reset password. Silakan coba lagi.');
        }
    }

    /**
     * Display the password reset form
     */
    public function resetPassword($token = null)
    {
        if (!$token) {
            return redirect()->to('/')->with('error', 'Token reset password tidak valid.');
        }

        // Check if token exists and is valid
        $tokenData = session()->get('reset_token_' . $token);

        if (!$tokenData || $tokenData['expires'] < time()) {
            return redirect()->to('/')->with('error', 'Token reset password telah kadaluarsa.');
        }

        $data = [
            'title' => 'Reset Password',
            'token' => $token,
            'email' => $tokenData['email']
        ];

        return view('auth/reset_password', $data);
    }

    /**
     * Attempt to reset the user's password
     */
    public function attemptReset()
    {
        $rules = [
            'token' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $token = $this->request->getPost('token');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Verify token
        $tokenData = session()->get('reset_token_' . $token);

        if (!$tokenData || $tokenData['expires'] < time() || $tokenData['email'] !== $email) {
            return redirect()->back()->withInput()->with('error', 'Token reset password tidak valid atau telah kadaluarsa.');
        }

        // Update password
        $member = $this->memberModel->where('email', $email)->first();

        if (!$member) {
            return redirect()->back()->withInput()->with('error', 'Member tidak ditemukan.');
        }

        // Hash the new password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update password (assuming you have a password field)
        $this->memberModel->update($member['id'], ['password' => $hashedPassword]);

        // Remove the used token
        session()->remove('reset_token_' . $token);

        return redirect()->to('/')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
