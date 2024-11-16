<?php
    session_start();
    require_once('includes/helper_functions.php');
    include('includes/header.php');
    include('includes/left_content.php');
    
    require_once('mysqli_connect.php');

    /*Μηδενίζεται εδώ ώστε αν δεν εισαχθεί κάποιο προιόν να μην εμφανιστεί η 
    δήλωση των στοιχείων */
    $total = 0; 
    $cnt = 0;
    $value[] = 0;
    //Αδειάζει κάθε προιόν ανάλογα με το ποιό θα επιλέξω
    if (isset($_GET["action"])){
        if ($_GET["action"] == "delete"){
            foreach ($_SESSION["cart"] as $keys => $value){
                if ($value["product_id"] == $_GET["id"]){
                    unset($_SESSION["cart"][$keys]);
                    //echo '<script>alert("Product has been Removed...!")</script>';
                    echo '<script>window.location="shoping_cart2.php"</script>';
                }
            }
        }
    }

    //Άδεισμα όλου του καλαθιού
     if (isset($_GET["action"])){
        if ($_GET["action"] == "deleteAll"){
            foreach ($_SESSION["cart"] as $keys => $value){
                
                    unset($_SESSION["cart"][$keys]);
                    //echo '<script>alert("All product has been Removed...!")</script>';
                    echo '<script>window.location="shoping_cart2.php"</script>';
                
            }
        }
    }


//Έλεγχος δήλωσης των στοιχείων
if (filter_input(INPUT_POST, 'submit')) {
    $errors = array();
    if (!$first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε όνομα';
    }
    if (!$last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε επώνυμο';
    }
    if ($email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL)) {
        $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    if (!$email) {
        $errors[] = 'Ξεχάσατε να δηλώσετε έγκυρο email';
    }



//Εάν δεν εμφανιστεί κάποι σφάλμα τότε περνάμε στην καταχώρηση της παραγγελίας
if (!empty($errors)) {
    print_error_messages($errors);
} else {
    
    foreach ($_SESSION["cart"] as $keys => $value){
    $pro_id = $value["product_id"];
    $pro_name = $value["item_name"];
    $pro_quant = $value["item_quantity"];
    $pro_price = $value["product_price"];
    $pro_finalprice = $value["item_quantity"] * $value["product_price"];


    $q = "INSERT INTO orders (pro_id, pro_name, pro_quant, pro_price, pro_finalprice, ord_date, lastname, firstname, email) "
            . "VALUES(?, ?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = my_mysqli_prepare($dbc, $q);
    my_mysqli_stmt_bind_param($stmt, 'isiddsss',
    $pro_id,
    $pro_name, 
    $pro_quant, 
    $pro_price,
    $pro_finalprice,
    $last_name, $first_name, $email);
    my_mysqli_stmt_execute($stmt);
    }
    if (mysqli_stmt_affected_rows($stmt) == 1) {
        print "<h1 style='color:green'>Ολοκλήρωση παραγγελίας</h1>\n";
        
    }
    mysqli_stmt_close($stmt);
    mysqli_close($dbc);
    include('includes/right_content.php');
    include('includes/footer.php>');
    exit();
}
}
    
?>
        <div class="center_title_bar">Στοιχεία παραγγελίας</div>
        
        <div class="prod_box_big">
        
            <table>
            <tr>
                <th width="10%">Κωδικός</th>
                <th width="30%">Όνομα προϊόντος</th>
                <th width="10%">Ποσότητα</th>
                <th width="13%">Τιμή</th>
                <th width="10%">Συνολική τιμή</th>
                <th width="17%">Διαγραφή από το καλάθι</th>
            </tr>
            <tr height="10" ></tr>

            <?php

                if(!empty($_SESSION["cart"])){
                    $total = 0;
                    $cnt = 0;
                    foreach ($_SESSION["cart"] as $key => $value) {
                        ?>
                        
                        <tr>
                        
                            <td style='text-align:center'><?php echo $value["product_id"]; ?></td>
                            <td style='text-align:center'><?php echo $value["item_name"]; ?></td>
                            <td style='text-align:center'><?php echo $value["item_quantity"]; ?></td>
                            <td style='text-align:center'> <?php echo $value["product_price"]; ?> €</td>
                            <td style='text-align:center'>
                                <?php echo number_format($value["item_quantity"] * $value["product_price"], 2); ?> €</td>
                            <td style='text-align:center'><a href="shoping_cart2.php?action=delete&id=<?php echo $value["product_id"]; ?>">
                            <img src='includes/del.png' width='16' height='16' alt="Διαγραφή από το καλάθι"></a></td>
                            
                                   
                        </tr>
                    
                        <?php
                        $total = $total + ($value["item_quantity"] * $value["product_price"]);
                        
                        $cnt = $cnt +1;
                        
                    }//foreach
                    
                        ?>
                        <tr height="15" ></tr>
                        <tr>
                        <td ></td>
                            <td colspan="3"  style='text-align:center'><b>Συνολική αξία παραγγελίας : </b></td>
                            <th  style='text-align:center'><?php echo number_format($total, 2); ?> €</th>
                            <td  style='text-align:center'><a href="shoping_cart2.php?action=deleteAll"><span
                                        class="text-danger" ><b>Άδειασμα όλου του καλάθιου</b></span></a></td>
                        </tr>
                        
                        <?php
                    }//if
                ?>
            </table>
        </div>
                
    <hr>
       

<?php
/*Αυτά τα session μετράνε και ανανεώνουν την ένδειξη στο καλάθι όταν 
μπαίνω στο καλάθι ή όταν είμαι στο καλάθι και ενημερώνω κάτι
Για να γίνεται ενημέρωση και όταν τροσθέτω κάτι τότε θα ενημερώνω 
αυτά τα session κι όταν εισάγω ενα προιόν στο σημείο που το εισάγω. */
$_SESSION["totalPrice"] = $total;
$_SESSION["cnt"] = $cnt;
if ($total>0){
?>
    <div class="center_title_bar">Δήλωση στοιχείων παραγγελίας</div>
<br>
<div class="specifications">
    
    <form action="shoping_cart2.php" method="post">
        <p>Όνομα: <input type="text" name="first_name" size="15" maxlength="20"
                     value="<?php if (isset($first_name)) echo $first_name; ?>"></p>
        <p>Επώνυμο: <input type="text" name="last_name" size="20" maxlength="40"
                       value="<?php if (isset($last_name)) echo $last_name; ?>"></p>
        <p>Email: <input type="email" name="email" size="20" maxlength="40"
                     value="<?php if (isset($email)) echo $email; ?>"></p>
    
    
        <p><input type="submit" name="submit" value="Εκτέλεση παραγγελίας"></p>
    </form>


</div>
<?php }//if ?>
    

    
<?php
include('includes/right_content.php');
include('includes/footer.php>');
?>
