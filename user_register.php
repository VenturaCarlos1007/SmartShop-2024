<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Incluye la biblioteca PHPMailer

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
}

if(isset($_POST['submit'])){

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $pass = filter_var(sha1($_POST['pass']), FILTER_SANITIZE_STRING);
   $cpass = filter_var(sha1($_POST['cpass']), FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
   $select_user->execute([$email]);

   if($select_user->rowCount() > 0){
      $message[] = '¡El correo electrónico ya existe!';
   } else {
      if($pass != $cpass){
         $message[] = '¡Confirmar contraseña no coincide!';
      } else {
         $insert_user = $conn->prepare("INSERT INTO `users`(name, email, password) VALUES(?,?,?)");
         $insert_user->execute([$name, $email, $cpass]);
         $message[] = 'Registrado con éxito, ¡inicie sesión ahora por favor!';
         
         // Enviar correo de bienvenida con PHPMailer
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

            // Destinatario y contenido del correo
            $mail->setFrom('smartshopsv24@gmail.com', 'SmartShop');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = 'Bienvenido a SmartShop';
            $mail->Body    = '<html>
                                <head>
                                    <title>Bienvenido a SmartShop</title>
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
                                            <h1>¡Bienvenido a SmartShop!</h1>
                                        </div>
                                        <div class="content">
                                            <p>Hola ' . htmlspecialchars($name) . ',</p>
                                            <p>Gracias por registrarte en SmartShop. Estamos encantados de tenerte con nosotros.</p>
                                            <p>Si tienes alguna pregunta, no dudes en <a href="mailto:smartshopsv24@gmail.com">contactarnos</a>.</p>
                                            <p>¡Feliz compra!</p>
                                            <p>El equipo de SmartShop</p>
                                        </div>
                                        <div class="footer">
                                            <p>&copy; 2024 SmartShop. Todos los derechos reservados.</p>
                                        </div>
                                    </div>
                                </body>
                              </html>';

            $mail->send();
            $message[] = '¡Se le ha enviado un correo de bienvenida!';
         } catch (Exception $e) {
            $message[] = "Error al enviar el correo: {$mail->ErrorInfo}";
         }
      }
   }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Registrarse</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'components/user_header.php'; ?>
<section class="form-container">
   <form action="" method="post">
      <h3>Regístrese ahora.</h3>
      <?php
      if(isset($message)){
         foreach($message as $msg){
            echo '<p class="error-message">' . htmlspecialchars($msg) . '</p>';
         }
      }
      ?>
      <input type="text" name="name" required placeholder="Introduzca su nombre de usuario" maxlength="20" class="box">
      <input type="email" name="email" required placeholder="Introduzca su correo electrónico" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Introduzca su contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirme su contraseña" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Regístrese ahora" class="btn" name="submit">
      <p>¿Ya tiene una cuenta?</p>
      <a href="user_login.php" class="option-btn">Inicie sesión</a>
   </form>
</section>
<?php include 'components/footer.php'; ?>
<script src="js/script.js"></script>
</body>
</html>
