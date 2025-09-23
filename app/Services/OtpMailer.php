<?

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class OtpMailer {
    public function send(string $toEmail, string $toName, string $code): bool {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = env('OTP_SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = env('OTP_SMTP_USERNAME');
        $mail->Password = env('OTP_SMTP_PASSWORD');
        $mail->SMTPSecure = env('OTP_SMTP_ENCRYPTION', PHPMailer::ENCRYPTION_STARTTLS);
        $mail->Port = (int) env('OTP_SMTP_PORT', 587);

        $mail->setFrom(env('OTP_FROM_ADDRESS'), env('OTP_FROM_NAME','CyberCore'));
        $mail->addAddress($toEmail, $toName);

        $mail->isHTML(true);
        $mail->Subject = 'Your CyberCore verification code';
        $mail->Body = view('emails.verify-otp', ['code'=>$code])->render();

        return $mail->send();
    }
}
