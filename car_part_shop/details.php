<?php
session_start();
include('includes/header.php');
include('includes/left_content.php');



if($_SESSION["cnt"] === $_SESSION["totalPrice"]){
  $_SESSION["cnt"] =0;
  $_SESSION["totalPrice"]=0; 
}
    ?>

      <div class="center_title_bar">EXPRESS Car Parts</div>
      <div class="prod_box_big">
        <div class="center_prod_box_big">
          <div class="product_img_big"> <img src="image/logo.jpg" alt="" border="0" width="182" height="85" />
           
          </div>
          <div class="details_big_box">
            <div class="product_title_big">EXPRESS Car Parts</div>
            <div class="specifications"> Ύδριση: <span class="blue">Από το 1989</span><br />
            Εγγύηση: <span class="blue">24 μήνες τουλάχιστον σε όλα μας τα προιόντα</span><br />
              Πολιτική μεταφοράς προιόντων: <span class="blue"> Παράδοση σε όλη την Ελλάδα</span><br />
             <br />
              Περιγραφή :<span class="blue"> 
                Παρέχουμε την μέγιστη ποιότητα στην χαμηλότεση τιμή. 
                Δωρεάν αλλαγές εάν δεν μείνετε ευχαριστιμένου από το προιόν. 
                Πιστοποίηση καλύτερου προμηθευτή για τα έτοι 2014-2017.
              </span><br />
            </div>
          </div>
        </div>
      </div>
      <?php
      include('includes/right_content.php');
      include('includes/footer.php');
      ?>
    