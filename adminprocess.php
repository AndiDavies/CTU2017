<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
//db_connect();
if (!checkSoton())
{
	header("Location: ".$url."admin.php");
}
//db_connect();
echo "<pre>";
foreach($_REQUEST as $key=>$value)
{
	echo $key." - ".$value."<br>";//</b><br>";
}
echo "</pre>";

// DEFINE Functions to delete sections or all form.
function sec1del($report_id)
{
		$delete1sql = "DELETE FROM ctu_report_section1 WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete2sql = "UPDATE ctu_activityreporting SET section1valid='no' , section1lastupdate = null WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
}
function sec2del($report_id)
{
		$delete1sql = "DELETE FROM ctu_report_section2 WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete2sql = "DELETE FROM ctu_report_section2current WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete3sql = "DELETE FROM ctu_report_section2planned WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete4sql = "UPDATE ctu_activityreporting SET section2valid='no' , section2lastupdate = null WHERE report_id = '".mysql_real_escape_string($report_id)."'";

		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete3qry = mysql_query($delete3sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete3sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete4qry = mysql_query($delete4sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete4sql."<br />\nError: (".mysql_errno().") ".mysql_error());
}
function sec3adel($report_id)
{
		$delete1sql = "DELETE FROM ctu_report_section3_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete2sql = "DELETE FROM ctu_report_section3outline_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete3sql = "DELETE FROM ctu_report_section3full_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete4sql = "UPDATE ctu_activityreporting SET section3valid='no' , section3lastupdate = null WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete3qry = mysql_query($delete3sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete3sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete4qry = mysql_query($delete4sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete4sql."<br />\nError: (".mysql_errno().") ".mysql_error());
}
function sec3bdel($report_id)
{
		$delete1sql = "DELETE FROM ctu_report_section3b_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete2sql = "DELETE FROM ctu_report_section3bclosed_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete3sql = "DELETE FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete4sql = "UPDATE ctu_activityreporting SET section3bvalid='no' , section3blastupdate = null WHERE report_id = '".mysql_real_escape_string($report_id)."'";

		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete3qry = mysql_query($delete3sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete3sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete4qry = mysql_query($delete4sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete4sql."<br />\nError: (".mysql_errno().") ".mysql_error());
}
function sec4del($report_id)
{
		$delete1sql = "DELETE FROM ctu_report_section4 WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		$delete2sql = "UPDATE ctu_activityreporting SET section4valid='no' , section4lastupdate = null WHERE report_id = '".mysql_real_escape_string($report_id)."'";
		
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
}
function unsubmit($report_id)
{
	$unsubsql = "UPDATE ctu_activityreporting SET status='inprogress' , statusdate = now() WHERE report_id = '".mysql_real_escape_string($report_id)."'";
	//$unsubqry = mysql_query($unsubsql);
	
	mysql_query($unsubsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$unsubsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	
	
	
	
}




if ($_REQUEST['action']=='clearsection')
{
	if ($_REQUEST['section']=='1')
	{
		sec1del($_REQUEST['report_id']);
	}
	elseif ($_REQUEST['section']=='2')
	{
		sec2del($_REQUEST['report_id']);
	}
	elseif ($_REQUEST['section']=='3')
	{
		sec3adel($_REQUEST['report_id']);
	}
	elseif ($_REQUEST['section']=='3b')
	{
		sec3bdel($_REQUEST['report_id']);
	}
	elseif ($_REQUEST['section']=='4')
	{
		sec4del($_REQUEST['report_id']);
	}
	elseif ($_REQUEST['section']=='all')
	{
		sec1del($_REQUEST['report_id']);
		sec2del($_REQUEST['report_id']);
		sec3adel($_REQUEST['report_id']);
		sec3bdel($_REQUEST['report_id']);
		sec4del($_REQUEST['report_id']);
	}
}
elseif ($_REQUEST['action']=='unsubmit')
{
	unsubmit($_REQUEST['report_id']);
}
header("Location: ".$url."admin.php");
?>