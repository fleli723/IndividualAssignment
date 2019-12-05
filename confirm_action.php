<?php

require_once("Template.php");

$page = new Template("Confirmation Action");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();


if ($_POST["name"] != " " && $_POST["address"] != " " && $_POST["zipCode"] != " ") //and if email is valid
{
	//Web Service
	$data = array("apikey" => "nhqco4abg03zttg1",
	"username" => "fleli723",
	"zip" => ($_SESSION["zipCode"]));


	if ($_SESSION["zipCode"] != $_POST["zipCode"])
	{
	$dataJson = json_encode($data);

	//Should probably urlencode

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
	
	//TO-DO ERROR HANDLING
	//if (!is_object($resultObject)) {
	//		print "Something went wrong decoding the return";
	//		curl_close($ch);
	//		//exit;
	//}
	//if (property_exists($resultObject,"result")) {
	//		if (property_exists($resultObject->result,"ErrorMessage")) {
	//				//If this was user-facing, a better error message would be needed.
	//				print "Something went wrong: " . $resultObject->result->ErrorMessage;
	//		} else {
	//		// Should probably check if property_exists on result too.
	//				print "Hex value is: " . $resultObject->result;
	//		}
	//} else {
	//		print "Something went wrong with the return, no result found";
	//}
	if (isset($resultObject->errorMessage))
	{
		$_SESSION["error"] = $resultObject->errorMessage;
		header("Location: confirm.php");
	}
	//CHANGE THIS
	$_SESSION["name"] = $_POST["name"];
	$_SESSION["address"] = $_POST["address"];
	$_SESSION["zipCode"] = $_POST["zipCode"];
	if (isset($resultObject->city) && isset($resultObject->state))
	{
		$_SESSION["city"] = $resultObject->city;
		$_SESSION["state"] = $resultObject->state;
	}
	

	curl_close($ch);
	}
	
	
	//ADD DB CONNECTION TO INSERT
	//SANITIZATION
}
else
{
	print "no";
	$_SESSION["error"] = "Please enter valid credentials";
	header("Location: addr.php");
}


print $page->getBottomSection();

?>