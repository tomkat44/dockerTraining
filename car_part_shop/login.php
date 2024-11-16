<?php
/*Με το session_start(); ενεργοποιώ το session και μπορώ να χρησιμοποιώ 
μία ιδιότητα των cookies για καλύτερη ανταλλαγή των μηνυμάτων cookies.
Αυτή η εντολή θα πρέπει να βρίσκεται πρώτη πρώτη.*/
session_start();


if($_SESSION["cnt"] === $_SESSION["totalPrice"]){
    $_SESSION["cnt"] =0;
    $_SESSION["totalPrice"]=0; 
  }
  

$page_title = 'Login';
//Πίνακας που θα περιέχει τα error
$errors = array();
require_once('includes/helper_functions.php');
//Εάν έχει αυποβληθεί η φόρμα με Post τότε:
if (filter_input(INPUT_POST, 'submit')) {
    //Απαιτώ την σύνδεση με την βάση
    require_once('mysqli_connect.php');
    if (!$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Παρακαλώ δηλώσετε έγκυρο username.';
    }
    
    if (!$pass = filter_input(INPUT_POST, 'pass', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Παρακαλώ δηλώσετε έγκυρο password.';
    }

    /*Πρέπει να δημιουργήσω μία συνάρτηση όπου να παιρνει το username και το 
    pass και να ελέγχει την βάση και να επιστρέφει αν είνια έγκυρα */
    if (empty($errors)) {
        /*Θέλω να επιστρέψει μία μεταβλητή true-false εάν υπάρχει 
        ο συνδιασμός τότε επιστρέφει true αλλιώς θα επιστρέψει με την τιμή 
        data μία τιμη που θα λέει ότι δεν βρέθηκε.Επειδή ομως έχω το 
        data στην περίπτωση που υπάρχει τότε θα φέρω για πάράδειγμα το 
        επώνυμο του πελάτη */
        list($status, $data) = check_data($dbc, $username, $pass);
        /*Την παραπάνω γραμμή επειδή αυτό που μου επιστρέφεται είναι list
        μπορώ να το γράψω όπως παρακάτω
        $result = check_data($dbc, $email, $pass)
        $status = $result[0]
        $data = $result[1]*/

        //Εάν βρει το αποτέλεσμα και είναι true ότι δηλαδή υπάρχει ο χρήστης
        if ($status) {
            //setcookie('user_id', $data, time()+3600, '/', '', 0, 0);
            $_SESSION['user_id'] = $data;
            //Επιπλέον μορφή ασφάλειας
            $_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
            //Θα μπορούσα το παραπάνω να το κρυπτογραφησω

            /*Μπορώ να ανακατευθύνω το πρόγραμμα να ακλουθήσει μία άλλη
            διαδρομή και να γίνει ένα redirect ώσε να ε μεταφέρει σε μία 
            άλλη διεύθυνση */
            //header("Location: loggedin.php");
            print "<a href='loggedin.php'>login</a>\n";
            exit();
        } else {
            //Εάν δεν βρει το χρήστη τότε θα εμφανίσει τα error
            $errors[] = $data;
        }
    }
    mysqli_close($dbc);
}

/*Το header επειδή ζητάω credentials το βάζω σε αυτό το σημείο.  */
include('includes/header.php');
include('includes/left_content.php');
print_error_messages($errors);
?>

<!--Δημιουργία Φόρμας-->

<h1>Login</h1>
<form action="login.php" method="post">
    <p>Email: <input type="text" name="username" size="20" maxlength="80"></p>
    <p>Password: <input type="password" name="pass" size="20" maxlength="20"></p>
    <p><input type="submit" name="submit" value="Login"></p>
</form>

<?php
include('includes/right_content.php');
include('includes/footer.php');
?>