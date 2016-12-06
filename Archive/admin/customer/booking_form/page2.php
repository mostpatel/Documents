<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}
$booking_id=$_GET['id'];
?>

<div class="buttons no_print">
<a href="<?php echo WEB_ROOT; ?>admin/customer/booking_form/index.php?view=bookingForm&id=<?php echo $booking_id; ?>">
<input type="button" value="+Back" class="btn btn-warning" />
</a>
</div> <!-- End of buttons -->


<div class="insideCoreContent adminContentWrapper wrapper">

<div class="mainClass">
  
  <div class="conditions">
  <img src="../../../images/page2.png" width="1024" />
  </div>  <!-- End of conditions -->
  
  <div class="signatures">
  
     <div class="ourSign">
     અસારવાવાળા અખિલ ભારત ટુર્સ એન્ડ ટ્રાવેલ્સ પ્રા. લિ. વતી સહી 

    <div class="signatureBox">
    </div>   <!-- End of signatureBox -->
    
     </div>  <!-- End of ourSign -->
     
     <div class="theirSign">
     ટિકિટ બુક કરાવનાર ની સહી 
     
     <div class="signatureBox">
    </div>   <!-- End of signatureBox -->
    
     </div>  <!-- End of theirSign -->
     
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
     
  </div>  <!-- End of signatures -->
  
     <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
     
  <hr />
  
  <div class="ticketInfo">
  
   <div class="ticketInfoHeading">
   ઓફીસ માટે જરૂરી રેલ્વે ટિકિટ, Air ટિકિટ ની વિગત 
   </div>  <!-- End of ticketInfoHeading -->
  
   <div class="railAir">
    Railway : Departure 
   </div>  <!-- End of railAir -->
   
   <div class="railDepartureDetails">
      
      <div class="oneFifth">
          Date : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Train No : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Coach : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Berth : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Rs. : 
      </div> <!-- End of oneFifth -->
      
      <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
      
      
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   
   <div class="railDepartureDetails" style="margin-bottom:7px;">
      
      <div class="oneThird">
               From :
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird">
               To :
      </div>  <!-- End of oneThird -->
               
       <div class="oneThird">
               PNR No. :
       </div>  <!-- End of oneThird -->
       
       <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
 <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   
   <div class="railAir">
    Railway : Return 
   </div>  <!-- End of railAir -->
   
   <div class="railDepartureDetails">
      
      <div class="oneFifth">
          Date : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Train No : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Coach : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Berth : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Rs. : 
      </div> <!-- End of oneFifth -->
      
      <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   
   <div class="railDepartureDetails" style="margin-bottom:7px">
      
      <div class="oneThird">
               From :
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird">
               To :
      </div>  <!-- End of oneThird -->
               
       <div class="oneThird">
               PNR No. :
       </div>  <!-- End of oneThird -->
       
       <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   
   
   <div class="railAir">
    Air : Departure 
   </div>  <!-- End of railAir -->
   
   <div class="railDepartureDetails">
      
      <div class="oneFifth">
          Date : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Flight No : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Dep. Time : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Airlines : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Rs. : 
      </div> <!-- End of oneFifth -->
      
      <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   
   <div class="railDepartureDetails" style="margin-bottom:7px">
      
      <div class="oneThird">
               From : 
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird">
               To : 
      </div>  <!-- End of oneThird -->
               
       <div class="oneThird">
               PNR No. : 
       </div>  <!-- End of oneThird -->
       
       <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   <div class="railAir">
    Air : Return 
   </div>  <!-- End of railAir -->
   
   <div class="railDepartureDetails">
      
      <div class="oneFifth">
          Date : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Flight No : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Dep. Time : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Airlines : 
      </div> <!-- End of oneFifth -->
      
      <div class="oneFifth">
          Rs. : 
      </div> <!-- End of oneFifth -->
      
      <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
   
   
   <div class="railDepartureDetails">
      
      <div class="oneThird">
               From : 
      </div>  <!-- End of oneThird -->
      
      <div class="oneThird">
               To : 
      </div>  <!-- End of oneThird -->
               
       <div class="oneThird">
               PNR No. : 
       </div>  <!-- End of oneThird -->
       
       <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
   
   </div> <!-- End of railDepartureDetails -->
   
   <div class="clearDiv">
     </div>  <!-- End of clearDiv -->
           
  
  </div>  <!-- End of ticketInfo -->
  
  <div class="clearDiv">
 </div>  <!-- End of clearDiv -->
  
</div> <!-- End of mainClass -->

      
</div>
<div class="clearfix"></div>
