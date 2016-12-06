<?php
require_once "../lib/cg.php";
require_once "../lib/bd.php";
require_once "../lib/common.php";

require_once "../lib/sub-category-functions.php";
require_once "../lib/category-functions.php";
require_once "../lib/super-category-functions.php";

require_once "../lib/adminuser-functions.php";

require_once "../lib/quotes-functions.php";

$selectedLink="home";
require_once("../inc/header.php");

$quote_counter_id = getCurrentQuoteCounter();
$todaysQuote = getQuoteByCurrentQuoteId($quote_counter_id);
?>
<div class="insideCoreContent adminContentWrapper wrapper"> 
 <div class="widgetContainer">
 
    <div class="notificationCenter" style="margin-bottom:10px;">
       Quote of the Day
   </div>
   
   <div class="quoteDisplay" style="margin-bottom:30px;">
   "<?php echo $todaysQuote ?>"
   </div><!-- End of quoteDisplay-->
   
   
   
  
   
 
   
   

 
 
       
        
    
 </div>
 </div>
 <div class="clearfix"></div>

<?php
require_once("../inc/footer.php");
 ?> 