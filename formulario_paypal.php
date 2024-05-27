<?php
session_start();
if (!isset($_SESSION['order_total'])) {
    header('Location: checkout.php');
    exit();
}
$orderTotal = $_SESSION['order_total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago con PayPal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f8f8;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .logo {
            display: block;
            margin: 0 auto 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        #paypal-button-container {
            text-align: center;
        }
    </style>
    <script src="https://www.paypal.com/sdk/js?client-id=AaAUF1R8TDWE0I_IVBRP5cNTCBkCO-FM8dV53jMD4Wr-nJYQcSTXbFdvWknhYel1AXMt0Vpd8xhHYvEL&currency=USD&locale=es_ES"></script>
</head>
<body>

<div class="container">
    <img src="images/logo.png" alt="SmartShop Logo" class="logo">
    <h1>Pago con PayPal</h1>

    <div id="paypal-button-container"></div>
</div>

<script>
    paypal.Buttons({
        createOrder: function(data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: '<?php echo $orderTotal; ?>' 
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            return actions.order.capture().then(function(details) {
                alert('Pago completado por ' + details.payer.name.given_name);
                // Redireccionar a confirmacion_pago.php con el orderID de PayPal
                window.location.href = 'http://localhost/SmartShop-2024/confirmacion_pago.php?orderID=' + data.orderID;
            });
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
