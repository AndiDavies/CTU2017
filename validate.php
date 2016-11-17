<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
//Firstly, if session report_id is not set throw user to report home page.
if (!isset($_SESSION['ctu']) || checksubmit($_SESSION['report_id']))
{
	header("Location: http://www.netscc.ac.uk/ctu_dev/forms/light/reporthome.php?reason=1");
}
ob_start(); 
?>

<!DOCTYPE HTML>

<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<!--<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-8">-->
	<title>CTU Activity Report Form</title>
	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="general-light.php" />
   
	
	<!--[if lt IE 9]>
	    	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE 8]>
    <style type="text/css">
    .form-input , .form-input-table{
    border-radius:0 !important;    
    }
    </style>
    <![endif]-->
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
    <script src="includes/js/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="includes/js/date.js"></script>
	<link rel="stylesheet" href="http://jqueryvalidation.org/files/demo/site-demos.css">
	
	<script>
	
	
	</script>



	
	
	
</head>	
<body>

<form class="general">

<?php
	$section = mysql_real_escape_string($_GET['section']);
	//echo $section;

	if ($section == '1')
	{
		//Update section validity
		$sectionsql = "UPDATE ctu_activityreporting SET section1valid = 'yes', section1lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
		$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		header("Location: ".$url."reporthome.php");
		
	}
	else if ($section == '2')
	{
		$sec2 = 0;
		echo '<h2>Section 2 Progress Summary Validation</h2>';
		echo '<h3>2.1 Objectives</h3>';
		//echo $_SESSION['report_id'];
		echo '<img class="valimg" src="images/tick.png" /> OK<br />';
		
		echo '<h3>2.2 Progress against objectives (optional)</h3>';
		//echo $_SESSION['report_id'];
		echo '<img class="valimg" src="images/tick.png" /> OK<br />';
		
		echo '<h3>2.3 Planned objectives for CTU Support Funding (to be completed by units in receipt of funding for less than 3 years only)</h3>';
		//echo $_SESSION['report_id'];
		echo '<img class="valimg" src="images/tick.png" /> OK<br />';
		
		//
		echo '<h3>2.4 Involvement with other research networks and participation on TSCs and DMCs</h3>';
		$sql = mysql_query('SELECT * FROM ctu_report_section2 WHERE report_id = "'.$_SESSION['report_id'].'";');
		while($row = mysql_fetch_array($sql))
		{
			//2.4
			if ($row['activitesdevwidernihr'] == '' || $row['activitesdevwidernihr'] ==  ctype_space($row['activitesdevwidernihr']) )
			{
				echo '<a href="section2.php?validate=V" class="errorlink"><img class="valimg" src="images/cross.png" />Section 2.4 is blank. This must contain a value. Click to edit.</a><br />';
				$sec2++;
			}
			else
			{
				echo '<img class="valimg" src="images/tick.png" /> OK<br />';
				
			}
			
			//2.5
			echo '<h3>2.5 Activity your unit has undertaken to increase the capacity for NIHR trials and studies (using your CTU Support Funding award)</h3>';
			if ($row['capacityactivity'] == '' || $row['capacityactivity'] ==  ctype_space($row['capacityactivity']) )
			{
				echo '<a href="section2.php?validate=V" class="errorlink"><img class="valimg" src="images/cross.png" />Section 2.5 is blank. This must contain a value. Click to edit.</a><br />';
				$sec2++;
			}
			else
			{
				echo '<img class="valimg" src="images/tick.png" /> OK<br />';
				
			}
			
			//2.6
			echo '<h3>2.6 Key training received by CTU staff and current unit training strategies</h3>';
			if ($row['trainingreceivedbyctustaff'] == '' || $row['trainingreceivedbyctustaff'] ==  ctype_space($row['trainingreceivedbyctustaff']) )
			{
				echo '<a href="section2.php?validate=V" class="errorlink"><img class="valimg" src="images/cross.png" />Section 2.6 is blank. This must contain a value. Click to edit.</a><br />';
				$sec2++;
			}
			else
			{
				echo '<img class="valimg" src="images/tick.png" /> OK<br />';
				
			}
			
			//2.7 - 2.8
			echo '<h3>2.7 Funding contributions from Higher Education Institution (HEI)</h3>';
			echo '<img class="valimg" src="images/tick.png" /> OK<br />';
			echo '<h3>2.8 Funding contributions from NHS Trusts</h3>';
			echo '<img class="valimg" src="images/tick.png" /> OK<br />';
			
			
			//2.9
			echo '<h3>2.9 Other infrastructure funding contributions</h3>';
			if ($row['variousfundingsources'] == '' || $row['variousfundingsources'] ==  ctype_space($row['variousfundingsources']) )
			{
				echo '<a href="section2.php?validate=V" class="errorlink"><img class="valimg" src="images/cross.png" />Section 2.9 is blank. This must contain a value. Click to edit.</a><br />';
				//$sec2++;
			}
			
			if ($sec2 <= 0)
			{
				//Update section validity
				$sectionsql = "UPDATE ctu_activityreporting SET section2valid = 'yes', section2lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
				$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
				
				header("Location: ".$url."reporthome.php");
			}
		}
	}
	else if ($section == '3a')
	{
		$sec3a = 0;
		echo '<h2>Section 3a: CTU NIHR Activity (Part 1)</h2>';
		
		echo '<h3>3.1 NIHR-related activity during this reporting period - summary</h3>';
		echo '<img class="valimg" src="images/tick.png" />a)  OK<br />';
		echo '<img class="valimg" src="images/tick.png" />b)  OK<br />';
		echo '<img class="valimg" src="images/tick.png" />c)  OK<br />';
		echo '<img class="valimg" src="images/tick.png" />d)  OK<br />';
		echo '<img class="valimg" src="images/tick.png" />e)  OK<br />';
		echo '<img class="valimg" src="images/tick.png" />f)  OK<br />';

		echo '<h3>3.2 Outline proposals submitted to NIHR in this reporting period</h3>';
		
		$sql = mysql_query("SELECT * FROM ctu_report_section3outline_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY outline_rank;");

				while ($row = mysql_fetch_array($sql))
				{
					if ($row['title'] == '' || $row['title'] ==  ctype_space($row['title']) 
						|| $row['programme'] == '' || $row['programme'] ==  ctype_space($row['title'])
						|| $row['reference'] == '' || $row['reference'] ==  ctype_space($row['reference'])
						|| $row['submitdate'] == '' || $row['submitdate'] ==  ctype_space($row['submitdate'])
						|| $row['staffinput'] == '' || $row['staffinput'] ==  ctype_space($row['staffinput'])
						|| $row['expectedvalue'] == '' //|| $row['expectedvalue'] ==  ctype_space($row['expectedvalue'])
						|| $row['status'] == '' || $row['status'] ==  ctype_space($row['status'])
						&& $row['expectedvalue'] !== '0'
						
						)
					{
						echo '<a href="section3edit.php?validate=V&outlineselect='. $row['outline_rank'] .'&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Outline '. $row['outline_rank'] .' has blank fields. This outlines fields must all contain a value. Click to edit.</a><br />';
						$sec3a++;

					}
					else
					{
						echo '<img class="valimg" src="images/tick.png" /> Outline '. $row['outline_rank'] .' OK<br />';
						//$sec3a = true;
					}
				
				
				}
		
		
		echo '<h3>3.3 Full proposals submitted to NIHR in this reporting period</h3>';
		$sql = mysql_query("SELECT * FROM ctu_report_section3full_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY full_rank;");
		
				while ($row = mysql_fetch_array($sql))
				{
					if ($row['title'] == '' || $row['title'] ==  ctype_space($row['title']) 
						|| $row['nihrprojectref'] == '' || $row['nihrprojectref'] ==  ctype_space($row['nihrprojectref'])
						|| $row['programme'] == '' || $row['programme'] ==  ctype_space($row['programme'])
						|| $row['datesubmitted'] == '' || $row['datesubmitted'] ==  ctype_space($row['datesubmitted'])
						//|| $row['duration'] == '' || $row['duration'] ==  ctype_space($row['duration'])
						//|| $row['plannedrecruitmenttotal'] == '' || $row['plannedrecruitmenttotal'] ==  ctype_space($row['plannedrecruitmenttotal'])
						//|| $row['numberofprojectsites'] == '' || $row['numberofprojectsites'] ==  ctype_space($row['numberofprojectsites'])
						//|| $row['intmultisite'] == '' || $row['intmultisite'] ==  ctype_space($row['intmultisite'])
						|| $row['expectedinput'] == '' || $row['expectedinput'] ==  ctype_space($row['expectedinput'])
						|| $row['currentstatus'] == '' || $row['currentstatus'] ==  ctype_space($row['currentstatus'])
						|| $row['estimatedoractualstartdate'] == '' || $row['estimatedoractualstartdate'] ==  ctype_space($row['estimatedoractualstartdate'])
						//|| $row['isstartdateestimated'] == '' || $row['isstartdateestimated'] ==  ctype_space($row['isstartdateestimated'])
						|| $row['totalcost'] == '' //|| $row['totalcost'] ==  ctype_space($row['totalcost'])
						|| $row['expectedvalue'] == '' //|| $row['expectedvalue'] ==  ctype_space($row['expectedvalue'])
						
						&& $row['totalcost'] !== '0'
						&& $row['expectedvalue'] !== '0'
						
						
						)
					{
						echo '<a href="section3edit.php?validate=V&projectselect='. $row['full_rank'] .'&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Full proposal '. $row['full_rank'] .' has blank fields. This projects fields must all contain a value. Click to edit.</a><br />';
						$sec3a++;
					}
					else
					{
						echo '<img class="valimg" src="images/tick.png" /> Full Proposal '. $row['full_rank'] .' OK<br />';
						//$sec3a = true;
					}

				}
				
				if ($sec3a <= 0)
				{
					//Transfer data to master
					//Delete the old data
					/*
					mysql_query ("DROP TABLE ctu_report_section3;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3 LIKE ctu_report_section3_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3 SELECT * FROM ctu_report_section3_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
					mysql_query ("DROP TABLE ctu_report_section3full;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3full LIKE ctu_report_section3full_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3full SELECT * FROM ctu_report_section3full_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
				
					mysql_query ("DROP TABLE ctu_report_section3outline;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3outline LIKE ctu_report_section3outline_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3outline SELECT * FROM ctu_report_section3outline_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
				*/
					
					
					
					
				
					//Update section validity
					$sectionsql = "UPDATE ctu_activityreporting SET section3valid = 'yes', section3lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
					$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
					header("Location: ".$url."reporthome.php");
				}
		
		
	}
	else if ($section == '3b')
	{
		$sec3b = 0;
		echo '<h2>Section 3b: CTU NIHR Activity (Part 2)</h2>';
		
		echo '<h3>3.4 Current NIHR research</h3>';
		
		$sql = mysql_query("SELECT * FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY current_rank;");

		while ($row = mysql_fetch_array($sql))
				{
					if ($row['title'] == '' || $row['title'] ==  ctype_space($row['title']) 
						|| $row['programme'] == '' || $row['programme'] ==  ctype_space($row['title'])
						|| $row['nihrprojectref'] == '' || $row['nihrprojectref'] ==  ctype_space($row['nihrprojectref'])
						|| $row['startdate'] == '' || $row['startdate'] ==  ctype_space($row['startdate'])
						|| $row['duration'] == '' //|| $row['duration'] ==  ctype_space($row['duration'])
						|| $row['currentstatus'] == '' || $row['currentstatus'] ==  ctype_space($row['currentstatus'])
						|| $row['plannedrecruitmenttotal'] == '' //|| $row['plannedrecruitmenttotal'] ==  ctype_space($row['plannedrecruitmenttotal'])
						|| $row['numberofprojectsites'] == '' //|| $row['numberofprojectsites'] ==  ctype_space($row['numberofprojectsites'])
						|| $row['intmultisite'] == '' || $row['intmultisite'] ==  ctype_space($row['intmultisite'])
						|| $row['expectedinput'] == '' || $row['expectedinput'] ==  ctype_space($row['expectedinput'])
						|| $row['totalcost'] == '' //|| $row['totalcost'] ==  ctype_space($row['totalcost'])
						|| $row['expectedvalue'] == '' //|| $row['expectedvalue'] ==  ctype_space($row['expectedvalue'])
						//|| $row['estimatedstaffcosts'] == '' || $row['estimatedstaffcosts'] ==  ctype_space($row['estimatedstaffcosts'])
						//|| $row['estimatednonstaffcosts'] == '' || $row['estimatednonstaffcosts'] ==  ctype_space($row['estimatednonstaffcosts'])
						//|| $row['nonstaffdesc'] == '' || $row['nonstaffdesc'] ==  ctype_space($row['nonstaffdesc'])
						|| $row['fundingreceivedthisperiod'] == '' || $row['fundingreceivedthisperiod'] ==  ctype_space($row['fundingreceivedthisperiod'])
						//|| $row['iffundingnotreceivedinperiod'] == '' || $row['iffundingnotreceivedinperiod'] ==  ctype_space($row['iffundingnotreceivedinperiod'])
						|| $row['totalfundingreceived'] == '' //|| $row['totalfundingreceived'] ==  ctype_space($row['totalfundingreceived'])
						|| $row['contractextension'] == '' || $row['contractextension'] ==  ctype_space($row['contractextension'])
						//|| $row['whyextensiongranted'] == '' || $row['whyextensiongranted'] ==  ctype_space($row['whyextensiongranted'])
						|| $row['totalvalueofextension'] == '' //|| $row['totalvalueofextension'] ==  ctype_space($row['totalvalueofextension'])
						|| $row['valueofextensiontounit'] == '' //|| $row['valueofextensiontounit'] ==  ctype_space($row['valueofextensiontounit'])
						|| $row['additionalfundingfromcontractextension'] == '' //|| $row['additionalfundingfromcontractextension'] ==  ctype_space($row['additionalfundingfromcontractextension'])
						|| $row['NIHRoffset'] == '' || $row['NIHRoffset'] ==  ctype_space($row['NIHRoffset'])
						
						&& $row['duration'] !== '0'
						&& $row['totalcost'] !== '0'
						&& $row['expectedvalue'] !== '0'
						&& $row['totalfundingreceived'] !== '0'
						&& $row['totalvalueofextension'] !== '0'
						&& $row['valueofextensiontounit'] !== '0'
						&& $row['additionalfundingfromcontractextension'] !== '0'
						
						)
					{
						echo '<a href="section3bedit.php?validate=V&currentselect='. $row['current_rank'] .'&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Current research '. $row['current_rank'] .' has blank fields. This outlines fields must all contain a value. Click to edit.</a><br />';
						$sec3b++;

					}
					else
					{
						echo '<img class="valimg" src="images/tick.png" /> Outline '. $row['current_rank'] .' OK<br />';
						
					}
			
				
				}
				
				
		echo '<h3>3.5 Total income from NIHR-funded projects</h3>';
		$sql = mysql_query("SELECT * FROM ctu_report_section3b_inprog WHERE report_id = '".$_SESSION['report_id']."';");

		while ($row = mysql_fetch_array($sql))
		{		
			if ($row['totalunitincomefromnihrfundedprojects'] == '' || $row['totalunitincomefromnihrfundedprojects'] ==  ctype_space($row['totalunitincomefromnihrfundedprojects'])
			|| $row['totalunitincomefromnihrfundedprojectsmeetoffset'] == '' || $row['totalunitincomefromnihrfundedprojectsmeetoffset'] ==  ctype_space($row['totalunitincomefromnihrfundedprojectsmeetoffset']) && $row['totalunitincomefromnihrfundedprojects'] !== '0' && $row['totalunitincomefromnihrfundedprojectsmeetoffset'] !== '0'
				)
			{
				echo '<a href="section3bedit.php?validate=V&currentselect='. $row['current_rank'] .'&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Total Income has blank fields. These fields must contain a value</a><br />';
				$sec3b++;

			}
			else
			{
				echo '<img class="valimg" src="images/tick.png" /> Totals OK<br />';
			}
		}
		
		echo '<h3>3.6 Discontinued Projects</h3>';
		$sql = mysql_query("SELECT * FROM ctu_report_section3bclosed_inprog WHERE report_id = '".$_SESSION['report_id']."';");

		while ($row = mysql_fetch_array($sql))
		{
			if ($row['title'] == '' || $row['title'] ==  ctype_space($row['title']) 
			|| $row['programme'] == '' || $row['programme'] ==  ctype_space($row['title'])
			|| $row['reference'] == '' || $row['reference'] ==  ctype_space($row['reference'])
			|| $row['reason'] == '' || $row['reason'] ==  ctype_space($row['reason'])
			)
			{
				echo '<a href="section3bedit.php?validate=V&closedselect='. $row['closed_rank'] .'&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Closed project '. $row['closed_rank'] .' has blank fields. This closed projects fields must all contain a value. Click to edit.</a><br />';
				$sec3b++;
			}
			else
			{
				echo '<img class="valimg" src="images/tick.png" /> Outline '. $row['closed_rank'] .' OK<br />';	
			}
		}
		
		echo '<h3>3.7 Any further comments on activity related to NIHR applications or proposals and funded project activities. This includes any EOI, bolt-on or add-on studies.</h3>';
		
		echo '<img class="valimg" src="images/tick.png" />Further comments OK<br />';	
		
		echo '<h3>3.8 NIHR CTU Support Funding for next 12 months.</h3>';
		
		$sql = mysql_query("SELECT * FROM ctu_report_section3b_inprog WHERE report_id = '".$_SESSION['report_id']."';");
		while ($row = mysql_fetch_array($sql))
		{
			if ($row['wouldyoulikechangetofunding'] == '' || $row['wouldyoulikechangetofunding'] ==  ctype_space($row['wouldyoulikechangetofunding'])) 
			{
				echo '<a href="section3b.php?validate=V&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />Would you like to be considered for a change to your funding has not been answered. This fields must contain a value</a><br />';
				$sec3b++;
			}
			
			if ($row['wouldyoulikechangetofunding'] == 'yes')
			{
				if ($row['fundingchangesupport'] == '' || $row['fundingchangesupport'] ==  ctype_space($row['fundingchangesupport'])) 
				{
					echo '<a href="section3b.php?validate=V&report_id='. $_SESSION['report_id'] .'" class="errorlink"><img class="valimg" src="images/cross.png" />The details of the funding request have not been set. This fields must contain a value</a><br />';
					$sec3b++;
				}
				else
				{
					echo '<img class="valimg" src="images/tick.png" />Funding for next 12 months OK<br />';	
				}
			}
		
		}
	
		if ($sec3b <= 0)
		{
		/*
					mysql_query ("DROP TABLE ctu_report_section3b;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3b LIKE ctu_report_section3b_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3b SELECT * FROM ctu_report_section3b_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
					mysql_query ("DROP TABLE ctu_report_section3bclosed;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3bclosed LIKE ctu_report_section3bclosed_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3bclosed SELECT * FROM ctu_report_section3bclosed_inprog;;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					
				
					mysql_query ("DROP TABLE ctu_report_section3bcurrent;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query ("CREATE TABLE ctu_report_section3bcurrent LIKE ctu_report_section3bcurrent_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
					mysql_query (" INSERT INTO ctu_report_section3bcurrent SELECT * FROM ctu_report_section3bcurrent_inprog;") or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		*/
		
			//Update section validity
			$sectionsql = "UPDATE ctu_activityreporting SET section3bvalid = 'yes', section3blastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
			$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
			header("Location: ".$url."reporthome.php");
		}
		

	
	}
	else if ($section == '4')
	{
		
		$sectionsql = "UPDATE ctu_activityreporting SET section4valid = 'yes', section4lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
		$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		header("Location: ".$url."reporthome.php");
	
	
	
	}









?>	





	
	
</form>


















</body>
</html>	