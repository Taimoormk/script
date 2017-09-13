<?php 
class General {

	private $mysqli;
	
	// connect to database
    function set_connection($mysqli) {
        $this->db =& $mysqli;
    }
	
	// get all categories
	function categories($order)
	{
	$sql = "SELECT * FROM categories ORDER BY $order";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	
	}
	
	function topics_query($order,$number)
	{
	$sql = "SELECT * FROM categories ORDER BY $order LIMIT $number";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	
	}
	
	function authors_query($order,$number)
	{
	$sql = "SELECT * FROM authors ORDER BY $order LIMIT $number";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	
	}
	
	function quotes_query($where,$order,$number)
	{
	$sql = "SELECT * FROM quotes $where ORDER BY $order LIMIT $number";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	
	}
	
	// get single category by slug
	function topic_by_slug($slug)
	{
	$sql = "SELECT * FROM categories WHERE slug='$slug' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$this->db->query("UPDATE categories SET hits=hits+1 WHERE slug='$slug'");
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	// get single author by slug
	function author_by_slug($slug)
	{
	$sql = "SELECT * FROM authors WHERE slug='$slug' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$this->db->query("UPDATE authors SET hits=hits+1 WHERE slug='$slug'");
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	
	// get single category by id
	function topic_by_id($id)
	{
	$sql = "SELECT * FROM categories WHERE id='$id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	// get single author by id
	function author_by_id($id)
	{
	$sql = "SELECT * FROM authors WHERE id='$id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	
	
	// get featured videos query
	function quotes($order,$number)
	{
	
	$sql = "SELECT * FROM quotes ORDER BY $order LIMIT $number";	
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	}
	
	
	
	// get all links 
	function links($order)
	{
	$sql = "SELECT * FROM links ORDER BY $order";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	
	}
	
	
	// get all pages
	function pages($order)
	{
	$sql = "SELECT * FROM pages ORDER BY $order";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			while ($row = $query->fetch_assoc()) {
				$rows[] = $row;
			}
			return $rows;
		}
	}
	
	// get single page by ID
	function page($id)
	{
	$sql = "SELECT * FROM pages WHERE id='$id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	
	// get single quote by ID AND Author ID
	function quote($id,$author_id)
	{
	$sql = "SELECT * FROM quotes WHERE id='$id' AND author_id='$author_id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$this->db->query("UPDATE quotes SET hits=hits+1 WHERE id='$id'");
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	
	// get a random quote
	function lucky_strike()
	{
	$sql = "SELECT * FROM quotes ORDER BY rand() LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	}
}
?>