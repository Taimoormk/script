<?php
include('include/autoloader.php');
$id = intval(make_safe(xss_clean($_GET['id'])));
$author_id = intval(make_safe(xss_clean($_GET['author'])));
$smarty->assign('is_quote',1);
$quote = $general->quote($id,$author_id);
foreach ($quote AS $key=>$value) {
$smarty->assign('quote_'.$key,$value);
}
$quote_to_tags = string_to_keywords(htmlspecialchars_decode($quote['quote'],ENT_QUOTES));
$tags = explode(',',$quote_to_tags);
if (count($tags) > 0) {
$smarty->assign('tags',$tags);
} else {
$smarty->assign('tags',0);
}
$quoteurl = $general_setting['siteurl']."/quote/".$quote['id']."/".$quote['author_id'];
$quote_url = str_replace(':/','://',str_replace('//','/',($quoteurl)));
$smarty->assign('quote_url',$quote_url);
if (!empty($quote['image'])) {
$thumbnail = $general_setting['siteurl'].'/upload/quotes/'.$quote['image'];
$thumbnail_url = str_replace(':/','://',str_replace('//','/',($thumbnail)));
} else {
$thumbnail_url = '';
}
$smarty->assign('thumbnail_url',$thumbnail_url);
if (isset($theme_setting['related_quotes_number'])) {$related_quotes_number = $theme_setting['related_quotes_number'];} else {$related_quotes_number = 5;}

$other_author = $general->quotes_query("WHERE author_id='$quote[author_id]' AND id!='$id'","id DESC",$related_quotes_number);
$smarty->assign('other_author',$other_author);
$other_topic = $general->quotes_query("WHERE category_id='$quote[category_id]' AND id!='$id'","id DESC",$related_quotes_number);
$smarty->assign('other_topic',$other_topic);
// assign the SEO variables (title,keywords,description).
$author = $general->author_by_id($quote['author_id']);
$topic = $general->topic_by_id($quote['category_id']);
$seo_title = $author['author'].' about '.$topic['category'].' : '.mb_substr(htmlspecialchars_decode($quote['quote'],ENT_QUOTES),0,40,'UTF-8');	
$smarty->assign('seo_title',$seo_title);	
$smarty->assign('seo_keywords',string_to_keywords(htmlspecialchars_decode(strip_tags($quote['quote']),ENT_QUOTES)));
$smarty->assign('seo_description',strip_tags(htmlspecialchars_decode($quote['quote'],ENT_QUOTES)));
// display the index HTML 
$smarty->display('quote.html');
?>