<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Incluye la biblioteca PHPMailer
include 'components/connect.php'; // Incluye la conexión a la base de datos

session_start();

$message = [];

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
    $message[] = '¡Pedido realizado con éxito! Su ID de pedido es: ' . $orderID;

    // Obtener el correo electrónico del usuario
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $select_user = $conn->prepare("SELECT email, name FROM `users` WHERE id = ?");
        $select_user->execute([$user_id]);
        $user = $select_user->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            $email = $user['email'];
            $name = $user['name'];

            // Enviar correo de confirmación de pedido con PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuración del servidor SMTP
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP de Gmail
                $mail->SMTPAuth   = true;
                $mail->Username   = 'smartshopsv24@gmail.com'; // Tu dirección de correo de Gmail
                $mail->Password   = 'sehp qjua zmln xibs'; // Tu contraseña de Gmail
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                // Configuración de la codificación
                $mail->CharSet = 'UTF-8';

                // Destinatario y contenido del correo
                $mail->setFrom('smartshopsv24@gmail.com', 'SmartShop');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Confirmación de Pedido - SmartShop';
                $mail->Body    = '<html>
                                    <head>
                                        <title>Confirmación de Pedido</title>
                                        <style>
                                            body { font-family: Arial, sans-serif; }
                                            .container { padding: 20px; }
                                            .header { background: #2980b9; color: white; padding: 10px 0; text-align: center; }
                                            .content { margin-top: 20px; }
                                            .footer { margin-top: 20px; text-align: center; color: #888; }
                                        </style>
                                    </head>
                                    <body>
                                        <div class="container">
                                            <div class="header">
                                                <h1>Confirmación de Pedido</h1>
                                            </div>
                                            <div class="content">
                                                <p>Hola ' . htmlspecialchars($name) . ',</p>
                                                <p>Gracias por su compra. Su pedido ha sido realizado con éxito.</p>
                                                <p>Su ID de pedido es: ' . htmlspecialchars($orderID) . '</p>
                                                <p>Si tiene alguna pregunta, no dude en <a href="mailto:smartshopsv24@gmail.com">contactarnos</a>.</p>
                                                <p>¡Gracias por comprar en SmartShop!</p>
                                                <p>El equipo de SmartShop</p>
                                            </div>
                                            <div class="footer">
                                                <p>&copy; 2024 SmartShop. Todos los derechos reservados.</p>
                                            </div>
                                        </div>
                                    </body>
                                  </html>';

                $mail->send();
                $message[] = '¡Correo de confirmación enviado!';
            } catch (Exception $e) {
                $message[] = "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        } else {
            $message[] = 'Error al obtener los detalles del usuario.';
        }
    } else {
        $message[] = 'Usuario no identificado.';
    }
} else {
    $message[] = 'Error en el pago. No se recibió ningún ID de pedido.';
}

$_SESSION['messages'] = $message; // Guarda el array de mensajes en la sesión
header('Location: checkout.php');
exit();
?>
