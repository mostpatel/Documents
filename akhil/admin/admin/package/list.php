<div class="insideCoreContent adminContentWrapper wrapper">
<h4 class="headingAlignment no_print">List Of Packages</h4>

<div class="printBtnDiv no_print"><button class="printBtn btn"><i class="icon-print"></i> Print</button></div>
	  <div class="no_print">
    <table id="adminContentTable" class="adminContentTable no_print">
    <thead>
    	<tr>
        	<th class="heading">No</th>
            <th class="heading">Name</th>
             <th class="heading">Package Category</th>
            <th class="heading">Location</th>
            <th class="heading">Days</th>
            <th class="heading">Nights</th>
            <th class="heading">Featured</th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
            <th class="heading no_print btnCol"></th>
        </tr>
    </thead>
    <tbody>
        
        <?php
		$locations=listPackages();
		$i=0;
		foreach($locations as $location)
		{
			$package_id=$location['package_id'];
			$package_types=getPackageTypeForPackage($package_id);
	        $package_itenary=getItenaryForPackageId($package_id);
	        $package_location = getLocationForPackage($package_id);
			$package_category = getPackageCategoryForPackage($package_id);
			$is_featured = getIfPackageFeaturedOrNot($package_id);
			$no_of_packages = getNumberOfFeaturedPackages();
		 ?>
          <tr class="resultRow">
        	<td><?php echo ++$i; ?>
            </td>
            <td><?php echo $location['package_name']; ?>
            </td>
            <td>
             <?php foreach($package_category as $l) echo $l['pkg_cat_name']." "; ?>	
            </td>
             <td> <?php foreach($package_location as $l) echo $l['location_name']." | "; ?>			
            </td>
            <td><?php echo $location['days']; ?>
            </td>
            <td><?php echo $location['nights']; ?>
            </td>
            <td><a href="<?php if($is_featured) echo $_SERVER['PHP_SELF'].'?action=delFeatured&id='.$location['package_id']; else if($no_of_packages<3) echo $_SERVER['PHP_SELF'].'?action=addFeatured&id='.$location['package_id']; ?>"><button title="View this entry" class="btn <?php if($is_featured) { ?>btn-danger<?php }else if($no_of_packages<3) { ?> btn-success <?php } else { ?> btn-warning<?php } ?>"><?php if($is_featured) { ?>Remove Featured<?php }else if($no_of_packages<3) { ?> Make Featured <?php } else { ?>Only 3 Allowed<?php } ?></button></td>
              <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=details&id='.$location['package_id'] ?>"><button title="View this entry" class="btn viewBtn"><span class="view">V</span></button></a>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?view=edit&id='.$location['package_id'] ?>"><button title="Edit this entry" class="btn splEditBtn"><span class="edit">E</span></button></a>
            </td>
             <td class="no_print"> <a href="<?php echo $_SERVER['PHP_SELF'].'?action=delete&id='.$location['package_id'] ?>"><button title="Delete this entry" class="btn delBtn"><span class="delete">X</span></button></a>
            </td>
           
            
          
  
        </tr>
         <?php }?>
         </tbody>
    </table>
      </div>
       <table id="to_print" class="to_print adminContentTable"></table> 
</div>
<div class="clearfix"></div>