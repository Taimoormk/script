<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 * @author Manuel Polacek / Hitflip
 */


/**
 * Smarty regex_replace modifier plugin
 *
 * Type:     modifier<br>
 * Name:     substring
 * Purpose:  substring like in php
 * @param string
 * @return string
 */
function smarty_modifier_get_category_slug($id)
{
global $mysqli;
$sql = "SELECT slug FROM categories WHERE id='$id' LIMIT 1";
$query = $mysqli->query($sql);
$row = $query->fetch_assoc();
return $row['slug'];
}

?>