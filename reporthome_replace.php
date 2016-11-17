<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
include("includes/environment.php");
include("includes/functions.php");
db_connect();
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>CTU Activity Report Form Home</title>
	
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

    //Check for GUID
	if (isset($_REQUEST['id']))
	{
	$id = addslashes($_REQUEST['id']);
	//echo "Your id is: <b>".$id."</b><br />";
	}
	
	if (isset($_SESSION['report_id']) && !isset($_REQUEST['id']))
	{
		$getguidsql = "SELECT guid from ctu_activityreporting WHERE report_id = '".$_SESSION['report_id']."'";
		$getguidqry = mysql_query($getguidsql);
		$getguid = mysql_fetch_array($getguidqry);
		$id = $getguid['guid'];
	}
	
	if ((isset($id) && strlen($id)==36 && preg_match('/^\{?[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}\}?$/i',$id)) or isset($_SESSION['report_id']))
	
    {

		//echo "guid appears to be valid.<br />";
		$getreportsql = "SELECT ctu_details.name,
			ctu_details.ctu_id, 
			ctu_reportingperiods.startdate, 
			ctu_reportingperiods.enddate, 
			ctu_reportingperiods.completiondeadline, 
			ctu_activityreporting.report_id, 
			ctu_activityreporting.section1valid, 
			ctu_activityreporting.section2valid, 
			ctu_activityreporting.section3valid, 
			ctu_activityreporting.section3bvalid, 
			ctu_activityreporting.section4valid, 
			ctu_activityreporting.section1lastupdate, 
			ctu_activityreporting.section2lastupdate, 
			ctu_activityreporting.section3lastupdate,
			ctu_activityreporting.section3blastupdate, 
			ctu_activityreporting.section4lastupdate,
			ctu_activityreporting.status,
			ctu_activityreporting.statusdate 
			FROM ctu_activityreporting 
				INNER JOIN ctu_details ON ctu_activityreporting.ctu_id = ctu_details.ctu_id 
				INNER JOIN ctu_reportingperiods ON ctu_activityreporting.period = ctu_reportingperiods.period 
			WHERE guid= '".$id."'";
		$getreportqry = mysql_query($getreportsql);
		$report = mysql_fetch_array($getreportqry);
		$_SESSION['ctu']=$report['ctu_id'];
		$_SESSION['report_id']=$report['report_id'];
		
		//valid guid and report pulled - lets log this page visit.
		$sqllog = "INSERT INTO ctu_visit_log ( report_id , ip ) VALUES ( '".$_SESSION['report_id']."' , '".$_SERVER['REMOTE_ADDR']."' )";
		$qrylog = mysql_query($sqllog);
		$reportstarted=1;
		if 	(	   empty($report['section1lastupdate']) 
				&& empty($report['section2lastupdate'])
				&& empty($report['section3lastupdate'])
				&& empty($report['section3blastupdate'])
				&& empty($report['section4lastupdate'])
			)
		{
			$reportstarted=0;
		}
		$formvalid=0;
		if ( 		$report['section1valid']=="yes"
				&&	$report['section2valid']=="yes"
				&&	$report['section3valid']=="yes"
				&&	$report['section3bvalid']=="yes"
				&&	$report['section4valid']=="yes"
			)
		{
			$formvalid=1;
		}
		
		?>	
        <form action="process1.php" method="post" class="general">
        <div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
        </div>
        <h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report</h1>
        <p>&nbsp;</p>
		<p class='emphasis large'><b>Welcome,</b> <?=$report['name']?></p>
		<p>&nbsp;</p>
        
        <?php
		//Switch on whether report has been submitted or not
		if ($report['status']!='submitted')
		{
			?>
        <p>The table below shows the current status of your Activity report.</p>
        <p>&nbsp;</p>
        <table cellpadding="0" class="ctutable ctutable-small">
        <thead>
        	<tr>
            	<th>Section</th>
                <th>Section Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        	<tr>
            	<td>1</td>
                <td>
					<?php 
					
					//echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );
					if ($report['section1valid']=="yes")
					{
						echo "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate']));
					}
					else if ($report['section1valid']=="not yet validated")
					{
						echo "<span class='notyet'>Not Yet Validated</span>";
						echo '<br /><br /><a class="form-btn" href="validate.php?section=1">';
						echo "Validate section&nbsp;1</a>";
					}
					else
					{
						echo "<span class='incorrect'>Incomplete</span>";
					}
					
					
					
					?>
				</td>
                <td><a class="form-btn" href="section1.php"><?php echo "Edit section&nbsp;1";?></a></td>
            </tr>
            <tr>
            	<td>2</td>
                <td>
					<?php 
					
					//echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );
					if ($report['section2valid']=="yes")
					{
						echo "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section2lastupdate']));
					}
					else if ($report['section2valid']=="not yet validated")
					{
						echo "<span class='notyet'>Not Yet Validated</span>";
						echo '<br /><br /><a class="form-btn" href="validate.php?section=2">';
						echo "Validate section&nbsp;2</a>";
					}
					else
					{
						echo "<span class='incorrect'>Incomplete</span>";
					}
					
					
					
					?>
				</td>
                <td><a class="form-btn" href="section2.php"><?php echo "Edit section&nbsp;2";?></a></td>
            </tr>
            <tr>
            	<td>3a</td>
                <td>
					<?php 
					
					//echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );
					if ($report['section3valid']=="yes")
					{
						echo "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section3lastupdate']));
					}
					else if ($report['section3valid']=="not yet validated")
					{
						echo "<span class='notyet'>Not Yet Validated</span>";
						echo '<br /><br /><a class="form-btn" href="validate.php?section=3a">';
						echo "Validate section&nbsp;3a</a>";
					}
					else
					{
						echo "<span class='incorrect'>Incomplete</span>";
					}
					
					
					
					?>
				</td>
                <td><a class="form-btn" href="section3.php"><?php echo "Edit section&nbsp;3a";?></a></td>
            </tr>
            <tr>
            	<td>3b</td>
               <td>
					<?php 
					
					//echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );
					if ($report['section3bvalid']=="yes")
					{
						echo "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section3blastupdate']));
					}
					else if ($report['section3bvalid']=="not yet validated")
					{
						echo "<span class='notyet'>Not Yet Validated</span>";
						echo '<br /><br /><a class="form-btn" href="validate.php?section=3b">';
						echo "Validate section&nbsp;3b</a>";
					}
					else
					{
						echo "<span class='incorrect'>Incomplete</span>";
					}
					
					
					
					?>
				</td>
                <td><a class="form-btn" href="section3b.php"><?php echo "Edit section&nbsp;3b";?></a></td>
            </tr>
            <tr>
            	<td>4<br />(Optional)</td>
                <td>
					<?php 
					
					//echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );
					if ($report['section4valid']=="yes")
					{
						echo "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section4lastupdate']));
					}
					else if ($report['section4valid']=="not yet validated")
					{
						echo "<span class='notyet'>Not Yet Validated</span>";
						echo '<br /><br /><a class="form-btn" href="validate.php?section=4">';
						echo "Validate section&nbsp;4</a>";
					}
					else
					{
						echo "<span class='incorrect'>Incomplete</span>";
					}
					
					
					
					?>
				</td>
                <td><a class="form-btn" href="section4.php"><?php echo "Edit section&nbsp;4";?></a></td>
            </tr>
			<tr>
            	<td></td>
               <!-- <td></td> -->
                <td></td>
                <td><a class="form-btn" href="pdf.php">View&nbsp;as&nbsp;PDF</a></td>
            </tr>
        </tbody>
        </table>
        <p>&nbsp;</p>
       
        <?php
	

		if ($reportstarted==0)
		{
			echo "<p>You do not appear to have started your Activity Report yet. You can start your report by clicking on 'Edit Section 1' in the table above, <br />or just click this button: <a class='form-btn' href='section1.php'>Begin report</a></p>";
		}
		elseif ($formvalid==1)
		{
			echo "<p>Your form is eligible for submission. When you are satisfied that your form contains all requested information please click the button below to submit your form.<br /><strong>Please note that you cannot edit your form further once it has been submitted.</strong></p><br /><p><a class=\"form-btn confirmLink\" href=\"submit.php\">Submit Form</a></p>";
		}
		else
		{
			echo "<p>You can edit any of the sections of your Activity Report by clicking on the 'Edit Section' buttons in the table above. Alternatively, you can start sections you have not yet completed by clicking on the 'Start Section' buttons.</p>";
		}
		?>
        
        <?php
		//end 'if submitted switch
		}
		else
		{
		?>
        <p>Your report was submitted on <b><?=date("l, jS F Y",strtotime($report['statusdate']))?></b> at <b><?=date("H:i:s",strtotime($report['statusdate']))?></b>.</p>
        <p>&nbsp;</p>
          <table cellpadding="0" class="ctutable ctutable-small">
        <thead>
        	<tr>
            	<th>Section</th>
                <th>Section Status</th>
            </tr>
        </thead>
        <tbody>
        	<tr>
            	<td>1</td>
                <td><?php echo ( $report['section1valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section1lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );?></td>
				<td><a class="form-btn" href="section1.php?view=view"><?php echo "View section&nbsp;1";?></a></td>
                
            </tr>
            <tr>
            	<td>2</td>
                <td><?php echo ( $report['section2valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section2lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );?></td>
                <td><a class="form-btn" href="section2.php?view=view"><?php echo "View section&nbsp;2";?></a></td>
            </tr>
            <tr>
            	<td>3a</td>
                <td><?php echo ( $report['section3valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section3lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );?></td>
                <td><a class="form-btn" href="section3.php?view=view"><?php echo "View section&nbsp;3a";?></a></td>
            </tr>
            <tr>
            	<td>3b</td>
                <td><?php echo ( $report['section3bvalid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section3blastupdate'])) : "<span class='incorrect'>Incomplete</span>" );?></td>
                <td><a class="form-btn" href="section3b.php?view=view"><?php echo "View section&nbsp;3b";?></a></td>
            </tr>
            <tr>
            	<td>4<br />(optional)</td>
                <td><?php echo ( $report['section4valid']=="yes" ? "<span class='correct'>Complete</span> - last updated:<br>".date("l jS F Y g:i:s A",strtotime($report['section4lastupdate'])) : "<span class='incorrect'>Incomplete</span>" );?></td>
                <td><a class="form-btn" href="section4.php?view=view"><?php echo "View section&nbsp;4";?></a></td>
            </tr>
			<tr>
            	<td></td>
                <td></td>
                <!--<td></td>-->
                <td><a class="form-btn" href="pdf.php">View&nbsp;as&nbsp;PDF</a></td>
            </tr>
        </tbody>
        </table>
         <p>&nbsp;</p>
        <p>If you have any queries about the Activity Report process, please contact the CTU team directly at <a href="mailto:nihrctu@soton.ac.uk">nihrctu@soton.ac.uk</a>.</p>
        <?php	
			
		}
		?>
         </form>
		 
		 
         
         <div id="dialog-confirm" title="Really Submit Form?">
    <span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 5px 0;"></span>Are you sure you want to submit your form? Changes cannot be made once the form is submitted.</p>
    </div>

         <?php
	//	if ($reportcomplete==0)
//		{
//			//NNNNNNNNNNNNNNNNNNNNNNNNNNNN
//		}
//		
    }
	else 
	{
		$reason = isset($_GET['reason']) ? $_GET['reason'] : null;
		if ($reason == '1')
		{
			?>
            
            <form action="reporthome.php" method="post" class="general">
            <div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
	        </div>
    	    <h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report</h1>
        	<p>&nbsp;</p><h3>Your activity report session timed out, or you tried to link directly to a report page.</h3>
            <p>Please use the link from your invitation email to access your report, or cut and paste the id number from the email and enter into the box below, then click 'submit'</p><p>&nbsp;</p>
           
            <input type="text" class="form-input" name="id" size="36"/>&nbsp;&nbsp;
            <input type="submit" class="form-btn" value="submit" /> 
            <p style="clear:both;">&nbsp;</p>
			<?php
			echo (isset($_GET['error']) && $_GET['error']=='1') ? "<span class='incorrect'>You entered an invalid id.</span>" : null;
			?>
            </form>
            <?php
		}
		else 
		{
			header("Location: http://www.netscc.ac.uk/ctu_dev/forms/light/reporthome.php?reason=1&error=1");
		}
	}


?>




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
        "Confirm" : function() {
          window.location.href = targetUrl;
        },
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