<?php
/*Αυτό το πρόγραμμα καλέιτε από την ίδια την login που κάνω
ανακατεύθυνση. 
Για την χρήση των coockies κάνω session start */
session_start();
/*Το helper Function το χρειάζομαι ώστε να χρησιμοποιήσω την 
 check_session(); επειδή την έχω βάλει εκεί διότι θα την καλώ
 περισσότερες από μία φορές*/
require_once('includes/helper_functions.php');
check_session();



$page_title = 'Logged In';
include('includes/header.php');
include('includes/left_content.php');
print "<h1>Logged in</h1>\n";
print "<h3 style='color:green'>Είστε συνδεδεμένος!</h3>\n";
//Το κουμπί γαι την logout
print "<h4><a href='logout.php'>Logout</a></h4>\n";
include('includes/right_content.php');
include('includes/footer.php');
?>
