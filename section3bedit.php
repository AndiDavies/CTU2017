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

?>


<!DOCTYPE HTML>

<html lang="en-US">
<head>

    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<!--<meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-8">-->
	<title>CTU Activity Report Form</title>
	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" type="text/css" href="general-light.php" />
   
	
	<!--[if lt IE 9]>
	    	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE 8]>
    <style type="text/css">
    .form-input , .form-input-table{
    border-radius:0 !important;    
    }
    </style>
    <![endif]-->
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
    <script src="includes/js/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="includes/js/date.js"></script>
	<script>
	$(document).ready(function(){

		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		
		
		$('.currency').blur(function()
			{
				$(this).formatCurrency({ symbol : '£'});
			});
		
        $('.currency').formatCurrency({ symbol : '£'});
		
		
		/*
		jQuery.validator.setDefaults({
			errorPlacement: function(error, element) {
			error.appendTo( element.closest("td").next("td") );
			}
		});
		*/
		

		$.validator.addMethod("valueNotEquals", function(value, element, arg){
			  return arg != value;
		}, "Please select a choice.");
		jQuery.validator.addMethod('currency', function(value, element) {
			var result = this.optional(element) || value.match(/^\xA3?\d{1,3}?([,]\d{3}|\d)*?([.]\d{1,2})?$/);
			return result;
		}, 'Please specify an amount in GBP');
		jQuery.validator.addMethod("date", function (value, element) 
		{
			try 
			{
				if (!value) 
				{
					return true;
				}
				var d = Date.parseExact(value, "d/M/yyyy");
				if (d == null) 
				{
					return false;
				} 
				else 
				{
					return true;
				}
			}
			catch (e) 
			{
				return false;
			}
		}, "Please enter a valid UK date dd/mm/yyyy");
		
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
		
		
		
		
		
		if (validate == 'V')
		{
			jQuery.validator.setDefaults(
			{
				rules: 
				{
				currentprojecttitle: {required: true},
				currentprojectprogramme: {valueNotEquals: "Select" },
				currentprojectnihrprojectref: {required: true},
				currentprojectstartdate: {required: true, date: true },
				currentprojectduration: {required: true},
				currentprojectcurrentstatus: {valueNotEquals: "Select" },
				currentprojectplannedrecruitmenttotal: {required: true},
				currentprojectnumberofprojectsites: {required: true},
				currentprojectexpectedinput: {required: true},
				currentprojecttotalcost: {required: true},
				currentprojectexpectedvalue: {required: true},
				currentprojectfundingreceivedthisperiod: {valueNotEquals: "Select" },
				//currentprojectiffundingnotreceivedinperiod
				currentprojecttotalfundingreceived: {required: true},
				currentprojectcontractextension: {valueNotEquals: "Select" },
				//currentprojectwhyextensiongranted
				currentprojecttotalvalueofextension: {required: true},
				currentprojectvalueofextensiontounit: {required: true},
				currentprojectadditionalfundingfromcontractextension: {required: true},

				closedtitle: {required: true},
				closedprogramme: {valueNotEquals: "Select" },
				closedreference: {required: true},
				closedreason: {required: true}
				}
			});
			$('form').validate();
			$('form').submit();
			
			
			
			
		}
		else
		{
			jQuery.validator.setDefaults(
			{
				rules: 
				{
				currentprojectstartdate: {date: true },
				}
			});
		}
	
		
		
		
		$('form').validate();
		
		
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
			$("a.lnk").each(function() {
			   var _href = $(this).attr("href"); 
			   $(this).attr("href", _href + '&view=view');
			});
		}
		$('input[name=back]').click(function() {
		
			
			cur = getQueryVariable('currentselect');
			repid = getQueryVariable('report_id');
			if (cur !== 'null' && repid !== 'null')
			{
				document.location.href="section3b.php?currentselect="+cur+"&report_id="+repid+"&view=view";
				
			}
			else
			{
				document.location.href="reporthome.php";	
			}
		});
		
		
		$.fn.textareaCounter = function(options) 
	{
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
	
	$("textarea[name=currentprojectexpectedinput]").textareaCounter({ limit : 50 });	
	$("textarea[name=closedreason]").textareaCounter({ limit : 50 });	
	
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
		
		
		
	});
	
	</script>
    
</head>
<body>
<form action="save3b.php" method="post" class="general" id="section3">
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
<table class="ti">
<?
	
	if (isset($_GET['currentselect']))
	{

		$currentsql = "SELECT * FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_GET['report_id']."' AND current_rank = '". $_GET['currentselect'] ."' ORDER BY current_rank";
		$currentqry = mysql_query($currentsql);
		while ($current= mysql_fetch_array($currentqry))
		{
		?>

		<tr <?php echo ($current['current_rank']%2==0 ? 'class="even"' : '')?>>
        		<td><p><span class="tablesubheading"></b><br /><strong>Project general information</strong></span></p><br/>
            <div class="form-block">
            	<label for="currentprojecttitle">Project title<span> </span></label>
				<textarea style="width:500px;height:50px;" type="text" name="currentprojecttitle" class="form-input"><?=$current['title']?></textarea>
				<label for="currentprojectprogramme">NIHR Programme<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentprojectprogramme">
                    <option>Select</option>
                   <option value="HTA" <?php echo ($current['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($current['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($current['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($current['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($current['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($current['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($current['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
                </select>
            	</div>
                <label for="currentprojectnihrprojectref">NIHR project reference<span></span></label>
				<input type="text" name="currentprojectnihrprojectref" class="form-input" value="<?=$current['nihrprojectref']?>" />
                <label for="currentprojectstartdate">Project Start Date (contract start date)<span> </span></label>
                <input type="text" class="datepick form-input-table form-datepick" id="currentproject<?=$current['current_rank']?>startdate" name="currentprojectstartdate" value="<?php echo (strlen($current['startdate'])>0 ? date('d/m/Y',strtotime($current['startdate'])) : "" )?>"/>
                <label for="currentprojectduration">Project duration (months)<span> </span></label>
                <input type="text" name="currentprojectduration" class="form-input number" value="<?=$current['duration']?>" />
                <label for="currentprojectcurrentstatus">Current project Status<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentprojectcurrentstatus">
                    <option>Select</option>
                    <option value="planning" <?php echo ($current['currentstatus']=='planning' ? "selected=\"selected\"" : "")?>>Planning</option>
					<option value="recruitment" <?php echo ($current['currentstatus']=='recruitment' ? "selected=\"selected\"" : "")?>>Recruitment</option>
					<option value="analysis" <?php echo ($current['currentstatus']=='analysis' ? "selected=\"selected\"" : "")?>>Analysis</option>
					<option value="other" <?php echo ($current['currentstatus']=='other' ? "selected=\"selected\"" : "")?>>Other</option>
                </select>
            	</div>
                <label for="currentprojectplannedrecruitmenttotal">Planned recruitment total<span></span></label>
                <input type="text" name="currentprojectplannedrecruitmenttotal" class="form-input number" value="<?=$current['plannedrecruitmenttotal']?>" />
                <label for="currentprojectnumberofprojectsites">Number of project sites<span> </span></label>
                <input type="text" name="currentprojectnumberofprojectsites" class="form-input number" value="<?=$current['numberofprojectsites']?>" />
                <p class="option-label">Is this an international multi-site project?</p>
               	<div class="option-group check">
				<input type="checkbox" name="currentprojectintmultisite" id="currentcheck1" <?php echo ($current['intmultisite']=='yes' ? 'checked' : '' )?> />
				<label for="currentcheck1" <?php echo ($current['intmultisite']=='yes' ? 'class="checked"' : '' )?>></label>			
				</div>
                <label for="currentprojectexpectedinput">Describe your unit's expected level of input <span></span></label>
				<textarea name="currentprojectexpectedinput" class="form-input limit wordcount50" style="width:640px;"><?=$current['expectedinput']?></textarea><span class="counter-text"></span>
               
               
                </div>
				<div>
				<p><span class="tablesubheading" style="float:left;margin-bottom:20px;"><br><strong>Project costs</strong></span></p><br>
					
				</div>
            	<div class="form-block">
				
                <label for="currentprojecttotalcost">Total cost of project<span> </span></label>
                <input type="text" name="currentprojecttotalcost" class="form-input currency" value="<?=$current['totalcost']?>" />
                <label for="currentprojectexpectedvalue">Expected value of project to your unit over course of project<span> </span></label>
                <input type="text" name="currentprojectexpectedvalue" class="form-input currency" value="<?=$current['expectedvalue']?>" />
                <!--
				<label for="currentprojectestimatedstaffcosts">Estimated total unit staff costs over course of project<span> </span></label>
                <input type="text" name="currentprojectestimatedstaffcosts" class="form-input currency" value="<?//=$current['estimatedstaffcosts']?>" />
                <label for="currentprojectestimatednonstaffcosts">Estimated total unit non-staff costs over course of project<span> </span></label>
                <input type="text" name="currentprojectestimatednonstaffcosts" class="form-input currency" value="<?//=$current['estimatednonstaffcosts']?>" />
                <label for="currentprojectnonstaffdesc">Brief description of non-staff costs<span></span></label>
				<textarea name="currentprojectnonstaffdesc" class="form-input limit wordcount50" style="width:640px;"><?//=$current['nonstaffdesc']?></textarea><span class="counter-text"></span>
                -->
                 <label for="currentprojectfundingreceivedthisperiod">Was funding received in this reporting period for this project?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentprojectfundingreceivedthisperiod" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current['fundingreceivedthisperiod']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current['fundingreceivedthisperiod']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                <label for="currentprojectiffundingnotreceivedinperiod">If no, please describe why funding has not been received</label>
                <input type="text" name="currentprojectiffundingnotreceivedinperiod" class="form-input" value="<?=$current['iffundingnotreceivedinperiod']?>"/>
                
                <label for="currentprojecttotalfundingreceived">Total funding received in this reporting period from original contract award*</label>
                <input type="text" name="currentprojecttotalfundingreceived" class="form-input currency" value="<?=$current['totalfundingreceived']?>"/>
                
                  <label for="currentprojectfundingreceivedthisperiod">Has project received a contract extension?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentprojectcontractextension" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current['contractextension']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current['contractextension']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                
                <label for="currentprojectwhyextensiongranted">If yes, why was extension granted?</label>
                <input type="text" name="currentprojectwhyextensiongranted" class="form-input" value="<?=$current['whyextensiongranted']?>" />
                
                <label for="currentprojecttotalvalueofextension">Total value of contract extension</label>
                <input type="text" name="currentprojecttotalvalueofextension" class="form-input currency" value="<?=$current['totalvalueofextension']?>"/>
                
                <label for="currentprojectvalueofextensiontounit">Expected value of contract extension to your unit</label>
                <input type="text" name="currentprojectvalueofextensiontounit" class="form-input currency" value="<?=$current['valueofextensiontounit']?>"/>
                
                <label for="currentprojectadditionalfundingfromcontractextension">Additional funding received this reporting period from contract extension (if applicable)</label>
                <input type="text" name="currentprojectadditionalfundingfromcontractextension" class="form-input currency" value="<?=$current['additionalfundingfromcontractextension']?>"/>
                <p style="clear:both;">&nbsp;</p>
                <p class="option-label">Does the project meet the NIHR criteria for offset?</p>
                <div class="option-group check">
				<input type="checkbox" name="currentprojectNIHRoffset" id="currentcheck2" <?php echo ($current['NIHRoffset']=='yes' ? 'checked' : '' )?>/>
				<label for="currentcheck2" <?php echo ($current['NIHRoffset']=='yes' ? 'class="checked"' : '' )?>></label>	
                </div> 
                </div>
            </td>
		</tr><!-- Table Row -->
		<input name="req_type" type="hidden" value="current"></input>
		<input name="currentselect" type="hidden" value="<?=$_GET['currentselect']?>"></input>
		
		
		<?
		}
	

	}
	else if (isset($_GET['closedselect']))
	{
	
	$closed1sql = "SELECT * FROM ctu_report_section3bclosed_inprog WHERE report_id='".$_SESSION['report_id']."' AND closed_rank = '". $_GET['closedselect'] ."' ";
		$closed1qry = mysql_query($closed1sql);
		$closed1 = mysql_fetch_array($closed1qry); 
		?>
		<tr>
			<td><label for="closedtitle">Title</label></td>
			<td><textarea style="width:500px;height:50px;"type="text" name="closedtitle" class="form-input-table spec" ><?=$closed1['title']?></textarea></td>
		</tr>
		<tr>
			<td><label for="closedprogramme">Programme</label></td>
			<td><div class="select-wrapper">
			<select name="closedprogramme">
            <option value="Select">Select</option>
            	<option value="HTA" <?php echo ($closed1['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($closed1['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($closed1['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($closed1['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($closed1['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($closed1['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($closed1['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td><label for="closedreference">Reference</label></td>
			<td><input type="text" name="closedreference" class="form-input-table" value="<?=$closed1['reference']?>" /></td>
		
		</tr>
		<tr>
			<td><label for="closedreason">Reason</label></td>
            <td><textarea style="width:500px;height:50px;" name="closedreason" class="form-input-table wordcount50"><?=$closed1['reason']?></textarea><span class="counter-text"></span></td>
		</tr><!-- Table Row -->
		<input name="req_type" type="hidden" value="closed"></input>
		<input name="closedselect" type="hidden" value="<?=$_GET['closedselect']?>"></input>
		
		
		
		<?php
	
		
	
	
	
	}

?>
</table>

</br></br>

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
		echo '<input class="form-btn" id="sub" type="submit" value="Save and Continue" />	<br/>';
		echo '<input class="form-btn" type="button" name="exit" id="exit" value="Exit" />';
	}


?>
</form>
<div id="dialog-confirm" title="Really Exit?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Warning!<br />If you haven�t clicked save,<br /> items will be lost!</p>
    </div>

</body>
</html>












