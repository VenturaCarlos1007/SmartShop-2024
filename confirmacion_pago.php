<?php
session_start();

$message = [];

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];
    $message[] = '¡Pedido realizado con éxito! Su ID de pedido es: ' . $orderID;
} else {
    $message[] = 'Error en el pago. No se recibió ningún ID de pedido.';
}

$_SESSION['messages'] = $message; // Guarda el array de mensajes en la sesión
header('Location: checkout.php');
exit();
?>
