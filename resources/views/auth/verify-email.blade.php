<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>verifikasi email - KKN-GO</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo h1 {
            color: #3B82F6;
            font-size: 28px;
            margin: 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background-color: #3B82F6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            text-align: center;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2563EB;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        .info-box {
            background-color: #FEF3C7;
            border-left: 4px solid #F59E0B;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .info-box p {
            margin: 0;
            font-size: 14px;
            color: #92400E;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- logo -->
        <div class="logo">
            <h1>KKN-GO</h1>
        </div>

        <!-- greeting -->
        <div class="content">
            <h2 style="color: #111827; margin-top: 0;">halo, {{ $user->student ? $user->student->first_name : $user->institution->pic_name }}!</h2>
            
            <p>terima kasih telah mendaftar di KKN-GO. untuk melanjutkan, silakan verifikasi alamat email anda dengan mengklik tombol di bawah ini:</p>
            
            <!-- button verifikasi -->
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="button">
                    verifikasi email saya
                </a>
            </div>
            
            <!-- info box -->
            <div class="info-box">
                <p><strong>catatan penting:</strong> link verifikasi ini akan kadaluarsa dalam {{ $expiresIn }} menit.</p>
            </div>
            
            <!-- fallback link -->
            <p style="font-size: 14px; color: #6b7280;">
                jika tombol tidak berfungsi, salin dan tempel link berikut ke browser anda:
            </p>
            <p style="font-size: 12px; word-break: break-all; color: #3B82F6;">
                {{ $verificationUrl }}
            </p>
        </div>

        <!-- footer -->
        <div class="footer">
            <p>email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
            <p>jika anda tidak mendaftar di KKN-GO, abaikan email ini.</p>
            <p style="margin-top: 15px;">
                &copy; {{ date('Y') }} KKN-GO. all rights reserved.
            </p>
        </div>
    </div>
</body>
</html>