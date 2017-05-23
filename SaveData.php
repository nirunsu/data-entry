<?php
error_reporting( error_reporting() & ~E_NOTICE );
$objConnect = mysql_connect("localhost","root","123456") or die("Error Connect to Database");
$objDB = mysql_select_db("cmosis");	
    $Act=$_GET['Act'];
	switch($Act){
	case 'Add'	:   				
		$strSQL = "INSERT INTO tbl_dataentry";
		$strSQL .="(Model,PO,WA,
					PN,TotalPO,TotalWA,
					BatchNo,StartSN,EndSN,
					ShipDate,InvoiceNo,PN_Lens,
					QTY_Lens,Lot_Lens,PN_Die,
					QTY_Die,Lot_Die,PN_Flex,
					QTY_Flex,Lot_Flex,PN_Cable,
					QTY_Cable,Lot_Cable,Length_Cable,
					PN_ConT,QTY_ConT,Lot_ConT,
					PN_Cap,QTY_Cap,Lot_Cap,
					PN_Core,QTY_Core,Lot_Core,
					PN_PV6101,Lot_PV6101,Exp_PV6101,
					PN_EK_APM,Lot_EK_APM,Exp_EK_APM,
					PN_320NC,Lot_320NC,Exp_320NC,
					PN_Solder,Lot_Solder,Exp_Solder,
					PN_Flux,Lot_Flux,Exp_Flux)";
		$strSQL .="VALUES ";
		$strSQL .="('".$_POST["inputModel"]."','".$_POST["inputPO"]."','".$_POST["inputWA"]."',
					'".$_POST["inputPN"]."','".$_POST["inputTotalPO"]."','".$_POST["inputTotalWA"]."',
					'".$_POST["inputBatchNo"]."','".$_POST["inputStartSN"]."','".$_POST["inputEndSN"]."',
					'".$_POST["inputShipDate"]."','".$_POST["inputInvoiceNo"]."','".$_POST["inputPN_Lens"]."',
					'".$_POST["inputQTY_Lens"]."','".$_POST["inputLot_Lens"]."','".$_POST["inputPN_Die"]."',
					'".$_POST["inputQTY_Die"]."','".$_POST["inputLot_Die"]."','".$_POST["inputPN_Flex"]."',
					'".$_POST["inputQTY_Flex"]."','".$_POST["inputLot_Flex"]."','".$_POST["inputPN_Cable"]."',
					'".$_POST["inputQTY_Cable"]."','".$_POST["inputLot_Cable"]."','".$_POST["inputLength_Cable"]."',
					'".$_POST["inputPN_ConT"]."','".$_POST["inputQTY_ConT"]."','".$_POST["inputLot_ConT"]."',
					'".$_POST["inputPN_Cap"]."','".$_POST["inputQTY_Cap"]."','".$_POST["inputLot_Cap"]."',
					'".$_POST["inputPN_Core"]."','".$_POST["inputQTY_Core"]."','".$_POST["inputLot_Core"]."',
					'".$_POST["inputPN_PV6101"]."','".$_POST["inputLot_PV6101"]."','".$_POST["inputExp_PV6101"]."',
					'".$_POST["inputPN_EK_AMP"]."','".$_POST["inputLot_EK_APM"]."','".$_POST["inputExp_EK_APM"]."',
					'".$_POST["inputPN_320NC"]."','".$_POST["inputLot_320NC"]."','".$_POST["inputExp_320NC"]."',
					'".$_POST["inputPN_Solder"]."','".$_POST["inputLot_Solder"]."','".$_POST["inputExp_Solder"]."',
					'".$_POST["inputPN_Flux"]."','".$_POST["inputLot_Flux"]."','".$_POST["inputExp_Flux"]."')";				
		$objQuery = mysql_query($strSQL);							
		if($objQuery)
			{
				echo "<script language=\"javascript\">";
				//echo "alert('Add Data Complete ');";
				echo "window.location='adddata_cmosis.php';";
				echo "</script>";	
			}

		}
mysql_close($objConnect);				
?> 
