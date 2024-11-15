<?php
$page_title = "Ημερολόγιο";
include('includes/header.php');
?>

<?php
//Ορισμός της συνάρτησης
function make_calendar_pulldown(){

$months = array(1=>'Ιανουάριος',
'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος',
'Μάιος', 'Ιούνιος', 'Ιούλιος', 'Αύγουστος',
'Σεπτέμβριος','Οκτώβριος','Νοέμβριος','Δεκέμβριος');

print("<select name='day'>\n");
for($d=1;$d<=31;$d++){

    print("<option value=$d>$d</option>\n");
}
print("</select>\n");

print("<select name='months'>\n");
foreach($months as $k => $m){
    print("<option value=$k>$m</option>\n");
}

print("</select>\n");

print("<select name='years'>\n");
for($y=2015;$y<=2025;$y++){
    print("<option value=$y>$y</option>\n");
}

print("</select>\n");
}


//Δημιουργεί μάι φόρμα και καλεί την συνάρτηση
print("<h1>Επιλέξτε μία Ημερομηνία</h1>
<form action='calendar.php' method='post'</h1>\n");

make_calendar_pulldown();

print("</form>");

?>


<?php
include('includes/footer.php');
?>