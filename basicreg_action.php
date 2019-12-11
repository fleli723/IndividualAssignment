<?php

require_once("Template.php");

$page = new Template("Email and Password Action");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();

if ($_POST["password"] == $_POST["passwordVerify"] &&
filter_var($_POST["email"],FILTER_VALIDATE_EMAIL) && $_POST["password"] != " " &&
isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["passwordVerify"]) &&
$_POST["email"] != '' && $_POST["password"] != '' && $_POST["passwordVerify"] != '')
{
	$_SESSION["email"] = $_POST["email"];
	$_SESSION["password"] = $_POST["password"];

	die(header("Location: addr.php"));
}
else
{
	$_SESSION["error"] = "Please enter valid credentials";
	die(header("Location: basicreg.php"));
}


print $page->getBottomSection();

?>