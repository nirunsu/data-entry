<?php
session_start();
error_reporting( error_reporting() & ~E_NOTICE );
include('conn.php');
/*include ('function.php');*/

?>
<?php
class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $high;
	var $limit;
	var $return;
	var $default_ipp;
	var $querystring;
	var $url_next;

	function Paginator()
	{
		$this->current_page = 1;
		$this->mid_range = 7;
		$this->items_per_page = $this->default_ipp;
		$this->url_next = $this->url_next;
	}
	function paginate()
	{

		if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
		$this->num_pages = ceil($this->items_total/$this->items_per_page);

		if($this->current_page < 1 Or !is_numeric($this->current_page)) $this->current_page = 1;
		if($this->current_page > $this->num_pages) $this->current_page = $this->num_pages;
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;


		if($this->num_pages > 10)
		{
			//$this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"".$this->url_next.$this->$prev_page."\">&laquo; Previous</a> ":"<span class=\"inactive\" href=\"#\">&laquo; Previous</span> "; //�?ลั�?�?�?ที�?ห�?�?า�?ร�?เสมอ
			$this->return = ($this->current_page != 1 And $this->items_total >= 10) ? "<a class=\"paginate\" href=\"".$this->url_next.$prev_page."\">&laquo; Previous</a> ":"<span class=\"inactive\" href=\"#\">&laquo; Previous</span> ";
			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= " ... ";
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					$this->return .= ($i == $this->current_page And $_GET['Page'] != 'All') ? "<a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"".$this->url_next.$i."\">$i</a> ";
				}
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= " ... ";
			}
			$this->return .= (($this->current_page != $this->num_pages And $this->items_total >= 10) And ($_GET['Page'] != 'All')) ? "<a class=\"paginate\" href=\"".$this->url_next.$next_page."\">Next &raquo;</a>\n":"<span class=\"inactive\" href=\"#\">&raquo; Next</span>\n";
		}
		else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
				$this->return .= ($i == $this->current_page) ? "<a class=\"current\" href=\"#\">$i</a> ":"<a class=\"paginate\" href=\"".$this->url_next.$i."\">$i</a> ";
			}
		}
		$this->low = ($this->current_page-1) * $this->items_per_page;
		$this->high = ($_GET['ipp'] == 'All') ? $this->items_total:($this->current_page * $this->items_per_page)-1;
		$this->limit = ($_GET['ipp'] == 'All') ? "":" LIMIT $this->low,$this->items_per_page";
	}

	function display_pages()
	{
		return $this->return;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  <meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>AMS Naneye Information Data..</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta http-equiv="refresh" content="130">
	
	<!-- FancyBox	-->
 	<script type="text/javascript" src="fancybox/scripts/jquery-1.4.3.min.js"></script> 
	<script type="text/javascript" src="fancybox/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script type="text/javascript" src="fancybox/fancybox/jquery.fancybox-1.3.4.pack.js"></script> 
	<link rel="stylesheet" type="text/css" href="fancybox/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<link rel="stylesheet" href="fancybox/style/style.css" /> 
	
	<script type="text/javascript">
		$(document).ready(function() {		
			 $("a.zoom").fancybox({
				'overlayOpacity'	:	0.5,
				'overlayColor'		:	'#FF9933',
			 });

			$('a[id^="ShowYield"]').fancybox({
				'width'				: '60%',
				'height'			: '90%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
				parent.location.reload(true);
			}
			});

			$('a[id^="add"]').fancybox({
				'width'				: '80%',
				'height'			: '80%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
				parent.location.reload(true);
				}
			});
			
			$('a[id^="AddFile"]').fancybox({
				'width'				: '50%',
				'height'			: '60%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
				parent.location.reload(true);
				}
			});
				$('a[id^="Login"]').fancybox({
				'width'				: '50%',
				'height'			: '100%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
				parent.location.reload(true);
				}
			});
				$('a[id^="zoom"]').fancybox({
				'width'				: '35%',
				'height'			: '100%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
				//parent.location.reload(true);
				} 
			});
				$('a[id^="picmember"]').fancybox({
				'width'				: '28%',
				'height'			: '100%',
				'autoScale'     	: false,
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'type'				: 'iframe',
				onClosed	:	function() {
					//parent.location.reload(true);
				}
			});

			$('a[id^="delete"]').fancybox({
				'width'				: '20%',
				'height'			: '20%',
				onStart		:	function() {
				return window.confirm('Do you want to delete?');
				},
				onClosed	:	function() {
					parent.location.reload(true);
				}
			});

		});
	</script>
		<!-- End FancyBox-->
    <!-- Le styles -->
<link href="assets/css/bootstrap.css" rel="stylesheet">
 <!-- <link href="assets/css/bootstrap-responsive.css" rel="stylesheet">-->
<link href="assets/js/google-code-prettify/prettify.css" rel="stylesheet">
   <style type="text/css">
      body {
		background: url(bg/bg.jpg) repeat center center fixed; 
        padding-top: 40px;
        padding-bottom: 20px;
        background-color: #fff;
		font-family:Arial, Helvetica, sans-serif,Comic Sans MS;
		font-size: 12px;
      }

      .form-signin {
        max-width: 1260px;
        padding: 9px 9px 9px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-signin .form-signin-heading,
      .form-signin .checkbox {
        margin-bottom: 10px;
      }
      .form-signin input[type="text"],
      .form-signin input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    .paginate {
	font-family:Arial, Helvetica, sans-serif;
	font-size: .7em;
	}
	a.paginate {
	border: 1px solid #000080;
	padding: 2px 6px 2px 6px;
	text-decoration: none;
	color: #000080;
	}
	h2 {
		font-size: 12pt;
		color: #003366;
		}

		 h2 {
		line-height: 1.2em;
		letter-spacing:-1px;
		margin: 0;
		padding: 0;
		text-align: left;
		}
	a.paginate:hover {
	background-color: #000080;
	color: #FFF;
	text-decoration: underline;
	}
	a.current {
	border: 1px solid #000080;
	font: bold .7em Arial,Helvetica,sans-serif;
	padding: 2px 6px 2px 6px;
	cursor: default;
	background:#000080;
	color: #FFF;
	text-decoration: none;
	}
	span.inactive {
	border: 1px solid #999;
	font-family: Arial, Helvetica, sans-serif;
	font-size: .7em;
	padding: 2px 6px 2px 6px;
	color: #999;
	cursor: default;
	}
	
.imageBox {
  position: absolute;
  visibility: hidden;
  border:5px solid #FF6600;
  top: 250px;
  padding:1px;
  width:580px;
  height:auto;
  overflow: hidden;
  text-align: center;
  left:500px;

}
div.imageBox img{
  width:580px;
  height:auto;
  overflow: hidden;
  text-align: center;
}
	h3:hover{
	font-size: 42px;
	}
	
	b.deaw{
		font: 18px arial, sans-serif;	
		color:#009933;
		font-weight: bold;
	}
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="../assets/js/html5shiv.js"></script>
    <![endif]-->

    <!-- Fav and touch icons -->

<link rel="shortcut icon" href="assets/ico/favicon.png">

<script language="Javascript">
function ShowPicture(id,show, img) {
  if (show=="1"){
    document.getElementById(id).style.visibility = "visible";
    document.getElementById(id).childNodes[1].src = img;
  }
  else if (show=="0"){
    document.getElementById(id).style.visibility = "hidden";
  }
}

</script>
</head>

  <body>
    <img src="icon/black_ribbon_bottom_right.png" class="black-ribbon stick-right stick-bottom" />
    <div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="brand" href="dataTC.php">AMS Naneye Information..</a>
          <div class="nav-collapse collapse">
		  <?php
		  error_reporting( error_reporting() & ~E_NOTICE );
		  if($_SESSION['UserName']==""){
			 ?>
			  <p class="navbar-text pull-right" title="Login">
              Logged in as <a href="login.php" class="various iframe" id="Login">Username</a>
            </p>
			<?php
		  }else{
            ?>
			<p class="navbar-text pull-right" title="Logout">
		  <?php 
		  include('manumember.php');
		  ?>
            </p>	
			<?php
		  }
			?>
			<ul class="nav">
			<?php			
			//include('menudataTC.php');		
			include('mainmenu.php');				
			?>
			</ul>
			
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
<div class="container">
<br>
<H3 style="color:#ff6600" align="center">AMS Naneye Process Traveller Card(T/C)</H3>
<marquee behavior="alternate" scrollamount="3" ><H2 style="color:#ff6600"><?php include('policy.php');?></H2></marquee>				

<?php
	//include 'testPass.php';
?>
<form name="frmSearch" method="GET" class="form-search" action="<?php echo $_SERVER['SCRIPT_NAME'];?>">
<div class="input-append">
    	<input name="txtKeyword" type="text" id="txtKeyword" style="width: 150px;" class="span2 search-query" title="ค้นหาโดยใส่ PO หรือ WA หรือ PN หรือ PN Lens หรือ Lot_Lens หรือ PN Die หรือ Lot_Die ครับ" placeholder="" autocomplete="off" value="<?php echo $_GET["txtKeyword"];?>" style="font-family:Comic Sans MS;color:#ff6600">
    	<div class="input-append">
			<button type="submit" class="btn" style="font-family:Comic Sans MS;color:#ff6600"><b>Search</b></button>&nbsp;
		</div>
	<div class="input-append">
	
	<a href="export_dataTC.php?txtKeyword=<?php echo rawurlencode($_GET["txtKeyword"]);?>"><img title="Export to Excel" class="img-circle" src="icon/excel.png" width="25" height="25" /></a>
	
	</div>	
	<div class="input-append">
		<?php
		  error_reporting( error_reporting() & ~E_NOTICE );
		  if($_SESSION['UserName']!=""){
		?>
		    <!--<a href="add_dataTC.php" target="_blank"><img title="Add Data" class="img-circle" src="icon/Add.jpg" width="25" height="25" /></a> -->
			<a href="add_dataTC_New.php" target="_blank"><img title="Add Data New TC" class="img-circle" src="icon/Add.jpg" width="25" height="25" /></a>
		<?php
		  }
         ?>
	</div>
	<?php include"menugraphNaneye.php";?>
</div>
</form>
	
<?php
if ($_GET["txtKeyword"] != "") {
       
		$strSQL = "SELECT  * FROM  tbl_datatc  WHERE (PO LIKE '%" . $_GET["txtKeyword"] . "%' or WA LIKE '%" . $_GET["txtKeyword"] . "%' or PN LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Die LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Die LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Lens LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Lens LIKE '%" . $_GET["txtKeyword"] . "%')";
	
		$strSumOutput="SELECT SUM(T_QTYOut6) AS SumOutput FROM tbl_datatc WHERE (PO LIKE '%" . $_GET["txtKeyword"] . "%' or WA LIKE '%" . $_GET["txtKeyword"] . "%' or PN LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Die LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Die LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Lens LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Lens LIKE '%" . $_GET["txtKeyword"] . "%')";
	
		$strSumInput="SELECT SUM(D_QTYIn2) AS SumInput FROM tbl_datatc WHERE (PO LIKE '%" . $_GET["txtKeyword"] . "%' or WA LIKE '%" . $_GET["txtKeyword"] . "%' or PN LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Die LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Die LIKE '%" . $_GET["txtKeyword"] . "%' or PN_Lens LIKE '%" . $_GET["txtKeyword"] . "%' or WFno_Lens LIKE '%" . $_GET["txtKeyword"] . "%')";
	
	} else {
        $strSQL = "SELECT * FROM tbl_datatc " ;
		
		$strSumOutput="SELECT SUM(T_QTYOut6) AS SumOutput FROM tbl_datatc";
	
		$strSumInput="SELECT SUM(D_QTYIn2) AS SumInput FROM tbl_datatc";
    }

    $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
    $Num_Rows = mysql_num_rows($objQuery);

		//count Input	
	 $objQueryInput = mysql_query($strSumInput) or die ("Error Query [".$strSumInput."]");
	 $SunOutput;
	while($objResultInput = mysql_fetch_array($objQueryInput))
		{
		//echo "InputTotal: = ";
		//echo number_format($objResultInput['SumInput']);
		$SumInput=$objResultInput['SumInput'];
		$SumInput1=number_format($objResultInput['SumInput']);
		}
	
	//count Output	
	$objQueryOutput = mysql_query($strSumOutput) or die ("Error Query [".$strSumOutput."]");
	while($objResultOutput = mysql_fetch_array($objQueryOutput))
		{
		//echo "&nbsp;&nbsp;&nbsp;&nbsp;	OutputTotal: = ";
		//echo number_format($objResultOutput['SumOutput']);
		$SumOutput=$objResultOutput['SumOutput'];
		$SumOutput1=number_format($objResultOutput['SumOutput']);
		}
	//(out/In)*100	
	$sumYield=@($SumOutput/$SumInput)*100;
	//echo "&nbsp;&nbsp;&nbsp;&nbsp;	Yield: = ";
	//echo round($sumYield,2)." %";
	
	echo "<b class='deaw' title='OutputShipping/InputDie soldering'>InputTotal: = $SumInput1 OutputTotal: = $SumOutput1 Yield: = ".round($sumYield,3). "%  </b>";
	
	$Per_Page = 8;   // Per Page

	$Page = $_GET["Page"];
	if(!$_GET["Page"])
	{
		$Page=1;
	}

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	//$Page_Start = (($Per_Page*$Page)-$Per_Page)+1;
	$Page_Start = (($Per_Page*$Page)-$Per_Page);
	if($Num_Rows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($Num_Rows % $Per_Page)==0)
	{
		$Num_Pages =($Num_Rows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($Num_Rows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}
	$Page_End = $Per_Page * $Page;
	if($Page_End > $Num_Rows)
	{
		$Page_End = $Num_Rows;
	}

$strSQL .=" order  by ID desc LIMIT $Page_Start , $Per_Page";	//asc and desc
$objQuery  = mysql_query($strSQL);

?>
<form class="form-signin">	

<table width="100%" class="table">
	<tr bgcolor="#000000">
		<td><div align="center"><b style="color:#ffffff">ID.</B> </div></td>
		<td><div align="center"><b style="color:#ffffff">PO. </B></div></td>
		<td><div align="center"><b style="color:#ffffff">WA. </B></div></td>
		<td><div align="center"><b style="color:#ffffff">BatchNo. </B></div></td>
		<td><div align="center"><b style="color:#ffffff">PN. </B></div></td>		
		<td><div align="center"><b style="color:#ffffff">StartSN. </B></div></td>
		<td><div align="center"><b style="color:#ffffff">EndSN. </B></div></td>
		<td><div align="center"><b style="color:#ffffff">PN Die.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Lot Die.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">PN Lens.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Lot Lens.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Total Fail.</B></div></td>
		<!--<td><div align="center"><b style="color:#ffffff">Detail Fail.</B></div></td>-->
		<td><div align="center"><b style="color:#ffffff">Yield.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">CumYield.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Excel.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Edit.</B></div></td>
		<td><div align="center"><b style="color:#ffffff">Delete.</B></div></td>
	</tr>
<?php
	$i=0;
	while($objResult = mysql_fetch_array($objQuery))
	{
	$Cal=@($objResult["T_QTYOut6"]/$objResult["R_QTYIn1"])*100;	
	
	$sumFailR=@($objResult["R_Fail1"]+$objResult["R_Fail2"]+$objResult["R_Fail3"]+$objResult["R_Fail4"]+$objResult["R_Fail5"]+$objResult["R_Fail6"]);
	$sumFailD=@($objResult["D_Fail1"]+$objResult["D_Fail2"]+$objResult["D_Fail3"]+$objResult["D_Fail4"]+$objResult["D_Fail5"]+$objResult["D_Fail6"]+$objResult["D_Fail7"]+$objResult["D_Fail8"]);
	$sumFailT=@($objResult["T_Fail1"]+$objResult["T_Fail2"]+$objResult["T_Fail3"]+$objResult["T_Fail4"]+$objResult["T_Fail5"]+$objResult["T_Fail6"]);
	$FailTotal=@$sumFailR+$sumFailD+$sumFailT;
	
	
	$DetailFailT=array($objResult["R_Remark1"],$objResult["R_Remark2"],$objResult["R_Remark3"],$objResult["R_Remark4"],$objResult["R_Remark5"],$objResult["R_Remark6"],
						$objResult["D_Remark1"],$objResult["D_Remark2"],$objResult["D_Remark3"],$objResult["D_Remark4"],$objResult["D_Remark5"],$objResult["D_Remark6"],$objResult["D_Remark7"],$objResult["D_Remark8"],
						$objResult["T_Remark1"],$objResult["T_Remark2"],$objResult["T_Remark3"],$objResult["T_Remark4"],$objResult["T_Remark5"],$objResult["T_Remark6"]);
	
	$i++;
?>
   <tr class="success">
      <?php
		if ($objResult["PNRibbon"]==""){
		?>
		 <td><div align="center"><a title="Show Data old TC" target="_blank" href="ShowdataTC.php?ID=<?php echo $objResult["ID"];?>"><?php echo $objResult["ID"];?></a></div></td>	 
		<?php
		}else{
		  ?>
		<td><div align="center"><a title="Show Data New TC" target="_blank" href="ShowdataTC_New.php?ID=<?php echo $objResult["ID"];?>"><?php echo $objResult["ID"];?></a></div></td>	
		<?php
		}
		?>				
		<td><div align="center"><?php echo $objResult["PO"];?></div></td>
		<td><div align="center"><?php echo $objResult["WA"];?></div></td>
		<td><div align="center"><?php echo $objResult["BatchNo"];?></div></td>
		<td><div align="center"><?php echo $objResult["PN"];?></div></td>
		<td><div align="center"><?php echo $objResult["StartSN"];?></div></td>
		<td><div align="center"><?php echo $objResult["EndSN"];?></div></td>
		<td><div align="center"><?php echo $objResult["PN_Die"];?></div></td>
		<td><div align="center"><?php echo $objResult["WFno_Die"];?></div></td>
		<td><div align="center"><?php echo $objResult["PN_Lens"];?></div></td>
		<td><div align="center"><?php echo $objResult["WFno_Lens"];?></div></td>
		<td><div align="center" style="color:#ff0000"><?php echo $FailTotal;?></div></td>
		<!--<td><div align="center">
		<?php for($i=0;$i<=19;$i++)
			{
				if($DetailFailT[$i]=="-" or $DetailFailT[$i] =="--"){									
					
				}else{					
					echo"$DetailFailT[$i]";
				}
				
			}
		?>
		</div></td>-->
		<?php
		if ($Cal >= 50){
		?>	
		<td><div align="center" style="color:#0000ff" ><?php echo round($Cal,2)." %";?></div></td>
		<?php
		}else{
		?>	
		<td><div align="center" style="color:#ff0000" ><?php echo round($Cal,2)." %";?></div></td>
		<?php
		}
		?>
		
		<?php
		if ($Cal >= 50){
		?>	
		<td><div align="center" style="color:#0000ff" ><a class="btn btn-mini btn-info" id="ShowYield" title="Show Yield." target="_blank" href="naneyeCalYield.php?ID=<?php echo $objResult["ID"];?>">Click.</a></div></td>
		<?php
		}else{
		?>	
		<td><div align="center" style="color:#ff0000" ><a class="btn btn-mini btn-info" id="ShowYield" title="Show Yield." target="_blank" href="naneyeCalYield.php?ID=<?php echo $objResult["ID"];?>">Click.</a></div></td>
		<?php
		}
		?>
		<?php
		if ($objResult["PNRibbon"]==""){
		?>
		<td><div><a class="btn btn-mini btn-success" title ="Click here to Export Excel File." href="reportTC.php?ID=<?php echo $objResult["ID"];?>" target="_blank">Click.</a></div></td>
		<td><div><a class="btn btn-mini btn-primary" title="Click here to Edit Data." class="various iframe" href="EditData_TC.php?ID=<?php echo $objResult["ID"];?>" target="_blank" >Click.</a></div></td>
		<?php
		}else{
		?>
		<td><div><a class="btn btn-mini btn-success" title ="Click here to Export Excel File." href="reportTC_New.php?ID=<?php echo $objResult["ID"];?>" target="_blank">Click.</a></div></td>
		<td><div><a class="btn btn-mini btn-primary" title="Click here to Edit Data." class="various iframe" href="EditData_TC_New.php?ID=<?php echo $objResult["ID"];?>" target="_blank" >Click.</a></div></td>
		<?php
		}
		?>
		
	    <td><div><a class="btn btn-mini btn-danger" title="Click here to Delete Data." class="various iframe" href="JavaScript:if(confirm('Confirm Delete?')==true){window.location='DeleteRecord_TC.php?ID=<?php echo $objResult["ID"];?>';}">Click.</a></div></td>

	</tr>
<?php
}
?>
</table>

</form>
<?php
if ($Num_Rows == "") {
    echo "No Record data.";
} else {
    echo "Total " . $Num_Rows . " Record : " . $Num_Pages . " Page : ";
}
?>
<?php
$pages = new Paginator;
$pages->items_total = $Num_Rows;
$pages->mid_range = 10;
$pages->current_page = $Page;
$pages->default_ipp = $Per_Page;
//$pages->url_next = $_SERVER["SCRIPT_NAME"]."?QueryString=$Next_Page&txtKeyword=$_GET[txtKeyword]&Page=";

//Suport อักขระพิเศษ-----------
$Keyword=rawurlencode($_GET[txtKeyword]);
$pages->url_next = $_SERVER["SCRIPT_NAME"]."?QueryString=$Next_Page&txtKeyword=$Keyword&Page=";
//-------------

$pages->paginate();
echo $pages->display_pages()
?>
 <div class="footer" >
 <center>
<?php
	include("footer.php");
?>
</center>
 </div>

</div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
	 <!-- <script src="assets/js/jquery.js"></script>-->
    <script src="assets/js/bootstrap-transition.js"></script>
    <script src="assets/js/bootstrap-alert.js"></script>
    <script src="assets/js/bootstrap-modal.js"></script>
    <script src="assets/js/bootstrap-dropdown.js"></script>
    <script src="assets/js/bootstrap-scrollspy.js"></script>
    <script src="assets/js/bootstrap-tab.js"></script>
    <script src="assets/js/bootstrap-tooltip.js"></script>
    <script src="assets/js/bootstrap-popover.js"></script>
    <script src="assets/js/bootstrap-button.js"></script>
    <script src="assets/js/bootstrap-collapse.js"></script>
    <script src="assets/js/bootstrap-carousel.js"></script>
    <script src="assets/js/bootstrap-typeahead.js"></script>
  </body>
</html>