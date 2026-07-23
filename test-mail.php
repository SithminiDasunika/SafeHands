<?php

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $to = trim($_POST['email'] ?? '');

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";

    } else {

        $subject = "SafeHands Email Test";

        $body = "
        <html>
        <body style='font-family: Arial, sans-serif;'>

            <h2 style='color:#1E88E5;'>
                SafeHands Healthcare
            </h2>

            <p>
                Your SafeHands email system is working successfully.
            </p>

            <p>
                Test verification code:
                <strong>123456</strong>
            </p>

        </body>
        </html>
        ";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: SafeHands <noreply@safehands.local>\r\n";

        $sent = mail(
            $to,
            $subject,
            $body,
            $headers
        );

        if ($sent) {

            $message =
                "PHP accepted the email for sending. Check your inbox and spam folder.";

        } else {

            $message =
                "Email sending failed. PHP mail() is not configured correctly on this MAMP environment.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>SafeHands Mail Test</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f8fc;
            padding: 50px 20px;
        }

        .card {
            max-width: 500px;
            margin: auto;
            background: white;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        h1 {
            color: #1E88E5;
        }

        input {
            width: 100%;
            padding: 14px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px 0 20px;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #1E88E5;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .message {
            margin-top: 20px;
            padding: 15px;
            background: #eef5ff;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<div class="card">

    <h1>SafeHands Mail Test</h1>

    <p>
        Enter an email address that you can check.
    </p>

    <form method="POST">

        <input
            type="email"
            name="email"
            placeholder="your-email@gmail.com"
            required
        >

        <button type="submit">
            Send Test Email
        </button>

    </form>

    <?php if (!empty($message)): ?>

        <div class="message">

            <?= htmlspecialchars($message) ?>

        </div>

    <?php endif; ?>

</div>

</body>
</html>