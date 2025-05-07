<?php
session_start();
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Correo electrónico no válido'); window.history.back();</script>";
        exit;
    }

    $pdfPath = $_SESSION['comprobante_pdf'] ?? '';

    if (!$pdfPath || !file_exists($pdfPath)) {
        echo "<script>alert('No se encontró el comprobante para enviar.'); window.history.back();</script>";
        exit;
    }

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'omartinezlucero26@gmail.com';  
        $mail->Password = 'kezviwqtbruqazgo';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('tu_email@gmail.com', 'GameVault');
        $mail->addAddress($email); 

        $mail->isHTML(true);
        $mail->Subject = '!Tu Compra en GameVault ha sido un exito! - Comprobante de Compra - GameVault ';
        $mail->Body    = '¡Felicidades! 🎉 Gracias por confiar en nosotros. Tu compra se ha realizado con éxito, y como muestra de agradecimiento, adjuntamos tu comprobante de compra. ¡Esperamos que disfrutes de tu experiencia en GameVault y sigas siendo parte de nuestra comunidad!';

        $mail->addAttachment($pdfPath);
        $mail->send();
        header("Location: comprobante.php?enviado=1");
        exit;
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}
?>
