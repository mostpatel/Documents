<?php
$page="categories";
include_once('admin/lib/cg.php');
include_once('admin/lib/category-functions.php');
include_once('admin/lib/super-category-functions.php');
include_once('header.php');
$categories = listCategories();

?>
<!-- Industrial market start -->
	<section id="ind-market" class="ind-market">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h2 class="title-border"><strong>Product Category</strong></h2>
				</div>
			</div> <!-- Title row end -->

		</div>
		
		<div class="container">
			<div class="row">
				<div id="isotope" class="isotope" style="position: relative; height: 758px;">
					 <?php 
				foreach($categories as $category) { ?>
					<div class="col-sm-4 <?php echo $category['super_cat_id']; ?> isotope-item text-center" style="position: absolute; left: 0px; top: 0px;">
						<div class="isotop-img-conatiner">
							<img class="img-responsive" src="<?php echo WEB_ROOT; ?>images/category/<?php echo $category['cat_img_path']; ?>" alt="<?php echo $category['cat_name']; ?>">
						<!--	<a class="isotop-readmore"  href="products.php?id=<?php echo $category['cat_id']; ?>"><i class="fa fa-link"></i></a> -->
                        <a class="isotop-readmore"  href="contact_us.php?sub=<?php echo $category['cat_name']; ?>"><i class="fa fa-link"></i></a>
						</div>
						<div class="isotope-item-title">
							<h3><a href="products.php?id=<?php echo $category['cat_id']; ?>"><?php echo $category['cat_name']; ?></a></h3>
						</div>
						
					</div><!-- Isotope item end -->
			<?php } ?>
					

				</div><!--/ Isotope content end -->
			</div><!--/ Content row end -->
		</div><!--/ Container end -->
	</section><!--/ Industrial market end -->


    

	

<?php
include_once('footer.php');
?>
