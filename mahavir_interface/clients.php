<?php
$page="clients";
require_once('admin/lib/cg.php');
  require_once('header.php'); 
  require_once('admin/lib/category-functions.php');
  require_once('admin/lib/event-functions.php'); 
 $events = listEvents();
?>

<section id="main-container">

		<div class="container">



			<!-- Clients -->

			<div class="row">

				<div class="col-md-12">

					<h2 class="article-title">Our Clients</h2>

				</div>

			</div><!-- Title row end -->



			<div class="row" style="margin-top:25px;">

				<div class="col-md-12">
                
               
					
                    
                     <?php
			 foreach($events as $event)
			 {
			?>


					

							<div class="client-logo-item col-md-2" style="height:120px;margin:10px;box-sizing:border-box;">

					          <a href="#">

					            <img src="<?php echo WEB_ROOT.'/images/event/'.$event['event_img_path']; ?>" alt="client" width="90%" style="margin-left:15%;">

					          </a>

				        	</div>

					
                        
                        <?php
			            }
						?>

						
				

<div class="gap-20"></div>

				</div><!--/ 1st Clients end -->
              </div><!--/ Content row end -->
          </div><!--/ container end -->
      </section><!--/ Main container end -->

	





	<div class="gap-40"></div>



<?php
include_once('footer.php');
?>
