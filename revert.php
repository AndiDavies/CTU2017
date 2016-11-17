<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
if (!checkSoton())
{
	exit("You are not authorised to view this page.");
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	
</head>
<body>
<?php
$sql = "SELECT * FROM `ctu_report_section3full_history` where report_id = 1 and datestamp = '2013-10-14 09:46:01' order by full_rank";
$qry = mysql_query($sql);
while ($result = mysql_fetch_array($qry))
{
	$sql2 = "INSERT INTO `ctu_report_section3full` (report_id ,	full_rank ,	title ,	nihrprojectref , programme , datesubmitted , duration ,	plannedrecruitmenttotal , numberofprojectsites , intmultisite ,	expectedinput ,	currentstatus ,	estimatedoractualstartdate , isstartdateestimated ,	totalcost ,	expectedvalue ,	estimatedstaffcosts , estimatednonstaffcosts , nonstaffdesc) VALUES ( '".$result['report_id']."',	'".$result['full_rank']."' , '".$result['title']."','".$result['nihrprojectref']."','".$result['programme']."','".$result['datesubmitted']."','".$result['duration']."','".$result['plannedrecruitmenttotal']."','".$result['numberofprojectsites']."','".$result['intmultisite']."','".$result['expectedinput']."','".$result['currentstatus']."','".$result['estimatedoractualstartdate']."','".$result['isstartdateestimated']."','".$result['totalcost']."','".$result['expectedvalue']."','".$result['estimatedstaffcosts']."','".$result['estimatednonstaffcosts']."','".$result['nonstaffdesc']."')";
	echo $sql2."<br><br>";
	//
	
	
	
	//$qry2 = mysql_query($sql2);
}

$sql = "SELECT * FROM `ctu_report_section3outline_history` where report_id = 1 and datestamp = '2013-10-14 09:46:01' order by outline_rank";
$qry = mysql_query($sql);
while ($result = mysql_fetch_array($qry))
{
	$sql2 = "INSERT INTO `ctu_report_section3outline` (report_id , outline_rank , title , programme , reference , submitdate , staffinput , expectedvalue , status) VALUES ( '".$result['report_id']."',	'".$result['outline_rank']."' , '".mysql_real_escape_string($result['title'])."','".$result['programme']."','".$result['reference']."','".$result['submitdate']."','".$result['staffinput']."','".$result['expectedvalue']."','".$result['status']."')";
	echo $sql2."<br><br>";
	//$qry2 = mysql_query($sql2);
}

?>
</body>
</html>