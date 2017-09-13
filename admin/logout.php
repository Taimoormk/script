<?php
session_start();
if (isset($_SESSION['quotes_cms_admin'])) {
unset($_SESSION['quotes_cms_admin']);
session_destroy();
echo "<meta http-equiv='refresh' content='0;URL=login.php'>";
}
?>