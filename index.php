<?php
include('include/autoloader.php');
$smarty->assign('is_home',1); // to use with menu (home select)
// fetch the url to get the page id
$ur = explode('?',curPageURL());
if (count($ur) != 0) {
if (isset($ur[1])) {
parse_str($ur[1],$query);	
}
}
if (isset($theme_setting['home_quotes_number']) AND $theme_setting['home_quotes_number'] != 0) {
$home_quotes_number = $theme_setting['home_quotes_number'];
} else {
$home_quotes_number = 24;	
}
	$page = 1; // first page number
	$size = $home_quotes_number; // number of quotes per category page you can change it from theme setting
	if (isset($query['page'])){ $page = (int) $query['page']; }
	// count quotes number that related to this category
	$sqls = "SELECT * FROM quotes";
	$qu = $mysqli->query($sqls);
	$total_records = $qu->num_rows;
	$smarty->assign('total_records',$total_records);
	if ($total_records > 0) {
	// define the pagination class. found at : include/pagination.php 	
	$pagination = new Pagination();
	$pagination->setLink("./?page=%s"); // the link of each page (%s) represent the page number variable
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_records);
	$get = "SELECT * FROM quotes ORDER BY id DESC ".$pagination->getLimitSql();
	$q = $mysqli->query($get);
	while ($row = $q->fetch_assoc()) {
	$quotes[] = $row;
	}
	$smarty->assign('quotes',$quotes);
	$pagi = $pagination->create_links();
	$smarty->assign('pagi',$pagi);
	}
// assign the SEO variables (title,keywords,description).	
$smarty->assign('seo_title',$general_setting['seo_title']);	
$smarty->assign('seo_keywords',$general_setting['seo_keywords']);
$smarty->assign('seo_description',$general_setting['seo_description']);
// display the index HTML 
$smarty->display('index.html');
?>