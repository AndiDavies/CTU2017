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
	<title>CTU Activity Report Form Administration</title>
	
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
<p class='emphasis large'><b>Welcome,</b> Administrator</p><br />
<p>Here are the current CTU reporting centres.</p><br />
<?php
//Get details
$sql = "SELECT 		ctu_activityreporting.report_id,
					ctu_activityreporting.guid,
					ctu_activityreporting.section1valid,
					ctu_activityreporting.section1lastupdate,
					ctu_activityreporting.section2valid,
					ctu_activityreporting.section2lastupdate,	 
					ctu_activityreporting.section3valid,
					ctu_activityreporting.section3lastupdate,
					ctu_activityreporting.section3bvalid,
					ctu_activityreporting.section3blastupdate,	 
					ctu_activityreporting.section4valid,
					ctu_activityreporting.section4lastupdate,
					ctu_activityreporting.status,
					ctu_activityreporting.statusdate,
					ctu_details.ukcrc,
					ctu_details.name,
					ctu_details.currentaward
		FROM
					ctu_activityreporting
		INNER JOIN 
					ctu_details
		ON
					ctu_activityreporting.ctu_id = ctu_details.ctu_id
		WHERE 
					ctu_activityreporting.period = '201213'";
$qry = mysql_query($sql);

?>
<table class="ctutable" cellpadding="0">
<thead>
	<tr>
    	<th>name</th>
        <th>guid</th>
        <th></th>
        <th>sec 1 status</th>
        <th>sec 2 status</th>
        <th>sec 3a status</th>
        <th>sec 3b status</th>
        <th>sec 4 status</th>
        <th>Submitted?</th>
		
    </tr>
</thead>
<tbody>
	<?php
    while ($result=mysql_fetch_array($qry))
{
	?>
    <tr>
    	<td><?=$result['name']?></td>
       	<td><a href="<?=$url?>reporthome.php?id=<?=$result['guid']?>"><?=$result['guid']?></a></td>
        <td><a class="form-btn confirmLink" href="adminprocess.php?action=clearsection&report_id=<?=$result['report_id']?>&section=all">clear&nbsp;entire&nbsp;form</a></td>
        
		<td><?php echo ($result['section1valid']=="yes" ? "<span style='color:green'>completed</span><br />".$result['section1lastupdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=clearsection&report_id=".$result['report_id']."&section=1\">clear&nbsp;1</a></span>" : "not complete")?></td>
        
        <td><?php echo ($result['section2valid']=="yes" ? "<span style='color:green'>completed</span><br />".$result['section2lastupdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=clearsection&report_id=".$result['report_id']."&section=2\">clear&nbsp;2</a></span>" : "not complete")?></td>
        
        <td><?php echo ($result['section3valid']=="yes" ? "<span style='color:green'>completed</span><br />".$result['section3lastupdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=clearsection&report_id=".$result['report_id']."&section=3\">clear&nbsp;3a</a></span>" : "not complete")?></td>
        
        <td><?php echo ($result['section3bvalid']=="yes" ? "<span style='color:green'>completed</span><br />".$result['section3blastupdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=clearsection&report_id=".$result['report_id']."&section=3b\">clear&nbsp;3b</a></span>" : "not complete")?></td>
        
        <td><?php echo ($result['section4valid']=="yes" ? "<span style='color:green'>completed</span><br />".$result['section4lastupdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=clearsection&report_id=".$result['report_id']."&section=4\">clear&nbsp;4</a></span>" : "not complete")?></td>
        
        <td><?php echo ($result['status']=="submitted" ? "<span style='color:green'>submitted</span><br />".$result['statusdate']."<br /><span style=\"padding:3px;margin:3px;line-height:35px;\"><a class=\"form-btn confirmLink\" href=\"adminprocess.php?action=unsubmit&report_id=".$result['report_id']."\">Unsubmit&nbsp;form</a></span>" : "in progress")?></td>
        
      
        
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