<?php
   if(isset($message) && is_array($message)){
      foreach($message as $msg){
         echo '
         <div class="message">
            <span>'.$msg.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<header class="header">

   <section class="flex">

      <a href="index.php" class="logo">Smart<span>Shop</span></a>
      

      <nav class="navbar">
         <a href="index.php">Inicio</a>
         <a href="about.php">Sobre nosotros</a>
         <a href="orders.php">Pedidos</a>
         <a href="shop.php">Compra ahora</a>
         <a href="contact.php">Contáctanos</a>
         <div class="dropdown">
            <button class="dropbtn">Categorías <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content">
               <a href="category.php?category=laptop">Laptops</a>
               <a href="category.php?category=telefono">Smartphones</a>
               <a href="category.php?category=tv">Television</a>
               <a href="category.php?category=reloj">Relojes</a>
               <a href="category.php?category=mouse">Mouse</a>
               <a href="category.php?category=camara">Cámaras</a>
            </div>
         </div>
      </nav>

      <div class="icons">
         <?php
            $count_wishlist_items = $conn->prepare("SELECT * FROM `wishlist` WHERE user_id = ?");
            $count_wishlist_items->execute([$user_id]);
            $total_wishlist_counts = $count_wishlist_items->rowCount();

            $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $count_cart_items->execute([$user_id]);
            $total_cart_counts = $count_cart_items->rowCount();
         ?>
         <div id="menu-btn" class="fas fa-bars"></div>
         <a href="search_page.php"><i class="fas fa-search"></i>Buscar</a>
         <a href="wishlist.php"><i class="fas fa-heart"></i><span>(<?= $total_wishlist_counts; ?>)</span></a>
         <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_counts; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
      </div>

      <div class="profile">
         <?php          
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
         <p><?= $fetch_profile["name"]; ?></p>
         <a href="update_user.php" class="btn">Actualizar perfil</a>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">Registrarse</a>
            <a href="user_login.php" class="option-btn">Iniciar sesión</a>
         </div>
         <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('¿Cerrar sesión del sitio web?');">Cerrar sesión</a> 
         <?php
            }else{
         ?>
         <p>Inicie sesión o regístrese primero para continuar</p>
         <div class="flex-btn">
            <a href="user_register.php" class="option-btn">Registrarse</a>
            <a href="user_login.php" class="option-btn">Iniciar sesión</a>
         </div>
         <?php
            }
         ?>      
      </div>

   </section>

</header>

<style>
/* Estilos para el menú desplegable */
.dropdown {
   position: relative;
   display: inline-block;
}

.dropbtn {
   background-color: #2980b9;
   color: white;
   padding: 16px;
   font-size: 16px;
   border: none;
   cursor: pointer;
}

.dropbtn:hover, .dropbtn:focus {
   background-color: #003a61;
}

.dropdown-content {
   display: none;
   position: absolute;
   background-color: #f9f9f9;
   min-width: 160px;
   box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
   z-index: 1;
}

.dropdown-content a {
   color: black;
   padding: 12px 16px;
   text-decoration: none;
   display: block;
}

.dropdown-content a:hover {
   background-color: #f1f1f1;
}

.dropdown:hover .dropdown-content {
   display: block;
}



/* Estilos específicos para móviles */
@media (max-width: 768px) {

   .header .dropdown {
      width: 100%;
   }

   .header .dropdown .dropbtn {
      width: 100%;
   }

   .header .dropdown-content {
      position: static;
      width: 100%;
   }

   .header .icons {
      justify-content: space-between;
      width: 100%;
   }

   .header .profile {
      display: block;
      width: 100%;
      text-align: left;
      padding: 10px 20px;
   }

   .header .profile p {
      margin: 10px 0;
   }

   .header .profile .btn,
   .header .profile .option-btn,
   .header .profile .delete-btn {
      display: block;
      width: 100%;
      text-align: center;
      margin-bottom: 10px;
   }
}
</style>
