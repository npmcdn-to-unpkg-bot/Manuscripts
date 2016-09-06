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
//	6 September 2016
//
//
/////////////////////////////////////////////////////////// Clean post and get	
	
	if(($reload == "")) {
		$MerdUser = session_id();
		if(empty($MerdUser)) { session_start(); }
		$SIDmerd = session_id();
		header("Content-type: text/html;charset=UTF-8");
		mb_internal_encoding("UTF-8");
		include("../config.php");
		include("../era.dbconnect.php");
		include("./index_functions.php");
		if (!mysqli_set_charset($mysqli_link, "utf8")) {
			echo "PROBLEM WITH CHARSET!";
			die;
		}
		$kID = $_GET["kID"];
		$_GET = array();
		$_POST = array();
	}
	
/////////////////////////////////////////////////////////// Load associations

	$keywords = array();
	$queryD = "SELECT DISTINCT(keywords) FROM manuscript_books WHERE keywords LIKE \"%$kID%\" ";
	$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
	while($rowD = mysqli_fetch_row($mysqli_resultD)) {
		if(preg_match("/,/","$rowD[0]")) {
			if(($rowD[0] != $kID)) {
				$tempKeys = explode(",","$rowD[0]");
			}
		} else {
			if(($rowD[0] != $kID)) {
				$tempKeys = array();
				$tempKeys[] = $rowD[0];
			}
		}
		foreach($tempKeys as $t) {
			$cK = 0;
			$queryE = "SELECT COUNT(*) FROM manuscript_books WHERE keywords LIKE \"%$kID%\" AND keywords LIKE \"%$t%\" ";
			$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
			while($rowE = mysqli_fetch_row($mysqli_resultE)) {
				$cK = $rowE[0];
				if(($cK < 10)) { $cK = "0".$cK; }
			}
			$keywords[] = "$cK|$t";	
		}
	}
	if(($keywords[0] != "")) {
		$count = 0;
		$keywords = array_unique($keywords);
		rsort($keywords);
		foreach($keywords as $kk) {
			$go = explode("|","$kk");
			$queryD = "SELECT * FROM keywords WHERE keyword_code LIKE \"$go[1]\"";
			$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
			while($rowD = mysqli_fetch_row($mysqli_resultD)) {
				echo "<li>";
				echo "<a href=\"javascript: ";
				echo "var doThis = tagApi.tagsManager('pushTag','$rowD[1]'); \" ";
				echo "style=\"color:#333333;\" >";
				echo "$rowD[1]";
				echo "</a></li>";
			}
			if(($count > 8)) {
				break;	
			}
			$count++;
		}
	} else {
		$queryD = "SELECT * FROM keywords WHERE keyword_code LIKE \"$kID\"";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		while($rowD = mysqli_fetch_row($mysqli_resultD)) {
			$myKeyword = ucwords($rowD[1]);
			echo "No associated terms for $myKeyword.";	
		}
	}
	
/////////////////////////////////////////////////////////// Finish

	include("../era.dbdisconnect.php");

?>