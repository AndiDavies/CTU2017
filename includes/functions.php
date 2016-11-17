<?php

function db_connect() {

 	global $db_loc, $db_user, $db_pass, $db_database;

    $f_dbh = new PDO("mysql:host=$db_loc;dbname=$db_database;charset=utf8", $db_user, $db_pass);
    $f_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 return($f_dbh);
}


function checkSoton() {

	if (substr($_SERVER['REMOTE_ADDR'],0,7) == "127.0.0") {
//	if (substr($_SERVER['REMOTE_ADDR'],0,7) == "152.78.") {
		return TRUE;

	} else {
		return FALSE;
	}
}

function checksubmit($report_id) {

	$sql = "SELECT status FROM ctu_activityreporting WHERE report_id = '".$report_id."'";
	$qry = mysqli_query($sql);
	$status = mysqli_fetch_array($qry);

	if ($status['status']=='submitted') {
		return TRUE;

	} else {
		return FALSE;
	}
}
