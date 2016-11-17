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
$sqlhistory = "INSERT INTO ctu_report_section3b_history SELECT ctu_report_section3b.*, '".$time."' FROM ctu_report_section3b WHERE report_id = '".$_SESSION['report_id']."'";
$qryhistory  = mysql_query($sqlhistory);
echo mysql_error();
$sql1history = "INSERT INTO ctu_report_section3bcurrent_history SELECT ctu_report_section3bcurrent.*, '".$time."' FROM ctu_report_section3bcurrent WHERE report_id = '".$_SESSION['report_id']."'";
$qry1history  = mysql_query($sql1history);
echo mysql_error();
$sql2history = "INSERT INTO ctu_report_section3bclosed_history SELECT ctu_report_section3bclosed.*, '".$time."' FROM ctu_report_section3bclosed WHERE report_id = '".$_SESSION['report_id']."'";
$qry2history  = mysql_query($sql2history);
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

//how many project items were passed?
//(If they don't have a title - they don't count!)
$countcurrentprojects=0;
$countclosed=0;

while (isset($_POST['currentproject'.($countcurrentprojects+1).'title']) && strlen($_POST['currentproject'.($countcurrentprojects+1).'title'])>0) {
	$countcurrentprojects++;
}
while (isset($_POST['closed'.($countclosed+1).'title']) && strlen($_POST['closed'.($countclosed+1).'title'])>0) { 
	$countclosed++;
}

//echo "Current Projects: ".$countcurrentprojects."<br />";
//echo "Closed Projects: ".$countclosed."<br />";

//find sum of A'
$totalunitincomefromnihrfundedprojects =0;
$totalunitincomefromnihrfundedprojectsmeetoffset=0;
for ($i=1;$i<=$countcurrentprojects;$i++)
{
	$totalunitincomefromnihrfundedprojects=$totalunitincomefromnihrfundedprojects+floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalfundingreceived']));
	$totalunitincomefromnihrfundedprojects=$totalunitincomefromnihrfundedprojects+floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'additionalfundingfromcontractextension']));
	if (isset($_POST['currentproject'.$i.'NIHRoffset']))
	 { 
	 	$totalunitincomefromnihrfundedprojectsmeetoffset=$totalunitincomefromnihrfundedprojectsmeetoffset+floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalfundingreceived'])); 
	}
}
//echo "total income from nihr funded projects = ".$totalunitincomefromnihrfundedprojects."<br>";
//echo "total income from nihr offset applicable projects  = ".$totalunitincomefromnihrfundedprojectsmeetoffset."<br>";


//If no errors, commit to database:
if ($valid==1)
{
	//Check if row exists already:
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section3b WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']==0)
	{
		//Row does not exist - 3 tables to insert into
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section3b 
				(	report_id,
					furthercommentsonnihrapplications ,
					IPdetails ,
					wouldyoulikechangetofunding ,
					fundingchangesupport ,
					totalunitincomefromnihrfundedprojects,
					totalunitincomefromnihrfundedprojectsmeetoffset
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['furthercommentsonnihrapplications'])."',
					'".mysql_real_escape_string($_POST['IPdetails'])."',
					'".mysql_real_escape_string($_POST['wouldyoulikechangetofunding'])."',
					'".mysql_real_escape_string($_POST['fundingchangesupport'])."',
					'".mysql_real_escape_string($totalunitincomefromnihrfundedprojects)."',
					'".mysql_real_escape_string($totalunitincomefromnihrfundedprojectsmeetoffset)."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
				
//		loop through current projects
		for ($i=1;$i<=$countcurrentprojects;$i++)
		{
		$insertcurrentsql = "INSERT INTO ctu_report_section3bcurrent
						(	report_id,
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
							estimatedstaffcosts,
							estimatednonstaffcosts,
							nonstaffdesc,
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
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'title'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'programme'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'nihrprojectref'])."',
							".(strlen($_POST['currentproject'.$i.'startdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['currentproject'.$i.'startdate'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'duration'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'currentstatus'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'plannedrecruitmenttotal'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'numberofprojectsites'])."',
							'".(isset($_POST['currentproject'.$i.'intmultisite'])&&$_POST['currentproject'.$i.'intmultisite']=='on' ? "yes" : "no")."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'expectedinput'])."',
							".(strlen($_POST['currentproject'.$i.'totalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalcost'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'expectedvalue'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'estimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'estimatedstaffcosts'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'estimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'estimatednonstaffcosts'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'nonstaffdesc'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'fundingreceivedthisperiod'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'iffundingnotreceivedinperiod'])."',
							".(strlen($_POST['currentproject'.$i.'totalfundingreceived'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalfundingreceived'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'contractextension'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'whyextensiongranted'])."',
							".(strlen($_POST['currentproject'.$i.'totalvalueofextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalvalueofextension'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'valueofextensiontounit'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'valueofextensiontounit'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'additionalfundingfromcontractextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'additionalfundingfromcontractextension'])))."'" : "NULL").",
							'".(isset($_POST['currentproject'.$i.'NIHRoffset'])&&$_POST['currentproject'.$i.'NIHRoffset']=='on' ? "yes" : "no")."'
						)";
		//echo "<pre>".$insertcurrentsql."</pre>";
		$insertcurrentqry = mysql_query($insertcurrentsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertcurrentsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		
		}
//
//
		//loop through full closed projects
		for ($x=1;$x<=$countclosed;$x++)
		{
		$insertclosedsql = "INSERT INTO ctu_report_section3bclosed
						(	report_id,	
							closed_rank,
							title,
							programme,
							reference,
							reason
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['closed'.$x.'title'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'programme'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'reference'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'reason'])."'
						)";
		//echo "<pre>".$insertclosedsql."<pre>";
		$insertclosedqry = mysql_query($insertclosedsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertclosedsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	
	}
//		
//		
	}
	else
	{
		//echo "Row exists - remove and replace.";
		$delete1sql = "DELETE FROM ctu_report_section3b WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete2sql = "DELETE FROM ctu_report_section3bcurrent WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete3sql = "DELETE FROM ctu_report_section3bclosed WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete3qry = mysql_query($delete3sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		
//		
//		//Replace:
	//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section3b 
				(	report_id,
					furthercommentsonnihrapplications ,
					IPdetails ,
					wouldyoulikechangetofunding ,
					fundingchangesupport ,
					totalunitincomefromnihrfundedprojects,
					totalunitincomefromnihrfundedprojectsmeetoffset
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['furthercommentsonnihrapplications'])."',
					'".mysql_real_escape_string($_POST['IPdetails'])."',
					'".mysql_real_escape_string($_POST['wouldyoulikechangetofunding'])."',
					'".mysql_real_escape_string($_POST['fundingchangesupport'])."',
					'".mysql_real_escape_string($totalunitincomefromnihrfundedprojects)."',
					'".mysql_real_escape_string($totalunitincomefromnihrfundedprojectsmeetoffset)."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
			
//		loop through current projects
		for ($i=1;$i<=$countcurrentprojects;$i++)
		{
		$insertcurrentsql = "INSERT INTO ctu_report_section3bcurrent
						(	report_id,
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
							estimatedstaffcosts,
							estimatednonstaffcosts,
							nonstaffdesc,
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
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'title'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'programme'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'nihrprojectref'])."',
							".(strlen($_POST['currentproject'.$i.'startdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['currentproject'.$i.'startdate'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'duration'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'currentstatus'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'plannedrecruitmenttotal'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'numberofprojectsites'])."',
							'".(isset($_POST['currentproject'.$i.'intmultisite'])&&$_POST['currentproject'.$i.'intmultisite']=='on' ? "yes" : "no")."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'expectedinput'])."',
							".(strlen($_POST['currentproject'.$i.'totalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalcost'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'expectedvalue'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'estimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'estimatedstaffcosts'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'estimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'estimatednonstaffcosts'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'nonstaffdesc'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'fundingreceivedthisperiod'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'iffundingnotreceivedinperiod'])."',
							".(strlen($_POST['currentproject'.$i.'totalfundingreceived'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalfundingreceived'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['currentproject'.$i.'contractextension'])."',
							'".mysql_real_escape_string($_POST['currentproject'.$i.'whyextensiongranted'])."',
							".(strlen($_POST['currentproject'.$i.'totalvalueofextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'totalvalueofextension'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'valueofextensiontounit'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'valueofextensiontounit'])))."'" : "NULL").",
							".(strlen($_POST['currentproject'.$i.'additionalfundingfromcontractextension'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['currentproject'.$i.'additionalfundingfromcontractextension'])))."'" : "NULL").",
							'".(isset($_POST['currentproject'.$i.'NIHRoffset'])&&$_POST['currentproject'.$i.'NIHRoffset']=='on' ? "yes" : "no")."'
						)";
		//echo "<pre>".$insertcurrentsql."</pre>";
		$insertcurrentqry = mysql_query($insertcurrentsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertcurrentsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	
		}
//
//
		//loop through full closed projects
		for ($x=1;$x<=$countclosed;$x++)
		{
		$insertclosedsql = "INSERT INTO ctu_report_section3bclosed
						(	report_id,	
							closed_rank,
							title,
							programme,
							reference,
							reason
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['closed'.$x.'title'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'programme'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'reference'])."',
							'".mysql_real_escape_string($_POST['closed'.$x.'reason'])."'
						)";
		//echo "<pre>".$insertclosedsql."<pre>";
		$insertclosedqry = mysql_query($insertclosedsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertclosedsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		}
//	
}
	//Update section validity
	$sectionsql = "UPDATE ctu_activityreporting SET section3bvalid = 'yes', status = 'inprogress' , section3blastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	//echo "<pre>".$sectionsql."</pre>";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());

	header("Location: ".$url."reporthome.php");
}
else
{
//Throw back to section3.php with $_GET of errors;
$errorstring="";
foreach ($errors as $key)
{
	$errorstring.=$key."%%";
}
header("Location: ".$url."section3b.php?errors=".$errorstring);
}
?>