<?php
session_start();
$page_title = 'Λίστα παραγγελιών';
require_once('includes/helper_functions.php');
check_session();

include('includes/header.php');
//print ('<div class="center_content">');
include('includes/left_content.php');


print("<div class='center_title_bar'>Λίστα παραγγελιών</div>\n");
print("<br/>\n");
require_once('mysqli_connect.php'); //Σύνδεση με την βάση







/*Θέλω να εμφανίζονται μόνο οι 10 εγγραφές και για αυτό  θέτω αυτόν τον αριθμό εδώ. */
$display = 10;
$q = "SELECT COUNT(ord_id) FROM orders";
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
        $order_by = 'ord_id ASC';
        break;
    case 'pid':
        $order_by = 'pro_id ASC';
        break;
    case 'pn':
        $order_by = 'pro_name ASC';
        break;
        case 'od':
            $order_by = 'ord_date ASC';
            break;
        //Τα __r είναι δια να γίνεται και ανάποδα η αναζήτηση
    case 'lid':
        $order_by = 'ord_id DESC';
        break;
    case 'lpid':
        $order_by = 'pro_id DESC';
        break;
    case 'lpn':
        $order_by = 'pro_name DESC';
        break;    
        case 'lod':
            $order_by = 'ord_date DESC';
            break; 
    default:
    /*Το switch - case δεν πρόκειται να έρθει ποτέ εδώ. αλλά τη βάζω διότι
    έχω εξαντλήσει όλες τις πιθανότητες */
        $order_by = 'pro_id ASC';
        break;
}




$q = "SELECT * FROM orders"
. " ORDER BY $order_by "
. "LIMIT $start, $display";
   
    //Προετοιμάζω το statement
$stmt = my_mysqli_prepare($dbc, $q);
$result = mysqli_query($dbc, $q);
//Εκτελώ το statement
my_mysqli_stmt_execute($stmt);
//Με την παρακάτω εντολή φέρνω τα αποτελέσματα τοπικά ώσε να μην τα κταράω στη βάση
my_mysqli_stmt_store_result($stmt);
//μεταφέρω τα αντικείμενα από αυτά που θέλω να εξάγω
my_mysqli_stmt_bind_result($stmt, $ord_id, $pro_id, $pro_name, $pro_quant, $pro_price, $pro_finalprice, $ord_date,  $lastname, $firstname, $email);

/*Επειδή ο πίνακας είνια πολύ μεγάλος έχω βάλει να είνια μέσα σε scroll */
print("<div id='table-wrapper'>
<div id='table-scroll'>");
print("<table class='blueTable'>\n");
print("<tr>\n");

$link = ($sort == 'id'? 'lid': 'id');
print("\t<th width= '5%'><a href='view_orders.php?sort=$link'>Κωδικός Παραγγελίας</th>\n");

$link = ($sort == 'pid'? 'lpid': 'pid');
print("\t<th width= '5%'><a href='view_orders.php?sort=$link'>Κωδικός Προιόντος</th>\n");
$link = ($sort == 'pn'? 'lpn': 'pn');
print("\t<th width= '15%'><a href='view_orders.php?sort=$link'>Ονομασία Προιόντος</th>\n");

print("\t<th width= '5%'>Ποσότητα</th>\n");
print("\t<th width= '5%'>Τιμή μονάδος</th>\n");
print("\t<th width= '5%'>Συνολική τιμή</th>\n");
$link = ($sort == 'od'? 'lod': 'od');
print("\t<th width= '15%'><a href='view_orders.php?sort=$link'>Ημερομηνία παραγγελίας</th>\n");
print("\t<th width= '15%'>Επώνυμο πελάτη</th>\n");
print("\t<th width= '15%'>Όνομα Πελάτη</th>\n");
print("\t<th width= '15%'>Email Πελάτη</th>\n");

print("</tr>\n");
/*Όσο ακόμη υπάρχουν γραμμές μέσα στο sqli τότε να συνεχίζεις την επανάληψη */
while (mysqli_stmt_fetch($stmt)) {

    $row = mysqli_fetch_array($result);

    print("<tr :nth-child>\n");
?>


<?php

    print("\t<td >$ord_id</td>\n");
    print("\t<td >$pro_id</td>\n");
    print("\t<td><a href='single_product.php?id_single=$pro_id'>$pro_name</a></td>\n");
    print("\t<td>$pro_quant</td>\n");
    print("\t<td >$pro_price</td>\n");
    print("\t<td >$pro_finalprice</td>\n");
    print("\t<td >$ord_date</td>\n");
    print("\t<td >$lastname</td>\n");
    print("\t<td >$firstname</td>\n");
    print("\t<td >$email</td>\n");
    

print("</tr>\n");
?>


<?php

}//while
print("</table>\n");
print("</div></div>");



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
        print "<a href='view_orders.php?start=$link&sort=$sort'> Προηγούμενη </a> ";
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
            print "<a href='view_orders.php?start=$link&sort=$sort'> $i </a> ";
        } else {
            print "$i ";
        }
    }
    
    /*Εδώ βάζω το link για την επόμενη σελίδα.  Με τον έλεγχο $current != $pages κοιτάω εάν
    η σελίδα που είνια είναι ίδια με τον μέγιστο αριθμό σελίδας ώστε να μην εμφανίσει το
    επόμενη γιατί είμαι στην τελευταία*/
    if ($current != $pages) {
        $link = $start + $display;
        print "<a href='view_orders.php?start=$link&sort=$sort'> Επόμενη </a>";
    }
    print "</p>\n";
}

?>
</div >

<?php

include('includes/right_content.php');
include('includes/footer.php');
?>