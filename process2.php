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
$sqlhistory = "INSERT INTO ctu_report_section2_history SELECT ctu_report_section2.*, '".$time."' FROM ctu_report_section2 WHERE report_id = '".$_SESSION['report_id']."'";
$qryhistory  = mysql_query($sqlhistory);
echo mysql_error();
$sql1history = "INSERT INTO ctu_report_section2planned_history SELECT ctu_report_section2planned.*, '".$time."' FROM ctu_report_section2planned WHERE report_id = '".$_SESSION['report_id']."'";
$qry1history  = mysql_query($sql1history);
echo mysql_error();
$sql2history = "INSERT INTO ctu_report_section2current_history SELECT ctu_report_section2current.*, '".$time."' FROM ctu_report_section2current WHERE report_id = '".$_SESSION['report_id']."'";
$qry2history  = mysql_query($sql2history);
echo mysql_error();
echo "report_id - ".$_SESSION['report_id']."<br>";
echo "buffer size:".ob_get_length()."<br>";
//spit out $_POST array
echo "<pre>";
foreach($_POST as $key=>$value)
{
	echo "<b>".mysql_real_escape_string($key)."</b> - ".mysql_real_escape_string($value)."<br>";
}
echo "</pre>";
//how many objectives passed?
//build necessary required items.
$countobjectives=1;
$countplanned=1;
$required=array('objective1achieved','objective1progress');
while (isset($_POST['objective'.($countobjectives+1).'achieved'])) {
	array_push($required, 'objective'.($countobjectives+1).'achieved', 'objective'.($countobjectives+1).'progress');
	$countobjectives++;
}
while (isset($_POST['plannedobjective'.($countplanned+1)]) && strlen($_POST['plannedobjective'.($countplanned+1)])>0) { $countplanned++;}
//echo $countplanned;
//finish required array
array_push($required, 'activitesdevwidernihr','capacityactivity','trainingreceivedbyctustaff','fundingfromhei','fundingfromnhstrusts','otherfunding','variousfundingsources','plannedobjective1','plannedobjective1date');
//echo "<pre>";
//print_r($required);
//echo "</pre>";
foreach($_POST as $key=>$value)
{
/*

SAM TOOK THIS OUT TO ALLOW BLANK FIELDS!!!!
if (in_array($key,$required) && strlen($value)==0)
{
	$valid=0;
	$errors[]=$key;
}
*/
//echo $key."='\".mysqli_real_escape_string(\$_POST['".$key."']).\"', ";
}
//echo "</pre><br />";
//echo "<u>Validity Section</u><br /><br />";
foreach ($required as $element)
{
	//echo "<b>".$element.":</b> required  - ".((array_key_exists($element,$_POST) && strlen($_POST[$element])>0) ? "Present" : "<span style='color:red'>Absent</span>")."<br />";
}
//echo "<strong>activitesdevwidernihr</strong> string is ".str_word_count($_POST['activitesdevwidernihr'])." words long";
if (str_word_count($_POST['activitesdevwidernihr'])>300)
{
	$valid=0;
	$errors[]='activitesdevwidernihr';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";
//echo "<strong>capacityactivity</strong> string is ".str_word_count($_POST['capacityactivity'])." words long";
if (str_word_count($_POST['capacityactivity'])>300)
{
	$valid=0;
	$errors[]='capacityactivity';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";
//echo "<strong>trainingreceivedbyctustaff</strong> string is ".str_word_count($_POST['trainingreceivedbyctustaff'])." words long";
if (str_word_count($_POST['trainingreceivedbyctustaff'])>300)
{
	$valid=0;
	$errors[]='trainingreceivedbyctustaff';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";
//echo "<strong>variousfundingsources</strong> string is ".str_word_count($_POST['variousfundingsources'])." words long";
if (str_word_count($_POST['variousfundingsources'])>300)
{
	$valid=0;
	$errors[]='variousfundingsources';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";

//
//echo "<strong>fundingfromhei</strong> string is a valid currency amount";
if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['fundingfromhei'])) 
{
	//$valid=0;
	$errors[]='fundingfromhei';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";
//echo "<strong>fundingfromnhstrusts</strong> string is a valid currency amount";
if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['fundingfromnhstrusts'])) 
{
	//$valid=0;
	$errors[]='fundingfromnhstrusts';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";
//echo "<strong>otherfunding</strong> string is a valid currency amount";
if (!preg_match("/^£?(([1-9]{1,3}(,\d{3})*(\.\d{2})?)|(0\.[1-9]\d)|(0\.0[1-9]))$/",$_POST['otherfunding'])) 
{
	//$valid=0;
	$errors[]='otherfunding';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br />";


//echo "Section Valid? : ".($valid==0 ? "No" : "Yes")."<br />";
if (sizeof($errors)>0) 
{
	//echo "<pre>";
	//print_r($errors);
	//echo "</pre>";
}


//If no errors, commit to database:
if ($valid==1)
{
	//Check if row exists already:
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section2 WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']==0)
	{
		//Row does not exist - 3 tables to insert into
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section2 
				(	report_id,
					activitesdevwidernihr,
					capacityactivity,
					trainingreceivedbyctustaff,
					fundingfromhei,
					fundingfromnhstrusts,
					otherfunding,
					variousfundingsources
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['activitesdevwidernihr'])."', 
					'".mysql_real_escape_string($_POST['capacityactivity'])."', 
					'".mysql_real_escape_string($_POST['trainingreceivedbyctustaff'])."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fundingfromhei'])))."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fundingfromnhstrusts'])))."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['otherfunding'])))."', 
					'".mysql_real_escape_string($_POST['variousfundingsources'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql);
		//loop through current objectives
		for ($i=1;$i<=$countobjectives;$i++)
		{
		$insertcurrentsql = "INSERT INTO ctu_report_section2current
						(	report_id,
							objective_rank,
							achieved,
							progress
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['objective'.$i.'achieved'])."',
							'".mysql_real_escape_string($_POST['objective'.$i.'progress'])."'
						)";
		//echo "<pre>".$insertcurrentsql."</pre>";
		$insertcurrentqry = mysql_query($insertcurrentsql);
		}
		//loop through future objectives
		for ($x=1;$x<=$countplanned;$x++)
		{
		$insertplannedsql = "INSERT INTO ctu_report_section2planned
						(	report_id,	
							objective_rank,
							objective,
							targetdate
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['plannedobjective'.$x])."',
							'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['plannedobjective'.$x.'date'])))."'
						)";
		//echo "<pre>".$insertplannedsql."<pre>";
		$insertplannedqry = mysql_query($insertplannedsql);
		}
		
		
	}
	else
	{
		//echo "Row exists - remove and replace.";
		$delete1sql = "DELETE FROM ctu_report_section2 WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete2sql = "DELETE FROM ctu_report_section2current WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete3sql = "DELETE FROM ctu_report_section2planned WHERE report_id = '".mysql_real_escape_string($_SESSION['report_id'])."'";
		$delete1qry = mysql_query($delete1sql);
		$delete2qry = mysql_query($delete2sql);
		$delete3qry = mysql_query($delete3sql);
		
		
		//Replace:
		//first the main report table
		$insertmainsql = "INSERT INTO ctu_report_section2 
				(	report_id,
					activitesdevwidernihr,
					capacityactivity,
					trainingreceivedbyctustaff,
					fundingfromhei,
					fundingfromnhstrusts,
					otherfunding,
					variousfundingsources
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['activitesdevwidernihr'])."',
					'".mysql_real_escape_string($_POST['capacityactivity'])."',  
					'".mysql_real_escape_string($_POST['trainingreceivedbyctustaff'])."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fundingfromhei'])))."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['fundingfromnhstrusts'])))."', 
					'".mysql_real_escape_string(floatval(preg_replace('/[^\d\.]/','',$_POST['otherfunding'])))."', 
					'".mysql_real_escape_string($_POST['variousfundingsources'])."'
				)";
				//echo "<pre>".$insertmainsql."</pre>";
				$insertmainqry = mysql_query($insertmainsql);
		//loop through current objectives
		for ($i=1;$i<=$countobjectives;$i++)
		{
		$insertcurrentsql = "INSERT INTO ctu_report_section2current
						(	report_id,
							objective_rank,
							achieved,
							progress
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$i."',
							'".mysql_real_escape_string($_POST['objective'.$i.'achieved'])."',
							'".mysql_real_escape_string($_POST['objective'.$i.'progress'])."'
						)";
		//echo "<pre>".$insertcurrentsql."</pre>";
		$insertcurrentqry = mysql_query($insertcurrentsql);
		}
		//loop through future objectives
		for ($x=1;$x<=$countplanned;$x++)
		{
		$insertplannedsql = "INSERT INTO ctu_report_section2planned
						(	report_id,	
							objective_rank,
							objective,
							targetdate
						)
						VALUES
						(
							'".mysql_real_escape_string($_SESSION['report_id'])."',
							'".$x."',
							'".mysql_real_escape_string($_POST['plannedobjective'.$x])."',
							'".date('Y-m-d',strtotime(str_replace('/','-',$_POST['plannedobjective'.$x.'date'])))."'
						)";
		//echo "<pre>".$insertplannedsql."<pre>";
		$insertplannedqry = mysql_query($insertplannedsql);
		}
	}
	
	//Update section validity
	$sectionsql = "UPDATE ctu_activityreporting SET section2valid = 'not yet validated', status = 'inprogress', section2lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
	$sectionqry = mysql_query($sectionsql) or die ("<b>A fatal MySQL error occured</b>. \n<br />Query: ".$sectionsql."<br />\nError: (".mysql_errno().") ".mysql_error());
	if (isset($_POST['vred']))
	{
		$vred = mysql_real_escape_string($_POST['vred']);
		header("Location: ".$url."validate.php?". $vred  ."");
	}
	else
	{
		header("Location: ".$url."reporthome.php");
	}
}
else
{
//Throw back to section2.php with $_GET of errors;
$errorstring="";
foreach ($errors as $key)
{
	$errorstring.=$key."%%";
}
header("Location: ".$url."section2.php?errors=".$errorstring);
}
?>