<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>OTP Verification</title>
    <style>
        body {
            background: #f4f4f7;
            padding: 0;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 40px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 6px 20px rgba(0,0,0,0.10);
        }
        .title {
            text-align: center;
            color: #2c3e50;
            font-size: 24px;
            font-weight: bold;
        }
        .otp-box {
            text-align: center;
            background: #f0f8ff;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border-left: 5px solid #3498db;
        }
        .otp-box h1 {
            font-size: 38px;
            color: #3498db;
            letter-spacing: 5px;
            margin: 0;
        }
        .info {
            font-size: 16px;
            color: #444;
            line-height: 1.6;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 13px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="title">Your OTP Verification Code</div>

        <p class="info">Use the OTP below to verify your identity.  
        Please do not share this code with anyone.</p>

        <div class="otp-box">
            <h1>{{ $otp }}</h1>
        </div>

        <div class="footer">
            © {{ date('Y') }} Your Company. All rights reserved.
        </div>
    </div>

</body>
</html>
