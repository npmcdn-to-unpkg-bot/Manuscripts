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
	$inputSuperBookTitle = $_GET["inputSuperBookTitle"];
	$inputEditionName = $_GET["inputEditionName"];
	$action = $_GET["action"];
	$doBook = "";
	$found = "";
	$editions = array();
	$sTitle = "";
	if(($inputEditionName == "undefined")) { $inputEditionName = ""; }
	if(($inputSuperBookTitle == "undefined")) { $inputSuperBookTitle = ""; }
	$css = "outline: none; border-color: #5567b2; box-shadow: 0 0 12px #7f8fce;";
	$time = time();
	$dateStamp = date("Y-m-d H:i:s",$time);
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Create record for new book and / or edition

	if(($action == "CREATE_BOOK") OR ($action == "CREATE_EDITION")) {
		
		$editions["book_code"] = $_GET["book_code"];
		$editions["super_book_code"] = $_GET["super_book_code"];
		$editions["edition_status"] = $_GET["edition_status"];
		$editions["edition_type"] = $_GET["edition_type"];
		$editions["full_book_title"] = $_GET["full_book_title"];
		$editions["short_book_titles"] = $_GET["short_book_titles"];
		$editions["translated_title"] = $_GET["translated_title"];
		$editions["translated_language"] = $_GET["translated_language"];
		$editions["languages"] = $_GET["languages"];
		$editions["stated_publishers"] = $_GET["stated_publishers"];
		$editions["actual_publishers"] = $_GET["actual_publishers"];
		$editions["stated_publication_places"] = $_GET["stated_publication_places"];
		$editions["actual_publication_places"] = $_GET["actual_publication_places"];
		$editions["stated_publication_years"] = $_GET["stated_publication_years"];
		$editions["actual_publication_years"] = $_GET["actual_publication_years"];
		$editions["pages"] = $_GET["pages"];
		$editions["quick_pages"] = $_GET["quick_pages"];
		$editions["number_of_volumes"] = $_GET["number_of_volumes"];
		$editions["section"] = $_GET["section"];
		$editions["edition"] = $_GET["edition"];
		$editions["book_sheets"] = $_GET["book_sheets"];
		$editions["notes"] = $_GET["notes"];
		$editions["research_notes"] = $_GET["research_notes"];
		
		$editions["author_code_1"] = $_GET["author_code_1"];
		$editions["author_name_1"] = $_GET["author_name_1"];
		$editions["author_type_1"] = $_GET["author_type_1"];
		$editions["author_code_2"] = $_GET["author_code_2"];
		$editions["author_name_2"] = $_GET["author_name_2"];
		$editions["author_type_2"] = $_GET["author_type_2"];
		$editions["author_code_3"] = $_GET["author_code_3"];
		$editions["author_name_3"] = $_GET["author_name_3"];
		$editions["author_type_3"] = $_GET["author_type_3"];
		$editions["author_code_4"] = $_GET["author_code_4"];
		$editions["author_name_4"] = $_GET["author_name_4"];
		$editions["author_type_4"] = $_GET["author_type_4"];
		
		$found = "y";
		$doneBook = "y";
		$msg = "";
		$error_code = "0";
		
///////////////////////////////// Create edition sub-routine
		
		if(($action == "CREATE_EDITION")) {
			
			if(($editions["super_book_code"] != "")) {	
				
				$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE super_book_code = \"".$editions["super_book_code"]."\" ";
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) { 
					$sTitle = $row[0];		
				}
				
				if(($sTitle == "")) {
					$msg = "Error : Empty Super Book Title. Please start over again ...";
					$error_code = "1";
				}
				
				if(($error_code != "1") && ($sTitle != "")) {
					$temp_BC = "";
					$query = "SELECT book_code FROM manuscript_books_editions ORDER BY book_code DESC LIMIT 1 ";
					$mysqli_result = mysqli_query($mysqli_link, $query);
					while($row = mysqli_fetch_row($mysqli_result)) {
						$temp_BC = $row[0];
					}
					if(($temp_BC == "")) {
						$msg = "Error : Empty Edition Book Code. Please start over again ...";
						$error_code = "1";
					}
				}
				
				if(($error_code != "1") && ($temp_BC != "")) {
					$temp_BC = preg_replace("/nbk00/i","","$temp_BC");
					$temp_BC = preg_replace("/bk00/i","","$temp_BC");
					$temp_BC++;
					$temp_BC = "nbk00".$temp_BC;
					$editions["book_code"] = $temp_BC;
					$lastID = "";
					$queryD = "INSERT INTO manuscript_books_editions VALUES (\"";
					$queryD .= $editions["book_code"]."\", \"";
					$queryD .= $editions["super_book_code"]."\", \"";
					$queryD .= $editions["edition_status"]."\", \"";
					$queryD .= $editions["edition_type"]."\", \"";
					$queryD .= $editions["full_book_title"]."\", \"";
					$queryD .= $editions["short_book_titles"]."\", \"";
					$queryD .= $editions["translated_title"]."\", \"";
					$queryD .= $editions["translated_language"]."\", \"";
					$queryD .= $editions["languages"]."\", \"";
					$queryD .= $editions["stated_publishers"]."\", \"";
					$queryD .= $editions["actual_publishers"]."\", \"";
					$queryD .= $editions["stated_publication_places"]."\", \"";
					$queryD .= $editions["actual_publication_places"]."\", \"";
					$queryD .= $editions["stated_publication_years"]."\", \"";
					$queryD .= $editions["actual_publication_years"]."\", \"";
					$queryD .= $editions["pages"]."\", \"";
					$queryD .= $editions["quick_pages"]."\", \"";
					$queryD .= $editions["number_of_volumes"]."\", \"";
					$queryD .= $editions["section"]."\", \"";
					$queryD .= $editions["edition"]."\", \"";
					$queryD .= $editions["book_sheets"]."\", \"";
					$queryD .= $editions["notes"]."\", \"";
					$queryD .= $editions["research_notes"]."\")";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);	
					$queryE = "SELECT LAST_INSERT_ID()";
					$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
					while($rowE = mysqli_fetch_row($mysqli_resultE)) {
						$lastID = $rowE[0];
					}
					
					if(($lastID == "")) {
						$msg = "Error : Cannot Create Edition Record. Please start over again ...";
						$error_code = "1";
					} else {
						
						$msg = "New Edition Record Created ...";
						$error_code = "0";
						
///////////////// Create new authors if no author_code						
						
						$temp_AC1 = "";
						$temp_AC2 = "";
						$temp_AC3 = "";
						$temp_AC4 = "";
						$author1 = "";
						$author2 = "";
						$author3 = "";
						$author4 = "";					
						
						if(($editions["author_code_1"] == "") && ($editions["author_name_1"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC1 = $row[0];
							}
							if(($temp_AC1 == "")) {
								$msg = "Error : Creating First Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC1 = preg_replace("/nau00/i","","$temp_AC1");
								$temp_AC1 = preg_replace("/au00/i","","$temp_AC1");
								$temp_AC1++;
								$temp_AC1 = "nau00".$temp_AC1;
								$editions["author_code_1"] = $temp_AC1;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_1"]."\",\"".$editions["author_name_1"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create First Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_2"] == "") && ($editions["author_name_2"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC2 = $row[0];
							}
							if(($temp_AC2 == "")) {
								$msg = "Error : Creating Second Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC2 = preg_replace("/nau00/i","","$temp_AC2");
								$temp_AC2 = preg_replace("/au00/i","","$temp_AC2");
								$temp_AC2++;
								$temp_AC2 = "nau00".$temp_AC2;
								$editions["author_code_2"] = $temp_AC2;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_2"]."\",\"".$editions["author_name_2"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Second Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_3"] == "") && ($editions["author_name_3"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC3 = $row[0];
							}
							if(($temp_AC3 == "")) {
								$msg = "Error : Creating Third Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC3 = preg_replace("/nau00/i","","$temp_AC3");
								$temp_AC3 = preg_replace("/au00/i","","$temp_AC3");
								$temp_AC3++;
								$temp_AC3 = "nau00".$temp_AC3;
								$editions["author_code_3"] = $temp_AC3;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_3"]."\",\"".$editions["author_name_3"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Third Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_4"] == "") && ($editions["author_name_4"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC4 = $row[0];
							}
							if(($temp_AC4 == "")) {
								$msg = "Error : Creating Fourth Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC4 = preg_replace("/nau00/i","","$temp_AC4");
								$temp_AC4 = preg_replace("/au00/i","","$temp_AC4");
								$temp_AC4++;
								$temp_AC4 = "nau00".$temp_AC4;
								$editions["author_code_4"] = $temp_AC4;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_4"]."\",\"".$editions["author_name_4"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Fourth Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}							
						
///////////////// Create books_authors join records when author_code exists
						
						if(($error_code != "1")) {
							if(($editions["author_code_1"] != "") && ($editions["author_name_1"] != "") && ($editions["author_type_1"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_1"]."\",\"".$editions["author_type_1"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author1 = "y";
							}
							if(($editions["author_code_2"] != "") && ($editions["author_name_2"] != "") && ($editions["author_type_2"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_2"]."\",\"".$editions["author_type_2"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author2 = "y";
							}
							if(($editions["author_code_3"] != "") && ($editions["author_name_3"] != "") && ($editions["author_type_3"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_3"]."\",\"".$editions["author_type_3"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author3 = "y";
							}
							if(($editions["author_code_4"] != "") && ($editions["author_name_4"] != "") && ($editions["author_type_4"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_4"]."\",\"".$editions["author_type_4"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author4 = "y";
							}
						}
						
///////////////// Finish authors routines						
						
					}					
				}				
			} else {
				$msg = "Error : Empty Super Book Code. Please start over again ...";
				$error_code = "1";
			}
		}
		
///////////////////////////////// Create book and edition sub-routine

		if(($action == "CREATE_BOOK")) {

			$temp_SBC = "";
			$query = "SELECT super_book_code FROM manuscript_books ORDER BY super_book_code DESC LIMIT 1 ";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) {
				$temp_SBC = $row[0];
			}
			
			if(($temp_SBC == "")) {
				$msg = "Error : Empty Super Book Code. Please start over again ...";
				$error_code = "1";
			}
			
			if(($error_code != "1") && ($temp_SBC != "")) {
				$temp_SBC = preg_replace("/zspbk00/i","","$temp_SBC");
				$temp_SBC = preg_replace("/spbk00/i","","$temp_SBC");
				$temp_SBC++;
				$temp_SBC = "zspbk00".$temp_SBC;
				$editions["super_book_code"] = $temp_SBC;
				$lastSID = "";
				$queryD = "INSERT INTO manuscript_books VALUES (\"";
				$queryD .= $editions["super_book_code"]."\", \"";
				$queryD .= $editions["full_book_title"]."\", ";
				$queryD .= "\"\", ";
				$queryD .= "\"\", ";
				$queryD .= "\"\")";
				$mysqli_resultD = mysqli_query($mysqli_link, $queryD);		
				$queryE = "SELECT LAST_INSERT_ID()";
				$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
				while($rowE = mysqli_fetch_row($mysqli_resultE)) {
					$lastSID = $rowE[0];
				}
				if(($lastSID == "")) {
					$msg = "Error : Cannot Create Book Record. Please start over again ...";
					$error_code = "1";
				}
			}

			if(($error_code != "1") && ($editions["super_book_code"] != "")) {
				$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE super_book_code = \"".$editions["super_book_code"]."\" ";
				$mysqli_result = mysqli_query($mysqli_link, $query);
				while($row = mysqli_fetch_row($mysqli_result)) { 
					$sTitle = $row[0];		
				}
				
				if(($sTitle == "")) {
					$msg = "Error : Empty Super Book Title. Please start over again ...";
					$error_code = "1";
				}
			
				if(($error_code != "1") && ($sTitle != "")) {
					$temp_BC = "";
					$query = "SELECT book_code FROM manuscript_books_editions ORDER BY book_code DESC LIMIT 1 ";
					$mysqli_result = mysqli_query($mysqli_link, $query);
					while($row = mysqli_fetch_row($mysqli_result)) {
						$temp_BC = $row[0];
					}
					if(($temp_BC == "")) {
						$msg = "Error : Empty Edition Book Code. Please start over again ...";
						$error_code = "1";
					}
				}
				
				if(($error_code != "1") && ($temp_BC != "")) {
					$temp_BC = preg_replace("/nbk00/i","","$temp_BC");
					$temp_BC = preg_replace("/bk00/i","","$temp_BC");
					$temp_BC++;
					$temp_BC = "nbk00".$temp_BC;
					$editions["book_code"] = $temp_BC;
					$lastID = "";
					$queryD = "INSERT INTO manuscript_books_editions VALUES (\"";
					$queryD .= $editions["book_code"]."\", \"";
					$queryD .= $editions["super_book_code"]."\", \"";
					$queryD .= $editions["edition_status"]."\", \"";
					$queryD .= $editions["edition_type"]."\", \"";
					$queryD .= $editions["full_book_title"]."\", \"";
					$queryD .= $editions["short_book_titles"]."\", \"";
					$queryD .= $editions["translated_title"]."\", \"";
					$queryD .= $editions["translated_language"]."\", \"";
					$queryD .= $editions["languages"]."\", \"";
					$queryD .= $editions["stated_publishers"]."\", \"";
					$queryD .= $editions["actual_publishers"]."\", \"";
					$queryD .= $editions["stated_publication_places"]."\", \"";
					$queryD .= $editions["actual_publication_places"]."\", \"";
					$queryD .= $editions["stated_publication_years"]."\", \"";
					$queryD .= $editions["actual_publication_years"]."\", \"";
					$queryD .= $editions["pages"]."\", \"";
					$queryD .= $editions["quick_pages"]."\", \"";
					$queryD .= $editions["number_of_volumes"]."\", \"";
					$queryD .= $editions["section"]."\", \"";
					$queryD .= $editions["edition"]."\", \"";
					$queryD .= $editions["book_sheets"]."\", \"";
					$queryD .= $editions["notes"]."\", \"";
					$queryD .= $editions["research_notes"]."\")";
					$mysqli_resultD = mysqli_query($mysqli_link, $queryD);	
					$queryE = "SELECT LAST_INSERT_ID()";
					$mysqli_resultE = mysqli_query($mysqli_link, $queryE);
					while($rowE = mysqli_fetch_row($mysqli_resultE)) {
						$lastID = $rowE[0];
					}
					
					if(($lastID == "")) {
						$msg = "Error : Cannot Create Edition Record. Please start over again ...";
						$error_code = "1";
					} else {
						
						$msg = "New Book and Edition Records Created ...";
						$error_code = "0";
						
						
						
///////////////// Create new authors if no author_code						
						
						$temp_AC1 = "";
						$temp_AC2 = "";
						$temp_AC3 = "";
						$temp_AC4 = "";
						$author1 = "";
						$author2 = "";
						$author3 = "";
						$author4 = "";
						
						if(($editions["author_code_1"] == "") && ($editions["author_name_1"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC1 = $row[0];
							}
							if(($temp_AC1 == "")) {
								$msg = "Error : Creating First Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC1 = preg_replace("/nau00/i","","$temp_AC1");
								$temp_AC1 = preg_replace("/au00/i","","$temp_AC1");
								$temp_AC1++;
								$temp_AC1 = "nau00".$temp_AC1;
								$editions["author_code_1"] = $temp_AC1;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_1"]."\",\"".$editions["author_name_1"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create First Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_2"] == "") && ($editions["author_name_2"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC2 = $row[0];
							}
							if(($temp_AC2 == "")) {
								$msg = "Error : Creating Second Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC2 = preg_replace("/nau00/i","","$temp_AC2");
								$temp_AC2 = preg_replace("/au00/i","","$temp_AC2");
								$temp_AC2++;
								$temp_AC2 = "nau00".$temp_AC2;
								$editions["author_code_2"] = $temp_AC2;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_2"]."\",\"".$editions["author_name_2"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Second Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_3"] == "") && ($editions["author_name_3"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC3 = $row[0];
							}
							if(($temp_AC3 == "")) {
								$msg = "Error : Creating Third Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC3 = preg_replace("/nau00/i","","$temp_AC3");
								$temp_AC3 = preg_replace("/au00/i","","$temp_AC3");
								$temp_AC3++;
								$temp_AC3 = "nau00".$temp_AC3;
								$editions["author_code_3"] = $temp_AC3;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_3"]."\",\"".$editions["author_name_3"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Third Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}
						
						if(($editions["author_code_4"] == "") && ($editions["author_name_4"] != "") && ($editions["book_code"] != "") && ($error_code != "1")) {
							$query = "SELECT author_code FROM manuscript_authors ORDER BY author_code DESC LIMIT 1 ";
							$mysqli_result = mysqli_query($mysqli_link, $query);
							while($row = mysqli_fetch_row($mysqli_result)) {
								$temp_AC4 = $row[0];
							}
							if(($temp_AC4 == "")) {
								$msg = "Error : Creating Fourth Author Code. Please start over again ...";
								$error_code = "1";
							} else {
								$alastID = "";
								$temp_AC4 = preg_replace("/nau00/i","","$temp_AC4");
								$temp_AC4 = preg_replace("/au00/i","","$temp_AC4");
								$temp_AC4++;
								$temp_AC4 = "nau00".$temp_AC4;
								$editions["author_code_4"] = $temp_AC4;
								$queryA = "INSERT INTO manuscript_authors VALUES (\"".$editions["author_code_4"]."\",\"".$editions["author_name_4"]."\")";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);	
								$queryA = "SELECT LAST_INSERT_ID()";
								$mysqli_resultA = mysqli_query($mysqli_link, $queryA);
								while($rowA = mysqli_fetch_row($mysqli_resultA)) {
									$alastID = $rowA[0];
								}
								if(($alastID == "")) {
									$msg = "Error : Cannot Create Fourth Author Code. Please start over again ...";
									$error_code = "1";
								}
							}
						}							
						
///////////////// Create books_authors join records when author_code exists
						
						if(($error_code != "1")) {
							if(($editions["author_code_1"] != "") && ($editions["author_name_1"] != "") && ($editions["author_type_1"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_1"]."\",\"".$editions["author_type_1"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author1 = "y";
							}
							if(($editions["author_code_2"] != "") && ($editions["author_name_2"] != "") && ($editions["author_type_2"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_2"]."\",\"".$editions["author_type_2"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author2 = "y";
							}
							if(($editions["author_code_3"] != "") && ($editions["author_name_3"] != "") && ($editions["author_type_3"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_3"]."\",\"".$editions["author_type_3"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author3 = "y";
							}
							if(($editions["author_code_4"] != "") && ($editions["author_name_4"] != "") && ($editions["author_type_4"] != "") && ($editions["book_code"] != "")) {
								$queryH = "INSERT INTO manuscript_books_authors VALUES (\"".$editions["book_code"]."\",\"".$editions["author_code_4"]."\",\"".$editions["author_type_4"]."\",\"2\")";
								$mysqli_resultH = mysqli_query($mysqli_link, $queryH);
								$author4 = "y";
							}
						}
				
///////////////// Finish authors routines		
							
					}
				}
			}
			
			if(($error_code != "1") && ($editions["super_book_code"] == "")) {
				$msg = "Error : Empty Super Book Code. Please start over again ...";
				$error_code = "1";
			}
		}

///////////////////////////////// Finish data activities
		
	}
	
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// Pre-check if superbook record exists	
	
	if(($inputSuperBookTitle != "") && ($action == "ADD")) {
		$query = "SELECT super_book_title, super_book_code FROM manuscript_books WHERE super_book_code = \"$inputSuperBookTitle\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$sTitle = $row[0];		
		}
		if(($sTitle == "")) {
			$doBook = "y";
			$inputSuperBookTitle = "";
		}
	} else {
		$doBook = "y";
	}	
	
	if(($inputEditionName !="") && ($doBook != "y") && ($action == "ADD")) {
		$query = "SELECT * FROM manuscript_books_editions WHERE super_book_code = \"$inputSuperBookTitle\" AND book_code = \"$inputEditionName\" ";
		$mysqli_result = mysqli_query($mysqli_link, $query);
		while($row = mysqli_fetch_row($mysqli_result)) { 
			$editions["book_code"] = $row[0];
			$editions["super_book_code"] = $row[1];
			$editions["edition_status"] = $row[2];
			$editions["edition_type"] = $row[3];
			$editions["full_book_title"] = $row[4];
			$editions["short_book_titles"] = $row[5];
			$editions["translated_title"] = $row[6];
			$editions["translated_language"] = $row[7];
			$editions["languages"] = $row[8];
			$editions["stated_publishers"] = $row[9];
			$editions["actual_publishers"] = $row[10];
			$editions["stated_publication_places"] = $row[11];
			$editions["actual_publication_places"] = $row[12];
			$editions["stated_publication_years"] = $row[13];
			$editions["actual_publication_years"] = $row[14];
			$editions["pages"] = $row[15];
			$editions["quick_pages"] = $row[16];
			$editions["number_of_volumes"] = $row[17];
			$editions["section"] = $row[18];
			$editions["edition"] = $row[19];
			$editions["book_sheets"] = $row[20];
			$editions["notes"] = $row[21];
			$editions["research_notes"] = $row[22];
			$found = "y";
		}
		$author_codes = array();
		if(($editions["book_code"] != "")) {
			$query = "SELECT author_code, author_type FROM manuscript_books_authors WHERE book_code = \"".$editions["book_code"]."\" ORDER BY author_type ASC";
			$mysqli_result = mysqli_query($mysqli_link, $query);
			while($row = mysqli_fetch_row($mysqli_result)) { 
				$author_codes[] = "$row[0]|$row[1]";
			}
			$aC = count($author_codes);
			if(($aC > 0)) {
				$authors = array();
				foreach($author_codes as $code) {
					if(($code != "")) {
						$codes = explode("|","$code");
						$query = "SELECT author_name FROM manuscript_authors WHERE author_code = \"$codes[0]\" ";
						$mysqli_result = mysqli_query($mysqli_link, $query);
						while($row = mysqli_fetch_row($mysqli_result)) {
							$authors[] = "$codes[0]|$row[0]|$codes[1]";
						}
					}
				}
			}
		}
	} else {
		$doBook = "y";
	}
	
	if(($found != "y")) {
		$doBook = "y";
	}
	
	if(($inputSuperBookTitle != $editions["super_book_code"])) {
		$doBook = "y";
	}
	
	if(($doBook == "y")) {
		$css = "";
	}
	
/////////////////////////////////////////////////////////// Get stated_publishers autocomplete array	
//	
//	$stated_publishers = array();
//	$queryP = "SELECT DISTINCT(stated_publishers) FROM manuscript_books_editions WHERE stated_publishers != \"\" ORDER BY stated_publishers ASC";
//	$mysqli_result = mysqli_query($mysqli_link, $queryP);
//	while($rowP = mysqli_fetch_row($mysqli_result)) { 
//		$stated_publishers[] = $rowP[0];	
//	}
//	
/////////////////////////////////////////////////////////// Get author_type autocomplete array	
	
	$author_type = array();
	$queryP = "SELECT DISTINCT(author_type) FROM manuscript_books_authors WHERE author_type != \"\" ORDER BY author_type ASC";
	$mysqli_result = mysqli_query($mysqli_link, $queryP);
	while($rowP = mysqli_fetch_row($mysqli_result)) { 
		$author_type[] = $rowP[0];	
	}
	
/////////////////////////////////////////////////////////// Get edition_status autocomplete array
	
	$edition_status = array();
	$queryP = "SELECT DISTINCT(edition_status) FROM manuscript_books_editions WHERE edition_status != \"\" AND edition_status != \"Unreviewed\" ORDER BY edition_status ASC";
	$mysqli_result = mysqli_query($mysqli_link, $queryP);
	while($rowP = mysqli_fetch_row($mysqli_result)) { 
		$edition_status[] = $rowP[0];	
	}
	
/////////////////////////////////////////////////////////// Get edition autocomplete array

	$edition = array();
	$edition[] = "in-folio";
	$edition[] = "in-4";
	$edition[] = "in-8";
	$edition[] = "in-12";
	$edition[] = "in-16";
	$edition[] = "in-18";
	$edition[] = "in-20";
	$edition[] = "in-24";
	$edition[] = "in-32";
	$edition[] = "small format";
	$edition[] = "other (see note)";
	$edition[] = "unknown";
	
/////////////////////////////////////////////////////////// Get languages autocomplete array
	
	if(($action == "ADD")) {
		$languages = array();
		$queryP = "SELECT DISTINCT(languages) FROM manuscript_books_editions ";
		$queryP .= "WHERE languages != \"\" AND languages NOT LIKE \"%;%\" AND languages NOT LIKE \"%Bilingual%\" AND languages NOT LIKE \"%Hebrew%\" AND languages NOT LIKE \"%English, French, German, Italian, Latin%\" ORDER BY languages ASC";
		$mysqli_result = mysqli_query($mysqli_link, $queryP);
		while($rowP = mysqli_fetch_row($mysqli_result)) { 
			$languages[] = $rowP[0];	
		}
		$languages[] = "Dutch";
		$languages[] = "French, English";
		$languages[] = "French, Greek";
		$languages[] = "French, Hebrew";
		$languages[] = "German, English";
		$languages[] = "Greek, Hebrew";
		$languages[] = "Hebrew";
		$languages[] = "Other";
		sort($languages);
	}
	
/////////////////////////////////////////////////////////// Get edition_type autocomplete array
	
	$edition_type = array();
	$queryP = "SELECT DISTINCT(edition_type) FROM manuscript_books_editions WHERE edition_type != \"\" AND edition_type != \"French Edition\" ORDER BY edition_type ASC";
	$mysqli_result = mysqli_query($mysqli_link, $queryP);
	while($rowP = mysqli_fetch_row($mysqli_result)) { 
		$edition_type[] = $rowP[0];	
	}
	
/////////////////////////////////////////////////////////// Start page

?>
	<div class="page-header">
		<h3>
		<?php 
			if(($doBook == "y") && ($doneBook != "y") && ($action == "ADD")) { 
				echo "Add New Book / Edition"; 
			}
			if(($doBook != "y") && ($doneBook != "y") && ($action == "ADD")) {
				echo "Add New Edition";
			} 
			if(($doneBook == "y") && (($action == "CREATE_EDITION") OR ($action == "CREATE_BOOK"))) {
				echo "Create Record";	
			}
		?>
		</h3>
	</div>
<?php

	if(($inputSuperBookTitle != "") && ($action == "ADD")) {
		echo "<div style=\"background-color: #2b7c93; color: #ffffff; padding: 20px; font-size: 1.3em; ";
		echo "border-left: 1px solid #1a4a58; border-right: 1px solid #1a4a58; border-top: 1px solid #1a4a58; border-bottom: 0px solid #1a4a58;\">";
		echo "$sTitle<br />[Master Record # $inputSuperBookTitle]<br /><span style=\"font-size: 0.8em; color: #72b3c5;\">$dateStamp</span>";
		echo "</div>";
	}
	
	if(($action == "CREATE_BOOK") OR ($action == "CREATE_EDITION")) {
		if(($error_code == "0")) {
			echo "<div style=\"background-color: #2faf56; color: #ffffff; padding: 20px; font-size: 1.3em; ";
			echo "border-left: 1px solid #1a4a58; border-right: 1px solid #1a4a58; border-top: 1px solid #1a4a58; border-bottom: 0px solid #1a4a58;\">";
			echo "$msg<br /><span style=\"font-size: 0.8em; color: #7ad796;\">$dateStamp</span>";
			echo "</div>";
		}
		if(($error_code == "1")) {
			echo "<div style=\"background-color: #ec573f; color: #ffffff; padding: 20px; font-size: 1.3em; ";
			echo "border-left: 1px solid #1a4a58; border-right: 1px solid #1a4a58; border-top: 1px solid #1a4a58; border-bottom: 0px solid #1a4a58;\">";
			echo "$msg<br /><span style=\"font-size: 0.8em; color: #ff9f90;\">$dateStamp</span>";
			echo "</div>";
		}
	}

?>
	<div style="padding-left: 30px; padding-right: 30px; padding-bottom: 15px; padding-top: 30px; background-color: <?php
    		if(($doneBook == "y")) {
				echo "#d5d5d5";
			} else {
				echo "#f0f0f0";	
			}
		?>; border: 1px solid #1a4a58;">
		<form class="form-horizontal" role="form" id="Edition_Form" name="Edition_Form">
        	<?php if(($doneBook == "y")) { ?>
            <div class="form-group">
            	<div class="col-sm-3"><label class="control-label" style="color: #126b84;">SUPER BOOK CODE</label></div>
                <div class="col-sm-3"><input type="text" class="form-control" value="<?php echo $editions["super_book_code"]; ?>" id="tmp_super_book_code" name="tmp_super_book_code" disabled ></div>
                <div class="col-sm-2"><label class="control-label" style="color: #126b84;">BOOK CODE</label></div>
                <div class="col-sm-4"><input type="text" class="form-control" value="<?php echo $editions["book_code"]; ?>" id="tmp_book_code" name="tmp_book_code" disabled ></div>
            </div>
            <?php } ?>
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;"><?php
                	if(($doBook != "y") && ($action == "ADD")) {
						echo "Full Edition Title";
					} 
					if(($doBook == "y") && ($action == "ADD")) {
						echo "Full Book / Edition Title";
					}
					if(($action == "CREATE_EDITION")) {
						echo "Full Edition Title";
					}
					if(($action == "CREATE_BOOK")) {
						echo "Full Book / Edition Title";
					}
				?></label></div>
				<div class="col-sm-9">
					<input type="hidden" value="<?php echo $editions["book_code"]; ?>" id="ed_book_code" name="ed_book_code">
					<input type="hidden" value="<?php echo $inputSuperBookTitle; ?>" id="ed_super_book_code" name="ed_super_book_code">
                    <textarea rows="4" type="text" class="form-control"  id="ed_full_book_title" name="ed_full_book_title" placeholder="<?php
                		if(($doBook != "y") && ($action == "ADD")) {
							echo "Full Edition Title";
						} 
						if(($doBook == "y") && ($action == "ADD")) {
							echo "Full Book / Edition Title";
						}
						if(($action == "CREATE_EDITION") OR ($action == "CREATE_BOOK")) {
							echo "";	
						}
					?> ..." <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> ><?php 
						echo $editions["full_book_title"]; 
						if(($editions["full_book_title"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?></textarea>
                    <!--
					<input type="text" class="form-control" value="<?php 
						echo $editions["full_book_title"]; 
						if(($editions["full_book_title"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?>" id="ed_full_book_title" name="ed_full_book_title" placeholder="<?php
                		if(($doBook != "y") && ($action == "ADD")) {
							echo "Full Edition Title";
						} 
						if(($doBook == "y") && ($action == "ADD")) {
							echo "Full Book / Edition Title";
						}
						if(($action == "CREATE_EDITION") OR ($action == "CREATE_BOOK")) {
							echo "";	
						}
					?> ..." <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                   //-->
				</div>
			</div>
<?php

/////////////////////////////////////////////////////////// Author routine begin

	$aus = 0;
	for($in=1;$in<5;$in++) {
		
		$writers = explode("|","$authors[$aus]");
		$aus++;
		$field1 = "author_name_".$aus;
		$field2 = "author_type_".$aus;
		if(($editions["$field1"] == "") && ($editions["$field2"] == "")) {
			$editions["author_code"] = $writers[0];
			$editions["author_name"] = $writers[1];
			$editions["author_type"] = $writers[2];
		} else {
			$editions["author_name"] = $editions["$field1"];
			$editions["author_type"] = $editions["$field2"];
		}
?>                     
            <div class="form-group" style="background-color: #e2e2e2; padding-top: 15px; padding-bottom: 15px;">
            	<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Author(s)</label></div>
                <div class="col-sm-5">
                	<input type="hidden" value="<?php echo $editions["author_code"]; ?>" id="ed_author_code_<?php echo $in; ?>" name="ed_author_code_<?php echo $in; ?>">
                	<input type="text" class="form-control" value="<?php 
						echo $editions["author_name"]; 
						if(($editions["author_name"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?>" id="ed_author_name_<?php echo $in; ?>" name="ed_author_name_<?php echo $in; ?>" placeholder="<?php if(($doneBook != "y")) { echo "Author Name ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                </div>
                <div class="col-sm-4">
                	<select id="ed_author_type_<?php echo $in; ?>" name="ed_author_type_<?php echo $in; ?>" class="form-control" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    <?php
						$b = 0;
						$selectES = "";
						foreach($author_type as $es) {
							if(($editions["author_type"] == "")) {
								if(($in == 1) && ($b == 1)) {
									$selectES = "selected";
								} elseif(($in == 2) && ($b == 2)) {
									$selectES = "selected";
								} elseif(($in == 3) && ($b == 3)) {
									$selectES = "selected";
								} elseif(($in == 4) && ($b == 4)) {
									$selectES = "selected";
								} else {
									$selectES = "";
								}
							}
							if(($editions["author_type"] != "")) {
								if(($editions["author_type"] == $es)) { 
									$selectES = "selected"; 
								} else { 
									$selectES = ""; 
								} 
							} 
							echo "<option value=\"$es\" $selectES >$es</option>";
							$b++;
						}
					?>
                    </select>
                </div>
            </div>
<?php

	}

?>
            <div class="form-group">
            	<div class="col-sm-12"><div id="show_results" style="-webkit-column-width: 250px; -moz-column-width: 250px; column-width: 250px; padding-bottom: 5px;"></div></div>
            </div>
<?php

/////////////////////////////////////////////////////////// Author routine end

?> 
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Edition Status</label></div>
				<div class="col-sm-3">
                	<select id="ed_edition_status" name="ed_edition_status" class="form-control" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    <?php
						$b = 0;
						$selectES = "";
						foreach($edition_status as $es) {
							if(($editions["edition_status"] == "")) {
								if(($b == 0)) {
									$selectES = "selected";
								} else {
									$selectES = "";
								}
							}
							if(($editions["edition_status"] != "")) {
								if(($editions["edition_status"] == $es)) { 
									$selectES = "selected"; 
								} else { 
									$selectES = ""; 
								} 
							} 
							echo "<option value=\"$es\" $selectES >$es</option>";
							$b++;
						}
					?>
                    </select>
					<!--
                    	<input type="text" class="form-control" value="<?php 
						echo $editions["edition_status"]; 
						if(($editions["edition_status"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?>" id="ed_edition_status" name="ed_edition_status" placeholder="<?php if(($doneBook != "y")) { echo "Edition Status ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    //-->
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Ed. Type</label></div>
				<div class="col-sm-4">
                	<select id="ed_edition_type" name="ed_edition_type" class="form-control" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    <?php
						$b = 0;
						$selectES = "";
						foreach($edition_type as $es) {
							if(($editions["edition_type"] == "")) {
								if(($b == 5)) {
									$selectES = "selected";
								} else {
									$selectES = "";
								}
							}
							if(($editions["edition_type"] != "")) {
								if(($editions["edition_type"] == $es)) { 
									$selectES = "selected"; 
								} else { 
									$selectES = ""; 
								} 
							}
							echo "<option value=\"$es\" $selectES >$es</option>";
							$b++;
						}
					?>
                    </select>                
                	<!--
						<input type="text" class="form-control" value="<?php 
							echo $editions["edition_type"]; 
							if(($editions["edition_type"] == "") && ($doneBook != "y")) { $cssa = $css; } 
						?>" id="ed_edition_type" name="ed_edition_type" placeholder="<?php if(($doneBook != "y")) { echo "Edition Type ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
							if(($doneBook == "y")) { echo "disabled"; } 
						?> >
                    //-->
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Short Title[s]</label></div>
				<div class="col-sm-9">
					<input type="text" class="form-control" value="<?php 
						echo $editions["short_book_titles"]; 
						if(($editions["short_book_titles"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?>" id="ed_short_book_titles" name="ed_short_book_titles" placeholder="<?php if(($doneBook != "y")) { echo "Short Book Titles ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Language[s]</label></div>
				<div class="col-sm-9">
                	<select id="ed_languages" name="ed_languages" class="form-control" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    <?php
						$b = 0;
						$selectES = "";
						foreach($languages as $es) {
							if(($editions["languages"] == "")) {
								if(($b == 4)) {
									$selectES = "selected";
								} else {
									$selectES = "";
								}
							}
							if(($editions["languages"] != "")) {
								if(($editions["languages"] == $es)) { 
									$selectES = "selected"; 
								} else { 
									$selectES = ""; 
								} 
							}
							echo "<option value=\"$es\" $selectES >$es</option>";
							$b++;
						}
					?>
                    </select> 
                	<!--
						<input type="text" class="form-control" value="<?php 
							echo $editions["languages"]; 
							if(($editions["languages"] == "") && ($doneBook != "y")) { $cssa = $css; } 
						?>" id="ed_languages" name="ed_languages" placeholder="<?php if(($doneBook != "y")) { echo "Languages ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
							if(($doneBook == "y")) { echo "disabled"; } 
						?> >
                    //-->
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Translation</label></div>
				<div class="col-sm-5">
					<input type="text" class="form-control" value="<?php 
						echo $editions["translated_title"]; 
						if(($editions["translated_title"] == "") && ($doneBook != "y")) { $cssa = $css; } 
					?>" id="ed_translated_title" name="ed_translated_title" placeholder="<?php if(($doneBook != "y")) { echo "Translated Title ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["translated_language"]; 
						if(($editions["translated_language"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_translated_language" name="ed_translated_language" placeholder="<?php if(($doneBook != "y")) { echo "Language ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>
			<div class="form-group" style="background-color: #e2e2e2; padding-top: 15px; padding-bottom: 15px;">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Stated Place</label></div>
				<div class="col-sm-3">
					<input type="text" class="form-control" value="<?php 
						echo $editions["stated_publication_places"]; 
						if(($editions["stated_publication_places"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_stated_publication_places" name="ed_stated_publication_places" placeholder="<?php if(($doneBook != "y")) { echo "Place ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Actual Pl.</label></div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["actual_publication_places"]; 
						if(($editions["actual_publication_places"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_actual_publication_places" name="ed_actual_publication_places" placeholder="<?php if(($doneBook != "y")) { echo "Confirmed Place ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>
            <div class="form-group">
            	<div class="col-sm-12"><div id="show_results_places" style="-webkit-column-width: 250px; -moz-column-width: 250px; column-width: 250px; padding-bottom: 5px;"></div></div>
            </div>
			<div class="form-group" style="background-color: #e2e2e2; padding-top: 15px; padding-bottom: 15px;">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Stated Publishers</label></div>
				<div class="col-sm-3">
					<input type="text" class="form-control" value="<?php 
						echo $editions["stated_publishers"]; 
						if(($editions["stated_publishers"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_stated_publishers" name="ed_stated_publishers" placeholder="<?php if(($doneBook != "y")) { echo "Publishers ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Actual Pb.</label></div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["actual_publishers"]; 
						if(($editions["actual_publishers"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_actual_publishers" name="ed_actual_publishers" placeholder="<?php if(($doneBook != "y")) { echo "Confirmed Publisher ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>
            <div class="form-group">
            	<div class="col-sm-12"><div id="show_results_publishers" style="-webkit-column-width: 250px; -moz-column-width: 250px; column-width: 250px; padding-bottom: 5px;"></div></div>
            </div>
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Stated Year</label></div>
				<div class="col-sm-3">
					<input type="text" class="form-control" value="<?php 
						echo $editions["stated_publication_years"]; 
						if(($editions["stated_publication_years"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_stated_publication_years" name="ed_stated_publication_years" placeholder="<?php if(($doneBook != "y")) { echo "Year ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Actual Yr.</label></div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["actual_publication_years"]; 
						if(($editions["actual_publication_years"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_actual_publication_years" name="ed_actual_publication_years" placeholder="<?php if(($doneBook != "y")) { echo "Confirmed Year ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Pages</label></div>
				<div class="col-sm-5">
					<input type="text" class="form-control" value="<?php 
						echo $editions["pages"]; 
						if(($editions["pages"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_pages" name="ed_pages" placeholder="<?php if(($doneBook != "y")) { echo "Pages ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["quick_pages"]; 
						if(($editions["quick_pages"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_quick_pages" name="ed_quick_pages" placeholder="<?php if(($doneBook != "y")) { echo "Quick Pages ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>			
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">No. Volumes</label></div>
				<div class="col-sm-3">
					<input type="text" class="form-control" value="<?php 
						echo $editions["number_of_volumes"]; 
						if(($editions["number_of_volumes"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_number_of_volumes" name="ed_number_of_volumes" placeholder="<?php if(($doneBook != "y")) { echo "Volumes ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Section</label></div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["section"]; 
						if(($editions["section"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_section" name="ed_section" placeholder="<?php if(($doneBook != "y")) { echo "Section ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Format</label></div>
				<div class="col-sm-3">
                	<select id="ed_edition" name="ed_edition" class="form-control" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
                    <?php
						$b = 0;
						$selectES = "";
						foreach($edition as $es) {
							if(($editions["edition"] == "")) {
								if(($b == 11)) {
									$selectES = "selected";
								} else {
									$selectES = "";
								}
							}
							if(($editions["edition"] != "")) {
								if(($editions["edition"] == $es)) { 
									$selectES = "selected"; 
								} else { 
									$selectES = ""; 
								} 
							} 
							echo "<option value=\"$es\" $selectES >$es</option>";
							$b++;
						}
					?>
                    </select>
                	<!--
						<input type="text" class="form-control" value="<?php 
							echo $editions["edition"]; 
							if(($editions["edition"] == "") && ($doneBook != "y")) { $cssa = $css; }
						?>" id="ed_edition" name="ed_edition" placeholder="<?php if(($doneBook != "y")) { echo "Edition ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
							if(($doneBook == "y")) { echo "disabled"; } 
						?> >
                    //-->
				</div>
				<div class="col-sm-2"><label class="control-label" style="color: #126b84;">Bk. Sheets</label></div>
				<div class="col-sm-4">
					<input type="text" class="form-control" value="<?php 
						echo $editions["book_sheets"]; 
						if(($editions["book_sheets"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?>" id="ed_book_sheets" name="ed_book_sheets" placeholder="<?php if(($doneBook != "y")) { echo "Book Sheets ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> >
				</div>
			</div>						
			<div class="form-group">
				<div class="col-sm-3"><label class="control-label" style="color: #126b84;">Notes</label></div>
				<div class="col-sm-5">
                	<textarea rows="4" type="text" class="form-control"id="ed_notes" name="ed_notes" placeholder="<?php if(($doneBook != "y")) { echo "Notes ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> ><?php 
						echo $editions["notes"]; 
						if(($editions["notes"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?></textarea>
                    <!--
						<input type="text" class="form-control" value="<?php 
							echo $editions["notes"]; 
							if(($editions["notes"] == "") && ($doneBook != "y")) { $cssa = $css; }
						?>" id="ed_notes" name="ed_notes" placeholder="<?php if(($doneBook != "y")) { echo "Notes ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
							if(($doneBook == "y")) { echo "disabled"; } 
						?> >
                    //-->
				</div>
				<div class="col-sm-4">
                	<textarea rows="4" type="text" class="form-control" id="ed_research_notes" name="ed_research_notes" placeholder="<?php if(($doneBook != "y")) { echo "Research Notes ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> ><?php 
						echo $editions["research_notes"]; 
						if(($editions["research_notes"] == "") && ($doneBook != "y")) { $cssa = $css; }
					?></textarea>
                    <!--
						<input type="text" class="form-control" value="<?php 
							echo $editions["research_notes"]; 
							if(($editions["research_notes"] == "") && ($doneBook != "y")) { $cssa = $css; }
						?>" id="ed_research_notes" name="ed_research_notes" placeholder="<?php if(($doneBook != "y")) { echo "Research Notes ..."; } ?>" <?php echo "style=\"$cssa;\""; $cssa = ""; ?> <?php 
							if(($doneBook == "y")) { echo "disabled"; } 
						?> >
                   //-->
				</div>
			</div>
			<div class="form-group">
            	<div class="col-sm-6">&nbsp;</div>
            	<div class="col-md-6" style="float:right;">
					<button type="button" class="btn <?php
						if(($doBook != "y")) {
							echo "btn-success";
						} 
						if(($doBook == "y")) {
							echo "btn-warning";
						}
					?>" id="createEditionButton" style="width:100%;" <?php 
						if(($doneBook == "y")) { echo "disabled"; } 
					?> ><?php
						if(($doBook != "y")) {
							echo "Create Edition";
						} 
						if(($doBook == "y")) {
							echo "Create Book / Edition";
						}
					?></button>	
				</div>
            </div>	
		</form>
	</div>
	<p>&nbsp;<br />&nbsp;<br />If you are adding a new 'edition' to an existing 'book' (this is determined by having preselected -- or not preselected -- a 'book' in the left-hand panel), for convenience the details of a book's previously recorded edition will have been preloaded into the form below for editing. Please carefully check over each field and make changes where required. If, however, you are adding a new edition that does not have a 'book' already in the database then the form will be blank and you will need to enter all the required details at the 'book' and 'edition' levels. In either case, once you are finished please click on 'Create Edition' and a new record will be generated. This will then be available via the autocomplete function next time you undertake a title search in the 'book' and 'edition' fields.<br />&nbsp;<br />&nbsp;
	</p>
	<script language="javascript" type="text/javascript" >
	
		$(document).ready(function(e) {
			$("#ed_full_book_title").focus();
		});
		
		$(document).on('keyup','#ed_stated_publishers',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results_publishers").load("./data_publishers.php?inputReset=no&inputBox=ed_stated_publishers&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results_publishers").load("./data_publishers.php?inputReset=yes",function(){}); 
			}
		});
		
		$(document).on('keyup','#ed_stated_publication_places',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results_places").load("./data_places.php?inputReset=no&inputBox=ed_stated_publication_places&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results_places").load("./data_places.php?inputReset=yes",function(){}); 
			}
		});
		
		$(document).on('keyup','#ed_author_name_1',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=no&inputBox=ed_author_name_1&inputCode=ed_author_code_1&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=yes",function(){});
				var updateCode = $("#author_code_1").val(''); 
			}
		});
		
		$(document).on('keyup','#ed_author_name_2',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=no&inputBox=ed_author_name_2&inputCode=ed_author_code_2&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=yes",function(){});
				var updateCode = $("#author_code_2").val(''); 
			}
		});
		
		$(document).on('keyup','#ed_author_name_3',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=no&inputBox=ed_author_name_3&inputCode=ed_author_code_3&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=yes",function(){});
				var updateCode = $("#author_code_3").val(''); 
			}
		});
		
		$(document).on('keyup','#ed_author_name_4',function(){
			var minLength = 5;
			var value = $(this).val();
			if(value.length >= minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=no&inputBox=ed_author_name_4&inputCode=ed_author_code_4&inputFilter="+value,function(){});
			}
			if(value.length < minLength) {
				var show = $("#show_results").load("./data_authors.php?inputReset=yes",function(){});
				var updateCode = $("#author_code_4").val(''); 
			}
		});
		
		$("#createEditionButton").click(function(){
			
			var r = confirm('Are you sure you want to add the following record to the editions table?');
			if (r == true) { 
				var book_code = encodeURIComponent($("#ed_book_code").val());
				var super_book_code = encodeURIComponent($("#ed_super_book_code").val());
		//		var edition_status = encodeURIComponent($("#ed_edition_status").val());
				var edition_status = encodeURIComponent($("select[id=ed_edition_status]").val());
		//		var edition_type = encodeURIComponent($("#ed_edition_type").val());
				var edition_type = encodeURIComponent($("select[id=ed_edition_type]").val());
				var full_book_title = encodeURIComponent($("#ed_full_book_title").val());
				var short_book_titles = encodeURIComponent($("#ed_short_book_titles").val());
				var translated_title = encodeURIComponent($("#ed_translated_title").val());
				var translated_language = encodeURIComponent($("#ed_translated_language").val());
		//		var languages = encodeURIComponent($("#ed_languages").val());
				var languages = encodeURIComponent($("select[id=ed_languages]").val());
				var stated_publishers = encodeURIComponent($("#ed_stated_publishers").val());
				var actual_publishers = encodeURIComponent($("#ed_actual_publishers").val());
				var stated_publication_places = encodeURIComponent($("#ed_stated_publication_places").val());
				var actual_publication_places = encodeURIComponent($("#ed_actual_publication_places").val());
				var stated_publication_years = encodeURIComponent($("#ed_stated_publication_years").val());
				var actual_publication_years = encodeURIComponent($("#ed_actual_publication_years").val());
				var pages = encodeURIComponent($("#ed_pages").val());
				var quick_pages = encodeURIComponent($("#ed_quick_pages").val());
				var number_of_volumes = encodeURIComponent($("#ed_number_of_volumes").val());
				var section = encodeURIComponent($("#ed_section").val());
		//		var edition = encodeURIComponent($("#ed_edition").val());
				var edition = encodeURIComponent($("select[id=ed_edition]").val());
				var book_sheets = encodeURIComponent($("#ed_book_sheets").val());
				var notes = encodeURIComponent($("#ed_notes").val());
				var research_notes = encodeURIComponent($("#ed_research_notes").val());
				
				var author_code_1 = encodeURIComponent($("#ed_author_code_1").val());
				var author_name_1 = encodeURIComponent($("#ed_author_name_1").val());
				var author_type_1 = encodeURIComponent($("#ed_author_type_1").val());
				var author_code_2 = encodeURIComponent($("#ed_author_code_2").val());
				var author_name_2 = encodeURIComponent($("#ed_author_name_2").val());
				var author_type_2 = encodeURIComponent($("#ed_author_type_2").val());
				var author_code_3 = encodeURIComponent($("#ed_author_code_3").val());
				var author_name_3 = encodeURIComponent($("#ed_author_name_3").val());
				var author_type_3 = encodeURIComponent($("#ed_author_type_3").val());
				var author_code_4 = encodeURIComponent($("#ed_author_code_4").val());
				var author_name_4 = encodeURIComponent($("#ed_author_name_4").val());
				var author_type_4 = encodeURIComponent($("#ed_author_type_4").val());
				
				dataE = "book_code=" + book_code + 
						"&super_book_code=" + super_book_code + 
						"&edition_status=" + edition_status + 
						"&edition_type=" + edition_type + 
						"&full_book_title=" + full_book_title + 
						"&short_book_titles=" + short_book_titles + 
						"&translated_title=" + translated_title + 
						"&translated_language=" + translated_language + 
						"&languages=" + languages + 
						"&stated_publishers=" + stated_publishers + 
						"&actual_publishers=" + actual_publishers + 
						"&stated_publication_places=" + stated_publication_places +
						"&actual_publication_places=" + actual_publication_places + 
						"&stated_publication_years=" + stated_publication_years + 
						"&actual_publication_years=" + actual_publication_years + 
						"&pages=" + pages + 
						"&quick_pages=" + quick_pages + 
						"&number_of_volumes=" + number_of_volumes + 
						"&section=" + section +
						"&edition=" + edition +
						"&book_sheets=" + book_sheets +
						"&notes=" + notes +
						"&research_notes=" + research_notes + 
						"&author_code_1=" + author_code_1 +
						"&author_name_1=" + author_name_1 +
						"&author_type_1=" + author_type_1 +
						"&author_code_2=" + author_code_2 +
						"&author_name_2=" + author_name_2 +
						"&author_type_2=" + author_type_2 +
						"&author_code_3=" + author_code_3 +
						"&author_name_3=" + author_name_3 +
						"&author_type_3=" + author_type_3 +
						"&author_code_4=" + author_code_4 +
						"&author_name_4=" + author_name_4 +
						"&author_type_4=" + author_type_4 +
						"&action=<?php
						if(($doBook != "y")) {
							echo "CREATE_EDITION";
						} 
						if(($doBook == "y")) {
							echo "CREATE_BOOK";
						}
					?>";
				
				var enameVal = $('#manuscriptRecords').load("./data_editions.php",dataE);
				return false;
			}
		});
		
    </script>
<?php
	
/////////////////////////////////////////////////////////// Finish

	include("./admin/era.dbdisconnect.php");

?>