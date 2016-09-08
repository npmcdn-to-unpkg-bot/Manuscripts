<?php

/////////////////////////////////////////////////////////// Credits
//
//
//	Manuscripts Categorisation Toolkit
//	Digital Humanities Research Group
//  School of Humanities and Communication Arts
//  Western Sydney University
//
//	Procedural Scripting: PHP | MySQL | JQuery
//
//	FOR ALL ENQUIRIES ABOUT CODE
//
//	Who:	Dr Jason Ensor
//	Email: 	j.ensor@westernsydney.edu.au | jasondensor@gmail.com
//	Web: 	http://www.jasonensor.com
//	Mobile: 0419 674 770
//
//  DATA SOURCES
//
//	FBTEE STN Data | http://fbtee.uws.edu.au/
//
//  WEB FRAMEWORK
//
//  Bootstrap Twitter | http://getbootstrap.com/
//	JQuery | http://jquery.com/download/
//	Nifty Responsive Admin Template | https://wrapbootstrap.com/theme/nifty-responsive-admin-template-WB0048JF7
//
//  VERSION 0.1
//	5 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	
/////////////////////////////////////////////////////////// Clean post and get	
	
	header("Content-type: text/html;charset=UTF-8");
	mb_internal_encoding("UTF-8");
	include("../config.php");
	include("../era.dbconnect.php");
	include("./index_functions.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$_GET = array();
	$_POST = array();

/////////////////////////////////////////////////////////// Data routine

	echo "var nodes = [\n";
	$keywords = array();
	$knodes = array();
	$b = 1;
	$queryD = "SELECT * FROM keywords ORDER BY keyword ASC";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		$keywords[] = trim(ucwords($rowD[1]));
		$temp_kid = $rowD[0];
		$knodes["$b"] = "$temp_kid";
		$b++;
	}
	$c = count($keywords);
	$d = 1;
	foreach($keywords as $k) {
		echo "\t{id: $d, label: \"$k\"}";	
		if(($d != $c)) {
			echo ",\n";	
		} else {
			echo "\n";	
		}
		$d++;
	}
	echo "];\n\n";
	echo "var edges = [\n";
	$edges = "";
	foreach($knodes as $kn) {
		$matches = "";
		$key = array_search("$kn", $knodes);	
		$queryD = "SELECT keywords FROM manuscript_books WHERE keywords LIKE \"%$kn%\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			if(preg_match("/,/i",$rowD[0])) {
				$matches = explode(",","$rowD[0]");	
			} else {
				$matches = array();
				$matches[] = "$rowD[0]";
			}
		}
		if(($matches != "")) {
			foreach($matches as $m) {
				$keyM = array_search("$m", $knodes);	
				if(($key != $keyM)) {
					$edges .= "\t{from: $key, to: $keyM},\n";
				}
			}
		}
	}
	$edges = rtrim($edges, ",\n");
	echo $edges."\n";
	echo "];\n\n";

/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>