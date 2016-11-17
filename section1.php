<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
//Firstly, if session report_id is not set throw user to report home page.
if (isset($_SESSION['ctu'])==FALSE || checksubmit($_SESSION['report_id']))
{

	//var_dump(isset($_SESSION['ctu']));
//		echo !isset($_SESSION['ctu'])."<br/>".$_SESSION['report_id'];
	//	echo checksubmit($_SESSION['report_id']);
//	echo "problem";
	//header("Location: http://www.netscc.ac.uk/ctu_dev/forms/light/reporthome.php?reason=1");
}
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CTU Activity Report Form</title>
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="reset.css" />
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
$formid=$_SESSION['report_id'];
$reportingperiod = date('jS F Y',strtotime($report['startdate']))." - ".date('jS F Y',strtotime($report['enddate']));
$reportdueby = date('jS F Y',strtotime($report['completiondeadline']));
//get CTU details from DB
	$ctusql = "SELECT * FROM ctu_details where ctu_id = '".$_SESSION['ctu']."'";
	$ctuqry = mysql_query($ctusql);
	
	$ctu = mysql_fetch_array($ctuqry);
	$currentfundingaward="£".number_format($ctu['currentaward']);
	
	//Decide whether there is a section 1 with data in the DB.
	$sqlcheck = "SELECT * FROM ctu_report_section1 WHERE report_id = '".$_SESSION['report_id']."'";
	$qrycheck = mysql_query($sqlcheck);
	
	if (mysql_num_rows($qrycheck)>0)
	{
		$currentvalues = mysql_fetch_array($qrycheck);
		?>
	
	<form action="process1.php" method="post" class="general" id="section1">
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
    <div style="display:block;border:1px #000 solid;padding:10px 10px 10px 10px;">
    <p>The purpose of this Activity Report is to provide an overview of your unit's activity in the last 12 months.<br /><strong><u>Please refer to guidance notes when completing this form.</u></strong><br>Your completed form will be the basis for the assessment of your unit's performance and future funding award, and will be used to calculate any offset to be applied to future payments.<br/><br/>
<em>Please note failure to submit your activity report by the stated deadline may mean that Support Funding payment is delayed.</em></p>
</div>
	<p>&nbsp;</p>
    <p><strong>Unique ID:</strong> <i><?=$ctu['ukcrc']?></p>
	<p><strong>Reporting Period:</strong> <i><?=$reportingperiod?></i></p>
	<p><strong>Please submit your completed form by:</strong> <i><?=$reportdueby?></i></p> 
	<p>&nbsp;</p>
    <h2>Section 1: CTU Contact Details</h2>(To be populated by NETSCC)
	<h3>1.1 CTU Director Contact Details</h3>
    <p>&nbsp;</p>
    <label for="director1title">Title <span>(required)</span></label>
	<input type="text" name="director1title" class="form-input" value="<?=$currentvalues['director1title']?>" required />
	<label for="director1forename">Forename <span>(required)</span></label>
	<input type="text" name="director1forename" class="form-input" value="<?=$currentvalues['director1forename']?>" required />
    <label for="director1surname">Surname <span>(required)</span></label>
	<input type="text" name="director1surname" class="form-input" value="<?=$currentvalues['director1surname']?>" required />
	<label for="director1jobtitle">Job Title <span>(required)</span></label>
	<input type="text" name="director1jobtitle" class="form-input" value="<?=$currentvalues['director1jobtitle']?>" required />
    <label for="director1email">Email Address <span>(required)</span></label>
	<input type="text" name="director1email" class="form-input" value="<?=$currentvalues['director1email']?>" required />
 	<p style="clear:both"></p>
	<h3>1.2 CTU Director 2 Contact Details (if applicable)</h3>
    <p>&nbsp;</p>
    <label for="director2title">Title <span>(optional)</span></label>
	<input type="text" name="director2title" class="form-input" value="<?=$currentvalues['director2title']?>" />
	<label for="director2forename">Forename <span>(optional)</span></label>
	<input type="text" name="director2forename" class="form-input" value="<?=$currentvalues['director2forename']?>" />
    <label for="director2surname">Surname <span>(optional)</span></label>
	<input type="text" name="director2surname" class="form-input" value="<?=$currentvalues['director2surname']?>" />
	<label for="director2jobtitle">Job Title <span>(optional)</span></label>
	<input type="text" name="director2jobtitle" class="form-input" value="<?=$currentvalues['director2jobtitle']?>" />
    <label for="director2email">Email Address <span>(optional)</span></label>
	<input type="text" name="director2email" class="form-input" value="<?=$currentvalues['director2email']?>" />
	<p style="clear:both"></p>
	<h3>1.3 CTU Details</h3>
    <p>&nbsp;</p>
    <label for="ctuname">CTU Name <span>(required)</span></label>
	<input type="text" name="ctuname" class="form-input" value="<?=$currentvalues['ctuname']?>" required />
	<label for="organisation">Host Organisation <span>(required)</span></label>
	<input type="text" name="organisation" class="form-input" value="<?=$currentvalues['organisation']?>" required />
    <label for="financecode">Finance Code <span>(required - see guidance)</span></label>
	<input type="text" name="financecode" class="form-input" value="<?=$currentvalues['financecode']?>" required />
	<label for="address">Address <span>(required)</span></label>
    <textarea class="form-input form-input-address" name="address" required><?=$currentvalues['address'];?></textarea>
    <label for="postcode">Postcode<span> (required)</span></label>
	<input type="text" name="postcode" class="form-input" value="<?=$currentvalues['postcode']?>" required />
    <label for="telephone">Telephone<span> (required)</span></label>
	<input type="text" name="telephone" class="form-input" value="<?=$currentvalues['telephone']?>" required />
    <p style="clear:both"></p>
	<p><strong>Current funding award:</strong> <?=$currentfundingaward?></p>
    <p>&nbsp;</p>
    <p style="clear:both"></p>
	<h3>1.4 CTU Primary Contact details (if different to above)</h3>
    <p>&nbsp;</p>
    <label for="ctupriconname">Name <span>(optional)</span></label>
	<input type="text" name="ctupriconname" class="form-input" value="<?=$currentvalues['ctupriconname']?>" />
	<label for="ctupriconjobtitle">Job title <span>(optional)</span></label>
	<input type="text" name="ctupriconjobtitle" class="form-input" value="<?=$currentvalues['ctupriconjobtitle']?>" />
    <label for="ctupriconemail">Email Address <span>(optional)</span></label>
	<input type="text" name="ctupriconemail" class="form-input" value="<?=$currentvalues['ctupriconemail']?>" />
   <p style="clear:both"></p>
	<h3>1.5 CTU developments or changes</h3>

    <p>Please outline any key staffing or organisational developments or changes over the last 12 months. Please supply information on the potential effects of these changes and your proposals for handling any issues arising.</p>
    <p>&nbsp;</p>
<label for="ctudevelopments">Limit - 300 words<!--CTU Developments --> <span>(optional)</span></label>
		<textarea name="ctudevelopments" class="form-input limit wordcount300" style="margin-bottom:0px;"><?=$currentvalues['ctudevelopments']?></textarea><span class="counter-text"></span>
	<p style="clear:both"></p>
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
	<?php
	}
	else
	{
	?>
	<form action="process1.php" method="post" class="general" id="section1">
    <div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
    </div>
	<h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report</h1>
    
    <p>&nbsp;</p>
    <div style="display:block;border:1px #000 solid;padding:10px 10px 10px 10px;">
    <p>The purpose of this Activity Report is to provide an overview of your unit's activity in the last 12 months.<br /><strong><u>Please refer to guidance notes when completing this form.</u></strong><br>Your completed form will be the basis for the assessment of your unit's performance and future funding award, and will be used to calculate any offset to be applied to future payments.<br/><br/>
<em>Please note failure to submit your activity report by the stated deadline may mean that Support Funding payment is delayed.</em></p>
</div>
	<p>&nbsp;</p>
    <p><strong>Unique ID:</strong> <i><?=$ctu['ukcrc']?></p>
	<p><strong>Reporting Period:</strong> <i><?=$reportingperiod?></i></p>
	<p><strong>Please submit your completed form by:</strong> <i><?=$reportdueby?></i></p> 
	<p>&nbsp;</p>
    <h2>Section 1: Contact and CTU Details</h2>
	<h3>1.1 CTU Director Contact Details</h3>
    <p>&nbsp;</p>
    <?php
	//Get CTU Director Details from DB
	$directorsql = "SELECT * FROM ctu_contacts where type = 'director' and ctu_id = '".$_SESSION['ctu']."'";
	$directorqry = mysql_query($directorsql);
	$director = mysql_fetch_array($directorqry);
	?>
    <label for="director1title">Title <span>(required)</span></label>
	<input type="text" name="director1title" class="form-input" value="<?=$director['title']?>" required />
	<label for="director1forename">Forename <span>(required)</span></label>
	<input type="text" name="director1forename" class="form-input" value="<?=$director['forename']?>" required />
    <label for="director1surname">Surname <span>(required)</span></label>
	<input type="text" name="director1surname" class="form-input" value="<?=$director['surname']?>" required />
	<label for="director1jobtitle">Job Title <span>(required)</span></label>
	<input type="text" name="director1jobtitle" class="form-input" value="<?=$director['jobtitle']?>" required />
    <label for="director1email">Email Address <span>(required)</span></label>
	<input type="text" name="director1email" class="form-input" value="<?=$director['email']?>" required />
 	<p style="clear:both"></p>
	<h3>1.2 CTU Director 2 Contact Details (if applicable)</h3>
    <p>&nbsp;</p>
    <?php
    //Get CTU Secondary details from DB
	$secondarysql = "SELECT * FROM ctu_contacts where type = 'secondary' and ctu_id = '".$_SESSION['ctu']."'";
	$secondaryqry = mysql_query($secondarysql);
	$secondary = mysql_fetch_array($secondaryqry);
    ?>
    <label for="director2title">Title <span>(optional)</span></label>
	<input type="text" name="director2title" class="form-input" value="<?=$secondary['title']?>" />
	<label for="director2forename">Forename <span>(optional)</span></label>
	<input type="text" name="director2forename" class="form-input" value="<?=$secondary['forename']?>" />
    <label for="director2surname">Surname <span>(optional)</span></label>
	<input type="text" name="director2surname" class="form-input" value="<?=$secondary['surname']?>" />
	<label for="director2jobtitle">Job Title <span>(optional)</span></label>
	<input type="text" name="director2jobtitle" class="form-input" value="<?=$secondary['jobtitle']?>" />
    <label for="director2email">Email Address <span>(optional)</span></label>
	<input type="text" name="director2email" class="form-input" value="<?=$secondary['email']?>" />
	<p style="clear:both"></p>
	<h3>1.3 CTU Details</h3>
    <p>&nbsp;</p>
    <?php
	//get CTU details from DB
	$ctusql = "SELECT * FROM ctu_details where ctu_id = '".$_SESSION['ctu']."'";
	$ctuqry = mysql_query($ctusql);
	$ctu = mysql_fetch_array($ctuqry); 
	?>
    <label for="ctuname">CTU Name <span>(required)</span></label>
	<input type="text" name="ctuname" class="form-input" value="<?=$ctu['name']?>" required />
	<label for="organisation">Host Organisation <span>(required)</span></label>
	<input type="text" name="organisation" class="form-input" value="<?=$ctu['host']?>" required />
    <label for="financecode">Finance Code <span>(required - see guidance)</span></label>
	<input type="text" name="financecode" class="form-input" value="<?=$ctu['financecode']?>" required />
	<label for="address">Address <span>(required)</span></label>
    <textarea class="form-input form-input-address" name="address" required><?php 
	echo $ctu['address1']."\n".$ctu['address2']."\n".$ctu['address3']."\n".$ctu['address4']."\n".$ctu['address5']."\n".$ctu['address6'];
	?>
    </textarea>
    <label for="postcode">Postcode<span> (required)</span></label>
	<input type="text" name="postcode" class="form-input" value="<?=$ctu['postcode']?>" required />
    <label for="telephone">Telephone<span> (required)</span></label>
	<input type="text" name="telephone" class="form-input" value="<?=$ctu['tel']?>" required />
    <p style="clear:both"></p>
	<p><strong>Current funding award:</strong> <?=$currentfundingaward?></p>
    <p>&nbsp;</p>
    <p style="clear:both"></p>
	<h3>1.4 CTU Primary Contact details (if different to above)</h3>
    <p>&nbsp;</p>
    <?php
	//get Primary contact details from DB
	$contactsql = "SELECT * FROM ctu_contacts where type = 'contact' and ctu_id ='".$_SESSION['ctu']."'";
	$contactqry = mysql_query($contactsql);
	$contact = mysql_fetch_array($contactqry);
	?>
    <label for="ctupriconname">Name <span>(optional)</span></label>
	<input type="text" name="ctupriconname" class="form-input" value="<?=$contact['forename']?>&nbsp;<?=$contact['surname']?>" />
	<label for="ctupriconjobtitle">Job title <span>(optional)</span></label>
	<input type="text" name="ctupriconjobtitle" class="form-input" value="<?=$contact['jobtitle']?>" />
    <label for="ctupriconemail">Email Address <span>(optional)</span></label>
	<input type="text" name="ctupriconemail" class="form-input" value="<?=$contact['email']?>" />
   <p style="clear:both"></p>
	<h3>1.5 CTU developments</h3>
    <p>Please outline any key staffing or organisational developments or changes over the last 12 months. Please supply information on the potential effects of these changes and your proposals for handling any issues arising.</p>
    <p>&nbsp;</p>
<label for="ctudevelopments">Limit - 300 words<!--CTU Developments --> <span>(optional)</span></label>
		<textarea name="ctudevelopments" class="form-input limit wordcount300" style="margin-bottom:0px;"></textarea><span class="counter-text"></span>
	<p style="clear:both">&nbsp;</p>
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
 <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Warning!<br />If you haven't clicked save,<br /> items will be lost!</p>
    </div>
	<?php
	}
	?>
	
	
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.js"></script>
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.js"></script>
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
				});
			}
		});
	</script>
    	<script type="text/javascript">
	jQuery.validator.addMethod( 
	"phone",
	function(phone_number , element) {
		return this.optional(element) || /^\d{11,}$/.test(phone_number.replace(/\s/g, ''));
	}, "Please enter phone number in dialing code + number format: i.e. 02380 595000"
	);
	jQuery.validator.addMethod("postcodeUK", function(value, element) {
		return this.optional(element) || /[A-Z]{1,2}[0-9R][0-9A-Z]? [0-9][ABD-HJLNP-UW-Z]{2}/i.test(value);
	}, "Please specify a valid Postcode");
	/*
	jQuery.validator.setDefaults({
		errorPlacement: function(error, element) {
			error.appendTo('#invalid-' + element.attr('name'));
			}
	});*/
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
			$('input, textarea').attr('readonly', true);
			$('select').attr("disabled", true);
			$('.del').hide();
		}
		
		
		
	$("#section1").validate({
		rules: {
			telephone: {
				phone: true
			},
			postcode: {
				postcodeUK: true
			},
			director1email: {
				email: true
			},
			director2email: {
				email: true
			},
			ctupriconemail: {
				email: true
			}
		}
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
	$("textarea.limit").textareaCounter();
	});
	
	
	
	</script>
	
</body>
</html>