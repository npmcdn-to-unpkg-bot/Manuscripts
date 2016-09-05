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
//  21 August 2014
//
//
/////////////////////////////////////////////////////////// Vars

	session_start();
	header("Content-type: text/html;charset=UTF-8");
//	header("Content-type: text/html;charset=ISO-8859-1");
	mb_internal_encoding("UTF-8");
	$wildcard = "";
	$do_not_process = "";
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$needle = strtolower($_GET['term']);
	$inputReset = $_GET["inputReset"];
	$inputBox = $_GET["inputBox"];
	$inputCode = $_GET["inputCode"];
	$inputFilter = $_GET["inputFilter"];
	$return_arr = array();
	$found = "";
	
/////////////////////////////////////////////////////////// Find author names and author codes

	if(($inputReset != "yes")) {
		
		$query = "SELECT author_name, author_code FROM manuscript_authors WHERE author_name LIKE \"%$inputFilter%\" AND author_name != \"\" AND author_name IS NOT NULL ORDER BY author_name ASC";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$row[0] = preg_replace("/$inputFilter/","<u>$inputFilter</u>","$row[0]");
			$inputFilter = ucwords($inputFilter);
			$row[0] = preg_replace("/$inputFilter/","<u>$inputFilter</u>","$row[0]");
		//	$row[0] = preg_replace("/\//i","\/",$row[0]);
			$array = "$row[0]|$row[1]";
			$return_arr[] = $array;
			$found = "y";
		}
		
		if(($found == "y")) {
			$c = count($return_arr);
			$d = 0;
			if(($c > 0)) {
				foreach($return_arr as $ra) {
					$vars = explode("|","$ra");
					$r = $vars[0];
					$s = $vars[0];
					$t = $vars[1];
					$s = strip_tags($s);
					$s = addslashes($s);
					echo "<a href=\"javascript: ";
					echo "var updateInput = $('#".$inputBox."').val('".$s."'); ";
					echo "var updateCode = $('#".$inputCode."').val('".$t."'); ";
					echo "var show = $('#show_results').load('./data_authors.php?inputReset=yes',function(){});\" ";
					echo "style=\"text-decoration:none; color: #800000;\">".$r."</a>";
					if(($d < $c)) {
						echo "<br />";	
					}
					$d++;
				}
			}
		}
	}
	
/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>