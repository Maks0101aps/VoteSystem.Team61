<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Code</title>
</head>
<body style="font-family: Arial, sans-serif;">
    <h2>Hello, {{ $user->first_name }}!</h2>
    <p>We received a login attempt for your account. Use the code below to complete your login.</p>
    <p style="font-size: 24px; font-weight: bold; letter-spacing: 2px;">{{ $code }}</p>
    <p>This code will expire in 10 minutes.</p>
    <p>If you did not attempt to log in, please secure your account immediately.</p>
    <p>Thank you,</p>
    <p>The VoteSystem Team</p>
</body>
</html>
