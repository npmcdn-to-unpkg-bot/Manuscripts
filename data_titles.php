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
	$needle = strtolower($_GET['term']);
	$variation = $_GET["variation"];	
	$found = "";
	if (!mysqli_set_charset($mysqli_link, "utf8")) {
		echo "PROBLEM WITH CHARSET!";
		die;
	}
	
/////////////////////////////////////////////////////////// Clean out generic phrase
	
	$haystack = "les ";
	if(strlen($needle)<4) {
		if (strlen(strstr($haystack,$needle))>0) { 
			$do_not_process = "y"; 
		}
	} else {
		$donot_process = "";
	}
	
/////////////////////////////////////////////////////////// Clean out another generic phrase

	$haystack = "la ";
	if(strlen($needle)<3) {
		if (strlen(strstr($haystack,$needle))>0) { 
			$do_not_process = "y"; 
		}
	} else {
		$donot_process = "";
	}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Create JSON encoded array

	if(($do_not_process == "")) {
		
		if(preg_match("/\*/i",$_GET['term'])) {
			$wildcard = "y";
			$_GET['term'] = preg_replace("/\*/i","",$_GET['term']);
		}

		if (isset($_GET['term'])){
			$_GET['term'] = preg_replace("/[^a-zA-Z0-9\s()']/", "", $_GET['term']);
			$seek = $_GET['term'];
			$seekB = preg_replace("/(l')/i", "", "$seek");
			$seekB = preg_replace("/(la)/i", "", "$seekB");
			$seekB = preg_replace("/\(le\)/i", "", "$seekB");
			$seekB = preg_replace("/(les)/i", "", "$seekB");
			$seekB = preg_replace("/\(/i", " ", "$seekB");
			$seekB = preg_replace("/\)/i", " ", "$seekB");
			$seekB = preg_replace("/  /i", " ", "$seekB");
			$return_arr = array();
			if(($wildcard == "y")) {
				$return_arr[] = "<u>".$_GET['term']."</u>*";	
			}
			
/////////////////////////////////////////////////////////// Title search			
			
			if(($variation == "TITLE")) {
				
//				$seeks = explode(" ",$seekB);
//				$cs = count($seeks);
//				if(($cs < 2)) {
//					$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE ";
//					$query .= "super_book_title LIKE \"%$seek%\" ";
//					$query .= "AND super_book_title != \"\" ";
//					$query .= "AND super_book_title IS NOT NULL ";
//					$query .= "ORDER BY replace(super_book_title, '[', '') ASC";
//				} else {
//					$sought = "";
//					$b = ($cs-1);
//					for($a=0;$a<$cs;$a++){
//						$sought .= $seeks[$a];
//						if($a != $b) {
//							$sought .= " +[(l'aes)]+ ";
//						}
//					}
//					
//					$sought = "ame +[(laes')]+ reli";
//					$sought = "ame .+reli.+";
//					
//					$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE ";
//					$query .= "(super_book_title RLIKE \"".$sought."\" ";
//					$query .= "OR super_book_title LIKE \"%$seek%\") ";
//					$query .= "AND super_book_title != \"\" ";
//					$query .= "AND super_book_title IS NOT NULL ";
//					$query .= "ORDER BY replace(super_book_title, '[', '') ASC";
//				}
				
				$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE ";
				$query .= "replace(replace(replace(replace(super_book_title, \"(l') \" , \"\"), \"(le) \", \"\"), \"(la) \", \"\"), \"(les) \", \"\") LIKE \"%$seek%\" ";
				$query .= "AND super_book_title != \"\" ";
				$query .= "AND super_book_title IS NOT NULL ";
				$query .= "ORDER BY replace(super_book_title, '[', '') ASC";
								
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) {	
					$row[0] = preg_replace("/$seek/i","<u>$seek</u>","$row[0]");
					$row[0] = preg_replace("/\//i","\/",$row[0]);
					$array = "{\"value\":\"$row[1]\",\"label\":\"$row[0]\"}";
			        $return_arr[] = $array;
					$found = "y";
			    }
			}
			
/////////////////////////////////////////////////////////// Author search	

			if(($variation == "AUTHOR")) {
				$query = "SELECT super_book_title, super_book_code, author_name, author_type FROM manuscript_authors_search WHERE author_name LIKE \"%$seek%\" ";
				$query .= "AND author_name != \"\" AND author_name IS NOT NULL ";
//				$query .= "ORDER BY author_name ASC, super_book_title ASC ";
				$query .= "GROUP BY super_book_title";
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) { 
				//	$row[0] = preg_replace("/$seek/i","<u>$seek</u>","$row[0]");
					$row[0] = preg_replace("/\//i","\/",$row[0]);
					$array = "{\"value\":\"$row[1]\",\"label\":\"$row[2] [$row[3]] $row[0]\"}";
			        $return_arr[] = $array;
					$found = "y";
			    }
			}
			
/////////////////////////////////////////////////////////// No results?

			if(($found != "y")) {
				$array = "{\"value\":\"No Matching Titles Found!\",\"label\":\"No Matching Titles Found!\"}";
				$return_arr[] = $array;
			}

/////////////////////////////////////////////////////////// Return array		
			
			$c = count($return_arr);
			if(($c > 0)) {
				foreach($return_arr as $r) {
					$t++;
					if(($t == 1)) { echo "["; }
					echo $r;
					if(($t < $c)) { echo ","; }
					if(($t == $c)) { echo "]"; }
				}
			}
			
		}
	}

/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>