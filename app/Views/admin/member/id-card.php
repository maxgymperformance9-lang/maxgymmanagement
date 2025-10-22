<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - <?= esc($member['nama_member']) ?></title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Oswald:wght@300;400;700&display=swap');

        body {
            font-family: 'Oswald', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #000;
            color: #FFD700;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .id-card {
            width: 338.58px; /* 8.56 cm in points */
            height: 213.54px; /* 5.4 cm in points */
            background-color: #000;
            border: 2px solid #FFD700;
            border-radius: 10px;
            padding: 15px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.3);
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }
        .logo-section {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .logo {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 1px solid #FFD700;
            object-fit: cover;
        }
        .company-name {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.1;
            max-width: 80px;
        }
        .card-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 14px;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #FFD700;
            text-shadow: 0.5px 0.5px 1px rgba(0,0,0,0.5);
            text-align: right;
            line-height: 1;
        }
        .main-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex: 1;
            margin-bottom: 8px;
        }
        .member-info {
            flex: 1;
        }
        .member-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 3px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .member-number {
            font-size: 10px;
            opacity: 0.9;
        }
        .qr-section {
            flex-shrink: 0;
        }
        .qr-code {
            width: 65px;
            height: 65px;
            border: 1px solid #FFD700;
            border-radius: 5px;
            padding: 2px;
            background-color: #fff;
        }
        .bottom-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }
        .valid-until {
            font-size: 8px;
            opacity: 0.8;
        }
        .instagram-section {
            display: flex;
            align-items: center;
            gap: 3px;
            font-size: 8px;
            opacity: 0.8;
        }
        .instagram-logo {
            width: 12px;
            height: 12px;
            filter: invert(1);
        }
    </style>
</head>
<body>
    <div class="id-card">
        <div class="header">
            <div class="logo-section">
                <img src="<?= esc($logo) ?>" alt="Logo" class="logo">
                <div class="company-name"><?= esc($companyName) ?></div>
            </div>
            <div class="card-title">
                MEMBER<br>
                CARD
            </div>
        </div>

        <div class="main-content">
            <div class="member-info">
                <div class="member-name"><?= esc($member['nama_member']) ?></div>
                <div class="member-number">No: <?= esc($member['no_member']) ?></div>
            </div>

            <div class="qr-section">
                <img src="<?= $qrCode ?>" alt="QR Code" class="qr-code">
            </div>
        </div>

        <div class="bottom-section">
            <div class="valid-until">
                Valid Until: <?= date('d/m/Y', strtotime($member['tanggal_expired'])) ?>
            </div>

            <div class="instagram-section">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/e/e7/Instagram_logo_2016.svg/2048px-Instagram_logo_2016.svg.png" alt="Instagram" class="instagram-logo">
                <span>@maxgym.performance</span>
            </div>
        </div>
    </div>
</body>
</html>
