<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - KKN-GO</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji'; 
            background-color: #f7fafc; 
            margin: 0; 
            padding: 0; 
        }
        .container { 
            max-width: 600px; 
            margin: 40px auto; 
            background-color: #ffffff; 
            border-radius: 8px; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.1); 
            overflow: hidden; 
        }
        .header { 
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
            color: #ffffff; 
            padding: 30px 20px; 
            text-align: center; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 28px; 
            font-weight: 700;
        }
        .content { 
            padding: 30px; 
            color: #374151; 
            line-height: 1.6; 
        }
        .content p { 
            margin: 0 0 1.5em; 
        }
        .button-container { 
            text-align: center; 
            margin: 30px 0; 
        }
        .button { 
            display: inline-block; 
            padding: 14px 32px; 
            background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%);
            color: #ffffff !important; 
            text-decoration: none; 
            border-radius: 8px; 
            font-weight: 600;
            font-size: 16px;
            transition: transform 0.2s;
        }
        .button:hover {
            transform: translateY(-2px);
        }
        .footer { 
            text-align: center; 
            padding: 20px; 
            font-size: 12px; 
            color: #718096; 
            background-color: #edf2f7; 
        }
        .link { 
            word-break: break-all; 
            color: #3b82f6; 
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px 16px;
            border-radius: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>KKN-GO</h1>
            <p style="margin: 8px 0 0; font-size: 14px; opacity: 0.9;">platform kolaborasi KKN Indonesia</p>
        </div>
        <div class="content">
            <p>Halo, <strong>{{ $user->name }}</strong>!</p>
            <p>Terima kasih telah mendaftar di KKN-GO. Hanya satu langkah lagi untuk mengaktifkan akun Anda. Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.</p>
            
            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="button">verifikasi email saya</a>
            </div>
            
            <p style="font-size: 14px; color: #718096;">Jika tombol tidak berfungsi, salin dan tempel URL berikut ke browser Anda:</p>
            <p style="font-size: 12px;"><a href="{{ $verificationUrl }}" class="link">{{ $verificationUrl }}</a></p>
            
            <div class="warning">
                <strong>⚠️ Penting:</strong> Link verifikasi ini akan kedaluwarsa dalam {{ $expiresIn }} menit.
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} KKN-GO. Semua Hak Cipta Dilindungi.</p>
            <p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>
        </div>
    </div>
</body>
</html>