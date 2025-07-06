<!DOCTYPE html>
<html>
<head>
    <title>Verification Code for VoteSystem</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>Thank you for registering with VoteSystem. To complete your registration, please use the following verification code:</p>
    <h2>{{ $code }}</h2>
    <p>This code will expire in 30 minutes. If you did not register for VoteSystem, please ignore this email.</p>
    <p>Best regards,<br>VoteSystem Team</p>
</body>
</html>
