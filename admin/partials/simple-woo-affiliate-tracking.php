<div class="wrap">
	<h2>Simple WooCommerce Affiliate Tracker</h2>
	<h3>This will export a CSV file with two columns, the "refid" and number of sales associated with that refid</h3>
	<form method="get" action="<?php echo admin_url('admin-post.php'); ?>">
		<input type="hidden" name="action" value="swat_export_csv">
		<input type="submit" class="button-primary" value="Export to CSV" />
	</form>
</div>