document.addEventListener("DOMContentLoaded", function() {
  document.getElementById("checkoutForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita que el formulario se envíe automáticamente

    var method = document.querySelector('select[name="method"]').value; // Obtiene el método de pago seleccionado

    // Redirección según el método de pago seleccionado
    if (method === "cash on delivery") {
      // No hace falta redirigir para pago contra entrega
      alert("Su pedido se realizará con pago contra entrega.");
    } else if (method === "credit card") {
      // Redirige al formulario de tarjeta de crédito
      window.location.href = "public/checkout.html"; // Reemplaza "formulario_tarjeta.php" con la URL de tu formulario de tarjeta de crédito
    } else if (method === "paypal") {
      // Redirige a PayPal
      window.location.href = "formulario_paypal.php"; // Reemplaza "https://www.paypal.com" con la URL de PayPal
    }
  });
});
