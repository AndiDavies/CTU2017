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


	if (isset($_GET['del_outline']))
	{
		echo 'del_outline';
		$outline_rank = mysql_real_escape_string($_GET['outline_rank']);
		$report_id = mysql_real_escape_string($_GET['report_id']);
		$delete = 'DELETE FROM ctu_report_section3outline_inprog WHERE report_id ="'. $report_id .'" AND outline_rank ="'. $outline_rank .'";';
		$deleteqry = mysql_query($delete) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete."<br />\nError: (".mysql_errno().") ".mysql_error());
		header("Location: ".$url."section3.php");
	}
	
	
	if (isset($_GET['del_project']))
	{
		echo 'del_project';
		$project_rank = mysql_real_escape_string($_GET['project_rank']);
		$report_id = mysql_real_escape_string($_GET['report_id']);
		$delete = 'DELETE FROM ctu_report_section3full_inprog WHERE report_id ="'. $report_id .'" AND full_rank ="'. $project_rank .'";';
		$deleteqry = mysql_query($delete) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete."<br />\nError: (".mysql_errno().") ".mysql_error());
		header("Location: ".$url."section3.php");
		
	}
	
	
	
?>