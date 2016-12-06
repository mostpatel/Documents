<link rel="stylesheet" href="../../../css/a5.css" />
<div class="mainInvoiceContainer">

  <div class="sectionOne">
        
          <div class="leftSectionOne">
             <img src="<?php echo WEB_ROOT."images/logo.png" ?>" style="min-width:170px;min-height:80px" /> 
            
          </div>   <!-- End of LeftSectionOne -->
          
          <div class="rightSectionOne">
          
           <div class="companyName"> Vaibhav Auto Parts & Service Station </div>
            
            <div class="address">
             Shop No.: 1/2, Raghuvir chambers, 
    Opp. S.T. Bus Stop, Naroda Gam, 
    Ahmedabad-30.<br> Ph : 22810533, Fax : 22815708, Mob : 9825043973
            </div>   <!-- End of address -->
            
            
            
          </div>   <!-- End of rightSectionOne -->
          
          <div class="clearFix"></div>
          
        </div> <!-- End of SectionOne -->
        
        <div class="sectionTwo">
        
        <div class="chalanNo">
        Invoice No : 1665
        </div>   <!-- End of chalanNo -->
        
        <div class="date">
        Date : 02/09/2014
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionTwo -->
  
  
        <div class="sectionThree">
        
        <div class="cusName">
        Name : Jeet Rameshchandra Patel
        </div>   <!-- End of cusName -->
        
        <div class="vNo">
        Vehicle No : GJ1FS9138
        </div>   <!-- End of vNo -->
        
         <div class="clearFix"></div>
           
        </div> <!-- End of sectionThree -->
        
        <div class="sectionFour">
        
          <table border="1" class="vaibhavTable">
            <tr>
            
            <td> No. </td>
            <td> Component Name </td>
            <td> Rate </td>
            <td> Qty. </td>
            <td> Discount (%) </td>
            <td> Net Amount</td>
            
            </tr>
            
            
            <?php 
			for($i=0; $i<=5; $i++)
			{
			?>
            <tr>
            <td> 1 </td>
            <td> Piston </td>
            <td> 2500 </td>
            <td> 10 </td>
            <td> 0 </td>
            <td> 25000 </td>
            </tr>
            
            <?php
			}
			?>
          </table>
           
        </div> <!-- End of sectionFour -->
        
        
        <div class="sectionFive">
        
        <div class="customerSign">
        Sign : asdf
        </div>   <!-- End of chalanNo -->
        
        <div class="total">
        Total : Rs. 150000
        </div>   <!-- End of date -->
        
         <div class="clearFix"></div>
           
        
        </div>  <!-- End of sectionFive -->
  
    
</div>  <!-- End of mainInvoiceContainer -->

</body>
</html>