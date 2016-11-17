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
<meta name="robots" content="noindex, nofollow" />

	<meta charset="UTF-8">
	<title>CTU Activity Report Form Administration</title>
	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="reset.css" />
	<link rel="stylesheet" href="general-light.css" />
	
	<!--[if lt IE 9]>
	    	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
</head>
<body>

<form action="adminprocess1.php" method="post" class="general">
<div class="banner"><img src="images/nihrlarge.png" alt="NIHR Logo"/>
</div>
<h1>NIHR Clinical Trials Unit (CTU) Support Funding<br>Activity Report Administration</h1>
<p>&nbsp;</p>
<p class='emphasis large'><b>Welcome,</b></p>
<p>Here are the current CTU Objectives.</p>
<?php
//Get details
$sql = "SELECT 		ctu_objectives.objective_rank,
					ctu_objectives.objective,
					ctu_objectives.targetdate,
					ctu_details.ukcrc,
					ctu_details.ctu_id,
					ctu_details.name
		FROM
					ctu_objectives
		INNER JOIN 
					ctu_details
		ON
					ctu_objectives.ctu_id = ctu_details.ctu_id
		ORDER BY 
					ctu_details.ctu_id, ctu_objectives.objective_rank";
$qry = mysql_query($sql);

?>
<table class="ctutable" cellpadding="0">
<thead>
	<tr>
    	<th>CTU ID</th>
        <th>UKCRC</th>
        <th>Name</th>
        <th>Rank</th>
        <th>Objective</th>
        <th>Target Date</th>
    </tr>
</thead>
<tbody>
	<?php
    while ($result=mysql_fetch_array($qry))
{
	?>
    <tr>
    	<td><?=$result['ctu_id']?></td>
       	<td><?=$result['ukcrc']?></td>
		<td><?=$result['name']?></td>
		<td><?=$result['objective_rank']?></td>
		<td><?=$result['objective']?></td>
        <td><?=date("d/m/Y",strtotime($result['targetdate']))?></td>
    </tr>
<?php
}
	?>
</tbody>
</table>			
					 
	 
?>
</form>
     
<?php

$sql2 = "SELECT ctu_details.name , ctu_activityreporting.guid FROM ctu_activityreporting INNER JOIN ctu_details ON ctu_details.ctu_id = ctu_activityreporting.ctu_id";
$qry2 = mysql_query($sql2);
while ($result = mysql_fetch_array($qry2))
{

echo $result['name']." - ".$url."reporthome.php?id=".$result['guid']."<br>";	
}
?>


<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
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
	});
	
	</script>
	
</body>
</html>