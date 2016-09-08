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
//	8 September 2016
//
//
/////////////////////////////////////////////////////////// Collect session data

	$MerdUser = session_id();
	if(empty($MerdUser)) { session_start(); }
	$SIDmerd = session_id(); 
	
/////////////////////////////////////////////////////////// Clean post and get	
	
	mb_internal_encoding("UTF-8");
	include("../config.php");
	include("../era.dbconnect.php");
	include("./index_functions.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$action = $_GET["action"];
	$super_book_code = $_GET["super_book_code"];
	$_GET = array();
	$_POST = array();

/////////////////////////////////////////////////////////// Data routine

	if(($action != "") && ($super_book_code != "")) {
		if(($action == "add")) {
			$theTime = date("Y-m-d H:i:s",time());
			$queryD = "INSERT INTO manuscript_cat_problem VALUES(\"0\", \"$super_book_code\", \"$theTime\", \"INVALID\", \"admin\"); ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		}
		if(($action == "delete")) {
			$queryD = "DELETE FROM manuscript_cat_problem WHERE super_book_code = \"$super_book_code\" ";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		}
	}

/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>