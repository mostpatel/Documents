<?php
if(!isset($_GET['id']))
{
	header("Location: index.php");
}
$file_id = $_GET['id'];
$customer_id = $_GET['state'];
$cheque_details =ListChequesForFileId($file_id);
?>

<div class="insideCoreContent adminContentWrapper wrapper">

<div class="mainClass">

<?php for($no=0;$no<=2;$no++) { ?>
  
  <div class="section1">
  
    <div class="ecs_top">
    
    	<div class="ecs_top_left">
           
           <div class="company_name">
           Aadhya <br />Finance Service
           </div> <!-- End of company_name -->
           
           <div class="tick_table">
            <p style="font-size:12px;"> Tick [✔]</p>
              <table class="tableStyling">
                
                <tr>
                <td> CREATE </td>
                <td width="25px"> [✔] </td>
                </tr>
                
                <tr>
                <td> MODIFY </td>
                <td width="25px"> </td>
                </tr>
                
                <tr>
                <td> CANCEL </td>
                <td width="25px"> </td>
                </tr>
                
              </table>
           </div> <!-- End of tick_table -->
           
        </div> <!-- End of ecs_top_left -->
        
        <div class="ecs_top_right">
         
         <div class="oneRow" style="margin-top:10px;">
           
           <div  class="UMRN">
           <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;">UMRN : </span> 
           
           <span style="height:20px;width:87%;border:1px solid #000;display:inline-block;float:left"></span>
           
           </div> <!-- End of UMRN -->
           
           <div class="ecsdate" style="text-align:right">
           Date : <span style="font-weight:bold;font-size:14px;font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:bold;line-height:20px;border:1px solid #000;padding:5px;"><?php echo date('d/m/Y',strtotime(getTodaysDate())); ?></span>
           </div> <!-- End of date -->
           
           <div class="clr"></div>
           
         </div> <!-- End of oneRow -->
         
         
         <div class="oneRow">
           
           <div class="bank_code">
          <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;"> Sponsor Bank Code : </span>
           
           <span style="height:20px;width:60%;border:1px solid #000;display:inline-block;float:left;font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:bold;line-height:20px;padding-left:10px;letter-spacing:2px;">
          ADBX
           </span>
          
           </div> <!-- End of bank_code -->
           
           <div class="utility_code">
           Utility Code : <span style="font-weight:bold;font-size:14px;font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:bold;line-height:20px;border:1px solid #000;padding:2px;padding-right:100px;letter-spacing:3px;">NACH00000000001841</span>
           </div> <!-- End of utility_code -->
           
           <div class="clr"></div>
           
         </div> <!-- End of oneRow -->
         
          <div class="oneRow">
           
           <div class="authorize">
           I/We hereby authorize :<b style="font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;text-decoration:underline;font-size:14px;"> Aadhya Finance Services</b>  
           </div> <!-- End of authorize -->
           
          <div class="to_debit_tick">
          to debit Tick [✔] SB/CA/CC/SB-NRE/SB-NRO/Other
          </div> <!-- End of to_debit_tick -->
           
           <div class="clr"></div>
           
         </div> <!-- End of oneRow -->
         
           <div class="oneRow">
           
           <div class="bank_ac_number">
           Bank A/C Number : <span style="font-weight:bold;font-size:14px;font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:bold;line-height:20px;border:1px solid #000;padding:2px;padding-right:450px;letter-spacing:3px;"><?php echo $cheque_details['ac_no']; ?></span>
           </div> <!-- End of bank_ac_number -->
           
           
         </div> <!-- End of oneRow -->
         
        </div> <!-- End of ecs_top_right -->
        
        <div class="clr"></div>
        
    </div> <!-- End of top -->
    
    <div class="ecs_middle">
    
    <div class="oneRowMiddle">
           
           <div class="with_bank">
          <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;"> With Bank </span>  <span style="height:20px;width:77%;border:1px solid #000;display:inline-block;font-family:Gotham, 'Helvetica Neue', Helvetica, Arial, sans-serif;font-weight:bold;padding-left:10px;"><?php echo $cheque_details['bank_name']; ?></span>
           </div> <!-- End of with_bank -->
           
          <div class="IFSC">
         <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;"> IFSC          </span><span style="height:20px;width:77%;border:1px solid #000;display:inline-block;"></span>
          </div> <!-- End of IFSC -->
          
          <div class="MICR">
         <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;"> OR MICR          </span><span style="height:20px;width:70%;border:1px solid #000;display:inline-block;"></span>
          </div> <!-- End of MICR -->
           
           <div class="clr"></div>
           
     </div> <!-- End of oneRow -->
     
     
     <div class="oneRowMiddle">
           
           <div class="amount_in_words">
         <span style="height:20px;vertical-align:middle;display:inline-block;float:left;line-height:20px;margin-right:10px;">  an Amount of Rupees </span> <span style="height:20px;width:70%;border:1px solid #000;display:inline-block;"></span>
           </div> <!-- End of amount_in_words -->
           
          <div class="amount_in_number">
                &nbsp; ₹    
          </div> <!-- End of amount_in_number -->
          
           
           <div class="clr"></div>
           
     </div> <!-- End of oneRow -->
     
     <div class="oneRowMiddle">
           
           <div class="frequency">
           FREQUENCY : <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  Mthly
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  Qthly
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  H-yrly
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  Yrly
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  As & when presented
           <span style="margin-left:50px;">DEBIT Type</span>
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  Fixed Amount
           <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>  Maximum Amount
           </div> <!-- End of frequency -->
           
          
          
           
           <div class="clr"></div>
           
     </div> <!-- End of oneRow -->
     
     <div class="oneRowMiddle">
           
           <div class="reference1">
           Reference 1 :<span style="height:20px;width:70%;border-bottom:1px solid #000;display:inline-block;"></span>
           </div> <!-- End of reference1 -->
           
          <div class="phone_no">
            Phone No :   <span style="height:20px;width:70%;border-bottom:1px solid #000;display:inline-block;"></span>
          </div> <!-- End of phone_no -->
          
           
           <div class="clr"></div>
           
     </div> <!-- End of oneRow -->
     
     <div class="oneRowMiddle">
           
           <div class="reference2">
           Reference 2 :<span style="height:20px;width:70%;border-bottom:1px solid #000;display:inline-block;"></span>
           </div> <!-- End of reference2 -->
           
          <div class="email_id">
            Email ID :   <span style="height:20px;width:70%;border-bottom:1px solid #000;display:inline-block;"></span>
          </div> <!-- End of email_id -->
          
           
           <div class="clr"></div>
           
     </div> <!-- End of oneRow -->
     
     
    </div> <!-- End of middle -->
    
    <div class="ecs_bottom">
    
      <div class="ecs_bottom_left" style="border:1px solid #000;padding-left:10px;">
       <span>PERIOD</span>
       <div class="period_from">
       From <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       </div>
       
       <div class="period_from">
       To
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>
       </div>
       
       <div class="period_from">
       Or &nbsp; &nbsp; <span style="height:20px;width:20px;border:1px solid #000;display:inline-block;top:5px;position:relative;"></span>   Until Cancelled
       </div>
       
      </div> <!-- End of ecs_bottom_left -->
      
      <div class="ecs_bottom_right">
      
        <div class="ecs_bottom_right_upper">
        
          <div class="signature_section" >
          Signature Primary Account Holder
          </div> <!-- End of signature_section -->
          
          <div class="signature_section">
          Signature Account Holder
          </div> <!-- End of signature_section -->
          
          <div class="signature_section">
          Signature Account Holder
          </div> <!-- End of signature_section -->
          
          <div class="clr"></div>
          
        </div> <!-- End of ecs_bottom_right_upper -->
        
        <div class="ecs_bottom_right_lower">
        
        <div class="signature_section">
          Name as in Bank Record
          </div> <!-- End of signature_section -->
          
          <div class="signature_section">
          Name as in Bank Record
          </div> <!-- End of signature_section -->
          
          <div class="signature_section">
          Name as in Bank Record
          </div> <!-- End of signature_section -->
          
          <div class="clr"></div>
        
        </div> <!-- End of ecs_bottom_right_lower -->
        
      </div> <!-- End of ecs_bottom_right -->
      
      <div class="clr"></div>
      
      <div class="terms_condition" style="font-size:11px;">
      <ol>
      	<li> This is to inform that the declaration has been carefully read, understand and made by me/us. I am authorizing the user entity. corporate to debit my account, based on instructor as agreed & signed by me.</li>
        <li> I have understood that i am authorized to cancel/amend this mandate by appropriately communicating the cancellation/amendment request to the entity/corporate the bank where i have authorized the debit.</li>
      </ol>
      </div> <!-- End of terms_condition -->
      
      
    </div> <!-- End of bottom -->
    
  </div>  <!-- End of section1 -->
  <?php if($no<2) { ?>
  <hr style="margin-top:5px; margin-bottom:5px" />
  <?php } ?>

  <?php } ?>
 
  
</div> <!-- End of mainClass -->

      
</div>
<div class="clearfix"></div>
