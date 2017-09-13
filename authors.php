<?php
include('include/autoloader.php');
$smarty->assign('is_authors',1); // to use with menu (home select)
$topics = $general->categories('id ASC');
$smarty->assign('topics',$topics);
$letters = letters();
$smarty->assign('letters',$letters);

$smarty->assign('seo_title','All Authors | '.$general_setting['seo_title']);	
$smarty->assign('seo_keywords',$general_setting['seo_keywords']);
$smarty->assign('seo_description',$general_setting['seo_description']);
// display the index HTML 
$smarty->display('authors.html');
?>