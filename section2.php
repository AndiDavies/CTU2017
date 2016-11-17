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
	//header("Location: http://www.netscc.ac.uk/ctu_dev/forms/light/reporthome.php?reason=1");
}


$objectiverows=2; // Will need fixing when data is pulled in from reloaded form.
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CTU Activity Report Form</title>
	
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
<?php

$getreportsql = "SELECT rp.startdate,
						rp.enddate,
						rp.completiondeadline
				FROM ctu_activityreporting AS ar
					INNER JOIN ctu_reportingperiods AS rp ON 
						ar.period = rp.period
				WHERE ar.report_id = '".$_SESSION['report_id']."'";
$getreportqry = mysql_query($getreportsql);
$report = mysql_fetch_array($getreportqry);

$reportingperiod = date('jS F Y',strtotime($report['startdate']))." - ".date('jS F Y',strtotime($report['enddate']));
$reportdueby = date('jS F Y',strtotime($report['completiondeadline']));
$ctusql = "SELECT * FROM ctu_details where ctu_id = '".$_SESSION['ctu']."'";
	$ctuqry = mysql_query($ctusql);
	$ctu = mysql_fetch_array($ctuqry); 
$currentCTU=$ctu['name'];
$currentfundingaward=$ctu['currentaward'];
?>

	<form action="process2.php" method="post" class="general" id="section2">
    <div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
    </div>
	<h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report
	
	<?php
	
	if (isset($_GET['view']))
	{
		echo '<b style="color:red;">VIEW MODE</b>';
	}
	
	
	?>
	
	
	
	</h1>
    <p>&nbsp;</p>
	<p class="form-watermark">CTU: <strong><?=$currentCTU?><br/></strong>Unique ID: <strong><i><?=$ctu['ukcrc']?></i></strong>&nbsp;&nbsp;&nbsp;Reporting Period: <strong><i><?=$reportingperiod?></i></strong></p>
    <h2>Section 2: Progress Summary</h2>
    <h3>2.1 Objectives</h3>
    <p>&nbsp;</p>
    <p>These are your objectives for the current reporting period which were provided in your previous activity report. Please state whether these objectives have been achieved below.</p><p>&nbsp;</p>
    <?php
	//Get Objectives from DB
	$objectivessql = "SELECT * FROM ctu_objectives where ctu_id = '".$_SESSION['ctu']."' order by objective_rank";
	//echo $objectivessql;
	$objectivesqry = mysql_query($objectivessql);
	$objectivecount = mysql_num_rows($objectivesqry);
	
	//Get objective data from current report
	?>
    <table cellspacing='0' class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<thead>
		<tr>
			<th>Objective number</th>
			<th>Objective</th>
			<th>Target date</th>
            <th>Achieved</th>
		</tr>
	</thead>
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>
    <?php
	if (mysql_num_rows($objectivesqry)<1)
	{
		?>
        <tr>
        	<td colspan="4">
            <strong>No objectives set for the reporting period</strong>
            </td>
        </tr>
        <?php
	}
	else
	{
	//Loop through objectives
	$objectivecounter=1;
	while ($objective = mysql_fetch_array($objectivesqry))
	{
	?>
		<tr <?php if ($objectivecounter%2==0){ echo 'class="even"'; }?> >
			<td style="text-align:center"><?=$objective['objective_rank']?></td>
			<td align="left"><?=$objective['objective']?></td>
			<td><?=date('d/m/Y',strtotime($objective['targetdate']))?></td>
            <td>
            <?php
			//Pull data on this objective from database if it exists
			$objsql = "SELECT * FROM ctu_report_section2current WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = '".$objective['objective_rank']."'";
			$objqry = mysql_query($objsql);
			$obj = mysql_fetch_array($objqry);
			?>
            <div class="select-wrapper">
        		<select name="objective<?=$objective['objective_rank']?>achieved" required>
                	<option value="Select" >Select</option>
                    <option value="yes" <?php echo ($obj['achieved']=='yes' ? "selected=\"selected\"" : "")?> >Yes</option>
                    <option value="no" <?php echo ($obj['achieved']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                    <option value="partially" <?php echo ($obj['achieved']=='partially' ? "selected=\"selected\"" : "")?>>Partially</option>
                </select>
                <span id="invalid-objective<?=$objective['objective_rank']?>achieved"></span>
                </div>
            </td>
		</tr><!-- Table Row -->
     <?php
	 $objectivecounter++;
	}
	}
	?>
	</tbody>
	<!-- Table Body -->
</table>
 <p>&nbsp;</p>
    <h3>2.2 Progress against objectives (optional)</h3>
    <p>&nbsp;</p>
    <p>If objectives have been listed in the previous year, please give details on progress if the objective has not been met, or if you would like to provide further information.</p>
    <p>&nbsp;</p>
    <table cellspacing='0' class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<thead>
		<tr>
			<th>Objective number</th>
			<th colspan="3">Progress Update</th>
		</tr>
	</thead>
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>
     <?php
	if (mysql_num_rows($objectivesqry)<1)
	{
		?>
        <tr>
        	<td colspan="4">
            <strong>No objectives set for the reporting period</strong>
            </td>
        </tr>
        <?php
	}
	else
	{
	//loop around objectives to provide progress update forms
	for ($i=1;$i<=$objectivecount;$i++)
	{
	$objsql = "SELECT * FROM ctu_report_section2current WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = '".$i."'";
	$objqry = mysql_query($objsql);
	$obj = mysql_fetch_array($objqry);
	?>
		<tr <?php if ($i%2==0){ echo 'class="even"'; }?>>
			<td style="text-align:center;"><label for="objective<?=$i?>progress" style="width:100px;"><?=$i?> <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
			<td colspan="3"><textarea name="objective<?=$i?>progress" class="form-input limit wordcount300"><?=$obj['progress']?></textarea><span class="counter-text"></br></span></td>
		</tr><!-- Table Row -->
     <?php
	}
	}
	?>
	</tbody>
	<!-- Table Body -->
</table>
<p>&nbsp;</p>
    <h3>2.3 Planned objectives for CTU Support Funding (to be completed by units in receipt of funding for less than 3 years only)</h3>
    <p>&nbsp;</p>
    <p>Please list below what you expect to achieve in the next reporting period and provide target dates. A maximum of 5 objectives are to be given.</p>
    <p>&nbsp;</p>
    <table cellspacing='0'  class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<thead>
		<tr>
			<th>Objective number</th>
			<th colspan="2">Objectives</th>
            <th>Target Date<br>DD/MM/YYYY</th>
		</tr>
	</thead>
	<!-- Table Header -->
	<!-- Table Body -->
    <?php
	//2 is my default number
	//get data individually for first 2.
	?>
	<tbody>
		<tr >
        	<?php
			//
			$objsql = "SELECT * FROM ctu_report_section2planned WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = 1";
			$objqry = mysql_query($objsql);
			$obj = mysql_fetch_array($objqry);
			?>
			<td style="text-align:center;"><label for="plannedobjective1" style="width:100px;">1 <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label></td>
			<td colspan="2"><textarea name="plannedobjective1" class="form-input" style="width:700px;" /><?=$obj['objective']?></textarea></td>
            <td><input type="text" class="datepick" id="plannedobjective1date" name="plannedobjective1date" value="<?php echo (strlen($obj['targetdate'])>0 ? date('d/m/Y',strtotime($obj['targetdate'])) : "" )?>" /></td>
		</tr><!-- Table Row -->
		<tr class="even">
        	<?php
			$objsql = "SELECT * FROM ctu_report_section2planned WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank = 2";
			$objqry = mysql_query($objsql);
			$obj = mysql_fetch_array($objqry);
			?>
			<td style="text-align:center;"><label for="plannedobjective2" style="width:100px;">2 <span>(optional)</span></label></td>
		    <td colspan="2"><textarea name="plannedobjective2" class="form-input" style="width:700px;"/><?=$obj['objective']?></textarea></td>
            <td><input type="text" class="datepick" id="plannedobjective2date" name="plannedobjective2date" value="<?php echo (strlen($obj['targetdate'])>0 ? date('d/m/Y',strtotime($obj['targetdate'])) : "" )?>" /></td>
		</tr><!-- Darker Table Row -->
        <?php
		//Now - loop to add anything above 2
			$objsql = "SELECT * FROM ctu_report_section2planned WHERE report_id = '".$_SESSION['report_id']."' AND objective_rank > 2 ORDER BY objective_rank";
//			echo $objsql;
			$objqry = mysql_query($objsql);
			//echo mysql_num_rows($objqry);
			while ($obj = mysql_fetch_array($objqry))
			{
		?>
       	<tr <?php echo ($obj['objective_rank']%2==0 ? 'class="even"' : '')?> >
			<td style="text-align:center;"><label for="plannedobjective<?=$obj['objective_rank']?>" style="width:100px;"><?=$obj['objective_rank']?> <span>(optional)</span></label></td>
		    <td colspan="2"><textarea name="plannedobjective<?=$obj['objective_rank']?>" class="form-input" style="width:700px;"/><?=$obj['objective']?></textarea></td>
            <td><input type="text" class="datepick" id="plannedobjective<?=$obj['objective_rank']?>date" name="plannedobjective<?=$obj['objective_rank']?>date" value="<?php echo (strlen($obj['targetdate'])>0 ? date('d/m/Y',strtotime($obj['targetdate'])) : "" )?>" /></td>
		</tr>
        <?php
        $objectiverows = $obj['objective_rank'];
			}
			//echo "<tr><td colspan=3>".$objectiverows."</td></tr>";
			
		?>
         <tr id="objectivebuttonrow">
        	<td colspan="7"><span style="display:block;text-align:right"><input type="button" class="form-btn" id="addobjectivebutton" value="add another objective"></span></td>
        </tr>
	</tbody>
	<!-- Table Body -->
</table>
<?php
//Get the rest of the form data:
$mainsql= "SELECT * FROM ctu_report_section2 WHERE report_id = '".$_SESSION['report_id']."'";
$mainqry = mysql_query($mainsql);
$main = mysql_fetch_array($mainqry);
?>
<p>&nbsp;</p>
    <h3>2.4 Involvement with other research networks and participation on TSCs and DMCs</h3>
    <p>&nbsp;</p>
  <label for="activitesdevwidernihr">Please detail work in this area<br><span>up to 300 words </span></label>
		<textarea name="activitesdevwidernihr" class="form-input limit wordcount300" style="margin-bottom:0px;" ><?=$main['activitesdevwidernihr']?></textarea><span class="counter-text"></span>
    <p style="clear:both">&nbsp;</p>
<p>&nbsp;</p>
 <h3>2.5 Activity your unit has undertaken to increase the capacity for NIHR trials and studies (using your CTU Support Funding award)</h3>
    <p>&nbsp;</p>
  <label for="capacityactivity">Please detail work in this area<br><span>up to 300 words</span></label>
		<textarea name="capacityactivity" class="form-input limit wordcount300" style="margin-bottom:0px;" ><?=$main['capacityactivity']?></textarea><span class="counter-text"></span>
    <p style="clear:both">&nbsp;</p>
    <h3>2.6 Key training received by CTU staff and current unit training strategies</h3>
    <p>&nbsp;</p>
  <label for="trainingreceivedbyctustaff">Please list below all key research and methodology training received during this reporting period. Please advise whether training at the unit is opened up to others and on what basis.<br> <span>up to 200 words </span></label>
		<textarea name="trainingreceivedbyctustaff" class="form-input limit wordcount300" style="margin-bottom:0px;" ><?=$main['trainingreceivedbyctustaff']?></textarea><span class="counter-text"></span>
    <p style="clear:both">&nbsp;</p>

 <p>&nbsp;</p>
	<h3>2.7 Funding contributions from Higher Education Institution (HEI)</h3>
    <p>&nbsp;</p>
    <label for="fundingfromhei">What amount of funding has your unit received from your HEI this reporting period? <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
	<input type="text" name="fundingfromhei" class="form-input currency" value="<?=$main['fundingfromhei']?>"  /><span id="invalid-fundingfromhei"></span>
    
      <p style="clear:both">&nbsp;</p>
	<h3>2.8 Funding contributions from NHS Trusts</h3>
    <p>&nbsp;</p>
    <label for="fundingfromnhstrusts">What amount of funding has your unit received from NHS Trusts this reporting period? <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
	<input type="text" name="fundingfromnhstrusts" class="form-input currency" value="<?=$main['fundingfromnhstrusts']?>"  /><span id="invalid-fundingfromnhstrusts"></span>
    
    
      <p style="clear:both">&nbsp;</p>
	<h3>2.9 Other infrastructure funding contributions</h3>
    <p>&nbsp;</p>
    <label for="otherfunding">What amount of other infrastructure funding has your unit received during this reporting period? <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></label>
	<input type="text" name="otherfunding" class="form-input currency" value="<?=$main['otherfunding']?>"  /><span id="invalid-otherfunding"></span>
<p style="clear:both">&nbsp;</p>
 <label for="variousfundingsources">Please provide details on other infrastructure funding received.<br><span>up to 200 words</span></label>
		<textarea name="variousfundingsources" class="form-input limit wordcount200" style="margin-bottom:0px" ><?=$main['variousfundingsources']?></textarea><span class="counter-text"></span>
    <p style="clear:both">&nbsp;</p>

<?php
		
	if (isset($_GET['validate']))	
	{	
		echo '<input class="form-btn" id="sub" type="submit" value="Validate and Continue" />	<br/>';	
		echo '<input type="hidden" name="vred" value="section=1">';
				echo '<input class="form-btn" type="button" name="exit" id="exit" value="Exit" />';
	}
	else if (isset($_GET['view']))
	{
		echo '<input class="form-btn" type="button" name="back" id="back" value="Back" />';	
	}
	else if (!isset($_POST['validate']) && !isset($_POST['view']))
	{
		echo '<input class="form-btn" id="sub"  name="save"  type="submit" value="Save and Continue" />	<br/>';
		echo '<input class="form-btn" type="button" name="exit" id="exit" value="Exit" />';
	}


?>
	</form>
    <div id="dialog-confirm" title="Really Exit?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Warning!<br />If you haven’t clicked save,<br /> items will be lost!</p>
    </div>
	
	
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
    <script src="includes/js/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="includes/js/date.js"></script>
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
				   // wordcount = $.trim(text).split(/[\s\.\?]+/).length;
				    var fullStr = text + " ";
					var initial_whitespace_rExp = /^[^A-Za-z0-9]+/gi;
					var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
					var non_alphanumerics_rExp = rExp = /[^A-Za-z0-9]+/gi;
					var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
					var splitString = cleanedStr.split(" ");
					wordcount = splitString.length-1
				}
			    if(wordcount > options.limit) {
			        //obj.next().html('0 words left');
					obj.next().html('<p>'+wordcount+' words ('+(options.limit - wordcount)+' words left)</p>');
					obj.next().addClass('errorwordcount');
			       // limited = $.trim(text).split(" ", options.limit);
					//limited = limited.join(" ");
					//$(this).val(limited);
					
			    } else {
			        obj.next().html('<p>'+wordcount+' words (<span class="correct">'+(options.limit - wordcount)+' words left</span>)</p>');
					obj.next().removeClass('errorwordcount');
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
					.after('<div class="selectTop"><p></p></div>')
					.change(function(){
						$(this).next().children().text( $('option:selected', this).text() );
					})
					.trigger('change');
			}
			
			// add 'invalid' class when HTML5 form valiation fails
			if( !$.browser.firefox ) {
				$('form.general').each(function(){
					$(this).find('input.form-input').bind('invalid', function(){
						$(this).addClass('invalid');
					});
					$(this).find('textarea.form-input').bind('invalid', function(){
						$(this).addClass('invalid');
					});
				});
			}

		});
		
	</script>
	<script type="text/javascript">
	function getQueryVariable(variable)
	{
		   var query = window.location.search.substring(1);
		   var vars = query.split("&");
		   for (var i=0;i<vars.length;i++) {
				   var pair = vars[i].split("=");
				   if(pair[0] == variable){return pair[1];}
		   }
		   return(false);
	}
	validate = getQueryVariable('validate');
	//console.log (validate);

	if (validate == 'V')
		{
			jQuery.validator.setDefaults(
			{
				rules: 
				{
				activitesdevwidernihr: {required: true},
				variousfundingsources: {required: true},
				capacityactivity: {required: true},
				trainingreceivedbyctustaff: {required: true}
				}
			});
			
			$('form').validate();
			$('form').submit();
		}
	
	
		view = getQueryVariable('view');
		if (view == 'view')
		{
			//console.log ('MOO');
			$('#addoutlinebutton').hide();
			$('#addfullbutton').hide();
			$('.form-btn[name="save"]').hide();
			$('input, textarea').attr('readonly', true);
			$('select').attr("disabled", true);
			$('.del').hide();
			
		}
	
	
	
	
	
	
	
	
	
	
	
	var objectiverows = <?=$objectiverows+1?>;
	jQuery.extend(jQuery.validator.messages, {
		required: "Required.",
		remote: "Please fix this field.",
		email: "Please enter a valid email address.",
		url: "Please enter a valid URL.",
		date: "Please enter a valid date.",
		dateISO: "Please enter a valid date (ISO).",
		number: "Please enter a valid number.",
		digits: "Please enter only digits.",
		creditcard: "Please enter a valid credit card number.",
		equalTo: "Please enter the same value again.",
		accept: "Please enter a value with a valid extension.",
		maxlength: jQuery.validator.format("Please enter no more than {0} characters."),
		minlength: jQuery.validator.format("Please enter at least {0} characters."),
		rangelength: jQuery.validator.format("Please enter a value between {0} and {1} characters long."),
		range: jQuery.validator.format("Please enter a value between {0} and {1}."),
		max: jQuery.validator.format("Please enter a value less than or equal to {0}."),
		min: jQuery.validator.format("Please enter a value greater than or equal to {0}.")
	});
	jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) {
		return arg != value;
	}, "Required");
	/*jQuery.validator.addMethod('currency', function(value, element) {
		var result = value.match(/^\xA3?\d{1,3}?([,]\d{3}|\d)*?([.]\d{1,2})?$/);
		return result;
	}, 'Please specify an amount in GBP');*/
	/*jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.appendTo('#invalid-' + element.attr('name'));
			}
	});*/
		jQuery.validator.addMethod("date", function (value, element) {
                        try {
                            if (!value) {
                                return true;
                            }

                            var d = Date.parseExact(value, "d/M/yyyy");

                            if (d == null) {
                                return false;
                            } else {
                                return true;
                            }
                        }
                        catch (e) {
                            return false;
                        }
    }, "Please enter a valid UK date dd/mm/yyyy");
	$.validator.addMethod("wordCount",
   function(value, element, params) {
	    var fullStr = value + " ";
		var initial_whitespace_rExp = /^[^A-Za-z0-9]+/gi;
		var left_trimmedStr = fullStr.replace(initial_whitespace_rExp, "");
		var non_alphanumerics_rExp = rExp = /[^A-Za-z0-9]+/gi;
		var cleanedStr = left_trimmedStr.replace(non_alphanumerics_rExp, " ");
		var splitString = cleanedStr.split(" ");
		typedWords = splitString.length-1;
      	if(typedWords <= params[0]) {
         return true;
      }
   },
   jQuery.format("Only {0} words allowed.")
);

	
$(document).ready(function(){
			window.setInterval(sessionAlive,900000);
			function sessionAlive() {
						$.ajax({
						   url: 'session_alive.php',
						   cache: false,
						});
			}
				$('input[name=exit]').click(function() {
			 $( "#dialog-confirm" ).dialog({
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: {
					"Exit": function() {
						$( this ).dialog( "close" );
						document.location.href="reporthome.php";
					},
					Cancel: function() {
					$( this ).dialog( "close" );
					}
					}
				});
		});
		$('input[name=back]').click(function() {
			 document.location.href="reporthome.php";	
		});
		$('.currency').blur(function()
			{
				$(this).formatCurrency({ symbol : '£'});
			});
		$('.currency').formatCurrency({ symbol : '£'});
		$("textarea[name=activitesdevwidernihr]").textareaCounter();
		$("textarea[name=capacityactivity]").textareaCounter();
		$("textarea[name=trainingreceivedbyctustaff]").textareaCounter({ limit : 200 });
		$("textarea[name=variousfundingsources]").textareaCounter({ limit : 200 });
		
		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		
		$('input[name=plannedobjective1date]').ready(function() 
		{
			var dateval = $('input[name=plannedobjective1date]').val();

			if (dateval == '01/01/1970')
			{
				$('input[name=plannedobjective1date]').val('');
			}
		});
		
		
		
		
		$("#section2").validate({
			rules: {
				/*fundingfromhei: {
					currency: true
				},
				fundingfromnhstrusts: {
					currency: true
				},
				otherfunding: {
					currency: true
				}*/
				plannedobjective2date: { required: function(element) {
						return $("[name=plannedobjective2]").val().length > 0;
						}
				},
				plannedobjective3date: { required: function(element) {
						return $("[name=plannedobjective3]").val().length > 0;
						}
				},
				plannedobjective4date: { required: function(element) {
						return $("[name=plannedobjective4]").val().length > 0;
						}
				},
				plannedobjective5date: { required: function(element) {
						return $("[name=plannedobjective5]").val().length > 0;
						}
				},
				plannedobjective6date: { required: function(element) {
						return $("[name=plannedobjective6]").val().length > 0;
						}
				},
				plannedobjective7date: { required: function(element) {
						return $("[name=plannedobjective7]").val().length > 0;
						}
				},
				plannedobjective8date: { required: function(element) {
						return $("[name=plannedobjective8]").val().length > 0;
						}
				},
				plannedobjective9date: { required: function(element) {
						return $("[name=plannedobjective9]").val().length > 0;
						}
				},
				plannedobjective10date: { required: function(element) {
						return $("[name=plannedobjective10]").val().length > 0;
						}
				}
				
			}
		});
		$('[name$="achieved"]').each(function() {
			$(this).rules('add', {
			valueNotEquals: "Select" 
			});
		});
		
		$('.wordcount300').each(function() {
			$(this).rules('add', {
			wordCount: ['300']
			});
		});
		$('.wordcount200').each(function() {
			$(this).rules('add', {
			wordCount: ['200']
			});
		});
	$("#addobjectivebutton").click(function() {
		if ($.browser.msie  && parseInt($.browser.version, 10) === 8)
		{
			if (objectiverows%2==0)
				{
          		$('#objectivebuttonrow').before('<tr class="even"><td><label for="plannedobjective'+objectiverows+'" style="width:100px;">'+objectiverows+' <span>(optional)</span></td> <td colspan="2"><textarea name="plannedobjective'+objectiverows+'" class="form-input" style="width:700px;"/></textarea></td> <td><input type="text" class="datepick" id="plannedobjective'+objectiverows+'date" name="plannedobjective'+objectiverows+'date" /></td></tr>');
				}
		  	else
		 	 	{
			  		$('#objectivebuttonrow').before('<tr><td><label for="plannedobjective'+objectiverows+'" style="width:100px;">'+objectiverows+' <span>(optional)</span></td> <td colspan="2"><textarea name="plannedobjective'+objectiverows+'" class="form-input" style="width:700px;" /></textarea></td> <td><input type="text" class="datepick" id="plannedobjective'+objectiverows+'date" name="plannedobjective'+objectiverows+'date" /></td></tr>');
		  		}
		}
		else
		{
			if (objectiverows%2==0)
				{
				$('#objectivebuttonrow').before('<tr class="even"><td><label for="plannedobjective'+objectiverows+'" style="width:100px;">'+objectiverows+' <span>(optional)</span></td> <td colspan="2"><textarea name="plannedobjective'+objectiverows+'" class="form-input" style="width:700px;" /></textarea></td> <td><input type="text" class="datepick" id="plannedobjective'+objectiverows+'date" name="plannedobjective'+objectiverows+'date" /></td></tr>');
				}
			else
				{
					$('#objectivebuttonrow').before('<tr><td><label for="plannedobjective'+objectiverows+'" style="width:100px;">'+objectiverows+' <span>(optional)</span></td> <td colspan="2"><textarea name="plannedobjective'+objectiverows+'" class="form-input" style="width:700px;" /></textarea></td> <td><input type="text" class="datepick" id="plannedobjective'+objectiverows+'date" name="plannedobjective'+objectiverows+'date" /></td></tr>');
				}
		}
		  
				  
		  $('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		  objectiverows++;
          return false;
        });
	});
	
	</script>
</body>
</html>