</div><!-- end of coreContent -->
<div class="clearfix"></div>
</div><!-- end of content -->
<div id="footer" class="no_print">
	<div class="footerProduct">A Tap & Type Product</div>
    <div class="footerWeb"><a href="http://www.tapandtype.com/">www.tapandtype.com</a></div>
    <div class="footerContact">Contact : 09978812644, 09824143009</div>
</div>

 <script type="text/javascript">
	
	
	 $('#backdrop').hide(); 
    </script>
</div><!-- end of mainDiv -->
	
    <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/jquery.tables.min.js"></script>
     <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/ZeroClipboard.js"></script>
        <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/TableTools.js"></script>
	<script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/bp.min.js"></script>
    <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/table.js"></script>
     <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/common.js"></script>
      <script type="text/javascript" src="<?php echo WEB_ROOT; ?>js/confirmDeletion.js"></script>
  <script src="<?php echo WEB_ROOT; ?>js/loginDivAccount.js"></script>	
    <?php
	if(isset($jsArray))
	{
		foreach($jsArray as $js)
		{
	?>
    <script type="text/javascript" src="<?php echo WEB_ROOT."js/".$js; ?>"></script>
    <?php
			}
		}
	 ?>
</body>
</html>
