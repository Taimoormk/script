<?php
include('include/autoloader.php');
// recieve the author slug variable
$slug = make_safe(xss_clean($_GET['slug']));
$smarty->assign('is_author',1); // to use with menu (selected author)
$author = $general->author_by_slug($slug); // the author method found in include/general.class.php
// fetching the result
foreach ($author AS $key=>$value) {
$smarty->assign('author_'.$key,$value);
}
// check if the author exists, if not redirect to error page 
if ($author == 0) {
header('Location:'.$general_setting['siteurl'].'/not-found');	
}


// fetch the url to get the page id
$ur = explode('?',curPageURL());
if (count($ur) != 0) {
if (isset($ur[1])) {
parse_str($ur[1],$query);	
}
}
// get the quotes number in author page from theme setting
if (isset($theme_setting['author_quotes_number']) AND $theme_setting['author_quotes_number'] != 0) {
$author_quotes_number = $theme_setting['author_quotes_number'];
} else {
$author_quotes_number = 24;	
}
	$page = 1; // first page number
	$size = $author_quotes_number; // number of quotes per author page you can change it from theme setting
	if (isset($query['page'])){ $page = (int) $query['page']; }
	// count quotes number that related to this author
	$sqls = "SELECT * FROM quotes WHERE author_id='$author[id]'";
	$qu = $mysqli->query($sqls);
	$total_records = $qu->num_rows;
	$smarty->assign('total_records',$total_records);
	if ($total_records > 0) {
	// define the pagination class. found at : include/pagination.php 	
	$pagination = new Pagination();
	$pagination->setLink("./author/$slug?page=%s"); // the link of each page (%s) represent the page number variable
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_records);
	$get = "SELECT * FROM quotes WHERE author_id='$author[id]' ORDER BY id DESC ".$pagination->getLimitSql();
	$q = $mysqli->query($get);
	while ($row = $q->fetch_assoc()) {
	$quotes[] = $row;
	}
	$smarty->assign('quotes',$quotes);
	$pagi = $pagination->create_links();
	$smarty->assign('pagi',$pagi);
	}

// assign the SEO variables (title,keywords,description).	
$smarty->assign('seo_title',$author['author'].'\'s Quotes');	
$smarty->assign('seo_keywords',$author['seo_keywords']);
$smarty->assign('seo_description',$author['seo_description']);
// display the author HTML 
$smarty->display('author.html');
?>