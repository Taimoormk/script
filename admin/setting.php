<?php include('header.php'); ?>
<div class="row">
<div class="col-md-3">
<div class="list-group">
	<a href="setting.php" class="list-group-item <?php if (!isset($_GET['case'])) {echo 'active';} ?>"><span class="fa fa-cog"></span> General Setting</a>
	<a href="setting.php?case=theme" class="list-group-item <?php if (isset($_GET['case']) AND $_GET['case'] == 'theme') {echo 'active';} ?>"><span class="fa fa-paint-brush"></span> Theme Setting</a>
	<a href="setting.php?case=clear_cache" class="list-group-item <?php if (isset($_GET['case']) AND $_GET['case'] == 'clear_cache') {echo 'active';} ?>"><span class="fa fa-eraser"></span> Clear Cache</a>
	<a href="setting.php?case=optimize_database" class="list-group-item <?php if (isset($_GET['case']) AND $_GET['case'] == 'optimize_database') {echo 'active';} ?>"><span class="fa fa-database"></span> Optimize Database</a>
	<a href="setting.php?case=update_statistics" class="list-group-item <?php if (isset($_GET['case']) AND $_GET['case'] == 'update_statistics') {echo 'active';} ?>"><span class="fa fa-bar-chart"></span> Update Statistics</a>
</div>
</div>
<div class="col-md-9">
<?php 
if (!empty($_GET['case'])) {
$case = make_safe($_GET['case']);	
} else {
$case = '';	
}
switch ($case) {
case 'optimize_database';
if (isset($_POST['save'])) {
	$message = notification('success','Database Optimized Successfuly.');
}   
?>

                <div class="page-header page-heading">
                    <h1><i class="fa fa-database"></i> Optimize Database</h1>
                </div>
	<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="facebook_account">Are you sure that you want to Optimize the Database ?</label>
		  </div>
		  <button type="submit" name="save" class="btn btn-warning">Optimize Database</button>
		</form>
	
<?php 
break;
case 'clear_cache';
if (isset($_POST['save'])) {
	$folder = '../cache';
	$delete = empty_templates_cache($folder);
	if ($delete) {
	$message = notification('success','All Cache Files Are Cleared.');
	} else {
	$message = notification('danger','Error Happened.');
	}
}   
?>

                <div class="page-header page-heading">
                    <h1><i class="fa fa-eraser"></i> Clear Cache</h1>
                </div>
	<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="facebook_account">Are you sure that you want to clear all cached files ?</label>
		  </div>
		  <button type="submit" name="save" class="btn btn-danger">Clear Cache</button>
		</form>
	
<?php 
break;
case 'update_statistics'; 
if (isset($_POST['topic'])) {
$query = $mysqli->query("SELECT * FROM categories ORDER BY id ASC");	
while ($row = $query->fetch_array()) {
$quotes_number = get_category_quotes($row['id']);	
$update = $mysqli->query("UPDATE categories SET quotes_number='$quotes_number' WHERE id='$row[id]'");
if ($update) {
$message = notification('success','Topics Quotes Number has been counted');
} else {
$message = notification('danger','Error Happened');
}
}
}  
if (isset($_POST['author'])) {
$query = $mysqli->query("SELECT * FROM authors ORDER BY id ASC");	
while ($row = $query->fetch_array()) {
$quotes_number = get_author_quotes($row['id']);	
$update = $mysqli->query("UPDATE authors SET quotes_number='$quotes_number' WHERE id='$row[id]'");
if ($update) {
$message = notification('success','Authors Quotes Number has been counted');
} else {
$message = notification('danger','Error Happened');
}
}
}
?>

        <div class="page-header page-heading">
            <h1><i class="fa fa-bar-chart"></i> Update Statistics</h1>
        </div>
	<?php if (isset($message)) {echo $message;} else {echo notification('warning','Be careful, This Action can\'t be undo.');} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label>Are you sure that you want to Update Statistics ? this operation may take some time to end.</label>
		  </div>
		  <button type="submit" name="topic" class="btn btn-primary">Update Topics Quotes Number</button>
		  <button type="submit" name="author" class="btn btn-default">Update Authors Quotes Number</button>
		</form>
	
<?php 
break;
case 'theme';
if (isset($_POST['save'])) {
	$all = base64_encode(serialize($_POST));
	$update = "UPDATE setting SET theme='$all'";
	$excute = $mysqli->query($update);
	if ($excute) {
	$message = notification('success','All Changes Saved.');
	} else {
	$message = notification('danger','Error Happened.');
	}
}
$query = "SELECT theme FROM setting";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();  
$theme = unserialize(base64_decode($row['theme']));
include('../themes/'.$general_setting['site_theme'].'/theme-options.php');
break;
default;
if (isset($_POST['save'])) {
	$all = base64_encode(serialize($_POST));
	$update = "UPDATE setting SET general='$all'";
	$excute = $mysqli->query($update);
	if ($excute) {
	$message = notification('success','All Changes Saved.');
	} else {
	$message = notification('danger','Error Happened.');
	}
}
$query = "SELECT general FROM setting";
$result = $mysqli->query($query);
$row = $result->fetch_assoc();  
$general = unserialize(base64_decode($row['general']));
?>
        <div class="page-header page-heading">
            <h1><i class="fa fa-cog"></i> General Setting</h1>
        </div>
		<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="siteurl">Site Url</label>
			<input type="text" class="form-control" name="siteurl" id="siteurl" placeholder="http://www.domain.com" value="<?php if (isset($general['siteurl'])) { echo $general['siteurl'];} ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_title">Site Name</label>
			<input type="text" class="form-control" name="seo_title" id="seo_title" placeholder="your site title" value="<?php if (isset($general['seo_title'])) { echo $general['seo_title']; } ?>" />
		  </div>
		  
		  <div class="form-group">
			<label for="seo_keywords">SEO Keywords</label>
			<input type="text" class="form-control seo-keywords" name="seo_keywords" id="seo_keywords" placeholder="news,rss,feeds" value="<?php if (isset($general['seo_keywords'])) { echo $general['seo_keywords']; } ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_description">SEO Description</label>
			<textarea class="form-control" name="seo_description" id="seo_description" rows="3" placeholder="some words about the site .. don't exceed 255 character."><?php if (isset($general['seo_description'])) { echo $general['seo_description']; } ?></textarea>
		  </div>
		   <div class="form-group">
			<label for="site_theme">Site Theme</label>
			<select name="site_theme" id="site_theme" class="form-control">
				<?php
				$path = '../themes/';
				$results = glob($path . "*");
					foreach ($results as $result) {
						if ($result === '.' or $result === '..') continue;
						if(is_dir($result)) {
						
						echo "
						<option value='".str_replace($path,'',$result)."'";
						if (isset($general['site_theme']) AND $general['site_theme'] == str_replace($path,'',$result)) {
						echo 'SELECTED';
						}
						echo ">".str_replace($path,'',$result)."</option>";		
						}
						}
						?>						
			</select>
		   </div>
		   <div class="form-group">
			<label for="sitemap_items">Number of Items (Quotes, Authors and Topics) in each Sitemap</label>
			<select name="sitemap_items" id="sitemap_items" class="form-control">
			<?php for($s=1000;$s<11000;$s=$s+1000) { ?>
				<option value="<?php echo $s; ?>" <?php if (isset($general['sitemap_items']) AND $general['sitemap_items'] == $s) {echo 'SELECTED';} ?>><?php echo $s; ?></option>
			<?php } ?>
			</select>
			</div>
		   <input type="hidden" name="installed" value="1" />
		  <button type="submit" name="save" class="btn btn-primary">Save</button>
		</form>
<?php } ?>
</div>
</div>
<?php include('footer.php'); ?>
 