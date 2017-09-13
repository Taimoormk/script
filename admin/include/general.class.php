<?php 
class General {

	var $mysqli;
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
	// get single link by id
	function link($id)
	{
	$sql = "SELECT * FROM links WHERE id='$id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	
	}
	// get single page by id
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
	// get single category by id
	function category($id)
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
	function author($id)
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
	
	function quote($id)
	{
	$sql = "SELECT * FROM quotes WHERE id='$id' LIMIT 1";
	$query = $this->db->query($sql);
		if ($query->num_rows == 0) {
			return 0;
		} else {
			$row = $query->fetch_assoc();
			return $row;
		}
	}
	
	function pageviews()
	{
	$sql = "SELECT SUM(hits) AS topics_hits FROM categories";
	$query = $this->db->query($sql);
	$row = $query->fetch_assoc();
	$sql2 = "SELECT SUM(hits) AS authors_hits FROM authors";
	$query2 = $this->db->query($sql2);
	$row2 = $query2->fetch_assoc();
	$sql3 = "SELECT SUM(hits) AS quotes_hits FROM quotes";
	$query3 = $this->db->query($sql3);
	$row3 = $query3->fetch_assoc();
	return $row['topics_hits']+$row2['authors_hits']+$row3['quotes_hits'];
	}
	
	function topics_number()
	{
	$sql = "SELECT id FROM categories";
	$query = $this->db->query($sql);
	return $query->num_rows;
	}
	
	function authors_number()
	{
	$sql = "SELECT id FROM authors";
	$query = $this->db->query($sql);
	return $query->num_rows;
	}
	
	function quotes_number()
	{
	$sql = "SELECT id FROM quotes";
	$query = $this->db->query($sql);
	return $query->num_rows;
	}
	
}
?>