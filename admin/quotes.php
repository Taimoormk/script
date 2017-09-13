<?php
include('header.php'); 
if (!empty($_GET['case'])) {
$case = make_safe($_GET['case']);	
} else {
$case = '';	
}
switch ($case) {
case 'category';
$id = intval($_GET['id']);
$category = $general->category($id);
?>
<div class="page-header page-heading">
<h1><?php echo $category['category']; ?> Quotes
<a href="quotes.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
</h1>	
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM quotes WHERE category_id='$id' ORDER BY id DESC";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any quote. <a href="?case=add">Add New Quote</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?case=category&id=$id&page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM quotes WHERE category_id='$id' ORDER BY id DESC ".$pagination->getLimitSql();
$q = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Quote</th>
			<th class="hidden-xs">Author</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $q->fetch_assoc()) {
?>
		<tr>
			<td><?php if (!empty($row['image'])) { ?><span class="fa fa-photo has-image"></span><?php } ?><?php echo htmlspecialchars_decode($row['quote'],ENT_QUOTES); ?></td>
			<td class="hidden-xs"><a href="quotes.php?case=author&id=<?php echo $row['author_id']; ?>"><?php echo get_author($row['author_id']); ?></a></td>
			<td align="right" width="80">
				<a class="btn btn-default btn-xs" href="quotes.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="quotes.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
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
case 'author';
$id = intval($_GET['id']);
$author = $general->author($id);
?>
<div class="page-header page-heading">
<h1>Quotes By <?php echo $author['author']; ?>
<a href="quotes.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
<a href="authors.php?case=add_quote&author_id=<?php echo $id; ?>" class="btn btn-success  pull-right"><span class="fa fa-plus"></span></a>
</h1>	
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM quotes WHERE author_id='$id' ORDER BY id DESC";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any quote. <a href="?case=add">Add New Quote</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?case=author&id=$id&page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM quotes WHERE author_id='$id' ORDER BY id DESC ".$pagination->getLimitSql();
$q = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Quote</th>
			<th class="hidden-xs">Topic</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $q->fetch_assoc()) {
?>
		<tr>
			<td><?php if (!empty($row['image'])) { ?><span class="fa fa-photo has-image"></span><?php } ?><?php echo htmlspecialchars_decode($row['quote'],ENT_QUOTES); ?></td>
			<td class="hidden-xs"><a href="quotes.php?case=category&id=<?php echo $row['category_id']; ?>"><?php echo get_category($row['category_id']); ?></a></td>
			<td align="right" width="80">
				<a class="btn btn-default btn-xs" href="quotes.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="quotes.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
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
case 'edit';
$id = abs(intval(make_safe(xss_clean($_GET['id']))));
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
$thumbnail = make_safe(xss_clean($_POST['old_thumbnail']));  
} elseif (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
$thumbnail = make_safe(xss_clean($_POST['old_thumbnail']));  
} else {
$up = new fileDir('../upload/quotes/');
$thumbnail = $up->upload($_FILES['thumbnail']);
$up->delete("$_POST[old_thumbnail]");
}
} else {
$thumbnail = $_POST['old_thumbnail'];
}
$sql = "UPDATE quotes SET category_id='$category_id',quote='$quote',image='$thumbnail' WHERE id='$id'";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Quote Edited Successfully.');
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
$quote = $general->quote($id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Edit Category
				<a href="quotes.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="" enctype="multipart/form-data">
		  <div class="form-group">
			<label for="author">Author <span>*</span></label>
			<div><?php echo get_author($quote['author_id']); ?></div>
		  </div>
		<div class="form-group">
			<label for="category_id">Topic <span>*</span></label>
			<select name="category_id" id="category_id" class="form-control">
			<?php 
			$categories = $general->categories('id ASC');
			foreach ($categories AS $category) {
			?>
			<option value="<?php echo $category['id']; ?>" <?php if ($quote['category_id'] == $category['id']) {echo 'SELECTED';} ?>><?php echo $category['category']; ?></option>
			<?php			
			}
			?>
			</select>
		  </div>
		  
		  <div class="form-group">
			<label for="quote">Quote</label>
			<textarea class="form-control wysiwyg" name="quote" id="quote" rows="15"><?php echo htmlspecialchars_decode($quote['quote'],ENT_QUOTES); ?></textarea>
		  </div>
		  <div class="form-group">
			<label for="category_id">Image</label>
			<div class="fileinput fileinput-new input-group" data-provides="fileinput">
			  <div class="form-control" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
			  <span class="input-group-addon btn btn-default btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span><input type="file" name="thumbnail"></span>
			  <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
			</div>
			<?php if (!empty($quote['image'])) { ?>
			<p>Current Image : <a href="javascript:void();" data-toggle="popover" data-placement="top" title="Current Image" data-content="<img src='../upload/quotes/<?php echo $quote['image']; ?>' class='img-responsive' />"><?php echo $quote['image']; ?></a></p>
			<?php } ?>
			</div>
			<input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <input type="hidden" name="old_thumbnail" value="<?php echo $quote['image']; ?>" />
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
$delete = $mysqli->query("DELETE FROM quotes WHERE id='$id'");
if ($delete) {
$message = notification('success','Quote Deleted Successfully.');
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
$quote = $general->quote($id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Delete Quote
				<a href="quotes.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		  <form role="form" method="POST" action="">
		  <div class="alert alert-warning">Are You Sure that you want to delete this Quote ? <br /><?php echo $quote['quote']; ?></div>
		  <?php if (isset($done)) { ?>
		  <a href="quotes.php" class="btn btn-default">Back To quotes</a>
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
	<h1 class="row"><div class="col-md-6"><i class="fa fa-quote-right"></i> Search for <?php echo $q; ?> Quotes</div>
	<div class="col-md-6">
	<div class="pull-right search-form">
	<form method="GET" action="quotes.php">
		<div class="input-group">
		  <input type="hidden" name="case" value="search" />
		  <input type="text" name="q" class="form-control" placeholder="Search" value="<?php echo $q; ?>">
		  <span class="input-group-addon"><button class="btn-link"><span class="fa fa-search"></span></button></span>
		</div>
	</form>
	</div>
	</div>
	</h1>
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM quotes WHERE quote LIKE '%$q%'";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any quote. <a href="?case=add">Add New Quote</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?case=search&q=$q&page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM quotes WHERE quote LIKE '%$q%' ORDER BY id ASC ".$pagination->getLimitSql();
$qu = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Quote</th>
			<th class="hidden-xs">Author</th>
			<th class="hidden-xs">Topic</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $qu->fetch_assoc()) {
?>
		<tr>
			<td><?php if (!empty($row['image'])) { ?><span class="fa fa-photo has-image"></span><?php } ?><?php echo str_replace("$q","<b style='background:yellow;'>$q</b>",htmlspecialchars_decode($row['quote'],ENT_QUOTES)); ?></td>
			<td class="hidden-xs"><a href="quotes.php?case=author&id=<?php echo $row['author_id']; ?>"><?php echo get_author($row['author_id']); ?></a></td>
			<td class="hidden-xs"><a href="quotes.php?case=category&id=<?php echo $row['category_id']; ?>"><?php echo get_category($row['category_id']); ?></a></td>
			<td align="right" width="80">
				<a class="btn btn-default btn-xs" href="quotes.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="quotes.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
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
	<h1 class="row"><div class="col-md-6"><i class="fa fa-quote-right"></i> Quotes</div>
	<div class="col-md-6">
	<div class="pull-right search-form">
	<form method="GET" action="quotes.php">
		<div class="input-group">
		  <input type="hidden" name="case" value="search" />
		  <input type="text" name="q" class="form-control" placeholder="Search">
		  <span class="input-group-addon"><button class="btn-link"><span class="fa fa-search"></span></button></span>
		</div>
	</form>
	</div>
	</div>
	</h1>
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM quotes ORDER BY id DESC";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any quote.');
} else {
$pagination = new Pagination();
$pagination->setLink("?page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM quotes ORDER BY id DESC ".$pagination->getLimitSql();
$q = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Quote</th>
			<th class="hidden-xs">Author</th>
			<th class="hidden-xs">Topic</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $q->fetch_assoc()) {
?>
		<tr>
			<td><?php if (!empty($row['image'])) { ?><span class="fa fa-photo has-image"></span><?php } ?><?php echo htmlspecialchars_decode($row['quote'],ENT_QUOTES); ?></td>
			<td class="hidden-xs"><a href="quotes.php?case=author&id=<?php echo $row['author_id']; ?>"><?php echo get_author($row['author_id']); ?></a></td>
			<td class="hidden-xs"><a href="quotes.php?case=category&id=<?php echo $row['category_id']; ?>"><?php echo get_category($row['category_id']); ?></a></td>
			<td align="right" width="80">
				<a class="btn btn-default btn-xs" href="quotes.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="quotes.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
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