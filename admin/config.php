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
/////////////////////////////////////////////////////////// Main DB configuration

	$localhost = "localhost";
	$username = "root";
//	$password = "Fz3e8ohb";
	$password = "root";
//	$password = "in8549luv";
//	$username = "thedotsquad";
//	$password = "jeH0v@H$";
	$database = "manuscripts";
	$database_mmf = "mmf";

/////////////////////////////////////////////////////////// Detaint all vars

	$dbc = @mysqli_connect($localhost, $username, $password);

	foreach($_POST as $key => $value) {
		$newVal = trim($value);
    //	$newVal = htmlspecialchars($newVal);
    	$newVal = mysqli_real_escape_string($dbc,$newVal);
		$_POST[$key] = $newVal;
	}

	foreach($_GET as $key => $value) {
		$newVal = trim($value);
    //	$newVal = htmlspecialchars($newVal);
    	$newVal = mysqli_real_escape_string($dbc,$newVal);
		$_GET[$key] = $newVal;
	}
	
?>