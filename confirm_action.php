<?php

require_once("Template.php");
require_once("DB.class.php");
$page = new Template("Confirmation Action");
$page->finalizeTopSection();
$page->finalizeBottomSection();

print $page->getTopSection();
session_start();



if ($_POST["email"] != " " && $_POST["name"] != " " && $_POST["address"] != " " && $_POST["zipCode"] != " " &&
isset($_POST["email"]) && isset($_POST["name"]) && isset($_POST["address"]) && isset($_POST["zipCode"]) &&
isset($_POST["city"]) && isset($_POST["state"])&&
$_POST["email"] != '' && $_POST["name"] != '' && $_POST["address"] != '' && $_POST["zipCode"] != '')
{
	if ($_SESSION["zipCode"] != $_POST["zipCode"])
	{
		//Web Service if zipcode is changed
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
		}
		
		$resultObject = json_decode($return);
		
		if (isset($resultObject->errorMessage))
		{
			$_SESSION["error"] = $resultObject->errorMessage;
			die(header("Location: confirm.php"));
		}
		
		//set session variables
		$_SESSION["zipCode"] = $_POST["zipCode"];
		if (isset($resultObject->city) && isset($resultObject->state))
		{
			$_SESSION["city"] = $resultObject->city;
			$_SESSION["state"] = $resultObject->state;
		}
		
		curl_close($ch);
		
		if ($_SESSION["email"] != $_POST["email"] || $_SESSION["name"] != $_POST["name"] ||
			$_SESSION["address"] != $_POST["address"])
		{
				$_SESSION["email"] = $_POST["email"];
				$_SESSION["name"] = $_POST["name"];
				$_SESSION["address"] = $_POST["address"];
				die(header("Location: confirm.php"));	
		}
		
		die(header("Location: confirm.php"));	
	}
	
	if ($_SESSION["email"] != $_POST["email"] || $_SESSION["name"] != $_POST["name"] ||
		$_SESSION["address"] != $_POST["address"])
	{
		$_SESSION["email"] = $_POST["email"];
		$_SESSION["name"] = $_POST["name"];
		$_SESSION["address"] = $_POST["address"];
		die(header("Location: confirm.php"));	
	}
	else
	{
		//insert into DB
		$con = new DB();
		if (!$con->getConnStatus())
		{
		$_SESSION["error"] = "An error has occurred with connection";
		die(header("Location: confirm.php"));
		}
		else
		{
			$emailPreEsc = filter_var($_SESSION["email"], FILTER_SANITIZE_EMAIL);
			$emailSafe = $con->dbEsc($emailPreEsc);
			
			$realNamePreEsc = filter_var($_SESSION["name"], FILTER_SANITIZE_STRING);
			$realNameSafe = $con->dbEsc($realNamePreEsc);
			
			$addressPreEsc = filter_var($_SESSION["address"], FILTER_SANITIZE_STRING);
			$addressSafe = $con->dbEsc($addressPreEsc);
			
			$zipCodePreEsc = filter_var($_SESSION["zipCode"], FILTER_SANITIZE_STRING);
			$zipCodeSafe = $con->dbEsc($zipCodePreEsc);
			
			$cityPreEsc = filter_var($_SESSION["city"], FILTER_SANITIZE_STRING);
			$citySafe = $con->dbEsc($cityPreEsc);
			
			$statePreEsc = filter_var($_SESSION["state"], FILTER_SANITIZE_STRING);
			$stateSafe = $con->dbEsc($statePreEsc);
			
			$query = "INSERT INTO register (inserttime, email, realName, address, zipCode, city, state) 
			VALUES (CURRENT_TIMESTAMP, '{$emailSafe}', '{$realNameSafe}', '{$addressSafe}', '{$zipCodeSafe}', '{$citySafe}', '{$stateSafe}')";
			
			$result = $con->dbCall($query);
			
			if (!$result) {
				$_SESSION["error"] = "An error has occurred with the db";
				die(header("Location: confirm.php"));
			}
		}
		die(header("Location: success.php"));
	}	
}
else
{
	$_SESSION["error"] = "Please enter valid credentials";
	die(header("Location: confirm.php"));
}


print $page->getBottomSection();

?>