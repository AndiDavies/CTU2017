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
   
	<script>
$(document).ready(function()
{
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
		

	var projectcurrentrows = <?=$projectcurrentrows+1?>;
	var projectclosedrows = <?=$projectclosedrows+1?>;
	sumjq = function(selector) {
			 var sum = 0;
			 $(selector).each(function() {
				 if($(this).val().length>0)
				 {
				 sum += parseFloat($(this).val().replace(new RegExp("£","g"),'').replace(new RegExp(",","g"),''));	 
				 }
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
				if ($('input[name=currentproject'+current+'totalfundingreceived]').val().length>0)
				{
				sum+= parseFloat(($('input[name=currentproject'+current+'totalfundingreceived]').val().replace(new RegExp("£","g"),'').replace(new RegExp(",","g"),'')));
				}
			}
		}
		return (sum);
	}
	var tempvar = '';
	/*$('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]').change(function() {
			 $('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			 $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
			 $('.currency').formatCurrency({ symbol : '£'});
		  });
	$('input[name$="NIHRoffset"]').change(function() {
		$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			 $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
			 $('.currency').formatCurrency({ symbol : '£'});
	});
	*/
	$('.currency').formatCurrency({ symbol : '£'});

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
		
		$('.del').click(function(event) {
		//event.preventDefault();
		(event.preventDefault) ? event.preventDefault() : event.returnValue = false;
		var lll = $(this).attr('href');
		//console.log(lll);
			 $( "#dialog-delete" ).dialog({
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: {
					"Delete": function() {
						$( this ).dialog( "close" );
						document.location.href=lll;
						
					},
					Cancel: function() {
					$( this ).dialog( "close" );
					}
					}
				});
		});
		
		$('#removecurrentbutton').click(function() {
			 $( "#dialog-remove" ).dialog({
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: {
					"Remove": function() {
						$( this ).dialog( "close" );
						$('#currentbuttonrow').prev().fadeOut(300, function() {
								$(this).remove();
								//$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 					 			$('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
								$('.currency').formatCurrency({ symbol : '£'});
						});
						if (projectcurrentrows==3)
							{
								$('#removecurrentbutton').hide();
							}	
						projectcurrentrows--;
						
					},
					Cancel: function() {
					$( this ).dialog( "close" );
					}
					}
				});
		});
		$('#removeclosedbutton').click(function() {
			 $( "#dialog-remove" ).dialog({
				resizable: false,
				height:200,
				width:350,
				show:400,
				modal: true,
				buttons: {
					"Remove": function() {
						$( this ).dialog( "close" );
						$('#closedbuttonrow').prev().fadeOut(300, function() {$(this).remove();});
						if (projectclosedrows==4)
							{
								$('#removeclosedbutton').hide();
							}	
						projectclosedrows--;
					},
					Cancel: function() {
					$( this ).dialog( "close" );
					}
					}
				});
		});
		jQuery.extend(jQuery.validator.messages, {
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
	jQuery.validator.addMethod("valueNotEquals", function(value, element, arg) {
		return arg != value;
	}, "Required");
	jQuery.validator.addMethod('currency', function(value, element) {
		var result = this.optional(element) || value.match(/^\xA3?\d{1,3}?([,]\d{3}|\d)*?([.]\d{1,2})?$/);
		return result;
	}, 'Please specify an amount in GBP');
	jQuery.validator.addMethod("wordCount",
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
	jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.appendTo('#invalid-' + element.attr('name'));
			}
	});
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
	$('.currency').blur(function()
			{
				$(this).formatCurrency({ symbol : '£'});
			});
		$('.currency').formatCurrency({ symbol : '£'});
		/*$("#section3b").validate({
			rules: {
						wouldyoulikechangetofunding: { valueNotEquals: "null" 
						},
						fundingchangesupport: { required: function(element) {
							return $("#wouldyoulikechangetofunding option:selected").text() == "Yes";
								},

						wordCount: ['250']
						},			
						currentproject1iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject1fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject2iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject2fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject3iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject3fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject4iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject4fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject5iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject5fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject6iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject6fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject7iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject7fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject8iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject8fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject9iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject9fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject10iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject10fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject11iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject11fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject12iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject12fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject13iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject13fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject14iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject14fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject15iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject15fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject16iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject16fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject17iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject17fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject18iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject18fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject19iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject19fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject20iffundingnotreceivedinperiod: { required: function(element) {
							return $("select[name=currentproject20fundingreceivedthisperiod] option:selected").text() == "No";
							 } 
						},
						currentproject1whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject1contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject2whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject2contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject3whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject3contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject4whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject4contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject5whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject5contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject6whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject6contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject7whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject7contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject8whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject8contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject9whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject9contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject10whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject10contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject11whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject11contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject12whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject12contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject13whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject13contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject14whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject14contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject15whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject15contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject16whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject16contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject17whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject17contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject18whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject18contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject19whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject19contractextension] option:selected").text() == "Yes";
							 } 
						},
						currentproject20whyextensiongranted: { required: function(element) {
							return $("select[name=currentproject20contractextension] option:selected").text() == "Yes";
							 } 
						}
					},
			messages: {
						fundingchangesupport: { required: "Please provide a brief supporting statement" }
					}
			});
		$(".number").each(function() {
			$(this).rules('add', {
			number: true 
			});
		});
		$(".currency").each(function() {
			$(this).rules('add', {
			currency: true 
			});
		});
		$(".datepick").each(function() {
			$(this).rules('add', {
			date: true 
			});
		});
		$('.wordcount300').each(function() {
			$(this).rules('add', {
			wordCount: ['300']
			});
		});
		$('.wordcount250').each(function() {
			$(this).rules('add', {
			wordCount: ['250']
			});
		});
		$('.wordcount200').each(function() {
			$(this).rules('add', {
			wordCount: ['200']
			});
		});
		$('.wordcount50').each(function() {
			$(this).rules('add', {
			wordCount: ['50']
			});
		});*/
		$(".datepick").each(function() 
		{
			$(this).rules('add', {
			date: true 
			});
		});
		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		$("textarea[name$=expectedinput]").textareaCounter({limit:50});
		$("textarea[name$=nonstaffdesc]").textareaCounter({limit:50});
		$("textarea[name$=reason]").textareaCounter({limit:50});
		$("textarea[name=furthercommentsonnihrapplications]").textareaCounter({limit:300});
		//$("textarea[name=IPdetails]").textareaCounter({limit:250});
		$("textarea[name=fundingchangesupport]").textareaCounter({limit:250});
		
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
		view = getQueryVariable('view');
		if (view == 'view')
		{
			//console.log ('MOO');
			$('#addclosedbutton').hide();
			$('#addcurrentbutton').hide();
			$('.form-btn[name="save"]').hide();
			$('input, textarea').attr('readonly', true);
			$('select').attr("disabled", true);
			$('.del').hide();
			$("a.lnk").each(function() {
			   var _href = $(this).attr("href"); 
			   $(this).attr("href", _href + '&view=view');
			});
		}
		validate = getQueryVariable('validate');
		if (validate == 'V')
		{
			//console.log('validate = V found');
			$("form").validate({
			
				rules: 
				{
				wouldyoulikechangetofunding: { valueNotEquals: "null" 
						},
						fundingchangesupport: { required: function(element) {
							return $("#wouldyoulikechangetofunding option:selected").text() == "Yes";
								},

						wordCount: ['250']
						},		
				}
			});
			//console.log('about to validate');
			//$('form').validate();
			//console.log('done');
			$('form').submit();
			
			
			
			
			

		}
		
		
		
		
		
		
		$('input[name=back]').click(function() {
			 document.location.href="reporthome.php";	
		});
		
		
		
		
		
		 //$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]'));
		// $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
		 $('.currency').formatCurrency({ symbol : '£'});

		$("#addcurrentbutton").click(function() {
		if ($.browser.msie  && parseInt($.browser.version, 10) === 8)
		{
			if (projectcurrentrows%2==0)
				{
          		$('#currentbuttonrow').before('<tr class="even"> <td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="currentprojecttitle[]">Project title<span> </span></label> <textarea type="text" style="width:500px;height:50px;" name="currentprojecttitle[]" class="form-input"> </textarea> <label for="currentproject[]programme[]">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="currentprojectnihrprojectref"[]>NIHR project reference<span></span></label> <input type="text" name="currentprojectnihrprojectref[]" class="form-input"  /> <label for="currentprojectstartdate[]">Project Start Date (contract start date)<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="currentprojectstartdate[]" name="currentprojectstartdate[]" /> <label for="currentprojectduration[]">Project duration (months)<span> </span></label> <input type="text" name="currentprojectduration[]" class="form-input number"  /> <label for="currentprojectcurrentstatus[]">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcurrentstatus[]"> <option>Select</option> <option value="planning">Planning</option> <option value="recruitment">Recruitment</option> <option value="analysis">Analysis</option> <option value="other">Other</option> </select> </div> <label for="currentprojectplannedrecruitmenttotal[]">Planned recruitment total<span></span></label> <input type="text" name="currentprojectplannedrecruitmenttotal[]" class="form-input number" /> <label for="currentprojectnumberofprojectsites[]">Number of project sites<span> </span></label> <input type="text" name="currentprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectintmultisite[]" id="currentcheck1[]" /> <label for="currentcheck1[]"></label> </div> <label for="currentprojectexpectedinput[]">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="currentprojectexpectedinput[]" class="form-input limit" style="width:640px;"></textarea><span class="counter-text"></span>   </div> <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/> <div class="form-block">  <label for="currentprojecttotalcost[]">Total cost of project<span> </span></label> <input type="text" name="currentprojecttotalcost[]" class="form-input currency"  /> <label for="currentprojectexpectedvalue[]">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="currentprojectexpectedvalue[]" class="form-input currency"  /> <span class="counter-text"></span>  <label for="currentprojectfundingreceivedthisperiod[]">Was funding received in this reporting period for this project?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectfundingreceivedthisperiod[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div> <label for="currentprojectiffundingnotreceivedinperiod[]">If no, please describe why funding has not been received</label> <input type="text" name="currentprojectiffundingnotreceivedinperiod[]" class="form-input" />  <label for="currentprojecttotalfundingreceived[]">Total funding received in this reporting period from original contract award*</label> <input type="text" name="currentprojecttotalfundingreceived[]" class="form-input currency" />  <label for="currentprojectfundingreceivedthisperiod[]">Has project received a contract extension?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcontractextension[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div>  <label for="currentprojectwhyextensiongranted[]">If yes, why was extension granted?</label> <input type="text" name="currentprojectwhyextensiongranted[]" class="form-input" />  <label for="currentprojecttotalvalueofextension[]">Total value of contract extension</label> <input type="text" name="currentprojecttotalvalueofextension[]" class="form-input currency" />  <label for="currentprojectvalueofextensiontounit[]">Expected value of contract extension to your unit</label> <input type="text" name="currentprojectvalueofextensiontounit[]" class="form-input currency" />  <label for="currentprojectadditionalfundingfromcontractextension[]">Additional funding received this reporting period from contract extension (if applicable)</label> <input type="text" name="currentprojectadditionalfundingfromcontractextension[]" class="form-input currency" /> <p style="clear:both;">&nbsp;</p> <p class="option-label">Does the project meet the NIHR criteria for offset?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectNIHRoffset[]" id="currentcheck2[]" /> <label for="currentcheck2[]"></label> </div> </div> </td> <input type="hidden" name="new_current[]" value="new_current"></tr><!-- Table Row -->');
				}
		  	else
		 	 	{
				$('#currentbuttonrow').before('<tr class="even"> <td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="currentprojecttitle[]">Project title<span> </span></label><textarea type="text" style="width:500px;height:50px;" name="currentprojecttitle[]" class="form-input"> </textarea> <label for="currentproject[]programme[]">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="currentprojectnihrprojectref"[]>NIHR project reference<span></span></label> <input type="text" name="currentprojectnihrprojectref[]" class="form-input"  /> <label for="currentprojectstartdate[]">Project Start Date (contract start date)<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="currentprojectstartdate[]" name="currentprojectstartdate[]" /> <label for="currentprojectduration[]">Project duration (months)<span> </span></label> <input type="text" name="currentprojectduration[]" class="form-input number"  /> <label for="currentprojectcurrentstatus[]">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcurrentstatus[]"> <option>Select</option> <option value="planning">Planning</option> <option value="recruitment">Recruitment</option> <option value="analysis">Analysis</option> <option value="other">Other</option> </select> </div> <label for="currentprojectplannedrecruitmenttotal[]">Planned recruitment total<span></span></label> <input type="text" name="currentprojectplannedrecruitmenttotal[]" class="form-input number" /> <label for="currentprojectnumberofprojectsites[]">Number of project sites<span> </span></label> <input type="text" name="currentprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectintmultisite[]" id="currentcheck1[]" /> <label for="currentcheck1[]"></label> </div> <label for="currentprojectexpectedinput[]">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="currentprojectexpectedinput[]" class="form-input limit" style="width:640px;"></textarea><span class="counter-text"></span>   </div> <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/> <div class="form-block">  <label for="currentprojecttotalcost[]">Total cost of project<span> </span></label> <input type="text" name="currentprojecttotalcost[]" class="form-input currency"  /> <label for="currentprojectexpectedvalue[]">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="currentprojectexpectedvalue[]" class="form-input currency"  /> <span class="counter-text"></span>  <label for="currentprojectfundingreceivedthisperiod[]">Was funding received in this reporting period for this project?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectfundingreceivedthisperiod[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div> <label for="currentprojectiffundingnotreceivedinperiod[]">If no, please describe why funding has not been received</label> <input type="text" name="currentprojectiffundingnotreceivedinperiod[]" class="form-input" />  <label for="currentprojecttotalfundingreceived[]">Total funding received in this reporting period from original contract award*</label> <input type="text" name="currentprojecttotalfundingreceived[]" class="form-input currency" />  <label for="currentprojectfundingreceivedthisperiod[]">Has project received a contract extension?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcontractextension[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div>  <label for="currentprojectwhyextensiongranted[]">If yes, why was extension granted?</label> <input type="text" name="currentprojectwhyextensiongranted[]" class="form-input" />  <label for="currentprojecttotalvalueofextension[]">Total value of contract extension</label> <input type="text" name="currentprojecttotalvalueofextension[]" class="form-input currency" />  <label for="currentprojectvalueofextensiontounit[]">Expected value of contract extension to your unit</label> <input type="text" name="currentprojectvalueofextensiontounit[]" class="form-input currency" />  <label for="currentprojectadditionalfundingfromcontractextension[]">Additional funding received this reporting period from contract extension (if applicable)</label> <input type="text" name="currentprojectadditionalfundingfromcontractextension[]" class="form-input currency" /> <p style="clear:both;">&nbsp;</p> <p class="option-label">Does the project meet the NIHR criteria for offset?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectNIHRoffset[]" id="currentcheck2[]" /> <label for="currentcheck2[]"></label> </div> </div> </td> <input type="hidden" name="new_current[]" value="new_current"></tr><!-- Table Row -->');
				}
		}
		else
		{
			if (projectcurrentrows%2==0)
				{
				$('#currentbuttonrow').before('<tr class="even"> <td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="currentprojecttitle[]">Project title<span> </span></label><textarea type="text" style="width:500px;height:50px;" name="currentprojecttitle[]" class="form-input"> </textarea> <label for="currentprojectprogramme[]">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="currentprojectnihrprojectref[]">NIHR project reference<span></span></label> <input type="text" name="currentprojectnihrprojectref[]" class="form-input"  /> <label for="currentprojectstartdate[]">Project Start Date (contract start date)<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="currentprojectstartdate[]" name="currentprojectstartdate[]" /> <label for="currentprojectduration[]">Project duration (months)<span> </span></label> <input type="text" name="currentprojectduration[]" class="form-input number"  /> <label for="currentprojectcurrentstatus[]">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcurrentstatus[]"> <option>Select</option> <option value="planning">Planning</option> <option value="recruitment">Recruitment</option> <option value="analysis">Analysis</option> <option value="other">Other</option> </select> </div> <label for="currentprojectplannedrecruitmenttotal[]">Planned recruitment total<span></span></label> <input type="text" name="currentprojectplannedrecruitmenttotal[]" class="form-input number" /> <label for="currentprojectnumberofprojectsites[]">Number of project sites<span> </span></label> <input type="text" name="currentprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectintmultisite[]" id="currentcheck1[]" /> <label for="currentcheck1[]"></label> </div> <label for="currentprojectexpectedinput[]">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="currentprojectexpectedinput[]" class="form-input limit" style="width:640px;"></textarea><span class="counter-text"></span>   </div> <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/> <div class="form-block">  <label for="currentprojecttotalcost[]">Total cost of project<span> </span></label> <input type="text" name="currentprojecttotalcost[]" class="form-input currency"  /> <label for="currentprojectexpectedvalue[]">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="currentprojectexpectedvalue[]" class="form-input currency"  /> <span class="counter-text"></span>  <label for="currentprojectfundingreceivedthisperiod[]">Was funding received in this reporting period for this project?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectfundingreceivedthisperiod[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div> <label for="currentprojectiffundingnotreceivedinperiod[]">If no, please describe why funding has not been received</label> <input type="text" name="currentprojectiffundingnotreceivedinperiod[]" class="form-input" />  <label for="currentprojecttotalfundingreceived[]">Total funding received in this reporting period from original contract award*</label> <input type="text" name="currentprojecttotalfundingreceived[]" class="form-input currency" />  <label for="currentprojectfundingreceivedthisperiod[]">Has project received a contract extension?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcontractextension[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div>  <label for="currentprojectwhyextensiongranted[]">If yes, why was extension granted?</label> <input type="text" name="currentprojectwhyextensiongranted[]" class="form-input" />  <label for="currentprojecttotalvalueofextension[]">Total value of contract extension</label> <input type="text" name="currentprojecttotalvalueofextension[]" class="form-input currency" />  <label for="currentprojectvalueofextensiontounit[]">Expected value of contract extension to your unit</label> <input type="text" name="currentprojectvalueofextensiontounit[]" class="form-input currency" />  <label for="currentprojectadditionalfundingfromcontractextension[]">Additional funding received this reporting period from contract extension (if applicable)</label> <input type="text" name="currentprojectadditionalfundingfromcontractextension[]" class="form-input currency" /> <p style="clear:both;">&nbsp;</p> <p class="option-label">Does the project meet the NIHR criteria for offset?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectNIHRoffset[]" id="currentcheck2[]" /> <label for="currentcheck2[]"></label> </div> </div> </td> <input type="hidden" name="new_current[]" value="new_current"></tr><!-- Table Row -->');
				}
			else
				{
					$('#currentbuttonrow').before('<tr class="even"> <td><p><span class="tablesubheading"><b>Project</b><br /><strong>Project general information</strong></span></p><br/> <div class="form-block"> <label for="currentprojecttitle[]">Project title<span> </span></label> <textarea type="text" style="width:500px;height:50px;" name="currentprojecttitle[]" class="form-input"> </textarea> <label for="currentprojectprogramme[]">NIHR Programme<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&amp;DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select> </div> <label for="currentprojectnihrprojectref[]">NIHR project reference<span></span></label> <input type="text" name="currentprojectnihrprojectref[]" class="form-input"  /> <label for="currentprojectstartdate[]">Project Start Date (contract start date)<span> </span></label> <input type="text" class="datepick form-input-table form-datepick" id="currentprojectstartdate[]" name="currentprojectstartdate[]" /> <label for="currentprojectduration[]">Project duration (months)<span> </span></label> <input type="text" name="currentprojectduration[]" class="form-input number"  /> <label for="currentprojectcurrentstatus[]">Current Status<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcurrentstatus[]"> <option>Select</option> <option value="planning">Planning</option> <option value="recruitment">Recruitment</option> <option value="analysis">Analysis</option> <option value="other">Other</option> </select> </div> <label for="currentprojectplannedrecruitmenttotal[]">Planned recruitment total<span></span></label> <input type="text" name="currentprojectplannedrecruitmenttotal[]" class="form-input number" /> <label for="currentprojectnumberofprojectsites[]">Number of project sites<span> </span></label> <input type="text" name="currentprojectnumberofprojectsites[]" class="form-input number"  /> <p class="option-label">Is this an international multi-site project?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectintmultisite[]" id="currentcheck1[]" /> <label for="currentcheck1[]"></label> </div> <label for="currentprojectexpectedinput[]">Describe your unit&#39;s expected level of input <span></span></label> <textarea name="currentprojectexpectedinput[]" class="form-input limit" style="width:640px;"></textarea><span class="counter-text"></span>   </div> <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/> <div class="form-block">  <label for="currentprojecttotalcost[]">Total cost of project<span> </span></label> <input type="text" name="currentprojecttotalcost[]" class="form-input currency"  /> <label for="currentprojectexpectedvalue[]">Expected value of project to your unit over course of project<span> </span></label> <input type="text" name="currentprojectexpectedvalue[]" class="form-input currency"  /> <span class="counter-text"></span>  <label for="currentprojectfundingreceivedthisperiod[]">Was funding received in this reporting period for this project?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectfundingreceivedthisperiod[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div> <label for="currentprojectiffundingnotreceivedinperiod[]">If no, please describe why funding has not been received</label> <input type="text" name="currentprojectiffundingnotreceivedinperiod[]" class="form-input" />  <label for="currentprojecttotalfundingreceived[]">Total funding received in this reporting period from original contract award*</label> <input type="text" name="currentprojecttotalfundingreceived[]" class="form-input currency" />  <label for="currentprojectfundingreceivedthisperiod[]">Has project received a contract extension?<span> </span></label> <div class="select-wrapper form-select"> <select name="currentprojectcontractextension[]" > <option>Select</option> <option value="yes">Yes</option> <option value="no">No</option> </select> </div>  <label for="currentprojectwhyextensiongranted[]">If yes, why was extension granted?</label> <input type="text" name="currentprojectwhyextensiongranted[]" class="form-input" />  <label for="currentprojecttotalvalueofextension[]">Total value of contract extension</label> <input type="text" name="currentprojecttotalvalueofextension[]" class="form-input currency" />  <label for="currentprojectvalueofextensiontounit[]">Expected value of contract extension to your unit</label> <input type="text" name="currentprojectvalueofextensiontounit[]" class="form-input currency" />  <label for="currentprojectadditionalfundingfromcontractextension[]">Additional funding received this reporting period from contract extension (if applicable)</label> <input type="text" name="currentprojectadditionalfundingfromcontractextension[]" class="form-input currency" /> <p style="clear:both;">&nbsp;</p> <p class="option-label">Does the project meet the NIHR criteria for offset?</p> <div class="option-group check"> <input type="checkbox" name="currentprojectNIHRoffset[]" id="currentcheck2[]" /> <label for="currentcheck2[]"></label> </div> </div> </td> <input type="hidden" name="new_current[]" value="new_current"></tr><!-- Table Row -->');
				}
		}
		  //$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]'));
		  //$('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr()); 
		  $('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]').change(function() {
			 //$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			 $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
			 $('.currency').formatCurrency({ symbol : '£'}); 
		  });
		  $('input[name$="NIHRoffset"]').change(function() {
		//$('input[name=totalunitincomefromnihrfundedprojects]').val(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]')); 
			// $('input[name=totalunitincomefromnihrfundedprojectsmeetoffset]').val(sumnihr());
			 $('.currency').formatCurrency({ symbol : '£'});
		  });
		 // alert(sumjq('input[name$="totalfundingreceived"],input[name$="additionalfundingfromcontractextension"]'));
		  projectcurrentrows++;
		  $('#removecurrentbutton').show();
		  $('.datepick').datepicker({dateFormat: "dd/mm/yy"});
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
		$("textarea[name$=expectedinput]").textareaCounter({limit:50});
		$("textarea[name$=nonstaffdesc]").textareaCounter({limit:50});
		$('.currency').blur(function()
			{
				$(this).formatCurrency({ symbol : '£'});
			});
		$('.currency').formatCurrency({ symbol : '£'});
		  return false;
        });
		$("#addclosedbutton").click(function() {
		if ($.browser.msie  && parseInt($.browser.version, 10) === 8)
		{
			if (projectclosedrows%2==0)
				{
          		$('#closedbuttonrow').before('<tr class="even"><td><label for="closedtitle">Title</label></td><td><textarea type="text" style="width:500px;height:50px;" name="closedtitle[]" class="form-input-table" ></textarea></td></tr><tr><td><label for="closedprogramme">Programme</label></td><td><div class="select-wrapper"> <select name="closedprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select></div></td></tr><tr><td><label for="closedreference">Reference</label></td><td><input type="text" name="closedreference[]" class="form-input-table" /></td><td><label for="closedreason">Reason</label></td><td><textarea name="closedreason[]" class="form-input-table wordcount50"></textarea><span class="counter-text"></span></td><input type="hidden" name="new_closed[]" value="new_closed"></tr><!-- Table Row --><!-- Table Row -->');
				}
		  	else
		 	 	{
		  		$('#closedbuttonrow').before('<tr><td><label for="closedtitle">Title</label></td><td><textarea type="text" style="width:500px;height:50px;" name="closedtitle[]" class="form-input-table" ></textarea></td></tr><tr><td><label for="closedprogramme">Programme</label></td><td><div class="select-wrapper"> <select name="closedprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select></div></td></tr><tr><td><label for="closedreference">Reference</label></td><td><input type="text" name="closedreference[]" class="form-input-table" /></td></tr><tr><td><label for="closedreason">Reason</label></td><td><textarea name="closedreason[]" class="form-input-table wordcount50"></textarea><span class="counter-text"></span></td><input type="hidden" name="new_closed[]" value="new_closed"></tr><!-- Table Row --><!-- Table Row -->');
		  		}
		}
		else
		{
			if (projectclosedrows%2==0)
				{
				$('#closedbuttonrow').before('<tr class="even"><td><label for="closedtitle">Title</label></td><td><textarea type="text" style="width:500px;height:50px;" name="closedtitle[]" class="form-input-table" ></textarea></td></tr><tr><td><label for="closedprogramme">Programme</label></td><td><div class="select-wrapper"> <select name="closedprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select></div></td></tr><tr><td><label for="closedreference">Reference</label></td><td><input type="text" name="closedreference[]" class="form-input-table" /></td></tr><tr><td><label for="closedreason">Reason</label></td><td><textarea name="closedreason[]" class="form-input-table wordcount50"></textarea><span class="counter-text"></span></td><input type="hidden" name="new_closed[]" value="new_closed"></tr><!-- Table Row --><!-- Table Row -->');
				}
			else
				{
				$('#closedbuttonrow').before('<tr><td><label for="closedtitle">Title</label></td><td><textarea type="text" style="width:500px;height:50px;" name="closedtitle[]" class="form-input-table" ></textarea></td></tr><tr><td><label for="closedprogramme">Programme</label></td><td><div class="select-wrapper"> <select name="closedprogramme[]"> <option>Select</option> <option value="HTA">HTA</option> <option value="EME">EME</option> <option value="HSDR">HS&DR</option> <option value="PHR">PHR</option> <option value="RfPB">RfPB</option> <option value="PGfAR">PGfAR</option> <option value="i4i">i4i</option> </select></div></td></tr><tr><td><label for="closedreference">Reference</label></td><td><input type="text" name="closedreference[]" class="form-input-table" /></td></tr><tr><td><label for="closedreason">Reason</label></td><td><textarea name="closedreason[]" class="form-input-table wordcount50"></textarea><span class="counter-text"></span></td><input type="hidden" name="new_closed[]" value="new_closed"></tr><!-- Table Row --><!-- Table Row -->');
				}
		}
		  projectclosedrows++;
		  $("#removeclosedbutton").show();
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
		$("textarea[name$=reason]").textareaCounter({limit:50});
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
	<form action="save3b.php" method="post" class="general" id="section3b">
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
    <h2>Section 3b: CTU NIHR Activity (Part 2)</h2>
    <h3>This includes all HTA, EME, HS&DR, PHR, PGfAR, i4i and RfPB programme activity</h3>
    <p>&nbsp;</p>
    <h3>3.4 Current NIHR research</strong></h3>  
    <p>&nbsp;</p>
    <div style="display:block;border:1px #000 solid;padding:10px 10px 10px 10px;">
    <p>'Current' is defined as those projects which are within contract start and end dates, and not just those initiated in the last 12 months.</p>
    <span class="callout"><b>Please note that financial accuracy in this section is vital</b><br />
    This information is used to calculate any offset achieved by your unit which is the key criteria in determining unit performance.<br />
    <b>Please note systematic reviews are NOT eligible for offset.</b></span>
    </div>
        <p>&nbsp;</p>
    <table cellspacing='0' class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	<thead>
    	<tr>
        	<th>
         <!-- <p><span style="display:block; text-align:left"><b>Please provide full details for each project</b></span></p>-->
            </th>
        </tr>
    </thead>
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>
		<tr >

        <?php
		
		$currentsql = "SELECT * FROM ctu_report_section3bcurrent_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY current_rank";
				$currentqry = mysql_query($currentsql);
				while ($current = mysql_fetch_array($currentqry))
				{
				echo '<tr><td  colspan="2" style="text-align:left;height:10px;"><a class="lnk" href="section3bedit.php?currentselect='. $current['current_rank'] .'&report_id='. $_SESSION['report_id'] .'">'. $current['current_rank'] .' - '. $current['nihrprojectref'] .' - '. $current['title'] .'</a><a class="del" href="delete3b.php?del_current=del_current&current_rank='. $current['current_rank'] .'&report_id='. $_SESSION['report_id'] .'" style="float:right;">Delete</a></td></tr>';
				}
		
		
		
		
		
		
		
		
		
		
		
		
		
		//OK Lets get the first project!
		/*
		$currentsql1 = "SELECT * FROM ctu_report_section3bcurrent WHERE report_id = '".$_SESSION['report_id']."' AND current_rank=1";
		$currentqry1 = mysql_query($currentsql1);
		$current1 = mysql_fetch_array($currentqry1);
		?>
			<td><p><span class="tablesubheading"><b>Project 1</b><br /><strong>Project general information</strong></span></p><br/>
            <div class="form-block">
            	<label for="currentproject1title">Project title<span> </span></label>
				<input type="text" name="currentproject1title" class="form-input" value="<?=$current1['title']?>"/>
				<label for="currentproject1programme">NIHR Programme<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject1programme">
                    <option>Select</option>
                   <option value="HTA" <?php echo ($current1['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($current1['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($current1['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($current1['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($current1['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($current1['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($current1['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
                </select>
            	</div>
                <label for="currentproject1nihrprojectref">NIHR project reference<span></span></label>
				<input type="text" name="currentproject1nihrprojectref" class="form-input" value="<?=$current1['nihrprojectref']?>" />
                <label for="currentproject1startdate">Project Start Date (contract start date)<span> </span></label>
                <input type="text" class="datepick form-input-table form-datepick" id="currentproject1startdate" name="currentproject1startdate" value="<?php echo (strlen($current1['startdate'])>0 ? date('d/m/Y',strtotime($current1['startdate'])) : "" )?>"/>
                <label for="currentproject1duration">Project duration (months)<span> </span></label>
                <input type="text" name="currentproject1duration" class="form-input number" value="<?=$current1['duration']?>" />
                <label for="currentproject1currentstatus">Current Status<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject1currentstatus">
                    <option>Select</option>
                    <option value="planning" <?php echo ($current1['currentstatus']=='planning' ? "selected=\"selected\"" : "")?>>Planning</option>
					<option value="recruitment" <?php echo ($current1['currentstatus']=='recruitment' ? "selected=\"selected\"" : "")?>>Recruitment</option>
					<option value="analysis" <?php echo ($current1['currentstatus']=='analysis' ? "selected=\"selected\"" : "")?>>Analysis</option>
					<option value="other" <?php echo ($current1['currentstatus']=='other' ? "selected=\"selected\"" : "")?>>Other</option>
                </select>
            	</div>
                <label for="currentproject1plannedrecruitmenttotal">Planned recruitment total<span></span></label>
                <input type="text" name="currentproject1plannedrecruitmenttotal" class="form-input number" value="<?=$current1['plannedrecruitmenttotal']?>" />
                <label for="currentproject1numberofprojectsites">Number of project sites<span> </span></label>
                <input type="text" name="currentproject1numberofprojectsites" class="form-input number" value="<?=$current1['numberofprojectsites']?>" />
                <p class="option-label">Is this an international multi-site project?</p>
               	<div class="option-group check">
				<input type="checkbox" name="currentproject1intmultisite" id="current1check1" <?php echo ($current1['intmultisite']=='yes' ? 'checked' : '' )?> />
				<label for="current1check1" <?php echo ($current1['intmultisite']=='yes' ? 'class="checked"' : '' )?>></label>			
				</div>
                <label for="currentproject1expectedinput">Describe your unit's expected level of input <span></span></label>
				<textarea name="currentproject1expectedinput" class="form-input limit wordcount50" style="width:640px;"><?=$current1['expectedinput']?></textarea><span class="counter-text"></span>
               
               
                </div>
                <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/>
            	<div class="form-block">
             
                <label for="currentproject1totalcost">Total cost of project<span> </span></label>
                <input type="text" name="currentproject1totalcost" class="form-input currency" value="<?=$current1['totalcost']?>" />
                <label for="currentproject1expectedvalue">Expected value of project to your unit over course of project<span> </span></label>
                <input type="text" name="currentproject1expectedvalue" class="form-input currency" value="<?=$current1['expectedvalue']?>" />
                <label for="currentproject1estimatedstaffcosts">Estimated total unit staff costs over course of project<span> </span></label>
                <input type="text" name="currentproject1estimatedstaffcosts" class="form-input currency" value="<?=$current1['estimatedstaffcosts']?>" />
                <label for="currentproject1estimatednonstaffcosts">Estimated total unit non-staff costs over course of project<span> </span></label>
                <input type="text" name="currentproject1estimatednonstaffcosts" class="form-input currency" value="<?=$current1['estimatednonstaffcosts']?>" />
                <label for="currentproject1nonstaffdesc">Brief description of non-staff costs<span></span></label>
				<textarea name="currentproject1nonstaffdesc" class="form-input limit wordcount50" style="width:640px;"><?=$current1['nonstaffdesc']?></textarea><span class="counter-text"></span>
                
                 <label for="currentproject1fundingreceivedthisperiod">Was funding received in this reporting period for this project?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject1fundingreceivedthisperiod" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current1['fundingreceivedthisperiod']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current1['fundingreceivedthisperiod']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                <label for="currentproject1iffundingnotreceivedinperiod">If no, please describe why funding has not been received</label>
                <input type="text" name="currentproject1iffundingnotreceivedinperiod" class="form-input" value="<?=$current1['iffundingnotreceivedinperiod']?>"/>
                
                <label for="currentproject1totalfundingreceived">Total funding received in this reporting period from original contract award*</label>
                <input type="text" name="currentproject1totalfundingreceived" class="form-input currency" value="<?=$current1['totalfundingreceived']?>"/>
                
                  <label for="currentproject1fundingreceivedthisperiod">Has project received a contract extension?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject1contractextension" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current1['contractextension']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current1['contractextension']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                
                <label for="currentproject1whyextensiongranted">If yes, why was extension granted?</label>
                <input type="text" name="currentproject1whyextensiongranted" class="form-input" value="<?=$current1['whyextensiongranted']?>" />
                
                <label for="currentproject1totalvalueofextension">Total value of contract extension</label>
                <input type="text" name="currentproject1totalvalueofextension" class="form-input currency" value="<?=$current1['totalvalueofextension']?>"/>
                
                <label for="currentproject1valueofextensiontounit">Expected value of contract extension to your unit</label>
                <input type="text" name="currentproject1valueofextensiontounit" class="form-input currency" value="<?=$current1['valueofextensiontounit']?>"/>
                
                <label for="currentproject1additionalfundingfromcontractextension">Additional funding received this reporting period from contract extension (if applicable)</label>
                <input type="text" name="currentproject1additionalfundingfromcontractextension" class="form-input currency" value="<?=$current1['additionalfundingfromcontractextension']?>"/>
                <p style="clear:both;">&nbsp;</p>
                <p class="option-label">Does the project meet the NIHR criteria for offset?</p>
                <div class="option-group check">
				<input type="checkbox" name="currentproject1NIHRoffset" id="current1check2" <?php echo ($current1['NIHRoffset']=='yes' ? 'checked' : '' )?>/>
				<label for="current1check2" <?php echo ($current1['NIHRoffset']=='yes' ? 'class="checked"' : '' )?>></label>	
                </div> 
                </div>
            </td>
		</tr><!-- Table Row -->
        <?php
		//Lets get any more rows and output them here
		$currentsql1 = "SELECT * FROM ctu_report_section3bcurrent WHERE report_id = '".$_SESSION['report_id']."' AND current_rank>1 ORDER by current_rank";
		$currentqry1 = mysql_query($currentsql1);
		while ($current1 = mysql_fetch_array($currentqry1))
		{
		?><tr <?php echo ($current1['current_rank']%2==0 ? 'class="even"' : '')?>>
        		<td><p><span class="tablesubheading"><b>Project <?=$current1['current_rank']?></b><br /><strong>Project general information</strong></span></p><br/>
            <div class="form-block">
            	<label for="currentproject<?=$current1['current_rank']?>title">Project title<span> </span></label>
				<input type="text" name="currentproject<?=$current1['current_rank']?>title" class="form-input" value="<?=$current1['title']?>"/>
				<label for="currentproject<?=$current1['current_rank']?>programme">NIHR Programme<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject<?=$current1['current_rank']?>programme">
                    <option>Select</option>
                   <option value="HTA" <?php echo ($current1['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($current1['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($current1['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($current1['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($current1['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($current1['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($current1['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
                </select>
            	</div>
                <label for="currentproject<?=$current1['current_rank']?>nihrprojectref">NIHR project reference<span></span></label>
				<input type="text" name="currentproject<?=$current1['current_rank']?>nihrprojectref" class="form-input" value="<?=$current1['nihrprojectref']?>" />
                <label for="currentproject<?=$current1['current_rank']?>startdate">Project Start Date (contract start date)<span> </span></label>
                <input type="text" class="datepick form-input-table form-datepick" id="currentproject<?=$current1['current_rank']?>startdate" name="currentproject<?=$current1['current_rank']?>startdate" value="<?php echo (strlen($current1['startdate'])>0 ? date('d/m/Y',strtotime($current1['startdate'])) : "" )?>"/>
                <label for="currentproject<?=$current1['current_rank']?>duration">Project duration (months)<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>duration" class="form-input number" value="<?=$current1['duration']?>" />
                <label for="currentproject<?=$current1['current_rank']?>currentstatus">Current Status<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject<?=$current1['current_rank']?>currentstatus">
                    <option>Select</option>
                    <option value="planning" <?php echo ($current1['currentstatus']=='planning' ? "selected=\"selected\"" : "")?>>Planning</option>
					<option value="recruitment" <?php echo ($current1['currentstatus']=='recruitment' ? "selected=\"selected\"" : "")?>>Recruitment</option>
					<option value="analysis" <?php echo ($current1['currentstatus']=='analysis' ? "selected=\"selected\"" : "")?>>Analysis</option>
					<option value="other" <?php echo ($current1['currentstatus']=='other' ? "selected=\"selected\"" : "")?>>Other</option>
                </select>
            	</div>
                <label for="currentproject<?=$current1['current_rank']?>plannedrecruitmenttotal">Planned recruitment total<span></span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>plannedrecruitmenttotal" class="form-input number" value="<?=$current1['plannedrecruitmenttotal']?>" />
                <label for="currentproject<?=$current1['current_rank']?>numberofprojectsites">Number of project sites<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>numberofprojectsites" class="form-input number" value="<?=$current1['numberofprojectsites']?>" />
                <p class="option-label">Is this an international multi-site project?</p>
               	<div class="option-group check">
				<input type="checkbox" name="currentproject<?=$current1['current_rank']?>intmultisite" id="current1check1" <?php echo ($current1['intmultisite']=='yes' ? 'checked' : '' )?> />
				<label for="current1check1" <?php echo ($current1['intmultisite']=='yes' ? 'class="checked"' : '' )?>></label>			
				</div>
                <label for="currentproject<?=$current1['current_rank']?>expectedinput">Describe your unit's expected level of input <span></span></label>
				<textarea name="currentproject<?=$current1['current_rank']?>expectedinput" class="form-input limit wordcount50" style="width:640px;"><?=$current1['expectedinput']?></textarea><span class="counter-text"></span>
               
               
                </div>
                <p><span class="tablesubheading"><strong>Project costs</strong></span></p><br/>
            	<div class="form-block">
             
                <label for="currentproject<?=$current1['current_rank']?>totalcost">Total cost of project<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>totalcost" class="form-input currency" value="<?=$current1['totalcost']?>" />
                <label for="currentproject<?=$current1['current_rank']?>expectedvalue">Expected value of project to your unit over course of project<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>expectedvalue" class="form-input currency" value="<?=$current1['expectedvalue']?>" />
                <label for="currentproject<?=$current1['current_rank']?>estimatedstaffcosts">Estimated total unit staff costs over course of project<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>estimatedstaffcosts" class="form-input currency" value="<?=$current1['estimatedstaffcosts']?>" />
                <label for="currentproject<?=$current1['current_rank']?>estimatednonstaffcosts">Estimated total unit non-staff costs over course of project<span> </span></label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>estimatednonstaffcosts" class="form-input currency" value="<?=$current1['estimatednonstaffcosts']?>" />
                <label for="currentproject<?=$current1['current_rank']?>nonstaffdesc">Brief description of non-staff costs<span></span></label>
				<textarea name="currentproject<?=$current1['current_rank']?>nonstaffdesc" class="form-input limit wordcount50" style="width:640px;"><?=$current1['nonstaffdesc']?></textarea><span class="counter-text"></span>
                
                 <label for="currentproject<?=$current1['current_rank']?>fundingreceivedthisperiod">Was funding received in this reporting period for this project?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject<?=$current1['current_rank']?>fundingreceivedthisperiod" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current1['fundingreceivedthisperiod']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current1['fundingreceivedthisperiod']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                <label for="currentproject<?=$current1['current_rank']?>iffundingnotreceivedinperiod">If no, please describe why funding has not been received</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>iffundingnotreceivedinperiod" class="form-input" value="<?=$current1['iffundingnotreceivedinperiod']?>"/>
                
                <label for="currentproject<?=$current1['current_rank']?>totalfundingreceived">Total funding received in this reporting period from original contract award*</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>totalfundingreceived" class="form-input currency" value="<?=$current1['totalfundingreceived']?>"/>
                
                  <label for="currentproject<?=$current1['current_rank']?>fundingreceivedthisperiod">Has project received a contract extension?<span> </span></label>
                <div class="select-wrapper form-select">
                <select name="currentproject<?=$current1['current_rank']?>contractextension" >
                    <option>Select</option>
                    <option value="yes" <?php echo ($current1['contractextension']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($current1['contractextension']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select>
            	</div>
                
                <label for="currentproject<?=$current1['current_rank']?>whyextensiongranted">If yes, why was extension granted?</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>whyextensiongranted" class="form-input" value="<?=$current1['whyextensiongranted']?>" />
                
                <label for="currentproject<?=$current1['current_rank']?>totalvalueofextension">Total value of contract extension</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>totalvalueofextension" class="form-input currency" value="<?=$current1['totalvalueofextension']?>"/>
                
                <label for="currentproject<?=$current1['current_rank']?>valueofextensiontounit">Expected value of contract extension to your unit</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>valueofextensiontounit" class="form-input currency" value="<?=$current1['valueofextensiontounit']?>"/>
                
                <label for="currentproject<?=$current1['current_rank']?>additionalfundingfromcontractextension">Additional funding received this reporting period from contract extension (if applicable)</label>
                <input type="text" name="currentproject<?=$current1['current_rank']?>additionalfundingfromcontractextension" class="form-input currency" value="<?=$current1['additionalfundingfromcontractextension']?>"/>
                <p style="clear:both;">&nbsp;</p>
                <p class="option-label">Does the project meet the NIHR criteria for offset?</p>
                <div class="option-group check">
				<input type="checkbox" name="currentproject<?=$current1['current_rank']?>NIHRoffset" id="current<?=$current1['current_rank']?>check2" <?php echo ($current1['NIHRoffset']=='yes' ? 'checked' : '' )?>/>
				<label for="current<?=$current1['current_rank']?>check2" <?php echo ($current1['NIHRoffset']=='yes' ? 'class="checked"' : '' )?>></label>	
                </div> 
                </div>
            </td>
		</tr><!-- Table Row -->
        <?php
		$projectcurrentrows=$current1['current_rank'];
		}*/
		?>
		<tr id="currentbuttonrow"><td colspan="7"><b>* excluding any income arising from contract extensions</b>. Please note income received as a result of contract extension is usually not eligible for offset and will be judged on a case by case basis. </td></tr>
        <tr>
        	<td colspan="7"><span style="display:block;text-align:right"><input type="button" class="form-btn" id="removecurrentbutton" value="remove last project" <?php 
				
				if ($projectcurrentrows < 2)
				{echo 'style="display:none;"';
				}
				?>>&nbsp;&nbsp;<input type="button" class="form-btn" id="addcurrentbutton" value="add another project"><input class="form-btn" type="submit" name="save" value="Save" /> </span></td>
        </tr>
<!-- Darker Table Row -->
	</tbody>
	<!-- Table Body -->
</table>
<p style="clear:both">&nbsp;</p>
 <p>&nbsp;</p>
    <h3>3.5 Total income from NIHR-funded projects</strong></h3>
    <p>&nbsp;</p>
    <table cellspacing='0' class="ctutable"> 
		<tr>
        	<td>
			
			<?php
			
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
			
			
			
			
			
			?>
			
			
			
			
			
			
			
			
			
			
  				<label for="totalunitincomefromnihrfundedprojects">Total unit income from NIHR funded projects this reporting period</label>
                <input type="text" name="totalunitincomefromnihrfundedprojects" value="<?=$funding?>" class="form-input currency" readonly/>
                <p style="clear:both;"><br/>The following figure will be used to calculate the amount that may be deducted from future Support Funding payments (auto-calculated based on figures in 3.4)<br /><br /></p>
                <label for="totalunitincomefromnihrfundedprojectsmeetoffset">Total unit income from NIHR projects which <strong>meet the criteria for offset</strong></label>
                <input type="text" name="totalunitincomefromnihrfundedprojectsmeetoffset" value="<?=$nihrfunding?>" class="form-input currency" readonly/>
                 <p style="clear:both;"><br/><strong>Disclaimer: Please note that the NIHR will check the figures provided for the offset calculation and ensure that all eligible projects and income are correctly identified.  NETSCC will confirm any future payment amounts in due course. </strong><br /><br /></p>
			</td>
		</tr>
</table>
<p style="clear:both">&nbsp;</p>
 <p>&nbsp;</p>
    <h3>3.6 Discontinued Projects</strong></h3>
    <p>&nbsp;</p>
	<p>Please list any NIHR projects which were discontinued or closed down in this reporting period. This excludes projects completed as planned.</p>
    <p>&nbsp;</p>
     <table cellspacing='0' class="four ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	
	
	<!--HERE!!!!-->
	
	
	<thead>
		<tr>
			<th></th>
			<!--<th>Project title</th>
			<th>NIHR Programme</th>
            <th>NIHR Project Reference</th>
            <th>Reason for Closure</th>-->
		</tr>
	</thead>
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>
		<tr>
        <?php
		
		$closedsql = "SELECT * FROM ctu_report_section3bclosed_inprog WHERE report_id = '".$_SESSION['report_id']."' ORDER BY closed_rank";
		$closedqry = mysql_query($closedsql);
		while ($closed = mysql_fetch_array($closedqry))
		{
			echo '<tr><td  colspan="4" style="text-align:left;height:10px;"><a class="lnk" href="section3bedit.php?closedselect='. $closed['closed_rank'] .'&report_id='. $_SESSION['report_id'] .'">'. $closed['closed_rank'] .' - '. $closed['reference'] .' - '. $closed['title'] .'</a><a class="del" href="delete3b.php?del_closed=del_closed&closed_rank='. $closed['closed_rank'] .'&report_id='. $_SESSION['report_id'] .'" style="float:right;">Delete</a></td></tr>';
		}
		
		
		
		
		/*
		//Get the first one of these:
		$closed1sql = "SELECT * FROM ctu_report_section3bclosed WHERE report_id='".$_SESSION['report_id']."' AND closed_rank=1";
		$closed1qry = mysql_query($closed1sql);
		$closed1 = mysql_fetch_array($closed1qry); 
		?>
			<td><input type="text" name="closed1title" class="form-input-table" value="<?=$closed1['title']?>"/></td>
			<td><div class="select-wrapper">
			<select name="closed1programme">
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
			<td><input type="text" name="closed1reference" class="form-input-table" value="<?=$closed1['reference']?>" /></td>
            <td><textarea name="closed1reason" class="form-input-table wordcount50"><?=$closed1['reason']?></textarea><span class="counter-text"></span></td>
		</tr><!-- Table Row -->
		<tr class="even">
        <?php
		//Get the first second of these:
		$closed2sql = "SELECT * FROM ctu_report_section3bclosed WHERE report_id='".$_SESSION['report_id']."' AND closed_rank=2";
		$closed2qry = mysql_query($closed2sql);
		$closed2 = mysql_fetch_array($closed2qry); 
		?>
			<td><input type="text" name="closed2title" class="form-input-table" value="<?=$closed2['title']?>"/></td>
			<td><div class="select-wrapper">
			<select name="closed2programme">
            	<option>Select</option>
				<option value="HTA" <?php echo ($closed2['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($closed2['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($closed2['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($closed2['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($closed2['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($closed2['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($closed2['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
			<td><input type="text" name="closed2reference" class="form-input-table" value="<?=$closed2['reference']?>"/></td>
            <td><textarea name="closed2reason" class="form-input-table wordcount50"><?=$closed2['reason']?></textarea><span class="counter-text"></td>
		</tr><!-- Darker Table Row -->
        <?php
		//Get anything thats left...:
		$closedsql = "SELECT * FROM ctu_report_section3bclosed WHERE report_id='".$_SESSION['report_id']."' AND closed_rank>2 order by closed_rank";
		$closedqry = mysql_query($closedsql);
		while ($closed = mysql_fetch_array($closedqry))
		{
			?>
            <tr <?php echo ($closed['closed_rank']%2==0 ? 'class="even"' : '')?>>
            <td><input type="text" name="closed<?=$closed['closed_rank']?>title" class="form-input-table" value="<?=$closed['title']?>"/></td>
			<td><div class="select-wrapper">
			<select name="closed<?=$closed['closed_rank']?>programme">
            	<option>Select</option>
				<option value="HTA" <?php echo ($closed['programme']=='HTA' ? "selected=\"selected\"" : "")?>>HTA</option>
                    <option value="EME" <?php echo ($closed['programme']=='EME' ? "selected=\"selected\"" : "")?>>EME</option>
                    <option value="HSDR" <?php echo ($closed['programme']=='HSDR' ? "selected=\"selected\"" : "")?>>HS&amp;DR</option>
                    <option value="PHR" <?php echo ($closed['programme']=='PHR' ? "selected=\"selected\"" : "")?>>PHR</option>
                    <option value="RfPB" <?php echo ($closed['programme']=='RfPB' ? "selected=\"selected\"" : "")?>>RfPB</option>
                    <option value="PGfAR" <?php echo ($closed['programme']=='PGfAR' ? "selected=\"selected\"" : "")?>>PGfAR</option>
                    <option value="i4i" <?php echo ($closed['programme']=='i4i' ? "selected=\"selected\"" : "")?>>i4i</option>
			</select>
		</div></td>
			<td><input type="text" name="closed<?=$closed['closed_rank']?>reference" class="form-input-table" value="<?=$closed['reference']?>"/></td>
            <td><textarea name="closed<?=$closed['closed_rank']?>reason" class="form-input-table wordcount50"><?=$closed['reason']?></textarea><span class="counter-text"></td>
            </tr>
            <?php
			$projectclosedrows=$closed['closed_rank'];
		}*/
		?>
        <tr id="closedbuttonrow">
        	<td colspan="7"><span style="display:block;text-align:right"><input type="button" class="form-btn" id="removeclosedbutton" value="remove last project" style="display:none;">&nbsp;&nbsp;<input type="button" class="form-btn" id="addclosedbutton" value="add another closed project"><input class="form-btn" type="submit" name="save" value="Save" /> </span></td>
        </tr>
	</tbody>
	<!-- Table Body -->
</table>
<?php
//The rest of the report comes from the main table
$mainsql = "SELECT * FROM ctu_report_section3b_inprog WHERE report_id='".$_SESSION['report_id']."'";
$mainqry = mysql_query($mainsql);
$main = mysql_fetch_array($mainqry);
?>
<p style="clear:both">&nbsp;</p>
 <p>&nbsp;</p>
    <h3>3.7 Any further comments on activity related to NIHR applications or proposals and funded project activities. This includes any EOI, bolt-on or add-on studies.</h3>
    <p style="clear:both">&nbsp;</p>
		<textarea name="furthercommentsonnihrapplications" class="form-input wordcount300" style="width:640px"><?=$main['furthercommentsonnihrapplications']?></textarea><span class="counter-text" style="margin-right:355px;margin-top:-15px;"></span>
    <p style="clear:both;">&nbsp;</p>
    <!--<h3>3.8 Please provide details on any Intellectual Property (IP) arising as a result of your CTU Support Funding Award.</h3>
    <p style="clear:both">&nbsp;</p>
		<textarea name="IPdetails" class="form-input wordcount250" style="width:640px"><?//=$main['IPdetails']?></textarea><span class="counter-text" style="margin-right:355px;margin-top:-15px;"></span>-->
    <p style="clear:both;">&nbsp;</p>
    <h3>3.8 NIHR CTU Support Funding for next 12 months.</h3>	
    <p style="clear:both">&nbsp;</p>
  <table cellpadding="0" class="ctutable">
  <tr><td>   <label for="wouldyoulikechangetofunding">Would you like to be considered for a change to your funding? <span> (required)</span></label>
                <div class="select-wrapper form-select">
                <select id="wouldyoulikechangetofunding" name="wouldyoulikechangetofunding" required>
                    <option value="null">Select</option>
                    <option value="yes" <?php echo ($main['wouldyoulikechangetofunding']=='yes' ? "selected=\"selected\"" : "")?>>Yes</option>
                    <option value="no" <?php echo ($main['wouldyoulikechangetofunding']=='no' ? "selected=\"selected\"" : "")?>>No</option>
                </select><span id="invalid-wouldyoulikechangetofunding"></span>
            	</div></td></tr>
                <tr><td>
                
        <label for="fundingchangesupport">If yes, please provide a brief supporting statement;</label>
		<textarea name="fundingchangesupport" class="form-input wordcount250" style="width:640px"><?=$main['fundingchangesupport']?></textarea><span class="counter-text" style="margin-top:4px;"></span><div style="display:block;"></div></td></tr></table>
    <p style="clear:both;">&nbsp;</p> 
        
        <input name="req_type" type="hidden" value="activity2"></input>
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
		echo '<input class="form-btn" id="sub" type="submit"  name="save" value="Save and Continue" />	<br/>';
		echo '<input class="form-btn" type="button" name="exit" id="exit" value="Exit" />';
	}


?>
	</form>
    <div id="dialog-confirm" title="Really Exit?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Warning!<br />If you haven’t clicked save,<br /> items will be lost!</p>
    </div>
	<div id="dialog-delete" title="Really Delete?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 7 7px 20px 0;"></span>Warning!<br />Are you sure you wish to delete this?</p>
    </div>
      <div id="dialog-remove" title="Really Remove Project?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 5px 0;"></span>You are about to remove the last project you added. Are you sure you want to do this?</p>
    </div>
   

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
    <script src="includes/js/jquery.formatCurrency-1.4.0.min.js"></script>
    <script src="includes/js/date.js"></script>

</body>
</html>