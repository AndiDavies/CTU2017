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

	if (isset($_POST['req_type']) && $_POST['req_type'] == 'activity2')
	{
		echo 'current main section 3 UPDATE <br>';
		//echo mysql_real_escape_string($_POST['totalunitincomefromnihrfundedprojects']).'<br>';		
		//echo mysql_real_escape_string($_POST['totalunitincomefromnihrfundedprojectsmeetoffset']).'<br>';			
				
				
				
				
		$furthercommentsonnihrapplications					= mysql_real_escape_string($_POST['furthercommentsonnihrapplications']);
		//$IPdetails											= mysql_real_escape_string($_POST['IPdetails']);
		$wouldyoulikechangetofunding						= mysql_real_escape_string($_POST['wouldyoulikechangetofunding']);
		$fundingchangesupport								= mysql_real_escape_string($_POST['fundingchangesupport']);
		
		$totalunitincomefromnihrfundedprojects				= (strlen($_POST['totalunitincomefromnihrfundedprojects'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['totalunitincomefromnihrfundedprojects'])))."'" : "NULL") ;
		$totalunitincomefromnihrfundedprojectsmeetoffset	= (strlen($_POST['totalunitincomefromnihrfundedprojectsmeetoffset'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['totalunitincomefromnihrfundedprojectsmeetoffset'])))."'" : "NULL") ;
		
		/*
		if ($totalunitincomefromnihrfundedprojectsmeetoffset == 'NULL')
		{
			$totalunitincomefromnihrfundedprojectsmeetoffset = 0.00;
			$totalunitincomefromnihrfundedprojects  = 0.00;
		}
		*/
		
		$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section3b_inprog WHERE report_id = '".$_SESSION['report_id']."'";
		$checkqry = mysql_query($checksql);
		$check = mysql_fetch_array($checkqry);
		if ($check['count']>0)
		{
			//UPDATE
			 $insertcurrentquery = 'UPDATE ctu_report_section3b_inprog 
								SET report_id="'.$_SESSION['report_id'].'",
								furthercommentsonnihrapplications="'. $furthercommentsonnihrapplications .'",
								wouldyoulikechangetofunding="'. $wouldyoulikechangetofunding .'",
								fundingchangesupport="'. $fundingchangesupport	.'",
								totalunitincomefromnihrfundedprojects='. $totalunitincomefromnihrfundedprojects .',
								totalunitincomefromnihrfundedprojectsmeetoffset='. $totalunitincomefromnihrfundedprojectsmeetoffset .'
								WHERE report_id ="'. $_SESSION['report_id'] .'";
								';
			$updateqry = mysql_query($insertcurrentquery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertcurrentquery."<br />\nError: (".mysql_errno().") ".mysql_error());
		}
		else
		{
			$insertmainsql = "INSERT INTO ctu_report_section3b_inprog
				(	report_id,
					furthercommentsonnihrapplications,
					wouldyoulikechangetofunding,
					fundingchangesupport,
					totalunitincomefromnihrfundedprojects,
					totalunitincomefromnihrfundedprojectsmeetoffset
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".$furthercommentsonnihrapplications."',
					'".$wouldyoulikechangetofunding."',
					'".$fundingchangesupport."',
					".$totalunitincomefromnihrfundedprojects.",
					".$totalunitincomefromnihrfundedprojectsmeetoffset."
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
			
			
			
			
			
		}
		
	
	}
	
	
	if (isset($_POST['req_type']) && $_POST['req_type'] == 'current')
	{
		echo 'current UPDATE <br>';
		
		//do the sorting here
		$title						= mysql_real_escape_string($_POST['currentprojecttitle']);
		$programme					= mysql_real_escape_string($_POST['currentprojectprogramme']);
		$nihrprojectref				= mysql_real_escape_string($_POST['currentprojectnihrprojectref']) ;
		$startdate					= (strlen($_POST['currentprojectstartdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['currentprojectstartdate'])))."'" : "NULL");
		$duration					= mysql_real_escape_string($_POST['currentprojectduration']);
		$currentstatus				= mysql_real_escape_string($_POST['currentprojectcurrentstatus']);
		$plannedrecruitmenttotal	= mysql_real_escape_string($_POST['currentprojectplannedrecruitmenttotal']);
		$numberofprojectsites		= mysql_real_escape_string($_POST['currentprojectnumberofprojectsites']);
		$intmultisite				= (isset($_POST['currentprojectintmultisite'])&&$_POST['currentprojectintmultisite']=='on' ? "'yes'" : "'no'");
		$expectedinput				= mysql_real_escape_string($_POST['currentprojectexpectedinput']);
		$totalcost					= (strlen($_POST['currentprojecttotalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalcost'])))."'" : "NULL") ;
		$expectedvalue				= (strlen($_POST['currentprojectexpectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectexpectedvalue'])))."'" : "NULL") ;
		/*$estimatedstaffcosts		= (strlen($_POST['currentprojectestimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectestimatedstaffcosts'])))."'" : "NULL") ;
		$estimatednonstaffcosts		= (strlen($_POST['currentprojectestimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectestimatednonstaffcosts'])))."'" : "NULL") ;
		$nonstaffdesc				= mysql_real_escape_string($_POST['currentprojectnonstaffdesc']);
		*/
		
		//$fundingreceivedthisperiod	= (isset($_POST['currentprojectfundingreceivedthisperiod'])&& $_POST['currentprojectfundingreceivedthisperiod']=='on' ? "'yes'" : "'no'");
		$fundingreceivedthisperiod	= mysql_real_escape_string($_POST['currentprojectfundingreceivedthisperiod']);
		
		$iffundingnotreceivedinperiod = mysql_real_escape_string($_POST['currentprojectiffundingnotreceivedinperiod']);
		$totalfundingreceived		= (strlen($_POST['currentprojecttotalfundingreceived'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalfundingreceived'])))."'" : "NULL") ;
		
		
		
		//$contractextension			= (isset($_POST['currentprojectcontractextension'])&& $_POST['currentprojectcontractextension']=='on' ? "'yes'" : "'no'");
		$contractextension	= mysql_real_escape_string($_POST['currentprojectcontractextension']);
		
		
		$whyextensiongranted		= mysql_real_escape_string($_POST['currentprojectwhyextensiongranted']);
		$totalvalueofextension		= (strlen($_POST['currentprojecttotalvalueofextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalvalueofextension'])))."'" : "NULL") ;
		$valueofextensiontounit		= (strlen($_POST['currentprojectvalueofextensiontounit'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectvalueofextensiontounit'])))."'" : "NULL") ;
		$additionalfundingfromcontractextension = (strlen($_POST['currentprojectadditionalfundingfromcontractextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectadditionalfundingfromcontractextension'])))."'" : "NULL") ;
		$NIHRoffset					= (isset($_POST['currentprojectNIHRoffset'])&&$_POST['currentprojectNIHRoffset']=='on' ? "'yes'" : "'no'");
		
		
		$insertcurrentquery = 'UPDATE ctu_report_section3bcurrent_inprog 
							SET title="'.$title.'",
							programme="'.$programme.'", 
							nihrprojectref="'.$nihrprojectref.'",
							startdate='.$startdate.', 
							duration="'.$duration.'", 
							currentstatus="'.$currentstatus.'", 
							plannedrecruitmenttotal="'.$plannedrecruitmenttotal.'", 
							numberofprojectsites="'.$numberofprojectsites.'", 
							intmultisite='.$intmultisite.', 
							expectedinput="'.$expectedinput.'", 
							totalcost='.$totalcost.', 			
							expectedvalue='.$expectedvalue.', 
							
							fundingreceivedthisperiod="'.$fundingreceivedthisperiod.'", 
							iffundingnotreceivedinperiod="'.$iffundingnotreceivedinperiod.'", 
							totalfundingreceived='.$totalfundingreceived.', 			
							contractextension="'.$contractextension.'", 
							whyextensiongranted="'.$whyextensiongranted.'", 
							totalvalueofextension='.$totalvalueofextension.', 
							valueofextensiontounit='.$valueofextensiontounit.',
							additionalfundingfromcontractextension='.$additionalfundingfromcontractextension.',
							NIHRoffset='.$NIHRoffset.'
							WHERE report_id ="'. $_SESSION['report_id'] .'" 
							AND current_rank ="'. $_POST['currentselect'] .'";
							';
							
							

		
		$updateqry = mysql_query($insertcurrentquery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertcurrentquery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		
		header("Location: ".$url."section3b.php");
	}
	
	if (isset($_POST['req_type']) && $_POST['req_type'] == 'closed')
	{
		echo 'closed UPDATE';
		//INSERT INTO
		
		$insertoutlinequery = 'UPDATE ctu_report_section3bclosed_inprog 
							SET title="'. mysql_real_escape_string($_POST['closedtitle']) .'", 
							programme="'. mysql_real_escape_string($_POST['closedprogramme']) .'", 
							reference="'. mysql_real_escape_string($_POST['closedreference']) .'", 
							reason="'. mysql_real_escape_string($_POST['closedreason']) .'"
							WHERE report_id ="'. $_SESSION['report_id'] .'" 
							AND closed_rank ="'. $_POST['closedselect'] .'";
							';

		
		$updateqry = mysql_query($insertoutlinequery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinequery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		header("Location: ".$url."section3b.php");
		
		
	}
	
	
	//Add New
	if (isset($_POST['new_current']))
	{
		
		$rnk = 0;
		echo '<br>NEW current<br>';
		//select here to find highest current _rank number.
		$stuff = mysql_query ('SELECT MAX(current_rank) FROM ctu_report_section3bcurrent_inprog WHERE report_id ="'. $_SESSION['report_id'] .'"');
		while($row = mysql_fetch_array($stuff))
			{
				echo 'highest current_rank '.$row['MAX(current_rank)'];
				$rnk = $row['MAX(current_rank)'];
			}
		
	
		for ($i = 0; isset($_POST['new_current'][$i]) ; $i++)
		{
			$rnk = $rnk+1;
			
			$title[$i]						= mysql_real_escape_string($_POST['currentprojecttitle'][$i]);
			$programme[$i]					= mysql_real_escape_string($_POST['currentprojectprogramme'][$i]);
			$nihrprojectref[$i]				= mysql_real_escape_string($_POST['currentprojectnihrprojectref'][$i]) ;
			$startdate[$i]					= (strlen($_POST['currentprojectstartdate'][$i])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['currentprojectstartdate'][$i])))."'" : "NULL");
			$duration[$i]					= mysql_real_escape_string($_POST['currentprojectduration'][$i]);
			$currentstatus[$i]				= mysql_real_escape_string($_POST['currentprojectcurrentstatus'][$i]);
			$plannedrecruitmenttotal[$i]	= mysql_real_escape_string($_POST['currentprojectplannedrecruitmenttotal'][$i]);
			$numberofprojectsites[$i]		= mysql_real_escape_string($_POST['currentprojectnumberofprojectsites'][$i]);
			$intmultisite[$i]				= (isset($_POST['currentprojectintmultisite'][$i])&&$_POST['currentprojectintmultisite'][$i]=='on' ? "'yes'" : "'no'");
			$expectedinput[$i]				= mysql_real_escape_string($_POST['currentprojectexpectedinput'][$i]);
			$totalcost[$i]					= (strlen($_POST['currentprojecttotalcost'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalcost'][$i])))."'" : "NULL") ;
			$expectedvalue[$i]				= (strlen($_POST['currentprojectexpectedvalue'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectexpectedvalue'][$i])))."'" : "NULL") ;
			/*$estimatedstaffcosts[$i]		= (strlen($_POST['currentprojectestimatedstaffcosts'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectestimatedstaffcosts'][$i])))."'" : "NULL") ;
			$estimatednonstaffcosts[$i]		= (strlen($_POST['currentprojectestimatednonstaffcosts'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectestimatednonstaffcosts'][$i])))."'" : "NULL") ;
			$nonstaffdesc[$i]				= mysql_real_escape_string($_POST['currentprojectnonstaffdesc'][$i]);
			*/
			//$fundingreceivedthisperiod[$i]	= (isset($_POST['currentprojectfundingreceivedthisperiod'][$i])&&$_POST['currentprojectfundingreceivedthisperiod'][$i]=='on' ? "'yes'" : "'no'");
			
			$fundingreceivedthisperiod[$i]	=  mysql_real_escape_string($_POST['currentprojectfundingreceivedthisperiod'][$i]);
			
			$iffundingnotreceivedinperiod[$i] = mysql_real_escape_string($_POST['currentprojectiffundingnotreceivedinperiod'][$i]);
			$totalfundingreceived[$i]		= (strlen($_POST['currentprojecttotalfundingreceived'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalfundingreceived'][$i])))."'" : "NULL") ;
			//$contractextension[$i]			= (isset($_POST['currentprojectcontractextension'][$i])&&$_POST['currentprojectcontractextension'][$i]=='on' ? "'yes'" : "'no'");
			
			$contractextension[$i]			=  mysql_real_escape_string($_POST['currentprojectcontractextension'][$i]);
			
			$whyextensiongranted[$i]		= mysql_real_escape_string($_POST['currentprojectwhyextensiongranted'][$i]);
			$totalvalueofextension[$i]		= (strlen($_POST['currentprojecttotalvalueofextension'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojecttotalvalueofextension'][$i])))."'" : "NULL") ;
			$valueofextensiontounit[$i]		= (strlen($_POST['currentprojectvalueofextensiontounit'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectvalueofextensiontounit'][$i])))."'" : "NULL") ;
			$additionalfundingfromcontractextension[$i] = (strlen($_POST['currentprojectadditionalfundingfromcontractextension'][$i])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentprojectadditionalfundingfromcontractextension'][$i])))."'" : "NULL") ;
			$NIHRoffset[$i]					= (isset($_POST['currentprojectNIHRoffset'][$i])&&$_POST['currentprojectNIHRoffset'][$i]=='on' ? "'yes'" : "'no'");
			
							
			
			
			
			$newcurrentquery = 'INSERT INTO ctu_report_section3bcurrent_inprog 
								(	
								report_id,
								current_rank,
								title,
								programme, 
								nihrprojectref,
								startdate, 
								duration, 
								currentstatus, 
								plannedrecruitmenttotal, 
								numberofprojectsites, 
								intmultisite, 
								expectedinput, 
								totalcost, 			
								expectedvalue, 
								
								fundingreceivedthisperiod, 
								iffundingnotreceivedinperiod, 
								totalfundingreceived, 			
								contractextension, 
								whyextensiongranted, 
								totalvalueofextension, 
								valueofextensiontounit,
								additionalfundingfromcontractextension,
								NIHRoffset
							) 
							VALUES 
							(
							"'. mysql_real_escape_string($_SESSION['report_id']).'",
							"'. $rnk .'",
							"'. $title[$i] .'", 
							"'. $programme[$i] .'",
							"'. $nihrprojectref[$i] .'", 
							'. $startdate[$i] .', 
							"'. $duration[$i] .'", 
							"'. $currentstatus[$i] .'", 
							"'. $plannedrecruitmenttotal[$i] .'", 
							"'. $numberofprojectsites[$i] .'", 
							'. $intmultisite[$i] .', 
							"'. $expectedinput[$i] .'",
							'. $totalcost[$i] .', 
							'. $expectedvalue[$i] .', 
							
							"'. $fundingreceivedthisperiod[$i] .'", 
							"'. $iffundingnotreceivedinperiod[$i] .'",
							'. $totalfundingreceived[$i] .', 
							"'. $contractextension[$i] .'", 
							"'. $whyextensiongranted[$i] .'", 
							'. $totalvalueofextension[$i] .',
							'. $valueofextensiontounit[$i] .', 
							'. $additionalfundingfromcontractextension[$i] .', 
							'. $NIHRoffset[$i] .'
							)
						
							';
			echo '<br>'.$i.' new records';
			echo '<br>next rank '.$rnk;
			$insertqry = mysql_query($newcurrentquery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$newcurrentquery."<br />\nError: (".mysql_errno().") ".mysql_error());
		};
		
		
		
		
	}
	
	if (isset($_POST['new_closed']))
	{
		echo '<br>NEW closed';
		$rnk = 0;
		//INSERT INTO
		
		$stuff = mysql_query ('SELECT MAX(closed_rank) FROM ctu_report_section3bclosed_inprog WHERE report_id ="'. $_SESSION['report_id'] .'"');
		while($row = mysql_fetch_array($stuff))
			{
				echo '<br>highest closed_rank '.$row['MAX(closed_rank)'].'<br>';
				$rnk = $row['MAX(closed_rank)'];
			}
		
	
		for ($i = 0; isset($_POST['new_closed'][$i]) ; $i++)
		{
		
		
			$rnk = $rnk+1;
			
		
			$insertoutlinequery = 'INSERT INTO ctu_report_section3bclosed_inprog
								(
								report_id,
								closed_rank,
								title,
								programme,
								reference,
								reason
								)
								VALUES
								(
								"'. mysql_real_escape_string($_SESSION['report_id']).'",
								"'. $rnk .'",
								"'. mysql_real_escape_string($_POST['closedtitle'][$i]) .'", 
								"'. mysql_real_escape_string($_POST['closedprogramme'][$i]) .'", 
								"'. mysql_real_escape_string($_POST['closedreference'][$i]) .'",
								"'. mysql_real_escape_string($_POST['closedreason'][$i]) .'"
								)
								';
							
			echo 'rank: '.$rnk;
			echo '<br>for loop: '.$i;
		//echo $_SESSION['projecttitle'][$i];
			$insertqry = mysql_query($insertoutlinequery) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinequery."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		}
		//header("Location: ".$url."section3.php");
		

	}
	
	$sectionsql = "UPDATE ctu_activityreporting SET section3bvalid = 'not yet validated', section3blastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	
	if (isset($_POST['vred']))
	{
		$vred = mysql_real_escape_string($_POST['vred']);
		header("Location: ".$url."validate.php?". $vred  ."");
	}
	else
	{
		header("Location: ".$url."section3b.php");
	}
	
	
	//take the user to section3b after all done
	










?>