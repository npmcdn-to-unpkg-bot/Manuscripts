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
	include("./admin/config.php");
	include("./admin/era.dbconnect.php");
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	$pk = $_POST['pk'];
    $name = $_POST['name'];
    $value = $_POST['value'];

/////////////////////////////////////////////////////////// Data routine

	if(!empty($value) && !empty($pk) && !empty($name)) {
		$name = preg_replace("/update/i","","$name");
		$queryD = "UPDATE manuscript_events SET $name = \"$value\" WHERE ID = \"$pk\" ";
		$mysqli_resultD = mysqli_query($mysqli_link, $queryD);
		header('HTTP 200 OK', true, 200);
	} else {
        header('HTTP 400 Bad Request', true, 400);
        echo "This field is required!";
    }
	
/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>