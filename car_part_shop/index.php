<?php
session_start();

include('includes/header.php');
include('includes/left_content.php');

require_once('includes/helper_functions.php');

require_once('mysqli_connect.php'); //Σύνδεση με την βάση



if($_SESSION["cnt"] === $_SESSION["totalPrice"]){
    $_SESSION["cnt"] =0;
    $_SESSION["totalPrice"]=0; 
  }





$q_index = "SELECT proid, proname, price, quantity, images, show_product FROM products"

." ORDER BY show_product DESC, proid DESC LIMIT 6 ";

      //  . "LIMIT $start, $display";
   
    //Προετοιμάζω το statement
$stmt_index = my_mysqli_prepare($dbc, $q_index );
$result_index = mysqli_query($dbc, $q_index );
//Εκτελώ το statement
my_mysqli_stmt_execute($stmt_index);
//Με την παρακάτω εντολή φέρνω τα αποτελέσματα τοπικά ώσε να μην τα κταράω στη βάση
my_mysqli_stmt_store_result($stmt_index);
//μεταφέρω τα αντικείμενα από αυτά που θέλω να εξάγω
my_mysqli_stmt_bind_result($stmt_index, $pro_id, $pro_name, $pro_price, $pro_quantity, $images, $show_product);

print("<div class='center_title_bar'>Τελευταίες παραλαβές</div>\n");

require_once('mysqli_connect.php'); //Σύνδεση με την βάση
while (mysqli_stmt_fetch($stmt_index) ) {

    //Τα προιόντα δεν τα διαγράφω αλλά τα βάζω να εμφανίζονται και να εξαφανίζονται 
    if($show_product != 0){   

    $row_index = mysqli_fetch_array($result_index);
    ?>
<div class="prod_box">
        <div class="center_prod_box">
          <div class="product_title"><a href="single_product.php?action=add_single_product&id_single=<?php echo $row_index["proid"]; ?>"><?php echo $pro_name; ?></a></div>
          <div class="product_img"><a href="single_product.php?action=add_single_product&id_single=<?php echo $row_index["proid"]; ?>"><img src="image/<?php echo $images; ?>" alt="" width='75' height='75' border="0" /></a></div>
          <div class="prod_price"><span class="price"><?php echo $pro_price; ?> €</span></div>
        </div>
        <div class="prod_details_tab">  <a href="single_product.php?action=add_single_product&id_single=<?php echo $row_index["proid"]; ?>" class="prod_details">Λεπτομέριες</a> </div>
        
    </div>


<?php
    }//if

}//while

$rnd1 = rand(0,9);
    ?>

<div class='center_title_bar'>Προτάσεις GIA εσάς</div>
    <div class="oferta"> <img src="image/label1.jpg" width="165" height="113" border="0" class="oferta_img" alt="" />
        <div class="oferta_details">
          <div class="oferta_title">Ιμάντας Χρονισμού</div>
          <br><br>
          <div class="prod_details_tab">  <a href="single_product.php?action=add_single_product&id_single=5" class="prod_details">Λεπτομέριες</a> </div>
          <form method="post" action="single_product.php?action=add_single_product&id_single=5 ?>">

    </form>
      </div>
</div>

    
    
    
    
  
  <?php
  include('includes/right_content.php');
  include('includes/footer.php');
  ?>
