<?php
function smarty_modifier_get_author_slug($id)
{
global $mysqli;
$sql = "SELECT slug FROM authors WHERE id='$id' LIMIT 1";
$query = $mysqli->query($sql);
$row = $query->fetch_assoc();
return $row['slug'];
}
?>