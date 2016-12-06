<div class="adminContentWrapper wrapper">
<!--
<h4 class="headingAlignment">Ledgers</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="ledgers/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Ledgers 
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="../settings/general_settings/ledgers_group_settings/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Ledgers Group
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="customer_ledgers/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Manage Customer's Opening Balances 
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="ledger_transactions/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     View Ledger's Transactions 
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="entries/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     View Ledger's Entries
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="group_entries/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     View Ledgers Group's Entries
     </div>
     
     </div>
     
      
  </div>
  <div class="rowOne">

        <div class="package">
     
     <a href="register_entries/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Register's Entries
     </div>
     
     </div>
     
  
        <div class="package">
     
     <a href="reconciliation/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Recouncilation
     </div>
     
     </div>
      <?php if(TAX_MODE==0) { ?>
       <div class="package">
     
     <a href="inventory_jv_report/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Inventory JV Reports
     </div>
     
     </div>
     <?php } ?>
  </div>
     
</div>

<h4 class="headingAlignment">Transactions</h4>

<div class="settingsSection">

<div class="rowOne">

  <div class="package">
     
     <a href="transactions/receipt/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Receipt 
     </div>
     
     </div>
     
       <div class="package">
     
     <a href="transactions/payment/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Payment 
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="transactions/jv/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Journal Entry
     </div>
    
     
     </div>
     
     <div class="package">
     
     <a href="transactions/contra/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Contra
     </div>
     
     </div>

     
     
     
   
     
     
     
     
     
   
     
      
  </div>
 
  <div class="rowOne">
     <?php if(TAX_MODE==0) { ?>
    <div class="package">
     
     <a href="transactions/purchase_order/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Purchase Order
     </div>
     
     </div>
   
      <div class="package">
     
     <a href="transactions/purchase_inventory/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Purchase 
     </div>
     
     </div>
   <?php } ?>
     <div class="package">
     
     <a href="transactions/delivery_challan/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
	<?php echo DELIVERY_CHALLAN_NAME; ?>
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="transactions/sales_inventory/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     <?php echo SALES_NAME; ?> 
     </div>
     
     </div>
     
     <?php if(TAX_MODE==0) { ?>
     <div class="package">
     
     <a href="transactions/debit_note/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Debit Note 
     </div>
     
     </div>
     
     <div class="package">
     
     <a href="transactions/credit_note/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Credit Note 
     </div>
     
     </div>
     <?php } ?>
      
    
    </div> 
   


 <?php if(defined('DELIVERY_CHALLAN_BULK') && DELIVERY_CHALLAN_BULK==1){ ?>
  <div class="rowOne">
   
   
      <div class="package">
     
     <a href="transactions/delivery_challan/?bulk=1">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Bulk <?php echo DELIVERY_CHALLAN_NAME; ?> Customer Group Wise 
     </div>
     
     </div>
   
     <div class="package">
     
     <a href="transactions/delivery_challan/?bulk=2">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
      Bulk <?php echo DELIVERY_CHALLAN_NAME; ?> Customer Wise
     </div>
     
     </div>
     
     </div>
    <?php } ?>
</div-->

<h4 class="headingAlignment">Inventory Transactions</h4>

<div class="settingsSection">

<div class="rowOne">

      <div class="package">
     
     <a href="transactions/purchase_order/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Purchase Order
     </div>
     
     </div>
     
     
      <div class="package">
     
     <a href="transactions/debit_inventory_jv/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Inwards Inventory Transaction
     </div>
     
     </div>
     
      <div class="package">
     
     <a href="transactions/credit_inventory_jv/">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Outwards Inventory Transaction
     </div>
     
     </div>
        
  </div>
     
</div>
<!--
<h4 class="headingAlignment">Balance & PL Sheet</h4>

<div class="settingsSection">

<div class="rowOne">

     <div class="package">
     
     <a href="balance_sheet">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Balance Sheet
     </div>
     
     </div>
     
       <div class="package">
     
     <a href="pl_sheet">
     <div class="squareBox">
         <div class="imageHolder">
         </div>
     </div>
     </a>
     
     <div class="explanation">
     Profit & loss A/c 
     </div>
     
     </div>
     
     
     
      
  </div>
     
</div>

-->

     
     
   </div>
     
      
  