<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Chào Mừng Quý Khách</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;
            background-color: #FAFAFA;
            color: #333333;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            border: 1px solid #eaeaea;
        }

        .email-header {
            padding: 40px 20px;
            text-align: center;
            background: linear-gradient(135deg, #6A82FB, #FC5C7D);
            color: #ffffff;
        }

        .email-header img {
            max-width: 100px;
            margin-bottom: 20px;
        }

        .email-header h1 {
            font-size: 28px;
            margin: 0;
        }

        .email-body {
            padding: 30px 20px;
        }

        .email-body h4 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #4A4A4A;
        }

        .email-body p {
            font-size: 16px;
            line-height: 1.8;
            color: #5A5A5A;
        }

        .email-cta {
            text-align: center;
            margin: 30px 0;
        }

        .email-cta a {
            display: inline-block;
            background-color: #6A82FB;
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            padding: 14px 28px;
            border-radius: 6px;
            box-shadow: 0 4px 10px rgba(106, 130, 251, 0.3);
            transition: all 0.3s ease;
        }

        .email-cta a:hover {
            background-color: #5C68E2;
            box-shadow: 0 6px 15px rgba(92, 104, 226, 0.4);
        }

        .email-footer {
            padding: 20px;
            font-size: 12px;
            text-align: center;
            color: #999999;
            background-color: #f8f8f8;
            border-top: 1px solid #eaeaea;
        }

        .email-footer a {
            color: #6A82FB;
            text-decoration: none;
            margin: 0 5px;
        }

        .email-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .email-container {
                width: 100%;
                border-radius: 0;
            }

            .email-header h1 {
                font-size: 22px;
            }

            .email-body h4 {
                font-size: 20px;
            }

            .email-body p {
                font-size: 14px;
            }

            .email-cta a {
                font-size: 16px;
                padding: 12px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <img src="https://lh3.googleusercontent.com/BqM2bF7Hr-IRcUS8vGgGTsjLZvb1qCiPVf0EO4-HZLJDMtj-slOCmx_FDY8HMHuUJDI" alt="Welcome Image">
            <h1>Chào Mừng Quý Khách</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <h4>Xin chào, {{ $data['ho_va_ten'] }}</h4>
            <p>Cảm ơn bạn đã đăng ký tại website của chúng tôi. Chúng tôi rất vui mừng được đồng hành cùng bạn.</p>

            <!-- Call-to-action -->
            <div class="email-cta">
                <a href="{{ $data['link_kich_hoat'] }}" target="_blank">Kích Hoạt Tài Khoản</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
        </div>
    </div>
</body>
</html>
