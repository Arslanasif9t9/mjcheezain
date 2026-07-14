<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — MJ Cheezain</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f6f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            max-width: 560px;
            width: 100%;
            padding: 48px 32px;
            text-align: center;
        }
        .error-card img.logo {
            max-height: 56px;
            margin-bottom: 24px;
        }
        .error-code {
            font-size: 72px;
            font-weight: 800;
            line-height: 1;
            color: #1f2937;
            margin-bottom: 8px;
        }
        .error-title {
            font-size: 22px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 12px;
        }
        .error-reason {
            font-size: 15px;
            color: #4b5563;
            line-height: 1.6;
            background: #fef3e7;
            border: 1px solid #f5d3a8;
            border-radius: 10px;
            padding: 14px 16px;
            margin: 0 0 20px;
            text-align: left;
        }
        .error-message {
            font-size: 15px;
            color: #6b7280;
            line-height: 1.6;
            margin-bottom: 28px;
        }
        .error-reference {
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 24px;
        }
        .btn-home {
            display: inline-block;
            background: #1f2937;
            color: #fff;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            padding: 12px 32px;
            border-radius: 10px;
            transition: background 0.2s;
        }
        .btn-home:hover { background: #374151; }
        .btn-secondary {
            display: inline-block;
            color: #1f2937;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 12px 20px;
        }
        @media (max-width: 480px) {
            .error-card { padding: 36px 20px; }
            .error-code { font-size: 56px; }
        }
    </style>
</head>
<body>
    <div class="error-card">
        <img src="{{ asset('img/logo-html.png') }}" alt="MJ Cheezain" class="logo" onerror="this.style.display='none'">
        <div class="error-code">@yield('code')</div>
        <div class="error-title">@yield('title')</div>
        @hasSection('reason')
            <div class="error-reason">@yield('reason')</div>
        @endif
        <div class="error-message">@yield('message')</div>
        @hasSection('reference')
            <div class="error-reference">Reference: @yield('reference')</div>
        @endif
        <a href="{{ url('/') }}" class="btn-home">Go to Homepage</a>
        <a href="javascript:history.back()" class="btn-secondary">Go Back</a>
    </div>
</body>
</html>
