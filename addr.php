<?php

require_once("Template.php");

$page = new Template("Address Page");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();


print 	'<form action="addr_action.php" method="post">
			Real Name:<br>
			<input type="text" name="name">
			<br>
			Address:<br>
			<input type="text" name="address">
			<br>
			Zip Code:<br>
			<input type="text" name="zipCode">
			<br><br>
			<input type="submit" value="Submit"">
		</form>';
		
		if (isset($_SESSION["error"]))
		{
			print $_SESSION["error"];
			unset($_SESSION["error"]);
		}


print $page->getBottomSection();

?>