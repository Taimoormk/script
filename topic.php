<?php
include('include/autoloader.php');
// recieve the category id and slug variables
$slug = make_safe(xss_clean($_GET['slug']));
$smarty->assign('is_category',1); // to use with menu (selected category)
$topic = $general->topic_by_slug($slug); // the category method found in include/general.class.php
// fetching the result
foreach ($topic AS $key=>$value) {
$smarty->assign('topic_'.$key,$value);
}
// check if the category exists, if not redirect to error page 
if ($topic == 0) {
header('Location:'.$general_setting['siteurl'].'/not-found');	
}


// fetch the url to get the page id
$ur = explode('?',curPageURL());
if (count($ur) != 0) {
if (isset($ur[1])) {
parse_str($ur[1],$query);	
}
}
// get the quotes number in category page from theme setting
if (isset($theme_setting['category_quotes_number']) AND $theme_setting['category_quotes_number'] != 0) {
$category_quotes_number = $theme_setting['category_quotes_number'];
} else {
$category_quotes_number = 24;	
}
	$page = 1; // first page number
	$size = $category_quotes_number; // number of quotes per category page you can change it from theme setting
	if (isset($query['page'])){ $page = (int) $query['page']; }
	// count quotes number that related to this category
	$sqls = "SELECT * FROM quotes WHERE category_id='$topic[id]'";
	$qu = $mysqli->query($sqls);
	$total_records = $qu->num_rows;
	$smarty->assign('total_records',$total_records);
	if ($total_records > 0) {
	// define the pagination class. found at : include/pagination.php 	
	$pagination = new Pagination();
	$pagination->setLink("./topic/$slug?page=%s"); // the link of each page (%s) represent the page number variable
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_records);
	$get = "SELECT * FROM quotes WHERE category_id='$topic[id]' ORDER BY id DESC ".$pagination->getLimitSql();
	$q = $mysqli->query($get);
	while ($row = $q->fetch_assoc()) {
	$quotes[] = $row;
	}
	$smarty->assign('quotes',$quotes);
	$pagi = $pagination->create_links();
	$smarty->assign('pagi',$pagi);
	}

// assign the SEO variables (title,keywords,description).	
$smarty->assign('seo_title',$topic['category'].' Quotes');	
$smarty->assign('seo_keywords',$topic['seo_keywords']);
$smarty->assign('seo_description',$topic['seo_description']);
// display the category HTML 
$smarty->display('topic.html');
?>