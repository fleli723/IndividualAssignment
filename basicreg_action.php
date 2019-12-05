<?php

require_once("Template.php");

$page = new Template("Email and Password Action");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();

//var_dump($_POST);


if ($_POST["password"] == $_POST["passwordVerify"] && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) && $_POST["password"] != " " && $_POST["passwordVerify"] != " ") //and if email is valid
{
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];
	$_SESSION["passwordVerify"] = $_POST["passwordVerify"];
	header("Location: addr.php");
	
	print $_SESSION["password"];
}
else
{
	print "no";
	$_SESSION["error"] = "Please enter valid credentials";
	header("Location: basicreg.php");
}


print $page->getBottomSection();

?>