<?php
session_start();
require_once('includes/helper_functions.php');
/*Ελέγχω το session με το  check_session(); ώστε να μην μπορεί κάποιος να
διαγράψει ή να μπει σε αυτή τη σελίδα χωρίς να έχει τα απαράιτητα credentials.*/
check_session();
$page_title = 'Διαγραφή προιόντος';
include('includes/header.php>');
include('includes/left_content.php');
require_once('mysqli_connect.php');

print "<div class='center_title_bar'>Διαγραφή προιόντος</div>
\n";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $id = filter_input(INPUT_POST, 'id');
    if (!$id) {
        print_access_error_exit();
    }
}

if (filter_input(INPUT_POST, 'submit')) {
    $sure = filter_input(INPUT_POST, 'sure');
    $confirm = ($sure == 'yes')? true: false;
   
    /*Δεν διαγράφψ το προιόν αλλά το καθιστώ ανενεργό θέτοντας το 
    show_product = 0 */
    if (!$confirm) {
        print "<h3 style='color:orange'>Το προιόν ΔΕΝ διαγράφηκε.</h3>\n";
    } else {
        $q = "UPDATE products SET show_product = 0 WHERE proid=?";
        $stmt = my_mysqli_prepare($dbc, $q);
        my_mysqli_stmt_bind_param($stmt, 'i', $id);
        my_mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) == 0) {
            print_access_error_exit();
        } else {
            print "<br/><h3 style='color:green'>To προϊόν διαγράφηκε επιτυχώς.<h3>\n";
        }
    }
    include('includes/right_content.php');
include('includes/footer.php');
    exit();
}

$q = "SELECT proid, proname FROM products WHERE proid=?";
$stmt = my_mysqli_prepare($dbc, $q);
my_mysqli_stmt_bind_param($stmt, 'i', $id);
my_mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) == 0) {
    print_access_error_exit();
}
my_mysqli_stmt_bind_result($stmt, $pro_id, $pro_name);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>
<br>
<form action="delete_product.php" method="post">
    
    <h4>Κωδικός: <?php print "$pro_id";?></h4>
    <h4>Ονομασία: <?php print "$pro_name";?></h4>
    <p>Είστε σίγουροι για τη διαγραφή του προιόντος;<br>
    <input type="radio" name="sure" value="yes">Ναι
    <input type="radio" name="sure" value="no">Όχι</p><br>
    <p><input type="submit" name="submit" value="Υποβολή"></p>
    <input type="hidden" name="id" value="<?php print $id;?>">
</form>

