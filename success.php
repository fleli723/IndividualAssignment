<?php

require_once("Template.php");

$page = new Template("Completed Registration Page");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();

print 	'<h1> You have successfully registered!</h1>';

print $page->getBottomSection();

?>