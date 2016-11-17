<?php
ob_start();
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
$valid=1;
$errors = array();
$time = date("Y-m-d H-i-s");
$sqlhistory = "INSERT INTO ctu_report_section4_history SELECT ctu_report_section4.*, '".$time."' FROM ctu_report_section4 WHERE report_id = '".$_SESSION['report_id']."'";
$qryhistory  = mysql_query($sqlhistory);
echo mysql_error();
//echo "Hello. This is debugging text. It's only really useful if things go wrong.<br/>";
echo "report_id - ".$_SESSION['report_id']."<br>";
//spit out $_POST array
echo "<pre>";
foreach($_POST as $key=>$value)
{
	echo $key." - ".$value."<br>";//</b><br>";
}
echo "</pre>";

//Nothing is required on this form - it's awesome!!



//If no errors, commit to database:
if ($valid==1)
{
	//Check if row exists already:
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section4 WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']==0)
	{
		//Row does not exist - 3 tables to insert into
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section4 
				(	report_id,
					anyfurthercomments
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['anyfurthercomments'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
				
	}
		
	
	else
	{
		//echo "Row exists - remove and replace.";
		$delete1sql = "DELETE FROM ctu_report_section4 WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		
//		
//		//Replace:
	//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section4 
				(	report_id,
					anyfurthercomments
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['anyfurthercomments'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
				
	}
//	
	//Update section validity
	$sectionsql = "UPDATE ctu_activityreporting SET section4valid = 'not yet validated', status = 'inprogress' , section4lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());

	header("Location: ".$url."reporthome.php");
}

else
{
//Throw back to section3.php with $_GET of errors;
$errorstring="";
header("Location: ".$url."section4.php?errors=".$errorstring);
}
?>