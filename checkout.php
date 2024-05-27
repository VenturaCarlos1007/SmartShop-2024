<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();
}

// Mostrar mensajes de la sesión
if (isset($_SESSION['messages'])) {
   $message = $_SESSION['messages'];
   unset($_SESSION['messages']); // Limpiar los mensajes después de mostrarlos
}

if (isset($_POST['order'])) {

   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $method = filter_var($_POST['method'], FILTER_SANITIZE_STRING);
   $address = 'Casa/piso No. ' . $_POST['flat'] . ', ' . $_POST['street'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, number, email, method, address, total_products, total_price) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $_SESSION['order_total'] = $total_price; // Guarda el total del pedido en una variable de sesión

      if ($method == 'paypal') {
         header('Location: formulario_paypal.php');
         exit();
      } else {
         $message[] = '¡Pedido realizado con éxito!';
      }

   } else {
      $message[] = 'Su carrito está vacío';
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Sus pedidos</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items[] = '';
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $cart_items[] = $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . ') - ';
               $total_products = implode($cart_items);
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= '$' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
         } else {
            echo '<p class="empty">Su carrito está vacío</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $total_products; ?>">
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Total general: <span>$<?= $grand_total; ?></span></div>
      </div>

      <h3>REALIZAR PEDIDOS</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Su nombre :</span>
            <input type="text" name="name" placeholder="Introduzca su nombre" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Número de teléfono:</span>
            <input type="number" name="number" placeholder="Introduzca su número" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Su correo electrónico :</span>
            <input type="email" name="email" placeholder="Introduzca su correo electrónico" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Seleccione un método de pago :</span>
            <select name="method" class="box" required>
               <option value="pago contra entrega">Pago contra entrega</option>
               <option value="paypal">PayPal</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Dirección línea 01 :</span>
            <input type="text" name="flat" placeholder="P. ej. Número de casa" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Dirección línea 02 :</span>
            <input type="text" name="street" placeholder="Nombre de la calle" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Ciudad :</span>
            <input type="text" name="city" placeholder="" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Departamento:</span>
            <input type="text" name="state" placeholder="" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>País :</span>
            <input type="text" name="country" placeholder="El Salvador" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Código postal :</span>
            <input type="number" min="0" name="pin_code" placeholder="E.j. 1601" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Realizar pedido">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

<?php
// Mostrar mensajes de la sesión
if (!empty($message)) {
   foreach ($message as $msg) {
      echo '<p class="message">' . $msg . '</p>';
   }
}
?>

</body>
</html>
