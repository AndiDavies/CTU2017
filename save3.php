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

if (isset($_POST['req_type']))
{
	
	if ($_POST['req_type'] == 'activity')
	{
	//SECTION 3.1
	echo 'Activity';
	//Check if the record already exists
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section3_inprog WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']>0)
	{
		 mysql_query("DELETE FROM ctu_report_section3_inprog WHERE report_id = '".$_SESSION['report_id']."'");
	}
		$insertmainsql = "INSERT INTO ctu_report_section3_inprog
				(	report_id,
					previousoutlineproposalsshortlisted,
					previousoutlineproposalsrejected,
					
					currentoutlineproposalssubmitted,
					currentoutlineproposalsshortlisted,
					currentoutlineproposalsrejected,
					currentoutlineproposalsdecisionpending,
					
					previousfullproposalsfunded,
					previousfullproposalsrejected,
					
					currentfullproposalssubmitted,
					currentfullproposalsfunded,
					currentfullproposalsfundwithchange,
					currentfullproposalsdeferred,
					currentfullproposalsresubmitting,
					currentfullproposalstransferred,
					currentfullproposalsrejected,
					currentfullproposalsdecisionpending,

					nihrprojectsstartedduringperiod,
					totalcurrentnihrprojects
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['previousoutlineproposalsshortlisted'])."',
					'".mysql_real_escape_string($_POST['previousoutlineproposalsrejected'])."',
					
					'".mysql_real_escape_string($_POST['currentoutlineproposalssubmitted'])."',
					'".mysql_real_escape_string($_POST['currentoutlineproposalsshortlisted'])."',
					'".mysql_real_escape_string($_POST['currentoutlineproposalsrejected'])."',
					'".mysql_real_escape_string($_POST['currentoutlineproposalsdecisionpending'])."',
					
					'".mysql_real_escape_string($_POST['previousfullproposalsfunded'])."',
					'".mysql_real_escape_string($_POST['previousfullproposalsrejected'])."',
					
					'".mysql_real_escape_string($_POST['currentfullproposalssubmitted'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsfunded'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsfundwithchange'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsdeferred'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsresubmitting'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalstransferred'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsrejected'])."',
					'".mysql_real_escape_string($_POST['currentfullproposalsdecisionpending'])."',
						
					'".mysql_real_escape_string($_POST['nihrprojectsstartedduringperiod'])."',
					'".mysql_real_escape_string($_POST['totalcurrentnihrprojects'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());


			
	}
	
	if ($_POST['req_type'] == 'project')
	{
		echo 'project UPDATE <br>';
		
		//do the sorting here
		$title						= mysql_real_escape_string($_POST['fullprojecttitle']);
		$nihrprojectref				= mysql_real_escape_string($_POST['fullprojectnihrprojectref']) ;
		$programme					= mysql_real_escape_string($_POST['fullprojectprogramme']);
		$datesubmitted				= (strlen($_POST['fullprojectdatesubmitted'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullprojectdatesubmitted'])))."'" : "NULL");
		$duration					= mysql_real_escape_string($_POST['fullprojectduration']);
		$plannedrecruitmenttotal	= mysql_real_escape_string($_POST['fullprojectplannedrecruitmenttotal']);
		$numberofprojectsites		= mysql_real_escape_string($_POST['fullprojectnumberofprojectsites']);
		$intmultisite				= (isset($_POST['fullprojectintmultisite'])&&$_POST['fullprojectintmultisite']=='on' ? "'yes'" : "'no'");
		$expectedinput				= mysql_real_escape_string($_POST['fullprojectexpectedinput']);
		$currentstatus				= mysql_real_escape_string($_POST['fullprojectcurrentstatus']);
		$estimatedoractualstartdate	= (strlen($_POST['fullprojectestimatedoractualstartdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullprojectestimatedoractualstartdate'])))."'" : "NULL");
		$isstartdateestimated		= (isset($_POST['fullprojectisstartdateestimated'])&&$_POST['fullprojectisstartdateestimated']=='on' ? "'yes'" : "'no'");
		$totalcost					= (strlen($_POST['fullprojecttotalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojecttotalcost'])))."'" : "NULL");
		$expectedvalue				= (strlen($_POST['fullprojectexpectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectexpectedvalue'])))."'" : "NULL");
		/*$estimatedstaffcosts		= (strlen($_POST['fullprojectestimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectestimatedstaffcosts'])))."'" : "NULL");
		$estimatednonstaffcosts		= (strlen($_POST['fullprojectestimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectestimatednonstaffcosts'])))."'" : "NULL");
		$nonstaffdesc				= mysql_real_escape_string($_POST['fullprojectnonstaffdesc']);*/

		
		$insertoutlinequery = 'UPDATE ctu_report_section3full_inprog 
							SET title="'.$title.'", 
							nihrprojectref="'.$nihrprojectref.'",
							programme="'.$programme.'", 
							datesubmitted='.$datesubmitted.', 
							duration="'.$duration.'", 
							plannedrecruitmenttotal="'.$plannedrecruitmenttotal.'", 
							numberofprojectsites="'.$numberofprojectsites.'", 
							intmultisite='.$intmultisite.', 
							expectedinput="'.$expectedinput.'", 
							currentstatus="'.$currentstatus.'", 
							estimatedoractualstartdate='.$estimatedoractualstartdate.', 
							isstartdateestimated='.$isstartdateestimated.', 
							totalcost='.$totalcost.', 
							expectedvalue='.$expectedvalue.'
			
							WHERE report_id ="'. $_SESSION['report_id'] .'" 
							AND full_rank ="'. $_POST['projectselect'] .'";
							';
							
							

		
		$updateqry = mysql_query($insertoutlinequery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinequery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		
		header("Location: ".$url."section3.php");
	}
	
	if ($_POST['req_type'] == 'outline')
	{
		echo 'outline UPDATE';
		//INSERT INTO
		
		$insertoutlinequery = 'UPDATE ctu_report_section3outline_inprog 
							SET title="'. mysql_real_escape_string($_POST['projecttitle']) .'", 
							programme="'. mysql_real_escape_string($_POST['projectprogramme']) .'", 
							reference="'. mysql_real_escape_string($_POST['projectreference']) .'", 
							submitdate='. (strlen($_POST['projectdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['projectdate'])))."'" : "NULL") .', 
							staffinput="'. mysql_real_escape_string($_POST['projectstaffinput']) .'", 
							expectedvalue='. (strlen($_POST['projectexpectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['projectexpectedvalue'])))."'" : "NULL") .', 
							status="'. mysql_real_escape_string($_POST['projectstatus']) .'" 
							WHERE report_id ="'. $_SESSION['report_id'] .'" 
							AND outline_rank ="'. $_POST['outlineselect'] .'";
							';

		
		$updateqry = mysql_query($insertoutlinequery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinequery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		header("Location: ".$url."section3.php");
		
		
	}
	
	
	//Add New
	if (isset($_POST['new_project']))
	{
		$rnk = 0;
		echo '<br>NEW project<br>';
		//select here to find highest full _rank number.
		$stuff = mysql_query ('SELECT MAX(full_rank) FROM ctu_report_section3full_inprog WHERE report_id ="'. $_SESSION['report_id'] .'"');
		while($row = mysql_fetch_array($stuff))
			{
				echo 'highest full_rank '.$row['MAX(full_rank)'];
				$rnk = $row['MAX(full_rank)'];
			}
		
	
		for ($i = 0; isset($_POST['new_project'][$i]) ; $i++)
		{
			echo '<br>loop in';
			$rnk = $rnk+1;
			echo '<br>RANK: '. $rnk.'<br>';
			$title[$i]						= mysql_real_escape_string($_POST['fullprojecttitle'][$i]);
			$nihrprojectref[$i]				= mysql_real_escape_string($_POST['fullprojectnihrprojectref'][$i]) ;
			$programme[$i]					= mysql_real_escape_string($_POST['fullprojectprogramme'][$i]);
			$datesubmitted[$i]				= (strlen($_POST['fullprojectdatesubmitted'][$i])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullprojectdatesubmitted'][$i])))."'" : "NULL");
			$duration[$i]					= mysql_real_escape_string($_POST['fullprojectduration'][$i]);
			$plannedrecruitmenttotal[$i]	= mysql_real_escape_string($_POST['fullprojectplannedrecruitmenttotal'][$i]);
			$numberofprojectsites[$i]		= mysql_real_escape_string($_POST['fullprojectnumberofprojectsites'][$i]);
			$intmultisite[$i]				= (isset($_POST['fullprojectintmultisite'][$i])&&$_POST['fullprojectintmultisite'][$i]=='on' ? "'yes'" : "'no'");
			$expectedinput[$i]				= mysql_real_escape_string($_POST['fullprojectexpectedinput'][$i]);
			$currentstatus[$i]				= mysql_real_escape_string($_POST['fullprojectcurrentstatus'][$i]);
			$estimatedoractualstartdate[$i]	= (strlen($_POST['fullprojectestimatedoractualstartdate'][$i])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullprojectestimatedoractualstartdate'][$i])))."'" : "NULL");
			$isstartdateestimated[$i]		= (isset($_POST['fullprojectisstartdateestimated'][$i])&&$_POST['fullprojectisstartdateestimated'][$i]=='on' ? "'yes'" : "'no'");
			$totalcost[$i]					= (strlen($_POST['fullprojecttotalcost'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojecttotalcost'][$i])))."'" : "NULL");
			$expectedvalue[$i]				= (strlen($_POST['fullprojectexpectedvalue'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectexpectedvalue'][$i])))."'" : "NULL");
			/*$estimatedstaffcosts[$i]		= (strlen($_POST['fullprojectestimatedstaffcosts'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectestimatedstaffcosts'][$i])))."'" : "NULL");
			$estimatednonstaffcosts[$i]		= (strlen($_POST['fullprojectestimatednonstaffcosts'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullprojectestimatednonstaffcosts'][$i])))."'" : "NULL");
			$nonstaffdesc[$i]				= mysql_real_escape_string($_POST['fullprojectnonstaffdesc'][$i]);*/
			
			
			
			$newprojectquery = 'INSERT INTO ctu_report_section3full_inprog 
								(	
								report_id,
								full_rank,
								title,
								nihrprojectref,
								programme,
								datesubmitted,
								duration,
								plannedrecruitmenttotal,
								numberofprojectsites,
								intmultisite,
								expectedinput,
								currentstatus,
								estimatedoractualstartdate,
								isstartdateestimated,
								totalcost,
								expectedvalue
								
							) 
							VALUES 
							(
							"'. mysql_real_escape_string($_SESSION['report_id']).'",
							"'. $rnk .'",
							"'. $title[$i] .'", 
							"'. $nihrprojectref[$i]	 .'",
							"'. $programme[$i] .'", 
							'. $datesubmitted[$i].', 
							"'. $duration[$i].'", 
							"'. $plannedrecruitmenttotal[$i] .'", 
							"'. $numberofprojectsites[$i] .'", 
							'. $intmultisite[$i].', 
							"'. $expectedinput[$i].'", 
							"'. $currentstatus[$i].'",
							'. $estimatedoractualstartdate[$i] .', 
							'. $isstartdateestimated[$i]	.', 
							'. $totalcost[$i].', 
							'. $expectedvalue[$i].'
							)
						
							';
			echo '<br>'.$i.' new records';
			echo '<br>next rank '.$rnk;
			$insertqry = mysql_query($newprojectquery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$newprojectquery."<br />\nError: (".mysql_errno().") ".mysql_error());
		};
		
		
		
		
	}
	if (isset($_POST['new_outline']))
	{
		echo '<br>NEW outline';
		$rnk = 0;
		//INSERT INTO
		
		$stuff = mysql_query ('SELECT MAX(outline_rank) FROM ctu_report_section3outline_inprog WHERE report_id ="'. $_SESSION['report_id'] .'"');
		while($row = mysql_fetch_array($stuff))
			{
				echo '<br>highest outline_rank '.$row['MAX(outline_rank)'].'<br>';
				$rnk = $row['MAX(outline_rank)'];
			}
		
	
		for ($i = 0; isset($_POST['new_outline'][$i]) ; $i++)
		{
		
			$rnk = $rnk+1;
			
			$insertoutlinequery = 'INSERT INTO ctu_report_section3outline_inprog
								(
								report_id,
								outline_rank,
								title,
								programme,
								reference,
								submitdate,
								staffinput,
								expectedvalue,
								status
							)
							VALUES
							(
							"'. mysql_real_escape_string($_SESSION['report_id']).'",
							"'. $rnk .'",
							"'. mysql_real_escape_string($_POST['projecttitle'][$i]) .'", 
							"'. mysql_real_escape_string($_POST['projectprogramme'][$i]) .'", 
							"'. mysql_real_escape_string($_POST['projectreference'][$i]) .'", 
							'. (strlen($_POST['projectdate'][$i])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['projectdate'][$i])))."'" : "NULL") .', 
							"'. mysql_real_escape_string($_POST['projectstaffinput'][$i]) .'", 
							'. (strlen($_POST['projectexpectedvalue'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['projectexpectedvalue'][$i])))."'" : "NULL") .', 
							"'. mysql_real_escape_string($_POST['projectstatus'][$i]) .'" 
							)	
							';
			echo 'rank: '.$rnk;
			echo '<br>for loop: '.$i;
		//echo $_SESSION['projecttitle'][$i];
			$insertqry = mysql_query($insertoutlinequery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinequery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		}
		//header("Location: ".$url."section3.php");
		

	}

	$sectionsql = "UPDATE ctu_activityreporting SET section3valid = 'not yet validated', section3lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	
	
	if (isset($_POST['vred']))
	{
		$vred = mysql_real_escape_string($_POST['vred']);
		header("Location: ".$url."validate.php?". $vred  ."");
	}
	else
	{
		header("Location: ".$url."section3.php");
	}
}













?>