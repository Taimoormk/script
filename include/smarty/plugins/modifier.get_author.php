<?php
function smarty_modifier_get_author($id)
{
global $mysqli;
$sql = "SELECT author FROM authors WHERE id='$id' LIMIT 1";
$query = $mysqli->query($sql);
$row = $query->fetch_assoc();
return $row['author'];
}
?>