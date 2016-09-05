<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	FBTEE Manuscripts Ingestion Toolkit
//	Digital Humanities Research Group
//  School of Humanities and Communication Arts
//  University of Western Sydney
//
//	Procedural Scripting: PHP | MySQL | JQuery
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@uws.edu.au | jasondensor@gmail.com
//	Mobile: 0419 674 770
//
//  DATA SOURCES
//
//	FBTEE STN Data | http://fbtee.uws.edu.au/
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter v3.1.1 | http://getbootstrap.com/
//  Font Awesome v4.0.3 | http://fortawesome.github.io/Font-Awesome/
//  Google Fonts API | http://fonts.googleapis.com
//  Modernizr v.2.6.2 | http://modernizr.com/
//  JQuery v.1.11.0 | http://jquery.com/download/
//	JQuery JQPlot v.1.0.8 | http://www.jqplot.com/
//	JQuery UI v.10.4 | https://jqueryui.com/
//
//  VERSION 0.1
//  26 October 2015
//
//
/////////////////////////////////////////////////////////// Clean post and get

	session_start();
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	header("Cache-Control: no-cache");
	header("Pragma: no-cache");
	header("Content-type: text/html;charset=UTF-8");
	mb_internal_encoding("UTF-8");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	
/////////////////////////////////////////////////////////// Write document header
	
?>
<!DOCTYPE html>
<html lang="en">
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
	<head>
		<title>Visualise Stock Sales (Sellers and Superbooks)</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Visualise Text">
		<meta name="robots" content="noindex,nofollow">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
        <script language="javascript" type="text/javascript" src="./js/d3.js"></script>
    	<script language="javascript" type="text/javascript" src="./js/d3plus.js"></script>
        <style type="text/css">
		
			body { 
  				font: 11px helvetica neue, helvetica, arial, sans-serif;
			}
			
			#selected {
				z-index:2000;
				position: absolute;
				background-color: #ffffff;
				color: #000000;
				border-radius: 5px;
				border: 3px solid #222222;
				padding: 10px 10px 10px 10px;
				bottom: 35px;
				left: 15px;
				height: 95px;
				width: 400px;
				opacity: 0.8;
				filter: alpha(opacity=80);
			}

			#selected h1 {
				font-size: 20px;
				margin: 0px;
				line-height: 20px;
				font-weight: bold;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

				#selected p {
				font-size: 12px;
				margin: 0px;
				line-height: 30px;
				font-weight: bold;
				white-space: nowrap;
				overflow: hidden;
				text-overflow: ellipsis;
			}

		</style>
        <link rel="apple-touch-icon" href="./touch-icon-iphone.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="./touch-icon-ipad.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="./touch-icon-iphone-retina.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="./touch-icon-ipad-retina.png" />
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png" />
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png" />
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png" />
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png" />
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png" />
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png" />
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png" />
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png" />
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png" />
		<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png" />
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png" />
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
        <link rel="icon" type="image/x-icon" href="./favicon.ico" />
		<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />     
        <link rel="manifest" href="./manifest.json" />
        <meta name="msapplication-TileColor" content="#ffffff" />
		<meta name="msapplication-TileImage" content="/ms-icon-144x144.png" />
		<meta name="theme-color" content="#ffffff" />
	</head>
	<body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <div id="selected" style="height:auto;padding:30px;">
		<p style="text-align:center;"><img src="./western_sydney_logo.png" alt="" width="50%"></p>
		<p style="line-height:30px;">
    		<h3 style="color:#800000;line-height:1.3em;text-align:center;">STOCK SALES<br />Sellers and Superbooks</h3>
        	Professor Simon Burrows<br />School of Humanities &amp; Communication Arts<br />Western Sydney University<br /><br />
    	</p>
	</div>
    <div id="viz"></div>
<?php
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Database Routines

	$sample_data = array();
	$sample_data_size = array();
	$connections = array();
	$nodes = array();
	$buyers = array();
	$people = 0;

/////////////////////////////////////////////////////////// Get Stock Sales Total	

	$myQueryCheck = "SELECT SUM(EventCopies) ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[0] = round("$row[0]",0);
		$sample_data[] = "{ \"name\": \"Stock Sales\", \"size\": $row[0] }";
	}
	
/////////////////////////////////////////////////////////// Get All Sales Event Type Nodes	
	
	$myQueryCheck = "SELECT DISTINCT(EventSaleReasonText), SUM(EventCopies) ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "GROUP BY EventSaleReasonText ";
	$myQueryCheck .= "ORDER BY EventSaleReasonText ASC ";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[1] = round("$row[1]",0);	
		$sample_data[] = "{ \"name\": \"$row[0]\", \"size\": $row[1] }";
		$connections[] = "{ \"source\": \"$row[0]\", \"target\": \"Stock Sales\" }";
		$nodes[] = "$row[0]";
	}
	
/////////////////////////////////////////////////////////// Data Preparation for Seller

	$myQueryCheck = "SELECT COUNT(DISTINCT(ID_Sale_AgentText)) ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[0] = round("$row[0]",0);
		$sample_data[] = "{ \"name\": \"Seller\", \"size\": $row[0] }";
		$people = $row[0];
	}
	
/////////////////////////////////////////////////////////// Data Preparation for Buyer
//	
//	$myQueryCheck = "SELECT COUNT(DISTINCT(ID_DealerName)) ";
//	$myQueryCheck .= "FROM manuscript_events_sales ";
//	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
//	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
//	$result = $mysqli->query("$myQueryCheck");
//	while ($row = mysqli_fetch_row($result)){
//		$row[0] = round("$row[0]",0);
//		$sample_data[] = "{ \"name\": \"Buyer\", \"size\": $row[0] }";
//		$people = ($people + $row[0]);
//	}
//	
/////////////////////////////////////////////////////////// Data Preparation for People
	
	$sample_data[] = "{ \"name\": \"People\", \"size\": $people }";
//	$connections[] = "{ \"source\": \"Buyer\", \"target\": \"People\" }";
	$connections[] = "{ \"source\": \"Seller\", \"target\": \"People\" }";
	
/////////////////////////////////////////////////////////// Get All Individual Sellers	
	
	$myQueryCheck = "SELECT DISTINCT(ID_Sale_AgentText), COUNT(ID_Sale_AgentText) ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "GROUP BY ID_Sale_AgentText ";
	$myQueryCheck .= "ORDER BY ID_Sale_AgentText ASC ";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		$row[1] = round("$row[1]",0);
		$row[0] = iconv("ISO-8859-1","UTF-8",$row[0]);
		$sample_data["$row[0]"] = "{ \"name\": \"$row[0]\", \"size\": $row[1] }";
		$sample_data_size["$row[0]"] = "$row[1]";
		$connections[] = "{ \"source\": \"$row[0]\", \"target\": \"Seller\" }";
		foreach($nodes as $n) {
			$queryF = "SELECT DISTINCT(ID_Sale_AgentText) ";
			$queryF .= "FROM manuscript_events_sales ";
			$queryF .= "WHERE EventType = \"Stock Sale\" ";
			$queryF .= "AND EventSaleReasonText = \"$n\" ";
			$queryF .= "AND ID_Sale_AgentText = \"$row[0]\" ";
			$queryF .= "GROUP BY ID_Sale_AgentText ";
			$queryF .= "ORDER BY ID_Sale_AgentText ASC ";
			$mysqliF = new mysqli("$localhost", "$username", "$password", "$database");
			$resultF = $mysqliF->query("$queryF");
			while ($rowF = mysqli_fetch_row($resultF)){
				$connections[] = "{ \"source\": \"$row[0]\", \"target\": \"$n\" }";
			}	
		}
	}
	
/////////////////////////////////////////////////////////// Get All Individual Buyers
//
//	$myQueryCheck = "SELECT DISTINCT(ID_DealerName), COUNT(ID_DealerName) ";
//	$myQueryCheck .= "FROM manuscript_events_sales ";
//	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
//	$myQueryCheck .= "GROUP BY ID_DealerName ";
//	$myQueryCheck .= "ORDER BY ID_DealerName ASC ";
//	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
//	$result = $mysqli->query("$myQueryCheck");
//	while ($row = mysqli_fetch_row($result)){
//		$buyerName = "Unknown";
//		$buyerProfession = "Unknown";
//		$row[1] = round("$row[1]",0);
//		$queryF = "SELECT Dealer_Name, Profession FROM manuscript_dealers WHERE Client_Code = \"$row[0]\" ";
//		$mysqliF = new mysqli("$localhost", "$username", "$password", "$database");
//		$resultF = $mysqliF->query("$queryF");
//		while ($rowF = mysqli_fetch_row($resultF)){
//			$buyerName = $rowF[0];
//			$buyerProfession = $rowF[1];
//		}
//		$buyerName = iconv("ISO-8859-1","UTF-8",$buyerName);
//		$needle = "$buyerName ($buyerProfession)";
//		$buyers["$row[0]"] = "$needle";
//		if(($sample_data["$needle"])) {
//			$t = $sample_data_size["$needle"];
//			$t = ($t + $row[1]);
//			$sample_data["$needle"] = "{ \"name\": \"$needle\", \"size\": $t }";
//		} else {
//			$sample_data[] = "{ \"name\": \"$buyerName ($buyerProfession)\", \"size\": $row[1] }";
//		}
//		$connections[] = "{ \"source\": \"$buyerName ($buyerProfession)\", \"target\": \"Buyer\" }";
//		foreach($nodes as $n) {
//			$queryF = "SELECT DISTINCT(ID_DealerName) ";
//			$queryF .= "FROM manuscript_events_sales ";
//			$queryF .= "WHERE EventType = \"Stock Sale\" ";
//			$queryF .= "AND EventSaleReasonText = \"$n\" ";
//			$queryF .= "AND ID_DealerName = \"$row[0]\" ";
//			$queryF .= "GROUP BY ID_DealerName ";
//			$queryF .= "ORDER BY ID_DealerName ASC ";
//			$mysqliF = new mysqli("$localhost", "$username", "$password", "$database");
//			$resultF = $mysqliF->query("$queryF");
//			while ($rowF = mysqli_fetch_row($resultF)){
//				$connections[] = "{ \"source\": \"$buyerName ($buyerProfession)\", \"target\": \"$n\" }";
//			}	
//		}
//	}
//	
/////////////////////////////////////////////////////////// Get All SuperBooks Nodes and Connections	
	
	$myQueryCheck = "SELECT DISTINCT(ID_SuperBookTitle), SUM(EventCopies) ";
	$myQueryCheck .= "FROM manuscript_events_sales ";
	$myQueryCheck .= "WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "GROUP BY ID_SuperBookTitle ";
	$myQueryCheck .= "ORDER BY ID_SuperBookTitle ASC ";	
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		if(($row[0] != "")) {
			$queryF = "SELECT super_book_title FROM manuscript_books WHERE super_book_code = \"$row[0]\" ";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				
////////////////////////////////// Add Name Data				
				
				$rowF[0] = preg_replace("/\"/i","'","$rowF[0]");
				$row[1] = round("$row[1]",0);				
				$sample_data[] = "{ \"name\": \"$rowF[0]\", \"size\": $row[1] }";
				
////////////////////////////////// Add Stock Sale Type Connection					
				
				$queryA = "SELECT DISTINCT(EventSaleReasonText) FROM manuscript_events_sales WHERE EventType = \"Stock Sale\" AND ID_SuperBookTitle = \"$row[0]\" ORDER BY EventSaleReasonText ASC ";
				$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
				while($rowA = mysqli_fetch_row($mysqli_resultA)) {
					$connections[] = "{ \"source\": \"$rowF[0]\", \"target\": \"$rowA[0]\" }";
				}
				
////////////////////////////////// Add Seller Connection				
				
				$queryA = "SELECT DISTINCT(ID_Sale_AgentText) FROM manuscript_events_sales WHERE EventType = \"Stock Sale\" AND ID_SuperBookTitle = \"$row[0]\" ORDER BY ID_Sale_AgentText ASC ";
				$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
				while($rowA = mysqli_fetch_row($mysqli_resultA)) {
					$connections[] = "{ \"source\": \"$rowF[0]\", \"target\": \"$rowA[0]\" }";
				}
			}
		}
	}	

/////////////////////////////////////////////////////////// Prepare Nodes and Connections	

	$sample_data = array_unique($sample_data);
	$connections = array_unique($connections);
	sort($sample_data);
	sort($connections);
	$cConn = count($connections);
	$cData = count($sample_data);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Do Viz

?>
 <script language="javascript" type="text/javascript">

  var sample_data = [
<?php
	
		$i = 0;	
		foreach($sample_data as $sd) {
			$i++;
			echo $sd;
			if(($i != $cData)) {
				echo ",\n";
			} else {
				echo "\n";	
			}
		}
	
?>
  ]

  var connections = [
<?php
	
		$i = 0;	
		foreach($connections as $conn) {
			$i++;
			echo $conn;
			if(($i != $cConn)) {
				echo ",\n";
			} else {
				echo "\n";	
			}
		}
	
?>
  ]

  var visualization = d3plus.viz()
    .container("#viz") 
    .type("network")  
    .data(sample_data) 
    .edges(connections) 
    .size("size")  
    .id("name") 
	.focus({
      "tooltip" : true
    })
    .draw() 

</script>
</body>
</html>
<?php

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>