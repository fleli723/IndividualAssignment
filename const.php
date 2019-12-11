<?php
define("MUSER","lelis_f_admin");
define("MPASS","qof43niv");
define("MSERVER","cnmtsrv1.uwsp.edu");
define("MDB","lelis_f");
$con = new mysqli(MSERVER, MUSER, MPASS, MDB);
 
if (basename($_SERVER['PHP_SELF']) == "const.php") {
  die(header("HTTP/1.0 404 Not Found"));
}
?>