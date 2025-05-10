<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
        }
        .email-header img {
            width: 100px;
            margin-bottom: 10px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-body img {
            width: 150px;
            display: block;
            margin: 0 auto 20px;
        }
        .email-body p {
            line-height: 1.6;
            margin: 10px 0;
        }
        .email-body a {
            display: inline-block;
            margin: 20px 0;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .email-body a:hover {
            background-color: #45a049;
        }
        .email-footer {
            background-color: #f9f9f9;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Thông báo quên mật khẩu</h1>
        </div>
        <div class="email-body">
            <img src="https://lh3.googleusercontent.com/BqM2bF7Hr-IRcUS8vGgGTsjLZvb1qCiPVf0EO4-HZLJDMtj-slOCmx_FDY8HMHuUJDI" alt="Welcome Image">
            <p>Xin chào <strong>{{ $data['ho_va_ten'] }}</strong>,</p>
            <p>Bạn đã yêu cầu đặt lại mật khẩu. Vui lòng nhấn vào nút bên dưới để thực hiện:</p>
            <a href="{{ $data['link'] }}">Đặt lại mật khẩu</a>
            <p>Nếu bạn không yêu cầu điều này, hãy bỏ qua email này hoặc liên hệ với chúng tôi để được hỗ trợ.</p>
        </div>
    </div>
</body>
</html>
