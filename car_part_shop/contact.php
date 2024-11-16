<?php
session_start();
include('includes/header.php');
include('includes/left_content.php');



if($_SESSION["cnt"] === $_SESSION["totalPrice"]){
  $_SESSION["cnt"] =0;
  $_SESSION["totalPrice"]=0; 
}


    ?>

      <div class="center_title_bar">Επικοινωνήστε μαζί μας</div>
      <div class="prod_box_big">
      <div class="specifications"> 
        Διεύθυνση : <span class="blue"> Αργέου 3 Ν.Σμύρνη Αθήνα</span><br />
        Τηλέφωνο : <span class="blue">210 - 6666666</span><br />
        Fax : <span class="blue">210 - 6666667</span><br /><br />
        Διαφορετικά μπορείτε να επικοινωνήσετε μαζί μας μέσω της παρακάτω φόρμας. 
        
        
      </div>

      <div class="prod_box_big">
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>Όνομα:</strong></label>
              <input type="text" class="contact_input" />
            </div>
            <div class="form_row">
              <label class="contact"><strong>Email:</strong></label>
              <input type="text" class="contact_input" />
            </div>
            <div class="form_row">
              <label class="contact"><strong>Τηλέφωνο:</strong></label>
              <input type="text" class="contact_input" />
            </div>
            <div class="form_row">
              <label class="contact"><strong>Εταιρία:</strong></label>
              <input type="text" class="contact_input" />
            </div>
            <div class="form_row">
              <label class="contact"><strong>Σχόλια:</strong></label>
              <textarea class="contact_textarea" ></textarea>
            </div>
            <div class="form_row"> <a href="#" class="prod_details">Αποστολή</a> </div>
          </div>
        </div>
      </div>
      </div>
    

      <?php
  include('includes/right_content.php');
  include('includes/footer.php');
  ?>
