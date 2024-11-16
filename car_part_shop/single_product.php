<?php
session_start();
//$page_title = $pro_name;


include('includes/header.php');
include('includes/left_content.php');
require_once('includes/helper_functions.php');


require_once('mysqli_connect.php'); //Σύνδεση με την βάση


if (isset($_POST["add_from_single"])){
    if (isset($_SESSION["cart"])){
        $item_array_id = array_column($_SESSION["cart"],"product_id");
        if (!in_array($_GET["id_single"],$item_array_id)){
            $count = count($_SESSION["cart"]);
            $item_array = array(
                'product_id' => $_GET["id_single"],
                'item_name' => $_POST["hidden_name"],
                'product_price' => $_POST["hidden_price"],
                'item_quantity' => $_POST["quantity"],
            );
            $_SESSION["cart"][$count] = $item_array;
            
            //Αύξηση τιμών καλαθιού στο right_content
            $_SESSION["totalPrice"] = $_SESSION["totalPrice"] +$_POST["hidden_price"]*$_POST["quantity"];
            $_SESSION["cnt"] = $_SESSION["cnt"] +1;
            /*Στο παρακάτω script έχω προσθέσει το τέλος να λαμβάνει το id_single="+product_id διότι
            κάθε φορά που πατάω αν μπει το αντικείμενο στο καλάθι θέλω να εμφανίζεται η ίδια σελίδα
            με το ίδιο το αντικέιμενο. Ο τρόπος να το πετύχω εφόσον δεν έχω αφήσει την τιμή του σε 
            κάποιο session έιναι να ξανα δώσω την τιμή στο URL */
            echo '<script>window.location="single_product.php?action=add_from_single&id_single="+product_id</script>';
        }else{

            $error = 'Το προιόν βρίσκεται ήδη στο καλάθι, δοκιμάστε κάποιο άλλο.';
        print_message($error);
            //echo '<script>alert("Product is already Added to Cart")</script>';
            //echo '<script>window.location="single_product.php?action=add_from_single&id_single="+product_id</script>';
        }
    }else{
        $item_array = array(
            'product_id' => $_GET["id_single"],
            'item_name' => $_POST["hidden_name"],
            'product_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"],
        );
        $_SESSION["cart"][0] = $item_array;
        //Αύξηση τιμών καλαθιού στο right_content
        $_SESSION["totalPrice"] = $_SESSION["totalPrice"] + $_POST["hidden_price"]*$_POST["quantity"];
        $_SESSION["cnt"] = $_SESSION["cnt"] +1;

        
    }
    
    }

    

$q = "SELECT proid, proname, price, images, show_product FROM products"
. " WHERE proid = ? ";

      //  . "LIMIT $start, $display";
$id_for_single_product =   $_GET["id_single"];
    //Προετοιμάζω το statement
$stmt = my_mysqli_prepare($dbc, $q);
my_mysqli_stmt_bind_param($stmt, 'i', $id_for_single_product);
$result = mysqli_query($dbc, $q);
//Εκτελώ το statement
my_mysqli_stmt_execute($stmt);
//Με την παρακάτω εντολή φέρνω τα αποτελέσματα τοπικά ώσε να μην τα κταράω στη βάση
my_mysqli_stmt_store_result($stmt);
//μεταφέρω τα αντικείμενα από αυτά που θέλω να εξάγω
my_mysqli_stmt_bind_result($stmt, $pro_id, $pro_name, $pro_price, $images, $show_product);

while (mysqli_stmt_fetch($stmt) ) {

    //Τα προιόντα δεν τα διαγράφω αλλά τα βάζω να εμφανίζονται και να εξαφανίζονται 
    if($show_product != 0){   

   ?>

<!--<div class="specifications">-->
<div class="single_product">

<form method="post" action="single_product.php?action=add_from_single&id_single=<?php echo $pro_id; ?>">

<table>

    <tr>
        <td rowspan="7" align="right"><img src='image/<?php echo $images; ?>' width='200' height='200'></td>
    </tr>

    <tr>
        <td rowspan="7"  width='20'>  </td>
    </tr>
    
    
        <tr><td>Κωδικός Προιόντος : <span class="blue"> <?php echo $pro_id; ?> </span></td></tr>
        <tr><td>Κατηγορία : <span class="blue"> <?php echo $pro_name; ?> </span></td></tr>
        <tr><td>Τιμή : <span class="blue"> <?php echo $pro_price; ?> €</span></td></tr>
                                   
        <tr><td>Ποσότητα : <input type='text' name='quantity' size='1'  value='1'></td></tr>
        <tr><td><input type='submit' src='includes/cart.png' name='add_from_single' width='16' height='16' value='Προσθήκη στο καλάθι'></input></td></tr>
      
        
        <input type="hidden" name="hidden_name" value="<?php echo $pro_name; ?>">
        <input type="hidden" name="hidden_price" value="<?php echo $pro_price; ?>">
        
    
    </tr>
</table>
    
</form>
</div>
<?php
    }
}
mysqli_stmt_close($stmt);
mysqli_close($dbc);



include('includes/right_content.php');
                        include('includes/footer.php');
?>