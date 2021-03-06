<?php
error_reporting(E_ERROR);
set_time_limit(600000*60);
include('include/config.php');

?>
<!DOCTYPE html>
<html>
<head>
    <!-- Script Charset -->
	<meta charset="UTF-8">
	<!-- Style Viewport for Responsive purpose -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- The Script Base URL -->
		<title>Quotes CMS | Installation</title>
		<!-- CSS Files -->
		<link rel="stylesheet" href="themes/default/css/bootstrap.min.css">
		<link rel="stylesheet" href="themes/default/css/bootstrap-theme.min.css">
		<link rel="stylesheet" href="themes/default/css/font-awesome.min.css">
		<link href="http://fonts.googleapis.com/css?family=Titillium+Web:400,700" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="themes/default/css/style.css">
		<!-- Javascript Files -->
		<script src="themes/default/js/jquery.min.js"></script>
		<script src="themes/default/js/bootstrap.min.js"></script>
		<script type="text/javascript">
		$(function() {
		$("#install_authors").click(function() {
		$("#install_authors").append(" <i class='fa fa-spinner fa-spin'></i>");
		$.ajax({
		  type: "GET",
		  url: 'install.php',
		  data: 'case=install_authors',
		  success: function(result) {
		  if (result == 0) {
			$("#install_authors i.fa").remove();
			$("#install_authors").removeClass('btn-default');
			$("#install_authors").addClass('btn-danger');
			$("#install_authors").append(" <i class='fa fa-close'></i>");
		  } else {
		    $("#install_authors i.fa").remove();
			$("#install_authors").removeClass('btn-default');
			$("#install_authors").addClass('btn-success');
			$("#install_authors").append(" <i class='fa fa-check'></i>");
		  }
		  }
		 });
		});
		});
		$(function() {
		$("#install_categories").click(function() {
		$("#install_categories").append(" <i class='fa fa-spinner fa-spin'></i>");
		$.ajax({
		  type: "GET",
		  url: 'install.php',
		  data: 'case=install_categories',
		  success: function(result) {
		  if (result == 0) {
			$("#install_categories i.fa").remove();
			$("#install_categories").removeClass('btn-default');
			$("#install_categories").addClass('btn-danger');
			$("#install_categories").append(" <i class='fa fa-close'></i>");
		  } else {
		    $("#install_categories i.fa").remove();
			$("#install_categories").removeClass('btn-default');
			$("#install_categories").addClass('btn-success');
			$("#install_categories").append(" <i class='fa fa-check'></i>");
		  }
		  }
		 });
		});
		});
		$(function() {
		$("#install_quotes").click(function() {
		$("#install_quotes").append(" <i class='fa fa-spinner fa-spin'></i>");
		$.ajax({
		  type: "GET",
		  url: 'install.php',
		  data: 'case=install_quotes',
		  success: function(result) {
		  if (result == 0) {
			$("#install_quotes i.fa").remove();
			$("#install_quotes").removeClass('btn-default');
			$("#install_quotes").addClass('btn-danger');
			$("#install_quotes").append(" <i class='fa fa-close'></i>");
		  } else {
		    $("#install_quotes i.fa").remove();
			$("#install_quotes").removeClass('btn-default');
			$("#install_quotes").addClass('btn-success');
			$("#install_quotes").append(" <i class='fa fa-check'></i>");
		  }
		  }
		 });
		});
		});
	</script>
	</head>
	<body>
	<div class="container">
	<div class="row">
	<div class="col-md-8 col-md-push-2">
	<div class="logo"><img src="themes/default/images/logo.png" class="img-responsive" /></div>
	<div class="install">
	<?php
	switch ($_GET['do']) {
	case 'create_admin';
	$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	// set charset to UTF-8
	$mysqli->set_charset("utf8");
	if (isset($_POST['create'])) {
	$admin = $_POST['admin'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	if (empty($admin)) {
	$message = '<div class="alert alert-warning">Insert The Admin Username Please.</div>';
	} elseif (empty($password)) {
	$message = '<div class="alert alert-warning">Insert The Admin Password Please.</div>';
	} elseif (empty($confirm_password)) {
	$message = '<div class="alert alert-warning">Insert The Admin Password Confirmation Please.</div>';
	} elseif ($password != $confirm_password) {
	$message = '<div class="alert alert-warning">The Password Doesn\'t Match The Confirmation.</div>';
	} else {
	
	$encoded_password = hash('sha256', md5($password));
	$insert = $mysqli->query("INSERT INTO admin (id,username,password) VALUES ('1','$admin','$encoded_password')");
	if ($insert) {
	$message = '<div class="alert alert-success">The Admin Has Been Created Successfully.</div>';
	} else {
	$message = '<div class="alert alert-danger">Error Happened</div>';
	}
	}
	}
	?>
	<div class="content">
	<h4>Create Admin</h4>
	<?php if (isset($message)) {echo $message;} ?>
	<form method="POST" action="">
		<div class="form-group">
			<label for="admin">Admin Username</label>
			<input type="text" class="form-control" name="admin" id="admin" />
		</div>
		<div class="form-group">
			<label for="password">Admin Password</label>
			<input type="password" class="form-control" name="password" id="password" />
		</div>
		<div class="form-group">
			<label for="confirm_password">Admin Password Confirmation</label>
			<input type="password" class="form-control" name="confirm_password" id="confirm_password" />
		</div>
	
	<input type="submit" name="create" value="Save" class="btn btn-success" />
	<?php 
	$get = $mysqli->query("SELECT * FROM admin WHERE id='1'");
	if ($get->num_rows == 1) {
	?><a href="?do=setting" class="btn btn-primary">Edit Setting</a>
	<?php
	}
	?>
	</form>
	</div>
	
	<?php
	break;
	case 'setting';
	// mysqli connect method
	$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
	/* check connection */
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	// set charset to UTF-8
	$mysqli->set_charset("utf8");
	if (isset($_POST['save'])) {
	$all = base64_encode(serialize($_POST));
		$update = $mysqli->query("UPDATE setting SET general='$all' WHERE id='1'");
		if ($update) {
			$message = '<div class="alert alert-success">Setting Saved</div>';
		} else {
			$message = '<div class="alert alert-danger">Error Happened</div>';
		}
		unset($_POST);
		unset($all);
	}
	$get = $mysqli->query("SELECT general FROM setting WHERE id='1'");
	$g = $get->fetch_array();
	$general = unserialize(base64_decode($g['general']));
	function curPageURL() 
	{
	 $pageURL = 'http';
	 if (isset($_SERVER["HTTPS"])) {
	 $https = $_SERVER["HTTPS"]; 
	 }
	 if (isset($https) AND $https == "on") {$pageURL .= "s";} else {$pageURL .= "";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
	}
	?>
	<h4>Setting</h4>
	<div class="install-content">
	<?php if (isset($message)) {echo $message;} ?>
			<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="siteurl">Site Url</label>
			<input type="text" class="form-control" name="siteurl" id="siteurl" placeholder="http://www.domain.com" value="<?php echo str_replace('/install.php?do=setting','',curPageURL()); ?>" />
			<p class="help">If you place the script in the root folder don't use slash <b>/</b> at the end of site url.</p>
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
				$path = 'themes/';
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
		   <input type="hidden" name="installed" value="1" />
		  <button type="submit" name="save" class="btn btn-primary">Save</button>
		  <?php if (isset($general['installed']) AND $general['installed'] == 1) { ?>
		  <a href="admin" target="_BLANK" class="btn btn-success">Go to Admin Panel</a>
		  <?php } ?>
		</form>
	</div>
	<?php
	break;
	case 'install_db';
	$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
		/* check connection */
		if (mysqli_connect_errno()) {
			printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	$mysqli->set_charset("utf8");
	$admin_table = "CREATE TABLE IF NOT EXISTS `admin` (
					  `id` int(1) NOT NULL,
					  `username` varchar(40) NOT NULL,
					  `password` varchar(255) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	$categories_table = "CREATE TABLE IF NOT EXISTS `categories` (
					  `id` int(12) NOT NULL AUTO_INCREMENT,
					  `category` varchar(255) NOT NULL,
					  `slug` varchar(255) NOT NULL,
					  `hits` int(12) NOT NULL,
					  `seo_keywords` varchar(255) NOT NULL,
					  `seo_description` varchar(255) NOT NULL,
					  `quotes_number` int(12) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	
	$links_table = "CREATE TABLE IF NOT EXISTS `links` (
					  `id` int(12) NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) NOT NULL,
					  `link` varchar(255) NOT NULL,
					  `nofollow` int(1) NOT NULL,
					  `target` varchar(10) NOT NULL,
					  `published` int(1) NOT NULL,
					  `link_order` int(12) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	$authors_table = "CREATE TABLE IF NOT EXISTS `authors` (
					  `id` int(12) NOT NULL AUTO_INCREMENT,
					  `author` varchar(255) NOT NULL,
					  `slug` varchar(140) NOT NULL,
					  `seo_keywords` varchar(255) NOT NULL,
					  `seo_description` varchar(255) NOT NULL,
					  `quotes_number` int(12) NOT NULL,
					  `hits` int(12) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	$quotes_table = "CREATE TABLE IF NOT EXISTS `quotes` (
					  `id` int(12) NOT NULL AUTO_INCREMENT,
					  `quote` text NOT NULL,
					  `author_id` int(12) NOT NULL,
					  `category_id` int(12) NOT NULL,
					  `image` varchar(255) NOT NULL,
					  `hits` int(12) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8";

	$pages_table = "CREATE TABLE IF NOT EXISTS `pages` (
					  `id` int(12) NOT NULL AUTO_INCREMENT,
					  `title` varchar(255) NOT NULL,
					  `content` text NOT NULL,
					  `page_order` int(12) NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

	$setting_table = "CREATE TABLE IF NOT EXISTS `setting` (
					  `id` int(1) NOT NULL,
					  `general` text NOT NULL,
					  `theme` text NOT NULL,
					  PRIMARY KEY (`id`)
					) ENGINE=MyISAM DEFAULT CHARSET=utf8;";

	
	$setting_data = "INSERT INTO `setting` (`id`, `general`, `theme`) VALUES (1, 'YTo3OntzOjc6InNpdGV1cmwiO3M6Mjg6Imh0dHA6Ly9sb2NhbGhvc3QvcXVvdGVzLWNtcy8iO3M6OToic2VvX3RpdGxlIjtzOjEwOiJRdW90ZXMgQ01TIjtzOjEyOiJzZW9fa2V5d29yZHMiO3M6MTc6InF1b3RlcyxxdW90YXRpb25zIjtzOjE1OiJzZW9fZGVzY3JpcHRpb24iO3M6MDoiIjtzOjEwOiJzaXRlX3RoZW1lIjtzOjc6ImRlZmF1bHQiO3M6OToiaW5zdGFsbGVkIjtzOjE6IjEiO3M6NDoic2F2ZSI7czowOiIiO30=', 'YTo1OntzOjc6InNpdGV1cmwiO3M6MjQ6Imh0dHA6Ly9sb2NhbGhvc3QvcXVvdGVzLyI7czo5OiJzZW9fdGl0bGUiO3M6NjoiUXVvdGVyIjtzOjEyOiJzZW9fa2V5d29yZHMiO3M6NjU6InNjcmlwdHMscGhwLHNxbCxjc3MsanF1ZXJ5LGFqYXgsd2ViIHRlY2huaXF1ZXMsYXJ0aWNsZXMsdHV0b3JpYWxzIjtzOjE1OiJzZW9fZGVzY3JpcHRpb24iO3M6MTE6ImhlbGxvIHdvcmxkIjtzOjY6InN1Ym1pdCI7czo0OiJTYXZlIjt9');";
	?>
	<h4>Installing Database Table</h4>
	<div class="install-content">
	<table class="table table-stripped">
	<tr>
	<td>Admin Table</td>
	<td><?php if ($mysqli->query($admin_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Categories Table</td>
	<td><?php if ($mysqli->query($categories_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Authors Table</td>
	<td><?php if ($mysqli->query($authors_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Quotes Table</td>
	<td><?php if ($mysqli->query($quotes_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Pages Table</td>
	<td><?php if ($mysqli->query($pages_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Links Table</td>
	<td><?php if ($mysqli->query($links_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Setting Table</td>
	<td><?php if ($mysqli->query($setting_table)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	<tr>
	<td>Insert Setting Data</td>
	<td><?php if ($mysqli->query($setting_data)) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?></td>
	</tr>
	</table>
	</div>
	<a href="?do=install_db" class="btn btn-danger">Install Database Again</a>
	<a href="?do=create_admin" class="btn btn-success">Create Admin</a>
	</div>
	<?php
	break;
	case 'check';
	?>
	<h4>Checking the requirements</h4>
	<div class="install-content">
	<table class="table">
	<tr class="active">
	<td>MySQL connection</td>
	<td align="right">
	<?php 
	$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
	if (mysqli_connect_errno()) {
	?>
	<i class="fa fa-close text-danger"></i>
	<?php } else { ?>
	<i class="fa fa-check text-success"></i>
	<?php } ?>
	</td>
	</tr>
	<tr class="active">
	<td colspan="2">Folders Permissions</td>
	</tr>
	<tr>
	<td><i class="fa fa-folder-open"></i> cache</td>
	<td align="right">
	<?php if (is_writable('cache')) { ?><i class="fa fa-check text-success"></i><?php } else { ?><i class="fa fa-close text-danger"></i><?php } ?>
	</td>
	</tr>
	</table>
	</div>
	<div class="buttons_div">
	<span class="left"><a href="?do=check" class="btn btn-warning">Check the Requirements Again</a></span>
	<?php 
	$mysqli = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
	if (!mysqli_connect_errno()) {
	?>
	<span class="right"><a href="?do=install_db" class="btn btn-success">Install Database Tables</a></span>
	<?php } ?>
	</div>
	<?php
	break;
	default;
	?>
	<h4>Instructions</h4>
	<div class="install-content">
	
	<ul>
	<li><b>Create a new database</b></li>
	<li><b>Edit include/config.php file using your database informations</b></li>
	<li><b>CHMOD following folders with <span style="color:red;">0777</span> permissions</b>
	<ul>
	<li><i class="fa fa-folder-open"></i>cache</li>
	</ul>
	</li>
	</ul>
	</div>
	<a href="?do=check" class="btn btn-primary">Let's Start</a>
	<?php
	}
	?>
	</div>
	</div>
	</div>
	</div>
	</body>
</html>