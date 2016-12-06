<?php
$page="categories";
require_once('admin/lib/cg.php');
include_once('header.php');
require_once('admin/lib/category-functions.php'); 
require_once('admin/lib/sub-category-functions.php'); 

$category = getCategoryById($_GET['id']);
$subCats = getsubCategoryByCategoryId($_GET['id']);
?>

<!-- Industrial market start -->
	<section id="ind-market" class="ind-market">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-border"><strong>Products of <?php echo $category['cat_name']; ?></strong></h2>
				</div>
			</div> <!-- Title row end -->

		</div>
		
		<div class="container">
			<div class="row">
				<div id="isotope" class="isotope" style="position: relative; height: 758px;">
                
                <?php
			 foreach($subCats as $subCat)
			 {
				 
				
			?>
					<div class="col-sm-4 gas isotope-item text-center" style="position: absolute; left: 0px; top: 0px;">
						<div class="isotop-img-conatiner">
							<img class="img-responsive" src="<?php echo WEB_ROOT.'images/category/'.$subCat['sub_cat_img_path']; ?>" alt="">
							<div class="isotop-readmore" style="text-align:center;width:80%;margin-left:10%;"><?php echo $subCat['sub_cat_name']; ?></div>
						</div>
						<div class="isotope-item-title">
							<h3><a href="<?php echo $subCat['youtube_link']; ?>"><?php echo $subCat['sub_cat_name']; ?></a></h3>
						</div>
						
					</div><!-- Isotope item end -->

<?php
			 }
?>
				</div><!--/ Isotope content end -->
			</div><!--/ Content row end -->
		</div><!--/ Container end -->
	</section><!--/ Industrial market end -->


    

	

<?php
include_once('footer.php');
?>
