</div>   


    
    <!-- end of center content -->

<div class="right_content">
      <div class="title_box">Αναζήτηση</div>
      <div class="border_box">


      <form  action="search.php" method="post">
  <input type="text" placeholder="Αναζήτηση..." name="searchText" class="newsletter_input" 
  value="<?php if (isset($search_name)) print($search_name);?>">
  <input type="submit" name="searchSubmit" value="Αναζήτηση"></button>
</form>


        <!--<input type="text" name="newsletter" class="newsletter_input" placeholder="Αναζήτηση..."/>
        <a href="search.php" class="join">Έυρεση</a> -->
    </div>
    <hr>
      <div class="shopping_cart">
        <div class="title_box">Καλάθι αγορών</div>
        <div class="cart_details"> <?php echo $_SESSION["cnt"]; ?> προϊόντα <br />
          <span class="border_cart"></span> Σύνολο: <span class="price" ><?php echo $_SESSION["totalPrice"];?> €</span> </div>
        <div class="cart_icon"><a href="shoping_cart2.php"><img src="image/shoppingcart.png" alt="" width="35" height="35" border="0" /></a></div>
      </div>
      
      <hr>
      <div class="title_box">Εισοδος Διαχειριστή</div>
      <ul class="left_menu">
      <?php   
      /*Εμφανίζει login - logout ανάλογα με το αν είμαι συνδεδεμένος ή όχι στην γραμμή εργαλείων */           
        if (isset($_SESSION['user_id']) &&(!strpos($_SERVER['PHP_SELF'], 'logout.php'))) {    
            print "<li class='odd'><a href='logout.php' >Logout</a><li>\n";
        } else {
            print "<li class='odd'><a href='login.php' >Login</a><li>\n";
        }?>
        
        
        <?php if (isset($_SESSION['user_id']) &&(!strpos($_SERVER['PHP_SELF'], 'logout.php'))) { ?>
        
           
           
            <li class="even"><a href="view_for_edit_products.php" >Επεξεργασία Προϊόντων</a></li>
            
            <li class="odd"><a href="view_orders.php" >Λίστα παραγγελιών</a></li>
            
            <li class="even"><a href="password.php" >Αλλαγή password</a></li>
            
        <?php } ?>

        <hr>

        <div class="title_box">ΗΟΤ Προϊόντα</div>
      <div class="border_box">
        <div class="product_title"><a href="single_product.php?action=add_single_product&id_single=1">Φίλτρο Λαδιού</a></div>
        <div class="product_img"><a href="single_product.php?action=add_single_product&id_single=1"><img src="image/oil1.jpg" width='70' height='70' alt="" border="0" /></a></div>
        <div class="prod_price"><span class="price">6.25 €</span></div>
      </div>
      <hr>
      <div class="title_box">Newsletter</div>
      <div class="border_box">
        <input type="text" name="newsletter" class="newsletter_input" value="your email"/>
        <a href="#" class="join">Εγγραφή</a> </div>
      
        <hr>
      <div class="banner_adds"> <a href="#"><img src="image/sachs_logo.jpg" alt="" width="150" height="150" border="0" /></a> </div>
    </div>
    <!-- end of right content -->
  </div>
  <!-- end of main content -->
  