<?php
function print_system_error()
{
    print "<h1>Σφάλμα συστήματος</h1>\n";
    print "<h3 class='error'>Δεν ήταν δυνατή η πραγματοποίηση της ενέργειας"
    . " λόγω σφάλματος συστήματος. "
    . " Παρακαλώ δοκιμάστε αργότερα.</h3>\n";
    include('includes/right_content.php');
include('includes/footer.php');
}

function print_error_messages($errors)
{
    if (!empty($errors)) {
        print "<h1>Σφάλμα</h1>\n";
        print "<p class='error'>Ανιχνεύτηκαν τα εξής λάθη:<br>\n";
        foreach ($errors as $message) {
            print " - $message!<br>\n";
        }
        print "</p><p>Παρακαλώ ξαναδοκιμάστε!</p>\n";
    }
}

function print_message($errors)
{
    print "<br/><h2 class='error'> $errors </h2>\n";

}

function my_mysqli_prepare($dbc, $q)
{
    if (!$stmt = mysqli_prepare($dbc, $q)) {
        print_system_error();
        die('stmt prepare() failed: ' . mysqli_error($dbc));
    }
    return $stmt;
}

function my_mysqli_stmt_store_result($stmt)
{
    if (!$r = mysqli_stmt_store_result($stmt)) {
        print_system_error();
        die('stmt store_result() failed: ' . mysqli_stmt_error($stmt));
    }
    return $r;
}


function my_mysqli_stmt_bind_param($stmt, $type, ...$params)
{        
    if (!$r = mysqli_stmt_bind_param($stmt, $type, ...$params)) {
            print_system_error();
            die('stmt bind_param() failed: ' . mysqli_stmt_error($stmt));  // PHP does not store error!           
        }
    return $r ;
}

function my_mysqli_stmt_execute($stmt)
{
    if (!$r = mysqli_stmt_execute($stmt)) {  
        print_system_error();
        die('stmt execute() failed: ' . mysqli_stmt_error($stmt));
    }
    return $r;
}

function my_mysqli_stmt_bind_result($stmt, &...$results)
{        
    if (!$r = mysqli_stmt_bind_result($stmt, ...$results)) {
            print_system_error();
            die('stmt bind_result() failed: ' . mysqli_stmt_error($stmt));  // PHP does not store error!           
        }
    return $r ;
}


function print_access_error_exit()
{
    print "<br/><h3 class='error'>Δεν επιτρέπεται η πρόσβαση</h3>";
    include('includes/right_content.php');
    include('includes/footer.php');
    exit();
}

function print_cart($cart){
    foreach ($cart as $message) {
        print " - $message!<br>\n";
    }
}


function check_data($dbc, $username, $pass)
{
    //Κάνω το ερώτημα στη βάση για να βρεθεί το ζευγάρι username kai pass
    $q = "SELECT user_id FROM users WHERE username like(?) and pass = SHA2(?, 256)";
    $stmt = my_mysqli_prepare($dbc, $q);
    my_mysqli_stmt_bind_param($stmt, 'ss', $username, $pass);
    my_mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 1) {
        my_mysqli_stmt_bind_result($stmt, $user_id);
        mysqli_stmt_fetch($stmt);
        $status = true;
        //Στο data βάζω το userId
        $data = $user_id;
    } else {
        /*Η συνάρτηση αυτή επιστρέφει το μήνημα σφάλματος αν δεν βρει το 
συνδιασμό username και pass 
Βάζει το status false*/
        $errors[] = 'Δεν ταιριάζει το ζεύγος username και password';
        $status = false;
        $data = $errors;
    }
    //Απελευθέρωση result
    mysqli_stmt_free_result($stmt);
    //Κλεινω το statement
    mysqli_stmt_close($stmt);
    //Επιστροφή του Array
    return array($status, $data);
}


/*Την συνάρτηση αυτή θα την χρησιμοποιήσω σε κάθε σιλίδα που δεν θέλω να 
βλέπει ένας χρήστης που δεν είναι πιστοποιημένος. */
function check_session()
{
    /*Την καλέι η ligedin και ελέγχει εάν είναι ο ρήστης πιστοποιημένος
    Εάν δεν βρει κα΄ποιο χρήστη τότε τον ανακατευθύνει στο login */
    if (!isset($_SESSION['user_id']) OR (!isset($_SESSION['agent'])) OR
            ($_SESSION['agent']) != md5($_SERVER['HTTP_USER_AGENT'])) {
        header("Location: login.php");
        exit();
    }
}

