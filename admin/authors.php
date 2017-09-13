<?php
include('header.php'); 
if (!empty($_GET['case'])) {
$case = make_safe($_GET['case']);	
} else {
$case = '';	
}
switch ($case) {
case 'add_quote';
$author_id = intval(make_safe($_GET['author_id']));
if (isset($_POST['submit'])) {
try
{
NoCSRF::check('form_token', $_POST, true, 60*10, false );
$category_id = intval(make_safe(xss_clean($_POST['category_id'])));
$quote = make_safe(xss_clean(htmlspecialchars($_POST['quote'],ENT_QUOTES)));
if (empty($quote)) {
$message = notification('warning','Insert the Quote Please.');
} else {
if (!empty($_FILES['thumbnail']['name'])) {
$info = getimagesize($_FILES['thumbnail']['tmp_name']);
if ($info === FALSE) {
$thumbnail = '';
} elseif (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
$thumbnail = ''; 
} else {
$up = new fileDir('../upload/quotes/');
$thumbnail = $up->upload($_FILES['thumbnail']);
}
} else {
$thumbnail = '';
}
$sql = "INSERT INTO quotes (author_id,category_id,quote,image,hits) VALUES ('$author_id','$category_id','$quote','$thumbnail','0')";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Quote Added Successfully.');
} else {
$message = notification('danger','Error Happened.');
}
}
}
catch ( Exception $e )
{
echo $e->getMessage() . ' Form ignored.';
}
}
$author = $general->author($author_id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Add Quote For <?php echo $author['author']; ?>
				<a href="authors.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="" enctype="multipart/form-data">
		  <div class="form-group">
			<label for="author">Author <span>*</span></label>
			<div><?php echo $author['author']; ?></div>
		  </div>
		<div class="form-group">
			<label for="category_id">Topic <span>*</span></label>
			<select name="category_id" id="category_id" class="form-control">
			<?php 
			$categories = $general->categories('id ASC');
			foreach ($categories AS $category) {
			?>
			<option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
			<?php			
			}
			?>
			</select>
		  </div>
		  
		  <div class="form-group">
			<label for="quote">Quote</label>
			<textarea class="form-control wysiwyg" name="quote" id="quote" rows="3" ></textarea>
		  </div>
		  <div class="form-group">
			<label for="category_id">Image</label>
			<div class="fileinput fileinput-new input-group" data-provides="fileinput">
			  <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
			  <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="thumbnail"></span>
			  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
			</div>
			</div>
			<input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <button type="submit" name="submit" class="btn btn-primary">Save</button>
		</form>
<?php
break;
case 'add';
if (isset($_POST['submit'])) {
try
{
NoCSRF::check('form_token', $_POST, true, 60*10, false );
$author = make_safe(xss_clean($_POST['author']));
$slug = make_safe(xss_clean($_POST['slug']));
$seo_keywords = make_safe(xss_clean($_POST['seo_keywords']));
$seo_description = make_safe(xss_clean($_POST['seo_description']));
if (empty($author)) {
$message = notification('warning','Insert Author Please.');
} else {
$sql = "INSERT INTO authors (author,slug,seo_keywords,seo_description,quotes_number,hits) VALUES ('$author','$slug','$seo_keywords','$seo_description','0','0')";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Author Added Successfully.');
} else {
$message = notification('danger','Error Happened.');
}
}
}
catch ( Exception $e )
{
echo $e->getMessage() . ' Form ignored.';
}
}
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Add New Author
				<a href="authors.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="author">Author <span>*</span></label>
			<input type="text" class="form-control" name="author" id="author" />
		  </div>
		<div class="form-group">
			<label for="slug">Slug <span>*</span></label>
			<input type="text" class="form-control" name="slug" id="slug" />
		  </div>
		  <div class="form-group">
			<label for="seo_keywords">SEO Keywords</label>
			<input type="text" class="form-control seo-keywords" name="seo_keywords" id="seo_keywords" />
		  </div>
		  <div class="form-group">
			<label for="seo_description">SEO Description</label>
			<textarea class="form-control" name="seo_description" id="seo_description" rows="3" ></textarea>
		  </div>
		  <input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <button type="submit" name="submit" class="btn btn-primary">Save</button>
		</form>
<?php
break;
case 'edit';
$id = abs(intval(make_safe(xss_clean($_GET['id']))));
if (isset($_POST['submit'])) {
try
{
NoCSRF::check('form_token', $_POST, true, 60*10, false );
$author = make_safe(xss_clean($_POST['author']));
$slug = make_safe(xss_clean($_POST['slug']));
$seo_keywords = make_safe(xss_clean($_POST['seo_keywords']));
$seo_description = make_safe(xss_clean($_POST['seo_description']));
if (empty($author)) {
$message = notification('warning','Insert Author Please.');
} else {
$sql = "UPDATE authors SET author='$author',slug='$slug',seo_keywords='$seo_keywords',seo_description='$seo_description' WHERE id='$id'";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Author Edited Successfully.');
} else {
$message = notification('danger','Error Happened.');
}
}
}
catch ( Exception $e )
{
echo $e->getMessage() . ' Form ignored.';
}
}
$author = $general->author($id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Edit Author
				<a href="authors.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="author">Author <span>*</span></label>
			<input type="text" class="form-control" name="author" id="author" value="<?php echo $author['author']; ?>" />
		  </div>
		<div class="form-group">
			<label for="slug">Slug <span>*</span></label>
			<input type="text" class="form-control" name="slug" id="slug" value="<?php echo $author['slug']; ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_keywords">SEO Keywords</label>
			<input type="text" class="form-control seo-keywords" name="seo_keywords" id="seo_keywords" value="<?php echo $author['seo_keywords']; ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_description">SEO Description</label>
			<textarea class="form-control" name="seo_description" id="seo_description" rows="3" ><?php echo $author['seo_description']; ?></textarea>
		  </div>
		  <input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <button type="submit" name="submit" class="btn btn-primary">Save</button>
		</form>
<?php
break;
case 'delete';
$id = abs(intval(make_safe(xss_clean($_GET['id']))));
if (isset($_POST['delete'])) {
try
{
NoCSRF::check('form_token', $_POST, true, 60*10, false );
$mysqli->query("DELETE FROM quotes WHERE author_id='$id'");	
$delete = $mysqli->query("DELETE FROM authors WHERE id='$id'");
if ($delete) {
$message = notification('success','Author and All related Quotes Deleted Successfully.');
$done = true;
} else {
$message = notification('danger','Error Happened.');
}
}
catch ( Exception $e )
{
echo $e->getMessage() . ' Form ignored.';
}
}
$tauthor = $general->author($id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Delete Author
				<a href="authors.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			
		  <form role="form" method="POST" action="">
			<?php if (isset($message)) {echo $message;} else { ?><div class="alert alert-warning">Are you sure that you want to delete <b><?php echo $tauthor['author']; ?></b> and all related quotes ? this step can not be undo .</div><?php } ?>
		  <?php if (isset($done)) { ?>
		  <a href="authors.php" class="btn btn-default">Back To authors</a>
		  <?php } else { ?>
		  <input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <button type="submit" name="delete" class="btn btn-danger">Delete</button>
		  <?php } ?>
		</form>
<?php
break;
case 'search';
$q = make_safe($_GET['q']);
?>
<div class="page-header page-heading">
	<h1 class="row"><div class="col-md-6"><i class="fa fa-user"></i> Search For <?php echo $q; ?> in Authors</div>
	<div class="col-md-6">
	<div class="pull-right search-form">
	<form method="GET" action="authors.php">
		<div class="input-group">
		  <input type="hidden" name="case" value="search" />
		  <input type="text" name="q" class="form-control" placeholder="Search" value="<?php echo $q; ?>">
		  <span class="input-group-addon"><button class="btn-link"><span class="fa fa-search"></span></button></span>
		</div>
	</form>
	</div>
	<a href="authors.php?case=add" class="btn btn-success pull-right"><span class="fa fa-plus"></span></a>
	</div>
	</h1>
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM authors WHERE author LIKE '$q%'";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any Author. <a href="?case=add">Add New Author</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?case=search&q=$q&page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM authors WHERE author LIKE '$q%' ORDER BY author ASC ".$pagination->getLimitSql();
$qu = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Author</th>
			<th class="hidden-xs">Quotes</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $qu->fetch_assoc()) {
?>
		<tr>
			<td><a href="quotes.php?case=author&id=<?php echo $row['id']; ?>"><?php echo $row['author']; ?></a></td>
			<td class="hidden-xs" id="author-quotes-<?php echo $row['id']; ?>"><?php echo get_author_quotes($row['id']); ?></td>
			<td align="right">
				<a class="btn btn-success btn-xs" href="authors.php?case=add_quote&author_id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Add Quote"><span class="fa fa-quote-left"></span></a>
				<a class="count_author_quotes btn btn-success btn-xs" href="javascript:void();" id="<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Add Quote"><span class="fa fa-quote-left"></span></a>
				<a class="btn btn-default btn-xs" href="authors.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="authors.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
			</td>
		</tr>
<?php
}
?>
	</tbody>
</table>
<?php
echo $pagination->create_links();
}
break;
default;
?>
<div class="page-header page-heading">
	<h1 class="row"><div class="col-md-6"><i class="fa fa-user"></i> Authors</div>
	<div class="col-md-6">
	<div class="pull-right search-form">
	<form method="GET" action="authors.php">
		<div class="input-group">
		  <input type="hidden" name="case" value="search" />
		  <input type="text" name="q" class="form-control" placeholder="Search">
		  <span class="input-group-addon"><button class="btn-link"><span class="fa fa-search"></span></button></span>
		</div>
	</form>
	</div>
	<a href="authors.php?case=add" class="btn btn-success pull-right"><span class="fa fa-plus"></span></a>
	</div>
	</h1>
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM authors ORDER BY id DESC";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any Author. <a href="?case=add">Add New Author</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM authors ORDER BY id DESC ".$pagination->getLimitSql();
$q = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Author</th>
			<th class="hidden-xs">Quotes</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $q->fetch_assoc()) {
?>
		<tr>
			<td><a href="quotes.php?case=author&id=<?php echo $row['id']; ?>"><?php echo $row['author']; ?></a></td>
			<td class="hidden-xs" id="author-quotes-<?php echo $row['id']; ?>"><?php echo get_author_quotes($row['id']); ?></td>
			<td align="right">
				<a class="btn btn-success btn-xs" href="authors.php?case=add_quote&author_id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Add Quote"><span class="fa fa-quote-left"></span></a>
				<a class="count_author_quotes btn btn-warning btn-xs" href="javascript:void();" id="<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Count Author Quotes"><span class="fa fa-bar-chart"></span></a>
				<a class="btn btn-default btn-xs" href="authors.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="authors.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
			</td>
		</tr>
<?php
}
?>
	</tbody>
</table>
<?php
echo $pagination->create_links();
}
} 
include('footer.php');
?>