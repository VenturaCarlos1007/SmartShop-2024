<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

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

   <h1 class="heading">Pedidos realizados.</h1>

   <div class="box-container">

   <?php
      if($user_id == ''){
         echo '<p class="empty">por favor, inicie sesión para ver sus pedidos</p>';
      }else{
         $select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?");
         $select_orders->execute([$user_id]);
         if($select_orders->rowCount() > 0){
            while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p>Colocado el :<span><?= $fetch_orders['placed_on']; ?></span></p>
      <p>Nombre : <span><?= $fetch_orders['name']; ?></span></p>
      <p>Correo electrónico : <span><?= $fetch_orders['email']; ?></span></p>
      <p>Número de teléfono :<span><?= $fetch_orders['number']; ?></span></p>
      <p>Dirección :<span><?= $fetch_orders['address']; ?></span></p>
      <p>Forma de pago :<span><?= $fetch_orders['method']; ?></span></p>
      <p>Sus pedidos :<span><?= $fetch_orders['total_products']; ?></span></p>
      <p>Precio total :<span>$<?= $fetch_orders['total_price']; ?></span></p>
      <p>Estado del pago : <span style="color:<?php if($fetch_orders['payment_status'] == 'pendiente'){ echo 'red'; }else{ echo 'green'; }; ?>"><?= $fetch_orders['payment_status']; ?></span> </p>
   </div>
   <?php
      }
      }else{
         echo '<p class="empty">aún no se han realizado pedidos!</p>';
      }
      }
   ?>

   </div>
   
   <div class="box-container">
   <?php
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
   ?>
</div>



</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>