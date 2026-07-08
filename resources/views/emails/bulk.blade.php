<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
    <div style="max-width:600px; margin:0 auto; font-family: Arial, sans-serif;">
        <h2 style="color: #1877F2;">MarketPlace Uganda</h2>
        <p>{!! nl2br(e($content)) !!}</p>
        <hr>
        <p style="color: #777; font-size: 12px;">This email was sent to you because you are a registered vendor.</p>
    </div>
</body>
</html>