<?php
session_start();
$page_title = 'Αναζήτηση προιόντων';


include('includes/header.php');
include('includes/left_content.php');
require_once('includes/helper_functions.php');

print("<h1 class='center_title_bar'>Αναζήτηση προιόντων</h1>\n");
require_once('mysqli_connect.php'); //Σύνδεση με την βάση




if (isset($_POST["add"])){
if (isset($_SESSION["cart"])){
    $item_array_id = array_column($_SESSION["cart"],"product_id");
    if (!in_array($_GET["id"],$item_array_id)){
        $count = count($_SESSION["cart"]);
        $item_array = array(
            'product_id' => $_GET["id"],
            'item_name' => $_POST["hidden_name"],
            'product_price' => $_POST["hidden_price"],
            'item_quantity' => $_POST["quantity"],
            
        );
        $_SESSION["cart"][$count] = $item_array;
        $_SESSION["totalPrice"] = $_SESSION["totalPrice"] +$_POST["hidden_price"];
        $_SESSION["cnt"] = $_SESSION["cnt"] +1;
        echo '<script>window.location="search.php"</script>';
    }else{
        $error = 'Το προϊόν βρίσκεται ήδη στο καλάθι, δοκιμάστε κάποιο άλλο.';
        print_message($error);
       // echo '<script>alert("Product is already Added to Cart")</script>';
        //echo '<script>window.location="search.php"</script>';
    }
}else{
    $item_array = array(
        'product_id' => $_GET["id"],
        'item_name' => $_POST["hidden_name"],
        'product_price' => $_POST["hidden_price"],
        'item_quantity' => $_POST["quantity"],
        
    );
    $_SESSION["cart"][0] = $item_array;
    $_SESSION["totalPrice"] = $_SESSION["totalPrice"] +$_POST["hidden_price"];
    $_SESSION["cnt"] = $_SESSION["cnt"] +1;
}
}





if (!$start = filter_input(INPUT_GET, 'start', FILTER_VALIDATE_INT, array('min_range' => 0))) {
    $start = 0;
}
/*Εάν δεν είναι null το κάλεσμα της συνάρτησης τότε */
if (!$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING)) {
    //Το sort θα είναι rd το οποίο σημαίνιε ότι θα γίνεται ταξινόμηση κατά ημερομηνία που είνια το default
    $sort = 'id';
}
/*Δημιουργώ μία case η οποία θα διαλέγει ανάλογα με την τιμή του 
sort ποιά θα είναι η ταξινόμηση.  */
switch ($sort) {
    case 'id':
        $order_by = 'proid ASC';
        break;
    case 'pn':
        $order_by = 'proname ASC';
        break;
    case 'pr':
        $order_by = 'price ASC';
        break;
        case 'qu':
            $order_by = 'quantity ASC';
            break;
        //Τα __r είναι δια να γίνεται και ανάποδα η αναζήτηση
    case 'lid':
        $order_by = 'proid DESC';
        break;
    case 'lpn':
        $order_by = 'proname DESC';
        break;
    case 'lpr':
        $order_by = 'price DESC';
        break;    
        case 'lqu':
            $order_by = 'quantity DESC';
            break; 
    default:
    /*Το switch - case δεν πρόκειται να έρθει ποτέ εδώ. αλλά τη βάζω διότι
    έχω εξαντλήσει όλες τις πιθανότητες */
        $order_by = 'proid ASC';
        break;
}


/*Κάθε φοράα που κάνω ανανέωση της σελίδας αναζήτησης ή να καταχωρήσω
ένα προιόν τότε δημιουργείται το πρόβλημα ότι δεν κρατάει το όνομα 
του like στο query της αναζήτησης
Για τον παραπάνω λόγω έχω βάλει την τιμή αυτή στο SESSION*/
if (filter_input(INPUT_POST, 'searchSubmit')) {
    $errors = array();
    if (!$search_name = filter_input(INPUT_POST, 'searchText', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Μη έγκυρη καταχώρηση.';
        echo '<script>window.location="view_products.php"</script>';
    }
    //Εάν δεν είναι κενό το session τότε καταχωρείται
    if($search_name != ''){
        $_SESSION['search_name'] = $search_name;  
        $query_search_name = $_SESSION['search_name']; 
    }
    $_SESSION['search_name'] = $search_name;
}
  /*Αφού κάνω όλους τους παραπάνω ελέγχους τότε βάζω την τιμή του 
  session που έχω διασφαλίζει ότι δεν είναι κενό στη μεταβλητή που θα εκτελέσει 
  την αναζήτηση */
    $query_search_name = $_SESSION['search_name'];

    if (isset($_SESSION["search_name"])){
        $query_search_name = $_SESSION['search_name'];
    }
   



/*Θέλω να εμφανίζονται μόνο οι 10 εγγραφές και για αυτό  θέτω αυτόν τον αριθμό εδώ. 
Σε αυτή τη σελίδα το πακέτο αυτό το βάζω πιο κάτω γιατί δεν αναγνωρίζει τα $query_search_name*/
$display = 10;
$q = "SELECT COUNT(proid) FROM products"
. " WHERE proname like ('%$query_search_name%') ORDER BY $order_by";
$stmt = my_mysqli_prepare($dbc, $q);
my_mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
my_mysqli_stmt_bind_result($stmt, $count);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

/*Εάν ο αριθμός που βγαίνει να εμφανίζονται είναι μεγαλύτερος του 1 τοτε θέλω
να εμφανίζονται σε δεκάδες τα επόμενα στοιχεία. Ανάλογ ανε το πόσες δεκάδες έχω 
βάζω στο page τον αρθιθμο των δεκάδων  το οποίο το ματατρέπει σε σελίδες με το CEIL*/
if ($count > $display) {
    //CEIL το ταβάνι του επόμενου ακεραίου
    $pages = ceil($count / $display);
    //Αν οι εγγραφές είναι λιγότερες ή ίσες με 10 τότε εμφανίζω μία σελίδα
} else {
    $pages = 1;
}




    if (!empty($errors)) {
        print_error_messages($errors);
    } else {
    //Το query προς τη βάση 
    $q = "SELECT proid, proname, price, quantity, images, show_product FROM products"
    . " WHERE proname like ('%$query_search_name%') ORDER BY $order_by";
        
    }

    
   
    //Προετοιμάζω το statement
$stmt = my_mysqli_prepare($dbc, $q);
$result = mysqli_query($dbc, $q);
//Εκτελώ το statement
my_mysqli_stmt_execute($stmt);
//Με την παρακάτω εντολή φέρνω τα αποτελέσματα τοπικά ώσε να μην τα κταράω στη βάση
my_mysqli_stmt_store_result($stmt);
//μεταφέρω τα αντικείμενα από αυτά που θέλω να εξάγω
my_mysqli_stmt_bind_result($stmt, $pro_id, $pro_name, $pro_price, $pro_quantity, $images, $show_product);
//print("<div class='prod_box'>\n");
print("<table class='blueTable'>\n");
print("<tr>\n");

$link = ($sort == 'id'? 'lid': 'id');
print("\t<th width='5%'><a href='search.php?sort=$link'>Κωδικός</th>\n");

$link = ($sort == 'pn'? 'lpn': 'pn');
print("\t<th width='25%'><a href='search.php?sort=$link'>Ονομασία Προιόντος</th>\n");
$link = ($sort == 'pr'? 'lpr': 'pr');
print("\t<th width='10%'><a href='search.php?sort=$link'>Τιμή</th>\n");

print("\t<th width='10%'>Ποσότητα</th>\n");
print("\t<th width='10%'>Εικόνα</th>\n");
print("\t<th width='10%'>Καλάθι</th>\n");

print("</tr>\n");
/*Όσο ακόμη υπάρχουν γραμμές μέσα στο sqli τότε να συνεχίζεις την επανάληψη */
while (mysqli_stmt_fetch($stmt)) {

    if($show_product != 0){ 

    $row = mysqli_fetch_array($result);
    ?>
    
<form method="post" action="search.php?action=add&id=<?php echo $row["proid"]; ?>">

<?php

    print("<tr>\n");


    print("\t<td  style='text-align:center'>$pro_id</td>\n");
    print("\t<td><a href='single_product.php?id_single=$pro_id'>$pro_name</a></td>\n");
    print("\t<td style='text-align:center'> $pro_price €</td>\n");

print("\t<td style='text-align:center'><input type='text' name='quantity' size='1'  value='1'></td>\n");    
print("\t<td style='text-align:center'><img src='image/$images' width='25' height='25'></td>\n");
print("\t<td style='text-align:center'><input type='submit' src='includes/cart.png' name='add' width='16' height='16' value='Προσθήκη'></input></td>\n");
print("</tr>\n");
?>

<input type="hidden" name="hidden_name" value="<?php echo $row["proname"]; ?>">
<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
<input type="hidden" name="hidden_quantity" value="<?php echo $row["quantity"]; ?>">

</form>

<?php

    }//if
}//while
print("</table>\n");
//print("</div>\n");


/*Αφού έχω τελειώσει με όλα θα πρέπει να ελευθερώνω το χώρω του result ασχετα πο
γίνεται με το τέλος του προγράμματος */
mysqli_stmt_free_result($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);



?>
<div class="specifications">

<?php


/*Εάν τώρα ο αριθμός των σελίδω όπως αυτος καταχωρήθηκε είναι μεγαλύτερος από 1 τότε 
 */
if ($pages > 1) {
    print "<p></p><p>";
    //Το current θα είνια όσο το start +1 σελίδα για να παω στο 1
    $current = ($start / $display) + 1;
    /*Εάν τώρα το current είναι διαφορετικό του 1 που σημαίνει ότι είμαι σε μία
    διαφορετική σελίδα που σημαίνει ότι πρέπει να εμφανίσω link το οποίο θα εμφανίσει 
    link και για την προηγούμενη και για την επόμενη σελίδα. Εδώ είναι η προηγούμενη 
    Ο έλεγχος που γίνεται είναι να κοιτάει εάν είνια στην πρώτη σελίδα ώστε να 
    μην εμφανίσει το προηγούμενο.*/
    if ($current != 1) {
        $link = $start - $display;
        print "<a href='search.php?start=$link&sort=$sort'>Προηγούμενη</a> ";
    }

    /*Εδώ βάζω μία αρίθμηση των γραμμών δηλαδή στην πρώτη σελίδα θα αρχίζω από το 0
    στη δεύτερη σείδα από το 10.
    Το disaly εδώ είναι το 10. 
    Με την παρακάτω διαδικασία εμφανίζεται μια αρίθμηση για να περνάω από την μία σελίδα στην επόμενη
     */
    for ($i = 1; $i <= $pages; $i++) {
        /*Αυτή η if κάνει όλη τη δουλειά ώστε να μην γίνεται link η τρέχουσα σελίδα.
        Ελέγχει δηλαδή εάν το i που θα πάρει την τιμή της σελίδας είνια το ίδιο με 
        το current που είνια η σελίδα στην οποία βρίσκομαι*/
        if ($i != $current) {
            $link = ($i - 1) * $display;
            print "<a href='search.php?start=$link&sort=$sort'>$i</a> ";
        } else {
            print "$i ";
        }
    }
    
    /*Εδώ βάζω το link για την επόμενη σελίδα.  Με τον έλεγχο $current != $pages κοιτάω εάν
    η σελίδα που είνια είναι ίδια με τον μέγιστο αριθμό σελίδας ώστε να μην εμφανίσει το
    επόμενη γιατί είμαι στην τελευταία*/
    if ($current != $pages) {
        $link = $start + $display;
        print "<a href='search.php?start=$link&sort=$sort'>Επόμενη</a>";
    }
    print "</p>\n";
}

?>
</div >

<?php

include('includes/right_content.php');
include('includes/footer.php');
?>
