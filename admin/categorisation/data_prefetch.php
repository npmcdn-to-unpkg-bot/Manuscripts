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

	echo "[\n";
	$keywords = array();
	$queryD = "SELECT * FROM keywords ORDER BY keyword ASC";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		$keywords[] = trim(ucwords($rowD[1]));
	}
	sort($keywords);
	$c = count($keywords);
	$x = 1;
	foreach($keywords as $k) {
		if(($x == $c)) {
			echo "\"$k\"\n";
		} else {
			echo "\"$k\",\n";	
		}
		$x++;
	}
	echo "]";

/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>