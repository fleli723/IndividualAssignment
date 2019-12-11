<?php

require_once("Template.php");

$page = new Template("Confirmation Page");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();


if (isset($_SESSION["email"]) && isset($_SESSION["password"]) &&
	isset($_SESSION["name"]) && isset($_SESSION["address"]) && isset($_SESSION["zipCode"]) &&
	isset($_SESSION["city"]) && isset($_SESSION["state"]))
{	
	print 	'<form action="confirm_action.php" method="post">
				Email:<br>
				<input type="text" name="email" value="'. $_SESSION["email"] . '">
				<br>
				Real Name:<br>
				<input type="text" name="name" value="'. $_SESSION["name"] . '">
				<br>
				Address:<br>
				<input type="text" name="address" value="'. $_SESSION["address"] . '">
				<br>
				Zip Code:<br>
				<input type="text" name="zipCode" value="'. $_SESSION["zipCode"] . '">
				<br>
				City:<br>
				<input type="text" name="city" value="'. $_SESSION["city"] . '" readonly>
				<br>
				State:<br>
				<input type="text" name="state" value="'. $_SESSION["state"] . '" readonly>
				<br><br>
				<input type="submit" value="Submit">
			</form>';
			
	if (isset($_SESSION["error"]))
	{
		print $_SESSION["error"];
		unset($_SESSION["error"]);
	}	
}		
else
{
		
	print 	'<br><form action="basicreg.php" method="post">
				<input type="submit" value="Go Back to the Start">
			</form>';
}	


print $page->getBottomSection();

?>