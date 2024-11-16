<?php
session_start();
$page_title = 'Λίστα προιόντων';



include('includes/header.php');
include('includes/left_content.php');
require_once('includes/helper_functions.php');
check_session();

print("<h1 class='center_title_bar'>Λίστα προιόντων</h1>\n");
require_once('mysqli_connect.php'); //Σύνδεση με την βάση




/*Θέλω να εμφανίζονται μόνο οι 10 εγγραφές και για αυτό  θέτω αυτόν τον αριθμό εδώ. */
$display = 10;
$q = "SELECT COUNT(proid) FROM products";
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

if (!$start = filter_input(INPUT_GET, 'start', FILTER_VALIDATE_INT, array('min_range' => 0))) {
    $start = 0;
}
/*Εάν δεν είναι null το κάλεσμα της συνάρτησης τότε */
if (!$sort = filter_input(INPUT_GET, 'sort', FILTER_SANITIZE_STRING)) {
    //Το sort θα είνια rd το οποίο σημαίνιε ότι θα γίνεται ταξινόμηση κατά ημερομηνία που είνια το default
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



$q = "SELECT proid, proname, price, quantity, images, show_product FROM products"
. " ORDER BY show_product desc, $order_by "
. "LIMIT $start, $display";
   
    //Προετοιμάζω το statement
$stmt = my_mysqli_prepare($dbc, $q);
$result = mysqli_query($dbc, $q);
//Εκτελώ το statement
my_mysqli_stmt_execute($stmt);
//Με την παρακάτω εντολή φέρνω τα αποτελέσματα τοπικά ώσε να μην τα κταράω στη βάση
my_mysqli_stmt_store_result($stmt);
//μεταφέρω τα αντικείμενα από αυτά που θέλω να εξάγω
my_mysqli_stmt_bind_result($stmt, $pro_id, $pro_name, $pro_price, $pro_quantity, $images, $show_product);
print("<table class='blueTable'>\n");

print("<tr >\n");


$link = ($sort == 'id'? 'lid': 'id');
print("\t<th ><a href='view_for_edit_products.php?sort=$link'>Κωδικός</th>\n");

$link = ($sort == 'pn'? 'lpn': 'pn');
print("\t<th><a href='view_for_edit_products.php?sort=$link'>Ονομασία Προιόντος</th>\n");
$link = ($sort == 'pr'? 'lpr': 'pr');
print("\t<th><a href='view_for_edit_products.php?sort=$link'>Τιμή</th>\n");
print("\t<th>Ποσότητα</th>\n");
print("\t<th>Εμφάνηση</th>\n");
print("\t<th>Εικόνα</th>\n");
print("\t<th>Επεξεργασία</th>\n");
print("\t<th>Διαγραφή</th>\n");

print("</tr>\n");

/*Όσο ακόμη υπάρχουν γραμμές μέσα στο sqli τότε να συνεχίζεις την επανάληψη */
while (mysqli_stmt_fetch($stmt)) {

    $row = mysqli_fetch_array($result);
    
    print("<tr >\n");
    


/*Πρέπει να πάει έξω από το php εάν θέλω να λειτουργήσω με submit αντι για href
 <form method="post" action="single_product.php?action=add_single_product&id_single=<?php echo $row["proid"]; ?>">
<input type="hidden" name="hidden_name_single" value="<?php echo $row["proname"]; ?>">
<input type="hidden" name="hidden_price_single" value="<?php echo $row["price"]; ?>">
<input type="hidden" name="hidden_quantity_single" value="<?php echo $row["quantity"]; ?>">
<input type="hidden" name="hidden_image_single" value="<?php echo $row["images"]; ?>">
*/

?>


<?php

    print("\t<td>$pro_id</td>\n");

    print("\t<td><a href='single_product.php?id_single=$pro_id'>$pro_name</a></td>\n");
    
    //print("\t<td><input type='submit' name='add' value='$pro_name'></td>\n");
    print("\t<td>$pro_price €</td>\n");
    print("\t<td>$pro_quantity</td>\n");
    print("\t<td>$show_product</td>\n");
    print("\t<td><a href='single_product.php?id_single=$pro_id'><img src='image/$images' width='25' height='25'></a></td>\n");

print ("<td><a href='edit_product.php?id=$pro_id'><img src='includes/upd.png' width='16' height='16'></a></td>\n");
if($show_product != 0){ 
print ("<td><a href='delete_product.php?id=$pro_id'><img src='includes/del.png' width='16' height='16'></a></td>\n");
}else{
    print ("<td><a href='reset_product.php?id=$pro_id'>Επαναφορά</a></td>\n");
}//if

print("</tr>\n");


/*Πρέπει να πάει έξω από το php ακριβώς από κάτω είναι συνέχεια του πάνω
πάει με το παπαπάνω σχόλιο που έχω βάλει με τα hidden
<input type="hidden" name="hidden_name" value="<?php echo $row["proname"]; ?>">
<input type="hidden" name="hidden_price" value="<?php echo $row["price"]; ?>">
<input type="hidden" name="hidden_quantity" value="<?php echo $row["quantity"]; ?>">
</form>
*/
?>


<?php

}//while
print("</table><br/>\n");

print ("<div id='add_button'> <a href='insert_product.php'> + Δημιουργεία νέου προιόντος</a> </div>");



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
        print "<a href='view_for_edit_products.php?start=$link&sort=$sort'> Προηγούμενη </a> ";
    }

    /*Εδώ βάζω μία αρίθμηση των γραμμών δηλαδή στην πρώτη σελίδα θα αρχίζω από το 0
    στη δεύτερη σείδα από το 10.
    Το disaly εδώ είναι το 10. 
    Με την παρακάτω διαδικασία εμφανίζεται μια αρίθμηση για να περνάω από την μία σελίδα στην επόμενη
     */
    for ($i = 1; $i <= $pages; $i++) {
        /*Αυτή η if κάνει όλη τη δουλειά ώστε να μην γίνεται link η τρέχουσα σελίδα.
        Ελέγχει δηλαδή εάν το i που θα πάρει την τιμή της σελίδας είναι το ίδιο με 
        το current που είνια η σελίδα στην οποία βρίσκομαι*/
        if ($i != $current) {
            $link = ($i - 1) * $display;
            print "<a href='view_for_edit_products.php?start=$link&sort=$sort'> $i </a> ";
        } else {
            print "$i ";
        }
    }
    
    /*Εδώ βάζω το link για την επόμενη σελίδα.  Με τον έλεγχο $current != $pages κοιτάω εάν
    η σελίδα που είνια είναι ίδια με τον μέγιστο αριθμό σελίδας ώστε να μην εμφανίσει το
    επόμενη γιατί είμαι στην τελευταία*/
    if ($current != $pages) {
        $link = $start + $display;
        print "<a href='view_for_edit_products.php?start=$link&sort=$sort'> Επόμενη </a>";
    }
    print "</p>\n";
}
?>
</div>
<?php

include('includes/right_content.php');
include('includes/footer.php');
?>