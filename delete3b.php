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


	if (isset($_GET['del_current']))
	{
		echo 'del_current';
		$outline_rank = mysql_real_escape_string($_GET['current_rank']);
		$report_id = mysql_real_escape_string($_GET['report_id']);
		$delete = 'DELETE FROM ctu_report_section3bcurrent_inprog WHERE report_id ="'. $report_id .'" AND current_rank ="'. $outline_rank .'";';
		$deleteqry = mysql_query($delete) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete."<br />\nError: (".mysql_errno().") ".mysql_error());
		header("Location: ".$url."section3b.php");
	}
	
	
	if (isset($_GET['del_closed']))
	{
		echo 'del_closed';
		$closed_rank = mysql_real_escape_string($_GET['closed_rank']);
		$report_id = mysql_real_escape_string($_GET['report_id']);
		$delete = 'DELETE FROM ctu_report_section3bclosed_inprog WHERE report_id ="'. $report_id .'" AND closed_rank ="'. $closed_rank .'";';
		$deleteqry = mysql_query($delete) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete."<br />\nError: (".mysql_errno().") ".mysql_error());
		header("Location: ".$url."section3b.php");
		
	}
	

	
?>