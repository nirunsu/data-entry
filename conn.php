<?php
$Host='localhost';
$UserHost='root';
$PassHost='123456';
$DB='cmosis';

$Connect=mysql_connect($Host,$UserHost,$PassHost);

if($Connect){
	$Select_DB=mysql_select_db($DB);
	
	if($Select_DB){
		mysql_query("SET NAMES UTF8");
		//echo "connection complate";
		
		/* $m_time = explode(" ",microtime());
		$m_time = $m_time[0] + $m_time[1];
		$loadstart = $m_time; */
		
	}else{
		echo "<script language=\"javascript\">";
		echo "alert('Non Select Database : $DB')";
		echo "</script>";
		}
	}else{
		echo "<script language=\"javascript\">";
		echo "alert('Connecting Error()');";
		echo "</script>";
		}

?>
