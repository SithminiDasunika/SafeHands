<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendOTPEmail($recipientEmail, $otp)
{
    $mail = new PHPMailer(true);

    try {

        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;

        $mail->Username = 'safehandprojecte02@gmail.com';

        $mail->Password = 'joriauzjajdpotlp';

        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom(
            'safehandprojecte02@gmail.com',
            'SafeHands'
        );

        $mail->addAddress($recipientEmail);

        $mail->isHTML(true);

        $mail->Subject = 'Your SafeHands Password Reset OTP';

        $mail->Body = '
            <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 30px;">
                <h2 style="color: #004ac6;">SafeHands</h2>

                <p>Hello,</p>

                <p>
                    We received a request to reset your SafeHands password.
                </p>

                <div style="
                    background: #f0f3ff;
                    padding: 20px;
                    text-align: center;
                    border-radius: 10px;
                    margin: 25px 0;
                ">
                    <p>Your verification code is:</p>

                    <strong style="
                        font-size: 32px;
                        letter-spacing: 8px;
                        color: #004ac6;
                    ">
                        ' . htmlspecialchars($otp) . '
                    </strong>
                </div>

                <p>
                    This OTP will expire in <strong>10 minutes</strong>.
                </p>

                <p>
                    If you did not request a password reset,
                    you can safely ignore this email.
                </p>

                <p>
                    Regards,<br>
                    <strong>SafeHands Team</strong>
                </p>
            </div>
        ';

        $mail->AltBody =
            'Your SafeHands password reset OTP is: '
            . $otp
            . '. It expires in 10 minutes.';

        $mail->send();

        return true;

    } catch (Exception $e) {

        error_log(
            'SafeHands email error: ' . $mail->ErrorInfo
        );

        return false;
    }
}