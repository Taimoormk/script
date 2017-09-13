<?php
function smarty_modifier_html_decode($string) {
       return stripslashes(htmlspecialchars_decode($string, ENT_QUOTES));
}

?>