<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
$valid=1;
$sqlcheckvalid = "SELECT count(*) as count FROM ctu_activityreporting WHERE report_id = '".$_SESSION['report_id']."' AND section1valid = 'yes' AND section2valid = 'yes' AND section3valid = 'yes' AND section3bvalid = 'yes' AND section4valid = 'yes' AND status != 'submitted'";
echo $sqlcheckvalid;
$qrycheckvalid = mysql_query($sqlcheckvalid);
//echo mysql_num_rows($qrycheckvalid);
if (mysql_num_rows($qrycheckvalid)>0)
{
	$sqlupdate = "UPDATE ctu_activityreporting SET status = 'submitted', statusdate = NOW() WHERE report_id = '".$_SESSION['report_id']."'";
	echo $sqlupdate;
	$qryupdate = mysql_query($sqlupdate);
}
header("Location: ".$url."reporthome.php");
?>