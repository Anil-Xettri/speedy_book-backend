<!DOCTYPE html>
<html>
<head>
    <title>speedy_book.com</title>
</head>
<body>
<h1>{{ $mail_details['subject'] }}</h1>
<p>Hi,</p>
<p style="margin-top: 20px">Please use the following One Time Password (OTP) to reset the password: <b>{{ $mail_details['body'] }}</b>. Do not share this OTP with anyone.</p>

<p>Thank you!</p>
</body>
</html>
