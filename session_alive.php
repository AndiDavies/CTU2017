<?php
session_start();
include("includes/environment.php");
include("includes/functions.php");
db_connect();
if (isset($_SESSION['report_id']))
{
$_SESSION['report_id']=$_SESSION['report_id'];
$sql="DELETE FROM ctu_util WHERE report_id = '".$_SESSION['report_id']."'";
$qry=mysql_query($sql);
$sql="INSERT INTO ctu_util ( lastalive , report_id ) VALUES ( now() , '".$_SESSION['report_id']."')";
$qry=mysql_query($sql);
echo "alive";
}
?>