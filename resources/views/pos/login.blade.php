<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول - {{ $posPoint->name }}</title>
    <link href="{{ asset('assets/fonts/almarai/almarai.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Almarai', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #e2e8f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 48px 44px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .login-logo {
            height: 56px;
            margin-bottom: 28px;
        }

        .login-title {
            font-size: 28px;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .login-subtitle {
            font-size: 16px;
            color: #64748b;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 14px;
            font-size: 16px;
            font-family: 'Almarai', sans-serif;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            background: #fff;
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .btn-login {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border: none;
            border-radius: 14px;
            color: white;
            font-size: 18px;
            font-weight: 800;
            font-family: 'Almarai', sans-serif;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .pos-name-badge {
            text-align: center;
            margin-top: 36px;
            padding-top: 28px;
            border-top: 2px solid #f1f5f9;
        }

        .pos-name-badge span {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: #f8fafc;
            border-radius: 24px;
            font-size: 15px;
            font-weight: 700;
            color: #64748b;
        }

        .error-alert {
            background: #fef2f2;
            border: 2px solid #fecaca;
            border-radius: 14px;
            padding: 16px 20px;
            margin-bottom: 28px;
            color: #dc2626;
            font-size: 15px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .keyboard-hint {
            text-align: center;
            margin-top: 28px;
            font-size: 14px;
            color: #94a3b8;
        }

        .keyboard-hint kbd {
            padding: 4px 10px;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            font-family: 'Almarai', sans-serif;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('logo-dark.png') }}" alt="Logo" class="login-logo">
                <h1 class="login-title">تسجيل الدخول</h1>
                <p class="login-subtitle">أدخل بيانات الدخول للمتابعة</p>
            </div>

            @if($errors->any())
            <div class="error-alert">
                <i class="ti ti-alert-circle fs-20"></i>
                {{ $errors->first() }}
            </div>
            @endif

            <form action="{{ route('pos.login', $posPoint->slug) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text"
                           name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           value="{{ old('username') }}"
                           autofocus
                           required>
                </div>

                <div class="form-group">
                    <label class="form-label">كلمة المرور</label>
                    <input type="password"
                           name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           required>
                </div>

                <button type="submit" class="btn-login">
                    <i class="ti ti-login"></i>
                    دخول
                </button>
            </form>

            <div class="pos-name-badge">
                <span>
                    <i class="ti ti-point"></i>
                    {{ $posPoint->name }}
                </span>
            </div>

            <div class="keyboard-hint">
                <kbd>Enter</kbd> للدخول
            </div>
        </div>
    </div>
</body>
</html>
