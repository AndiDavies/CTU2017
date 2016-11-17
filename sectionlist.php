<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors','1');
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
<meta name="robots" content="noindex, nofollow" />

	<meta charset="UTF-8">
	<title>CTU Activity Report Form</title>
	
	<link href='http://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'>
    <link type='text/css' href='../basic/css/basic.css' rel='stylesheet' media='screen' />
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
	<link rel="stylesheet" href="general-light.css" />
	
	<!--[if lt IE 9]>
	    	<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	    	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
</head>
<body>
<?php
include("includes/functions.php");
db_connect();
if (!isset($_SESSION['ctu']))
{
	$_SESSION['ctu']=1;
}
if (isset($_POST['ctu']))
{
	$_SESSION['ctu'] = $_POST['ctu'];
}

$currentctusql = "SELECT name FROM ctu_details WHERE ctu_id = '".$_SESSION['ctu']."'";
$currentctuqry = mysql_query($currentctusql);
$currentCTUresult = mysql_fetch_array($currentctuqry);
$currentCTU=$currentCTUresult['name'];
?>
	<h1>NIHR Clinical Trials Unit (CTU)<br>Support Funding Activity Report</h1>
	<form action="section2.php" method="post" class="general">
    <h2>Testing Section Index Page</h2>
    <p>&nbsp;</p>
    <p>Please use this page to select a CTU and navigate between sections of the form to test as that CTU.</p>
	<p>&nbsp;</p>
    <p><strong>Currently Selected CTU: <span class="emphasis"><?=$currentCTU?></span></strong>&nbsp;&nbsp;<input class="form-btn" type="button" value="Change CTU"/></p>
	<p>&nbsp;</p>
    <p><strong>Activity Report Section:</strong></p>
    <p><a href="section1.php">Section 1 - Contact and CTU Details</a></p>
    <p><a href="section2.php">Section 2 - Progress Summary</a></p>
    <p><a href="section3.php">Section 3 - CTU NIHR Activity</a></p>
    <p><a href="section4.php">Section 4 - Further Comments / Other Key Information</a></p>
    </ul>
    <p>&nbsp;</p>
	
	</form>
	<div id="dialog-message" style="display:none;" title="Choose new CTU">
    <p>Select a CTU from the following list.</p>
    <form action="sectionlist.php" method="post" id="ctuform">
    <select name="ctu" style="width:300px;border:1px #ccc solid;">
    <?php
	$ctusql = "SELECT name,ctu_id from ctu_details";
	$ctuqry = mysql_query($ctusql);
	while ($result=mysql_fetch_array($ctuqry))
	{
	echo "<option value=".$result['ctu_id'].">".$result['name']."</option>";
	}
	?>
    </select>
    </form>
    </div>
	
	
	
	<script>
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
		$('.datepick').datepicker({dateFormat: "dd/mm/yy"});
		$(this).find('input.form-input').change(function(e){
			if (e.target.validity.valid) {
				$(e.target).removeClass('invalid');
			}
		});
		$(this).find('textarea.form-input').change(function(e){
			if (e.target.validity.valid) {
				$(e.target).removeClass('invalid');
			}
		});
		$('.form-btn').click(function() {
    			$( "#dialog-message" ).dialog({
     				 modal: true,
					 width: 400,
     				 buttons: {
     				   Ok: function() {
        					  $('#ctuform').submit();
       					 }
     				 }
   				 });
		});
	});
	</script>
	
</body>
</html>