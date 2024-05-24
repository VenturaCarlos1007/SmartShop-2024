<?php
session_start();

if (isset($_GET['orderID'])) {
    $orderID = $_GET['orderID'];

    // Aquí puedes procesar el pedido, actualizar la base de datos, enviar correos, etc.
    $_SESSION['message'] = '¡Pedido realizado con éxito! Su ID de pedido es: ' . $orderID;
    header('Location: checkout.php');
    exit();
} else {
    $_SESSION['message'] = 'Error en el pago. No se recibió ningún ID de pedido.';
    header('Location: checkout.php');
    exit();
}
