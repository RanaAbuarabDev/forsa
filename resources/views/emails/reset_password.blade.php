<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إعادة تعيين كلمة المرور</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #3A63ED;
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>إعادة تعيين كلمة المرور</h2>
        <p>عزيزي المستخدم،</p>
        <p>لقد طلبت إعادة تعيين كلمة المرور الخاصة بك. يرجى استخدام رمز التحقق أدناه:</p>
        
        <div class="code">{{ $otp }}</div>
        
        <p>إذا لم تطلب إعادة تعيين كلمة المرور، يمكنك تجاهل هذا البريد الإلكتروني.</p>

        <div class="footer">
            <p>شكراً لاستخدامك تطبيقنا! 💙</p>
        </div>
    </div>
</body>
</html>
