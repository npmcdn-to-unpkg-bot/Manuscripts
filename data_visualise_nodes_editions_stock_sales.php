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
//  21 October 2015
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
		<title>Visualise Stock Sales (All Editions)</title>
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
	
		</style>
	</head>
	<body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <div id="viz"></div>
<?php
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Database Routines

	$sample_data = array();
	$connections = array();
	$nodes = array();

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
	}
	
/////////////////////////////////////////////////////////// Get All Edition Nodes and Connections	
	
	$myQueryCheck = "SELECT DISTINCT(ID_EditionName), SUM(EventCopies) ";
	$myQueryCheck .= "FROM manuscript_events_sales WHERE EventType = \"Stock Sale\" ";
	$myQueryCheck .= "GROUP BY ID_EditionName ORDER BY ID_EditionName ASC ";
	$mysqli = new mysqli("$localhost", "$username", "$password", "$database");
	$result = $mysqli->query("$myQueryCheck");
	while ($row = mysqli_fetch_row($result)){
		if(($row[0] != "")) {
			$queryF = "SELECT full_book_title FROM manuscript_books_editions WHERE book_code = \"$row[0]\" ";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				$rowF[0] = preg_replace("/\"/i","'","$rowF[0]");
				$sample_data[] = "{ \"name\": \"$rowF[0]\", \"size\": $row[1] }";
				$queryA = "SELECT DISTINCT(EventSaleReasonText) FROM manuscript_events_sales WHERE EventType = \"Stock Sale\" AND ID_EditionName = \"$row[0]\" ORDER BY EventSaleReasonText ASC ";
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
 <script language="javascript" type="text/javascript" >

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