<?php
//Για την επεξεργασία του πίνακα
//Έχει πολλές ομοιότητες με το delete_user



session_start();
require_once('includes/helper_functions.php');
include('mysqli_connect.php');
check_session();
$page_title = 'Επεξεργασία προϊόντος';
include('includes/header.php>');
include('includes/left_content.php');
print "<h1>Επεξεργασία προϊόντος</h1>\n";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    $id = filter_input(INPUT_POST, 'id');
    if (!$id) {
        print_access_error_exit();
    }
}

$flag_for_image = 0;

//Εάν έχει υποβληθεί η φόρμα μου, τότε:
if (filter_input(INPUT_POST, 'submit')) {
    $errors = array();

    if (!$pro_name = filter_input(INPUT_POST, 'pro_name', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε όνομα';
    }
    if (!$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε τιμή';
    }
    if (!$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε ποσότητα';
    }
    /*Βάζω εδώ την εισαγωγή της εικόνας έτσι ώστε αν εισάγει ο χρήστης εικόνα να 
    κρατήσει αυτή που εισήγαγε αλλιώς να κρατήσει την ίδια. Μάλλον θα την κάνω και hidden */
    if (!$image_name = filter_input(INPUT_POST, 'image_name', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε τιμή';
    }


    $allowed = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/JPG',
    'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png', '');
// print "<pre>"; print_r($_FILES['upload']); print "</pre>\n"; 
/*Ελέγχω τι ακριβώς τύπος είναι το αρχείο που έχω ανεβάσει με την από πάνω γραμμή (DEBUGING MESSAGE) */

/*Εάν βρίσκεται το allowed τότε */
if (in_array($_FILES['upload']['type'], $allowed)) {
/*Η πρωσορηνή θέση που ανεβαίνει το αρχείο πριν το κόμμα και μετά 
την θέση uploads που θα είναι η θέση στην οποία θα ανεβαίνουν οι εικόνες
Επιπλέον δημιουργώ ένα ένα φάκελο ο οποίος θα ονομάζεται upload 
και θα μπουν μέσα οι φωτό*/
move_uploaded_file($_FILES['upload']['tmp_name'],
"image/{$_FILES['upload']['name']}");

/*Περνάω το όνομα της εικόνας σε μία μεταβλήτή ώστε να μπει κατευθείαν 
και το όνομα στον πίνακα SQL και η εικόνα στον φάκελο. */
if($_FILES['upload']['name'] != ""){
    $image_name = $_FILES['upload']['name'];
    $flag_for_image = 1;
}


/*Μέχρι εδώ έχω τελειώσει και όλα πάνε καλά . Από εδώ και πέρα θα χρειαστεί
να προβλέψω ολα τα πιθανά λάθη που πρέπει να πάρω στην περίπτωση που σημβέι 
κάτι λάθος. */
} 
/*Γράφω τους τύπους των λαθών
Εάν ο φακελος error έχει μέσα στοιχεία τότε 
Ταυτόχρονα ελέγχω εάν έχει περαστεί το όνομα. */
if ($_FILES['upload']['error'] > 0 && $flag_for_image == 0) {
//τυπώνω ένα μήνυμα σφάλματος και...
//print "<p class='error'>Το αρχείο δεν μπόρεσε να ανέβει: <br>\n";

/*Επειδή τα λάθη είναι κωδικοποιημένα μπορώ να βρω στο ιντερνετ τους κωδικούς των λαθών
Παρακάτω κεφαλαία γράμματα είνια οοοι κωδικοί των λαθών αλλά υπάρχει αντιστοίχηση
ώωστε να μπορώ και εγώ να τους καταλάβω καλύτερα */
switch ($_FILES['upload']['error']) {

//Για μεγαλύτερη ευκολία μπορώ να μεταφράσω τα παρακάτω. 
case UPLOAD_ERR_INI_SIZE:
$message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
break;
case UPLOAD_ERR_FORM_SIZE:
$message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
break;
case UPLOAD_ERR_PARTIAL:
$message = "The uploaded file was only partially uploaded";
break;
case UPLOAD_ERR_NO_FILE:
$message = "No file was uploaded";
break;
case UPLOAD_ERR_NO_TMP_DIR:
$message = "Missing a temporary folder";
break;
case UPLOAD_ERR_CANT_WRITE:
$message = "Failed to write file to disk";
break;
case UPLOAD_ERR_EXTENSION:
$message = "File upload stopped by extension";
break;
default:
$message = "Unknown upload error";
break;
}
print "$message. </p>\n";
}
/*Στην περίπτωση που υπάρχει και είναι αρχείο, τότε πρέπει να το διαγράψει με την εντολή 
unlink.  */
if (file_exists($_FILES['upload']['tmp_name']) && 
is_file($_FILES['upload']['tmp_name'])) {
unlink($_FILES['upload']['tmp_name']);
}




    
    
    

    if (empty($errors)) {
        
            //Εάν τα στοιχέια είναι έγκυρα τότε κάνω την αλλαγή 
            $q1 = "UPDATE products SET proname=?, price=?, quantity=?, images=?, show_product=1 WHERE proid=?";
            $stmt1 = my_mysqli_prepare($dbc, $q1);
            //λήψη των μεταβλητών
            my_mysqli_stmt_bind_param($stmt1, 'sddsi', $pro_name, $price, $quantity, $image_name, $id);
            my_mysqli_stmt_execute($stmt1);
            //Εάν δεν επηρρεάστηκε καμία εγγρφή τότε βγάζει λάθος
            if (mysqli_stmt_affected_rows($stmt1) == 0) {
                $errors[] = 'Δεν πραγματοποιήθηκε κάποια μεταβολή.';
            } else {
                print "<p>Πραγματοποιήθηκε επεξεργασία στοιχείων με επιτυχία.</p>";

                /*Τις δύο παρακάτω γραμμές εάν τις ενεργοποιήσω τότε μετά την επεξεργασία θα 
                τον βγάζει από την σελίδα και να μην με κρατάει σε αυτή όπως έιναι τώρα.  */
                //include('include/footer.php');
                //exit();
            }
            mysqli_stmt_close($stmt1);
            
        }else{
            print_error_messages($errors);
        }   
        
    }
    


/*Αυτο΄είναι το πρώτο που έφτιαξα Δηλαδή να κάνω την ερώτηση */
$q = "SELECT proname, price, quantity, images FROM products WHERE proid=?";
$stmt = my_mysqli_prepare($dbc, $q);
my_mysqli_stmt_bind_param($stmt, 'i', $id);
my_mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if (mysqli_stmt_num_rows($stmt) == 0) {
    print_access_error_exit();
}
//Καταχώρηση τω σωστών αποτελεσμάτων. 
my_mysqli_stmt_bind_result($stmt, $pro_name, $price, $quantity, $image_name);
//Καλω την παρακάτω συνάστηση για την καταχώρηση των αποτελεσμάτων
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);
mysqli_close($dbc);
?>
<!--Το όνομα της φόρμας μπορώ να το αφήσω και κενό "", είναι ενα πλεονέκτημα
που μου δίνει η PHP είναι να αφήνα κενό το όνομα του action ώστε να καλεί
τον εαυτό της και εμένα με βοηθάει αυτό στην περίπτση που της αλλάξω όνομα.-->
<form action="edit_product.php" method="post" enctype="multipart/form-data">
    <p>Ονομάσία Προιόντος: <input type="text" name="pro_name" size="20" maxlength="40"
                     value="<?php print $pro_name; ?>"></p>
    <p>Τιμή Προιόντος: <input type="text" name="price" size="20" maxlength="40"
                     value="<?php print $price; ?>"></p>
    <p>Αρχική διαθέσιμη ποσότητα: <input type="text" name="quantity" size="10" maxlength="10"
                     value="<?php print $quantity; ?>"></p>

<!--Το όνομα της εικόνας το έχω βάλει απλά για να φαίνεται. Δεν επιρρεάζει κάτι και 
να το αλλάξω. Η εικόνα αλλάζει όταν την φορτώσει

Επιπλέον βάζω παύλα ώστε αν δεν έχει κάποιο όνομα στον έλεγχο να μην πετάξει σφάλμα-->
    <p>Τρέχουσα εικόνα: <input type="text" name="image_name" size="10" maxlength="10"
    value="<?php if($image_name!=''){print $image_name;}else{print '-';} ?>"> </p>
    <p><b>Αλλαγή εικόνας: </b><input type="file" name="upload"></p>
   
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
    <p><input type="submit" name="submit" value="Επεξεργασία"></p>

    <!--Η κρυφή αυτή μεταβλητή την χρησιμοποιώ ώστε να περάσω μέσω της POST
    την κρυφή παράμετρο που δεν θα τη βλέπει ο χρήστης και θα τη χρισιμοποιήσω'
    ώστε να έχω την δυνατότητα να επεξεργαστώ τα στοιχέια όλω των χρηστών.
    Μπορώ να περάσω και αυτό το ID μέσω του SESSION αλλά θα μου δίνει την 
    δυνατότητα νε αλλάξω τα στοιχεία μόνο του εαυτόυ μου που συνδέθηκα 'και 
    όχι όλα τα στοιχέια όπως εδώ. 
    -->
    <input type="hidden" name="id" value="<?php print $id;?>">
</form>



<?php
include('includes/right_content.php');
include('includes/footer.php');
?>
