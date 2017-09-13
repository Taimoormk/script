<?php
include('header.php'); 
if (!empty($_GET['case'])) {
$case = make_safe($_GET['case']);	
} else {
$case = '';	
}
switch ($case) {
case 'add';
if (isset($_POST['submit'])) {
try
{
NoCSRF::check('form_token', $_POST, true, 60*10, false );
$category = make_safe(xss_clean($_POST['category']));
$slug = make_safe(xss_clean($_POST['slug']));
$seo_keywords = make_safe(xss_clean($_POST['seo_keywords']));
$seo_description = make_safe(xss_clean($_POST['seo_description']));
if (empty($category)) {
$message = notification('warning','Insert Topic Please.');
} else {
$sql = "INSERT INTO categories (category,slug,seo_keywords,seo_description,quotes_number,hits) VALUES ('$category','$slug','$seo_keywords','$seo_description','0','0')";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Topic Added Successfully.');
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
				<h1>Add New Topic
				<a href="categories.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="category">Topic <span>*</span></label>
			<input type="text" class="form-control" name="category" id="category" />
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
$category = make_safe(xss_clean($_POST['category']));
$slug = make_safe(xss_clean($_POST['slug']));
$seo_keywords = make_safe(xss_clean($_POST['seo_keywords']));
$seo_description = make_safe(xss_clean($_POST['seo_description']));
if (empty($category)) {
$message = notification('warning','Insert Topic Name Please.');
} else {
$sql = "UPDATE categories SET category='$category',slug='$slug',seo_keywords='$seo_keywords',seo_description='$seo_description' WHERE id='$id'";
$query = $mysqli->query($sql);
if ($query) {
$message = notification('success','Topic Edited Successfully.');
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
$category = $general->category($id);
$form_token = NoCSRF::generate('form_token');
?>
			<div class="page-header page-heading">
				<h1>Edit Topic
				<a href="categories.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="category">Topic <span>*</span></label>
			<input type="text" class="form-control" name="category" id="category" value="<?php echo $category['category']; ?>" />
		  </div>
		<div class="form-group">
			<label for="slug">Slug <span>*</span></label>
			<input type="text" class="form-control" name="slug" id="slug" value="<?php echo $category['slug']; ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_keywords">SEO Keywords</label>
			<input type="text" class="form-control seo-keywords" name="seo_keywords" id="seo_keywords" value="<?php echo $category['seo_keywords']; ?>" />
		  </div>
		  <div class="form-group">
			<label for="seo_description">SEO Description</label>
			<textarea class="form-control" name="seo_description" id="seo_description" rows="3" ><?php echo $category['seo_description']; ?></textarea>
		  </div>
		  <input type="hidden" name="form_token" id="form_token" value="<?php echo $form_token; ?>" />
		  <button type="submit" name="submit" class="btn btn-primary">Save</button>
		</form>
<?php
break;
case 'delete';
$id = abs(intval(make_safe(xss_clean($_GET['id']))));
if (isset($_POST['move'])) {
$new_category = make_safe(xss_clean(intval($_POST['category_id'])));
if (empty($new_category)) {
$message = notification('warning','Please Select a Topic that you want to move the Quotes to.');	
} else {
$sql = "SELECT * FROM quotes WHERE category_id='$id'";
$query = $mysqli->query($sql);
if ($query->num_rows > 0) {
while ($row = $query->fetch_assoc()) {
$mysqli->query("UPDATE quotes SET category_id='$new_category' WHERE category_id='$id'");
}	
}
$delete = $mysqli->query("DELETE FROM categories WHERE id='$id'");
if ($delete) {
$message = notification('success','Quotes Moved and Topic Deleted Successfully.');
$done = true;
} else {
$message = notification('danger','Error Happened.');
}
}
}
if (isset($_POST['delete'])) {
$mysqli->query("DELETE FROM quotes WHERE category_id='$id'");	
$delete = $mysqli->query("DELETE FROM categories WHERE id='$id'");
if ($delete) {
$message = notification('success','Topic and All related Quotes Deleted Successfully.');
$done = true;
} else {
$message = notification('danger','Error Happened.');
}
}
$tcategory = $general->category($id);
?>
			<div class="page-header page-heading">
				<h1>Delete Topic
				<a href="categories.php" class="btn btn-default  pull-right"><span class="fa fa-arrow-right"></span></a>
				</h1>
			</div>
			<?php if (isset($message)) {echo $message;} ?>
		  <form role="form" method="POST" action="">
		  <?php if ($tcategory['quotes_number'] > 0) { ?>
			<div class="alert alert-warning">The Topic <b><?php echo $tcategory['category']; ?></b> Contains <b><?php echo $tcategory['quotes_number']; ?></b> quote(s). Do You Want To Move Them to Another Topic ?</div>
		<div class="form-group">
			<label for="seo_keywords">Choose a Topic to Move The Quote(s) To.</label>
		  <select class="form-control" name="category_id" id="category_id">
			<?php 
			$categories = $general->categories('id ASC');
			foreach ($categories AS $category) {
			if ($tcategory['id'] == $category['id']) {
				
			} else {
			?>
			<option value="<?php echo $category['id']; ?>"><?php echo $category['category']; ?></option>
			<?php			
			}
			}
			?>
			</select>
		</div>
		  <?php } ?>
		  <?php if (isset($done)) { ?>
		  <a href="categories.php" class="btn btn-default">Back To Topics</a>
		  <?php } else { ?>
		  <button type="submit" name="move" class="btn btn-warning">Move Then Delete</button>
		  <button type="submit" name="delete" class="btn btn-danger">Just Delete</button>
		  <?php } ?>
		</form>
<?php
break;
default;
?>
<div class="page-header page-heading">
	<h1><i class="fa fa-reorder"></i> Topics
	<a href="categories.php?case=add" class="btn btn-success pull-right"><span class="fa fa-plus"></span></a>
	</h1>
</div>
<?php
$page = 1;
$size = 20;
if (isset($_GET['page'])){ $page = (int) $_GET['page']; }
$sqls = "SELECT * FROM categories ORDER BY id DESC";
$query = $mysqli->query($sqls);
$total_records = $query->num_rows;
if ($total_records == 0) {
echo notification('warning','You Hadn\'t added any topic. <a href="?case=add">Add New Topic</a>');
} else {
$pagination = new Pagination();
$pagination->setLink("?page=%s");
$pagination->setPage($page);
$pagination->setSize($size);
$pagination->setTotalRecords($total_records);
$get = "SELECT * FROM categories ORDER BY id DESC ".$pagination->getLimitSql();
$q = $mysqli->query($get);
?>
<table width="100%" cellpadding="5" cellspacing="0" class="table table-striped">
    <thead>
        <tr>
			<th>Topic</th>
			<th class="hidden-xs">Quotes</th>
            <th></th>
        </tr>
    </thead>
	<tbody>
<?php 
while ($row = $q->fetch_assoc()) {
?>
		<tr>
			<td><a href="quotes.php?case=category&id=<?php echo $row['id']; ?>"><?php echo $row['category']; ?></a></td>
			<td class="hidden-xs" id="topic-quotes-<?php echo $row['id']; ?>"><?php echo $row['quotes_number']; ?></td>
			<td align="right">
				<a class="count_topic_quotes btn btn-warning btn-xs" href="javascript:void();" id="<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Count Topic Quotes"><span class="fa fa-bar-chart"></span></a>
				<a class="btn btn-default btn-xs" href="categories.php?case=edit&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Edit"><span class="fa fa-edit"></span></a>
				<a class="btn btn-danger btn-xs" href="categories.php?case=delete&id=<?php echo $row['id']; ?>" data-toggle="tooltip" data-placement="top" title="Delete"><span class="fa fa-close"></span></a>
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