<?php
session_start();
//error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
require_once($_SERVER['DOCUMENT_ROOT'].'/netscc/tcpdf/config/tcpdf_config.php');
// Include the main TCPDF library (search for installation path).
$tcpdf_include_dirs = array(realpath('../tcpdf/tcpdf.php'), '/usr/share/php/tcpdf/tcpdf.php', $_SERVER['DOCUMENT_ROOT'].'/tcpdf/tcpdf.php', '/usr/share/tcpdf/tcpdf.php', '/usr/share/php-tcpdf/tcpdf.php', '1/var/www/tcpdf/tcpdf.php', '/var/www/html/tcpdf/tcpdf.php', '/usr/local/apache2/htdocs/tcpdf/tcpdf.php', '/www/hosts/www.sdo.nihr.ac.uk/htdocs/netscc/tcpdf/tcpdf.php');
foreach ($tcpdf_include_dirs as $tcpdf_include_path) {
	if (@file_exists($tcpdf_include_path)) {
		require_once($tcpdf_include_path);
		break;
	}
}



//Firstly, if session report_id is not set throw user to report home page.
if (!isset($_SESSION['ctu']))
{
	header("Location: ".$url."reporthome.php?reason=1");
}

$getreportsql = "SELECT rp.startdate,
						rp.enddate,
						rp.completiondeadline
				FROM ctu_activityreporting AS ar
					INNER JOIN ctu_reportingperiods AS rp ON 
						ar.period = rp.period
				WHERE ar.report_id = '".$_SESSION['report_id']."'";
$getreportqry = mysql_query($getreportsql);
$report = mysql_fetch_array($getreportqry);
$formid=$_SESSION['report_id'];
$reportingperiod = date('jS F Y',strtotime($report['startdate']))." - ".date('jS F Y',strtotime($report['enddate']));
$reportdueby = date('jS F Y',strtotime($report['completiondeadline']));
//get CTU details from DB
	$ctusql = "SELECT * FROM ctu_details where ctu_id = '".$_SESSION['ctu']."'";
	$ctuqry = mysql_query($ctusql);
	$ctu = mysql_fetch_array($ctuqry);
	$currentfundingaward="£".number_format($ctu['currentaward']);



//============================================================+
// File name   : example_015.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 015 for TCPDF class
//               Bookmarks (Table of Content)
//               and Named Destinations.
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Bookmarks (Table of Content)
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
//require_once('../tcpdf/examples/tcpdf_include.php');

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'utf-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('NIHRCTU');
$pdf->SetTitle('CTU Activity Report Form 2013');
$pdf->SetSubject('CTU Activity Report');
$pdf->SetKeywords('NIHR,CTU,Activity');

// set default header data
$pdf->SetHeaderData('', 0, 'CTU Activity Report', 'NIHRCTU');

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setHeaderFont(Array('times', '', 10, '', false));
$pdf->setFooterFont(Array('times', '', 8, '', false));
$pdf->SetFont('times', '', 10, '', false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// Bookmark($txt, $level=0, $y=-1, $page='', $style='', $color=array(0,0,0))

//******************Some defaults
$linewidth=145;
$linegap = 3;
$defaultfill = array(240,240,240);
$dlh=6;
$dl2h=2*$dlh;


function checkaddpage($height)
{
	global $pdf;
	$dimensions = $pdf->getPageDimensions();
	if (($pdf->GetY() + $height) + $dimensions['bm'] >($dimensions['hk'])) {$pdf->AddPage(); }  
}

// set font
//$pdf->SetFont('times', 'B', 20);

// add a page
$pdf->AddPage();

// set default form properties
$pdf->setFormDefaultProp(array('lineWidth'=>1, 'borderStyle'=>'solid', 'fillColor'=>array(0, 64, 128), 'strokeColor'=>array(255, 128, 128)));

// set a bookmark for the current position
//$pdf->Bookmark('Section 1', 0, 0, '', 'B', array(0,64,128));

//Decide whether there is a section 1 with data in the DB.
	$sqlcheck = "SELECT * FROM ctu_report_section1 WHERE report_id = '".$_SESSION['report_id']."'";
	$qrycheck = mysql_query($sqlcheck);
	$currentvalues = mysql_fetch_array($qrycheck);
	
//Title
$pdf->SetFont('times', 'B', 18);
$pdf->MultiCell(0,5,"NIHR Clinical Trials Unit (CTU) Support Funding\nActivity Report 2014\n",0,'L');
$pdf->Ln($linegap);
$pdf->SetTextColor(0,64,128);
$pdf->MultiCell(0,5,$ctu['name'],0,'L');
$pdf->SetTextColor(0,0,0);
$pdf->Ln(5);

// print a line using Cell()
$pdf->SetFont('times', 'B', 10);
$pdf->Cell(0, 5,'Unique ID: '.$ctu['ukcrc'], 0, 1, 'L');
$pdf->Cell(0, 5,'Reporting Period: '.$reportingperiod, 0, 1, 'L');
$pdf->Cell(0, 5,'Completed form to be submitted by: '.$reportdueby, 0, 1, 'L');

//*************************************//
//**********Begin Section 1************//
//*************************************//
$pdf->Ln(5);

//Section heading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',14);
$pdf->Cell(0, 5,'Section 1: CTU Contact Details', 0, 1 ,'L');
// set a bookmark for the current position
$pdf->Bookmark('Section 1', 0, 0, '', 'B', array(0,64,128));

$pdf->Ln(5);
//Subheading
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 1.1: CTU Director Contact Details', 0, 1 ,'L');
$pdf->Ln(5);

$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);

//Title
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director1title'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Forename
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Forename:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director1forename'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Surname
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Surname:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director1surname'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Job title
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Job Title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 5,$currentvalues['director1jobtitle'], 0, 'L', 1);
$pdf->Ln($linegap);

//email
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Email:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director1email'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Subheading
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 1.2: CTU Director 2 Contact Details (if applicable)', 0, 1 ,'L');
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);

//Title
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director2title'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Forename
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Forename:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director2forename'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Surname
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Surname:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director2surname'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Job title
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Job Title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 5,$currentvalues['director2jobtitle'], 0, 'L', 1);
$pdf->Ln($linegap);

//email
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Email:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['director2email'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 1.3: CTU Details', 0, 1 ,'L');
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);

//CTU Name
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'CTU Name:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['ctuname'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Host Organisation
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Host Organisation:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 15,$currentvalues['organisation'], 0, 'L' ,1);
$pdf->Ln($linegap);

//Finance Code
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Finance Code:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['financecode'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Address
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Address:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 30,$currentvalues['address'], 0, 'L', 1);
$pdf->Ln($linegap);

//Postcode
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Postcode:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['postcode'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Telephone
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Telephone:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['telephone'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Current Funding
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Current award:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentfundingaward, 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 1.4: CTU Primary Contact details (if different to above)', 0, 1 ,'L');
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);

//Name
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Name:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['ctupriconname'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Job title
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Job Title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['ctupriconjobtitle'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Email
$pdf->SetFont('times','B',11);
$pdf->Cell(35, 5, 'Email Address:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->Cell($linewidth, 5,$currentvalues['ctupriconemail'], 0, 1, 'L' ,1);
$pdf->Ln($linegap);

//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'1.5 CTU developments or changes', 0, 1 ,'L');
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','',10);
$pdf->MultiCell(0, 15,"Please outline any key staffing or organisational developments or changes over the last 12 months. Please supply information on the potential effects of these changes and your proposals for handling any issues arising.", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
//Details
$pdf->Cell(35, 5, 'Limit - 300 words:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$currentvalues['ctudevelopments'], 0, 'L', 1);
$pdf->Ln($linegap);
//*************************************//
//**********End of Section 1***********//
//*************************************//

//*************************************//
//**********Begin Section 2************//
//*************************************//
$pdf->AddPage();


$pdf->Ln(5);
//Section heading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',14);
$pdf->Cell(0, 5,'Section 2: Progress Summary', 0, 1 ,'L');
// set a bookmark for the current position
$pdf->Bookmark('Section 2', 0, 0, '', 'B', array(0,64,128));
$pdf->Ln(5);
//Subheading
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 2.1: Objectives', 0, 1 ,'L');
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 5,"These are your objectives for the current reporting period which were provided in your previous activity report. Please state whether these objectives have been achieved below.", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);


//Output table for Objectives
$dimensions = $pdf->getPageDimensions();
$hasborder = false; //flag for fringe case
$lh=4.5;
//Get the objectives from the database.
$objectivessql = "SELECT * FROM ctu_objectives where ctu_id = '".$_SESSION['ctu']."' order by objective_rank";
$objectivesqry = mysql_query($objectivessql);
$objectivecount = mysql_num_rows($objectivesqry);

$pdf->SetFont('times','',9);
//Set Headings
$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(120, 10, 'Objective', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(20, 10, 'Target Date', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(20, 10, 'Achieved', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->Ln();

while ($objective  = mysql_fetch_array($objectivesqry))
{
	$rowcount = 0;
	$rowcount = $pdf->getNumLines($objective['objective'],120);
	
	$startY = $pdf->GetY();
 
	if (($startY + $rowcount * $lh) + $dimensions['bm'] > ($dimensions['hk'])) {
		//this row will cause a page break, draw the bottom border on previous row and give this a top border
		//we could force a page break and rewrite grid headings here
		if ($hasborder) {
			$hasborder = false;
		} else {
			$pdf->Cell(180,0,'','T'); //draw bottom border on previous row
			$pdf->AddPage();
			$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(120, 10, 'Objective (continued)', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'Target Date', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'Achieved', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->Ln();
		}
		$borders = 'LTR';
	} elseif ((ceil($startY) + $rowcount * $lh) + $dimensions['bm'] == floor($dimensions['hk'])) {
		//fringe case where this cell will just reach the page break
		//draw the cell with a bottom border as we cannot draw it otherwise
		$borders = 'LRB';	
		$hasborder = true; //stops the attempt to draw the bottom border on the next row
	} else {
		//normal cell
		$borders = 'LTR';
	}
 
	//now draw it
	$pdf->MultiCell(20,$rowcount * $lh,$objective['objective_rank'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(120,$rowcount * $lh,$objective['objective'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(20,$rowcount * $lh,date('d/m/Y',strtotime($objective['targetdate'])),$borders,'L',0,0,'','',true,0,false,true,0,'M');
		$objsql = "SELECT * FROM ctu_report_section2current WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = '".$objective['objective_rank']."'";
		$objqry = mysql_query($objsql);
		$obj = mysql_fetch_array($objqry);
	$pdf->MultiCell(20,$rowcount * $lh,ucwords($obj['achieved']),$borders,'C',0,0,'','',true,0,false,true,0,'M');
 
	$pdf->Ln();
}
 
$pdf->Cell(180,0,'','T');  //last bottom border
$pdf->Ln();
checkaddpage(40);
//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Section 2.2: Progress against objectives', 0, 1 ,'L');
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 5,"If objectives have been listed in the previous year, please give details on progress if the objective has not been met, or if you would like to provide further information.", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);


$dimensions = $pdf->getPageDimensions();
$hasborder = false; //flag for fringe case

//Get the objectives from the database.
$objectivessql = "SELECT * FROM ctu_objectives where ctu_id = '".$_SESSION['ctu']."' order by objective_rank";
$objectivesqry = mysql_query($objectivessql);
$objectivecount = mysql_num_rows($objectivesqry);

$pdf->SetFont('times','',9);
//Set Headings
$lh=4.5;
//$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
//$pdf->MultiCell(160, 10, 'Progress', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
//$pdf->Ln();

while ($objective  = mysql_fetch_array($objectivesqry))
{
		$objsql = "SELECT * FROM ctu_report_section2current WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = '".$objective['objective_rank']."'";
		$objqry = mysql_query($objsql);
		$obj = mysql_fetch_array($objqry);
	$rowcount = 0;
	$rowcount = $pdf->getNumLines($obj['progress'],160);
	
	$startY = $pdf->GetY();
 	$pdf->Cell(180,0,'Objective Number: '.$objective['objective_rank'],'LRT',2);
	$pdf->MultiCell(180,0,trim(str_replace("\t", " ", $obj['progress'])),'LRTB','L',0);
	
	//if (($startY + $rowcount * $lh) + $dimensions['bm'] > ($dimensions['hk'])) {
//		//this row will cause a page break, draw the bottom border on previous row and give this a top border
//		//we could force a page break and rewrite grid headings here
//		if ($hasborder) {
//			$hasborder = false;
//		} else {
//			$pdf->Cell(180,0,'','T'); //draw bottom border on previous row
//			$pdf->AddPage();
//			$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
//			$pdf->MultiCell(160, 10, 'Progress (continued)', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
//			$pdf->Ln();
//		}
//		$borders = 'LTR';
//	} elseif ((ceil($startY) + $rowcount * $lh) + $dimensions['bm'] == floor($dimensions['hk'])) {
//		//fringe case where this cell will just reach the page break
//		//draw the cell with a bottom border as we cannot draw it otherwise
//		$borders = 'LRB';	
//		$hasborder = true; //stops the attempt to draw the bottom border on the next row
//	} else {
//		//normal cell
//		$borders = 'LTR';
//	}
// 
//	//now draw it
//	$pdf->MultiCell(20,$rowcount * $lh,$objective['objective_rank'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
//	$pdf->MultiCell(160,$rowcount * $lh,$obj['progress'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
// 
//	$pdf->Ln();
}
 
$pdf->Cell(180,0,'','T');  //last bottom border
$pdf->Ln();
checkaddpage(40);
//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.3 Planned objectives for CTU Support Funding (to be completed by units in receipt of funding for less than 3 years only)', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 5,"Please list below what you expect to achieve in the next reporting period and provide target dates. A maximum of 5 objectives are to be given.", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

$objsql = "SELECT * FROM ctu_report_section2planned WHERE report_id = '".$_SESSION['report_id']."' ORDER BY objective_rank";
$objqry = mysql_query($objsql);

$dimensions = $pdf->getPageDimensions();
$hasborder = false; //flag for fringe case
$lh=4;
$pdf->SetFont('times','',9);
//Set Headings
$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(140, 10, 'Objectives', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(20, 10, 'Target Date', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
$pdf->Ln();

while ($obj  = mysql_fetch_array($objqry))
{
	$rowcount = 0;
	$rowcount = $pdf->getNumLines(trim($obj['objective']),140);
	
	$startY = $pdf->GetY();
	 
	if (($startY + $rowcount * $lh) + $dimensions['bm'] > ($dimensions['hk'])) {
		//this row will cause a page break, draw the bottom border on previous row and give this a top border
		//we could force a page break and rewrite grid headings here
		if ($hasborder) {
			$hasborder = false;
		} else {
			$pdf->Cell(180,0,'','T'); //draw bottom border on previous row
			$pdf->AddPage();
			$pdf->MultiCell(20, 10, 'Objective Number', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(140, 10, 'Objectives (continued)', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'Target Date', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
			$pdf->Ln();

		}
		$borders = 'LTR';
	} elseif ((ceil($startY) + $rowcount * $lh) + $dimensions['bm'] == floor($dimensions['hk'])) {
		//fringe case where this cell will just reach the page break
		//draw the cell with a bottom border as we cannot draw it otherwise
		$borders = 'LRB';	
		$hasborder = true; //stops the attempt to draw the bottom border on the next row
	} else {
		//normal cell
		$borders = 'LTR';
	}
 
	//now draw it
	$pdf->MultiCell(20,$rowcount * $lh,$obj['objective_rank'],$borders,'C',0,0,'','',true,0,false,true,$rowcount * $lh,'T');
	$pdf->MultiCell(140,$rowcount * $lh,trim($obj['objective']),$borders,'L',0,0,'','',true,0,false,true,$rowcount * $lh,'T');
	$pdf->MultiCell(20,$rowcount * $lh,(strlen($obj['targetdate'])>0 ? date('d/m/Y',strtotime($obj['targetdate'])) : "" ),$borders,'C',0,0,'','',true,0,false,true,$rowcount * $lh,'T');
 
	$pdf->Ln();
}
 
$pdf->Cell(180,0,'','T');  //last bottom border
$pdf->Ln();

$mainsql= "SELECT * FROM ctu_report_section2 WHERE report_id = '".$_SESSION['report_id']."'";
$mainqry = mysql_query($mainsql);
$main = mysql_fetch_array($mainqry);
checkaddpage(40);
//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.4 Involvement with other research networks and participation on TSCs and DMCs', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
checkaddpage(20);
//Details
$pdf->MultiCell(35, 5, 'Details of work in this area', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$main['activitesdevwidernihr'], 0, 'L', 1);
$pdf->Ln($linegap);

//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.5 Activity your unit has undertaken to increase the capacity for NIHR trials and studies (using your CTU Support Funding award)', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

//Details
$pdf->MultiCell(35, 5, 'Details of work in this area', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$main['capacityactivity'], 0, 'L', 1);
$pdf->Ln($linegap);

//Subheading
checkaddpage($dlh*8);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.6 Key training received by CTU staff and current unit training strategies', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

//Details
$pdf->SetFont('times','B',11);
$pdf->MultiCell(35,5, 'Please list below all key research and methodology training received during this reporting period. Please advise whether training at the unit is opened up to others and on what basis.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 30,$main['trainingreceivedbyctustaff'], 0, 'L', 1);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
$pdf->Ln($linegap);
//Subheading
checkaddpage($dlh*5);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.7 Funding contributions from Higher Education Institution (HEI)', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

//Details

$pdf->MultiCell(70, 5, 'What amount of funding has your unit received from your HEI this reporting period?', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 15,'£'.number_format($main['fundingfromhei'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Subheading
checkaddpage($dlh*5);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.8 Funding contributions from NHS Trusts', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

//Details
$pdf->MultiCell(70, 5, 'What amount of funding has your unit received from NHS Trusts this reporting period?', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 15,'£'.number_format($main['fundingfromnhstrusts'], 2), 0, 'L', 1);
$pdf->Ln($linegap);
//echo "got here!";
//Subheading
checkaddpage($dlh*5);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,'2.9 Other infrastructure funding contributions.', 0, 'L' ,0);
$pdf->SetFont('times','',10);
$pdf->SetTextColor(0,0,0);
$pdf->Ln($linegap);
$pdf->SetTextColor(0,0,0);
//$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

//Details
$pdf->MultiCell(70, 5, 'What amount of other infrastructure funding has your unit received during this reporting period?', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 15,'£'.number_format($main['otherfunding'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

checkaddpage($dlh*3);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 5, 'Please provide details on other infrastructure funding received.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 15,$main['variousfundingsources'], 0, 'L', 1);
$pdf->Ln($linegap);


$pdf->Ln(5);
//Section heading
checkaddpage(60);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',14);
$pdf->Cell(0, 5,'Section 3a: CTU NIHR Activity (Part 1)', 0, 1 ,'L');
// set a bookmark for the current position
$pdf->Bookmark('Section 3a', 0, 0, '', 'B', array(0,64,128));
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'This includes all HTA, EME, HS&DR, PHR, PGfAR, i4i and RfPB programme activity', 0, 1 ,'L');
$pdf->Ln(5);
//Subheading
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.1 NIHR-related activity during this reporting period - summary', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->MultiCell(0, 5,"a) Outline Proposals - where decisions were pending in previous reporting period", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);

$mainsql = "SELECT * FROM ctu_report_section3_inprog WHERE report_id = '".$_SESSION['report_id']."'";
$mainqry = mysql_query($mainsql);
$main = mysql_fetch_array($mainqry);
$y = $pdf->GetY();
checkaddpage($dl2h);
$pdf->MultiCell(70, 12, 'Number of Outline proposals; shortlisted.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['previousoutlineproposalsshortlisted'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Outline proposals; rejected.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['previousoutlineproposalsrejected'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,64,128);
$pdf->MultiCell(0, 5,"b) Outline Proposals - submitted in this reporting period", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);
checkaddpage($dl2h);
$pdf->MultiCell(70, 12, 'Number of Outline proposals; submitted.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentoutlineproposalssubmitted'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Outline proposals; shortlisted.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentoutlineproposalsshortlisted'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);

$pdf->SetFont('times','B',11);
$pdf->MultiCell(70,12, 'Number of Outline proposals; rejected.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentoutlineproposalsrejected'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Outline proposals; decision pending.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentoutlineproposalsdecisionpending'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
//Subheading
/*
$pdf->SetFont('times','',12);
$pdf->MultiCell(0, 5,"Please record details/references of Outline proposals where decisions are pending. You will be asked to update on these in your next activity report. (For CTU reference only)", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);
checkaddpage($dl2h);
$pdf->MultiCell($linewidth+35, 15,$main['outlineproposalsdetail'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
*/
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,"c) Full Proposals - where decisions were pending in previous reporting period", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);
checkaddpage($dl2h);
$pdf->MultiCell(70, 12, 'Number of Full proposals; funded.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['previousfullproposalsfunded'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; rejected.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['previousfullproposalsrejected'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0, 5,"d) Full Proposals - submitted in this reporting period", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);
checkaddpage($dl2h);
$pdf->MultiCell(70, 12, 'Number of Full proposals; submitted.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalssubmitted'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; funded.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsfunded'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70,12, 'Number of Full proposals; funded with changes.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsfundwithchange'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70,12, 'Number of Full proposals; deferred.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsdeferred'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; resubmitting.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsresubmitting'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; transferred.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalstransferred'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; rejected.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsrejected'], 0, 'L', 1,1,'','',false);
$pdf->Ln($linegap);
checkaddpage($dl2h);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 12, 'Number of Full proposals; decision pending.', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 12,$main['currentfullproposalsdecisionpending'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage($dl2h);


//Subheading
/*
$pdf->SetFont('times','',12);
$pdf->MultiCell(0, 5,"Please record details/references of Full proposals where decisions are pending. You will be asked to update on these in your next activity report. (For CTU reference only)", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell($linewidth+35, 15,$main['fullproposalsdetail'], 0, 'L', 1);
$pdf->Ln($linegap);
checkaddpage(18);
*/
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 18, 'e) Number of funded NIHR projects started during this reporting period (i.e. project has reached contract start date)', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 18,$main['nihrprojectsstartedduringperiod'], 0, 'L', 1,1,'','',true,0,false,true,0,'T');
$pdf->Ln($linegap);
checkaddpage(42);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(70, 36, 'f) Total number of \'current\' NIHR funded research projects (\'Current\' is defined as those projects which are within the contract start and end date. Not just those initiated in the last 12 months)', 0, 'R', 0,0,'','',true,0,false,true,0,'M');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth-35, 36,$main['totalcurrentnihrprojects'],  0, 'L', 1,1,'','',true,0,false,true,0,'T');
$pdf->Ln($linegap);
checkaddpage(60);
$pdf->MultiCell(170,0,"Full details of all proposals and funded projects should be provided in the following sub-sections\nThe following 3 questions ask for NIHR project references.\nWe have provided a list of NETS projects which we have recorded on our system as linked to your unit. Please use this to assist you in relation to NETS programmes applications. If you cannot find a project listed or there are any which you believe are included in error, please contact us as soon as possible before you submit your report so that we can advise you.\nIf the query is in relation to a CCF-managed programme please contact the relevant team at CCF.", 'LRTB','L',0);
$pdf->Ln($linegap);
//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.2 Outline proposals submitted to NIHR in this reporting period', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 5,"DO NOT include EOI or add-on/bolt-on submissions in this section. These should be recorded in section 3.7.", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
checkaddpage(20);
$hasborder = false; //flag for fringe case
$lh = 4.5;
//Get the objectives from the database.
$outlinessql = "SELECT * FROM ctu_report_section3outline_inprog WHERE report_id = '".$_SESSION['report_id']."' order by outline_rank";
$outlinesqry = mysql_query($outlinessql);
		
$pdf->SetFont('times','',10);
//Set Headings
$pdf->MultiCell(35, 20, 'Project Title', 'LRTB', 'C' , 0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(23, 20, 'NIHR Programme', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(20, 20, 'NIHR Project Reference', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(20, 20, 'Date Submitted', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(40, 20, 'CTU Role', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(22, 20, 'Expected Value of Project to CTU', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');
$pdf->MultiCell(20, 20, 'Current Status', 'LRTB', 'C',0,0,'','',true,0,false,true,20,'M');

$pdf->Ln();
while($outlines = mysql_fetch_array($outlinesqry))

{
	$rowcount = 0;
	$rowcount = max($pdf->getNumLines($outlines['title'],35),$pdf->getNumLines($outlines['staffinput'],40));
	
	$startY = $pdf->GetY();
 
	if (($startY + $rowcount * $lh) + $dimensions['bm'] + 10> ($dimensions['hk'])) {
		//this row will cause a page break, draw the bottom border on previous row and give this a top border
		//we could force a page break and rewrite grid headings here
		if ($hasborder) {
			$hasborder = false;
		} else {
			$pdf->Cell(180,0,'','T'); //draw bottom border on previous row
			$pdf->AddPage();
			$pdf->MultiCell(35, 10, 'Project Title', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(23, 10, 'NIHR Programme', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'NIHR Project Reference', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'Date Submitted', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(40, 10, 'CTU Staff Input', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(22, 10, 'Expected Value of Project to CTU', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(20, 10, 'Status', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->Ln();
		}
		$borders = 'LTR';
	} elseif ((ceil($startY) + $rowcount * $lh) + $dimensions['bm'] == floor($dimensions['hk'])) {
		//fringe case where this cell will just reach the page break
		//draw the cell with a bottom border as we cannot draw it otherwise
		$borders = 'LRB';	
		$hasborder = true; //stops the attempt to draw the bottom border on the next row
	} else {
		//normal cell
		$borders = 'LTR';
	}
 
	//now draw it
	$pdf->MultiCell(35,$rowcount * $lh,$outlines['title'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(23,$rowcount * $lh,$outlines['programme'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(20,$rowcount * $lh,$outlines['reference'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(20,$rowcount * $lh,date('d/m/Y',strtotime($outlines['submitdate'])),$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(40,$rowcount * $lh,$outlines['staffinput'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(22,$rowcount * $lh,'£'.number_format($outlines['expectedvalue'], 2),$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(20,$rowcount * $lh,$outlines['status'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
 
	$pdf->Ln();
}
 
$pdf->Cell(180,0,'','T');  //last bottom border
$pdf->Ln();
checkaddpage(60);
//Subheading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.3 Full proposals submitted to NIHR in this reporting period', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);
$pdf->MultiCell(0, 5,"Please provide full details on each proposal", 0, 'L', 0);
$pdf->Ln($linegap);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);

$fullsql = "SELECT * FROM ctu_report_section3full_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY full_rank";
$fullqry = mysql_query($fullsql);

while ($full = mysql_fetch_array($fullqry))
{
checkaddpage(20);	
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',18);
$pdf->Cell(0, 5,'Project '.$full['full_rank'], 0, 1 ,'L');
$pdf->Ln(1);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Project general information', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);

//Title
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['title'],$linewidth)));
$pdf->Cell(35, 5, 'Project title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['title'], 0, 'L', 1);
$pdf->Ln($linegap);

//Reference
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['nihrprojectref'],$linewidth)));
$pdf->Cell(35, 5, 'NIHR reference:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['nihrprojectref'], 0, 'L', 1);
$pdf->Ln($linegap);

//Programme
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['programme'],$linewidth)));
$pdf->Cell(35, 5, 'NIHR programme:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['programme'], 0, 'L', 1);
$pdf->Ln($linegap);

//Date Submitted
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(date('d/m/Y',strtotime($full['datesubmitted'])),$linewidth)));
$pdf->Cell(35, 5, 'Date Submitted:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,date('d/m/Y',strtotime($full['datesubmitted'])), 0, 'L', 1);
$pdf->Ln($linegap);

//Project Duration
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['duration']." months",$linewidth)));
$pdf->Cell(35, 5, 'Project Duration:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['duration']." months", 0, 'L', 1);
$pdf->Ln($linegap);

//Planned Recruitment Total
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['plannedrecruitmenttotal'],$linewidth)));
$pdf->Cell(35, 5, 'Planned Recruitment:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['plannedrecruitmenttotal'], 0, 'L', 1);
$pdf->Ln($linegap);

//Number of project sites
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($full['numberofprojectsites'],$linewidth)));
$pdf->Cell(35, 5, 'No. Project Sites:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$full['numberofprojectsites'], 0, 'L', 1);
$pdf->Ln($linegap);

//International Multisite?
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(ucfirst($full['intmultisite']),$linewidth)));
$pdf->Cell(35, 5, 'Int\'l Multi-site?:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,ucfirst($full['intmultisite']), 0, 'L', 1);
$pdf->Ln($linegap);

//input
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(trim($full['expectedinput']),$linewidth)));
$pdf->Cell(35, 5, 'Expected Unit Input:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,trim($full['expectedinput']), 0, 'L', 1);
$pdf->Ln($linegap);

//Current Status
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(trim($full['currentstatus']),$linewidth)));
$pdf->Cell(35, 5, 'Current Status:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,trim($full['currentstatus']), 0, 'L', 1);
$pdf->Ln($linegap);

//Estimated or Actual Start:
$pdf->SetFont('times','B',11);
$datestring="";
$datestring = (strlen($full['estimatedoractualstartdate'])>0 ? date('d/m/Y',strtotime($full['estimatedoractualstartdate'])) : "<not supplied>" );
checkaddpage(6*($pdf->getNumLines($datestring,$linewidth)));
$pdf->Cell(35, 5, 'Est. or Actual Start:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$datestring, 0, 'L', 1);
$pdf->Ln($linegap);

//Is start date estimated:
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(trim(ucwords($full['isstartdateestimated'])),$linewidth)));
$pdf->Cell(35, 5, 'Start date estimated?:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,trim(ucwords($full['isstartdateestimated'])), 0, 'L', 1);
$pdf->Ln($linegap);

checkaddpage(20);	
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Project Costs', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);

//Total cost:
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines('£'.number_format($full['totalcost'], 2),$linewidth)));
$pdf->Cell(35, 5, 'Total Project Cost:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,'£'.number_format($full['totalcost'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Expected Value:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($full['expectedvalue'], 2),$linewidth),$pdf->getNumLines('Expected value of project to your unit over course of project',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Expected value of project to your unit over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($full['expectedvalue'], 2), 0, 'L', 1);
$pdf->Ln($linegap);
$pdf->AddPage();
//Estimated total unit staff costs:
/*
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($full['estimatedstaffcosts'], 2),$linewidth),$pdf->getNumLines('Estimated total unit staff costs over course of project:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Estimated total unit staff costs over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($full['estimatedstaffcosts'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Estimated total unit non-staff costs:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($full['estimatednonstaffcosts'], 2),$linewidth),$pdf->getNumLines('Estimated total unit non-staff costs over course of project:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Estimated total unit non-staff costs over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($full['estimatednonstaffcosts'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Brief Description of non-staff costs:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines($full['nonstaffdesc'],$linewidth),$pdf->getNumLines('Brief description of non-staff costs:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Brief description of non-staff costs:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$full['nonstaffdesc'], 0, 'L', 1);
$pdf->Ln($linegap);
$pdf->AddPage();
//End Loop
*/
}

//checkaddpage(60);
//Section heading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',14);
$pdf->Cell(0, 5,'Section 3b: CTU NIHR Activity (Part 2)', 0, 1 ,'L');

// set a bookmark for the current position
$pdf->Bookmark('Section 3b', 0, 0, '', 'B', array(0,64,128));

$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'This includes all HTA, EME, HS&DR, PHR, PGfAR, i4i and RfPB programme activity', 0, 1 ,'L');
$pdf->Ln(5);
//Subheading
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.4 Current NIHR research', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);
checkaddpage(60);
$pdf->MultiCell(170,0,"'Current' is defined as those project which are within contract start and end dates, and not just those initiated in the last 12 months.\nPlease note that financial accuracy in this section is vital\nThis information is used to calculate any offset achieved by your unit which is the key criteria in determining unit performance.\nPlease note systematic reviews are NOT eligible for offset.", 'LRTB','L',0);
$pdf->Ln($linegap);

//Loop over section 3.4
$currentsql1 = "SELECT * FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER by current_rank";
$currentqry1 = mysql_query($currentsql1);

while ($current1 = mysql_fetch_array($currentqry1))
{
	
checkaddpage(20);	
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',18);
$pdf->Cell(0, 5,'Project '.$current1['current_rank'], 0, 1 ,'L');
$pdf->Ln(1);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Project general information', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);

//Title
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['title'],$linewidth)));
$pdf->Cell(35, 5, 'Project title:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['title'], 0, 'L', 1);
$pdf->Ln($linegap);

//Programme
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['programme'],$linewidth)));
$pdf->Cell(35, 5, 'NIHR programme:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['programme'], 0, 'L', 1);
$pdf->Ln($linegap);

//Reference
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['nihrprojectref'],$linewidth)));
$pdf->Cell(35, 5, 'NIHR reference:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['nihrprojectref'], 0, 'L', 1);
$pdf->Ln($linegap);

//Start Date
$pdf->SetFont('times','B',11);
$datestring="";
$datestring = (strlen($current1['startdate'])>0 ? date('d/m/Y',strtotime($current1['startdate'])) : "<not supplied>" );
$rh = 6* max($pdf->getNumLines($datestring,$linewidth),$pdf->getNumLines('Project Start Date (contract start date):',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Project Start Date (contract start date):', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datestring, 0, 'L', 1);
$pdf->Ln($linegap);

//Project Duration
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['duration']." months",$linewidth)));
$pdf->Cell(35, 5, 'Project Duration:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['duration']." months", 0, 'L', 1);
$pdf->Ln($linegap);

//Current Status
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(trim(ucwords($current1['currentstatus'])),$linewidth)));
$pdf->Cell(35, 5, 'Current Status:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,trim(ucwords($current1['currentstatus'])), 0, 'L', 1);
$pdf->Ln($linegap);

//Planned Recruitment Total
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['plannedrecruitmenttotal'],$linewidth)));
$pdf->Cell(35, 5, 'Planned Recruitment:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['plannedrecruitmenttotal'], 0, 'L', 1);
$pdf->Ln($linegap);

//Number of project sites
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines($current1['numberofprojectsites'],$linewidth)));
$pdf->Cell(35, 5, 'No. Project Sites:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,$current1['numberofprojectsites'], 0, 'L', 1);
$pdf->Ln($linegap);

//International Multisite?
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(ucfirst($current1['intmultisite']),$linewidth)));
$pdf->Cell(35, 5, 'Int\'l Multi-site?:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,ucfirst($current1['intmultisite']), 0, 'L', 1);
$pdf->Ln($linegap);

//input
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines(trim($current1['expectedinput']),$linewidth)));
$pdf->Cell(35, 5, 'Expected Unit Input:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,trim($current1['expectedinput']), 0, 'L', 1);
$pdf->Ln($linegap);

checkaddpage(20);	
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'Project Costs', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFont('times','',12);
$pdf->SetTextColor(0,0,0);

//Total cost:
$pdf->SetFont('times','B',11);
checkaddpage(6*($pdf->getNumLines('£'.number_format($current1['totalcost'], 2),$linewidth)));
$pdf->Cell(35, 5, 'Total Project Cost:', 0, 0, 'R');
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, 0,'£'.number_format($current1['totalcost'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Expected Value:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($current1['expectedvalue'], 2),$linewidth),$pdf->getNumLines('Expected value of project to your unit over course of project',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Expected value of project to your unit over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($current1['expectedvalue'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Estimated total unit staff costs:
/*
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($current1['estimatedstaffcosts'], 2),$linewidth),$pdf->getNumLines('Estimated total unit staff costs over course of project:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Estimated total unit staff costs over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($current1['estimatedstaffcosts'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Estimated total unit non-staff costs:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($current1['estimatednonstaffcosts'], 2),$linewidth),$pdf->getNumLines('Estimated total unit non-staff costs over course of project:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Estimated total unit non-staff costs over course of project:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($current1['estimatednonstaffcosts'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Brief Description of non-staff costs:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines($current1['nonstaffdesc'],$linewidth),$pdf->getNumLines('Brief description of non-staff costs:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Brief description of non-staff costs:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$current1['nonstaffdesc'], 0, 'L', 1);
$pdf->Ln($linegap);
*/
//Was funding received in this reporting period:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines(ucwords($current1['fundingreceivedthisperiod']),$linewidth),$pdf->getNumLines('Was funding received in this reporting period for this project?:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Was funding received in this reporting period for this project?:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,ucwords($current1['fundingreceivedthisperiod']), 0, 'L', 1);
$pdf->Ln($linegap);

//if no, please describe
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines($current1['iffundingnotreceivedinperiod'],$linewidth),$pdf->getNumLines('If no, please describe why funding has not been received:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'If no, please describe why funding has not been received:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$current1['iffundingnotreceivedinperiod'], 0, 'L', 1);
$pdf->Ln($linegap);

//Total funding received in this reporting period from original award.
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines('£'.number_format($current1['totalfundingreceived'], 2),$linewidth),$pdf->getNumLines('Total funding received in this reporting period from original contract award*:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Total funding received in this reporting period from original contract award*:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,'£'.number_format($current1['totalfundingreceived'], 2), 0, 'L', 1);
$pdf->Ln($linegap);

//Has project received an extension?:
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines(ucwords($current1['contractextension']),$linewidth),$pdf->getNumLines('Has project received a contract extension?:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Has project received a contract extension?:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,ucwords($current1['contractextension']), 0, 'L', 1);
$pdf->Ln($linegap);

//if yes, why?
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines($current1['whyextensiongranted'],$linewidth),$pdf->getNumLines('If yes, why was extension granted?:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'If yes, why was extension granted?:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$current1['whyextensiongranted'], 0, 'L', 1);
$pdf->Ln($linegap);

//Total value of contract extension.
$pdf->SetFont('times','B',11);
$datastring="";
$datastring = (strlen($current1['totalvalueofextension'])>0 ? '£'.number_format($current1['totalvalueofextension'], 2) : "" );
$rh = 6* max($pdf->getNumLines($datastring,$linewidth),$pdf->getNumLines('Total value of contract extension:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Total value of contract extension:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datastring, 0, 'L', 1);
$pdf->Ln($linegap);

//Expected value of extension
$pdf->SetFont('times','B',11);
$datastring="";
$datastring = (strlen($current1['valueofextensiontounit'])>0 ? '£'.number_format($current1['valueofextensiontounit'], 2) : "" );
$rh = 6* max($pdf->getNumLines($datastring,$linewidth),$pdf->getNumLines('Expected value of contract extension to your unit:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Expected value of contract extension to your unit:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datastring, 0, 'L', 1);
$pdf->Ln($linegap);

//Additional funding received this reporting period from contract extension (if applicable)
$pdf->SetFont('times','B',11);
$datastring="";
$datastring = (strlen($current1['additionalfundingfromcontractextension'])>0 ? '£'.number_format($current1['additionalfundingfromcontractextension'], 2) : "" );
$rh = 6* max($pdf->getNumLines($datastring,$linewidth),$pdf->getNumLines('Additional funding received this reporting period from contract extension (if applicable):',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Additional funding received this reporting period from contract extension (if applicable):', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datastring, 0, 'L', 1);
$pdf->Ln($linegap);

//Does the project meet the criteria for offset?
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines(ucwords($current1['NIHRoffset']),$linewidth),$pdf->getNumLines('Does the project meet the NIHR criteria for offset?:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Does the project meet the NIHR criteria for offset?:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,ucwords($current1['NIHRoffset']), 0, 'L', 1);
$pdf->Ln($linegap);

//end loop
$pdf->AddPage();
}

//Subheading
checkaddpage(60);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.5 Total income from NIHR-funded projects', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);

$mainsql = "SELECT * FROM ctu_report_section3b_inprog WHERE report_id='".$_SESSION['report_id']."'";
$mainqry = mysql_query($mainsql);
$main = mysql_fetch_array($mainqry);
	
			$tfunding = 0;
			$afunding = 0;
			//SQL FIND HERE TO POPULATE ROWS FOR 3.5
			//$totalssql = "SELECT * FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY current_rank";
			
			
			$totalssql = "SELECT SUM(totalfundingreceived) AS tfunding FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."';";
				$totalsqry = mysql_query($totalssql);
				while ($totals = mysql_fetch_array($totalsqry))
				{
					//echo $totals['tfunding'];
					$tfunding = $totals['tfunding'];
					//echo $tfunding.'<br />';
				}
			$totalssql = "SELECT SUM(additionalfundingfromcontractextension) AS afunding FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."';";
				$totalsqry = mysql_query($totalssql);
				while ($totals = mysql_fetch_array($totalsqry))
				{
					//echo $totals['afunding'];
					$afunding = $totals['afunding'];
					//echo $afunding;
				}
				
			$funding = $tfunding + $afunding;
			//echo $funding;
			
			$totalssql = "SELECT SUM(totalfundingreceived) AS nihrfunding FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."' AND NIHRoffset = 'yes';";
				$totalsqry = mysql_query($totalssql);
				while ($totals = mysql_fetch_array($totalsqry))
				{
					//echo $totals['afunding'];
					$nihrfunding = $totals['nihrfunding'];

				}
				if ($nihrfunding == null)
				{
					$nihrfunding = 0;
				}
			
			

//Total unit income from NIHR-funded projects
$datastring="";
$datastring = (strlen($funding)>0 ? '£'.number_format($funding, 2) : "" );
$rh = 6* max($pdf->getNumLines($datastring,$linewidth),$pdf->getNumLines('Total unit income from NIHR funded projects this reporting period:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Total unit income from NIHR funded projects this reporting period:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datastring, 0, 'L', 1);
$pdf->Ln($linegap);

$pdf->SetFont('times','',10);
$pdf->MultiCell(0, 5,'The following figure will be used to calculate the amount that may be deducted from future Support Funding payments (auto-calculated based on figures in 3.4)', 0, 'L' ,0);
$pdf->SetFont('times','B',11);
$pdf->Ln($linegap);
//Total unit income from NIHR-funded projects which meet offset
$datastring="";
$datastring = (strlen($nihrfunding)>0 ? '£'.number_format($nihrfunding, 2) : "" );
$rh = 6* max($pdf->getNumLines($datastring,$linewidth),$pdf->getNumLines('Total unit income from NIHR projects which meet the criteria for offset:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Total unit income from NIHR projects which meet the criteria for offset:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$datastring, 0, 'L', 1);
$pdf->Ln($linegap);

$pdf->SetFont('times','B',10);
$pdf->MultiCell(0, 5,'Disclaimer: Please note that the NIHR will check the figures provided for the offset calculation and ensure that all eligible projects and income are correctly identified. NETSCC will confirm any future payment amounts in due course.', 0, 'L' ,0);
$pdf->SetFont('times','B',11);
$pdf->Ln($linegap);

//Subheading
checkaddpage(60);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->Cell(0, 5,'3.6 Discontinued Projects', 0, 1 ,'L');
$pdf->Ln(5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('times','',10);
$pdf->MultiCell(0,5,'Please list any NIHR projects which were discontinued or closed down in this reporting period. This excludes projects completed as planned.',0,'L',0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->Ln($linegap);

//Table for discontinued projects
$hasborder = false; //flag for fringe case
$lh = 5;

//Get the closed projects from the database.
$closedsql = "SELECT * FROM ctu_report_section3bclosed_inprog WHERE report_id='".$_SESSION['report_id']."' order by closed_rank";
$closedqry = mysql_query($closedsql);
		
$pdf->SetFont('times','',10);
//Set Headings
$pdf->MultiCell(50, 10, 'Project Title', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(25, 10, 'NIHR Programme', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(30, 10, 'NIHR Project Reference', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
$pdf->MultiCell(75, 10, 'Reason for Closure', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');

$pdf->Ln();
while ($closed = mysql_fetch_array($closedqry))

{
	$rowcount = 0;
	$rowcount = max($pdf->getNumLines($closed['title'],40),$pdf->getNumLines($closed['reason'],90));
	
	$startY = $pdf->GetY();
 
	if (($startY + $rowcount * $lh) + $dimensions['bm'] > ($dimensions['hk'])) {
		//this row will cause a page break, draw the bottom border on previous row and give this a top border
		//we could force a page break and rewrite grid headings here
		if ($hasborder) {
			$hasborder = false;
		} else {
			$pdf->Cell(180,0,'','T'); //draw bottom border on previous row
			$pdf->AddPage();
			$pdf->MultiCell(50, 10, 'Project Title', 'LRTB', 'C' , 0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(25, 10, 'NIHR Programme', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(30, 10, 'NIHR Project Reference', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->MultiCell(75, 10, 'Reason for Closure', 'LRTB', 'C',0,0,'','',true,0,false,true,10,'M');
			$pdf->Ln();
		}
		$borders = 'LTR';
	} elseif ((ceil($startY) + $rowcount * $lh) + $dimensions['bm'] == floor($dimensions['hk'])) {
		//fringe case where this cell will just reach the page break
		//draw the cell with a bottom border as we cannot draw it otherwise
		$borders = 'LRB';	
		$hasborder = true; //stops the attempt to draw the bottom border on the next row
	} else {
		//normal cell
		$borders = 'LTR';
	}
 
	//now draw it
	$pdf->MultiCell(50,$rowcount * $lh,$closed['title'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(25,$rowcount * $lh,$closed['programme'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(30,$rowcount * $lh,$closed['reference'],$borders,'C',0,0,'','',true,0,false,true,0,'M');
	$pdf->MultiCell(75,$rowcount * $lh,$closed['reason'],$borders,'L',0,0,'','',true,0,false,true,0,'M');
	
 
	$pdf->Ln();
}
 
$pdf->Cell(180,0,'','T');  //last bottom border
$pdf->Ln();


//Subheading
checkaddpage(40);
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0,5,'3.7 Any further comments on activity related to NIHR applications or proposals and funded project activities. This includes any EOI, bolt-on or add-on studies.',0,'L',0);
$pdf->Ln(5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','',12);
$pdf->Ln($linegap);
$pdf->MultiCell($linewidth+35,0,$main['furthercommentsonnihrapplications'],0,'L',1);
$pdf->Ln($linegap);
/*
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0,5,'3.8 Please provide details on any Intellectual Property (IP) arising as a result of your CTU Support Funding Award.',0,'L',0);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','',12);
$pdf->Ln($linegap);
$pdf->MultiCell($linewidth+35,15,$main['IPdetails'],0,'L',1);
$pdf->Ln($linegap);
*/
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',11);
$pdf->MultiCell(0,5,'3.8 NIHR CTU Support Funding for next 12 months.',0,'L',0);
$pdf->Ln(5);
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor($defaultfill[0],$defaultfill[1],$defaultfill[2]);
$pdf->SetFont('times','B',11);
$pdf->Ln($linegap);

//Would you like to be considered for a change in funding??
$rh = 6* max($pdf->getNumLines(ucwords($main['wouldyoulikechangetofunding']),$linewidth),$pdf->getNumLines('Would you like to be considered for a change in funding?:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Would you like to be considered for a change in funding?:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,ucwords($main['wouldyoulikechangetofunding']), 0, 'L', 1);
$pdf->Ln($linegap);

//if yes, why?
$pdf->SetFont('times','B',11);
$rh = 6* max($pdf->getNumLines($main['fundingchangesupport'],$linewidth),$pdf->getNumLines('If yes, please provide a brief supporting statement:',35));
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'If yes, please provide a brief supporting statement:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$main['fundingchangesupport'], 0, 'L', 1);
$pdf->Ln($linegap);




$pdf->Ln(5);
checkaddpage(60);
//Section heading
$pdf->SetTextColor(0,64,128);
$pdf->SetFont('times','B',14);
$pdf->Cell(0, 5,'Section 4: Further Comments / Other Key Information (optional)', 0, 1 ,'L');

// set a bookmark for the current position
$pdf->Bookmark('Section 4', 0, 0, '', 'B', array(0,64,128));

$pdf->SetFont('times','B',11);
$pdf->SetTextColor(0,0,0);
$pdf->Ln(5);

$sql = "SELECT * FROM ctu_report_section4 WHERE report_id = '".$_SESSION['report_id']."'";
$qry = mysql_query($sql);
$data = mysql_fetch_array($qry);

//if yes, why?
$rh = 6* 6;
checkaddpage($rh);
$pdf->MultiCell(35, $rh, 'Please provide any other information you feel is relevant e.g. key staff publication activity:', 0, 'R', 0,0);
$pdf->SetFont('times','',12);
$pdf->MultiCell($linewidth, $rh,$data['anyfurthercomments'], 0, 'L', 1);
$pdf->Ln($linegap);

//$pdf->SetFont('times', 'I', 14);
//$pdf->Write(0, "You can set PDF Bookmarks using the Bookmark() method.\nYou can set PDF Named Destinations using the setDestination() method.");
//

//Close and output PDF document
ob_clean();
$pdf->Output('CTU_Activity_Report_2013_'.$ctu['ukcrc'].'.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
