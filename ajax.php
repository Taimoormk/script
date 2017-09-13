<?php
include('include/autoloader.php');
error_reporting(E_ERROR);
switch ($_GET['do']) {
case 'ajax_author_search';
// ajax search for top author widget
if(isset($_POST['queryString'])) {
$queryString = make_safe(xss_clean(htmlspecialchars($_POST['queryString'],ENT_QUOTES)));
if(mb_strlen($queryString,'UTF-8') >= 3) {
if (isset($theme_setting['top_authors_number'])) {$top_authors_number = $theme_setting['top_authors_number'];} else {$top_authors_number = 24;}
$query = $mysqli->query("SELECT * FROM authors WHERE author LIKE '$queryString%' ORDER BY author ASC LIMIT $top_authors_number");
$isthere = $query->num_rows;
$smarty->assign('isthere',$isthere);
if ($isthere > 0) {
    while ($result = $query->fetch_assoc()) {
		$authors[] = $result;
    }
	$smarty->assign('authors',$authors);
$smarty->display('ajax-search-authors.html');
}
} else {
        
} 
}
break;
default;
case 'ajax_random_quote';
// ajax generate random quote
$lucky = $general->lucky_strike();
$smarty->assign('lucky',$lucky);
if ($lucky != 0) {
foreach ($lucky AS $key=>$value) {
$smarty->assign('lucky_'.$key,$value);	
}	
}	
$smarty->display('ajax-random-quote.html');
break;

case 'ajax_author_by_letter';
// ajax search for author (by first letter) in all authors page
	if(isset($_GET))
	{
	$letter = make_safe(xss_clean(htmlspecialchars($_GET['letter'],ENT_QUOTES)));
	$page = $_GET['page'];
	$cur_page = $page;
	$page -= 1;
	if (isset($theme_setting['all_authors_number'])) {
	$per_page = $theme_setting['all_authors_number'];
	} else {
	$per_page = 60;
	}
	$smarty->assign('per_page',$per_page);
	$previous_btn = true;
	$next_btn = true;
	$start = $page * $per_page;
	
	$result_pag_data = $mysqli->query("SELECT * FROM authors WHERE author LIKE '$letter%' ORDER BY author ASC LIMIT $start, $per_page");
	while ($row = $result_pag_data->fetch_assoc()) {
		$authors[] = $row;
	}
	$smarty->assign('authors',$authors);


	$query_pag_num = "SELECT COUNT(*) AS count FROM authors WHERE author LIKE '$letter%'";
	$result_pag_num = $mysqli->query($query_pag_num);
	$row = $result_pag_num->fetch_assoc();
	$count = $row['count'];
	$smarty->assign('count',$count);
	$no_of_paginations = ceil($count / $per_page);

	if ($cur_page >= 7) {
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
			$end_loop = $cur_page + 3;
		else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		} else {
			$end_loop = $no_of_paginations;
		}
	} else {
		$start_loop = 1;
		if ($no_of_paginations > 7)
			$end_loop = 7;
		else
			$end_loop = $no_of_paginations;
	}
	$paginations .= "<div class='paginations'><ul class='pager'>";


	if ($previous_btn && $cur_page > 1) {
		$pre = $cur_page - 1;
		$paginations .= "<li rel='$pre' class='active previous'><a href='javascript:void();'>&larr; Previous</a></li>";
	} else if ($previous_btn) {
		$paginations .= "<li class='inactive previous'><a href='javascript:void();'>&larr; Previous</a></li>";
	}

	if ($next_btn && $cur_page < $no_of_paginations) {
		$nex = $cur_page + 1;
		$paginations .= "<li rel='$nex' class='active nextpage next'><a href='javascript:void();'>Next &rarr;</a></li>";
	} else if ($next_btn) {
		$paginations .= "<li class='inactive nextpage next'><a href='javascript:void();'>Next &rarr;</a></li>";
	}

	$pagi .= $paginations . "</ul></div>";  
	$smarty->assign('paginations',$pagi);
	}
	
	$smarty->display('ajax-authors-by-letter.html');
break;

case 'ajax_search_author_page';
// ajax search for author (by name) in all authors page
	if(isset($_GET))
	{
	$q = make_safe(xss_clean(htmlspecialchars($_GET['q'],ENT_QUOTES)));
	$smarty->assign('q',$q);
	$page = $_GET['page'];
	$cur_page = $page;
	$page -= 1;
	if (isset($theme_setting['all_authors_number'])) {
	$per_page = $theme_setting['all_authors_number'];
	} else {
	$per_page = 60;
	}
	$smarty->assign('per_page',$per_page);
	$previous_btn = true;
	$next_btn = true;
	$start = $page * $per_page;
	
	$result_pag_data = $mysqli->query("SELECT * FROM authors WHERE author LIKE '$q%' ORDER BY author ASC LIMIT $start, $per_page");
	while ($row = $result_pag_data->fetch_assoc()) {
		$authors[] = $row;
	}
	$smarty->assign('authors',$authors);


	$query_pag_num = "SELECT COUNT(*) AS count FROM authors WHERE author LIKE '$q%'";
	$result_pag_num = $mysqli->query($query_pag_num);
	$row = $result_pag_num->fetch_assoc();
	$count = $row['count'];
	$smarty->assign('count',$count);
	$no_of_paginations = ceil($count / $per_page);

	if ($cur_page >= 7) {
		$start_loop = $cur_page - 3;
		if ($no_of_paginations > $cur_page + 3)
			$end_loop = $cur_page + 3;
		else if ($cur_page <= $no_of_paginations && $cur_page > $no_of_paginations - 6) {
			$start_loop = $no_of_paginations - 6;
			$end_loop = $no_of_paginations;
		} else {
			$end_loop = $no_of_paginations;
		}
	} else {
		$start_loop = 1;
		if ($no_of_paginations > 7)
			$end_loop = 7;
		else
			$end_loop = $no_of_paginations;
	}
	$paginations .= "<div class='paginations'><ul class='pager'>";


	if ($previous_btn && $cur_page > 1) {
		$pre = $cur_page - 1;
		$paginations .= "<li rel='$pre' class='active previous'><a href='javascript:void();'>&larr; Previous</a></li>";
	} else if ($previous_btn) {
		$paginations .= "<li class='inactive previous'><a href='javascript:void();'>&larr; Previous</a></li>";
	}

	if ($next_btn && $cur_page < $no_of_paginations) {
		$nex = $cur_page + 1;
		$paginations .= "<li rel='$nex' class='active nextpage next'><a href='javascript:void();'>Next &rarr;</a></li>";
	} else if ($next_btn) {
		$paginations .= "<li class='inactive nextpage next'><a href='javascript:void();'>Next &rarr;</a></li>";
	}

	$pagi .= $paginations . "</ul></div>";  
	$smarty->assign('paginations',$pagi);
	}
	
	$smarty->display('ajax-search-authors-page.html');
break;
}
?>
