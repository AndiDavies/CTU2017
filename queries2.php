<?php
//phpinfo();
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
if (!checkSoton())
{
	exit("You are not authorised to view this page.");
}

//define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

date_default_timezone_set('Europe/London');

/** Include PHPExcel */
require_once '../phpexcel/Classes/PHPExcel.php';

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();

// Set document properties
$objPHPExcel->getProperties()->setCreator("Richard Dudley")
							 ->setLastModifiedBy("Richard Dudley")
							 ->setTitle("CTU Activity Report Full Data Export")
							 ->setSubject("CTU Activity Report Full Data Export")
							 ->setDescription("Complete export of submitted information from the CTU Activity Report round ending October 2013")
							 ->setKeywords("CTU php")
							 ->setCategory("Full Export");
							 
// Add first sheet and rename
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setTitle('Section 1');

//Get Data
$sql="SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
	`ctu_details`.`name`,
	`ctu_report_section1`.`director1title`,
	`ctu_report_section1`.`director1forename`,
	`ctu_report_section1`.`director1surname`,
	`ctu_report_section1`.`director1jobtitle`,
	`ctu_report_section1`.`director1email`,
	`ctu_report_section1`.`director2title`,
	`ctu_report_section1`.`director2forename`,
	`ctu_report_section1`.`director2surname`,
	`ctu_report_section1`.`director2jobtitle`,
	`ctu_report_section1`.`director2email`,
	`ctu_report_section1`.`ctuname`,
	`ctu_report_section1`.`organisation`,
	`ctu_report_section1`.`financecode`,
	`ctu_report_section1`.`address`,
	`ctu_report_section1`.`postcode`,
	`ctu_report_section1`.`telephone`,
	`ctu_report_section1`.`ctupriconname`,
	`ctu_report_section1`.`ctupriconjobtitle`,
	`ctu_report_section1`.`ctupriconemail`,
	`ctu_report_section1`.`ctudevelopments`
FROM `sdonihr`.`ctu_report_section1`  
INNER JOIN `sdonihr`.`ctu_details` 
ON `ctu_report_section1`.`report_id` = `ctu_details`.`ctu_id`
ORDER BY `ctu_details`.`ukcrc`";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'director1title')
			->setCellValue('D1', 'director1forename')
			->setCellValue('E1', 'director1surname')
			->setCellValue('F1', 'director1jobtitle')
			->setCellValue('G1', 'director1email')
			->setCellValue('H1', 'director2title')
			->setCellValue('I1', 'director2forename')
			->setCellValue('J1', 'director2surname')
			->setCellValue('K1', 'director2jobtitle')
			->setCellValue('L1', 'director2email')
			->setCellValue('M1', 'ctuname')
			->setCellValue('N1', 'organisation')
			->setCellValue('O1', 'financecode')
			->setCellValue('P1', 'address')
			->setCellValue('Q1', 'postcode')
			->setCellValue('R1', 'telephone')
			->setCellValue('S1', 'ctupriconname')
			->setCellValue('T1', 'ctupriconjobtitle')
			->setCellValue('U1', 'ctupriconemail')
			->setCellValue('V1', 'ctudevelopments');
//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['director1title'])
				->setCellValue('D'.$rowcount, $result['director1forename'])
				->setCellValue('E'.$rowcount, $result['director1surname'])
				->setCellValue('F'.$rowcount, $result['director1jobtitle'])
				->setCellValue('G'.$rowcount, $result['director1email'])
				->setCellValue('H'.$rowcount, $result['director2title'])
				->setCellValue('I'.$rowcount, $result['director2forename'])
				->setCellValue('J'.$rowcount, $result['director2surname'])
				->setCellValue('K'.$rowcount, $result['director2jobtitle'])
				->setCellValue('L'.$rowcount, $result['director2email'])
				->setCellValue('M'.$rowcount, $result['ctuname'])
				->setCellValue('N'.$rowcount, $result['organisation'])
				->setCellValue('O'.$rowcount, $result['financecode'])
				->setCellValue('P'.$rowcount, $result['address'])
				->setCellValue('Q'.$rowcount, $result['postcode'])
				->setCellValue('R'.$rowcount, $result['telephone'])
				->setCellValue('S'.$rowcount, $result['ctupriconname'])
				->setCellValue('T'.$rowcount, $result['ctupriconjobtitle'])
				->setCellValue('U'.$rowcount, $result['ctupriconemail'])
				->setCellValue('V'.$rowcount, $result['ctudevelopments']);
			
$rowcount++;
}

//Format Sheet 1 Content

//Set Autowidth on column
foreach(range('A','V') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:V1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);



//Add Sheet 2 - Section 2.1 - 2.2
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setTitle('Section 2.1 - 2.2');

$sql="Select
  sdonihr.ctu_details.ukcrc as `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section2current.objective_rank,
  sdonihr.ctu_objectives.objective,
  sdonihr.ctu_objectives.targetdate,
  sdonihr.ctu_report_section2current.achieved,
  sdonihr.ctu_report_section2current.progress
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section2current On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section2current.report_id Inner Join
  sdonihr.ctu_objectives On sdonihr.ctu_report_section2current.report_id =
    sdonihr.ctu_objectives.ctu_id And
    sdonihr.ctu_report_section2current.objective_rank =
    sdonihr.ctu_objectives.objective_rank
Order By
  sdonihr.ctu_details.ukcrc , sdonihr.ctu_report_section2current.objective_rank";
$qry = mysql_query($sql);	


//Output heading row
$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'objective_rank')
			->setCellValue('D1', 'objective')
			->setCellValue('E1', 'targetdate')
			->setCellValue('F1', 'achieved')
			->setCellValue('G1', 'progress');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['objective_rank'])
				->setCellValue('D'.$rowcount, $result['objective'])
				->setCellValue('E'.$rowcount, date('Y-m-d',strtotime($result['targetdate'])))
				->setCellValue('F'.$rowcount, $result['achieved'])
				->setCellValue('G'.$rowcount, $result['progress']);		

$rowcount++;
}

//Format Sheet 2 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(100);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('G1:G'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');

//Add Sheet 3 - Section 2.3
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->setTitle('Section 2.3');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section2planned.objective_rank,
  sdonihr.ctu_report_section2planned.objective,
  sdonihr.ctu_report_section2planned.targetdate
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section2planned On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section2planned.report_id
Order By
  sdonihr.ctu_details.ukcrc, sdonihr.ctu_report_section2planned.objective_rank";
	
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'objective_rank')
			->setCellValue('D1', 'objective')
			->setCellValue('E1', 'targetdate');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(2)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['objective_rank'])
				->setCellValue('D'.$rowcount, $result['objective'])
				->setCellValue('E'.$rowcount, date('Y-m-d',strtotime($result['targetdate'])));

$rowcount++;
}

//Format Sheet 3 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Add Sheet 4 - Section 2.4 - 2.9
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->setTitle('Section 2.4 - 2.9');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section2.activitesdevwidernihr,
  sdonihr.ctu_report_section2.capacityactivity,
  sdonihr.ctu_report_section2.trainingreceivedbyctustaff,
  sdonihr.ctu_report_section2.fundingfromhei,
  sdonihr.ctu_report_section2.fundingfromnhstrusts,
  sdonihr.ctu_report_section2.otherfunding,
  sdonihr.ctu_report_section2.variousfundingsources
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section2 On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section2.report_id
Order By
  `CTU ID`";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(3)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', '2.4 activitesdevwidernihr')
			->setCellValue('D1', '2.5 capacityactivity')
			->setCellValue('E1', '2.6 trainingreceivedbyctustaff')
			->setCellValue('F1', '2.7 fundingfromhei')
			->setCellValue('G1', '2.8 fundingfromnhstrusts')
			->setCellValue('H1', '2.9 otherfunding')
			->setCellValue('I1', '2.9b variousfundingsources');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(3)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['activitesdevwidernihr'])
				->setCellValue('D'.$rowcount, $result['capacityactivity'])
				->setCellValue('E'.$rowcount, $result['trainingreceivedbyctustaff'])
				->setCellValue('F'.$rowcount, $result['fundingfromhei'])
				->setCellValue('G'.$rowcount, $result['fundingfromnhstrusts'])
				->setCellValue('H'.$rowcount, $result['otherfunding'])
				->setCellValue('I'.$rowcount, $result['variousfundingsources']);
			
$rowcount++;
}

//Format Sheet 4 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(100);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}

//Set number format for currency rows
$objPHPExcel->getActiveSheet()->getStyle('F1:H'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');

//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:I'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Add Sheet 5 - Section 3.1
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(4);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.1');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section3.previousoutlineproposalsshortlisted,
  sdonihr.ctu_report_section3.previousoutlineproposalsrejected,
  sdonihr.ctu_report_section3.currentoutlineproposalssubmitted,
  sdonihr.ctu_report_section3.currentoutlineproposalsshortlisted,
  sdonihr.ctu_report_section3.currentoutlineproposalsrejected,
  sdonihr.ctu_report_section3.currentoutlineproposalsdecisionpending,
  sdonihr.ctu_report_section3.outlineproposalsdetail,
  sdonihr.ctu_report_section3.previousfullproposalsfunded,
  sdonihr.ctu_report_section3.previousfullproposalsrejected,
  sdonihr.ctu_report_section3.currentfullproposalssubmitted,
  sdonihr.ctu_report_section3.currentfullproposalsfunded,
  sdonihr.ctu_report_section3.currentfullproposalsfundwithchange,
  sdonihr.ctu_report_section3.currentfullproposalsdeferred,
  sdonihr.ctu_report_section3.currentfullproposalsresubmitting,
  sdonihr.ctu_report_section3.currentfullproposalstransferred,
  sdonihr.ctu_report_section3.currentfullproposalsrejected,
  sdonihr.ctu_report_section3.currentfullproposalsdecisionpending,
  sdonihr.ctu_report_section3.fullproposalsdetail,
  sdonihr.ctu_report_section3.nihrprojectsstartedduringperiod,
  sdonihr.ctu_report_section3.totalcurrentnihrprojects
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section3 On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section3.report_id
Order By
  `CTU ID`";

$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(4)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', '3.1A(i) previousoutlineproposalsshortlisted')
			->setCellValue('D1', '3.1A(ii) previousoutlineproposalsrejected')
			->setCellValue('E1', '3.1B(i) currentoutlineproposalssubmitted')
			->setCellValue('F1', '3.1B(ii) currentoutlineproposalsshortlisted')
			->setCellValue('G1', '3.1B(iii) currentoutlineproposalsrejected')
			->setCellValue('H1', '3.1B(iv) currentoutlineproposalsdecisionpending')
			->setCellValue('I1', '3.1B(details) outlineproposalsdetail')
			->setCellValue('J1', '3.1C(i) previousfullproposalsfunded')
			->setCellValue('K1', '3.1C(ii) previousfullproposalsrejected')
			->setCellValue('L1', '3.1D(i) currentfullproposalssubmitted')
			->setCellValue('M1', '3.1D(ii) currentfullproposalsfunded')
			->setCellValue('N1', '3.1D(iii) currentfullproposalsfundwithchange')
			->setCellValue('O1', '3.1D(iv) currentfullproposalsdeferred')
			->setCellValue('P1', '3.1D(v) currentfullproposalsresubmitting')
			->setCellValue('Q1', '3.1D(vi) currentfullproposalstransferred')
			->setCellValue('R1', '3.1D(vii) currentfullproposalsrejected')
			->setCellValue('S1', '3.1D(viii) currentfullproposalsdecisionpending')
			->setCellValue('T1', '3.1D(details) fullproposalsdetail')
			->setCellValue('U1', '3.1E nihrprojectsstartedduringperiod')
			->setCellValue('V1', '3.1F totalcurrentnihrprojects');

//initialise counter for subsquent rows
$rowcount = 2;		

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(4)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['previousoutlineproposalsshortlisted'])
				->setCellValue('D'.$rowcount, $result['previousoutlineproposalsrejected'])
				->setCellValue('E'.$rowcount, $result['currentoutlineproposalssubmitted'])
				->setCellValue('F'.$rowcount, $result['currentoutlineproposalsshortlisted'])
				->setCellValue('G'.$rowcount, $result['currentoutlineproposalsrejected'])
				->setCellValue('H'.$rowcount, $result['currentoutlineproposalsdecisionpending'])
				->setCellValue('I'.$rowcount, $result['outlineproposalsdetail'])
				->setCellValue('J'.$rowcount, $result['previousfullproposalsfunded'])
				->setCellValue('K'.$rowcount, $result['previousfullproposalsrejected'])
				->setCellValue('L'.$rowcount, $result['currentfullproposalssubmitted'])
				->setCellValue('M'.$rowcount, $result['currentfullproposalsfunded'])
				->setCellValue('N'.$rowcount, $result['currentfullproposalsfundwithchange'])
				->setCellValue('O'.$rowcount, $result['currentfullproposalsdeferred'])
				->setCellValue('P'.$rowcount, $result['currentfullproposalsresubmitting'])
				->setCellValue('Q'.$rowcount, $result['currentfullproposalstransferred'])
				->setCellValue('R'.$rowcount, $result['currentfullproposalsrejected'])
				->setCellValue('S'.$rowcount, $result['currentfullproposalsdecisionpending'])
				->setCellValue('T'.$rowcount, $result['fullproposalsdetail'])
				->setCellValue('U'.$rowcount, $result['nihrprojectsstartedduringperiod'])
				->setCellValue('V'.$rowcount, $result['totalcurrentnihrprojects']);
$rowcount++;
}

//Format Sheet 4 Content

//Set Autowidth on column
foreach(range('A','H') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(100);
foreach(range('J','S') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('T')->setWidth(100);
foreach(range('U','V') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}

//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('T1:T'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:V'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Add Sheet 6 - Section 3.2
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(5);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.2');


//Get details
$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3outline`.`outline_rank`,
    `ctu_report_section3outline`.`title`,
    `ctu_report_section3outline`.`programme`,
    `ctu_report_section3outline`.`reference`,
    `ctu_report_section3outline`.`submitdate`,
    `ctu_report_section3outline`.`staffinput`,
    `ctu_report_section3outline`.`expectedvalue`,
    `ctu_report_section3outline`.`status`
FROM `sdonihr`.`ctu_report_section3outline`  
INNER JOIN `sdonihr`.`ctu_details` 
ON `ctu_report_section3outline`.`report_id` = `ctu_details`.`ctu_id`
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3outline`.`outline_rank`;";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(5)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'Rank')
			->setCellValue('D1', 'Title')
			->setCellValue('E1', 'Programme')
			->setCellValue('F1', 'Reference')
			->setCellValue('G1', 'Submit Date')
			->setCellValue('H1', 'Staff Input')
			->setCellValue('I1', 'Expected Value')
			->setCellValue('J1', 'Status');

//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(5)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['outline_rank'])
				->setCellValue('D'.$rowcount, $result['title'])
				->setCellValue('E'.$rowcount, $result['programme'])
				->setCellValue('F'.$rowcount, $result['reference'])
				->setCellValue('G'.$rowcount, $result['submitdate'])
				->setCellValue('H'.$rowcount, $result['staffinput'])
				->setCellValue('I'.$rowcount, $result['expectedvalue'])
				->setCellValue('J'.$rowcount, $result['status']);
$rowcount++;
}

//FORMAT CONTENT

//Set Autowidth on column
foreach(range('A','J') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:J1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1:J'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);


//Set number format for currency rows
$objPHPExcel->getActiveSheet()->getStyle('I1:I'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');

//Add Sheet 7 - Section 3.3
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(6);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.3');

//Get Sheet 7 data

//Get details
$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3full`.`full_rank`,
    `ctu_report_section3full`.`title`,
    `ctu_report_section3full`.`nihrprojectref`,
	`ctu_report_section3full`.`programme`,
    `ctu_report_section3full`.`datesubmitted`,
	`ctu_report_section3full`.`duration`,
	`ctu_report_section3full`.`plannedrecruitmenttotal`,
	`ctu_report_section3full`.`numberofprojectsites`,
	`ctu_report_section3full`.`intmultisite`,
	`ctu_report_section3full`.`expectedinput`,
	`ctu_report_section3full`.`currentstatus`,
    `ctu_report_section3full`.`estimatedoractualstartdate`,
	`ctu_report_section3full`.`isstartdateestimated`,
	`ctu_report_section3full`.`totalcost`,
	`ctu_report_section3full`.`expectedvalue`,
	`ctu_report_section3full`.`estimatedstaffcosts`,
	`ctu_report_section3full`.`estimatednonstaffcosts`,
	`ctu_report_section3full`.`nonstaffdesc`    
FROM `sdonihr`.`ctu_report_section3full`  
INNER JOIN `sdonihr`.`ctu_details` 
ON `ctu_report_section3full`.`report_id` = `ctu_details`.`ctu_id`
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3full`.`full_rank`;";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(6)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'Rank')
			->setCellValue('D1', 'Title')
			->setCellValue('E1', 'NIHR Project Ref')
			->setCellValue('F1', 'Programme')
			->setCellValue('G1', 'Submit Date')
			->setCellValue('H1', 'Duration (months)')
			->setCellValue('I1', 'Planned Recruitment')
			->setCellValue('J1', 'No. Proj Sites')
			->setCellValue('K1', 'Is International m-site?')
			->setCellValue('L1', 'Expected Staff Input')
			->setCellValue('M1', 'Current Status')
			->setCellValue('N1', 'Est. or Actual Start Date')
			->setCellValue('O1', 'Start Date Estimated?')
			->setCellValue('P1', 'Total Cost')
			->setCellValue('Q1', 'Expected Value')
			->setCellValue('R1', 'Est. Staff Costs')
			->setCellValue('S1', 'Est. Non-staff Costs')
			->setCellValue('T1', 'Non-staff Costs Description');

//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(6)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['full_rank'])
				->setCellValue('D'.$rowcount, $result['title'])
				->setCellValue('E'.$rowcount, $result['nihrprojectref'])
				->setCellValue('F'.$rowcount, $result['programme'])
				->setCellValue('G'.$rowcount, date('Y-m-d',strtotime($result['datesubmitted'])))
				->setCellValue('H'.$rowcount, $result['duration'])
				->setCellValue('I'.$rowcount, $result['plannedrecruitmenttotal'])
				->setCellValue('J'.$rowcount, $result['numberofprojectsites'])
				->setCellValue('K'.$rowcount, $result['intmultisite'])
				->setCellValue('L'.$rowcount, $result['expectedinput'])
				->setCellValue('M'.$rowcount, $result['currentstatus'])
				->setCellValue('N'.$rowcount, date('Y-m-d',strtotime($result['estimatedoractualstartdate'])))
				->setCellValue('O'.$rowcount, $result['isstartdateestimated'])
				->setCellValue('P'.$rowcount, $result['totalcost'])
				->setCellValue('Q'.$rowcount, $result['expectedvalue'])
				->setCellValue('R'.$rowcount, $result['estimatedstaffcosts'])
				->setCellValue('S'.$rowcount, $result['estimatednonstaffcosts'])
				->setCellValue('T'.$rowcount, $result['nonstaffdesc']);
$rowcount++;
}

//FORMAT CONTENT

//Set Autowidth on column
foreach(range('A','T') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1:T'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);


//Set number format for currency rows
$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('R1:R'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('S1:S'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');



//Add Sheet 8 - Section 3.4
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(7);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.4');

//Get Sheet 3 data

//Get details
$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3bcurrent`.`current_rank`,
    `ctu_report_section3bcurrent`.`title`,
	`ctu_report_section3bcurrent`.`programme`,
    `ctu_report_section3bcurrent`.`nihrprojectref`,
	`ctu_report_section3bcurrent`.`startdate`,
	`ctu_report_section3bcurrent`.`duration`,
	`ctu_report_section3bcurrent`.`currentstatus`,
	`ctu_report_section3bcurrent`.`plannedrecruitmenttotal`,
	`ctu_report_section3bcurrent`.`numberofprojectsites`,
	`ctu_report_section3bcurrent`.`intmultisite`,
	`ctu_report_section3bcurrent`.`expectedinput`,
	`ctu_report_section3bcurrent`.`totalcost`,
	`ctu_report_section3bcurrent`.`expectedvalue`,
	`ctu_report_section3bcurrent`.`estimatedstaffcosts`,
	`ctu_report_section3bcurrent`.`estimatednonstaffcosts`,
	`ctu_report_section3bcurrent`.`nonstaffdesc`,  
    `ctu_report_section3bcurrent`.`fundingreceivedthisperiod`,
	`ctu_report_section3bcurrent`.`iffundingnotreceivedinperiod`,
	`ctu_report_section3bcurrent`.`totalfundingreceived`,
	`ctu_report_section3bcurrent`.`contractextension`,
	`ctu_report_section3bcurrent`.`whyextensiongranted`,
	`ctu_report_section3bcurrent`.`totalvalueofextension`,
	`ctu_report_section3bcurrent`.`valueofextensiontounit`,
	`ctu_report_section3bcurrent`.`additionalfundingfromcontractextension`,
	`ctu_report_section3bcurrent`.`NIHRoffset`
	  
FROM `sdonihr`.`ctu_report_section3bcurrent`  
INNER JOIN `sdonihr`.`ctu_details` 
ON `ctu_report_section3bcurrent`.`report_id` = `ctu_details`.`ctu_id`
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3bcurrent`.`current_rank`;";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(7)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'Rank')
			->setCellValue('D1', 'Title')
			->setCellValue('E1', 'NIHR Project Ref')
			->setCellValue('F1', 'Programme')
			->setCellValue('G1', 'Start Date')
			->setCellValue('H1', 'Duration (months)')
			->setCellValue('I1', 'Current Status')
			->setCellValue('J1', 'Planned Recruitment')
			->setCellValue('K1', 'No. Proj Sites')
			->setCellValue('L1', 'Is International m-site?')
			->setCellValue('M1', 'Expected Staff Input')
			->setCellValue('N1', 'Total Cost')
			->setCellValue('O1', 'Expected Value')
			->setCellValue('P1', 'Est. Staff Costs')
			->setCellValue('Q1', 'Est. Non-staff Costs')
			->setCellValue('R1', 'Non-staff Costs Description')
			->setCellValue('S1', 'Was funding received this period?')
			->setCellValue('T1', 'If funding not received this period, why?')
			->setCellValue('U1', 'Total funding this period from original award')
			->setCellValue('V1', 'Contract extension?')
			->setCellValue('W1', 'If contract extension, why?')
			->setCellValue('X1', 'Total value of contract extension')
			->setCellValue('Y1', 'Expected value of contract extension to unit')
			->setCellValue('Z1', 'Additional funding this period from extension')
			->setCellValue('AA1', 'Does project meet NIHR offset criteria?');

//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(7)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['current_rank'])
				->setCellValue('D'.$rowcount, $result['title'])
				->setCellValue('E'.$rowcount, $result['nihrprojectref'])
				->setCellValue('F'.$rowcount, $result['programme'])
				->setCellValue('G'.$rowcount, date('Y-m-d',strtotime($result['startdate'])))
				->setCellValue('H'.$rowcount, $result['duration'])
				->setCellValue('I'.$rowcount, $result['currentstatus'])
				->setCellValue('J'.$rowcount, $result['plannedrecruitmenttotal'])
				->setCellValue('K'.$rowcount, $result['numberofprojectsites'])
				->setCellValue('L'.$rowcount, $result['intmultisite'])
				->setCellValue('M'.$rowcount, $result['expectedinput'])
				->setCellValue('N'.$rowcount, $result['totalcost'])
				->setCellValue('O'.$rowcount, $result['expectedvalue'])
				->setCellValue('P'.$rowcount, $result['estimatedstaffcosts'])
				->setCellValue('Q'.$rowcount, $result['estimatednonstaffcosts'])
				->setCellValue('R'.$rowcount, $result['nonstaffdesc'])
				->setCellValue('S'.$rowcount, $result['fundingreceivedthisperiod'])
				->setCellValue('T'.$rowcount, $result['iffundingnotreceivedinperiod'])
				->setCellValue('U'.$rowcount, $result['totalfundingreceived'])
				->setCellValue('V'.$rowcount, $result['contractextension'])
				->setCellValue('W'.$rowcount, $result['whyextensiongranted'])
				->setCellValue('X'.$rowcount, $result['totalvalueofextension'])
				->setCellValue('Y'.$rowcount, $result['valueofextensiontounit'])
				->setCellValue('Z'.$rowcount, $result['additionalfundingfromcontractextension'])
				->setCellValue('AA'.$rowcount, $result['NIHRoffset']);
$rowcount++;
}

//FORMAT CONTENT

//Set Autowidth on column
foreach(range('A','Z') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}
$objPHPExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:AA1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('H1:H'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('E1:E'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
$objPHPExcel->getActiveSheet()->getStyle('A1:AA'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);


//Set number format for currency rows
$objPHPExcel->getActiveSheet()->getStyle('N1:N'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('O1:O'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('P1:P'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('Q1:Q'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('U1:U'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('X1:X'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('Y1:Y'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('Z1:Z'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');

//Add Sheet 9 - Section 3.5
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(8);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.5');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section3b.totalunitincomefromnihrfundedprojects,
  sdonihr.ctu_report_section3b.totalunitincomefromnihrfundedprojectsmeetoffset
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section3b On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section3b.report_id
Order By
  `CTU ID`";
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(8)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'Total Unit Income from NIHR Funded Projects')
			->setCellValue('D1', 'Total Unit Income from NIHR Funded Projects which meet NIHR Offset Criteria');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	$objPHPExcel->setActiveSheetIndex(8)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['totalunitincomefromnihrfundedprojects'])
				->setCellValue('D'.$rowcount, $result['totalunitincomefromnihrfundedprojectsmeetoffset']);

$rowcount++;
}

//Format Sheet 9 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:D'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');
$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowcount)->getNumberFormat()->setFormatCode('"£"#,##0.00_-');

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Add Sheet 10 - Section 3.6
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(9);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.6');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section3bclosed.closed_rank,
  sdonihr.ctu_report_section3bclosed.title,
  sdonihr.ctu_report_section3bclosed.programme,
  sdonihr.ctu_report_section3bclosed.reference,
  sdonihr.ctu_report_section3bclosed.reason
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section3bclosed On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section3bclosed.report_id
Order By
  `CTU ID`,
  sdonihr.ctu_report_section3bclosed.closed_rank";
  
$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(9)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', 'Discontinued rank')
			->setCellValue('D1', 'Project Title')
			->setCellValue('E1', 'Programme')
			->setCellValue('F1', 'NIHR Project Reference')
			->setCellValue('G1', 'Reason for Closure');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
	if (strlen($result['title'])>0)
	{	$objPHPExcel->setActiveSheetIndex(9)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['closed_rank'])
				->setCellValue('D'.$rowcount, $result['title'])
				->setCellValue('E'.$rowcount, $result['programme'])
				->setCellValue('F'.$rowcount, $result['reference'])
				->setCellValue('G'.$rowcount, $result['reason']);
		$rowcount++;
	}

}

//Format Sheet 9 Content

//Set Autowidth on column
foreach(range('A','G') as $columnID)
{
	$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:G'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Add Sheet 11 - Section 3.7 - 3.9
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(10);
$objPHPExcel->getActiveSheet()->setTitle('Section 3.7 - 3.9');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section3b.furthercommentsonnihrapplications,
  sdonihr.ctu_report_section3b.IPdetails,
  sdonihr.ctu_report_section3b.wouldyoulikechangetofunding,
  sdonihr.ctu_report_section3b.fundingchangesupport
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section3b On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section3b.report_id
Order By
  `CTU ID`"; 

$qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(10)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', '3.7 furthercommentsonnihrapplications')
			->setCellValue('D1', '3.8 IPdetails')
			->setCellValue('E1', '3.9 wouldyoulikechangetofunding')
			->setCellValue('F1', '3.9(details) fundingchangesupport');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
		$objPHPExcel->setActiveSheetIndex(10)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['furthercommentsonnihrapplications'])
				->setCellValue('D'.$rowcount, $result['IPdetails'])
				->setCellValue('E'.$rowcount, $result['wouldyoulikechangetofunding'])
				->setCellValue('F'.$rowcount, $result['fundingchangesupport']);
		$rowcount++;

}

//Format Sheet 10 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(100);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('D1:D'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('F1:F'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:F'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');

//Add Sheet 12 - Section 4
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(11);
$objPHPExcel->getActiveSheet()->setTitle('Section 4');

$sql="Select
  sdonihr.ctu_details.ukcrc As `CTU ID`,
  sdonihr.ctu_details.name,
  sdonihr.ctu_report_section4.anyfurthercomments
From
  sdonihr.ctu_details Inner Join
  sdonihr.ctu_report_section4 On sdonihr.ctu_details.ctu_id =
    sdonihr.ctu_report_section4.report_id
Order By
  `CTU ID`";
  
 $qry = mysql_query($sql);

//Output heading row
$objPHPExcel->setActiveSheetIndex(11)
			->setCellValue('A1', 'UKCRC')
			->setCellValue('B1', 'CTU')
			->setCellValue('C1', '4 Further Comments / Other Key Information');
	//initialise counter for subsquent rows
$rowcount = 2;			

//loop through query results outputting data into corresponding cells
while ($result=mysql_fetch_array($qry))
{
		$objPHPExcel->setActiveSheetIndex(11)
				->setCellValue('A'.$rowcount, $result['CTU ID'])
				->setCellValue('B'.$rowcount, $result['name'])
				->setCellValue('C'.$rowcount, $result['anyfurthercomments']);
		$rowcount++;

}

//Format Sheet 10 Content

//Set Autowidth on column
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(100);

foreach( range(0,$rowcount) as $heightcount)
{
$objPHPExcel->getActiveSheet()->getRowDimension($heightcount)->setRowHeight(-1);
}


//Set Fills, Styles and Alignment
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFill()->getStartColor()->setARGB('FFECEFF0');
$objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('C1:C'.$rowcount)->getAlignment()->setWrapText(true);
$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$rowcount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:C'.$rowcount)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);

//return active cell to A1
$objPHPExcel->getActiveSheet()->setSelectedCell('A1');


//Reset Active Sheet back to zero
$objPHPExcel->setActiveSheetIndex(0);
//Deliver to the browser
header("Pragma: public");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Content-Type: application/force-download");
header("Content-Type: application/octet-stream");
header("Content-Type: application/download");;
header("Content-Disposition: attachment;filename=queries.xls");
header("Content-Transfer-Encoding: binary ");

//$objWriter = new PHPExcel_Writer_Excel5($objPHPExcel);
//$objWriter->save("queries.xls");
PHPExcel_Shared_Font::setAutoSizeMethod(PHPExcel_Shared_Font::AUTOSIZE_METHOD_APPROX);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');
exit;