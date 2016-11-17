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

	<form action="process4.php" method="post" class="general" id="section4">
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
    <h2>Section 4: Further Comments / Other Key Information</h2>
    <p>&nbsp;</p>
      <table cellspacing='0' class="ctutable"> 
    <!-- cellspacing='0' is important, must stay -->
	<!-- Table Header -->
	
	<!-- Table Header -->
	<!-- Table Body -->
	<tbody>
		<tr >
        <?php
		//get data
		$sql = "SELECT * FROM ctu_report_section4 WHERE report_id = '".$_SESSION['report_id']."'";
				$qry = mysql_query($sql);
				$data = mysql_fetch_array($qry);

	   	?>
			<td>
            <label for="anyfurthercomments">Please provide any other information you feel is relevant e.g. key staff publication activity</label>
            <textarea name="anyfurthercomments" class="form-input wordcount250" style="width:640px;"><?=$data['anyfurthercomments']?></textarea><span style="margin-top:5px;margin-right:65px;"class="counter-text"></span></td>
	
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
		echo '<input class="form-btn" id="sub"  name="save" type="submit" value="Save and Continue" />	<br/>';
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
					$(this).find('textarea.form-input').bind('invalid', function(){
						$(this).addClass('invalid');
					});
				});
			}

		});
		
	</script>
	<script type="text/javascript">
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
		var result = value.match(/^\xA3?\d{1,3}?([,]\d{3}|\d)*?([.]\d{1,2})?$/);
		return result;
	}, 'Please specify an amount in GBP');
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
		$("#section4").validate({
			rules: {
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
		$('.wordcount250').each(function() {
			$(this).rules('add', {
			wordCount: ['250']
			});
		});
		 $("textarea[name=anyfurthercomments]").textareaCounter({ limit: 250 });
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
			$('#addoutlinebutton').hide();
			$('#addfullbutton').hide();
			$('.form-btn[name="save"]').hide();
			$('input[type="text"], textarea').attr('readonly', true);
			$('select').attr("disabled", true);
			$('.del').hide();
			
		}	
		$('input[name=back]').click(function() {
			 document.location.href="reporthome.php";	
		});
		
	});
	</script>
</body>
</html>