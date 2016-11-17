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
$projectoutlinerows=2;
$projectfullrows=1;
$projectcurrentrows=1;
$projectclosedrows=2;
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
 	
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
    <script src="includes/js/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="includes/js/date.js"></script>
	<link rel="stylesheet" href="http://jqueryvalidation.org/files/demo/site-demos.css">
<script>

$(document).ready(function()
{

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

		$(function()
		{
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
		

	var projectoutlinerows = <?=$projectoutlinerows+1?>;
	var projectfullrows = <?=$projectfullrows+1?>;
	var projectcurrentrows = <?=$projectcurrentrows+1?>;
	var projectclosedrows = <?=$projectclosedrows+1?>;
	sumjq = function(selector) {
			 var sum = 0;
			 $(selector).each(function() {
				 sum += Number($(this).val());	 
			 });
			 return sum;}
			 
	sumnihr = function() {
		var sum = 0;
		var current=1;
		for (var current = 1, limit = projectcurrentrows ; current <= projectcurrentrows ; current++)
		{
			//alert(current);
			if ($('input[name=currentproject'+current+'NIHRoffset]').prop('checked')) {
				//alert('checked');
				sum+= Number($('input[name=currentproject'+current+'totalfundingreceived]').val());
			}
		}
		return (sum);
	}
	var tempvar = '';
	$('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]').change(function() {
			 $('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			 $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
		  });
	$('input[name$="NIHRoffset"]').change(function() {
		$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			 $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
	});

		window.setInterval(sessionAlive,900000);
		function sessionAlive() 
		{
			$.ajax({
			url: 'session_alive.php',
			cache: false,
			});
		}
		$('input[name=exit]').click(function() {
			$( "#dialog-confirm" ).dialog(
			{
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: 
				{
					"Exit": function() 
					{
						$( this ).dialog( "close" );
						document.location.href="reporthome.php";
					},
					Cancel: function() 
					{
					$( this ).dialog( "close" );
					}
				}
			});
		});
		
		$('.del').click(function(event) 
		{
			//event.preventDefault();
			//event.returnValue = false;
			(event.preventDefault) ? event.preventDefault() : event.returnValue = false;
			var lll = $(this).attr('href');
			//console.log(lll);
			$( "#dialog-delete" ).dialog(
				{
					resizable: false,
					height:200,
					width:350,
					show:400,
					modal: true,
					buttons: 
					{
						"Delete": function() 
						{
							$( this ).dialog( "close" );
							document.location.href=lll;
						},
						Cancel: function() 
						{
						$( this ).dialog( "close" );
						}
					}
				});
		});
		
		$('input[name=back]').click(function() 
		{
			 document.location.href="reporthome.php";	
		});
		$('#removeoutlinebutton').click(function() 
		{
			$( "#dialog-remove" ).dialog(
			{
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: 
				{
					"Remove": function() 
					{
						$( this ).dialog( "close" );
						$('#outlinebuttonrow').prev().fadeOut(300, function() {$(this).remove();});
						if (projectoutlinerows==4)
						{
							$('#removeoutlinebutton').hide();
						}	
						projectoutlinerows--;
					},
					Cancel: function() 
					{
					$( this ).dialog( "close" );
					}
				}
			});
		});
		$('#removefullbutton').click(function() 
		{
			 $( "#dialog-remove" ).dialog({
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: {
					"Remove": function() {
						$( this ).dialog( "close" );
						$('#fullbuttonrow').prev().fadeOut(300, function() {$(this).remove();});
						if (projectfullrows==3)
							{
								$('#removefullbutton').hide();
							}	
						projectfullrows--;
					},
					Cancel: function() {
					$( this ).dialog( "close" );
					}
					}
				});
		});
		
		//REMOVE REQURED FROM INPUTS??
		$('input[name="save"]').click(function() 
		{
			$('input[type="text"]').removeAttr('required')
			$('form').get(0).setAttribute('action', 'save3.php'); //this works?

			//console.log('im in');
		});
		
		function getQueryVariable(variable)
		{
			   var query = window.location.search.substring(1);
			   var vars = query.split("&");
			   for (var i=0;i<vars.length;i++) {
					   var pair = vars[i].split("=");
					   if(pair[0] == variable){return pair[1];}
			   }
			   return(false);
		};
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
		};
		
		
		
		
		jQuery.extend(jQuery.validator.messages, 
		{
			required: "This field is required.",
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
		jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) 
		{
			return arg != value;
		}, "Required");
		jQuery.validator.addMethod('currency', function(value, element) 
		{
			var result = this.optional(element) || value.match(/^\xA3?\d{1,3}?([,]\d{3}|\d)*?([.]\d{1,2})?$/);
			return result;
		}, 'Please specify an amount in GBP');
		jQuery.validator.addMethod("date", function (value, element) 
		{
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
		jQuery.validator.setDefaults(
		{
			errorPlacement: function(error, element) {
				error.appendTo('#invalid-' + element.attr('name'));
				}
		});
		$('.currency').blur(function()
		{
			$(this).formatCurrency({ symbol : '£'});
		});
		$('.currency').formatCurrency({ symbol : '£'});
		$("#section3").validate(
		{
			rules: 
			{
				fundingfromhei: {
					currency: true
				},
				fundingfromnhstrusts: {
					currency: true
				},
				otherfunding: {
					currency: true
				}
			}
		});
		$(".number").each(function() 
		{
			$(this).rules('add', {
			number: true 
			});
		});
		$(".currency").each(function() 
		{
			$(this).rules('add', {
			currency: true 
			});
		});
		$(".datepick").each(function() 
		{
			$(this).rules('add', {
			date: true 
			});
		});
		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		$("textarea[name$=expectedinput]").textareaCounter({limit:50});
		$("textarea[name$=nonstaffdesc]").textareaCounter({limit:50});
		$("textarea[name$=staffinput]").textareaCounter({limit:50});
		$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]'));
		$('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
		
		$("#addoutlinebutton").click(function() 
		{ 
		if ($.browser.msie  && parseInt($.browser.version, 10) === 8)
		{
			if (projectoutlinerows%2==0)
			{
				$('#outlinebuttonrow').before('<tr class="even spec"><td> <label for="projecttitle">Project title</label></td><td><textarea name="projecttitle[]" class="form-input-table" ></textarea></td></tr><tr><td> <label class="outlabel" for="projectprogramme">NIHR Programme</label></td><td><div class="select-wrapper"><select name="projectprogramme[]"><option>Select</option><option value="HTA">HTA</option><option value="EME">EME</option><option value="HSDR">HS&amp;DR</option><option value="PHR">PHR</option><option value="RfPB">RfPB</option><option value="PGfAR">PGfAR</option><option value="i4i">i4i</option></select></div></br></br></br></td></tr><tr><td> <label class="outlabel" for="projectreference">Reference</label></td><td><input type="text" name="projectreference[]" class="form-input-table"/></td></tr><tr><td> <label class="outlabel" for="projectdate">Date submitted</label></td><td><input type="text" class="datepick form-input-table form-datepick" id="projectdate'+ projectoutlinerows +'" name="projectdate[]"/></td></tr><tr><td> <label class="outlabel" for="projectstaffinput">CTU Role</label></td><td><textarea name="projectstaffinput[]" class="form-input-table countstyle" ></textarea><span class="counter-text"></span></td></tr><tr><td> <label class="outlabel" for="projectexpectedvalue">Expected value of project to CTU</label></td><td><input type="text" name="projectexpectedvalue[]" class="form-input-table currency"/></td></tr><tr><td> <label class="outlabel" for="projectstatus">Current status</label></td><td><div class="select-wrapper"><select name="projectstatus[]" ><option>Select</option><option value="Full Proposal Invited">Full Proposal</option><option value="Reject">Rejected</option><option value="Decision Pending">Decision Pending</option></select></div></td></tr><input type="hidden" name="new_outline[]" value="new_outline"><tr ><td colspan="2" style="background:#CCCCCC;">&nbsp;</td></tr><!-- Darker Table Row -->').fadeIn('slow');
				
				
				
				
			}
		  	else
		 	{
					$('#outlinebuttonrow').before('<tr class="spec"><td> <label for="projecttitle">Project title</label></td><td><textarea name="projecttitle[]" class="form-input-table" ></textarea></td></tr><tr><td> <label class="outlabel" for="projectprogramme">NIHR Programme</label></td><td><div class="select-wrapper"><select name="projectprogramme[]"><option>Select</option><option value="HTA">HTA</option><option value="EME">EME</option><option value="HSDR">HS&amp;DR</option><option value="PHR">PHR</option><option value="RfPB">RfPB</option><option value="PGfAR">PGfAR</option><option value="i4i">i4i</option></select></div></br></br></br></td></tr><tr><td> <label class="outlabel" for="projectreference">NIHR project Reference</label></td><td><input type="text" name="projectreference[]" class="form-input-table"/></td></tr><tr><td> <label class="outlabel" for="projectdate">Date submitted</label></td><td><input type="text" class="datepick form-input-table form-datepick" id="projectdate'+ projectoutlinerows +'" name="projectdate[]"/></td></tr><tr><td> <label class="outlabel" for="projectstaffinput">CTU Role</label></td><td><textarea name="projectstaffinput[]" class="form-input-table countstyle" ></textarea><span class="counter-text"></span></td></tr><tr><td> <label class="outlabel" for="projectexpectedvalue">Expected value of project to CTU</label></td><td><input type="text" name="projectexpectedvalue[]" class="form-input-table currency"/></td></tr><tr><td> <label class="outlabel" for="projectstatus">Current status</label></td><td><div class="select-wrapper"><select name="projectstatus[]" ><option>Select</option><option value="Full Proposal Invited">Full Proposal</option><option value="Reject">Rejected</option><option value="Decision Pending">Decision Pending</option></select></div></td></tr><input type="hidden" name="new_outline[]" value="new_outline"><tr ><td colspan="2" style="background:#CCCCCC;">&nbsp; </td></tr>').fadeIn('slow');
		  	}
		}
		else
		{
			if (projectoutlinerows%2==0)
			{
				$('#outlinebuttonrow').before('<tr class="even spec"><td> <label for="projecttitle">Project title</label></td><td><textarea name="projecttitle[]" class="form-input-table" ></textarea></td></tr><tr><td> <label class="outlabel" for="projectprogramme">NI HR Programme</label></td><td><div class="select-wrapper"><select name="projectprogramme[]"><option>Select</option><option value="HTA">HTA</option><option value="EME">EME</option><option value="HSDR">HS&amp;DR</option><option value="PHR">PHR</option><option value="RfPB">RfPB</option><option value="PGfAR">PGfAR</option><option value="i4i">i4i</option></select></div></br></br></br></td></tr><tr><td> <label class="outlabel" for="projectreference">NIHR project Reference</label></td><td><input type="text" name="projectreference[]" class="form-input-table"/></td></tr><tr><td> <label class="outlabel" for="projectdate">Date submitted</label></td><td><input type="text" class="datepick form-input-table form-datepick" id="projectdate'+ projectoutlinerows +'" name="projectdate[]"/></td></tr><tr><td> <label class="outlabel" for="projectstaffinput">CTU Role</label></td><td><textarea name="projectstaffinput[]" class="form-input-table countstyle" ></textarea><span class="counter-text"></span></td></tr><tr><td> <label class="outlabel" for="projectexpectedvalue">Expected value of project to CTU</label></td><td><input type="text" name="projectexpectedvalue"[] class="form-input-table currency"/></td></tr><tr><td> <label class="outlabel" for="projectstatus">Current status</label></td><td><div class="select-wrapper"><select name="projectstatus[]" ><option>Select</option><option value="Full Proposal Invited">Full Proposal</option><option value="Reject">Rejected</option><option value="Decision Pending">Decision Pending</option></select></div></td></tr><input type="hidden" name="new_outline[]" value="new_outline"><tr ><td colspan="2" style="background:#CCCCCC;">&nbsp;</td></tr><!-- Darker Table Row -->').fadeIn('slow');
			}
			else
			{
					$('#outlinebuttonrow').before('<tr class="spec"><td> <label for="projecttitle">Project title</label></td><td><textarea name="projecttitle[]" class="form-input-table" ></textarea></td></tr><tr><td> <label class="outlabel" for="projectprogramme">NIHR Programme</label></td><td><div class="select-wrapper"><select name="projectprogramme[]"><option>Select</option><option value="HTA">HTA</option><option value="EME">EME</option><option value="HSDR">HS&amp;DR</option><option value="PHR">PHR</option><option value="RfPB">RfPB</option><option value="PGfAR">PGfAR</option><option value="i4i">i4i</option></select></div></br></br></br></td></tr><tr><td> <label class="outlabel" for="projectreference">NIHR project Reference</label></td><td><input type="text" name="projectreference[]" class="form-input-table"/></td></tr><tr><td> <label class="outlabel" for="projectdate">Date submitted</label></td><td><input type="text" class="datepick form-input-table form-datepick" id="projectdate'+ projectoutlinerows +'" name="projectdate[]"/></td></tr><tr><td> <label class="outlabel" for="projectstaffinput">CTU Role</label></td><td><textarea name="projectstaffinput[]" class="form-input-table countstyle" ></textarea><span class="counter-text"></span></td></tr><tr><td> <label class="outlabel" for="projectexpectedvalue">Expected value of project to CTU</label></td><td><input type="text" name="projectexpectedvalue[]" class="form-input-table currency"/></td></tr><tr><td> <label class="outlabel" for="projectstatus">Current status</label></td><td><div class="select-wrapper"><select name="projectstatus[]" ><option>Select</option><option value="Full Proposal Invited">Full Proposal</option><option value="Reject">Rejected</option><option value="Decision Pending">Decision Pending</option></select></div></td></tr><input type="hidden" name="new_outline[]" value="new_outline"><tr ><td colspan="2" style="background:#CCCCCC;">&nbsp;</td></tr>').fadeIn('slow');
			}
		};
		projectoutlinerows++;
		$('#removeoutlinebutton').show();
		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		
		//???
		$(function()
		{
			if( $.browser.msie && $.browser.version < 9 ) 
			{
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
			if( $.browser.msie && $.browser.version <= 9 ) 
			{
				$('html').addClass('ie9');
				$('form.general').find('div.selectTop').remove();
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
			if( !$.browser.firefox ) 
			{
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
		$('.currency').blur(function()
		{
			$(this).formatCurrency({ symbol : '£'});
		});
		$("textarea[name$=staffinput]").textareaCounter({limit:50});

          return false;
        });
		$("#addfullbutton").click(function() {
		if ($.browser.msie  && parseInt($.browser.version, 10) === 8)
		{
			if (projectfullrows%2==0)
			{
          		$('#fullbuttonrow').before('<tr class="even"><td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="fullprojecttitle">Project title<span> </span></label> <textarea type="text" style="width:500px;height:50px;" name="fullprojecttitle[]" class="form-input"></textarea> <label for="fullprojectnihrprojectref">NIHR project reference<span> </span></label> <input type="text" name="fullprojectnihrprojectref[]" class="form-input"  /> <label for="fullprojectprogramme">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectprogramme[]" > <option>Select</option> <option value="HTA">HTA</option><option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="fullprojectdatesubmitted">Date Submitted<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectdatesubmitted'+ projectfullrows +'" name="fullprojectdatesubmitted[]" /> <label for="fullprojectduration">Project duration (months)<span> </span></label> <input type="text" name="fullprojectduration[]" class="form-input number"  /> <label for="fullprojectplannedrecruitmenttotal">Planned recruitment total<span> </span></label> <input type="text" name="fullprojectplannedrecruitmenttotal[]" class="form-input number"  /> <label for="fullprojectnumberofprojectsites">Number of project sites<span> </span></label> <input type="text" name="fullprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectintmultisite[]" id="fullcheck1'+ projectfullrows +'" /> <label for="fullcheck1'+ projectfullrows +'"></label> </div> <label for="fullprojectexpectedinput">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="fullprojectexpectedinput[]" class="form-input" style="width:640px;"></textarea><span class="counter-text"></span> <label for="fullprojectcurrentstatus">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectcurrentstatus[]"> <option>Select</option> <option value="Decision Pending" >Decision Pending</option> <option value="Reject" >Reject</option> <option value="Resubmission" >Resubmission</option> <option value="Funded" >Funded</option> <option value="Funded with Changes" >Funded with Changes</option> </select> </div> <label for="fullproject1estimatedoractualstartdate">Estimated or actual start date<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectestimatedoractualstartdate'+ projectfullrows +'" name="fullprojectestimatedoractualstartdate[]" /> <p class="option-label">Is start date estimated?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectisstartdateestimated[]" id="fullcheck2'+ projectfullrows +'" /> <label for="fullcheck2'+ projectfullrows +'"></label> </div></div><p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/><div class="form-block"><label for="fullprojecttotalcost">Total cost of project<span> </span></label> <input type="text" name="fullprojecttotalcost[]" class="form-input currency"  /> <label for="fullprojectexpectedvalue">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="fullprojectexpectedvalue[]" class="form-input currency"  /><input type="hidden" name="new_project[]" value="new_project"></input><span class="counter-text"></span> </div></td></tr>');
			}
		  	else
		 	{
			  		$('#fullbuttonrow').before('<tr><td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="fullprojecttitle">Project title<span> </span></label> <textarea type="text" style="width:500px;height:50px;" name="fullprojecttitle[]" class="form-input"></textarea><label for="fullprojectnihrprojectref">NIHR project reference<span> </span></label> <input type="text" name="fullprojectnihrprojectref[]" class="form-input"  /> <label for="fullprojectprogramme">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectprogramme[]" > <option>Select</option> <option value="HTA">HTA</option><option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="fullprojectdatesubmitted">Date Submitted<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectdatesubmitted'+ projectfullrows +'" name="fullprojectdatesubmitted[]" /> <label for="fullprojectduration">Project duration (months)<span> </span></label> <input type="text" name="fullprojectduration[]" class="form-input number"  /> <label for="fullprojectplannedrecruitmenttotal">Planned recruitment total<span> </span></label> <input type="text" name="fullprojectplannedrecruitmenttotal[]" class="form-input number"  /> <label for="fullprojectnumberofprojectsites">Number of project sites<span> </span></label> <input type="text" name="fullprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectintmultisite[]" id="fullcheck1'+ projectfullrows +'" /> <label for="fullcheck1'+ projectfullrows +'"></label> </div> <label for="fullprojectexpectedinput">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="fullprojectexpectedinput[]" class="form-input" style="width:640px;"></textarea><span class="counter-text"></span> <label for="fullprojectcurrentstatus">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectcurrentstatus[]"> <option>Select</option> <option value="Decision Pending" >Decision Pending</option> <option value="Reject" >Reject</option> <option value="Resubmission" >Resubmission</option> <option value="Funded" >Funded</option> <option value="Funded with Changes" >Funded with Changes</option> </select> </div> <label for="fullproject1estimatedoractualstartdate">Estimated or actual start date<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectestimatedoractualstartdate'+ projectfullrows +'" name="fullprojectestimatedoractualstartdate[]" /> <p class="option-label">Is start date estimated?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectisstartdateestimated[]" id="fullcheck2'+ projectfullrows +'" /> <label for="fullcheck2'+ projectfullrows +'"></label> </div></div><p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/><div class="form-block"><label for="fullprojecttotalcost">Total cost of project<span> </span></label> <input type="text" name="fullprojecttotalcost[]" class="form-input currency"  /> <label for="fullprojectexpectedvalue">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="fullprojectexpectedvalue[]" class="form-input currency"  /><input type="hidden" name="new_project[]" value="new_project"></input><span class="counter-text"></span> </div></td></tr>');
		  	}
		}
		else
		{
			if (projectfullrows%2==0)
			{
				$('#fullbuttonrow').before('<tr class="even"><td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="fullprojecttitle">Project title<span> </span></label><textarea style="width:500px;height:50px;" type="text" name="fullprojecttitle[]" class="form-input"></textarea><label for="fullprojectnihrprojectref">NIHR project reference<span> </span></label> <input type="text" name="fullprojectnihrprojectref[]" class="form-input"  /> <label for="fullprojectprogramme">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectprogramme[]" > <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="fullprojectdatesubmitted">Date Submitted<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectdatesubmitted'+ projectfullrows +'" name="fullprojectdatesubmitted[]" /> <label for="fullprojectduration">Project duration (months)<span> </span></label> <input type="text" name="fullprojectduration[]" class="form-input number"  /> <label for="fullprojectplannedrecruitmenttotal">Planned recruitment total<span> </span></label> <input type="text" name="fullprojectplannedrecruitmenttotal[]" class="form-input number"  /> <label for="fullprojectnumberofprojectsites">Number of project sites<span> </span></label> <input type="text" name="fullprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectintmultisite[]" id="fullcheck1'+ projectfullrows +'" /> <label for="fullcheck1'+ projectfullrows +'"></label> </div> <label for="fullprojectexpectedinput">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="fullprojectexpectedinput[]" class="form-input" style="width:640px;"></textarea><span class="counter-text"></span> <label for="fullprojectcurrentstatus">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectcurrentstatus[]"> <option>Select</option> <option value="Decision Pending" >Decision Pending</option> <option value="Reject" >Reject</option> <option value="Resubmission" >Resubmission</option> <option value="Funded" >Funded</option> <option value="Funded with Changes" >Funded with Changes</option> </select> </div> <label for="fullproject1estimatedoractualstartdate">Estimated or actual start date<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectestimatedoractualstartdate'+ projectfullrows +'" name="fullprojectestimatedoractualstartdate[]" /> <p class="option-label">Is start date estimated?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectisstartdateestimated[]" id="fullcheck2'+ projectfullrows +'" /> <label for="fullcheck2'+ projectfullrows +'"></label> </div></div><p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/><div class="form-block"><label for="fullprojecttotalcost">Total cost of project<span> </span></label> <input type="text" name="fullprojecttotalcost[]" class="form-input currency"  /> <label for="fullprojectexpectedvalue">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="fullprojectexpectedvalue[]" class="form-input currency"  /> </textarea><input type="hidden" name="new_project[]" value="new_project"><span class="counter-text"></span> </div></td></tr>');
			}
			else
			{
					$('#fullbuttonrow').before('<tr ><td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="fullprojecttitle">Project title<span> </span></label> <textarea type="text" style="width:500px;height:50px;" name="fullprojecttitle[]" class="form-input"></textarea><label for="fullprojectnihrprojectref">NIHR project reference<span> </span></label> <input type="text" name="fullprojectnihrprojectref[]" class="form-input"  /> <label for="fullprojectprogramme">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectprogramme[]" > <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="fullprojectdatesubmitted">Date Submitted<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectdatesubmitted'+ projectfullrows +'" name="fullprojectdatesubmitted[]" /> <label for="fullprojectduration">Project duration (months)<span> </span></label> <input type="text" name="fullprojectduration[]" class="form-input number"  /> <label for="fullprojectplannedrecruitmenttotal">Planned recruitment total<span> </span></label> <input type="text" name="fullprojectplannedrecruitmenttotal[]" class="form-input number"  /> <label for="fullprojectnumberofprojectsites">Number of project sites<span> </span></label> <input type="text" name="fullprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectintmultisite[]" id="fullcheck1'+ projectfullrows +'" /> <label for="fullcheck1'+ projectfullrows +'"></label> </div> <label for="fullprojectexpectedinput">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="fullprojectexpectedinput[]" class="form-input" style="width:640px;"></textarea><span class="counter-text"></span> <label for="fullprojectcurrentstatus">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="fullprojectcurrentstatus[]"> <option>Select</option> <option value="Decision Pending" >Decision Pending</option> <option value="Reject" >Reject</option> <option value="Resubmission" >Resubmission</option> <option value="Funded" >Funded</option> <option value="Funded with Changes" >Funded with Changes</option> </select> </div> <label for="fullproject1estimatedoractualstartdate">Estimated or actual start date<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="fullprojectestimatedoractualstartdate'+ projectfullrows +'" name="fullprojectestimatedoractualstartdate[]" /> <p class="option-label">Is start date estimated?</p> <div class="option-group check"> <input type="checkbox" name="fullprojectisstartdateestimated[]" id="fullcheck2'+ projectfullrows +'" /> <label for="fullcheck2'+ projectfullrows +'"></label> </div></div><p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/><div class="form-block"><label for="fullprojecttotalcost">Total cost of project<span> </span></label> <input type="text" name="fullprojecttotalcost[]" class="form-input currency"  /> <label for="fullprojectexpectedvalue">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="fullprojectexpectedvalue[]" class="form-input currency"  /> </textarea><input type="hidden" name="new_project[]" value="new_project"><span class="counter-text"></span> </div></td></tr>');
			}
		};
			projectfullrows++;
			$('#removefullbutton').show();
			$("textarea[name$=expectedinput]").textareaCounter({limit:50});
			$("textarea[name$=nonstaffdesc]").textareaCounter({limit:50});
			$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		$(function()
		{
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
				$('form.general').find('div.selectTop').remove();
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
		$('.currency').blur(function()
			{
				$(this).formatCurrency({ symbol : '£'});
			});
		$('.currency').formatCurrency({ symbol : '£'});
          return false;
        });
		
});
	
	
</script>
	
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
	<form action="process3.php" method="post" class="general" id="section3">
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
    <p class="form-watermark">CTU: <strong><?=$currentCTU?><br/></strong>Form Unique ID: <strong><i><?=$ctu['ukcrc']?></i></strong>&nbsp;&nbsp;&nbsp;Reporting Period: <strong><i><?=$reportingperiod?></i></strong></p>
    <?php
	//Pull in main data for this section
	$mainsql = "SELECT * FROM ctu_report_section3_inprog WHERE report_id = '".$_SESSION['report_id']."'";
	$mainqry = mysql_query($mainsql);
	$main = mysql_fetch_array($mainqry);
	?>
    
    <h2>Section 3a: CTU NIHR Activity (Part 1)</h2>
    <h3>This includes all HTA, EME, HS&DR, PHR, PGfAR, i4i and RfPB programme activity</h3>
    <p>&nbsp;</p>
    <h3>3.1 NIHR-related activity during this reporting period - summary</h3>
    <p>&nbsp;</p>
    <h3>a) Outline proposals where decisions were pending in previous reporting period</h3>
    <p style="clear:both">&nbsp;</p>
    <label for="previousoutlineproposalsshortlisted">Number of Outline proposals; shortlisted<span> </span></label>
	<input type="text" name="previousoutlineproposalsshortlisted" class="form-input number" value="<?=$main['previousoutlineproposalsshortlisted']?>" required /><span id='invalid-previousoutlineproposalsshortlisted'></span>    <label for="previousoutlineproposalsrejected">Number of Outline proposals; rejected<span> </span></label>
	<input type="text" name="previousoutlineproposalsrejected" class="form-input number" value="<?=$main['previousoutlineproposalsrejected']?>" required /><span id='invalid-previousoutlineproposalsrejected'></span>
	
    <p style="clear:both">&nbsp;</p>
	
    <h3>b) Outline Proposals - submitted in this reporting period</h3>
    <p style="clear:both">&nbsp;</p>
	
	<label for="currentoutlineproposalssubmitted">Number of Outline proposals; submitted<span> </span></label>
	<input type="text" name="currentoutlineproposalssubmitted" class="form-input number" value="<?=$main['currentoutlineproposalssubmitted']?>" required /><span id='invalid-currentoutlineproposalssubmitted'></span>
    <label for="currentoutlineproposalsshortlisted">Number of Outline proposals; shortlisted<span> </span></label>
	<input type="text" name="currentoutlineproposalsshortlisted" class="form-input number" value="<?=$main['currentoutlineproposalsshortlisted']?>"  /><span id='invalid-currentoutlineproposalsshortlisted'></span>
    <label for="currentoutlineproposalsrejected">Number of Outline proposals; rejected<span> </span></label>
	<input type="text" name="currentoutlineproposalsrejected" class="form-input number" value="<?=$main['currentoutlineproposalsrejected']?>" required /><span id='invalid-currentoutlineproposalsrejected'></span>
    <label for="currentoutlineproposalsdecisionpending">Number of Outline proposals; decision pending<span> </span></label>
	<input type="text" name="currentoutlineproposalsdecisionpending" class="form-input number" value="<?=$main['currentoutlineproposalsdecisionpending']?>" required /><span id='invalid-currentoutlineproposalsdecisionpending'></span>
    <p style="clear:both">&nbsp;</p>
	<!--
    <p>Please record details/references of Outline proposals where decisions are pending. <em>You will be asked to update on these in your next activity report. (For CTU reference only)</em></p>
    <p style="clear:both">&nbsp;</p>
		<textarea name="outlineproposalsdetail" class="form-input" style="width:640px"><?=$main['outlineproposalsdetail']?></textarea>
    <p style="clear:both;">&nbsp;</p>
	-->
	
	
     <h3>c) Full Proposals - where decisions were pending in <strong><em>previous</em></strong> reporting period</h3>
    <p style="clear:both">&nbsp;</p>
    <label for="previousfullproposalsfunded">Number of Full proposals; funded<span> </span></label>
	<input type="text" name="previousfullproposalsfunded" class="form-input number" value="<?=$main['previousfullproposalsfunded']?>" required /><span id='invalid-previousfullproposalsfunded'></span>
    <label for="previousfullproposalsrejected">Number of Full proposals; rejected<span> </span></label>
	<input type="text" name="previousfullproposalsrejected" class="form-input number" value="<?=$main['previousfullproposalsrejected']?>" required /><span id='invalid-previousfullproposalsrejected'></span>
    <p style="clear:both">&nbsp;</p>
	
    <h3>d) Full Proposals - submitted in this reporting period</h3>
    <p style="clear:both">&nbsp;</p>
	<label for="currentfullproposalssubmitted">Number of Full proposals; submitted<span> </span></label>
	<input type="text" name="currentfullproposalssubmitted" class="form-input number" value="<?=$main['currentfullproposalssubmitted']?>" required /><span id='invalid-currentfullproposalssubmitted'></span>
    <label for="currentfullproposalsfunded">Number of Full proposals; funded<span> </span></label>
	<input type="text" name="currentfullproposalsfunded" class="form-input number" value="<?=$main['currentfullproposalsfunded']?>" required /><span id='invalid-currentfullproposalsfunded'></span>
      <label for="currentfullproposalsfundwithchange">Number of Full proposals; funded with changes<span> </span></label>
	<input type="text" name="currentfullproposalsfundwithchange" class="form-input number" value="<?=$main['currentfullproposalsfundwithchange']?>" required /><span id='invalid-currentfullproposalsfundwithchange'></span>
      <label for="currentfullproposalsdeferred">Number of Full proposals; deferred<span> </span></label>
	<input type="text" name="currentfullproposalsdeferred" class="form-input number" value="<?=$main['currentfullproposalsdeferred']?>" required /><span id='invalid-currentfullproposalsdeferred'></span>
      <label for="currentfullproposalsresubmitting">Number of Full proposals; resubmitting<span> </span></label>
	<input type="text" name="currentfullproposalsresubmitting" class="form-input number" value="<?=$main['currentfullproposalsresubmitting']?>" required /><span id='invalid-currentfullproposalsresubmitting'></span>
      <label for="currentfullproposalstransferred">Number of Full proposals; transferred<span> </span></label>
	<input type="text" name="currentfullproposalstransferred" class="form-input number" value="<?=$main['currentfullproposalstransferred']?>" required /><span id='invalid-currentfullproposalstransferred'></span>
    <label for="currentfullproposalsrejected">Number of Full proposals; rejected<span> </span></label>
	<input type="text" name="currentfullproposalsrejected" class="form-input number" value="<?=$main['currentfullproposalsrejected']?>" required /><span id='invalid-currentfullproposalsrejected'></span>
    <label for="currentfullproposalsdecisionpending">Number of Full proposals; decision pending<span> </span></label>
	<input type="text" name="currentfullproposalsdecisionpending" class="form-input number" value="<?=$main['currentfullproposalsdecisionpending']?>" required /><span id='invalid-currentfullproposalsdecisionpending'></span>
    <p style="clear:both">&nbsp;</p>
	<!--
    <p>Please record details/references of Full proposals where decisions are pending. <em>You will be asked to update on these in your next activity report. (For CTU reference only)</em></p>
    <p style="clear:both">&nbsp;</p>
		<textarea name="fullproposalsdetail" class="form-input" style="width:640px"><?=$main['fullproposalsdetail']?></textarea>
    <p style="clear:both;">&nbsp;</p>
    -->
    <label for="nihrprojectsstartedduringperiod" class="heading">e) Number of funded NIHR projects started during this reporting period (i.e. project has reached contract start date)<span> </span></label>
	<input type="text" name="nihrprojectsstartedduringperiod" class="form-input number" value="<?=$main['nihrprojectsstartedduringperiod']?>" required /><span id='invalid-nihrprojectsstartedduringperiod'></span>
    <p style="clear:both;">&nbsp;</p>
    <label for="totalcurrentnihrprojects" class="heading">f) Total number of 'current' NIHR funded research projects ('Current' is defined as those projects which are within the contract start and end date. Not just those initiated in the last 12 months)<span> </span></label>
	<input type="text" name="totalcurrentnihrprojects" class="form-input number" value="<?=$main['totalcurrentnihrprojects']?>" required /><span id='invalid-totalcurrentnihrprojects'></span>
     <p style="clear:both">&nbsp;</p>
     <div style="display:block;border:1px #000 solid;padding:10px 10px 10px 10px;">
     <p><em><strong>Full details of all proposals and funded projects should be provided in the following sub-sections</strong></em></p>
     <p>The following 3 questions ask for NIHR project references.</p><p>We have provided a list of NETS projects which we have recorded on our system as linked to your unit. Please use this to assist you in relation to NETS programmes applications. If you cannot find a project listed or there are any which you believe are included in error, please contact us as soon as possible <em><strong>before</strong></em> you submit your report so that we can advise you.</p>
    <p>If the query is in relation to a CCF-managed programme please contact the relevant team at CCF.</p>
    </div>
    <p>&nbsp;</p>
    <h3>3.2 Outline proposals submitted to NIHR in this reporting period</strong></h3>
    <p>&nbsp;</p>
    <p><em>DO NOT include EOI or add-on/bolt-on submissions in this section. These should be recorded in section 3.7.</em></p>
	<p>&nbsp;</p>
    <table cellspacing='0' class="seven ctutable spec" > 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<!--
	<thead>
		<tr>
			<th>Project title</th>
			<th>NIHR Programme</th>
            <th>NIHR Project Reference</th>
			<th>Date Submitted</th>
            <th>CTU CTU Role</th>
            <th>Expected value of project to CTU</th>
            <th style="width:50px;">Status</th>
		</tr>
	</thead>
	<!-- Table Header -->
	<!-- Table Body -->
    <?php
	//If there are 2 then get them
	?>
	<tbody>
		<tr >
        	<?php
				$fullsql1 = "SELECT * FROM ctu_report_section3outline_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY outline_rank";
				$fullqry1 = mysql_query($fullsql1);
				while ($full1 = mysql_fetch_array($fullqry1))
				{
				echo '<tr><td  colspan="2" style="text-align:left;height:10px;"><a class="lnk" href="section3edit.php?outlineselect='. $full1['outline_rank'] .'&report_id='. $_SESSION['report_id'] .'">'. $full1['outline_rank'] .' - '. $full1['reference'] .' - '. $full1['title'] .'</a><a class="del" href="delete3.php?del_outline=del_outline&outline_rank='. $full1['outline_rank'] .'&report_id='. $_SESSION['report_id'] .'" style="float:right;">Delete</a></td></tr>';
				}
				

			
			
			/*
			$outline1sql = "SELECT * FROM ctu_report_section3outline WHERE report_id = '".$_SESSION['report_id']."' AND outline_rank=1";
			$outline1qry = mysql_query($outline1sql);
			$outline1 = mysql_fetch_array($outline1qry); 
			?>
			<td><textarea name="project1title" class="form-input-table" ><?=$outline1['title']?></textarea></td>
			<td><div class="select-wrapper">
			<select name="project1programme">
            	<option>Select</option>
				<option value="HTA" <?php echo ($outline1['programme']=='HTA' ? "selected=\"selected\"" : "")?> >HTA</option>
				<option value="EME" <?php echo ($outline1['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
				<option value="HSDR" <?php echo ($outline1['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
				<option value="PHR" <?php echo ($outline1['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                <option value="RfPB" <?php echo ($outline1['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                <option value="PGfAR" <?php echo ($outline1['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                <option value="i4i" <?php echo ($outline1['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
        	<td><input type="text" name="project1reference" class="form-input-table" value="<?=$outline1['reference']?>"/></td>
			<td><input type="text" class="datepick form-input-table" id="projectdate" name="projectdate" value="<?php echo (strlen($outline1['submitdate'])>0 ? date('d/m/Y',strtotime($outline1['submitdate'])) : "" )?>"/></td>
            <td><textarea name="project1staffinput" class="form-input-table countstyle" ><?=$outline1['staffinput']?></textarea><span class="counter-text"></span></td>
            <td><input type="text" name="project1expectedvalue" class="form-input-table currency" value="<?=$outline1['expectedvalue']?>"/></td>
            <td><div class="select-wrapper">
            <select name="project1status">
            <option>Select</option>
            <option value="Full Proposal Invited" <?php echo ($outline1['status']=='Full Proposal Invited' ? "selected=\"selected\"" : "")?>>Full Proposal Invited</option>
            <option value="Reject" <?php echo ($outline1['status']=='Reject' ? "selected=\"selected\"" : "")?>>Rejected</option>
            <option value="Decision Pending" <?php echo ($outline1['status']=='Decision Pending' ? "selected=\"selected\"" : "")?>>Decision Pending</option>
            </select>
        </div></td>
		</tr><!-- Table Row -->
		<tr class="even">
	        <?php
			$outline2sql = "SELECT * FROM ctu_report_section3outline WHERE report_id = '".$_SESSION['report_id']."' AND outline_rank=2";
			$outline2qry = mysql_query($outline2sql);
			$outline2 = mysql_fetch_array($outline2qry); 
			?>
			<td><textarea name="project2title" class="form-input-table" ><?=$outline2['title']?></textarea></td>
			<td><div class="select-wrapper">
			<select name="project2programme" >
            	<option>Select</option>
				<option value="HTA" <?php echo ($outline2['programme']=='HTA' ? "selected=\"selected\"" : "")?> >HTA</option>
				<option value="EME" <?php echo ($outline2['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
				<option value="HSDR" <?php echo ($outline2['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
				<option value="PHR" <?php echo ($outline2['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                <option value="RfPB" <?php echo ($outline2['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                <option value="PGfAR" <?php echo ($outline2['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                <option value="i4i" <?php echo ($outline2['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
        	<td><input type="text" name="project2reference" class="form-input-table" value="<?=$outline2['reference']?>"/></td>
			<td><input type="text" class="datepick form-input-table" id="project2date" name="project2date" value="<?php echo (strlen($outline2['submitdate'])>0 ? date('d/m/Y',strtotime($outline2['submitdate'])) : "" )?>"/></td>
            <td><textarea name="project2staffinput" class="form-input-table countstyle" ><?=$outline2['staffinput']?></textarea><span class="counter-text"></span></td>
            <td><input type="text" name="project2expectedvalue" class="form-input-table currency" value="<?=$outline2['expectedvalue']?>"/></td>
            <td width="50px;"><div class="select-wrapper">
            <select name="project2status">
            <option>Select</option>
            <option value="Full Proposal Invited" <?php echo ($outline2['status']=='Full Proposal Invited' ? "selected=\"selected\"" : "")?>>Full Proposal Invited</option>
            <option value="Reject" <?php echo ($outline2['status']=='Reject' ? "selected=\"selected\"" : "")?>>Rejected</option>
            <option value="Decision Pending" <?php echo ($outline2['status']=='Decision Pending' ? "selected=\"selected\"" : "")?>>Decision Pending</option>
            </select>
        </div></td>
		</tr><!-- Darker Table Row -->
        <?php
		//any more?
		$outlinessql = "SELECT * FROM ctu_report_section3outline WHERE report_id = '".$_SESSION['report_id']."' AND outline_rank>2 order by outline_rank";
		$outlinesqry = mysql_query($outlinessql);
		while($outlines = mysql_fetch_array($outlinesqry))
		{	
		?>
        <tr <?php echo ($outlines['outline_rank']%2==0 ? 'class="even"' : '')?>>
        <td><textarea name="project<?=$outlines['outline_rank']?>title" class="form-input-table"><?=$outlines['title']?></textarea></td>
			<td><div class="select-wrapper">
			<select name="project<?=$outlines['outline_rank']?>programme" >
            	<option>Select</option>
				<option value="HTA" <?php echo ($outlines['programme']=='HTA' ? "selected=\"selected\"" : "")?> >HTA</option>
				<option value="EME" <?php echo ($outlines['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
				<option value="HSDR" <?php echo ($outlines['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
				<option value="PHR" <?php echo ($outlines['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                <option value="RfPB" <?php echo ($outlines['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                <option value="PGfAR" <?php echo ($outlines['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                <option value="i4i" <?php echo ($outlines['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
        	<td><input type="text" name="project<?=$outlines['outline_rank']?>reference" class="form-input-table" value="<?=$outlines['reference']?>"/></td>
			<td><input type="text" class="datepick form-input-table" id="project<?=$outlines['outline_rank']?>date" name="project<?=$outlines['outline_rank']?>date" value="<?php echo (strlen($outlines['submitdate'])>0 ? date('d/m/Y',strtotime($outlines['submitdate'])) : "" )?>"/></td>
            <td><textarea name="project<?=$outlines['outline_rank']?>staffinput" class="form-input-table countstyle"><?=$outlines['staffinput']?></textarea><span class="counter-text"></span></td>
            <td><input type="text" name="project<?=$outlines['outline_rank']?>expectedvalue" class="form-input-table currency" value="<?=$outlines['expectedvalue']?>"/></td>
            <td width="50px;"><div class="select-wrapper">
            <select name="project<?=$outlines['outline_rank']?>status">
            <option>Select</option>
            <option value="Full Proposal Invited" <?php echo ($outlines['status']=='Full Proposal Invited' ? "selected=\"selected\"" : "")?>>Full Proposal Invited</option>
            <option value="Reject" <?php echo ($outlines['status']=='Reject' ? "selected=\"selected\"" : "")?>>Rejected</option>
            <option value="Decision Pending" <?php echo ($outlines['status']=='Decision Pending' ? "selected=\"selected\"" : "")?>>Decision Pending</option>
            </select>
        </div></td>
        </tr>
        <?php
        $projectoutlinerows = $outlines['outline_rank'];
		}
		
		*/
		?>
        <tr id="outlinebuttonrow">
        	<td colspan="7"><span id='invalid-projectdate'></span><span style="display:block;text-align:right"><input type="button" class="form-btn" id="removeoutlinebutton" value="remove last proposal" style="display:none;">&nbsp;&nbsp;<input type="button" class="form-btn" id="addoutlinebutton" value="add another proposal"><input class="form-btn" type="submit" name="save" value="Save" /> </span></td>
        </tr>
	</tbody>
	<!-- Table Body -->
</table>
<p style="clear:both">&nbsp;</p>
 <p>&nbsp;</p>
    <h3>3.3 Full proposals submitted to NIHR in this reporting period</strong></h3>
    <p>&nbsp;</p>
    <p>Please provide full details on each proposal</p>
        <p>&nbsp;</p>
    <table cellspacing='0' class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<thead>
    	<tr>
        	<th>
          <p><span style="display:block; text-align:left"><b>Full Proposal Details</b></span></p>
            </th>
        </tr>
    </thead>
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>

        
        <?php
		//OK Lets get the first project!
		//$fullsql1 = "SELECT * FROM ctu_report_section3full WHERE report_id = '".$_SESSION['report_id']."' AND full_rank=1";
		//$fullqry1 = mysql_query($fullsql1);
		//$full1 = mysql_fetch_array($fullqry1);
		
			//echo '<tr><td style="text-align:left;"><a href="section3edit.php?projectselect='. $full['full_rank'] .'">'. $full['title'] .'</a></td></tr>';
			//echo '<tr><td style="text-align:left;"><h2>'. $full['ctu_id'] .' - '. $full['name'] .'</h2></td></tr>';
			
			$fullsql1 = "SELECT * FROM ctu_report_section3full_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY full_rank";
			$fullqry1 = mysql_query($fullsql1);
			while ($full1 = mysql_fetch_array($fullqry1))
			{
			echo '<tr><td style="text-align:left;"><a class="lnk" href="section3edit.php?projectselect='. $full1['full_rank'] .'&report_id='. $_SESSION['report_id'] .'">'. $full1['full_rank'] .' - '. $full1['nihrprojectref'] .' - '. $full1['title'] .'</a>
			<a class="del" href="delete3.php?del_project=del_project&project_rank='. $full1['full_rank'] .'&report_id='. $_SESSION['report_id'] .'" style="float:right;">Delete</a></td></tr>';
			}
		
		
		
		
?>
		
        <tr id="fullbuttonrow">
        	<td colspan="7"><span style="display:block;text-align:right"><input type="button" class="form-btn" id="removefullbutton" value="remove last proposal" style="display:none;">&nbsp;&nbsp;<input type="button" class="form-btn" id="addfullbutton" value="add another proposal"><input class="form-btn" type="submit" name="save" value="Save" /> </span></td>
        </tr>
<!-- Darker Table Row -->
	</tbody>
	<!-- Table Body -->
</table>
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
		echo '<input class="form-btn" name="save" id="sub" type="submit" value="Save and Continue" />	<br/>';
		echo '<input class="form-btn" type="button" name="exit" id="exit" value="Exit" />';
	}


?>
<input name="req_type" type="hidden" value="activity"></input>

	</form>
    <div id="dialog-confirm" title="Really Exit?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 7 7px 20px 0;"></span>Warning!<br />If you haven’t clicked save,<br /> items will be lost!</p>
    </div>
	<div id="dialog-delete" title="Really Delete?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 7 7px 20px 0;"></span>Warning!<br />Are you sure you wish to delete this?</p>
    </div>
	
	
	
	
    <div id="dialog-remove" title="Really Remove Proposal?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 5px 0;"></span>You are about to remove the last proposal you added. Are you sure you want to do this?</p>
    </div>

</body>
</html>