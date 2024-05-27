<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Pedidos</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="orders">

   <h1 class="heading">Pedidos realizados</h1>

   <div class="box-container">

   <?php
      if ($user_id == '') {
         echo '<p class="empty">Por favor, inicie sesión para ver sus pedidos</p>';
      } else {
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if ($select_orders->rowCount() > 0) {
            while ($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)) {
   ?>
   <div class="box">
      <p>Colocado el día:<span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
      <p>Nombre : <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
      <p>Correo electrónico : <span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
      <p>Número de teléfono :<span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
      <p>Dirección :<span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
      <p>Forma de pago :<span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
      <p>Sus pedidos :<span><?= htmlspecialchars($fetch_orders['total_products']); ?></span></p>
      <p>Precio total :<span>$<?= htmlspecialchars($fetch_orders['total_price']); ?></span></p>
      <p>Estado del pago : <span style="color:<?php if ($fetch_orders['payment_status'] == 'pendiente') { echo 'red'; } else { echo 'green'; }; ?>"><?= htmlspecialchars($fetch_orders['payment_status']); ?></span> </p>
   </div>
   <?php
            }
         } else {
            echo '<p class="empty">¡Aún no se han realizado pedidos!</p>';
         }
      }
   ?>

   </div>
   
   <?php
      if ($user_id != '' && $select_orders->rowCount() > 0) {
         echo '<div class="box-container">';
         echo '<style>';
         echo '.box-container {';
         echo '    margin-top: 50px;';
         echo '    display: flex;';
         echo '    justify-content: center;';
         echo '    align-items: center;';
         echo '}';
         echo '</style>';

         echo '<style>';
         echo '.download-btn {';
         echo '    display: inline-block;';
         echo '    padding: 10px 20px;';
         echo '    background-color: #4CAF50;';
         echo '    color: #fff;';
         echo '    text-decoration: none;';
         echo '    border-radius: 5px;';
         echo '    margin-right: 10px;';
         echo '    font-size: 16px;';
         echo '}';
         echo '.download-btn:hover {';
         echo '    background-color: #45a049;';
         echo '}';
         echo '</style>';

         echo '<a href="download.php?format=pdf" class="download-btn">Descargar PDF</a>';

         echo '<a href="download.php?format=csv" class="download-btn">Descargar CSV</a>';
         echo '</div>';
      }
   ?>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
