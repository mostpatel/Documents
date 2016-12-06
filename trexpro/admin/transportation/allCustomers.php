<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment">List of Parties</h4>
    <div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
   	<div class="no_print">
    <table id="adminContentTable" class="adminContentTable">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
            <th class="heading">Address</th>
            <th class="heading">Contact No</th>
            <th class="heading">Opening Balance</th>
            <th class="heading no_print btnCol" ></th>
             <th class="heading no_print btnCol" ></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$parties=listCustomer();
		$no=0;
		if($parties!=false)
		{ 
		foreach($parties as $agencyDetails)
		{
		 ?>
         <tr class="resultRow">
        	<td><?php echo ++$no; ?>
            </td>
            <td><?php  echo $agencyDetails['customer_name']; ?>
            </td>
            <td><?php echo $agencyDetails['customer_address'] ?>
            </td> 
             <td><?php $contact_nos=$agencyDetails['contact_no']; foreach($contact_nos as $contact_no) echo $contact_no[0]."<br>"; ?>
            </td>
            <td><?php echo $agencyDetails['opening_balance']; if($agencyDetails['opening_cd']==0) echo " Dr"; else echo " Cr"; ?>
            </td> 
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$agencyDetails['customer_id']; ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
             </td>
            <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=editCustomer&id='.$agencyDetails['customer_id']; ?>"><button title="Edit this entry" class="btn editBtn splEditBtn "><span class="delete">E</span></button></a>
            </td>
            <td class="no_print"> 
            <a href="<?php echo $_SERVER['PHP_SELF'].'?action=deleteCustomer&lid='.$agencyDetails['customer_id']; ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
            
          
  
        </tr>
         <?php } }?>
         </tbody>
    </table>
    </div>
     <table id="to_print" class="to_print adminContentTable"></table>
</div>
<div class="clearfix"></div>     