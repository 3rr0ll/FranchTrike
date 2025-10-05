<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 Forbidden | FranchTrike</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Reset and base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            color: #1a237e;
        }

        .error-container {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 30px rgba(26, 35, 126, 0.1);
            padding: 40px 32px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        .error-code {
            font-size: 120px;
            font-weight: 800;
            color: #1a237e;
            letter-spacing: 3px;
            margin-bottom: 10px;
            text-shadow: 2px 2px 0 #fbc02d;
            animation: bounceIn 0.8s ease-out;
        }

        .error-title {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            margin-bottom: 10px;
        }

        .error-message {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .home-btn {
            display: inline-block;
            background-color: #1a237e;
            color: #fff;
            padding: 12px 32px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .home-btn:hover {
            background-color: #fbc02d;
            color: #1a237e;
            transform: translateY(-2px);
        }

        @media (max-width: 600px) {
            .error-container {
                padding: 28px 16px;
            }
            .error-code {
                font-size: 80px;
            }
            .error-title {
                font-size: 22px;
            }
            .error-message {
                font-size: 15px;
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes bounceIn {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.05); opacity: 1; }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body style="background: url('{{ asset('images/login_bg.jpg') }}') no-repeat center center fixed; background-size: cover;">
    <div class="error-container" style="background: rgba(255,255,255,0.92); border-radius: 16px;">
        <div style="display: flex; justify-content: center; margin-bottom: 24px;">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height: 70px; width: auto;">
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Access Forbidden</div>
        <div class="error-message">
            Sorry, you do not have permission to access this page.<br>
            If you believe this is an error, please contact the administrator or go back to the homepage.
        </div>
        <a href="{{ route('landing') }}" class="home-btn">Go to Home</a>
    </div>
</body>
</html>
