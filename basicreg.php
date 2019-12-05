<?php

require_once("Template.php");

$page = new Template("Email and Password Page");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();

//add topnavbar with links to 2 other sites

print 	'<form action="basicreg_action.php" method="post">
			Email:<br>
			<input type="text" name="email" placeholder="Enter a Valid Email">
			<br>
			Password:<br>
			<input type="password" name="password">
			<br>
			Verify Password:<br>
			<input type="password" name="passwordVerify">
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