<?php

require_once("Template.php");

$page = new Template("Address Action");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();


if ($_POST["name"] != " " && $_POST["address"] != " " &&
$_POST["zipCode"] != " " && isset($_POST["name"]) &&
isset($_POST["address"]) && isset($_POST["zipCode"]) &&
$_POST["name"] != '' && $_POST["address"] != '' && $_POST["zipCode"] != '')
{
	//Web Service
	$data = array("apikey" => "nhqco4abg03zttg1",
	"username" => "fleli723",
	"zip" => ($_POST["zipCode"]));

	$dataJson = json_encode($data);

	$contentLength = strlen($dataJson);

	$header = array(
	'Content-Type: application/json',
			'Accept: application/json',
	'Content-Length: ' . $contentLength
	);

	$url = "http://cnmt310.braingia.org/ziplookup.php";
	
	$ch = curl_init();
	
	curl_setopt($ch,
		CURLOPT_URL, $url);
	curl_setopt($ch,
		CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,
		CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch,
		CURLOPT_POSTFIELDS, $dataJson);
	curl_setopt($ch,
		CURLOPT_HTTPHEADER, $header);
	
	$return = curl_exec($ch);
	
	$httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	if ($httpStatus != 200) {
			print "Something went wrong with the request: " . $httpStatus;
			curl_close($ch);
			//exit;
	}
	
	$resultObject = json_decode($return);
	
	if (!is_object($resultObject)) {
			$_SESSION["error"] = "Something went wrong decoding the return";
			curl_close($ch);
	}
	
	if (property_exists($resultObject->result,"errorMessage"))
	{
		$_SESSION["error"] = $resultObject->errorMessage;
		die(header("Location: addr.php"));	
	}
	
	if (isset($resultObject->errorMessage))
	{
		$_SESSION["error"] = $resultObject->errorMessage;
		die(header("Location: addr.php"));
	}
	
	//storing into session if valid data
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["address"] = $_POST["address"];
	$_SESSION["zipCode"] = $_POST["zipCode"];
	if (isset($resultObject->city) && isset($resultObject->state))
	{
		$_SESSION["city"] = $resultObject->city;
		$_SESSION["state"] = $resultObject->state;
	}
	

	curl_close($ch);
	
	die(header("Location: confirm.php"));
}
else
{
	$_SESSION["error"] = "Please enter valid credentials";
	die(header("Location: addr.php"));
}


print $page->getBottomSection();

?>