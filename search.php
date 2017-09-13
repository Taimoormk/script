<?php
include('include/autoloader.php');
	$smarty->assign('is_search',1); // define that the page is search page
	// fetch the url to get the page id
	$ur = explode('?',curPageURL());
	if (count($ur) != 0) {
	if (isset($ur[1])) {
	parse_str($ur[1],$query);	
	}
	}
	// check if the search word not empty
	if (isset($query['q'])) {
	$q = make_safe(xss_clean(htmlspecialchars($query['q'],ENT_QUOTES)));
	}
	$smarty->assign('q',$q);
	// then if not empty get the results
	if (!empty($q)) {
	// get the quotes number in search results page from theme setting
	if (isset($theme_setting['search_quotes_number']) AND $theme_setting['search_quotes_number'] != 0) {
	$search_quotes_number = $theme_setting['search_quotes_number'];
	} else {
	$search_quotes_number = 24;	
	}
	$page = 1; // first page number
	$size = $search_quotes_number; // number of quotes per search results page you can change it from theme setting
	if (isset($query['page'])){ $page = (int) $query['page']; }
	// count quotes number that related to this search query
	$sqls = "SELECT * FROM quotes WHERE quote LIKE '%$q%'";
	$qu = $mysqli->query($sqls);
	$total_records = $qu->num_rows;
	$smarty->assign('total_records',$total_records);
	if ($total_records > 0) {
	// define the pagination class. found at : include/pagination.php 	
	$pagination = new Pagination();
	$pagination->setLink("./search/?q=$q&page=%s"); // the link of each page (%s) represent the page number variable
	$pagination->setPage($page);
	$pagination->setSize($size);
	$pagination->setTotalRecords($total_records);
	$get = "SELECT * FROM quotes WHERE quote LIKE '%$q%' ORDER BY id DESC ".$pagination->getLimitSql();
	$qu = $mysqli->query($get);
	while ($row = $qu->fetch_assoc()) {
	$quotes[] = $row;
	}
	$smarty->assign('quotes',$quotes);
	$pagi = $pagination->create_links();
	$smarty->assign('pagi',$pagi);
	}
}

// assign the SEO variables (title,keywords,description).	
$smarty->assign('seo_title','Search results for : '.$q);	
$smarty->assign('seo_keywords',$general_setting['seo_keywords']);
$smarty->assign('seo_description',$general_setting['seo_description']);
// display the search HTML 
$smarty->display('search.html');
?>