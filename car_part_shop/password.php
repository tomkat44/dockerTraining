<?php
/*Για να μην έχω το πρόβλημα ότι κάποιος καλέσει τη σελίδα password 
με το χέρι από την URL θα πρέπει σε κάθε σελίδα που δεν θέλω να γίνεται αυτό 
να πάω και να βάλω τις τρεις παρακάτω γραμμές σε κάθε σελίδα που 
θέλω να γίνεται έλεγχος των credential username & password. */
session_start();
require_once('includes/helper_functions.php');
check_session();
//$page_title = 'Αλλαγή password';

include('includes/header.php');
include('includes/left_content.php');
print("<div class='center_title_bar'>Αλλαγή password</div>\n");



if (filter_input(INPUT_POST, 'submit')) {
    require_once('mysqli_connect.php');
    $errors = array();
    if (!$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε username';
    }
    
    if (!$curpass = filter_input(INPUT_POST, 'curpass', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε password';
    }
    /* TODO: Check if email, password pair exists in DB */

    if (empty($errors)) {
    list($status, $data) = check_data($dbc, $username, $curpass);
    if ($status) {
        $user_id = $data;
        
    } else {
        $errors = $data;
       
    }

    //;Τα 2 pass πρέπει να είναι ίδια
    if (!$pass1 = filter_input(INPUT_POST, 'pass1', FILTER_SANITIZE_STRING)) {
        $errors[] = 'Ξεχάσατε να δηλώσετε νέο password';
    } else {
        $pass2 = filter_input(INPUT_POST, 'pass2', FILTER_SANITIZE_STRING);
        if ($pass2 != $pass1) {
            $errors[] = 'Δεν ταιριάζουν τα νέα passwords μεταξύ τους';
        }
    }

    



    
    if (!empty($errors)) {
        print_error_messages($errors);
    } else {
        $q = "UPDATE users SET pass=SHA2(?, 256) WHERE user_id=$user_id";
        $stmt = my_mysqli_prepare($dbc, $q);
        my_mysqli_stmt_bind_param($stmt, 's', $pass1);
        my_mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) == 1) {
            print "<h1>Επιτυχής αλλαγή password</h1>\n";
            print "<h3 style='color:green'>Έχετε αλλάξει το password επιτυχώς!</h>\n";
        }
        mysqli_stmt_free_result($stmt);
        mysqli_stmt_close($stmt);
        mysqli_close($dbc);
        include('includes/right_content.php');
        include('includes/footer.php');
        exit();
    }
    mysqli_close($dbc);
}
}


?>
<br/>

<div class="specifications">
<form action="password.php" method="post">
    <p>Username: <input type="text" name="username" size="20" maxlength="80"
                     value="<?php if (isset($username)) echo $username; ?>"></p>
    <p>Τρέχον password: <input type="password" name="curpass" size="10"
                               maxlength="20"></p>
    <p>Νέο password: <input type="password" name="pass1" size="10"
                            maxlength="20"></p>
    <p>Επιβεβαίωση νέου password: <input type="password" name="pass2"
                                         size="10" maxlength="20"></p>
    <p><input type="submit" name="submit" value="Αλλαγή password"></p>
</form>
</div>
<?php
include('includes/right_content.php');
include('includes/footer.php>');
?>
