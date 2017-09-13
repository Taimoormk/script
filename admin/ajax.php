<?php
session_start();
header("Content-type: text/html; charset=utf8");
set_time_limit(60000*60);
error_reporting(E_ERROR);
if(!isset($_SESSION['quotes_cms_admin'])) {
header("location:login.php");
exit;
}
include('../include/config.php');
include('../include/connect.php');
include('include/functions.php');
include('include/setting.php');
include('include/general.class.php');
$general = new General;
$general->set_connection($mysqli);
if (isset($_POST['action'])) {
$action = make_safe(xss_clean($_POST['action']));
} else {
$action = '';	
}
if (isset($_GET['case'])) {
$case = make_safe(xss_clean($_GET['case'])); 
} else {
$case = '';	
}
// sort links
if ($action == "sort_links"){
	$records = $_POST['records'];
	$counter = 1;
	foreach ($records as $record) {
		$sql = "UPDATE links SET link_order='$counter' WHERE id='$record'";
		$query = $mysqli->query($sql);
		$counter = $counter + 1;	
	}
}
// sort pages
if ($action == "sort_pages"){
	$records = $_POST['records'];
	$counter = 1;
	foreach ($records as $record) {
		$sql = "UPDATE pages SET page_order='$counter' WHERE id='$record'";
		$query = $mysqli->query($sql);
		$counter = $counter + 1;	
	}
}

if ($action == "count_author_quotes"){
	$id = intval(make_safe(xss_clean($_POST['id'])));
	$quotes_number = get_author_quotes($id);	
	$sql = "UPDATE authors SET quotes_number='$quotes_number' WHERE id='$id'";
	$query = $mysqli->query($sql);
	if ($query) {
		echo $quotes_number;
	} 
}

if ($action == "count_topic_quotes"){
	$id = intval(make_safe(xss_clean($_POST['id'])));
	$quotes_number = get_category_quotes($id);	
	$sql = "UPDATE categories SET quotes_number='$quotes_number' WHERE id='$id'";
	$query = $mysqli->query($sql);
	if ($query) {
		echo $quotes_number;
	} 
}
?>