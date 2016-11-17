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
	<meta charset="UTF-8">
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
	<link rel="stylesheet" href="http://jqueryvalidation.org/files/demo/site-demos.css">
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
				projecttitle: {required: true},
				projectprogramme: {valueNotEquals: "Select" },
				projectreference: {required: true},
				projectdate: {required: true, date: true },
				projectstaffinput: {required: true},
				projectexpectedvalue: {required: true},
				projectstatus: { valueNotEquals: "Select" },
				
				fullprojecttitle: {required: true},
				fullprojectnihrprojectref: {required: true},
				fullprojectprogramme: {valueNotEquals: "Select" },
				fullprojectdatesubmitted: {required: true, date: true },
				fullprojectduration: {required: true},
				fullprojectplannedrecruitmenttotal: {required: true},
				fullprojectnumberofprojectsites: {required: true},
				fullprojectexpectedinput: {required: true},
				fullprojectcurrentstatus: {valueNotEquals: "Select" },
				fullprojectestimatedoractualstartdate: {required: true, date: true },
				fullprojecttotalcost: {required: true},
				fullprojectexpectedvalue: {required: true}

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
				projectdate: {date: true },
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
		
			
			outsel = getQueryVariable('outlineselect');
			repid = getQueryVariable('report_id');
			if (outsel !== 'null' && repid !== 'null')
			{
				document.location.href="section3.php?outlineselect="+outsel+"&report_id="+repid+"&view=view";
				
			}
			else
			{
				document.location.href="reporthome.php";	
			}
		});
	
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
		
	$("textarea[name=projectstaffinput]").textareaCounter({ limit : 50 });	
	$("textarea[name=fullprojectexpectedinput]").textareaCounter({ limit : 50 });	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
	});
	
	</script>


</head>
<body>
<form action="save3.php" method="post" class="general" id="form">
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

		
		
	
<?

	if (isset($_GET['projectselect']))
	{
		//echo 'Project select';
		$fullsql = "SELECT * FROM ctu_report_section3full_inprog WHERE report_id = '".$_GET['report_id']."' AND full_rank = '". $_GET['projectselect'] ."' ORDER BY full_rank";
		$fullqry = mysql_query($fullsql);
		while ($full = mysql_fetch_array($fullqry))
		{
		?>
		<table class="outline-table">
		<tr>
			<td><label for="fullprojecttitle">Project title<span> </span></label>
			<td>	<textarea style="width:500px;height:50px;" type="text" name="fullprojecttitle" class="form-input"><?=$full['title']?></textarea></td>
				</td>
			<td class="errortd"></td>
		</tr>
		
		<tr>
			<td> <label class="outlabel" for="fullprojectprogramme">NIHR Programme</label></td>
			<td><div class="select-wrapper">
			<select name="fullprojectprogramme">
            	<option>Select</option>
				<option value="HTA" <?php echo ($full['programme']=='HTA' ? "selected=\"selected\"" : "")?> >HTA</option>
				<option value="EME" <?php echo ($full['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
				<option value="HSDR" <?php echo ($full['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
				<option value="PHR" <?php echo ($full['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                <option value="RfPB" <?php echo ($full['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                <option value="PGfAR" <?php echo ($full['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                <option value="i4i" <?php echo ($full['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></br></br></br></td>
		<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="fullprojectnihrprojectref">NIHR project Reference</label></td>
        	<td><input type="text" name="fullprojectnihrprojectref" class="form-input-table" value="<?=$full['nihrprojectref']?>" /></td>
			<td class="errortd"></td>
		</tr>
		<tr>
			<td><label for="fullprojectdatesubmitted">Date Submitted</label></td>
            <td><input type="text" class="datepick form-input-table form-datepick" id="fullprojectdatesubmitted" name="fullprojectdatesubmitted" value="<?php echo (strlen($full['datesubmitted'])>0 ? date('d/m/Y',strtotime($full['datesubmitted'])) : "" )?>" /></td>
		</tr>
		<tr>
			<td><label for="fullprojectduration">Project duration (months)</label></td>
            <td><input type="text" name="fullprojectduration" class="form-input number"  value="<?=$full['duration']?>"/></td>
		</tr>
		<tr>
		    <td><label for="fullprojectplannedrecruitmenttotal">Planned recruitment total</label></td>
            <td><input type="text" name="fullprojectplannedrecruitmenttotal" class="form-input number"  value="<?=$full['plannedrecruitmenttotal']?>"/></td>
        </tr>
		<tr>
			<td><label for="fullprojectnumberofprojectsites">Number of project sites</label></td>
            <td><input type="text" name="fullprojectnumberofprojectsites" class="form-input number" value="<?=$full['numberofprojectsites']?>" /></td>
		</tr>
        <tr>     
			<td colspan="2">	
				<p class="option-label">International multi-site project?</p>
               	<div class="option-group check">
				<input type="checkbox" name="fullprojectintmultisite" id="fullcheck1" <?php echo ($full['intmultisite']=='yes' ? 'checked' : '' )?>/>
				
				<label for="fullcheck1" <?php echo ($full['intmultisite']=='yes' ? 'class="checked"' : '' )?>></label>			
				</div>
			</td>
		</tr>
		<tr>
			<td><label for="fullprojectexpectedinput">Describe your unit's expected level of input</label></td>
			<td><textarea name="fullprojectexpectedinput" class="form-input countstyle" style="width:640px;"><?=$full['expectedinput']?></textarea><span class="counter-text"></span></td>
        </tr>
		<tr>		
			<td><label for="fullprojectcurrentstatus">Current Status</label></td>
            <td><div class="select-wrapper form-select">
                <select name="fullprojectcurrentstatus">
                    <option>Select</option>
                    <option value="Decision Pending" <?php echo ($full['currentstatus']=='Decision Pending' ? "selected=\"selected\"" : "")?>>Decision Pending</option>
					<option value="Reject" <?php echo ($full['currentstatus']=='Reject' ? "selected=\"selected\"" : "")?>>Reject</option>
					<option value="Resubmission" <?php echo ($full['currentstatus']=='Resubmission' ? "selected=\"selected\"" : "")?>>Resubmission</option>
					<option value="Funded" <?php echo ($full['currentstatus']=='Funded' ? "selected=\"selected\"" : "")?>>Funded</option>
					<option value="Funded with Changes" <?php echo ($full['currentstatus']=='Funded with Changes' ? "selected=\"selected\"" : "")?>>Funded with Changes</option>
                </select>
            	</div></td>
		</tr>
        <tr>       
			<td><label for="fullprojectestimatedoractualstartdate">Estimated or actual start date</label></td>
            <td><input type="text" class="datepick form-input-table form-datepick" id="fullprojectestimatedoractualstartdate" name="fullprojectestimatedoractualstartdate" value="<?php echo (strlen($full['estimatedoractualstartdate'])>0 ? date('d/m/Y',strtotime($full['estimatedoractualstartdate'])) : "" )?>"/></td>
		</tr>
		<tr>
			<td colspan="2">
                <p class="option-label">Is start date estimated?</p>
                <div class="option-group check">
				<input type="checkbox" name="fullprojectisstartdateestimated" id="fullcheck2" <?php echo ($full['isstartdateestimated']=='yes' ? 'checked' : '' )?>/>
				<label for="fullcheck2" <?php echo ($full['isstartdateestimated']=='yes' ? 'class="checked"' : '' )?>></label>	
                </div>
            </td>
		</tr>
		<tr>
            <td><label for="fullprojecttotalcost">Total cost of project<span> </span></label>
            <td><input type="text" name="fullprojecttotalcost" class="form-input currency" value="<?=$full['totalcost']?>"/><td>
		</tr>
		<tr>		
            <td><label for="fullprojectexpectedvalue">Expected value of project to your unit over course of project</label></td>
			<td><input type="text" name="fullprojectexpectedvalue" class="form-input currency" value="<?=$full['expectedvalue']?>"/></td>
		</tr>
				<!--
                <label for="fullprojectestimatedstaffcosts">Estimated total unit staff costs over course of project<span> </span></label>
                <input type="text" name="fullprojectestimatedstaffcosts" class="form-input currency" value="<?//=$full['estimatedstaffcosts']?>"/>
                <label for="fullprojectestimatednonstaffcosts">Estimated total unit non-staff costs over course of project<span> </span></label>
                <input type="text" name="fullprojectestimatednonstaffcosts" class="form-input currency" value="<?//=$full['estimatednonstaffcosts']?>"/>
                <label for="fullprojectnonstaffdesc">Brief description of non-staff costs<span></span></label>
				<textarea name="fullprojectnonstaffdesc" class="form-input" style="width:640px;"><?//=$full['nonstaffdesc']?></textarea><span class="counter-text"></span>-->
                
        
		<input name="req_type" type="hidden" value="project"></input>
		<input name="projectselect" type="hidden" value="<?=$_GET['projectselect']?>"></input>
        <?php
		}
	}
	else if (isset($_GET['outlineselect']))
	{
		//echo 'Outline select';
		
		$outlinesql = "SELECT * FROM ctu_report_section3outline_inprog WHERE report_id = '".$_GET['report_id']."' AND outline_rank = '". $_GET['outlineselect'] ."' ORDER BY outline_rank";
		$outlineqry = mysql_query($outlinesql);
		while ($outline = mysql_fetch_array($outlineqry))
		{
		?>
		<table class="outline-table">
		<tr>
			<td> <label for="projecttitle">Project title</label></td>
			<td><textarea name="projecttitle" class="form-input-table" ><?=$outline['title']?></textarea></td>
			<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="projectprogramme">NIHR Programme</label></td>
			<td><div class="select-wrapper">
			<select name="projectprogramme">
            	<option>Select</option>
				<option value="HTA" <?php echo ($outline['programme']=='HTA' ? "selected=\"selected\"" : "")?> >HTA</option>
				<option value="EME" <?php echo ($outline['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
				<option value="HSDR" <?php echo ($outline['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
				<option value="PHR" <?php echo ($outline['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                <option value="RfPB" <?php echo ($outline['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                <option value="PGfAR" <?php echo ($outline['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                <option value="i4i" <?php echo ($outline['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></br></br></br></td>
		<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="projectdate">Date submitted</label></td>
			<td><input type="text" class="datepick form-input-table form-datepick" id="project1date" name="projectdate" value="<?php echo (strlen($outline['submitdate'])>0 ? date('d/m/Y',strtotime($outline['submitdate'])) : "" )?>"/></td>
			<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="projectreference">NIHR project Reference</label></td>
        	<td><input type="text" name="projectreference" class="form-input-table" value="<?=$outline['reference']?>" /></td>
			<td class="errortd"></td>
		</tr>
		
		<tr>
			<td> <label class="outlabel" for="projectstaffinput">CTU Role</label></td>
            <td><textarea name="projectstaffinput" class="form-input-table countstyle" ><?=$outline['staffinput']?></textarea><span class="counter-text"></span></td>
			<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="projectexpectedvalue">Expected value of project to CTU </label></td>
            <td><input type="text" name="projectexpectedvalue" class="form-input-table currency" value="<?=$outline['expectedvalue']?>"/></td>
			<td class="errortd"></td>
		</tr>
		<tr>
			<td> <label class="outlabel" for="projectstatus">Current status</label></td>
            <td><div class="select-wrapper">
            <select name="projectstatus">
            <option>Select</option>
            <option value="Full Proposal Invited" <?php echo ($outline['status']=='Full Proposal Invited' ? "selected=\"selected\"" : "")?>>Full Proposal Invited</option>
            <option value="Reject" <?php echo ($outline['status']=='Reject' ? "selected=\"selected\"" : "")?>>Rejected</option>
            <option value="Decision Pending" <?php echo ($outline['status']=='Decision Pending' ? "selected=\"selected\"" : "")?>>Decision Pending</option>
            </select>

        </div></td>
		<td class="errortd"></td>
		</tr><!-- Table Row -->
		<input name="req_type" type="hidden" value="outline"></input>
		<input name="outlineselect" type="hidden" value="<?=$_GET['outlineselect']?>"></input>
		<?php
		}
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
<div id="dialog-confirm" title="Really Exit?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Warning!<br />If you haven�t clicked save,<br /> items will be lost!</p>
    </div>
</form>


</body>
</html>