
<?php

/*
    $db_user = 'root';
    $db_password = 'root';
    $db_host = 'localhost:9906';
    $db_name = 'e_shop_php';
    
    $dbc = @mysqli_connect($db_host, $db_user, $db_password, $db_name) OR 
            die('Δεν είναι δυνατή η σύνδεση με τη βάση δεδομένων: ' . mysqli_connect_error());
    
*/
// check the MySQL connection status
/*
$conn = new mysqli($host, $user, $pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected to MySQL server successfully!";
}
*/

//These are the defined authentication environment in the db service

// Σύνδεση με MyPHPAdmin in the docker-compose.yml.

$host = 'db';
$user = 'root';
$pass = 'root';
$db_name = 'e_shop_php';
$dbc = new mysqli($host, $user, $pass, $db_name);


   
?>
    
    
