<?php
//Check to see if form is submitted already
function checksubmit($report_id)
{
	$sql = "SELECT status FROM ctu activityreporting WHERE report_id = '".$_SESSION['report_id']."'";
	$qry = mysql_query($sql);
	$status = mysql_fetch_array($qry);
	if ($status['status']=='submitted') 
		{ 
			return("[Form Submitted]"); 
		}
	else
		{
			return("[Not Submitted]");
		}
}
?>