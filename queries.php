<?php
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
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CTU Activity Report Form Simpl</title>
	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="general-light.php" />
	
	<!--[if lt IE 9]>
	    	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
</head>
<body>
<form action="adminprocess1.php" method="post" class="general wide">
<div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
</div>
<h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report Administration</h1>
<p>&nbsp;</p>
<p class='emphasis large'><b>Simple Reports</b></p><br />
<p>Section 3.2 (Outlines) for CCF</p><br />
<?php
//Get details

$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3outline_inprog`.`outline_rank`,
    `ctu_report_section3outline_inprog`.`title`,
    `ctu_report_section3outline_inprog`.`programme`,
    `ctu_report_section3outline_inprog`.`reference`,
    `ctu_report_section3outline_inprog`.`submitdate`,
    `ctu_report_section3outline_inprog`.`staffinput`,
    `ctu_report_section3outline_inprog`.`expectedvalue`,
    `ctu_report_section3outline_inprog`.`status`
FROM `sdonihr`.`ctu_report_section3outline_inprog`  
Inner Join
sdonihr.ctu_activityreporting
	On sdonihr.ctu_report_section3outline_inprog.report_id = sdonihr.ctu_activityreporting.report_id
Inner Join sdonihr.ctu_details
	On sdonihr.ctu_activityreporting.ctu_id = sdonihr.ctu_details.ctu_id
WHERE ctu_activityreporting.period = '201415' AND ctu_report_section3outline_inprog.programme IN ( 'RfPB','PGfAR','i4i')
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3outline_inprog`.`outline_rank`;";


$qry = mysql_query($sql);

?>
<table class="ctutable ctutable-large" cellpadding="0">
<thead>
	<tr>
    	<th>UKCRC</th>
        <th>CTU</th>
        <th>rank</th>
        <th>Title</th>
        <th>Programme</th>
        <th>Reference</th>
        <th>Submit Date</th>
        <th>Staff Input</th>
        <th>Expected Value</th>
        <th>Status</th>
    </tr>
</thead>
<tbody>
	<?php
    while ($result=mysql_fetch_array($qry))
{
	?>
    <tr>
    	<td><?=$result['CTU ID']?></td>
       	<td><?=$result['name']?></td>
        <td><?=$result['outline_rank']?></td>
        <td><?=$result['title']?></td>
        <td><?=$result['programme']?></td>
        <td><?=$result['reference']?></td>
        <td><?=$result['submitdate']?></td>
        <td><?=$result['staffinput']?></td>
        <td><?=$result['expectedvalue']?></td>
        <td><?=$result['status']?></td>
    </tr>
<?php
}
	?>
</tbody>
</table>			
<p>Section 3.3 (Fulls) for CCF</p><br />
<?php
//Get details
$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3full_inprog`.`full_rank`,
    `ctu_report_section3full_inprog`.`title`,
    `ctu_report_section3full_inprog`.`nihrprojectref`,
	`ctu_report_section3full_inprog`.`programme`,
    `ctu_report_section3full_inprog`.`datesubmitted`,
	`ctu_report_section3full_inprog`.`duration`,
	`ctu_report_section3full_inprog`.`plannedrecruitmenttotal`,
	`ctu_report_section3full_inprog`.`numberofprojectsites`,
	`ctu_report_section3full_inprog`.`intmultisite`,
	`ctu_report_section3full_inprog`.`expectedinput`,
	`ctu_report_section3full_inprog`.`currentstatus`,
    `ctu_report_section3full_inprog`.`estimatedoractualstartdate`,
	`ctu_report_section3full_inprog`.`isstartdateestimated`,
	`ctu_report_section3full_inprog`.`totalcost`,
	`ctu_report_section3full_inprog`.`expectedvalue` 
FROM `sdonihr`.`ctu_report_section3full_inprog`  
Inner Join
sdonihr.ctu_activityreporting
	On sdonihr.ctu_report_section3full_inprog.report_id = sdonihr.ctu_activityreporting.report_id
Inner Join sdonihr.ctu_details
	On sdonihr.ctu_activityreporting.ctu_id = sdonihr.ctu_details.ctu_id
WHERE ctu_activityreporting.period = '201415' AND ctu_report_section3full_inprog.programme IN ( 'RfPB','PGfAR','i4i')
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3full_inprog`.`full_rank`;";
$qry = mysql_query($sql);




?>
<table class="ctutable ctutable-large" cellpadding="0" style="overflow:auto;">
<thead>
	<tr>
    	<th>UKCRC</th>
        <th>CTU</th>
        <th>rank</th>
        <th>Title</th>
        <th>NIHR Project Ref</th>
        <th>Programme</th>
        <th>Submit Date</th>
        <th>Duration</th>
        <th>Planned Recruitment Total</th>
        <th>Number project sites</th>
        <th>International Multi-site?</th>
        <th>Expected staff input</th>
        <th>Current Status</th>
        <th>Expected or actual Start Date</th>
        <th>Start Date estimated?</th>
        <th>Total cost</th>
        <th>Expected value</th>
        <th>Estimated staff costs</th>
        <th>Estimated non-staff costs</th>
        <th>Description of non-staff costs</th>
    </tr>
</thead>
<tbody>
	<?php
    while ($result=mysql_fetch_array($qry))
{
	?>
    <tr>
    	<td><?=$result['CTU ID']?></td>
       	<td><?=$result['name']?></td>
        <td><?=$result['full_rank']?></td>
        <td><?=$result['title']?></td>
        <td><?=$result['nihrprojectref']?></td>
        <td><?=$result['programme']?></td>
        <td><?=$result['datesubmitted']?></td>
        <td><?=$result['duration']?></td>
        <td><?=$result['plannedrecruitmenttotal']?></td>
        <td><?=$result['numberofprojectsites']?></td>
        <td><?=$result['intmultisite']?></td>
        <td><?=$result['expectedinput']?></td>
        <td><?=$result['currentstatus']?></td>
        <td><?=$result['estimatedoractualstartdate']?></td>
        <td><?=$result['isstartdateestimated']?></td>
        <td><?=$result['totalcost']?></td>
        <td><?=$result['expectedvalue']?></td>
    </tr>
<?php
}
	?>
</tbody>
</table>			
<p>Section 3.4 (Current) for CCF</p><br />

<?php
$sql = "SELECT 
	`ctu_details`.`ukcrc` AS 'CTU ID',
    `ctu_details`.`name`,
    `ctu_report_section3bcurrent_inprog`.`current_rank`,
    `ctu_report_section3bcurrent_inprog`.`title`,
	`ctu_report_section3bcurrent_inprog`.`programme`,
    `ctu_report_section3bcurrent_inprog`.`nihrprojectref`,
	`ctu_report_section3bcurrent_inprog`.`startdate`,
	`ctu_report_section3bcurrent_inprog`.`duration`,
	`ctu_report_section3bcurrent_inprog`.`currentstatus`,
	`ctu_report_section3bcurrent_inprog`.`plannedrecruitmenttotal`,
	`ctu_report_section3bcurrent_inprog`.`numberofprojectsites`,
	`ctu_report_section3bcurrent_inprog`.`intmultisite`,
	`ctu_report_section3bcurrent_inprog`.`expectedinput`,
	`ctu_report_section3bcurrent_inprog`.`totalcost`,
	`ctu_report_section3bcurrent_inprog`.`expectedvalue`,
	`ctu_report_section3bcurrent_inprog`.`estimatedstaffcosts`,
	`ctu_report_section3bcurrent_inprog`.`estimatednonstaffcosts`,
	`ctu_report_section3bcurrent_inprog`.`nonstaffdesc`,  
    `ctu_report_section3bcurrent_inprog`.`fundingreceivedthisperiod`,
	`ctu_report_section3bcurrent_inprog`.`iffundingnotreceivedinperiod`,
	`ctu_report_section3bcurrent_inprog`.`totalfundingreceived`,
	`ctu_report_section3bcurrent_inprog`.`contractextension`,
	`ctu_report_section3bcurrent_inprog`.`whyextensiongranted`,
	`ctu_report_section3bcurrent_inprog`.`totalvalueofextension`,
	`ctu_report_section3bcurrent_inprog`.`valueofextensiontounit`,
	`ctu_report_section3bcurrent_inprog`.`additionalfundingfromcontractextension`,
	`ctu_report_section3bcurrent_inprog`.`NIHRoffset`
	  
FROM `sdonihr`.`ctu_report_section3bcurrent_inprog`  
Inner Join
sdonihr.ctu_activityreporting
	On sdonihr.ctu_report_section3bcurrent_inprog.report_id = sdonihr.ctu_activityreporting.report_id
Inner Join sdonihr.ctu_details
	On sdonihr.ctu_activityreporting.ctu_id = sdonihr.ctu_details.ctu_id
WHERE ctu_activityreporting.period = '201415' AND ctu_report_section3bcurrent_inprog.programme IN ( 'RfPB','PGfAR','i4i')
ORDER BY `ctu_details`.`ukcrc` , `ctu_report_section3bcurrent_inprog`.`current_rank`;";

$qry = mysql_query($sql);
?>
<table class="ctutable ctutable-large" cellpadding="0" style="overflow:auto;">
<thead>
	<tr>
    	<th>UKCRC</th>
        <th>CTU</th>
        <th>rank</th>
        <th>Programme</th>
        <th>NIHR Project Ref</th>
        <th>Title</th>
        <th>Start Date</th>
        <th>Duration</th>
        <th>Current Status</th>
        <th>Planned Recruitment Total</th>
        <th>Number project sites</th>
        <th>International Multi-site?</th>
        <th>Expected staff input</th>
        <th>Total cost</th>
        <th>Expected value</th>
        <th>Estimated staff costs</th>
        <th>Estimated non-staff costs</th>
        <th>Description of non-staff costs</th>
        <th>Funding received this period</th>
        <th>If funding not received this period, why?</th>
		<th>Total funding receieved this period from original award</th>
        <th>Has project received a contract extension</th>
        <th>If extension granted, why?</th>
        <th>Total value of extension</th>
        <th>Expected value of extension to your unit</th>
        <th>Additional funding from extension</th>
        <th>Does the project meet NIHR offset criteria</th>
    </tr>
</thead>
<tbody>
	<?php
    while ($result=mysql_fetch_array($qry))
{
	?>
    <tr>
    	<td><?=$result['CTU ID']?></td>
       	<td><?=$result['name']?></td>
        <td><?=$result['current_rank']?></td>
        <td><?=$result['programme']?></td>
        <td><?=$result['nihrprojectref']?></td>
        <td><?=$result['title']?></td>
        <td><?=$result['startdate']?></td>
        <td><?=$result['duration']?></td>
        <td><?=$result['currentstatus']?></td>
        <td><?=$result['plannedrecruitmenttotal']?></td>
        <td><?=$result['numberofprojectsites']?></td>
        <td><?=$result['intmultisite']?></td>
        <td><?=$result['expectedinput']?></td>
        <td><?=$result['totalcost']?></td>
        <td><?=$result['expectedvalue']?></td>
        <td><?=$result['estimatedstaffcosts']?></td>
        <td><?=$result['estimatednonstaffcosts']?></td>
        <td><?=$result['nonstaffdesc']?></td>
        <td><?=$result['fundingreceivedthisperiod']?></td>
        <td><?=$result['iffundingnotreceivedinperiod']?></td>
        <td><?=$result['totalfundingreceived']?></td>
        <td><?=$result['contractextension']?></td>
        <td><?=$result['whyextensiongranted']?></td>
        <td><?=$result['totalvalueofextension']?></td>
        <td><?=$result['valueofextensiontounit']?></td>
        <td><?=$result['additionalfundingfromcontractextension']?></td>
        <td><?=$result['NIHRoffset']?></td>
      
    </tr>
<?php
}
	?>
</tbody>
</table>	
  
	


</form>

      <div id="dialog-confirm" title="DOH!">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 5px 0;"></span>This is the live system. You cannot delete things!</p>
    </div>




<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
<script>
	(function($){
	$.fn.textareaCounter = function(options) {
		// setting the defaults
		// $("textarea").textareaCounter({ limit: 100 });
		var defaults = {
			limit: 300
		};	
		var options = $.extend(defaults, options);
 
		// and the plugin begins
		return this.each(function() {
			var obj, text, wordcount, limited;
 
			obj = $(this);
			//obj.after('Max. '+options.limit+' words');
 
			obj.keyup(function() {
			    text = obj.val();
			    if(text === "") {
			    	wordcount = 0;
			    } else {
				    wordcount = $.trim(text).split(/[\s\.\?]+/).length;
				}
			    if(wordcount >= options.limit) {
			        obj.next().html('0 words left');
			        limited = $.trim(text).split(" ", options.limit);
					limited = limited.join(" ");
					$(this).val(limited);
			    } else {
			        obj.next().html('<p>'+(options.limit - wordcount)+' words left</p>');
			    } 
			});
			
			obj.trigger('keyup');
			
		});
	};
})(jQuery);
		$(function(){
			if( $.browser.msie && $.browser.version < 9 ) {
				$('html').addClass('ie');
				
				$('div.option-group').each(function(){
					var $this = $(this);
					
					$this.find('label').append('<span class="before"></span> <span class="after"></span>');
					
					// radio button styling
					if( $this.hasClass('radio') ) {
						$this.delegate('input[type="radio"]', 'click', function(){
							if( this.checked ) {
								$(this).siblings('label').removeClass('checked').end().next('label').addClass('checked');
							}
							else {
								$(this).next('label').removeClass('checked');
							}
						});
					}
					
					// checkbox styling
					else if( $this.hasClass('check') ) {
						$this.delegate('input[type="checkbox"]', 'click', function(){
							if( this.checked ) {
								$(this).next('label').addClass('checked');
							}
							else {
								$(this).next('label').removeClass('checked');
							}
						});
					}
				});
				
			}
			
			
			// select box styling
			if( $.browser.msie && $.browser.version <= 9 ) {
				$('html').addClass('ie9');
				
				$('form.general')
					.find('select')
					.css({ 'opacity': '0', 'position': 'relative', 'z-index': '10' })
					.after('<span class="selectTop"/>')
					.change(function(){
						$(this).next().text( $('option:selected', this).text() );
					})
					.trigger('change');
			}
			
			// add 'invalid' class when HTML5 form valiation fails
			if( !$.browser.firefox ) {
				$('form.general').each(function(){
					$(this).find('input.form-input').bind('invalid', function(){
						$(this).addClass('invalid');
					});
				});
			}
		});
	</script>
    	<script type="text/javascript">
	$(document).ready(function(){
    $("#dialog-confirm").dialog({
		autoOpen: false,
		resizable: false,
		height:200,
		width:350,
		show:400,
		modal: true
    });
 
	});
	$(".confirmLink").click(function(e) {
    e.preventDefault();
    var targetUrl = $(this).attr("href");

    $("#dialog-confirm").dialog({
      buttons : {
               "Cancel" : function() {
          $(this).dialog("close");
        }
      }
    });

    $("#dialog-confirm").dialog("open");
  });
	</script>
	
</body>
</html>