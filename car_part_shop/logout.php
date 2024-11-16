<?php
/*Και εδώ δεν ενεργοποιώ το header και το foοter διότι δημιουργείται πρόβλημα
Πρέπει πρώτα να τελειώσω με τα cookies και μετά να γράψω τα header kai
footer πιο κάτω */

//Πάλι ενεροποιώ το seseeion
session_start();

//Απαιτώ τα helper function
require_once('includes/helper_functions.php');
check_session();
$_SESSION = array();

//Καταστρέφει όλα τα υπόλοιπα
unset($_SESSION['user_id']);
unset($_SESSION['agent']);
session_destroy();

/*Ορίζω ένα νέο cookie με το default όνομα, βάζω κενό το όνομα κου
cokie διότι θα διαγραφεί. βάζω μία ώρα στο παρελθόν ώστε να λήξει το cookie.
Δίνοντας ένα παρελθοντικό χρόνο ο Browser αυτόματα διαγράφει το cookie to ότι 
βάζω τόσο μεγάλο αριθμό είναι ώστε να διασφαλίαω τυχών αποκλήσεις στην ώρα
βάζω μηδενικά στα http. Πρέπει αυτές τις 6 παραμέτρους να τις κάνω 
αακριβώς.
Μπορώ βέβαια όπως στο login να το βάλω */
setcookie('PHPSESSID', '', time()-3600, '/', '', 0, 0);

/*Μετά από αυτή ητ διαδικασία βάζω την αποσύνδεση και τα header και τα cookie.
 */
session_start();
/*Πρέπει να τα μηδενήσω καθώς διαγράφονται τα coockies και τα παρακάτω βγάζουν σφάλμα 
εάν δεν αρχικοποιηθούν */


$page_title = 'Αποσύνδεση';
include('includes/header.php');
include('includes/left_content.php');

/*if($_SESSION["cnt"] === $_SESSION["totalPrice"]){
    $_SESSION["cnt"] =0;
    $_SESSION["totalPrice"]=0; 
  }*/
  $_SESSION["cnt"] =0;
  $_SESSION["totalPrice"]=0; 


print "<h1>Αποσύνδεση</h1>\n";
print "<h3 style='color:green'>Έχετε αποσυνδεθεί επιτυχώς.</h3>\n";
include('includes/right_content.php');
include('includes/footer.php');
?>

