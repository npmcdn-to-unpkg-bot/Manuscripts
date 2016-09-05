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
//	header("Content-type: text/html;charset=ISO-8859-1");
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
		<title>Visualise Text</title>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Visualise Text">
		<meta name="robots" content="noindex,nofollow">
		<meta http-equiv='cache-control' content='no-cache'>
		<meta http-equiv='expires' content='0'>
		<meta http-equiv='pragma' content='no-cache'>
		<script language="javascript" type="text/javascript" src="./js/jquery-2.1.1.js"></script>
        <script language="javascript" type="text/javascript" src="./js/cytoscape.min.js"></script>
        <script language="javascript" type="text/javascript" src="./js/arbor/arbor-lite.js"></script>
		<script language="javascript" type="text/javascript" src="./js/arbor/arbor-graphics.js"></script>
		<script language="javascript" type="text/javascript" src="./js/arbor/arbor-renderer.js"></script>
        <script language="javascript" type="text/javascript" src="./js/springy.js"></script>
        <script language="javascript" type="text/javascript" src="./js/springyui.js"></script>
		<!--[if lt IE 9]>
			<script language="javascript" type="text/javascript" src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <style type="text/css">
body { 
  font: 14px helvetica neue, helvetica, arial, sans-serif;
  background-color: #ffffff;
}

#cy {
  height: 100%;
  width: 100%;
  position: absolute;
  left: 0;
  top: 0;
}

#info {
  color: #c88;
  font-size: 1em;
  position: absolute;
  z-index: -1;
  left: 1em;
  top: 1em;
}		
		</style>
        <link rel="stylesheet" type="text/css" href="./css/visualise.css">
	</head>
	<body style="overflow-x: hidden;">
	<!--[if lt IE 7]>
    	<p class="browsehappy">You are using an <strong>outdated</strong> browser. 
    	Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
    <div id="cy"></div>
<?php
	
/////////////////////////////////////////////////////////// Get Data

	$edges = array();
	$nodes = array();
	$query = "SELECT DISTINCT ID_SuperBookTitle, ID_PlaceName FROM manuscript_events ORDER BY ID_SuperBookTitle ASC";
	$mysqli_result = mysqli_query($mysqli_link, $query);
	while($row = mysqli_fetch_row($mysqli_result)) {
		$titletemp = "";
		$placetemp = "";
		$nlink = "";
		$subQ = "SELECT DISTINCT ID_SuperBookTitle, ID_PlaceName, COUNT(DISTINCT ID_PlaceName) AS ID_Count FROM manuscript_events WHERE ID_SuperBookTitle = \"$row[0]\" GROUP BY ID_SuperBookTitle ASC";
		$mysqli_resultQ = mysqli_query($mysqli_link, $subQ);
		while($rowQ = mysqli_fetch_row($mysqli_resultQ)) {
			if(($rowQ[2] > 1)) {
				$nlink = "y";	
			}
		}
		if(($nlink == "y")) {
			$sumBooks = "0";
			$sumBooksB = "0";
			$queryF = "SELECT SUM(EventCopies) FROM manuscript_events WHERE ID_SuperBookTitle = \"$row[0]\"";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				$sumBooks = $rowF[0];
			}
			$sumBooksB = number_format($sumBooks);
			$queryF = "SELECT super_book_title FROM manuscript_books WHERE super_book_code = \"$row[0]\"";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				$rowF[0] = preg_replace("/\"/i","'","$rowF[0]");
				if(strlen($rowF[0]) > 100) {
					$rowF[0] = substr($rowF[0], 0, 100);	
				}
				$rowF[0] .= " ... [".$row[0]."] - $sumBooksB copies";
				$r = rand(128,255); 
       			$g = rand(128,255); 
       			$b = rand(128,255); 
       			$color = dechex($r) . dechex($g) . dechex($b);
       			$faveColor = "#".$color;
				$faveColor = "#dba0c3";
				$nodes[] = "{ data: { id: \"$row[0]\", realname: \"\", name: \"$rowF[0]\", copies: \"$sumBooks\", faveColor: \"$faveColor\", href: \"#\" } }";
			}
			$queryF = "SELECT Place_Name FROM manuscript_places WHERE Place_Code = \"$row[1]\"";
			$mysqli_resultF = mysqli_query($mysqli_link, $queryF);
			while($rowF = mysqli_fetch_row($mysqli_resultF)) {
				$rowF[0] = preg_replace("/\"/i","'","$rowF[0]");
				$nodes[] = "{ data: { id: \"$row[1]\", realname: \"$rowF[0]\", name: \"$rowF[0]\", faveColor: \"#9eb6d8\", href: \"#\" } }";
			}
			$edges[] = "{ data: { source: \"$row[0]\", target: \"$row[1]\" } }";
		}
	}
	$edges = array_unique($edges);
	$nodes = array_unique($nodes);

/////////////////////////////////////////////////////////// Visualise

?>
    <script language="javascript" type="text/javascript">
	
	$(document).ready(function(){
		var cy = cytoscape({
  			container: $('#cy')[0],
  			style: cytoscape.stylesheet()
    		.selector('node')
      		.css({
//        		'content': 'data(name)',
				'opacity' : '1.0',
				'content': 'data(realname)',
        		'color': '#000000',
				'font-family': 'verdana',
				'font-size' : '1em',
				'line-color': '#000000',
        		'target-arrow-color': '#000000',
        		'source-arrow-color': '#000000',
        		'text-outline-color': '#000000',
				'text-halign': 'center',
    			'text-valign': 'center',
				'background-color' : 'data(faveColor)',
				'width': 'mapData(degree,0,15,8,70)',
    			'height': 'mapData(degree,0,15,8,70)'
      		})
    		.selector(':selected')
      		.css({
				'content': 'data(name)',
        		'line-color': '#000000',
        		'target-arrow-color': '#000000',
        		'source-arrow-color': '#000000',
        		'text-outline-color': '#000000',
				'background-color': 'data(faveColor)'
      		}),
  			elements: {
    			nodes: [
<?php
	$c = count($nodes);
	$b = 0;
	foreach($nodes as $n) {
		$b++;
		echo $n;
		if(($b != $c)) {
			echo ",\n";
		} else {
			echo "\n";
		}
	}
?>
    			],
    			edges: [
<?php
	$c = count($edges);
	$b = 0;
	foreach($edges as $n) {
		$b++;
		echo $n;
		if(($b != $c)) {
			echo ",\n";
		} else {
			echo "\n";
		}
	}
?>
    			]
  			},
			zoom: 1,
			minZoom: 1,
			maxZoom: 1,
//			layout: {
//    			name: 'arbor',
//				animate: true,
//				maxSimulationTime: 8000,
//    			padding: 5,
//				fit: true,
//				repulsion: 150,
//				stiffness: 110,
//				friction: 0.2,
//				gravity: false
//			}
  			layout: {
    			name: 'springy',
				animate: true,
				maxSimulationTime: 8000,
    			padding: 5,
				fit: true,
				random: true,
				infinite: true,
				repulsion: 450,
				stiffness: 110,
				damping: 0.2
  			}
		});
  
  		var degmap = {};
		var nodes = cy.nodes();
		for (var i = 0; i < nodes.length; i++) {
			var cBooks = nodes[i].data('copies');
			var cBooks = (cBooks / 250);
			var checkTitle = nodes[i].data('realname');
			if(checkTitle == "") {
 //   			degmap[nodes[i].id()] = { degree: nodes[i].degree() };
				degmap[nodes[i].id()] = { degree: cBooks };
			} else {
				degmap[nodes[i].id()] = { degree: 10 };
			}
		}
		cy.batchData(degmap);

//		cy.on('mouseover', 'node', function(){
//	  		var bookTitle = this.data('name');
//			var checkTitle = this.data('realname');
//			if(checkTitle == "") {
//				var newTitle = this.css({
//        			'content': bookTitle
//					'color': '#000000'
//				});
//			}
//		});
//		
//		cy.on('mouseout', 'node', function(){
//	  		var bookTitle = this.data('name');
//			var checkTitle = this.data('realname');
//			if(checkTitle == "") {
//				var myself = this;
//				var hoverTimeout = setTimeout(function() {
//					var newTitle = myself.css({
//        				'content': ''
//					});
//				}, 2000);
//			}
//		});
			
	});
	
	</script>
    </body>
</html>
<?php

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>