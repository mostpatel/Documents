<?php 
$body_id="home";
$active_link = "about";
require_once('admin/lib/cg.php');
require_once('admin/lib/bd.php');
require_once('admin/lib/common.php');
require_once('admin/lib/package-functions.php');
require_once('admin/lib/location-functions.php');
require_once('admin/lib/testonomial-functions.php');
require_once('admin/lib/careers-functions.php');
require_once('header.php');

?>
<div id="main" role="main">

<div class="row" style="padding-top:35px;">
	
	<section class="maincontent center p2">
		<h2 class="typ13 typ3u center" style="padding-bottom:20px;">Work with Bhagwati Holidays</h2>
		
        <p style="margin-bottom:20px;">
           Bhagwati Holidays is a coming of an age Travel Planning Company based out in Ahmedabad. We are really passionate about making people's travel exciting and as wonderful as possible. If you are an travel enthusiast as well then we would love to welcome to our family. As of now following are the openings at Bhagwati Holidays. If you fit into any of the following profiles, feel free to catch up with us.
        </p>
        
        <?php
		
		$careers = listCareers();
		
		foreach($careers as $career)
		{
			
			$positionName = $career['position_name'];
			$qualification = $career['qualification'];
			$description = $career['description'];
			$gender = $career['gender'];
			$no = $career['no'];
			
		?>
		<style
		
		>
        table tr td
		{
			padding:10px;
			padding-bottom:0px;
		}
		
		.bold
		{
			font-weight:bold;
		}
        </style>
        
        <h2 class="typ13 typ3u center" style="padding-bottom:20px;">Current Vacancies</h2>
        
        <table  style="margin-bottom:20px">
          <tr >
          
              <td align="left" class="bold">
              Position Name  
              </td>
              <td> : </td>
              <td align="left">
              <?php echo $positionName; ?>
              </td>
              
          </tr>
          
          <tr>
          
              <td align="left" class="bold">
              Qualification 
              </td>
              <td> : </td>
              <td align="left">
              <?php echo $qualification; ?>
              </td>
              
          </tr>
          
          <tr>
          
              <td align="left" class="bold">
              Description 
              </td>
              <td> : </td>
              <td align="left">
              <?php echo $description; ?>
              </td>
              
          </tr>
          
          <tr>
          
              <td align="left" class="bold">
              Preferred Gender
              </td>
              <td> : </td>
              <td align="left">
              <?php 
			  
			  if($gender==0)
			  {
				echo "No prefrence";
			  }
			  else if($gender==1)
			  {
				echo "Female";  
			  }
			  if($gender==2)
			  {
				echo "Male";  
			  }
			  
			  ?>
              </td>
              
          </tr>
          
          <tr>
          
              <td align="left" class="bold">
              No. of Position 
              </td>
              
              <td> : </td>
              
              <td align="left">
              <?php echo $no; ?>
              </td>
              
          </tr>
          
        </table>	
            
		<?php	
		}
		
		?>
		
		<p style="margin-bottom:20px;">
           Interested candidates can <a href="contact.php" style="color:#e2bb3d"> CONTACT US<a>.
        </p>

			

	</section>
    
    

</div>	
		
			
	

	

</div> <!-- #main -->
<?php require_once('footer.php'); ?>