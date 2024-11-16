<?php
session_start();
require_once('includes/helper_functions.php');
$page_title = 'Εγγραφή';
include('includes/header.php>');
include('includes/left_content.php');

$flag_for_image = 0;


if (filter_input(INPUT_POST, 'insert_product')) {
    $errors = array();
    $image_name = "";

    if (!$pro_name = filter_input(INPUT_POST, 'pro_name', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε όνομα';
    }
    if (!$price = filter_input(INPUT_POST, 'price', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε τιμή';
    }
    if (!$quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε ποσότητα';
    }


    /*Καθορίζω τους τύπους των αρχείων που μπορεί να εμφανιστούν ώστε να είναι
    όλοι αποδεκτοί.  */
    $allowed = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/JPG',
                     'image/X-PNG', 'image/PNG', 'image/png', 'image/x-png');
    // print "<pre>"; print_r($_FILES['upload']); print "</pre>\n"; 
    /*Ελάγχω τι ακριβώς τύπος είναι το αρχείο που έχω ανεβάσει με την από πάνω γραμμή (DEBUGING MESSAGE) */

    /*Εάν βρίσκεται το allowed τότε */
    if (in_array($_FILES['upload']['type'], $allowed)) {
        /*Η πρωσορηνή θέση που ανεβαίνει το αρχείο πριν το κόμμα και μετά 
        την θέση uploads που θα είναι η θέση στην οποία θα ανεβαίνουν οι εικόνες
        Επιπλέον δημιουργώ ένα ένα φάκελο ο οποίος θα ονομάζεται upload και θα μπουν μέσα οι φωτό*/
        move_uploaded_file($_FILES['upload']['tmp_name'],
                "image/{$_FILES['upload']['name']}");

                /*Περνάω το όνομα της εικόνας σε μία μεταβλήτή ώστε να μπει κατευθείαν 
                και το όνομα στον πίνακα SQL και η εικόνα στον φάκελο. */
                if($_FILES['upload']['name'] != ""){
                    $image_name = $_FILES['upload']['name'];
                    $flag_for_image = 1;
                 }

                /*Μέχρι εδώ έχω τελειώσει α όλα πάνε καλά . Από εδώ και πέρα θα χρειαστεί
                να προβλέψω ολα τα πιθανά λάθη που πρέπει να πάρω στην περίπτωση που σημβέι 
                κάτι λάθος.*/
    
    } 
    /*Εδώ ο έλεγχος του αν λεχει μει τιμή η όχι είναι αντίθετη με το 
    edit διότι δεν έχω αρχική τιμή στην εικόνα και το έσω βάλει
    να δέχται και την μη ύπαρξη εικόνας την οποία μπορώ να πάω στο 
    edit και να την βάλω μετά. */
    if ($_FILES['upload']['error'] > 0 && $flag_for_image == 1) {
        //τυπώνω ένα μήνυμα σφάλαματος και...
       // print "<p class='error'>Το αρχείο δεν μπόρεσε να ανέβει: <br>\n";

        /*Επειδή τα λάθη είναι κωδικοποιημένα μπορώ να βρω στο ιντερνετ τους κωδικούς των λαθών
        Παρακάτω κεφαλαία γράμματα είνια οοι κωδικοί των λαθών αλλά υπάρχει αντιστοίχηση
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


    
    
   
    if (!empty($errors)) {
        print_error_messages($errors);
    } else {
        require_once('mysqli_connect.php');
        
        $q = "INSERT INTO products (proname, price, quantity, images, show_product) "
        ." VALUES(?, ?, ?, ?,'1')";
        $stmt = my_mysqli_prepare($dbc, $q);
        my_mysqli_stmt_bind_param($stmt, 'sdis', $pro_name, $price, $quantity, $image_name);
        my_mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) == 1) {
            print "<h1 style='color:green'>Επιτυχής ένταξη του προιόντος στη λίστα</h1>\n";
           
        }
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
        include('includes/right_content.php');
        include('includes/footer.php>');
        exit();
    }


}

?>


<h1>Εισαγωγή νέου προιόντος</h1>
<form action="insert_product.php" method="post" enctype="multipart/form-data">
    <p>Ονομάσία Προιόντος: <input type="text" name="pro_name" size="20" maxlength="40"
                     value="<?php if (isset($pro_name)) echo $pro_name; ?>"></p>
    <p>Τιμή Προιόντος: <input type="text" name="price" size="20" maxlength="40"
                       value="<?php if (isset($price)) echo $price; ?>"></p>
    <p>Αρχική διαθέσιμη ποσότητα: <input type="text" name="quantity" size="10" maxlength="10"
                     value="<?php if (isset($quantity)) echo $quantity; ?>"></p>
   
    <input type="hidden" name="MAX_FILE_SIZE" value="2000000">
            <fieldset>                
                    <!--Δημιουργώ ένα input πεδιο το  οποίο θα μου φέρει την εικόνα.
                    Τυπικά δημιουργεί το κουμπί το οποιο θα μου εμφανίσει την επιλογή εικόνας.-->
                <p><b>Αρχείο εικόνας: </b><input type="file" name="upload"></p>
                <p>Επιλογή εικόνας  JPEG ή PNG μεγέθους 2MB ή μικρότερη.</p>
                
            </fieldset>
            
    
    <p><input type="submit" name="insert_product" value="Εγγραφή Προιόντος"></p>
</form>


<?php
include('includes/right_content.php');
include('includes/footer.php>');
?>
