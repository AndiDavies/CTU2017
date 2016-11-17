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


$sqlhistory = "INSERT INTO ctu_report_section3_history SELECT ctu_report_section3.*, '".$time."' FROM ctu_report_section3 WHERE report_id = '".$_SESSION['report_id']."'";
$qryhistory  = mysql_query($sqlhistory);
echo mysql_error();
$sql1history = "INSERT INTO ctu_report_section3full_history SELECT ctu_report_section3full.*, '".$time."' FROM ctu_report_section3full WHERE report_id = '".$_SESSION['report_id']."'";
$qry1history  = mysql_query($sql1history);
echo mysql_error();
$sql2history = "INSERT INTO ctu_report_section3outline_history SELECT ctu_report_section3outline.*, '".$time."' FROM ctu_report_section3outline WHERE report_id = '".$_SESSION['report_id']."'";
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
//
$required=array('previousoutlineproposalsshortlisted',
				'previousoutlineproposalsrejected',
				'currentoutlineproposalssubmitted',
				'currentoutlineproposalsshortlisted',
				'currentoutlineproposalsrejected',
				'currentoutlineproposalsdecisionpending',
				'previousfullproposalsfunded',
				'previousfullproposalsrejected',
				'currentfullproposalssubmitted',
				'currentfullproposalsfunded',
				'currentfullproposalsfundwithchange',
				'currentfullproposalsdeferred',
				'currentfullproposalsresubmitting',
				'currentfullproposalstransferred',
				'currentfullproposalsrejected',
				'currentfullproposalsdecisionpending',
				'nihrprojectsstartedduringperiod',
				'totalcurrentnihrprojects'
				);

				
//how many outline proposals passed?
$countoutlineproposals=0;
$countfullproposals=0;

while (isset($_POST['project'.($countoutlineproposals+1).'title']) && strlen($_POST['project'.($countoutlineproposals+1).'title'])>0) {
	$countoutlineproposals++;
}
while (isset($_POST['fullproject'.($countfullproposals+1).'title']) && strlen($_POST['fullproject'.($countfullproposals+1).'title'])>0) { 
	$countfullproposals++;
}

//echo "Outline Proposals: ".$countoutlineproposals."<br />";
//echo "Full Proposals: ".$countfullproposals."<br />";

//foreach($_POST as $key=>$value)
//{
//	if (in_array($key,$required) && strlen($value)==0)
//	{
//		$valid=0;
//		$errors[]=$key;
//	}
//}

//echo ($valid==1 ? "All required items present" : "Something is missing!");
//echo "<br/>";

//echo "<u>Validity Section</u><br /><br />";
//foreach ($required as $element)
//{
//	//echo "<b>".$element.":</b> required  - ".((array_key_exists($element,$_POST) && strlen($_POST[$element])>0) ? "Present" : "<span style='color:red'>Absent</span>")."<br />";
//}
//echo "<strong>activitesdevwidernihr</strong> string is ".str_word_count($_POST['activitesdevwidernihr'])." words long";
//if (str_word_count($_POST['activitesdevwidernihr'])>300)
//{
//	$valid=0;
//	$errors[]='activitesdevwidernihr';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
////echo "<strong>capacityactivity</strong> string is ".str_word_count($_POST['capacityactivity'])." words long";
//if (str_word_count($_POST['capacityactivity'])>300)
//{
//	$valid=0;
//	$errors[]='capacityactivity';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
////echo "<strong>trainingreceivedbyctustaff</strong> string is ".str_word_count($_POST['trainingreceivedbyctustaff'])." words long";
//if (str_word_count($_POST['trainingreceivedbyctustaff'])>300)
//{
//	$valid=0;
//	$errors[]='trainingreceivedbyctustaff';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
////echo "<strong>variousfundingsources</strong> string is ".str_word_count($_POST['variousfundingsources'])." words long";
//if (str_word_count($_POST['variousfundingsources'])>300)
//{
//	$valid=0;
//	$errors[]='variousfundingsources';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
//
////
////echo "<strong>fundingfromhei</strong> string is a valid currency amount";
//if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['fundingfromhei'])) 
//{
//	//$valid=0;
//	$errors[]='fundingfromhei';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
////echo "<strong>fundingfromnhstrusts</strong> string is a valid currency amount";
//if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['fundingfromnhstrusts'])) 
//{
//	//$valid=0;
//	$errors[]='fundingfromnhstrusts';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
////echo "<strong>otherfunding</strong> string is a valid currency amount";
//if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['otherfunding'])) 
//{
//	//$valid=0;
//	$errors[]='otherfunding';
//	//echo " - bad!";
//}
//else 
//{
//	//echo " - ok!";
//}
////echo "<br />";
//
//
//echo "Section Valid? : ".($valid==0 ? "No" : "Yes")."<br />";
if (sizeof($errors)>0) 
{
	//echo "<pre>";
	//print_r($errors);
	//echo "</pre>";
}
//
//
//If no errors, commit to database:
if ($valid==1)
{
	//Check if row exists already:
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section3 WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']==0)
	{
		//Row does not exist - 3 tables to insert into
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section3 
				(	report_id,
					previousoutlineproposalsshortlisted,
					previousoutlineproposalsrejected,
					currentoutlineproposalssubmitted,
					currentoutlineproposalsshortlisted,
					currentoutlineproposalsrejected,
					currentoutlineproposalsdecisionpending,
					outlineproposalsdetail,
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
					fullproposalsdetail,
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
					'".mysql_real_escape_string($_POST['outlineproposalsdetail'])."',
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
					'".mysql_real_escape_string($_POST['fullproposalsdetail'])."',
					'".mysql_real_escape_string($_POST['nihrprojectsstartedduringperiod'])."',
					'".mysql_real_escape_string($_POST['totalcurrentnihrprojects'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		//loop through outline proposals
		for ($i=1;$i<=$countoutlineproposals;$i++)
		{
		$insertoutlinesql = "INSERT INTO ctu_report_section3outline
						(	report_id,
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
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['project'.$i.'title'])."',
							'".mysql_real_escape_string($_POST['project'.$i.'programme'])."',
							'".mysql_real_escape_string($_POST['project'.$i.'reference'])."',
							".(strlen($_POST['project'.$i.'date'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['project'.$i.'date'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['project'.$i.'staffinput'])."',
							".(strlen($_POST['project'.$i.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['project'.$i.'expectedvalue'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['project'.$i.'status'])."'
						)";
		//echo "<pre>".$insertoutlinesql."</pre>";
		$insertoutlineqry = mysql_query($insertoutlinesql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinesql."<br />\nError: (".mysql_errno().") ".mysql_error());
		}


		//loop through full proposals
		for ($x=1;$x<=$countfullproposals;$x++)
		{
		$insertfullsql = "INSERT INTO ctu_report_section3full
						(	report_id,	
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
							expectedvalue,
							estimatedstaffcosts,
							estimatednonstaffcosts,
							nonstaffdesc
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'title'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'nihrprojectref'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'programme'])."',
							".(strlen($_POST['fullproject'.$x.'datesubmitted'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullproject'.$x.'datesubmitted'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'duration'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'plannedrecruitmenttotal'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'numberofprojectsites'])."',
							".(isset($_POST['fullproject'.$x.'intmultisite'])&&$_POST['fullproject'.$x.'intmultisite']=='on' ? "'yes'" : "'no'").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'expectedinput'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'currentstatus'])."',
							".(strlen($_POST['fullproject'.$x.'estimatedoractualstartdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullproject'.$x.'estimatedoractualstartdate'])))."'" : "NULL").",
							".(isset($_POST['fullproject'.$x.'isstartdateestimated'])&&$_POST['fullproject'.$x.'isstartdateestimated']=='on' ? "'yes'" : "'no'").",
							".(strlen($_POST['fullproject'.$x.'totalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'totalcost'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'expectedvalue'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'estimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'estimatedstaffcosts'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'estimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'estimatednonstaffcosts'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'nonstaffdesc'])."'
						)";
		//echo "<pre>".$insertfullsql."<pre>";
		$insertfullqry = mysql_query($insertfullsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		}
	}
	else
	{
		//echo "Row exists - remove and replace.";
		$delete1sql = "DELETE FROM ctu_report_section3 WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		
		$delete2sql = "DELETE FROM ctu_report_section3outline WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete3sql = "DELETE FROM ctu_report_section3full WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete1qry = mysql_query($delete1sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete1sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete2qry = mysql_query($delete2sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete2sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		$delete3qry = mysql_query($delete3sql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$delete3sql."<br />\nError: (".mysql_errno().") ".mysql_error());
		//Replace:
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section3 
				(	report_id,
					previousoutlineproposalsshortlisted,
					previousoutlineproposalsrejected,
					currentoutlineproposalssubmitted,
					currentoutlineproposalsshortlisted,
					currentoutlineproposalsrejected,
					currentoutlineproposalsdecisionpending,
					outlineproposalsdetail,
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
					fullproposalsdetail,
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
					'".mysql_real_escape_string($_POST['outlineproposalsdetail'])."',
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
					'".mysql_real_escape_string($_POST['fullproposalsdetail'])."',
					'".mysql_real_escape_string($_POST['nihrprojectsstartedduringperiod'])."',
					'".mysql_real_escape_string($_POST['totalcurrentnihrprojects'])."'
				)";
			//	echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertmainsql."<br />\nError: (".mysql_errno().") ".mysql_error());
				//echo mysql_error();
		//loop through outline proposals
		for ($i=1;$i<=$countoutlineproposals;$i++)
		{
		$insertoutlinesql = "INSERT INTO ctu_report_section3outline
						(	report_id,
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
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['project'.$i.'title'])."',
							'".mysql_real_escape_string($_POST['project'.$i.'programme'])."',
							'".mysql_real_escape_string($_POST['project'.$i.'reference'])."',
							".(strlen($_POST['project'.$i.'date'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['project'.$i.'date'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['project'.$i.'staffinput'])."',
							".(strlen($_POST['project'.$i.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['project'.$i.'expectedvalue'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['project'.$i.'status'])."'
						)";
		//echo "<pre>".$insertoutlinesql."</pre>";
		$insertoutlineqry = mysql_query($insertoutlinesql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertoutlinesql."<br />\nError: (".mysql_errno().") ".mysql_error());
		//echo mysql_error();
		}
		//loop through full proposals
		for ($x=1;$x<=$countfullproposals;$x++)
		{
		$insertfullsql = "INSERT INTO ctu_report_section3full
						(	report_id,	
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
							expectedvalue,
							estimatedstaffcosts,
							estimatednonstaffcosts,
							nonstaffdesc
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'title'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'nihrprojectref'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'programme'])."',
							".(strlen($_POST['fullproject'.$x.'datesubmitted'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullproject'.$x.'datesubmitted'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'duration'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'plannedrecruitmenttotal'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'numberofprojectsites'])."',
							".(isset($_POST['fullproject'.$x.'intmultisite'])&&$_POST['fullproject'.$x.'intmultisite']=='on' ? "'yes'" : "'no'").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'expectedinput'])."',
							'".mysql_real_escape_string($_POST['fullproject'.$x.'currentstatus'])."',
							".(strlen($_POST['fullproject'.$x.'estimatedoractualstartdate'])>0 ? "'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['fullproject'.$x.'estimatedoractualstartdate'])))."'" : "NULL").",
							".(isset($_POST['fullproject'.$x.'isstartdateestimated'])&&$_POST['fullproject'.$x.'isstartdateestimated']=='on' ? "'yes'" : "'no'").",
							".(strlen($_POST['fullproject'.$x.'totalcost'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'totalcost'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'expectedvalue'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'expectedvalue'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'estimatedstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'estimatedstaffcosts'])))."'" : "NULL").",
							".(strlen($_POST['fullproject'.$x.'estimatednonstaffcosts'])>0 ? "'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fullproject'.$x.'estimatednonstaffcosts'])))."'" : "NULL").",
							'".mysql_real_escape_string($_POST['fullproject'.$x.'nonstaffdesc'])."'
						)";
		//echo "<pre>".$insertfullsql."<pre>";
		$insertfullqry = mysql_query($insertfullsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$insertfullsql."<br />\nError: (".mysql_errno().") ".mysql_error());
		//echo mysql_error();
		}
}
//Update section validity
	$sectionsql = "UPDATE ctu_activityreporting SET section3valid = 'yes', status = 'inprogress', section3lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
//	echo mysql_error();
	header("Location: ".$url."reporthome.php");
}
else
{
////Throw back to section2.php with $_GET of errors;
//$errorstring="";
//foreach ($errors as $key)
//{
//	$errorstring.=$key."%%";
//}
header("Location: ".$url."section3.php?errors=".$errorstring);
}

?>