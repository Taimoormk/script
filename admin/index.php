<?php 
include ('header.php'); 
?>

        <div class="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <div class="page-header">
					<h1>Dashboard</h1>
					</div>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-reorder fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $general->topics_number(); ?></div>
                                    <div class="stat-text">Topics</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $general->authors_number(); ?></div>
                                    <div class="stat-text">Authors</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-quote-right fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $general->quotes_number(); ?></div>
                                    <div class="stat-text">Quotes</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-bar-chart fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <div class="huge"><?php echo $general->pageviews(); ?></div>
                                    <div class="stat-text">Views</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<div class="page-header page-heading">
<h1>Sitemaps</h1>
</div>
<table class="table">
<thead>
<tr>
<th>Sitemap</th>
<th>Type</th>
</tr>
</thead>
<tbody>
<?php 
if (isset($general_setting['sitemap_items'])) {
$number = $general_setting['sitemap_items'];	
} else {
$number = 5000;	
}
if ($general->topics_number() > 0) {
$topic_sitemaps = ceil($general->topics_number()/$number);	
for($t=0;$t<$topic_sitemaps;$t++) {
$n = $t+1;
$topic_sitemap_link = $general_setting['siteurl'].'/topics-sitemap-'.$n.'.xml';
$topic_sitemap = str_replace(':/','://',str_replace('//','/',($topic_sitemap_link)));
?>
<tr>
<td><a href="<?php echo $topic_sitemap; ?>" target="_BLANK"><?php echo $topic_sitemap; ?></a></td>
<td>Topics</td>
</tr>
<?php
}
}
if ($general->authors_number() > 0) {
$author_sitemaps = ceil($general->authors_number()/$number);	
for($a=0;$a<$author_sitemaps;$a++) {
$b = $a+1;
$author_sitemap_link = $general_setting['siteurl'].'/authors-sitemap-'.$b.'.xml';
$author_sitemap = str_replace(':/','://',str_replace('//','/',($author_sitemap_link)));
?>
<tr>
<td><a href="<?php echo $author_sitemap; ?>" target="_BLANK"><?php echo $author_sitemap; ?></a></td>
<td>Authors</td>
</tr>
<?php
}
}
if ($general->quotes_number() > 0) {
$quotes_sitemaps = ceil($general->quotes_number()/$number);	
for($q=0;$q<$quotes_sitemaps;$q++) {
$c = $q+1;
$quotes_sitemap_link = $general_setting['siteurl'].'/quotes-sitemap-'.$c.'.xml';
$quotes_sitemap = str_replace(':/','://',str_replace('//','/',($quotes_sitemap_link)));
?>
<tr>
<td><a href="<?php echo $quotes_sitemap; ?>" target="_BLANK"><?php echo $quotes_sitemap; ?></a></td>
<td>Quotes</td>
</tr>
<?php
}
}
?>
</tbody>
</table>
        </div>
<?php include ('footer.php'); ?>