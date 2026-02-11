<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نقطة البيع غير متاحة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Almarai:wght@300;400;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@2.44.0/tabler-icons.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Almarai', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .inactive-container {
            text-align: center;
            padding: 48px;
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            max-width: 480px;
            margin: 20px;
        }

        .inactive-logo {
            height: 48px;
            margin-bottom: 32px;
        }

        .inactive-icon {
            width: 100px;
            height: 100px;
            background: #fef2f2;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
        }

        .inactive-icon i {
            font-size: 48px;
            color: #ef4444;
        }

        .inactive-title {
            font-size: 26px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 14px;
        }

        .inactive-message {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .pos-name {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 24px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            color: #475569;
        }

        .pos-name i {
            color: #94a3b8;
        }
    </style>
</head>
<body>
    <div class="inactive-container">
        <img src="{{ asset('logo-dark.png') }}" alt="Logo" class="inactive-logo">
        <div class="inactive-icon">
            <i class="ti ti-device-desktop-off"></i>
        </div>
        <h1 class="inactive-title">نقطة البيع غير متاحة</h1>
        <p class="inactive-message">هذه النقطة متوقفة حالياً، يرجى التواصل مع الإدارة</p>
        <div class="pos-name">
            <i class="ti ti-point"></i>
            {{ $posPoint->name }}
        </div>
    </div>
</body>
</html>
