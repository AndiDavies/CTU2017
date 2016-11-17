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
echo "report_id - ".$_SESSION['report_id']."<br>";
//spit out $_POST array
echo "<pre";
foreach($_POST as $key=>$value)
{
	echo "<B>".$key."</b> - ".$value."<br>";
}
echo "</pre>";

$required = array('director1title','director1forename','director1surname','director1jobtitle','director1email','ctuname','organisation','financecode','address','postcode','telephone');
$sqlhistory = "INSERT INTO ctu_report_section1_history SELECT ctu_report_section1.*, now() FROM ctu_report_section1 WHERE report_id = '".$_SESSION['report_id']."'";
$qryhistory  = mysql_query($sqlhistory);
echo mysql_error();
foreach($_POST as $key=>$value)
{
if (in_array($key,$required) && strlen($value)==0)
{
	$valid=0;
	$errors[]=$key;
}
//echo $key."='\".mysqli_real_escape_string(\$_POST['".$key."']).\"', ";
}
//echo "</pre><br />";
//echo "<u>Validity Section</u><br /><br />";
foreach ($required as $element)
{
	//echo "<b>".$element.":</b> required  - ".((array_key_exists($element,$_POST) && strlen($_POST[$element])>0) ? "Present" : "<span style='color:red'>Absent</span>")."<br />";
}
//echo "<br />";
//echo "Only ctudevelopments has additional validation criteria (300 words or less)<br /><br />";
//echo "<strong>ctudevelopments</strong> string is ".str_word_count($_POST['ctudevelopments'])." words long";

if (str_word_count($_POST['ctudevelopments'])>300)
{
	$valid=0;
	$errors[]='ctudevelopments';
	//echo " - bad!";
}
else 
{
	//echo " - ok!";
}
//echo "<br /><br />";
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
	$checksql = "SELECT count(report_id) as 'count' FROM ctu_report_section1 WHERE report_id = '".$_SESSION['report_id']."'";
	$checkqry = mysql_query($checksql);
	$check = mysql_fetch_array($checkqry);
	if ($check['count']==0)
	{
		//Row does not exist - insert
		$insertsql = "INSERT INTO ctu_report_section1 
				(	report_id,
					director1title,
					director1forename,
					director1surname,
					director1jobtitle,
					director1email,
					director2title,
					director2forename,
					director2surname,
					director2jobtitle,
					director2email,
					ctuname,
					organisation,
					financecode,
					address,
					postcode,
					telephone,
					ctupriconname,
					ctupriconjobtitle,
					ctupriconemail,
					ctudevelopments
				) 
				VALUES 
				(	'".mysql_real_escape_string($_SESSION['report_id'])."',
					'".mysql_real_escape_string($_POST['director1title'])."', 
					'".mysql_real_escape_string($_POST['director1forename'])."', 
					'".mysql_real_escape_string($_POST['director1surname'])."', 
					'".mysql_real_escape_string($_POST['director1jobtitle'])."', 
					'".mysql_real_escape_string($_POST['director1email'])."', 
					'".mysql_real_escape_string($_POST['director2title'])."', 
					'".mysql_real_escape_string($_POST['director2forename'])."', 
					'".mysql_real_escape_string($_POST['director2surname'])."', 
					'".mysql_real_escape_string($_POST['director2jobtitle'])."', 
					'".mysql_real_escape_string($_POST['director2email'])."', 
					'".mysql_real_escape_string($_POST['ctuname'])."', 
					'".mysql_real_escape_string($_POST['organisation'])."', 
					'".mysql_real_escape_string($_POST['financecode'])."', 
					'".mysql_real_escape_string($_POST['address'])."', 
					'".mysql_real_escape_string($_POST['postcode'])."', 
					'".mysql_real_escape_string($_POST['telephone'])."', 
					'".mysql_real_escape_string($_POST['ctupriconname'])."', 
					'".mysql_real_escape_string($_POST['ctupriconjobtitle'])."', 
					'".mysql_real_escape_string($_POST['ctupriconemail'])."', 
					'".mysql_real_escape_string($_POST['ctudevelopments'])."'    
				)";
				//echo $insertsql;
				$insertqry = mysql_query($insertsql);
				//echo mysql_error();
	}
	else
	{
		//Row exists - update
		//echo "row exists<br/>";
		$updatesql = "UPDATE ctu_report_section1 SET
							director1title='".mysql_real_escape_string($_POST['director1title'])."', 
							director1forename='".mysql_real_escape_string($_POST['director1forename'])."', 
							director1surname='".mysql_real_escape_string($_POST['director1surname'])."', 
							director1jobtitle='".mysql_real_escape_string($_POST['director1jobtitle'])."', 
							director1email='".mysql_real_escape_string($_POST['director1email'])."', 
							director2title='".mysql_real_escape_string($_POST['director2title'])."', 
							director2forename='".mysql_real_escape_string($_POST['director2forename'])."', 
							director2surname='".mysql_real_escape_string($_POST['director2surname'])."', 
							director2jobtitle='".mysql_real_escape_string($_POST['director2jobtitle'])."', 
							director2email='".mysql_real_escape_string($_POST['director2email'])."', 
							ctuname='".mysql_real_escape_string($_POST['ctuname'])."', 
							organisation='".mysql_real_escape_string($_POST['organisation'])."', 
							financecode='".mysql_real_escape_string($_POST['financecode'])."', 
							address='".mysql_real_escape_string($_POST['address'])."', 
							postcode='".mysql_real_escape_string($_POST['postcode'])."', 
							telephone='".mysql_real_escape_string($_POST['telephone'])."', 
							ctupriconname='".mysql_real_escape_string($_POST['ctupriconname'])."', 
							ctupriconjobtitle='".mysql_real_escape_string($_POST['ctupriconjobtitle'])."', 
							ctupriconemail='".mysql_real_escape_string($_POST['ctupriconemail'])."', 
							ctudevelopments='".mysql_real_escape_string($_POST['ctudevelopments'])."'
					WHERE 
							report_id = '".$_SESSION['report_id']."'";
		//echo $updatesql;	
		$updateqry = mysql_query($updatesql);				
	}
	
	//Update section validity
	$sectionsql = "UPDATE ctu_activityreporting SET section1valid = 'not yet validated', status = 'inprogress', section1lastupdate = now() WHERE report_id = '".$_SESSION['report_id']."'";
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
//Throw back to section1.php with $_GET of errors;
$errorstring="";
foreach ($errors as $key)
{
	$errorstring.=$key."%%";
}
header("Location: ".$url."section1.php?errors=".$errorstring);
}

?>