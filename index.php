<?php
define('vendors', true);
if(isset($_GET['apply'])) {
	include('pages/apply.php');
} else {
	include('pages/index.php');
}
?>
