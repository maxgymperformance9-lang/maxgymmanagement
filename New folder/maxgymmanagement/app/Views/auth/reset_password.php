<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - MaxGym Management</title>
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

        .reset-container {
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

        .btn-reset {
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

        .btn-reset:hover {
            transform: translateY(-2px);
        }

        .btn-reset:disabled {
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

        .password-strength {
            margin-top: 5px;
            font-size: 12px;
        }

        .strength-weak { color: #dc3545; }
        .strength-medium { color: #ffc107; }
        .strength-strong { color: #28a745; }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="logo-section">
            <div class="logo">
                <i class="material-icons">fitness_center</i>
            </div>
            <h1>Reset Password</h1>
            <p>Enter your new password below</p>
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

        <?php if (session()->has('message')): ?>
            <div class="alert alert-success">
                <?= session('message') ?>
            </div>
        <?php endif ?>

        <form action="<?= base_url('reset-password') ?>" method="post" id="resetForm">
            <?= csrf_field() ?>

            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required
                       placeholder="Enter your email address">
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required
                       placeholder="Enter new password" minlength="8">
                <div class="password-strength" id="passwordStrength"></div>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirm New Password</label>
                <input type="password" id="password_confirm" name="password_confirm" required
                       placeholder="Confirm new password">
            </div>

            <button type="submit" class="btn-reset" id="submitBtn">
                <i class="material-icons" style="vertical-align: middle; margin-right: 8px;">lock_reset</i>
                Reset Password
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
        // Password strength indicator
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthIndicator = document.getElementById('passwordStrength');
            const submitBtn = document.getElementById('submitBtn');

            let strength = 0;
            let feedback = [];

            if (password.length >= 8) strength++;
            else feedback.push('At least 8 characters');

            if (/[a-z]/.test(password)) strength++;
            else feedback.push('Lowercase letter');

            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('Uppercase letter');

            if (/[0-9]/.test(password)) strength++;
            else feedback.push('Number');

            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('Special character');

            if (strength < 3) {
                strengthIndicator.className = 'password-strength strength-weak';
                strengthIndicator.textContent = 'Weak password: ' + feedback.join(', ');
                submitBtn.disabled = true;
            } else if (strength < 4) {
                strengthIndicator.className = 'password-strength strength-medium';
                strengthIndicator.textContent = 'Medium strength';
                submitBtn.disabled = false;
            } else {
                strengthIndicator.className = 'password-strength strength-strong';
                strengthIndicator.textContent = 'Strong password';
                submitBtn.disabled = false;
            }
        });

        // Form validation
        document.getElementById('resetForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirm').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
        });
    </script>
</body>
</html>
