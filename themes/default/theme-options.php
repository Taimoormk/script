<?php 
// prevent direct access
if (!isset($theme)) {
die('You Can not Access Directly');	
}
?>
<div class="page-header page-heading">
            <h1><i class="fa fa-paint-brush"></i> Theme Setting</h1>
        </div>
		<?php if (isset($message)) {echo $message;} ?>
		<form role="form" method="POST" action="">
		  <div class="form-group">
			<label for="home_quotes_number">Number Of Quotes In HomePage</label>
			<input type="number" name="home_quotes_number" id="home_quotes_number" class="form-control" value="<?php if (isset($theme['home_quotes_number'])) {echo $theme['home_quotes_number'];} ?>" placeholder="24" />
		  </div>
		  <div class="form-group">
			<label for="category_quotes_number">Number Of Quotes In Each Topic Page</label>
			<input type="number" name="category_quotes_number" id="category_quotes_number" class="form-control" value="<?php if (isset($theme['category_quotes_number'])) {echo $theme['category_quotes_number'];} ?>" placeholder="24" />
		  </div>
		  <div class="form-group">
			<label for="author_quotes_number">Number Of Quotes In Each Author Page</label>
			<input type="number" name="author_quotes_number" id="author_quotes_number" class="form-control" value="<?php if (isset($theme['author_quotes_number'])) {echo $theme['author_quotes_number'];} ?>" placeholder="24" />
		  </div>
		  <div class="form-group">
			<label for="search_quotes_number">Number Of Quotes In Search results Page</label>
			<input type="number" name="search_quotes_number" id="search_quotes_number" class="form-control" value="<?php if (isset($theme['search_quotes_number'])) {echo $theme['search_quotes_number'];} ?>" placeholder="24" />
		  </div>
		  <div class="form-group">
			<label for="related_quotes_number">Number Of Related Quotes (author & topic) In Single Quote Page</label>
			<select name="related_quotes_number" id="related_quotes_number" class="form-control">
			<?php for ($r=1;$r<11;$r++) { ?>
			<option value="<?php echo $r; ?>" <?php if (isset($theme['related_quotes_number']) AND $theme['related_quotes_number'] == $r) {echo 'SELECTED';} ?>><?php echo $r; ?></option>
			<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
			<label for="top_topics_number">Number Of Topics In (Top Topics Widget)</label>
			<select name="top_topics_number" id="top_topics_number" class="form-control">
			<?php for ($f=4;$f<61;$f=$f+2) { ?>
			<option value="<?php echo $f; ?>" <?php if (isset($theme['top_topics_number']) AND $theme['top_topics_number'] == $f) {echo 'SELECTED';} ?>><?php echo $f; ?></option>
			<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
			<label for="top_authors_number">Number Of Authors In (Top Authors Widget)</label>
			<select name="top_authors_number" id="top_authors_number" class="form-control">
			<?php for ($g=4;$g<61;$g=$g+2) { ?>
			<option value="<?php echo $g; ?>" <?php if (isset($theme['top_authors_number']) AND $theme['top_authors_number'] == $g) {echo 'SELECTED';} ?>><?php echo $g; ?></option>
			<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
			<label for="all_authors_number">Number Of Authors In (All Authors Page)</label>
			<select name="all_authors_number" id="all_authors_number" class="form-control">
			<?php for ($y=12;$y<61;$y=$y+3) { ?>
			<option value="<?php echo $y; ?>" <?php if (isset($theme['all_authors_number']) AND $theme['all_authors_number'] == $y) {echo 'SELECTED';} ?>><?php echo $y; ?></option>
			<?php } ?>
			</select>
		  </div>
		  <div class="form-group">
			<input type="checkbox" name="display_random_quote_widget" id="display_random_quote_widget" value="1" <?php if (isset($theme['display_random_quote_widget']) AND $theme['display_random_quote_widget'] == 1) {echo 'CHECKED';} ?> /> <span class="checkbox-label">Display Random Quote Widget ?</span>
		  </div>
		  <div class="form-group">
			<input type="checkbox" name="display_disqus_comments" id="display_disqus_comments" value="1" <?php if (isset($theme['display_disqus_comments']) AND $theme['display_disqus_comments'] == 1) {echo 'CHECKED';} ?> /> <span class="checkbox-label">Display Disqus Comments ?</span>
		  </div>
		  <div class="form-group">
			<label for="disqus_shortname">Disqus Shortname</label>
			<input type="text" class="form-control" name="disqus_shortname" id="disqus_shortname" value="<?php if (isset($theme['disqus_shortname'])) { echo $theme['disqus_shortname']; }?>" />
		  </div>
		  <button type="submit" name="save" class="btn btn-primary">Save</button>
		</form>