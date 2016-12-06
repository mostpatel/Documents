

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="../../../../css/a5.css" />
<title>Invoice - A5 Format</title>
</head>

<body>

<div class="mainInvoiceContainer">

  <div class="sectionOne">
        
          <div class="leftSectionOne">
            LOGO
            
          </div>   <!-- End of LeftSectionOne -->
          
          <div class="rightSectionOne">
          
            <div class="companyName"> ખુશ્બુ ઓટો પ્રાઇવેટ લીમીટેડ  </div>
            
            <div class="address">
             સરખેજ-બાવળા હાઇવે, જામનગર ટ્રાન્સપોર્ટની બાજુમાં, ઉજાલા સર્કલ પાસે, સરખેજ. ફોન : ૨૬૮૯૧૮૯૦

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
            
            <tr>
            
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td>  </td>
            <td> <span style="visibility:hidden"> blank </span> </td>
            
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