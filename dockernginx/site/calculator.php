<?php
$page_title = "Υπολογιστής";
include('includes/header.php');

/*Όταν καλώ την συνάρτηση με 2 ορίσματα θα μπει αυτόματα 24% εάν όμως βάλει 3 ορίσματα θα πάρει αυτό που έβαλα */
function calculate_total($qty, $cost, $taxrate = 24 ){
    $total = $qty * $cost;
    $total += $total*($taxrate/100);   
    return array($total, $taxrate);  
    //έτσι επιστρέφω 2 τιμές 
}



if(filter_input(INPUT_POST,'submitted')){
//Εδώ δεν χρησιμοποιώ το submitted αλλά πάιρνει την τιμή 1.
//και εδώ θα μπείνια μέσα μόνο με αίτηση POST
    $filter_option = array("options" => array("min_range" => 0)); 
    /*Υπάρχει στο W3 στα FilterAdvances καφάλαιο. Τυπικά το παραπάνω βάσει ότι οι τιμές θα είνια 
    μεγαλύτερες του μηδέν 0.  για ευκολία στην κατανόηση το εισάγω σε μία μεταβλήτή αυτόν τον έλεγχο
    τη $filter_option και μετά από κάτω την τοποθετώ σε κάθε μία από τις τιμές*/
    
    //θα μπορούσα να βάλω και ,"max_range" => 1000 όλες αυτές οι λέξεις option, max. min είνια δεσμευμένες για το FILTER_VALIDATE_FLOAT αν δω την ιστοσελίδα
    $quantity = filter_input(INPUT_POST,'quantity',FILTER_VALIDATE_INT,$filter_option);
    $price = filter_input(INPUT_POST,'price',FILTER_VALIDATE_FLOAT,$filter_option);
    $tax = filter_input(INPUT_POST,'tax',FILTER_VALIDATE_FLOAT,$filter_option);
   

    if($quantity && $price){ //έβγαλα το && tax γιατί 

        //Ορίζω αν θα είνια με ένα ή δύο ορίσματα
        if ($tax){
            list($total, $tax) = calculate_total($quantity, $price, $tax);
            
        } else {
           /* $temp_array = calculate_total($quantity, $price);
            $total = $temp_array[0];
            $tax = $temp_array[1];
            Με την από κάτω σειρά γλιτώνω την από πάνω καθώς η συνάρτηση επιστρέφει 2 τιμές*/

            list($total, $tax) =  calculate_total($quantity, $price);
        }       
        //Το $total είναι διαφορετική από αυτή που είνια μέσα στη συνάστηση γιατί υπάρχει τοπικότητα        
        $total_str = number_format($total, 2 ,',','.');
        $price_str = number_format($price, 2 ,',','.');
        print("<p>Αγοράστε <b></b>$quantity \n</b> 
    αντικείμενα προς \$<b>$price_str</b> έκαστο. Με φόρο <b>$tax</b> το σύνολο είναι 
    \$<b>$total_str</b>.</p>");
        
    //όλο το παρακάτω το έκανα σε function παραπάνω
       /* $total = $quantity * $price;
        $total += $total*($tax/100);
        $total = number_format($total, 2 ,',','.');*/

       
        //include('includes/footer.php');
        //exit();
        //Εάν θέλω να εξαφανιστει το πεδίο των καταχωρήσεων και να μην εξαφανίζεται το footer

    }else{
        print("<h1>Σφάλμα</h1>");
        print("<p class='error'>Παρακαλώ εισάγετε έγκυρο αριθμό ποσότητας, 
        τιμής και φόρου.</p>\n");
    }

}
?>



<h1> Υπολογιστής κόστους Αντικειμένου </h1>
<form action="calculator.php" method="post">
<p> Ποσότητα: <input type="text" name="quantity" size="5" maxlength="5" 
value="
<?php
if(isset($quantity)) 
print($quantity);
?>"> </p>
<p> Τιμή: <input type="text" name="price" size="5" maxlength="10"
value="
<?php
if(isset($price)) 
print($price);
?>"> </p>
<p> Φόρος (%): <input type="text" name="tax" size="5" maxlength="5"
value="
<?php
if(isset($tax)) 
print($tax);
?>"> (προεραιτικό) </p>
<p> <input type="submit" name="submit" value="Υπολογισμός"> </p>
<input type="hidden" name="submitted" value="1">
</form>




<?php
include('includes/footer.php');
?>