<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - MaxGym Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .forgot-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .logo i {
            font-size: 36px;
            color: white;
        }

        .logo-section h1 {
            color: #333;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .logo-section p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5e9;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-submit {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        .info-text {
            background-color: #e7f3ff;
            color: #0066cc;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
            border-left: 4px solid #0066cc;
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="logo-section">
            <div class="logo">
                <i class="material-icons">fitness_center</i>
            </div>
            <h1>Forgot Password</h1>
            <p>Enter your email to reset your password</p>
        </div>

        <div class="info-text">
            <i class="material-icons" style="vertical-align: middle; margin-right: 8px; font-size: 16px;">info</i>
            We'll send a password reset link to your email address.
        </div>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach ?>
                </ul>
            </div>
        <?php endif ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-danger">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('success')): ?>
            <div class="alert alert-success">
                <?= session('success') ?>
            </div>
        <?php endif ?>

        <form action="<?= base_url('forgot-password') ?>" method="post" id="forgotForm">
            <?= csrf_field() ?>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required
                       placeholder="Enter your registered email address">
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">send</i>
                Send Reset Link
            </button>
        </form>

        <div class="back-link">
            <a href="<?= base_url('/') ?>">
                <i class="material-icons" style="vertical-align: middle; font-size: 16px; margin-right: 5px;">arrow_back</i>
                Back to Login
            </a>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            const email = document.getElementById('email').value;
            const submitBtn = document.getElementById('submitBtn');

            if (!email) {
                e.preventDefault();
                alert('Please enter your email address.');
                return false;
            }

            // Basic email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                alert('Please enter a valid email address.');
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="material-icons" style="vertical-align: middle; margin-right: 8px;">hourglass_empty</i>Sending...';
        });
    </script>
</body>
</html>
