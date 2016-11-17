<?php
$environment = "prod";
if ($environment == "dev")
{
	$url = "http://152.78.238.150/ctu/";
	$db_loc = "localhost";
	$db_user = "root";
	$db_pass = "N3t5cc1T";
	$db_database = "sdonihr";
}
elseif ($environment == "prod")
{
	$url = "http://www.netscc.ac.uk/ctu/";
	$db_loc = "clkpdb-vip.thuk.clk.mgtcore.net";
	$db_user = "sdonihr";
	$db_pass = "Ahv5waeJ";
	$db_database = "sdonihr";
}
?>